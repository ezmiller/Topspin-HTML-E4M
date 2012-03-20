<?php

	extract($_POST);

	//topspin email for media widget
	
	//set POST variables
	$url = 'http://app.topspin.net/api/v1/fan/create_fan';
	$fields = array(
		'artist_id'=>urlencode($artist_id),
		'fan[source_campaign]'=>urlencode($fan['source_campaign']),
		'fan[email]'=>urlencode($fan['email']),
		'fan[referring_url]'=>urlencode($fan['referring_url']),
		'fan[confirmation_target]'=>urlencode($fan['confirmation_target'])
	);

	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string,'&');

	$url = $url . '?' . $fields_string;

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL,$url);

	//execute post
	$result = json_decode( curl_exec($ch) );

	//close connection
	curl_close($ch);
	
	
	$message = array();
	if($result['success']){
		$message['success'] = true;
	}else{
		$message['error'] = $result['error'];
	}
		
	// and finally return true
	header('Content-type: application/json');
	echo json_encode($message);