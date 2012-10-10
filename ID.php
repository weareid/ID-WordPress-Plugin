<?php
/*
Plugin Name: ID Plugin
Plugin URI: http://www.interactivedimension.com
Description: What we deem to be essential stuff for our WordPress sites!
Version: 1.0
Author: Louis Northmore
Author URI: http://www.interactivedimension.com
*/

/* Crazy lazy stuff in here. Use at your own risk! */

//remove WP admin bar
function admin_bar_remove() {
    add_filter('show_admin_bar', '__return_false');
show_admin_bar(false);

}

add_action('init', 'admin_bar_remove', 0);

//enable debug on ID LAN.
if ($_SERVER['SERVER_ADDR'] == '10.0.0.4') {
    add_action('wp_head', 'debug_mode_on');


    function debug_mode_on() {
    define('WP_DEBUG', true);
    $hostname = gethostbyaddr($_SERVER['SERVER_ADDR']);
    echo "<!-- We're on the ID LAN (".$_SERVER['SERVER_ADDR'].") ($hostname). DEBUG mode is ON! -->";
    }
}

//hide post preview
function hide_post_preview() {
    echo <<<END

<style type="text/css">
#preview-action {
    display: none;
}
</style>

END;
}
add_action('admin_head', 'hide_post_preview');

//add post thumbnails
add_theme_support( 'post-thumbnails' );

//reduce post revisions to 5
define( 'WP_POST_REVISIONS', 5);

add_action('wp-head', 'header_info_div');
function header_info_div() {
    $output = '
       <div style="width: 100%; height: 50px; background-color: #ffffff;">
        ID INFO DIV.
       </div>
    ';
    return $output;
}

