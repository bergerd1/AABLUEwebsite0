<?php
// Load the test environment
// https://github.com/nb/wordpress-tests

$path = '/Users/gilbitron/Dropbox/_xampp/wordpress-tests/bootstrap.php';

if (file_exists($path)) {
    $GLOBALS['wp_tests_options'] = array(
        'active_plugins' => array('nivo-slider/nivo-slider.php')
    );
    require_once $path;
} else {
    exit("Couldn't find wordpress-tests/bootstrap.php\n");
}