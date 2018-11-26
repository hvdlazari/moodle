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
 * en language file
 * @package     block
 * @subpackage  video_manager
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//video_manager/block_video_manager.php
$string['pluginname'] = 'Video Manager';
$string['manager'] = 'Video Manager';
$string['manager_categories'] = 'Categories Manager';
$string['manager_videos'] = 'Videos Manager';
$string['addvideo'] = 'Add Vídeo';
$string['videodata'] = 'Video Data';
$string['form:name'] = 'Name';
$string['form:file'] = 'File';
$string['form:parentid'] = 'Category';

//video_manager/settings.php
$string['headerconfig'] = 'Global configuration';
$string['descconfig'] = 'This config is only accesible by admin.';
$string['label:token'] = 'Topen Api';
$string['desc:token'] = 'Token';
$string['defaultblock:token'] = '';
$string['label:endpoints'] = 'Endpoints';
$string['desc:endpoints'] = 'Endpoints';
$string['defaultblock:endpoints'] = '{
	"list":"http://ottaas.com.br/api/ingest/list",
	"upload":"http://ottaas.com.br/api/ingest/upload",
}';

$string['category:system'] = 'System';

$string['task:get_status_video'] = 'Check video processing status on Api.';