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
 * Video Manager Class Task
 *
 * @package    block_video_manager
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace block_video_manager\task;

defined('MOODLE_INTERNAL') || die();

class get_status_video extends \core\task\scheduled_task {

    public function get_name() {
        // Shown in admin screens
        return get_string('task:get_status_video', 'block_video_manager');
    }

    public function execute() {
    	global $DB;

    }
}