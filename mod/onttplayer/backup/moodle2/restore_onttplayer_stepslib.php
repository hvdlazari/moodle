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
 * @package    mod_onttplayer
 * @subpackage backup-moodle2
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that will be used by the restore_onttplayer_activity_task
 */

/**
 * Structure step to restore one onttplayer activity
 */
class restore_onttplayer_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('onttplayer', '/activity/onttplayer');
        $paths[] = new restore_path_element('onttplayer_videos', '/activity/onttplayer/onttplayer_videos/onttplayer_video');

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_onttplayer($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        // See MDL-9367.

        // insert the onttplayer record
        $newitemid = $DB->insert_record('onttplayer', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function after_execute() {
        // Add choice related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_onttplayer', 'intro', null);
    }

    protected function process_onttplayer_videos($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->onttplayerid = $this->get_new_parentid('onttplayer');
        $data->timemodified = time();

        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        // See MDL-9367.

        // insert the onttplayer record
        $newitemid = $DB->insert_record('onttplayer_videos', $data);
        
        // Add mapping, restore of logs needs it.
        $this->set_mapping('onttplayer_videos', $oldid, $newitemid);
    }
}
