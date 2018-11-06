<?php

/**
 * Definition of log events
 *
 * @package    mod_onttplayer
 * @category   log
 * @copyright  2018 VC Intelligence {@link http://vcgroupweb.com}
 */

defined('MOODLE_INTERNAL') || die();

$logs = array(
    array('module'=>'onttplayer', 'action'=>'view', 'mtable'=>'onttplayer', 'field'=>'name'),
    array('module'=>'onttplayer', 'action'=>'view all', 'mtable'=>'onttplayer', 'field'=>'name'),
    array('module'=>'onttplayer', 'action'=>'update', 'mtable'=>'onttplayer', 'field'=>'name'),
    array('module'=>'onttplayer', 'action'=>'add', 'mtable'=>'onttplayer', 'field'=>'name'),
);