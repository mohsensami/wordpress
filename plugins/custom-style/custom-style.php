<?php
/*
 * Plugin Name: استایل سفارشی
 * Author: محسن سامی
 * Version: 1.0.0
 * Decription: ایجاد استایل د اسکریپت سفارشی در سایت
 * Licence: GPLv2 or later
 */

defined('ABSPATH') || exit;

define('HMCS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HMCS_IMAGE_URL', plugins_url('images', __FILE__));
define('HMCS_BASENAME', plugin_basename(__FILE__));



//if (is_admin()) {
    include HMCS_PLUGIN_DIR . 'admin/include/functions.php';
//}
    
add_action('wp_head', 'hmcs_echoStyle');
function hmcs_echoStyle(){
    $style = get_option('hmcs_style_key');
    echo '<style type="text/css">' . PHP_EOL;
    echo $style ? $style : '';
    echo '</style>'. PHP_EOL;
}

add_action('wp_footer', 'hmcs_echoScript');
function hmcs_echoScript(){
    $script = str_replace('\\', '', get_option('hmcs_script_key'));
    echo '<script type="text/javascript">' . PHP_EOL;
    echo $script ? $script : '';
    echo '</script>';
}