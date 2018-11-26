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
 * Timeline block installation.
 *
 * @package    block_timeline
 * @copyright  2018 Ryan Wyllie <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

 /**
  * Add the timeline block to the dashboard for all users by default
  * when it is installed.
  */
function xmldb_block_video_manager_install() {
    global $DB;

    $check_categories = $DB->get_records('video_manager_categories');
    if (empty($check_categories)) {
        $data = new stdClass;
        $data->parentid = 0;
        $data->name = get_string('category:system','block_video_manager');
        $DB->insert_record('video_manager_categories',$data);
    }
}
