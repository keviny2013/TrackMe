/*
	javascript for index.html in trackMe
	1- clicking startTracking ->startTracking()-> 
			path1: sendNewOrder(position)->responseToNewOrder(ajaxResponse)
			path2 (repeats on every trackingInterval): trackMe-> sendLocation(position)->responseTo sendLocation(ajaxResponse)
	2- clicking stopTracking->stopTracking()
	
	3- common functions: 
		ajaxCall(cUrl, callback): generic ajax call with customized callback function
		displayMessages(messages): lists messages from ajaxResponse['messages']
			(array) errors holds info for developer.
			(array) messages holds info for user
		adLeadingZero(cVar): makes one digit to 2 digits with leading zero. Example: '1' -> '01'
		getLocal(cVar): gets value from localStorage and replaces with existing one
		displayError(positionError): callback function for geolocation errors	
 		
	keys for saved orders are: formattedStartTime 
		localStorage.setItem(formattedStartTime, lastOrderParamsStr)
		to get a list of the orders: getOrders();
	keys for saved locations (tracking) are: orderid and timePassed 
		currentOrderTracks[timePassed] = [lat, lng];
		currentOrderTracksStr = JSON.stringify(currentOrderTracks);
		localStorage.setItem(orderid, currentOrderTracksStr);
		to get a list of the locations: getLocations(orderid);
				
*/
//*** localStorage.clear();



	// settings: default values
	var timeLimit = 10;   			//***
	var trackingInterval = 2000; 	//***  miliseconds
		
	// session variables 
	var unsafe = false;		// for safety status
	var refreshIntervalId;	// controls geolocation repeats, intervals
	
	var startTime;			// unix time (just seconds) that tracking started
	var currentTime;		// current unix time (just seconds)
	var formattedStartTime; // YYMMDD-HHMMSS
	var currentDate;		// YYMMDD
	var timePassed;			// seconds since startTime
	
	var deviceid = '';
	var orderid;	
	var lat;
	var lng;
	var lastOrderParams			// holds params of last order
	var lastLat;				// last location's lat
	var lastLng;				// last location's lng
	
	// optional
	var tel; 
	var countryid; 	
	var memberid = '';
	
	// replacing values from local storage if exists
	var flds = [ 'tel', 'countryid', 'timeLimit', 'trackingInterval', 'memberid', 'deviceid'];
	for(i =0; i < flds.length; i++)
	{
		flds[i] = getLocal(flds[i]);
	}
	
// gets value from localStorage
function getLocal(cVar)
{
	if(localStorage.getItem(cVar))
	{
		return localStorage.getItem(cVar);
	}
	else
	{
		return cVar;
	}
}

// generic ajax call with customized callback function
function ajaxCall(cUrl, callback)
{

	if(unsafe == true)
	{
		alert('Sorry cannot process ajax.');
		return false;
	}
	
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
// alert('url: ' + cUrl + "\n" + 'xmlhttp.responseText: ' + xmlhttp.responseText);	
			document.getElementById("status").innerHTML = '';
			if(xmlhttp.responseText)
			{			
				var response = JSON.parse(xmlhttp.responseText);			
				if(response['unsafe'] == true)
				{
					unsafe = true;
				}
			}
			else
			{			
				$response = xmlhttp.responseText;
			}
			callback(response);	
	
		}
	}

	xmlhttp.open("GET", cUrl, true);
	xmlhttp.setRequestHeader("charset", "utf-8");
	xmlhttp.send();	
	
}	// end ajaxCall

/*
	ajax status updates and
	array messages is ajaxResponse['messages']
*/
function displayMessages(messages)
{
	if(messages)
	{
		var messageStr = '';
		for(i = 0; i < messages.length; i++)
		{
			messageStr += "<br>" + messages[i];
		}
		document.getElementById("status").innerHTML = messageStr;
		document.getElementById("status").style.display = "block";				
	}
}

// makes one digit to 2 digits with leading zero. Example: '1' -> '01'
function adLeadingZero(cVar)
{
	if(cVar < 10)
	{
		return '0' + cVar;
	}
	else
	{
		return cVar;
	}
}		



