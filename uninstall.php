<?php 
	global $wpdb;
	
	
	$sql = "DROP TABLE " . $wpdb->base_prefix . "wdbp_flowchart;";
	$wpdb->query($sql);
	$wpdb->show_errors();
	$wpdb->flush();
	
		
	$sql = "DROP TABLE " . $wpdb->base_prefix . "wdbp_flowchart_bp;";
	$wpdb->query($sql);
	$wpdb->show_errors();
	$wpdb->flush();


	
	remove_menu_page( 'ucf_devops_rest_main_menu'); 
	remove_menu_page( 'wp_workday_rest_json'); 

?>
