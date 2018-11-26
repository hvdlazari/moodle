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
 * This is the instance form for create/edit operations.
 * @package     block
 * @subpackage  video_manager
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot . '/blocks/video_manager/lib.php');
require_once($CFG->dirroot . '/blocks/video_manager/locallib.php');

class category_manager_form extends moodleform
{

    public function definition()
    {
        $class = new block_video_manager();

        $mform =& $this->_form;
        $mform->addElement('header', 'displayinfo', get_string('videodata', 'block_video_manager'));

        $options = $class->get_categories();
        $mform->addElement('select', 'parentid', get_string('form:parentid','block_video_manager'), $options);
        $mform->addRule('parentid', null, 'required', null, 'client');

        $mform->addElement('text', 'name', get_string('form:name', 'block_video_manager'),array('size'=>40));
        $mform->setType('name', PARAM_RAW);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();

    }
}

class video_manager_form extends moodleform
{

    public function definition()
    {
        $class = new block_video_manager();

        $mform =& $this->_form;
        $mform->addElement('header', 'displayinfo', get_string('videodata', 'block_video_manager'));

        $filemanageropts = $this->_customdata['filemanageropts'];
        $filemanageropts['maxfiles'] = 1;

        $options = $class->get_categories();
        $mform->addElement('select', 'categoryid', get_string('form:parentid','block_video_manager'), $options);
        $mform->addRule('categoryid', null, 'required', null, 'client');

        $mform->addElement('text', 'name', get_string('form:name', 'block_video_manager'),array('size'=>40));
        $mform->setType('name', PARAM_RAW);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('filemanager', 'file',get_string('form:file', 'block_video_manager'), null, $filemanageropts);
        $mform->addRule('file', null, 'required', null, 'client');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();

    }
}