<?php


function wdbp_get_token(){
	global $wpdb, $access_token;
   

    // ucf4- 
	//$client_id = "ZGNhMWFjZjQtOTkyYS00N2VmLTg2MzEtNDJiZjExNjk0MDA4";
    //$client_secret = "xt1ikmxvz7cpwjjwow5x0tgf7cx9x4crgzcpdj3lpp0oh3s9t6qaof21m5z6apkprvlocrviwoan3yv8rw65eman50hiomkufdw";
    //$refresh_token = "rvnq1duuvj2mnwj96bp53df2nkb0urmr1ax5a1sgg39yk72ymloccqo0uewb17opei3ems750xjqg7u88ehd003m3lra3hnw4m1"; # ucf4
    //$token_url = "https://wd2-impl-services1.workday.com/ccx/oauth2/ucf4/token" ; # ucf4
    //$raw_body = "grant_type=refresh_token&refresh_token=rvnq1duuvj2mnwj96bp53df2nkb0urmr1ax5a1sgg39yk72ymloccqo0uewb17opei3ems750xjqg7u88ehd003m3lra3hnw4m1"; #ucf4
    
    $client_id = "YWEyNmUyNjYtZTUxMC00MWMxLTlhYjYtOGRhMThmYWQ3NDY1";  # ucf
    $client_secret = "h7egrv04x0pl1rsa1zeuwh3gca0e3gbiqy16weepn1ruv3ucznrfgbibjn77563toexlm16ucqgr71x33cer10gw172343cey1u";  #ucf  
    $token_url = "https://services1.myworkday.com/ccx/oauth2/ucf/token";
    $refresh_token="Zhsiffbuuvzihwjqr84g9ggopefkfeqratgvv1v7skofpf9xw7w7f2y9cpnjyr1dfsfclnjuja7qsr81mt35j8k33c8rccdu97g"; # ucf
    $raw_body = "grant_type=refresh_token&refresh_token=Zhsiffbuuvzihwjqr84g9ggopefkfeqratgvv1v7skofpf9xw7w7f2y9cpnjyr1dfsfclnjuja7qsr81mt35j8k33c8rccdu97g";  #ucf
    
    //not needed $headers = '{"Content-type": "application/x-www-form-urlencoded"}';
   
	$body = array(
		'client_id' 	=> $client_id ,
        'client_secret'	=> $client_secret ,
        'grant_type' 	=> 'refresh_token' ,
        'refresh_token'	=> $refresh_token );
	
	$headers[] = 'Accept: application/json';
	
	$curl = curl_init($token_url); // we use curl to get the results - easy

	curl_setopt($curl, CURLOPT_POST, TRUE);  
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
	$data = curl_exec($curl);
	
	//print($data);
	
	$myjson  = json_decode($data , false );
	
	if (isset($myjson->{'error'})) {
		// handle error
		print("Error:" . $myjson->{'error'} . "\n");
		$access_token = "";
		curl_close($curl);
		return;
	}
	$access_token = $myjson->{'access_token'};
	
	curl_close($curl);
	
		
		
		

    
}

function wdbp_get_data($url) {
	
	global $wpdb, $access_token;
	   
	   
	$datasource_header = array (
		'Authorization' => 'Bearer ' . $access_token,
        'Content-Type'	=> 'text/xml' 
		);
	
	$headers[] = 'Authorization: Bearer ' . $access_token;
	
	#print_r($datasource_header);
	
	$curl = curl_init($url); // we use curl to get the results - easy

	curl_setopt($curl, CURLOPT_POST, 0);  
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE );
	$data = curl_exec($curl);
	
	$ndata = str_replace(":", "_", $data) ;
	#print($ndata);
	$xml_data=simplexml_load_string($ndata) or die("Error: simplexml_load_string() Cannot create object");
	return($xml_data);
}

