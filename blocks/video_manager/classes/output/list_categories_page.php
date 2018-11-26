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
 * Search form renderable.
 *
 * @package    block_video_manager
 * @copyright  2016 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_video_manager\output;
defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

/**
 * Search form renderable class.
 *
 * @package    block_video_manager
 * @copyright  2016 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class list_categories_page implements renderable, templatable {

	protected $categories;

	public function __construct($categories = null) {
		$this->categories = $categories;
    }

    public function export_for_template(renderer_base $output) {
        $items = [];
    	if ($this->categories) {
    		foreach ($this->categories as $id => $cat) {
    			$urldelete = new moodle_url('/blocks/video_manager/category.php',array('categoryid'=>$id,'action'=>'delete'));
    			$urledit = new moodle_url('/blocks/video_manager/category.php',array('categoryid'=>$id,'action'=>'edit'));

                $actions = [];
                if ($id != 1) {
                    $actions = array(
                        array(
                            'title' => 'Delete',
                            'url' => $urldelete->out(false),
                            'icon'=> $output->pix_icon('t/delete', 'delete')
                        ),
                        array(
                            'title' => 'Edit',
                            'url' => $urledit->out(false),
                            'icon'=> $output->pix_icon('t/edit', 'edit')
                        )
                    );
                }

    			$items['categories'][] = array(
    				'id'=>$id,
    				'name'=>$cat,
    				'url'=>new moodle_url('/blocks/video_manager/manager_categories.php'),
    				'actions' => $actions
    			);
    		}
    	}
        return $items;
    }
}