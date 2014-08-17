<?php
/*
this is home.php:  logo, help, register, language

multi-language

carrier through ajax

*/

var_dump('post: ' , $_POST);

chdir('../');
DEFINE("DOCROOT", getcwd());
require_once(DOCROOT . '/includes/functions/firstPageFunctions.php');

$flds = array('language', 'country', 'tel', 'carrier', 'pin');

resetValues($flds);










?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Track me</title>
	
    <style>
		body {
			 margin: 0;
			 padding: 1em 0;
			 background: #beige;
			 font-family: Georgia, Times New Roman, serif;
		}

		#container {
			 width: 30%;
			 background: #fff;
			 color: #555;
			 border: 3px solid #ccc;
			 -webkit-border-radius: 10px;
			 -moz-border-radius: 10px;
			 -ms-border-radius: 10px;
			 border-radius: 10px;
			 border-top: 3px solid #ddd;
			 padding: 5em 2em;
			 margin: 0 auto;
			 -webkit-box-shadow: 3px 7px 5px #000;
			 -moz-box-shadow: 3px 7px 5px #000;
			 -ms-box-shadow: 3px 7px 5px #000;
			 box-shadow: 3px 7px 5px #000;
		}

		ul {
		 list-style: none;
		 padding: 0;
		}

		ul > li {
		 padding: 0.12em 1em
		}

		label {
		 display: block;
		 float: left;
		 width: 130px;
		}

		input, select {
		 font-family: Georgia, Serif;
		}
		   
    </style>
	
	<script>
	
	function changeCarrier()
	{
		document.getElementById('carrier').
	
	}
	
	</script>

</head>
<body>
todo: logo, language, register, help <br>

	<h1>TRACK Me</h1>
	
	
  <div id="container">
    <h3>REGISTER</h3>

  </div>
	
	<br>
	--------------- UNDER CONSTRUCTION ------------------------------------
	<br>
	<p>.</p>
	<pre>
	SUMMARY:
	1- This program (trackMe) will check your location periodically. Default setting is every 10 seconds. Your new location will be added to our database whenever we detect a change. 
	2- Program tracks for a limited time. Default setting is up to 1 hour. 
	3- Tracking stops anytime you close this window.
	
	
	4- Tracking maynot work properly because of lost internet connection, server issues, etc. Things to do: We will develop a plan to keep statistics about the failures.
	
	5- Once a month, we will delete the records more than 90 days. You can ask us to delete them earlier or keep them longer.
	6- We will give copy of your tracking records to the people that you authorized.	
	7- We may give copy of your tracking records to law enforcement if they ask them in official way. This action does not require your authorization.
	
	8- You can contact us for customized service. You can use "contact us" link and form to contact us.

	WARNINGS:
	1- WARNING: Every location check and any communication between your device and our servers are data usage. This might increase your bills (internet bill, cell phone bill, etc.)  if you have limited data plans with your service providers or carriers.
	
	2- WARNING: We don't accept any kind of liability! 
		Whatever we mentioned above are our intention to do. We cannot promise a perfect service.
		Most technologies and equipment that we use are beyond our control. 
		Your devices, our devices, our servers may be hacked, may be stolen, etc.
		We cannot promise error-free or fault-free service.
		Free trials are available. 
		If you like it use it! If you don't like it, don't use it!
		
	3- You are legally responsible for tracking that you asked for. Not us.
	If you track somebody, you might face legal penalty.
	
	</pre>
	<br>

	<br>

</body>
</html>
