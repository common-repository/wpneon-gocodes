<?php
/*
Plugin Name: GoCodes 2 by WPNeon
Plugin URI: http://wpneon.com/gocodes-wordpress-redirection-plugin/
Description: Create shortcut URLs to anywhere on the internet, right from your Wordpress Admin. When upgrading, be sure to read the <a href="http://wordpress.org/extend/plugins/gocodes/other_notes/">upgrade notes.</a>
Author: WPNeon
Author URI: http://wpneon.com
Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}


define( "GOCODES_URL", admin_url( 'tools.php?page=gocodes' ) );





//***** Hooks *****
register_activation_hook( __FILE__, 'wsc_gocodes_install' ); //Install
add_action( 'init', 'wsc_gocodes_query' ); //Redirect
add_action( 'admin_menu', 'wsc_gocodes_add_pages' ); //Admin pages
//***** End Hooks *****



//***** Redirection *****
function wsc_gocodes_query() {
	global $wpdb;
	$table_name = $wpdb->prefix . "wsc_gocodes";
	$request = $_SERVER['REQUEST_URI'];
	if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
		$request = substr( $_SERVER['PHP_SELF'], 1 );
		if ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] != '' ) {
			$request .= '?' . $_SERVER['QUERY_STRING'];
		}
	}
	if ( isset( $_GET['gocode'] ) ) {
		$request = '/go/' . sanitize_text_field( $_GET['gocode'] ) . '/';
	}
	$url_trigger = esc_html( get_option("wsc_gocodes_url_trigger") );
	$nofollow    = esc_html( get_option("wsc_gocodes_nofollow") );
	if ( $url_trigger == '' ) {
		$url_trigger = 'go';
	}
	if ( strpos( '/' . $request, '/' . $url_trigger . '/' ) ) {
		$gocode_key = explode( $url_trigger . '/', $request );
		$gocode_key = $gocode_key[1];
		$gocode_key = str_replace( '/', '', $gocode_key );
		$gocode_db  = $wpdb->get_row( "SELECT id, target, key1, docount FROM $table_name WHERE key1 = '$gocode_key'", OBJECT );
		$gocode_target = $gocode_db->target;
		if ( $gocode_target != "" ) {
			if ( $gocode_db->docount == 1 ) {
				$update = "UPDATE " . $table_name . " SET hitcount=hitcount+1 WHERE id='$gocode_db->id'";
				$results = $wpdb->query( $update );
			}
			if ( $nofollow != '' ) { 
				header( "X-Robots-Tag: noindex, nofollow", true );
			}
			wp_redirect( $gocode_target, 301 );
			exit;
		} else { 
			$badgckey = get_option('siteurl');
			wp_redirect( $badgckey, 301 );
			exit;
		}
	}
}
//***** End Redirection *****


if ( is_admin() ) {
	include( plugin_dir_path( __FILE__ ) . '/installer.php' );
	include( plugin_dir_path( __FILE__ ) . '/menus.php' );
}


//Just a boring function to insert the menus
function wsc_gocodes_add_pages() {
	add_menu_page( 'GoCodes', 'GoCodes', 'manage_options', 'gocodes', 'wsc_gocodes_managemenu', '', null );
	add_submenu_page( 'gocodes', 'GoCodes Links', 'Links', 'manage_options', 'gocodes', 'wsc_gocodes_managemenu' );
	add_submenu_page( 'gocodes', 'GoCodes Settings', 'Settings', 'manage_options', 'gocodes-settings', 'wsc_gocodes_optionsmenu' );
}


//***** Text Truncation Helper Function *****
function wsc_gocodes_truncate( $text ) {
	if ( strlen( $text ) > 79 ) {
		$text = $text . " ";
		$text = substr( $text, 0, 80 );
		$text = $text . "...";
		return $text;
	} else {
		return $text;
	}
}

//***** Add Item to Favorites Menu *****
function wsc_gocodes_add_menu_favorite( $actions ) {
	$actions[GOCODES_URL] = array( 'GoCodes', 'manage_options' );
	return $actions;
}
add_filter( 'favorite_actions', 'wsc_gocodes_add_menu_favorite' ); //Favorites Menu



/*
Copyright 2008 Matt Harzewski

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

?>
