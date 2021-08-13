<?php

add_action('admin_menu', 'hmcs_addMenuPage');
function hmcs_addMenuPage(){
    
    $hook = add_menu_page(
                'تنظیمات استایل سفارشی',
                'استایل سفارشی',
                'administrator',
                'hmcs_style_option',
                'hmcs_echo_option_style',
                HMCS_IMAGE_URL . '/small_icon.png',
                '25.36'
            );
    
    function hmcs_echo_option_style(){
        include HMCS_PLUGIN_DIR . 'admin/view/style_options.php';
    }
    
    
}

add_filter("plugin_action_links_" . HMCS_BASENAME , 'hmcc_addLinkToPluginPage');
function hmcc_addLinkToPluginPage($links){
    $links[] = '<a target="_blank" href="' . admin_url('admin.php?page=hmcs_style_option') . '">تنظیمات</a>';
    return $links;
}

add_action( 'admin_bar_menu', 'add_custom_menu', 50 );
function add_custom_menu() {
    global $wp_admin_bar;
    $menuArgs = array(
        'parrent'   =>  'root-default',
        'id'   =>  'style_menu',
        'title'   =>  'استایل پیشرفته',
        'href'   =>  'admin.php?page=hmcs_style_option',
        'meta'   =>  array(
            'target'   =>  '_blank',
        ),
    );
    $wp_admin_bar->add_menu($menuArgs);
}