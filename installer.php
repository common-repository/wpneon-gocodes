<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

function wsc_gocodes_install() {

	//***Installer variables***
	global $wpdb;
	$table_name             = $wpdb->prefix . "wsc_gocodes";
	$charset_collate        = $wpdb->get_charset_collate();
	$demotarget             = "http://wpneon.com/";
	$demokey                = "wpneonsite";

	//***Installer***
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
		$sql = "CREATE TABLE IF NOT EXISTS $table_name(
			id mediumint(11) NOT NULL AUTO_INCREMENT,
			target varchar(255) NOT NULL,
			key1 varchar(255) NOT NULL,
			docount int(1) NOT NULL,
			hitcount mediumint(15) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$insert = "INSERT INTO " . $table_name . " (target, key1) " . "VALUES ('" . sanitize_text_field( $demotarget ) . "','" . sanitize_text_field( $demokey ) . "')";
		$results = $wpdb->query( $insert );
		add_option( "wsc_gocodes_url_trigger", "go" );
	}
}

?>