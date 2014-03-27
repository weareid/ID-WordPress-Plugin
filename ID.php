<?php
/*
Plugin Name: ID Plugin
Plugin URI: http://www.interactivedimension.com
Description: What we deem to be essential stuff for our WordPress sites!
Version: 1.0.2
Author: Interactive Dimension
Author URI: http://www.interactivedimension.com
*/

/* Crazy lazy stuff in here. Use at your own risk! */

//this plugin can update from GitHub!
require_once( 'github_updater/BFIGitHubPluginUploader.php' );
if ( is_admin() ) {
    new BFIGitHubPluginUpdater( __FILE__, 'weareid', "ID-WordPress-Plugin" );
}

DEFINE('load_bootstrap', true);

//remove WP admin bar
function admin_bar_remove() {
    add_filter('show_admin_bar', '__return_false');
    show_admin_bar(false);
}
add_action('init', 'admin_bar_remove', 0);

//enable debug on ID LAN. Careful!
if (
    $_SERVER['SERVER_ADDR'] == '10.0.0.4' || 
    $_SERVER['SERVER_ADDR'] == '192.168.0.3' ||
    $_SERVER['SERVER_ADDR'] == '192.168.0.5'
    ) {
    define('id_local_mode', true);
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

function load_bootstrap_js() {

echo '<!-- Loading Twitter Bootstrap JS -->';
echo "<script type='text/javascript' src='".plugin_dir_url(__FILE__)."bootstrap-3.1.1-dist/js/bootstrap.min.js'></script>";

}

function load_bootstrap_css() {
    $theme  = get_theme( get_current_theme() );
    //echo '<!-- Loading Twitter Bootstrap CSS -->';
    //echo "<link rel='stylesheet' href=\"".plugin_dir_url(__FILE__)."bootstrap-3.1.1-dist/css/bootstrap.min.css\">";
    wp_register_style( 'bootstrap-css', plugin_dir_url(__FILE__).'bootstrap-3.1.1-dist/css/bootstrap.min.css', false, $theme['Version'] );
    //wp_enqueue_style( 'bootstrap-css' );
}


if(load_bootstrap == true) {
add_action('wp_enqueue_scripts', 'load_bootstrap_css', 9, 2);
add_action('wp_footer', 'load_bootstrap_js', 99, 2);
}

// changing the login page URL
function put_my_url(){
    return ('http://www.interactivedimension.com/'); // putting my URL in place of the WordPress one
}
add_filter('login_headerurl', 'put_my_url');

// changing the login page URL hover text
function put_my_title(){
    return ('Powered by Interactive Dimension'); // changing the title from "Powered by WordPress" to whatever you wish
}
add_filter('login_headertitle', 'put_my_title');

function id_get_wp_info() {

    if($_GET['wp_info']) {
    $version = get_bloginfo('version');

    $data = array(
        'version' => $version
    );

    echo json_encode($data);

    die();
    }
}
add_action('init','id_get_wp_info');