//
function startTracking()
{
	document.getElementById('startTracking').style.display = 'none';
	document.getElementById('stopTracking').style.display = 'block';
	
	var gl = navigator.geolocation;
	if (gl) {
		
		startTime = new Date().getTime();
		// converting to seconds
		startTime = parseInt(startTime / 1000);
		
		gl = navigator.geolocation;
		gl.getCurrentPosition(sendNewOrder, displayError);			
		
	} else {
		alert("Cannot get your location. Try again if your browser supports geolocation. ");
	}
}			

// starting a tracking order (service)
function sendNewOrder(position)
{
	// params for order
	
	// we prefer client's time against server's time
	// formatted start time:  YYMMDD-HHMMSS with leading zeros
	var d = new Date();
	var yr = d.getFullYear().toString(); 
	var cMonth = adLeadingZero(d.getMonth());
	var cDay = adLeadingZero(d.getDate());
	var cHr = adLeadingZero(d.getHours());		
	var cMinutes = adLeadingZero(d.getHours());		
	var cSeconds = adLeadingZero(d.getSeconds());		
	currentDate = yr.substr(2, 3) + cMonth +  cDay;
	formattedStartTime = yr.substr(2, 3) + cMonth +  cDay + '-' +  cHr + cMinutes + cSeconds;

	lat = position.coords.latitude;
	lng = position.coords.longitude;
	// generating json string with params
	lastOrderParams = {};
	lastOrderParams = {
		'formattedStartTime'	: formattedStartTime,
		'timeLimit'	: timeLimit,
		'trackingInterval'	: trackingInterval,
		'memberid'	: memberid,
		'lat'		: lat,
		'lng'		: lng		
	};	
	if(localStorage.getItem('deviceid'))
	{
		deviceid = localStorage.getItem('deviceid');
		lastOrderParams.deviceid  = deviceid;
	}
	else
	{
		lastOrderParams.width = screen.width;
		lastOrderParams.height = screen.height;
		lastOrderParams.platform = navigator.platform;
	}

	var lastOrderParamsStr = JSON.stringify(lastOrderParams);
	var cUrl = 'includes/ajax/respondNewOrder.php?params=' + lastOrderParamsStr;
	ajaxCall(cUrl, responseToNewOrder);		

}

// starting a tracking order (service)
function responseToNewOrder(ajaxResponse)
{	
	if(ajaxResponse)
	{
		// messages
		displayMessages(ajaxResponse['messages']);	
		
		// deviceid
		if(!deviceid && ajaxResponse['data']['deviceid'])
		{
			deviceid = ajaxResponse['data']['deviceid'];
			localStorage.setItem('deviceid', deviceid);
			lastOrderParams.deviceid = deviceid;
		}
		
		// orderid
		if(typeof(ajaxResponse['data']['orderid']) != 'undefined' && ajaxResponse['data']['orderid'])
		{
			orderid = ajaxResponse['data']['orderid'];		
			// saving order number
			var ordersStr = '';
			if(localStorage.getItem('orders'))
			{
				ordersStr = localStorage.getItem('orders');
				var orders = JSON.parse(ordersStr);
				orders.push(orderid);
			}
			else
			{
				var orders = [orderid];
			}
			newOrdersStr = JSON.stringify(orders);
			localStorage.setItem('orders', newOrdersStr);
			
			// saving order's data
			lastOrderParamsStr = '';
			lastOrderParams.orderid = orderid;
			lastOrderParamsStr = JSON.stringify(lastOrderParams);
	
			// key for saved order
			var orderMeta = orderid.toString() + 'meta';
			localStorage.setItem(orderMeta, lastOrderParamsStr);
			
			// tracking starts here
			refreshIntervalId = setInterval(trackMe, trackingInterval);			
			// trackMe();
		}
		else
		{
			alert('Error-255: Sorry cannot place the order. Please try again.');
		}

	}
	else
	{
		alert('ERROR: Sorry, a network error was occured. Please try again.');
	}
	
}

