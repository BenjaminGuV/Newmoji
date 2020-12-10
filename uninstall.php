<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 *
 * @link       https://github.com/BenjaminGuV/Newmoji
 * @since      1.0.0
 *
 * @package    Newmoji
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

register_deactivation_hook( __FILE__, 'nwmj_newmoji_remove_database' );

function nwmj_newmoji_remove_database() {
	global $wpdb;

	$table_name             = $wpdb->prefix . "newmoji_votes";

	$sql_delete = sprintf( "DROP TABLE IF EXISTS %s;", $table_name );

	$wpdb->query( $sql_delete );
	delete_option("my_plugin_db_version");
}


