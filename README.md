#[Topspin Powered HTML E4M]

##Overview
Quickly create an HTML and jQuery powered Topspin Email For Media Widget. Benefits include no Flash reliance, better mobile styling and completely custom interface. The main methods include jQuery Form Plugin, cURL, HTML5 form markup and a little bit of Javascript.    

Making your own custom HTML E4M doesn't get any easier than this!

Working Demo: http://www.eyesandearsentertainment.com/htmle4m/

---
 
##Installation

###Include

````html
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.form.js"></script>
````

###HTML Form
This markup provides the necessary visible and hidden inputs to properly relay information to the Topspin REST API. Change the `artist_id`, `fan[source_campaign]`, and `fan[referring_url]`, `fan[confirmation_target]`. In order to provide the `fan[source_campaign]`, you will need a relevant Topspin account and an Email For Media widget created within the platform. To locate the `fan[source_campaign]`, simply do the following:

1. Find the widget_id=”" url in your embed code
2. Paste that into a browser
3. Find the url inside the <campaign> tag (ex. http://app.topspin.net/api/v1/artist/1051/campaign/10150220)

In order to provide the `fan[confirmation_target]`, you will need to create a new Topspin Labs Download Anywhere URL. Go to http://labs.topspin.net/downloadanywhere/ and enter your credentials. Once generated, replace the `fan[confirmation_target]` value with your unique Download Anywhere URL.

````html
<form id="signup" action="assets/includes/send.php" method="post" >
	<input id="email"  name="fan[email]" type="email" value="enter your email here" placeholder="enter your email here" class="erase email" />	
	<input name="artist_id" value="1051" id="artist_id" type="hidden">
	<input name="fan[source_campaign]" value="http://app.topspin.net/api/v1/artist/1051/campaign/10150220" id="source_campaign" type="hidden">
	<input name="fan[referring_url]" value="http://www.eyesandearsentertainment.com" id="referring_url" type="hidden">
	<input name="fan[confirmation_target]" value="http://labs.topspin.net/downloadanywhere/confirm.php?sessionid=188a0d0a116380c2180c37a7dcb33e1e" id="confirmation_target" type="hidden">			
	<input id="submit" name="submit" type="submit" value="submit" />
</form>
````

###jQuery Form Method

Utilize jQuery form to submit the form via AJAX. Includes a basic success function that returns "Thanks, Check Your Inbox!" when properly submitted.

````javascript
// ajax submit the form
$('#signup').bind('submit', function(e) {
	e.preventDefault();
	$(this).ajaxSubmit({
		success: function() { 
			$('#email').val('Thanks, Check Your Inbox!'); //return a thank you message 
			$('#email, #submit').prop('disabled', true); //disable the form upon submit
		}			
	});
});
````

###jQuery Input Clear

Include to handle basic active / inactive states on the HTML form. Provides a clean way to remove the placeholder text when in focus and add it back in when default.

````javascript
// clear out our form on focus
$('input.erase').each(function() {
	$(this)
		.data('default', $(this).val())
		.addClass('inactive')
		.focus(function() {
			$(this).removeClass('inactive');
			if ($(this).val() == $(this).data('default') || '') {
				$(this).val('');
			}
		})
		.blur(function() {
			var default_val = $(this).data('default');
			if ($(this).val() == '') {
				$(this).addClass('inactive');
				$(this).val($(this).data('default'));
			}
	});
});
````

###cURL PHP

Extract POST variables from the HTML form, open connection to Topspin API and send relevant fan email information. This will be the function that the HTML form action submits to over AJAX.

````php
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
````

---

##Copyright and License
Dual licensed under the MIT and GPL licenses:

* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html

You may use the Topspin HTML E4M under the terms of either the MIT License or the GNU General Public License (GPL) Version 2.
The MIT License is recommended for most projects. It is simple and easy to understand and it places almost no restrictions on what you can do with the plugin.

If the GPL suits your project better you are also free to use the plugin under that license.

You don't have to do anything special to choose one license or the other and you don't have to notify anyone which license you are using. You are free to use the Topspin HTML E4M in commercial projects 

---

