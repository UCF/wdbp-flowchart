<?php

Function wdbp_flowchart_main_page(){
	global $wpdb;
	global $wp;

	echo "Welcome to Information Page";

}

add_action( 'admin_menu', 'ucf_devops_rest_add_info_menu' );  
Function wdbp_flowchart_add_info_menu(){


    $page_title = 'Credits and Info';
	$menu_title = "DevOps/Wordpress Config";
	$capability = 'manage_options';
	$menu_slug  = 'ucf_devops_rest_main_menu';
	$function   = 'ucf_devops_rest_main_page';
	$icon_url   = 'dashicons-media-code';
	$position   = 4;
	
	add_menu_page( $page_title,$menu_title,	$capability,$menu_slug,	$function,$icon_url,$position );
	
	$submenu1_slug = 'ucf_devops_rest_manage';
    add_submenu_page( $menu_slug, 'Manage Setup', 'Manage Setup'
		, 'manage_options' , $submenu1_slug , $submenu1_slug);

}

Function wdbp_flowchart_manage(){
	global $wpdb;
	global $wp;
	

	$skip_devops_settings_form = 0;
	// get current URL 
	//$current_url =  home_url(add_query_arg(array($_GET), $wp->request));
	$current_url =  "admin.php?page=" . $_GET['page'];
	$default_tab = "DevOpsSettings";
	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;  

	if ($tab == "DevOpsSettings") {
		if ($skip_devops_settings_form  == 0) {
			print '<h3 class="nav-tab-wrapper"> ';    
			print '<a class="nav-tab nav-tab-active" href="' . esc_html($current_url) . '&tab=DevOpsSettings">DevOps Settings</a> ';  
			print '<a class="nav-tab" href="' . esc_html($current_url) . '&tab=WiqlSettings">Wiql Settings</a> ';
			print '<a class="nav-tab" href="' . esc_html($current_url) . '&tab=QueryIDSettings">Query Settings</a> ';		
			print '</h3>'; 
			echo '<form action="" method="post">
			<table>
			<tr><td><label for="seachlabel">Description:</label></td><td><input type="text" id="description" name="description" size="100" ></td></tr><p>
			<tr><td><label for="seachlabel">PAT Token:</label></td><td><input type="text" id="pat_token" name="pat_token" size="100" ></td></tr><p>
			<tr><td><label for="seachlabel">PAT Exipre:</label></td><td><input type="text" id="pat_expire" name="pat_expire" size="100" ></td></tr><p>
			<tr><td><label for="seachlabel">Organization:</label></td><td><input type="text" id="organization" name="organization" size="100" ></td></tr><p>
			<tr><td><label for="seachlabel">Project:</label></td><td><input type="text" id="project" name="project" size="100" ></td></tr>
			</table>
			<P>
			<input type="submit" value="Add Settings" name="addsettings">';
			echo '<input type="hidden" id="tab" name="tab" value ="' . esc_html($tab) . '">';
			print '<br> </form>';
		} else {
			print "<P>&nbsp;<P>";
		}
		
	} else if ($tab == "WiqlSettings") {
		print '<h3 class="nav-tab-wrapper"> ';    
		print '<a class="nav-tab " href="' . esc_html($current_url) . '&tab=DevOpsSettings">DevOps Settings</a> ';  
		print '<a class="nav-tab " href="' . esc_html($current_url) . '&tab=WiqlSettings">Wiql Settings</a> ';  
		print '<a class="nav-tab nav-tab-active" href="' . esc_html($current_url) . '&tab=QueryIDSettings">Query Settings</a> ';	
	}
	else { //Query Tab 

			
	}

}  


?>
