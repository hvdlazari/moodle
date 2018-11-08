<?php

/**
 * OnTT Player configuration form
 *
 * @category   log
 * @copyright  2018 VC Intelligence {@link http://vcgroupweb.com}
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
//require_once($CFG->dirroot.'/mod/resource/locallib.php');
require_once($CFG->libdir.'/filelib.php');

class mod_onttplayer_mod_form extends moodleform_mod {
    function definition() {
        global $CFG, $DB, $PAGE;

        $mform =& $this->_form;
        // $PAGE->requires->yui_module('moodle-course-formatchooser', 'M.course.init_formatchooser',
        //         array(array('formid' => $mform->getAttribute('id'))));

        $config = get_config('onttplayer');

        //-------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('name'), array('size'=>'48'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $this->standard_intro_elements();
        $element = $mform->getElement('introeditor');
        $attributes = $element->getAttributes();
        $attributes['rows'] = 5;
        $element->setAttributes($attributes);

        //-------------------------------------------------------
        $mform->addElement('header', 'video', get_string('form:header:videos','mod_onttplayer'));
        $mform->addElement('button', 'library_videos', get_string('button:library_videos','mod_onttplayer'));

        $mform->addElement('hidden', 'addcourseformatoptionshere');
        $mform->setType('addcourseformatoptionshere', PARAM_BOOL);
        

        //-------------------------------------------------------
        $this->standard_coursemodule_elements();

        //-------------------------------------------------------
        $this->add_action_buttons();

    }
}