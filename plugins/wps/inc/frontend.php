<?php
function wps_user_visit_callback()
{

    global $wpdb, $table_prefix;
    $user_ip = ip2long( $_SERVER[ 'REMOTE_ADDR' ] );
    $date = date( 'Y-m-d H:i:s' );
    $is_user_visit_site_today = $wpdb->get_var( "SELECT id 
                                                    FROM {$table_prefix}wps_user_visits
                                                    WHERE ip={$user_ip} AND DATE('{$date}') = DATE(date)
                                                     LIMIT 1" );

    if ( intval( $is_user_visit_site_today ) == 0 ) {

        $result = $wpdb->insert( $table_prefix . 'wps_user_visits',
            array (
                'ip'   => $user_ip,
                'date' => $date
            ), array (
                '%d',
                '%s'
            ) );
//            if($wpdb->insert_id){
//
//            }
    }
    $today_visits_exist = $wpdb->get_var( "SELECT id 
                                                FROM {$table_prefix}wps_visits
                                             WHERE DATE('{$date}') = DATE(date)" );
    if ( $today_visits_exist ) {

//                $wpdb->update($table_prefix.'wps_visits',array(
//                'total_visits'  => $today_visits_exist->total_visits + 1,
//                ),
//                    array(
//                        'id'  => $today_visits_exist->id
//                    ));
        $wpdb->query( "UPDATE {$table_prefix}wps_visits SET total_visits = total_visits + 1 WHERE id={$today_visits_exist}" );

        if ( $is_user_visit_site_today == 0 ) {

//                $wpdb->update($table_prefix.'wps_visits',array(
//                    'unique_visits'  => $today_visits_exist + 1,
//                ));
            $wpdb->query( "UPDATE {$table_prefix}wps_visits SET unique_visits = unique_visits + 1 WHERE id={$today_visits_exist}" );

        }

    } else {

        $wpdb->insert($table_prefix.'wps_visits',array (
            'total_visits'  => 1,
            'unique_visits' => 1,
            'date'          => date( 'Y-m-d' )
        ),array(
            '%d',
            '%d',
            '%s'
        ));

    }
}

add_action( 'init', 'wps_user_visit_callback' );
add_action('init','wps_set_user_view');
function wps_set_user_view(){

    if(is_user_logged_in()){
        $currentUser = wp_get_current_user();
        $userViews = wps_get_user_view($currentUser->ID);
        update_user_meta($currentUser->ID,'views',$userViews + 1);
    }
}
function wps_get_user_view($user_ID){
    //if(is_user_logged_in()){
//        $currentUser = wp_get_current_user();
        $views = get_user_meta($user_ID,'views',true);
        return intval($views);
    //}
}
function wps_get_users_by_view(){
    $args = array(
        'meta_key' => 'views',
        'order_by' => 'meta_value_num',
        'order'    => 'ASC'
    );
    $wps_user_view_query = new WP_User_Query($args);
    $results = $wps_user_view_query->get_results();
    echo '<ul>';
    foreach ($results as $user){
        $userData = get_userdata($user->ID);
        echo '<li>'.$userData->first_name.' '.$userData->last_name.'</li>';
    }
    echo '</ul>';
}
if(!function_exists('dd')){
    function dd($data){

        echo '<pre>';
        //var_dump($data);
        echo '</pre>';
        die();

    }
}
