<?php
/**
* Plugin Name: Brad's Workday Business Process Flowchart
* Plugin URI: https://www.yourwebsiteurl.com/
* Description: Brad's Workday Business Process Flowchart
* Version: 0.11
* Author: Bradley Smith
* Author URI: http://yourwebsiteurl.com/
**/

// Load all the nav menu interface functions.
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';



 
register_activation_hook( __FILE__, 'wdbp_flowchart_activate' );
function wdbp-flowchart_activate(){
	global $wpdb;
	global $wp;
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$sql = "CREATE TABLE " . $wpdb->base_prefix . "wdbp_flowchart (
		client_id			text,
		client_secret	text,
		refresh_token	text,
	PRIMARY KEY  (client_id )		
	)";
	dbDelta( $sql );
	//$wpdb->show_errors();
	$wpdb->flush();
	
	$sql = "CREATE TABLE " . $wpdb->base_prefix . "wdbp_flowchart_bp (
		entry_index		int,
		WID		varchar(128),	
		description		varchar(128),
		organization	varchar(128),
	PRIMARY KEY  (entry_index)		
	)";
	dbDelta( $sql );
	//$wpdb->show_errors();
	$wpdb->flush();

}


function wdbp_flowchart_update(){
	global $wpdb;
	global $wp;
		
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


}
add_action('upgrader_process_complete', 'wdbp_flowchart_update');

require_once( plugin_dir_path( __FILE__ ) . 'includes/workday-bp-graph.php');

require_once( plugin_dir_path( __FILE__ ) . 'includes/admin_menu.php');

?>
