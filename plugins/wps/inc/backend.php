<?php

function wps_admin_menu(){
    global  $wpdb,$table_prefix;
    $today = date("Y-m-d");
    $totalStatistics = $wpdb->get_row("SELECT SUM(total_visits) as total_visits,
                                           SUM(unique_visits) as total_unique_visits
                                    FROM {$table_prefix}wps_visits");
    $todayStatitics = $wpdb->get_row("SELECT total_visits,
                                           unique_visits
                                    FROM {$table_prefix}wps_visits
                                    WHERE date = '{$today}'");
    //var_dump( $todayStatitics );
    //yesterday stat
    $yesterdayStatitics = $wpdb->get_row("SELECT total_visits,
                                           unique_visits
                                    FROM {$table_prefix}wps_visits
                                    WHERE date = DATE_SUB('{$today}',INTERVAL 1 DAY)");
    //var_dump( $yesterdayStatitics );
    //echo $wpdb->last_query;
    $where = " WHERE 1 ";
    if(isset($_GET['startDate']) && !empty($_GET['startDate']) && isset($_GET['endDate']) && !empty($_GET['endDate'])){
        $startDate = wpsConvertToGregorian(esc_sql($_GET['startDate']));
        $endDate = wpsConvertToGregorian(esc_sql($_GET['endDate']));
        $where.="AND date >= '{$startDate}' AND date <= '{$endDate}'";
    }

    $visitsChartData = $wpdb->get_results("select `date`,total_visits,unique_visits
                                                         from {$table_prefix}wps_visits{$where}");
    //var_dump($wpdb->last_query);
    $visitsDates = [];
    $totalVisits = [];
    $uniqueVisits = [];
    foreach ($visitsChartData as $item){
        $visitsDates[]=$item->date;
        $totalVisits[]=$item->total_visits;
        $uniqueVisits[]=$item->unique_visits;
    }
    array_walk($visitsDates,'wpsConvertToPersian');
    include  WPS_TPL."admin_main_page.php";
}
function wps_admin_menu_settings(){
    $tabs = array(
        'general' => 'عمومی',
        'messages' => 'اطلاع رسانی',
        'about'   => 'درباره ما'
    );
    $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

    if(isset($_POST['submit'])){
        $wps_enable = isset($_POST['wps_enable']) ? 1 : 0 ;
        update_option('wps_enable',$wps_enable);
        // update admin email setting for wps plugin
        !empty($_POST['wps_admin_email'])
        && filter_var($_POST['wps_admin_email'],FILTER_VALIDATE_EMAIL)?
            update_option('wps_admin_email',esc_sql($_POST['wps_admin_email'])):null;
        !empty($_POST['wps_admin_mobile']) ?
            update_option('wps_admin_mobile',esc_sql($_POST['wps_admin_mobile'])):null;
//        $wps_status = "wps_enable:".$wps_enable;
//        file_put_contents(WPS_DIR.'settings.txt',$wps_status);
        isset($_POST['wps_daily_report_sms']) && !empty($_POST['wps_daily_report_sms'])?
            update_option('wps_daily_report_sms',strip_tags($_POST['wps_daily_report_sms'])):null;

        isset($_POST['wps_daily_report_email']) && !empty($_POST['wps_daily_report_email'])?
            update_option('wps_daily_report_email',strip_tags($_POST['wps_daily_report_email'])):null;
    }
//    $wps_settings = file(WPS_DIR.'settings.txt');
//    $wps_enable_option = $wps_settings[0];
//    $wps_enable = explode(':',$wps_enable_option);
//    $wps_enable_status = $wps_enable[1];
    $wps_enable_value = intval(get_option('wps_enable'));
    $wps_admin_email = get_option('wps_admin_email');
    $wps_admin_mobile = get_option('wps_admin_mobile');
    $wps_daily_report_sms = get_option('wps_daily_report_sms');
    $wps_daily_report_email = get_option('wps_daily_report_email');
    
    include  WPS_TPL."admin_settings_page.php";
}
/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
       'آمار بازدید کاربران',
        'آمار بازدید کاربران',
        'manage_options',
        'wps/wps-stat.php',
        'wps_admin_menu',
        'dashicons-chart-area',//WPS_IMAGES.'icon.png',
        6
    );
    add_submenu_page(
        'wps/wps-stat.php',
        'داشبورد',
        'داشبورد',
        'manage_options',
        'wps/wps-stat.php',
        'wps_admin_menu'
    );
    add_submenu_page(
        'wps/wps-stat.php',
        'تنظیمات',
        'تنظیمات',
        'manage_options',
        'wps/wps-settings.php',
        'wps_admin_menu_settings'
    );
    wps_load_assets();
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

//define load assets
function wps_load_assets(){
    wp_register_script( 'chart.min.js', WPS_JS.'Chart.min.js',array('jquery') );
    wp_register_script( 'persianDatepicker.min.js', WPS_JS.'persianDatepicker.min.js',array('jquery') );
    wp_register_script( 'wps.admin.js', WPS_JS.'admin.js',array('jquery','chart.min.js','persianDatepicker.min.js') );

    wp_enqueue_script( 'chart.min.js' );
    wp_enqueue_script( 'persianDatepicker.min.js' );
    wp_enqueue_script( 'wps.admin.js' );

    //add style
    wp_register_style('persianDatepicker-default.css',WPS_CSS.'persianDatepicker-default.css');
    wp_register_style('wps.admin.css',WPS_CSS.'admin.css');
    wp_enqueue_style('persianDatepicker-default.css');
    wp_enqueue_style('wps.admin.css');

}
function wpsConvertToPersian(&$date){
    if(is_null($date) || empty($date))
            return $date;

    $dateArray = explode('-',$date);
    !function_exists('gregorian_to_jalali') ? include WPS_INC.'jdf.php' : null;
    $persianDate = gregorian_to_jalali($dateArray[0],$dateArray[1],$dateArray[2]);
    $date = implode('/',$persianDate);
   // return implode('/',$persianDate);
}
function wpsConvertToGregorian($date){
    $dateParts = explode('-',$date);
    !function_exists('jalali_to_gregorian') ? include WPS_INC.'jdf.php' : null;
    $newDate = jalali_to_gregorian($dateParts[0],$dateParts[1],$dateParts[2]);
    return implode('-',$newDate);

}
if(!function_exists('dd')){
    function dd($data){

        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();

    }
}

// hooks
add_action('wps_notify','wps_notify_callback');
function wps_notify_callback(){
    global $wpdb,$table_prefix;
    $wps_admin_email = get_option('wps_admin_email');
    $wps_admin_mobile = get_option('wps_admin_mobile');
    $today = date("Y-m-d");
    $wps_daily_report_sms = get_option('wps_daily_report_sms');
    $todayStatitics = $wpdb->get_row("SELECT total_visits,
                                           unique_visits
                                    FROM {$table_prefix}wps_visits
                                    WHERE date = '{$today}'");
    $tags = array(
        '#totalVisits#',
        '#uniqueVisits#'
    );
    $values = array(
        $todayStatitics->total_visits,
        $todayStatitics->unique_visits
    );
    $wps_daily_report_sms = str_replace($tags,$values,$wps_daily_report_sms);

    ob_start();
    include WPS_TPL.'notify_email.php';
    $email_content = ob_get_clean();

    $wps_daily_report_email = str_replace($tags,$values,$email_content);

    wps_send_sms(array(
        'to' => $wps_admin_mobile,
        'msg' => $wps_daily_report_sms
    ));
    wps_send_email(array(
        'to' => $wps_admin_email,
        'subject' => 'گزارش بازدید روزانه از وب سایت',
        'message' => $wps_daily_report_email
    ));

}
function wps_send_email($params = array()){
   // $headers[] = 'From: 7learn.com <info@7learn.com>';
   // $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers="";
    wp_mail($params['to'],$params['subject'],$params['message'],$headers);
}
function wps_send_sms($params = array()){

    !class_exists('farapayamak') ? require_once WPS_INC.'farapayamak.class.php' : null;
    $fp = new farapayamak();
    $fp->user = "5689452";
    $fp->pass = "6546554";
    $fp->from = "100020003000";
    $fp->to = $params['to'];
    $fp->msg = $params['msg'];
    $fp->send_sms();

}
