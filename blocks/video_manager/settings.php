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
 * global settings for admin of video manager block plugin
 * @package     block
 * @subpackage  video_manager
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if ($ADMIN->fulltree) {
	$settings->add(
		new admin_setting_heading(
		    'headerconfig',
		    get_string('headerconfig', 'block_video_manager'),
		    get_string('descconfig', 'block_video_manager')
		)	
	);

	$settings->add(
		new admin_setting_configtextarea(
		    'video_manager/token',
		    get_string('label:token', 'block_video_manager'),
		    get_string('desc:token', 'block_video_manager'),
		    get_string('defaultblock:token', 'block_video_manager')
		)
	);

	$settings->add(
		new admin_setting_configtextarea(
		    'video_manager/endpoints',
		    get_string('label:endpoints', 'block_video_manager'),
		    get_string('desc:endpoints', 'block_video_manager'),
		    get_string('defaultblock:endpoints', 'block_video_manager')
		)
	);
}