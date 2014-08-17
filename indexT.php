<?php
/*
logo, help, register, language

multi-language

carrier through ajax

*/

chdir('..');
DEFINE("DOCROOT", getcwd());
require_once(DOCROOT . '/includes/config4.php');
require_once(DOCROOT . '/includes/classes/countries.php');
require_once(DOCROOT . '/includes/classes/smallClasses.php');
require_once(DOCROOT . '/includes/functions/firstPageFunctions.php');

$flds = array('language', 'country', 'tel', 'carrier');

resetValues($flds);

//** getting  language from cookies or DB or local storage
if(!empty($_COOKIES))
{
	getCookies($flds);	
}


// get countries
$oCountries = new getCountries;
$oCountryProperties = $oCountries->getProperties();
unset($oCountries);

// var_dump('countries', $oCountryProperties);
// var_dump('countries', $oCountryProperties->countries);

//** replace this with error class div
if(!empty($oCountryerrors))
{
	var_dump('errors', $oCountryerrors);
}
if(!empty($oCountryProperties->countries))
{
	$params = array('array' => $oCountryProperties->countries, 'selected' => $country);
	$oCountries = new options($params);
	$oCountryProperties = $oCountries->getProperties();
	unset($oCountries);
	
	//** replace this with error class div
	if(!empty($oCountryerrors))
	{
		var_dump('errors', $oCountryerrors);
	}
	// now \$oCountryProperties->options is string for DOM element
	
}





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
			 background: beige;
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
		
		#notes {
			color: tan;
			font-size: 0.7em;
		}
		   
    </style>
	
	<script type="application/javascript">

	
	
	//** get carriers for selected country
	function changeCarrier(countryId)
	{
// alert('countryId: ' + countryId);
		document.getElementById('carrier').options.length = 0;
		getCarriers(countryId);
	}
	
	// ajax call to get carriers
	function getCarriers(countryId)
	{

			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				 xmlhttp=new XMLHttpRequest();
			}
			else
			{	// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText)
					{
						var carriers = JSON.parse(xmlhttp.responseText);
						document.telform.carrier.options[0] = new Option('', '',  false, false);
						var num = 1;
						for (key in carriers)
						{
							document.telform.carrier.options[num] = new Option(carriers[key], key,  false, false);
							num++;
						}
					}

				}
			}
			
			//** ad token for security
			var cUrl = '../includes/ajax/getCarriers.php?country=' + countryId;

			xmlhttp.open("GET", cUrl, true);
			xmlhttp.setRequestHeader("charset", "utf-8");
			xmlhttp.send();
		
	}
	
	// ajax call to send tel number, country and carrier	
	function beforeSubmit()
	{
	
		var pinNumber = document.getElementById('pinNumber').value;

		if(pinNumber)
		{
			sendPin(pinNumber);
			return;
		}
		var country = document.getElementById('country').value;
		var phoneNumber = document.getElementById('phoneNumber').value;
		var carrier = document.getElementById('carrier').value;
		if(!country || !carrier || !phoneNumber)
		{
			alert('Dont leave country or carrier or phoneNumber blank');
			return;
		}
		
		var oParams = {		
				'country' : country,
				'phoneNumber' : phoneNumber,
				'carrier' : carrier
			};
		var jParams = JSON.stringify(oParams);
//  alert('jParams: ' + jParams);
			
			// ajax call starts
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				 xmlhttp=new XMLHttpRequest();
			}
			else
			{	// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{

document.getElementById("status").innerHTML = xmlhttp.responseText;
document.getElementById("pinNumber").style.visibility = "visible";
				}
			}
			
			//** ad token for security
			var cUrl = '../includes/ajax/includes/ajax_sendSms2.php?params=' + jParams;

			xmlhttp.open("GET", cUrl, true);
			xmlhttp.setRequestHeader("charset", "utf-8");
			xmlhttp.send();	
			// ajax call ends

	}
	
	// ajax call to send pin number for verification and process response	
	function sendPin(pinNumber)
	{
			// ajax call starts
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				 xmlhttp=new XMLHttpRequest();
			}
			else
			{	// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					// if pin is confirmed
					if(xmlhttp.responseText == 'show start button')
					{
						document.getElementById("telform").style.visibility = "hidden";
						document.getElementById("startButton").style.visibility = "visible";	
					}
					else
					{
// alert('pinNumber-261: ' + pinNumber);					
					document.getElementById("status").innerHTML = xmlhttp.responseText;
					document.getElementById("pinNumber").style.visibility = "visible";	
					}
				}
			}
			
			//** ad token for security
			var cUrl = '../includes/ajax/includes/ajax_sendSms2.php?pin=' + pinNumber;

			xmlhttp.open("GET", cUrl, true);
			xmlhttp.setRequestHeader("charset", "utf-8");
			xmlhttp.send();	
			// ajax call ends
			
	}
