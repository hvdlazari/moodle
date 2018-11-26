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
 * configuration form of video manager block. This configures the block itself, this is not the instance form.
 * This is the configuration of the block itself, NOT the edit page of an video manager page instance.
 * @package     block
 * @subpackage  video_manager
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_video_manager_edit_form extends block_edit_form
{

    protected function specific_definition($mform)
    {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('titleinputlabel', 'block_video_manager'));
        $mform->setDefault('config_title', get_string('typeatext', 'block_video_manager'));
        $mform->setType('config_title', PARAM_TEXT);

    }
}