// repeating at each interval
function trackMe()
{
	currentTime = new Date().getTime();
	// converting to seconds
	currentTime = parseInt(currentTime / 1000);
	timePassed = currentTime - startTime;
	
	// stopping tracking: if time passed timeLimit
	if(timePassed > timeLimit)
	{
		clearInterval(refreshIntervalId);
		stopTracking();
	}
	
	gl = navigator.geolocation;
    gl.getCurrentPosition(sendLocation, displayError);	

}

/*
	gets the position from geolocation
	returns if change is less than 50 feet
	saves locally
	calls ajax
*/
function sendLocation(position)
{
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	
	if(lastLat)
	{
		var miles = getDistanceFromLatLonInKm(lastLat, lastLng, lat,lng);
		var feet = miles * 5280;
		
		// will not send ajax request, if change is less than 50 feet 
		if(feet < 50)
		{
			return;
		}		
	}
	lastLat = lat;
	lastLng = lng;
	
	// saving cuurent location
	currentLocation = [timePassed, lat, lng];
	var orderTrack = orderid.toString() + 'track';
	var tracksStr = '';
	var newTracksStr = '';
	if(localStorage.getItem(orderTrack))
	{
		tracksStr = localStorage.getItem(orderTrack);
		var tracks = JSON.parse(tracksStr);
		tracks.push(currentLocation);
	}
	else
	{
		var tracks = [currentLocation];
	}
	newTracksStr = JSON.stringify(tracks);
	localStorage.setItem(orderTrack, newTracksStr);
	
	// params for ajax call
	var locationParams = {
		'orderid'  	: orderid,
		'lat'  		: lat,
		'lng'		: lng,
		'timePassed': timePassed
	};

	var locationParamsStr = JSON.stringify(locationParams);
	var cUrl = 'includes/ajax/respondSendLocation.php?params=' + locationParamsStr;
	ajaxCall(cUrl, responseToSendLocation);	
	
}

function responseToSendLocation(ajaxResponse)
{
	// messages
	if(ajaxResponse)
	{
		displayMessages(ajaxResponse['messages']);
	}

}
	
// callback function for geolocation errors	
function displayError(positionError) 
{
	// console.log('Location error: ' + dump(positionError));
	alert('Sorry an error occurred during getting your location.');
}		

// response to clicking 'stop tracking'
function stopTracking()
{
	//** ask for password to stop, if pass is not provided in 10 seconds dont stop it.

	document.getElementById('startTracking').style.display = 'block';		
	document.getElementById('stopTracking').style.display = 'none';
	
	var locationsList = getLocations(orderid);
	if(!locationsList)
	{
		locationsList = '<br>No location change was found for order: ' + orderid;
	}

	document.getElementById("locations").innerHTML = '<b>Locations for order: ' + orderid + '</b>' + locationsList;
}

// geolocation distance calculator
function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
  var R = 3959; // Radius of the earth in miles
  // deg2rad() is below
  var dLat = deg2rad(lat2-lat1); 
  var dLon = deg2rad(lon2-lon1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  // Distance in miles
  var d = R * c; 
  return d;
}

// geolocation degree to radiant
function deg2rad(deg) {
  return deg * (Math.PI/180)
}

// gets list of Orders from localStorage
function getOrders()
{
	if(!localStorage.getItem('orders'))
	{
		return;
	}
	var recordsStr = localStorage.getItem('orders');
	var records = JSON.parse(recordsStr);
	if(records.length < 1)
	{
		return;
	}
	
	var out = '';
	var metaKey = '';
	var trackKey = '';
	var val;
	
			
	for(key in records)
	{
		val = records[key];
		metaKey = val.toString() + 'meta';
		out += '<br>' + key + ': ' + val + ': ' + localStorage.getItem(metaKey);
	}
		
	return out;

}


// get tracks of current order from localStorage
function getLocations(orderid)
{
	trackKey = orderid.toString() + 'track';
	if(!localStorage.getItem(trackKey))
	{
		return;
	}
	else
	{
	
	}

	var recordsStr = localStorage.getItem(trackKey);

	var records = JSON.parse(recordsStr);
	if(records.length < 1)
	{
		return;
	}
	
	var out = '';
	var val;
		
	for(i = 0; i < records.length; i++)
	{
		val = records[i];
		out += '<br>' + i + ': ' + val;
	}
		
	return out;

}
