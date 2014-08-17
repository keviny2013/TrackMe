<?php
/*
logo, help, register, language
multi-language

device number, navigator.platform, navigator.userAgent, IP address, 
screen.width, screen.size
cookies, local storage
Geolocation API
Geolocation.watchPosition()
https://developer.mozilla.org/en-US/docs/Web/API/Geolocation.watchPosition

////
		// motzilla website for developers
			function getBrowserName () {

				var
					aKeys = ["MS", "Firefox", "Safari", "Chrome", "Opera"],
					sUsrAg = navigator.userAgent, nIdx = aKeys.length - 1;

				for (nIdx; nIdx > -1 && sUsrAg.indexOf(aKeys[nIdx]) === -1; nIdx--);

				// return nIdx
				var name = aKeys[nIdx];
				browser = [ nIdx , name, navigator.userAgent];
				return browser;

			}

			var browser = getBrowserName();
			alert('Browser: ' + browser[0] + '---' + browser[1] + "\n" + navigator.platform);
			var str = browser[0] + ' : ' + browser[1] + '<br>' + browser[2];
			
			document.getElementById('motzilla').innerHTML = str;

//// 

*/

DEFINE("DOCROOT", getcwd());
require_once('/includes/config4.php');
require_once('/includes/classes/countries.php');
require_once('/includes/classes/smallClasses.php');
require_once(DOCROOT . '/includes/classes/sqlClasses.php');
require_once('/includes/functions/firstPageFunctions.php');

if(!empty($_COOKIES))
{
	$cookies = 
}
// sets the values to '' if they don't exists
$flds = array('language', 'countryId', 'countryName', 'phoneNumber', 'carrierId', 'carrierName');
resetValues($flds);

//** getting  language from cookies or DB or local storage
getCookies($flds);	


// get countries
$oCountries = new getCountries;
$oCountryProperties = $oCountries->getProperties();
unset($oCountries);

// var_dump('countries', $oCountryProperties->countries);

//** replace this with error class div
if(!empty($oCountryerrors))
{
	var_dump('errors', $oCountryerrors);
}
if(!empty($oCountryProperties->countries))
{
	$params = array('array' => $oCountryProperties->countries, 'selected' => $countryId);
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
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script type="text/javascript" src="js/first.js"></script>
</head>
<body>
todo: logo, language, register, help <br>

	
  <div id="container">
    <h1>TRACK Me</h1>
	<p id="status" style="display:none"></p>
	<div id="cLocation" style="display:none"></div>
	
	<!-- *** replace this id with startStop  -->
	<p id="startButton" style="display:none"><a id="startTracking" onclick="startTracking()" href="#">START TRACKING</a></p>
	
	<!-- form for pin -->
	<div id="pinDiv" style="display:none" >
		<form name="pinform" id="pinform"  action="#" method="post" >
		<ul>
		   <li>
		   <label for="pinNumber">Pin Number</label>
		   <input type="number" name="pinNumber" id="pinNumber" required />
		   </li>
		   <li><input type="button" name="pinSubmit" id="pinSubmit" value="SUBMIT" onclick="sendPin()"  />
		   </li>
		</ul>
		</form>
	</div>


	 <!-- form for tel number and carrier --> 
	 <div id="telDiv" style="display:block">
		<form name="telform" id="telform" action="#" method="post"  >
		 <ul>
		  <li>
		  <label for="country">country</label>
		<?php if(empty($countryId)) { ?>
		   <select name="country" id="country"  onchange="sendCountry(this.value)" required />
				<option></option>
				<?php echo $oCountryProperties->options;  ?>
		   </select>
		   
		<?php } else {
				echo '<input type="text" name="country" id="country" value="' . $countryId . '" placeholder="' . $countryName . '" required  />';
			  }
		?>
		
		  </li>	 
		  <li>
		   <label for="phoneNumber">Phone Number</label>
			<?php 
			if(!empty($phoneNumber))
			{
				echo '<input type="number" name="phoneNumber" id="phoneNumber" value="' . $phoneNumber . '"  required  />';
			}
			else
			{
				echo '<input type="number" name="phoneNumber" id="phoneNumber"placeholder="Numbers only. Include area code too"  required  />';		
			}
		   ?>
		  </li>
		  <li>
		  <label for="carrier">carrier</label>
		   <select name="carrier" id="carrier" required >
				<option value="<?php echo $carrierId; ?>" selected><?php echo $carrierName; ?></option>
		   </select>
		  </li>
		 <li><input type="button" name="telSubmit" id="telSubmit" value="SUBMIT" onclick="sendTel()" /></li>
		</ul>
	   </form>
   </div>
   
  </div>
	
	<br>
	--------------- UNDER CONSTRUCTION ------------------------------------
	<br><br><br>
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
	<br><br>

</body>
</html>
