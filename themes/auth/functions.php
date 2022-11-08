<?php
function numberize($str)
{
    $char = str_split($str);
    $out_put = '';
    foreach ($char as $item) {
        if (intval($item) or $item === '0') {
            $out_put .= $item;
        } else {
            $out_put .= ord(strtolower($item));
        }
    }
    return $out_put;
}

function generateHeader()
{
    $secret_key = '1@1trb!f42+=!x%nx991+_)21o1dik_#of_nj+-=lpmn0(jckx';
    $milliseconds = strval(round(microtime(true) * 1000));
    $hash = substr(hash('sha256', $secret_key . $milliseconds), 0, 20);
    return ['token' => numberize($hash), 'now' => intval($milliseconds)];

}









add_filter('template_redirect', function () {
  ob_start(null, 0, 0);
});










// if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
//     // We're on the login page!
// }










function check_is_haco_member($phone_number) {
    $sourceHeader = generateHeader();

    $url = 'https://api.hacoupian.net/api/v1/auth/check-employee/';



    $args = array(
     'headers'     => array(
             'x-clock'  => $sourceHeader['now'],
             'x-token' => $sourceHeader['token']
     ),
     'body' => json_encode(
            array(
                'mobile'     => strval($phone_number),
            )
        ),
    );

    $response = wp_remote_post( $url, $args );


    $response_body = json_decode(wp_remote_retrieve_body( $response ), true);

    $return = $response_body;

    return $return['message'];

}





function send_sms_code($phone_number, $code) {

    $url = 'http://messenger.haco.dc/send-sms';



    $args = array(
     'headers'     => array(
        'auth-secret'  => 'aEvPFiZVr$&QLutJ9!CCnp&#Y(ecy%$V1))&!DhP!sxQ4cE3EWktNDQf0z8Ue8^W',
     ),
     'body' => json_encode(
            array(
                'phone'     => strval($phone_number),
                'driver'     => 'Vesal',
                'message'     => strval($code),
            )
        ),
    );

    wp_remote_post( $url, $args );


    // $response_body = json_decode(wp_remote_retrieve_body( $response ), true);

    // $return = $response_body;

    // return $return['message'];

}










add_filter( 'authenticate', 'check_if_activated', 50);
function check_if_activated($user)
{
    if (isset($user->roles[0]) && $user->roles[0] == 'administrator') { return $user; }

    // If we have an error, no need to check the activation key
    // (Wrong credentials, for instance)
    if (is_wp_error($user))
    {
        return $user;
    }
    // Checks the meta and returns an error if needed
        $validation_key = get_user_meta($user->ID, 'is_actived', true);
        return !empty($validation_key) ? $user : new WP_Error('your_plugin_error_code', 'این اکانت غیر فعال است');
}





function new_modify_user_table( $column ) {
   $column['userpoints'] = 'وضعیت';
   return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );
function new_modify_user_table_row( $val, $column_name, $user_id ) {
  switch ($column_name) {
    case 'userpoints' :
     if (get_user_meta($user_id, 'is_actived', true) == 1 || user_can( $user_id, 'manage_options' )) {return 'فعال';}
     break;
    default:
  }
 return $val;
}
add_filter('manage_users_custom_column','new_modify_user_table_row', 1, 3 );








// add_action( 'user_register', 'myplugin_registration_save', 10, 1 );

// function myplugin_registration_save( $user_id ) {

//     if ( isset( $_POST['first_name'] ) )
//         update_user_meta($user_id, 'first_name', $_POST['first_name']);

// }




function wpse_44020_logout_redirect( $logouturl, $redir )
{
    return $logouturl . '&amp;redirect_to=' . home_url('signin');
}
add_filter( 'logout_url', 'wpse_44020_logout_redirect', 10, 2 );





add_filter( 'login_url', 'my_login_page', 10, 3 );
function my_login_page( $login_url, $redirect, $force_reauth ) {
    return home_url( '/signin/?redirect_to=' . $redirect );
}