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
require_once($CFG->dirroot . '/blocks/video_manager/locallib.php');
require_once($CFG->dirroot . '/blocks/video_manager/manager_forms.php');

// Check for all required variables.
$categoryid = optional_param('categoryid', null, PARAM_INT);
$action = optional_param('action', null, PARAM_TEXT);

require_login();
$syscontext = context_system::instance();

require_capability('block/video_manager:view', $syscontext);

$class = new block_video_manager();

//breadcrumb
$PAGE->navbar->add(get_string('manager', 'block_video_manager'),new moodle_url('/blocks/video_manager/view.php'));
$PAGE->navbar->add(get_string('addvideo', 'block_video_manager'));

$PAGE->set_url('/blocks/video_manager/category.php', array('categoryid'=>$categoryid));
$PAGE->set_context($syscontext);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('addvideo', 'block_video_manager'));

$entry = new stdClass;
if ($categoryid && $category = $class->get_category($categoryid)) {
    $entry->id = $categoryid;
    $entry->parentid = $category->parentid;
    $entry->name = $category->name;
}

$category_manager_form = new category_manager_form();
$category_manager_form->set_data($entry);

$returnurl = new moodle_url('/blocks/video_manager/manager_categories.php');

if (isset($action) && $action=='delete' && $categoryid) {
    $class->delete_category($categoryid);
    redirect($returnurl);
}

if ($category_manager_form->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    redirect($returnurl);
} else if ($form_submitted_data = $category_manager_form->get_data()) {
    //form has been submitted
    if ($form_submitted_data->id != 0) {
        if (!$class->update_category($form_submitted_data)) {
            print_error('updateerror', 'block_video_manager');
        }
    } else {
        if (!$class->insert_category($form_submitted_data)) {
            print_error('inserterror', 'block_video_manager');
        }
    }
    redirect($returnurl);
} else {
    // form didn't validate or this is the first display
    echo $OUTPUT->header();
    if ($categoryid && $category) {
        $category_manager_form->set_data($category);
    }
    $category_manager_form->display();
    echo $OUTPUT->footer();
}