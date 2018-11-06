<?php

/**
 * OnTT Player external functions and service definitions.
 *
 * @package    mod_onttplayer
 * @category   external
 * @copyright  2018 VC Intelligence {@link http://vcgroupweb.com}
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die;

$functions = array(

    'mod_onttplayer_view_resource' => array(
        'classname'     => 'mod_onttplayer_external',
        'methodname'    => 'view_resource',
        'description'   => 'Simulate the view.php web interface resource: trigger events, completion, etc...',
        'type'          => 'write',
        'capabilities'  => 'mod/onttplayer:view',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'mod_onttplayer_get_resources_by_courses' => array(
        'classname'     => 'mod_onttplayer_external',
        'methodname'    => 'get_resources_by_courses',
        'description'   => 'Returns a list of files in a provided list of courses, if no list is provided all files that
                            the user can view will be returned.',
        'type'          => 'read',
        'capabilities'  => 'mod/onttplayer:view',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
    ),
);
