<?php
defined('MOODLE_INTERNAL') || die();

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
 * a basic video manager block plugin
 * @package     block
 * @subpackage  video_manager
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_video_manager extends block_base
{
    /**
     * block initializations
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_video_manager');
    }

    public function get_content() {
        global $COURSE, $DB, $PAGE;
        $syscontext = context_system::instance();
        // Check to see if we are in editing mode and that we can manage pages.
        if ($this->content !== NULL) {
            return $this->content->text;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        
        if (has_capability('block/video_manager:view', $syscontext)) {
            $this->content->text = html_writer::link(
                    new moodle_url('/blocks/video_manager/view.php'),
                    get_string('manager','block_video_manager'));
        }

        return $this->content->text;
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function has_config() {
        return true;
    }
}