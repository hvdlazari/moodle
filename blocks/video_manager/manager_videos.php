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
 * This is the create/edit page for a video manager instance.
 * @package     block
 * @subpackage  video_manager
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/video_manager/lib.php');
require_once($CFG->dirroot . '/blocks/video_manager/locallib.php');

// Check for all required variables.
$categoryid = optional_param('categoryid',1, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT); 

$params = array('categoryid'=>$categoryid, 'page'=>$page);

require_login();
$syscontext = context_system::instance();

require_capability('block/video_manager:view', $syscontext);

//breadcrumb
$PAGE->navbar->add(get_string('pluginname', 'block_video_manager'),new moodle_url('/blocks/video_manager/view.php'));
$PAGE->navbar->add(get_string('manager_videos', 'block_video_manager'));
$PAGE->set_url('/blocks/video_manager/manager_videos.php',$params);
$PAGE->set_context($syscontext);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('manager_videos', 'block_video_manager'));

$class = new block_video_manager();

$output = $PAGE->get_renderer('block_video_manager');
echo $output->header();

echo html_writer::link(new moodle_url('/blocks/video_manager/video.php'),'Adicionar Vídeo');

$cats = $class->get_categories();
echo $output->single_select('/blocks/video_manager/manager_categories.php','selectID',$cats);

echo $output->footer();