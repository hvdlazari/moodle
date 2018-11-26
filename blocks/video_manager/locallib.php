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
 * Video Manager
 *
 * @package    block_video_manager
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

class block_video_manager {

    public function get_categories(&$output=null, $parentid=0, $indent=null) {
        global $DB;

        $categories = $DB->get_records('video_manager_categories',array('parentid'=>$parentid));
        if ($categories) {
            foreach ($categories as $cat) {
                $output[$cat->id] =  $indent . $cat->name;
                if ($cat->id != $parentid) {
                    self::get_categories($output, $cat->id, $indent . "&nbsp;&nbsp;");
                }
            }
        }
        return $output;
    }

    public function get_category($categoryid) {
        global $DB;
        return $DB->get_record('video_manager_categories',array('id'=>$categoryid));
    }

    public function insert_category($data) {
        global $DB;
        return $DB->insert_record('video_manager_categories', $data);
    }

    public function update_category($data) {
        global $DB;

        if (!$DB->get_record('video_manager_categories',array('id'=>$data->id))) {
            return false;
        }

        return $DB->update_record('video_manager_categories', $data);        
    }

    public function delete_category($categoryid) {
        global $DB;

        $category = $DB->get_record('video_manager_categories',array('id'=>$categoryid));

        if ($children = $DB->get_records('video_manager_categories',array('parentid'=>$categoryid))) {
            foreach ($children as $child) {
                $data = new stdclass;
                $data->id = $child->id;
                $data->parentid = $category->parentid;
                $DB->update_record('video_manager_categories',$data);
            }
            
        }

        if ($videos = $DB->get_records('video_manager_videos',array('categoryid'=>$categoryid))) {
            foreach ($videos as $video) {
                $data = new stdclass;
                $data->id = $video->id;
                $data->categoryid = $category->parentid;
                $DB->update_record('video_manager_videos',$data);
            }
            
        }

        return $DB->delete_records('video_manager_categories',array('id'=>$categoryid));
    }    

    public function get_videos($data) {
        global $DB;
        return $DB->get_records('video_manager_videos',array('deleted'=>0));
    }

    public function get_video($videoid) {
        global $DB;
        return $DB->get_records('video_manager_videos',array('id'=>$videoid));
    }

    public function insert_video($data) {
        global $DB;
        return $DB->insert_record('video_manager_videos', $data);
    }

    public function update_video($data) {
        global $DB;

        if (!$DB->get_record('video_manager_videos',array('id'=>$data->id))) {
            return false;
        }

        return $DB->update_record('video_manager_videos', $data); 
    }

    public function delete_video($videoid) {
        global $DB;
        return $DB->delete_records('video_manager_videos',array('id'=>$videoid));
    }
}