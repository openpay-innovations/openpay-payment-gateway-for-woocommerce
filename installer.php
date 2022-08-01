<?php
global $wpdb;
$table_name = $wpdb->prefix . "openpay";
$openpay_db_version = '1.0.0';
$charset_collate = $wpdb->get_charset_collate();

if ( $wpdb->get_var( $wpdb->prepare("SELECT TABLES LIKE {$wpdb->prefix}openpay") ) != $table_name ) {

    $sql = "CREATE TABLE $table_name (
            id int(9) NOT NULL AUTO_INCREMENT,
            `plan_id` varchar(255) NOT NULL,
            `order_id` int(128) NOT NULL,
            PRIMARY KEY  (ID)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    add_option( 'openpay_db_version', $openpay_db_version );
}