<?php

/**
 * OnTT Player module admin settings and defaults
 *
 * @package    mod_onttplayer
 * @copyright  2018 VC Intelligence {@link http://vcgroupweb.com}
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

	//--- general settings -----------------------------------------------------------------------------------
	$settings->add(
		new admin_setting_configtextarea(
			'onttplayer/token',
			get_string('token', 'onttplayer'),
			get_string('token_desc', 'onttplayer'),
			''
		)
	);
}