function wdbp_header(){
	echo '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Flowchart.js Example Project</title>
</head>
<body>


<style>
.pre {
    display:       block;
    unicode-bidi:  embed;
    font-family:   monospace;
    white-space:   pre;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowchart/1.6.6/flowchart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-countdown/2.1.0/js/jquery.plugin.js"></script>

';


}

function wdbp_process($xml_data){
	
	$wd_report_entry = $xml_data->{'wd_Report_Entry'};
	#print_r($wd_report_entry);
	$wd_Definition =  $wd_report_entry->{'wd_Definition'};
	$wd_InitiatingGroups =  $wd_report_entry->{'wd_InitiatingGroups'};
	
	$wd_steps = $wd_report_entry->{'wd_Steps'};
	
	$sizeof_steps = sizeof($wd_steps);
	#print("Size of steps: " . strval($sizeof_steps));
	wdbp_header(); // prints html header



	$script_code = " 'st=>start: Start" . '\n' . "'\n";
    $script_code = $script_code .  "+ 'e=>end" . '\n' . "'\n";
	$script_path = "+ 'st";
	$prev_cond = 0;
//	for ($i = 0; $i < 4; $i++ ) {
//		print_r($wd_steps[$i]);
//	}
	for ($i = 0; $i < $sizeof_steps; $i++ ) {
		// elements are:
		//[wd_Step] => Hire (Default Definition) step b - Action
		//[wd_Type] => Action
		//[wd_Task] => Change Organization Assignments
		//[wd_If] => Parent process is pending or has completed but does not have a Change Organization task already started.? (Workday Owned)
		//[wd_Groups] => Initiator
		
		if(isset($wd_steps[$i]->{'wd_Step'}))
			$wd_Step = str_replace("'", "\\'", str_replace("Hire (Default Definition) ", "", $wd_steps[$i]->{'wd_Step'}));
		else 
			$wd_Step = "";
		if(isset($wd_steps[$i]->{'wd_Type'}))
			$wd_Type = $wd_steps[$i]->{'wd_Type'};
		else 
			$wd_Type = "";
		if(isset($wd_steps[$i]->{'wd_Task'}))
			$wd_Task = str_replace("'", "\\'", $wd_steps[$i]->{'wd_Task'});
		else 
			$wd_Task = "";
		if(isset($wd_steps[$i]->{'wd_If'})) {
			//$wd_If = str_replace("'", "\\'", $wd_steps[$i]->{'wd_If'});
			$words_array =  explode ( " " ,  str_replace("'", "\\'", $wd_steps[$i]->{'wd_If'}));
			$strv = "";
			for($j = 0; $j < sizeof($words_array); $j++ ) {
				if(($j != 0) && (($j %5)==0)) 
					$strv = $strv . '\n';
				$strv = $strv . $words_array[$j] . " ";
			}
			$wd_If = $strv;
		}else 
			$wd_If = "";
		if(isset($wd_steps[$i]->{'wd_Groups'})) {
			$words_array =  explode ( " " ,  str_replace("'", "\\'", $wd_steps[$i]->{'wd_Groups'}));
			$strv = "";
			for($j = 0; $j < sizeof($words_array); $j++ ) {
				if(($j != 0) && (($j %5)==0)) 
					$strv = $strv . '\n';
				$strv = $strv . $words_array[$j] . " ";
			}
			$wd_Groups = $strv;
		} else 
			$wd_Groups = "";
		
		if($wd_If != "") {	// we have an if condition
			if($prev_cond == 1) {
				$script_path = $script_path . "cond" .  strval($i-1) . "(no)->cond" . strval($i) . '\n' . "'\n+ '";
				$script_path = $script_path . "op" . strval($i-1) ;
			}
			$script_code = $script_code . "+ 'cond" . strval($i) . "=>condition: " . $wd_If . '\n' . "'\n";
			$script_code = $script_code . "+ 'op" . strval($i) . "=>operation: " . $wd_Step . '\n' . $wd_Type . '\n' . $wd_Task . '\n' . "'\n";
			$script_path = $script_path . "->cond" . strval($i) . '\n' . "'\n+ '";
			$script_path = $script_path . "cond" .  strval($i) . "(yes)->op" . strval($i) . '\n' . "'\n+ '";
			$prev_cond = 1;
		} else {
			if($prev_cond == 1) {
				$script_path = $script_path . "cond" .  strval($i-1) . "(no)->op" . strval($i) . '\n' .  "'\n+ '";
				$script_path = $script_path . "op" . strval($i-1) . "->op" . strval($i) ;
			} else {
				$script_path = $script_path . "->op" . strval($i);
			}
			$script_code = $script_code . "+ 'op" . strval($i) . "=>operation: " . $wd_Step . '\n' . $wd_Type . '\n' . $wd_Task . '\n' . "'\n";
			
			$prev_cond = 0;
		}
	}
	if($prev_cond == 1) {
		$script_path = $script_path . "cond" .  strval($i-1) . "(no)->op" . strval($i-1) . '\n' .  "'\n+ '";
	}
	//$script_path = $script_path . "op" . strval($i-1) . "->e" . '\n' . "'";
	//echo "'\n;\n";
	$script_path = $script_path . "'";
	echo '<div id="diagram"></div>' . "\n";



	
	print("<script>\n");
	print("var code = " . $script_code . $script_path ."\n;\n");
	print("var diagram = flowchart.parse(code); \n");
	print("diagram.drawSVG('diagram',{theme: 'hand'}); \n");
	print("</script></body>\n");
	return;

	
}

function wdbp_flowchart($atts = [], $content = null) {
	
	
	if(isset($atts['wid']))
		$wid = sanitize_text_field($atts['wid']);
	else
		return;
	
	$access_token = "";
	wdbp_get_token($wid);
	#print($access_token);

	$wd_raas = "https://services1.myworkday.com/ccx/service/customreport2/ucf/mi343521/UCF_Extract_Business_Process_Definitions_to_XPDL?Definition%21WID=545f239323e30101561beb61c19a0000&format=simplexml";
	$xml_data = wdbp_get_data($wd_raas);

	//print_r($xml_data);

	wdbp_process($xml_data);
}


add_shortcode ('wdbp_flowchart' , 'wdbp_flowchart');
?>