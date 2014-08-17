<?php

//*** remove
set_error_handler('error_handler1');
ini_set('display_errors', '0');

session_start();

// echo '<h4>This is track/index.php</h4>';

// var_dump('GET: ', $_GET);

$cError = '';
$cTime = '';

$timePattern = '[1-9][0-9]{9,12}';
$telPattern = '[1-9][0-9]{10,12}';
// $latPattern = '[\-]{0,1}[0-9]{1,4}.[0-9]{1, 30}';
$latPattern = '([\-]{0,1}\d{1,5}.\d{1,15})';
if( !preg_match("/^" . $timePattern . "$/" , $_GET['time']) )   
{
	//*** report securityIssue();
	
	//*** remove this
	$cError = 'Time issue: ' . $_GET['time'];
}
elseif( !preg_match("/^" . $telPattern . "$/" , $_GET['tel']) )   
{
	//*** report securityIssue();
	
	//*** remove this
	$cError = 'Tel number issue: ' . $_GET['tel'];
}
elseif( !preg_match("/^" . $latPattern . "$/" , $_GET['lat']) || !preg_match("/^" . $latPattern . "$/" , $_GET['lng']) )   
{
	//*** report securityIssue();
	
	//*** remove this
	$cError = 'latitude or langitude issue. lat: ' . $_GET['lat'] . ' --- lng: ' . $_GET['lng'];
}


// echo '<br>line 31';
if(!$cError)
{
	getOldLocation();
}
$cTime = time();
$response = array('error' => $cError, 'time' => $cTime);
$response = json_encode($response);
echo $response;


// FUNCTIONS ONLY
/* 
	checks the last record
	gets the last recorded location from database
	if exists: calls checkDistance()
	if does not exist: calls addNew()
	
*/
function getOldLocation()
{
	// get last registered lat and lng
	$tel = $_GET['tel'];
	// $qry = "select max(time), lat, lng from trackme where (`tel` = '" . $tel . "') "; 
	$qry = "select count(*) from trackme where (`tel` = '" . $tel . "') ";
	
	try {
		$conn = new PDO('mysql:host=mysql902.ixwebhosting.com; dbname=C33142_weeklyzip', 'C33142_level3', 'Level_3');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		if ($res1 = $conn->query($qry)) 
		{
// echo '<br>line 55';		
			if($res1->fetchColumn() > 0) 
			{	
// echo '<br>line 58';			
				$stmt = $conn->prepare('select max(timePassed), lat, lng from trackme where `orderid` = :orderid');
				$stmt->execute(array('tel' => $tel));
				// $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
				$result = $stmt->fetchALL(PDO::FETCH_ASSOC);
				$oldLat = $result['lat'];
				$oldLng = $result['lng'];
				
				// echo '<br>';

/*
echo '<br>tel: ' . $tel;				
echo '<br>OldTime: ' . $result['time'];				
echo '<br>oldLat: ' . $oldLat;
echo '<br>newLat: ' . $_GET['lat'];
echo '<br>oldLng: ' . $oldLng;
echo '<br>newLng: ' . $_GET['lng'];
*/

// echo '<br>';
				if(!empty($oldLat) && !empty($oldLng))
				{
					checkDistance($oldLat, $oldLng);
				}
				else
				{
					addNewLocation();
				}
			}
			else
			{
				addNewLocation();
			}
		}
	
	} catch(PDOException $e) {
		//*** report to admin
		// echo 'ERROR: ' . $e->getMessage();
	};
	
}

/* check the difference between last recorded distance
	//* add distance option
*/
function checkDistance($oldLat, $oldLng)
{
	// 
	$miles = 
	( 3959 * acos( cos( deg2rad($oldLat) ) * cos( deg2rad( $_GET['lat'] ) ) * cos( deg2rad( $_GET['lng'] ) - deg2rad($oldLng) ) + sin( deg2rad($oldLat) ) * sin( deg2rad( $_GET['lat'] ) ) ) ) ;
	
	$feet = $miles * 5280;
	
// echo '<br>Distance: ' . $miles . ' miles';
// echo '<br>Distance: ' . $feet . ' feet';

	
	if($feet > 100)
	{
		addNewLocation();
	}

}


/* adding new record
	//* add distance option
*/
function addNewLocation()
{
	$qry = "insert into `tracking` (`orderid`,  `lat`, `lng`, `timePassed`) values ( '" . $_GET['orderid'] . "', 
		 '" . $_GET['lat'] . "', '" . $_GET['lng'] . "', '" . $_GET['timePassed'] . "')";
	


	$tel = $_GET['tel'];
	$lat = $_GET['lat'];
	$lng = $_GET['lng'];
	$time = $_GET['time'];	
	
	try {
		$conn = new PDO('mysql:host=mysql902.ixwebhosting.com; dbname=C33142_weeklyzip', 'C33142_level3', 'Level_3');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare('INSERT INTO trackme (tel, lat, lng,time ) VALUES( :tel, :lat, :lng, :time)');
		$stmt->execute(array(
			'tel' => $tel,
			'lat' => $lat,
			'lng' => $lng,
			'time' => $time
			)
		);	
		
// echo '<h4>new record: ' . $stmt->rowCount() . '</h4>'; 	
		
	}
	catch(PDOException $e) 
	{
		//** report to admin
		// echo 'ERROR: ' . $e->getMessage();
	};	


}


//**
function securityIssue()
{
	//** report to admin
	// echo '<h4>Invalid data</h4>';
	return;

}

