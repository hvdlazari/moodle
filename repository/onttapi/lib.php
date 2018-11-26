<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This plugin is used to access ontt player videos
 *
 * @since Moodle 3.5
 * @package    repository_onttapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/repository/lib.php');

/**
 * repository_youtube class
 *
 * @since Moodle 3.5
 * @package    repository_onttapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class repository_onttapi extends repository {

	// public function get_listing($path='', $page = '') {
 //        return array('list' => array());
 //    }

     /**
     * Return a upload form
     * @return array
     */
    public function get_listing($path = '', $page = '') {
        global $CFG;
        $ret = array();
        $ret['nologin']  = true;
        $ret['nosearch'] = true;
        $ret['norefresh'] = true;
        $ret['saveas'] = false;
        $ret['list'] = array();
        $ret['dynload'] = false;
        $ret['upload'] = array('label'=>get_string('attachment', 'repository'), 'id'=>'ontt-form');
        $ret['allowcaching'] = false; // indicates that result of get_listing() can be cached in filepicker.js
        return $ret;
    }

    /**
     * Process uploaded file
     * @return array|bool
     */
    public function upload($saveas_filename, $maxbytes) {
        global $CFG;

        $types = optional_param_array('accepted_types', '*', PARAM_RAW);
        $savepath = optional_param('savepath', '/', PARAM_PATH);
        $itemid   = optional_param('itemid', 0, PARAM_INT);
        $license  = optional_param('license', $CFG->sitedefaultlicense, PARAM_TEXT);
        $author   = optional_param('author', '', PARAM_TEXT);
        $areamaxbytes = optional_param('areamaxbytes', FILE_AREA_MAX_BYTES_UNLIMITED, PARAM_INT);
        $overwriteexisting = optional_param('overwrite', false, PARAM_BOOL);

        return $this->process_upload($saveas_filename, $maxbytes, $types, $savepath, $itemid, $license, $author, $overwriteexisting, $areamaxbytes);
    }

    /**
     * Do the actual processing of the uploaded file
     * @param string $saveas_filename name to give to the file
     * @param int $maxbytes maximum file size
     * @param mixed $types optional array of file extensions that are allowed or '*' for all
     * @param string $savepath optional path to save the file to
     * @param int $itemid optional the ID for this item within the file area
     * @param string $license optional the license to use for this file
     * @param string $author optional the name of the author of this file
     * @param bool $overwriteexisting optional user has asked to overwrite the existing file
     * @param int $areamaxbytes maximum size of the file area.
     * @return object containing details of the file uploaded
     */
    public function process_upload($saveas_filename, $maxbytes, $types = '*', $savepath = '/', $itemid = 0,
            $license = null, $author = '', $overwriteexisting = false, $areamaxbytes = FILE_AREA_MAX_BYTES_UNLIMITED) {
        global $USER, $CFG;

        if ((is_array($types) and in_array('*', $types)) or $types == '*') {
            $this->mimetypes = '*';
        } else {
            foreach ($types as $type) {
                $this->mimetypes[] = mimeinfo('type', $type);
            }
        }

        if ($license == null) {
            $license = $CFG->sitedefaultlicense;
        }

        $record = new stdClass();
        $record->filearea = 'draft';
        $record->component = 'user';
        $record->filepath = $savepath;
        $record->itemid   = $itemid;
        $record->license  = $license;
        $record->author   = $author;

        $context = context_user::instance($USER->id);
        $elname = 'repo_upload_file';

        $fs = get_file_storage();
        $sm = get_string_manager();

        if ($record->filepath !== '/') {
            $record->filepath = file_correct_filepath($record->filepath);
        }

        if (!isset($_FILES[$elname])) {
            throw new moodle_exception('nofile');
        }
        // if (!empty($_FILES[$elname]['error'])) {
        //     switch ($_FILES[$elname]['error']) {
        //     case UPLOAD_ERR_INI_SIZE:
        //         throw new moodle_exception('upload_error_ini_size', 'repository_upload');
        //         break;
        //     case UPLOAD_ERR_FORM_SIZE:
        //         throw new moodle_exception('upload_error_form_size', 'repository_upload');
        //         break;
        //     case UPLOAD_ERR_PARTIAL:
        //         throw new moodle_exception('upload_error_partial', 'repository_upload');
        //         break;
        //     case UPLOAD_ERR_NO_FILE:
        //         throw new moodle_exception('upload_error_no_file', 'repository_upload');
        //         break;
        //     case UPLOAD_ERR_NO_TMP_DIR:
        //         throw new moodle_exception('upload_error_no_tmp_dir', 'repository_upload');
        //         break;
        //     case UPLOAD_ERR_CANT_WRITE:
        //         throw new moodle_exception('upload_error_cant_write', 'repository_upload');
        //         break;
        //     case UPLOAD_ERR_EXTENSION:
        //         throw new moodle_exception('upload_error_extension', 'repository_upload');
        //         break;
        //     default:
        //         throw new moodle_exception('nofile');
        //     }
        // }

        \core\antivirus\manager::scan_file($_FILES[$elname]['tmp_name'], $_FILES[$elname]['name'], true);

        // {@link repository::build_source_field()}
        $sourcefield = $this->get_file_source_info($_FILES[$elname]['name']);
        $record->source = self::build_source_field($sourcefield);

        if (empty($saveas_filename)) {
            $record->filename = clean_param($_FILES[$elname]['name'], PARAM_FILE);
        } else {
            $ext = '';
            $match = array();
            $filename = clean_param($_FILES[$elname]['name'], PARAM_FILE);
            if (strpos($filename, '.') === false) {
                // File has no extension at all - do not add a dot.
                $record->filename = $saveas_filename;
            } else {
                if (preg_match('/\.([a-z0-9]+)$/i', $filename, $match)) {
                    if (isset($match[1])) {
                        $ext = $match[1];
                    }
                }
                $ext = !empty($ext) ? $ext : '';
                if (preg_match('#\.(' . $ext . ')$#i', $saveas_filename)) {
                    // saveas filename contains file extension already
                    $record->filename = $saveas_filename;
                } else {
                    $record->filename = $saveas_filename . '.' . $ext;
                }
            }
        }

        // Check the file has some non-null contents - usually an indication that a user has
        // tried to upload a folder by mistake
        // if (!$this->check_valid_contents($_FILES[$elname]['tmp_name'])) {
        //     throw new moodle_exception('upload_error_invalid_file', 'repository_upload', '', $record->filename);
        // }

        if ($this->mimetypes != '*') {
            // check filetype
            $filemimetype = file_storage::mimetype($_FILES[$elname]['tmp_name'], $record->filename);
            if (!in_array($filemimetype, $this->mimetypes)) {
                throw new moodle_exception('invalidfiletype', 'repository', '', get_mimetype_description(array('filename' => $_FILES[$elname]['name'])));
            }
        }

        // if (empty($record->itemid)) {
        //     $record->itemid = 0;
        // }

        if (($maxbytes!==-1) && (filesize($_FILES[$elname]['tmp_name']) > $maxbytes)) {
            $maxbytesdisplay = display_size($maxbytes);
            throw new file_exception('maxbytesfile', (object) array('file' => $record->filename,
                                                                    'size' => $maxbytesdisplay));
        }

        // if (file_is_draft_area_limit_reached($record->itemid, $areamaxbytes, filesize($_FILES[$elname]['tmp_name']))) {
        //     throw new file_exception('maxareabytes');
        // }

        $record->contextid = $context->id;
        $record->userid    = $USER->id;

        // if (repository::draftfile_exists($record->itemid, $record->filepath, $record->filename)) {
        //     $existingfilename = $record->filename;
        //     $unused_filename = repository::get_unused_filename($record->itemid, $record->filepath, $record->filename);
        //     $record->filename = $unused_filename;
        //     $stored_file = $fs->create_file_from_pathname($record, $_FILES[$elname]['tmp_name']);
        //     if ($overwriteexisting) {
        //         repository::overwrite_existing_draftfile($record->itemid, $record->filepath, $existingfilename, $record->filepath, $record->filename);
        //         $record->filename = $existingfilename;
        //     } else {
        //         $event = array();
        //         $event['event'] = 'fileexists';
        //         $event['newfile'] = new stdClass;
        //         $event['newfile']->filepath = $record->filepath;
        //         $event['newfile']->filename = $unused_filename;
        //         $event['newfile']->url = moodle_url::make_draftfile_url($record->itemid, $record->filepath, $unused_filename)->out(false);

        //         $event['existingfile'] = new stdClass;
        //         $event['existingfile']->filepath = $record->filepath;
        //         $event['existingfile']->filename = $existingfilename;
        //         $event['existingfile']->url      = moodle_url::make_draftfile_url($record->itemid, $record->filepath, $existingfilename)->out(false);
        //         return $event;
        //     }
        // } else {
        //     $stored_file = $fs->create_file_from_pathname($record, $_FILES[$elname]['tmp_name']);
        // }

        // return array(
        //     'url'=>moodle_url::make_draftfile_url($record->itemid, $record->filepath, $record->filename)->out(false),
        //     'id'=>$record->itemid,
        //     'file'=>$record->filename);
        //Initialise the cURL var

		// files to upload
		$filenames = array($_FILES[$elname]['tmp_name']);

		$files = array();
		foreach ($filenames as $f){
		   $files[$f] = file_get_contents($f);
		}


		// curl
		$curl = curl_init();

		$boundary = uniqid();
		$delimiter = '-------------' . $boundary;

		$post_data = self::build_files($boundary, $files);

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->get_option('ontt_upload'),
		  CURLOPT_RETURNTRANSFER => 1,
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POST => 1,
		  CURLOPT_POSTFIELDS => $post_data,
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".$this->get_option('ontt_token'),
		    "Content-Type: multipart/form-data; boundary=" . $delimiter,
		    "Content-Length: " . strlen($post_data)

		  ),		  
		));

		//
		$response = curl_exec($curl);

		$info = curl_getinfo($curl);
		//echo "code: ${info['http_code']}";

		//print_r($info['request_header']);

		var_dump($response);
		$err = curl_error($curl);

		echo "error";
		var_dump($err);
		curl_close($curl);
    }

    protected function build_files($boundary, $files){
	    $data = '';
	    $eol = "\r\n";

	    $delimiter = '-------------' . $boundary;

	    foreach ($files as $name => $content) {
	        $data .= "--" . $delimiter . $eol
	            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
	            //. 'Content-Type: image/png'.$eol
	            . 'Content-Transfer-Encoding: binary'.$eol
	            ;

	        $data .= $eol;
	        $data .= $content . $eol;
	    }
	    $data .= "--" . $delimiter . "--".$eol;
	    return $data;
	}
    /**
     * Save ontt_token in config table.
     * @param array $options
     * @return boolean
     */
    public function set_option($options = array()) {
        if (!empty($options['ontt_token'])) {
            set_config('ontt_token', trim($options['ontt_token']), 'onttapi');
        }
        if (!empty($options['ontt_upload'])) {
            set_config('ontt_upload', trim($options['ontt_upload']), 'onttapi');
        }
        if (!empty($options['ontt_list'])) {
            set_config('ontt_list', trim($options['ontt_list']), 'onttapi');
        }
        unset($options['ontt_token']);
        unset($options['ontt_upload']);
        unset($options['ontt_list']);
        return parent::set_option($options);
    }

    /**
     * Get ontt_token from config table.
     *
     * @param string $config
     * @return mixed
     */
    public function get_option($config = '') {
        if ($config === 'ontt_token') {
            return trim(get_config('onttapi', 'ontt_token'));
        } else if ($config === 'ontt_upload') {
            return trim(get_config('onttapi', 'ontt_upload'));
        } else if ($config === 'ontt_list') {
            return trim(get_config('onttapi', 'ontt_list'));
        } else {
            $options = parent::get_option();
            $options['ontt_token'] = trim(get_config('onttapi', 'ontt_token'));
            $options['ontt_upload'] = trim(get_config('onttapi', 'ontt_upload'));
            $options['ontt_list'] = trim(get_config('onttapi', 'ontt_list'));
        }

        return $options;
    }

    /**
     * file types supported by youtube plugin
     * @return array
     */
    public function supported_filetypes() {
        return array('video');
    }

    /**
     * Youtube plugin only return external links
     * @return int
     */
	public function supported_returntypes() {
		return FILE_INTERNAL | FILE_EXTERNAL | FILE_REFERENCE | FILE_CONTROLLED_LINK;
	}

	/**
     * Is this repository accessing private data?
     *
     * @return bool
     */
    public function contains_private_data() {
        return false;
    }

	/**
     * Add plugin settings input to Moodle form.
     * @param object $mform
     * @param string $classname
     */
    public static function type_config_form($mform, $classname = 'repository') {
        parent::type_config_form($mform, $classname);
        $token = get_config('onttapi', 'ontt_token');
        if (empty($token)) {
            $token = '';
        }

        $mform->addElement('text', 'ontt_upload', get_string('ontt_upload', 'repository_onttapi'), array('size' => '40'));
        $mform->addRule('ontt_upload', get_string('required'), 'required', null, 'client');
        $mform->setType('ontt_upload', PARAM_TEXT);

        $mform->addElement('text', 'ontt_list', get_string('ontt_list', 'repository_onttapi'), array('size' => '40'));
        $mform->addRule('ontt_list', get_string('required'), 'required', null, 'client');
        $mform->setType('ontt_list', PARAM_TEXT);

        $mform->addElement('textarea', 'ontt_token', get_string('ontt_token', 'repository_onttapi'), array('value' => $token, 'rows' => '10','cols'=>'37'));
        $mform->setType('ontt_token', PARAM_RAW_TRIMMED);
        $mform->addRule('ontt_token', get_string('required'), 'required', null, 'client');

    }

    /**
     * Names of the plugin settings
     * @return array
     */
    public static function get_type_option_names() {
        return array('ontt_token', 'ontt_upload', 'ontt_list', 'pluginname');
    }
}