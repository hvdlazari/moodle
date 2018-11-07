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
 * Resource module version information
 *
 * @package    mod_resource
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/mod/onttplayer/lib.php');
require_once($CFG->dirroot.'/mod/onttplayer/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

$id       = optional_param('id', 0, PARAM_INT); // Course Module ID
$r        = optional_param('r', 0, PARAM_INT);  // Resource instance ID
$redirect = optional_param('redirect', 0, PARAM_BOOL);
$forceview = optional_param('forceview', 0, PARAM_BOOL);

if ($r) {
    if (!$onttplayer = $DB->get_record('onttplayer', array('id'=>$r))) {
        print_error('invalidaccessparameter');
    }
    $cm = get_coursemodule_from_instance('onttplayer', $onttplayer->id, $onttplayer->course, false, MUST_EXIST);

} else {
    if (!$cm = get_coursemodule_from_id('onttplayer', $id)) {
        print_error('invalidcoursemodule');
    }
    $onttplayer = $DB->get_record('onttplayer', array('id'=>$cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/onttplayer:view', $context);

// Completion and trigger events.
onttplayer_view($onttplayer, $course, $cm, $context);

$PAGE->set_url('/mod/onttplayer/view.php', array('id' => $cm->id));

$PAGE->set_title($course->shortname.': '.$onttplayer->name);
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->box($onttplayer->intro, "generalbox center clearfix");
echo $OUTPUT->footer();