<?php
$current_db_version = get_option('wps_db_version');
if(intval(WPS_DB_VERSION) > intval($current_db_version)){
    wps_db_upgrade();
}
function wps_db_upgrade(){
    global $wpdb;

//create wps_user_visits tbl
    $wps_user_visits_tbl_prefix = $wpdb->prefix.'wps_user_visits';
    $wps_user_visits_tbl = "CREATE TABLE IF NOT EXISTS `".$wps_user_visits_tbl_prefix."` (
  `id` bigint(20) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;";

    $wps_user_visits_tbl_alter = "ALTER TABLE `".$wps_user_visits_tbl_prefix."`
 ADD PRIMARY KEY (`id`), ADD KEY `date` (`date`);ALTER TABLE `".$wps_user_visits_tbl_prefix."`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1";

//create wps_visits
    $wps_visits_tbl_prefix = $wpdb->prefix.'wps_visits';
    $wps_visits_tbl= "CREATE TABLE IF NOT EXISTS `".$wps_visits_tbl_prefix."` (
  `id` bigint(20) NOT NULL,
  `total_visits` bigint(20) NOT NULL,
  `unique_visits` bigint(20) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;";

    $wps_visits_tbl_alter = "ALTER TABLE `".$wps_visits_tbl_prefix."`
 ADD PRIMARY KEY (`id`), ADD KEY `date` (`date`);ALTER TABLE `".$wps_visits_tbl_prefix."`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($wps_user_visits_tbl);
    dbDelta($wps_user_visits_tbl_alter);
    dbDelta($wps_visits_tbl);
    dbDelta($wps_visits_tbl_alter);
    
    update_option('wps_db_version',WPS_DB_VERSION);
}
function wps_init_options(){
//    $options = array(
//        'api_key' => '46f4f489we9f4asf98sdg4we98gg'
//    );
//
//    update_option('wps_options',$options);
}