/*
	//
	function startTracking()
	{
		var gl = navigator.geolocation;
		if (gl) {
			document.getElementById('status').innerHTML = '<h3>Tracking started... You are being tracked.<br> If you want to stop tracking close this window.</h3>';
			
			startTime = new Date().getTime();
			startTime = startTime / 1000;
			//*** refreshIntervalId = setInterval(trackMe, 2000);
			// trackMe();
		} else {
			alert("Cannot get your location. Try again if your browser supports geolocation. ");
		}
	}			

	function trackMe()
	{
		curTime = new Date().getTime();
		curTime = curTime / 1000;
		timePassed = curTime - startTime;
		timePassed = parseInt(timePassed);
alert('timePassed: ' + timePassed);		
		
		// if time passed > 10 seconds
		if(timePassed > 10)
		{
			clearInterval(refreshIntervalId);
		}
		
		gl = navigator.geolocation;
		gl.getCurrentPosition(sendAddress, displayError);	
	}
	
	// getting address from Google Map Api using current lat and lng
	function sendAddress(position)
	{
			var lat = position.coords.latitude;
			var lng = position.coords.longitude;
// alert('lat-lng:' + lat + '-' + lng);
	
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				 xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{

var timestamp = xmlhttp.responseText;
var date = new Date(timestamp*1000);
var serverRespondTime = date.toISOString().match(/(\d{2}:\d{2}:\d{2})/)
alert('serverRespond  hr: ' + serverRespondTime[1]);				
					// scr = xmlhttp.responseText;
					scr = 'Seconds passed: ' + timePassed ;
					scr += '<br>Server Last Respond: ' + serverRespondTime;
					
					document.getElementById("cLocation").innerHTML=scr;
				}
			}
			
			//*** tel number
			var cUrl = 'http://yabul.com/track/includes/ajax_trackMe.php?tel=' + tel + '&lat=' + lat + '&lng=' + lng + '&time=' + curTime;
			xmlhttp.open("GET", cUrl, true);
			xmlhttp.send();
		
	}
		
		
	function displayError(positionError) 
	{
		alert('Sorry an error occurred during getting your location.');
		console.log('Location error: ' + error);
	}		
*/ 	
	</script>

</head>
<body>
todo: logo, language, register, help <br>

	
  <div id="container">
    <h1>TRACK Me</h1>
	<p id="status"></p>
	<p id="startButton" style="visibility:hidden"><a id="startTracking" onclick="startTracking()" href="#">START TRACKING</a></p>
	<div id="cLocation"></div>
	
    <form name="telform" action="#" method="post">
     <ul>
      <li>
       <label for="pinNumber">Pin Number</label>
       <input type="number" name="pinNumber" id="pinNumber" style="visibility:hidden" />
	  </li>
      <li> 	 
      <li>
      <label for="country">country</label>
       <select name="country" id="country"  onchange="changeCarrier(this.value);" required />
			<option></option>
			<?php echo $oCountryProperties->options;  ?>
	   </select>
      </li>	 
      <li>
       <label for="phoneNumber">Phone Number</label>
       <input type="number" name="phoneNumber" id="phoneNumber" required />
	  </li>
      <li>
      <label for="carrier">carrier</label>
       <select name="carrier" id="carrier" required >
	   </select>
      </li>
     <li><input type="button" name="submitForm" id="submitForm" value="SUBMIT" onclick="beforeSubmit()" /></li>
    </ul>
   </form>
  </div>
	
	<br>
	--------------- UNDER CONSTRUCTION ------------------------------------
	<br>
	<h1>WARNINGS</h1>
	<pre>
	SUMMARY:
	1- If you click 'START TRACKING', this program (trackMe) will check your location periodically. Default setting is every 10 seconds. Your new location will be added to our database whenever we detect a change. 
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
