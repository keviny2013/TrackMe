<?php
/*
	validates params
	//** apply order limitations
	properties will be used to respond ajax
*/		
class respondSendLocation extends respondAjax
{
	protected function validate($params)
	{

		$timePattern = '[0-9]{1,6}';	
		$orderidPattern = '[0-9]{1,12}';
		$latPattern = '([\-]{0,1}\d{1,5}.\d{1,20})';
		if( !preg_match("/^" . $timePattern . "$/" , $params->timePassed) )   
		{
			//** security issue
			$$this->unsafe = true;
			$this->errors[] = 'timePassed is out of pattern, called in: ' . $_SERVER['PHP_SELF'];
			return;
		}
		elseif( !preg_match("/^" . $orderidPattern . "$/" , $params->orderid) )   
		{
			//** security issue
			$this->unsafe = true;
			$this->errors[] = 'orderid is out of pattern, called in: ' . $_SERVER['PHP_SELF'];
			return;
		}
		elseif( !preg_match("/^" . $latPattern . "$/" , $params->lat) || !preg_match("/^" . $latPattern . "$/" , $params->lng) )   
		{
			//** security issue
			$$this->unsafe = true;
			$this->errors[] = 'lat and/or lng is out of pattern in: ' . $_SERVER['PHP_SELF'];
			return;
		}

		$this->action($params);
		
	}
	
	
	/*
		tracking: adding location change to tracking table
	*/
	protected function action($params)
	{
		if(!empty($_SESSION['lastLat']))
		{
			$distance = $this->checkDistance($_SESSION['lastLat'], $_SESSION['lastLng'], $params);
			if($distance < 100)
			{
				return;
			}
		}
	$qry = "insert into `tracking` (`orderid`,  `lat`, `lng`, `timePassed`) values ( '" . $params->orderid . "',  '" . $params->lat . "', '" . $params->lng . "', '" . $params->timePassed . "')";

//***
// $this->messages[] =  'Qry: ' . $qry;
	
		$insertParams = array('qry' => $qry);
		$oInsert = new insertQry($insertParams);
		$aInsertProperties = $oInsert->getProperties();
		unset($oInsert);

		if(!empty($aInsertProperties['messages']))
		{
			$this->messages[] = $aInsertProperties['messages'];
		}		

		if(empty($aInsertProperties['results']) || empty($aInsertProperties['results']['newId'])  || ($aInsertProperties['results']['newId'] < 1) )
		{
			 $this->properties['errors'][] = 'respondSendLocationClass.php line 74: Adding new location was failed.';
			 $this->messages[] = 'Failed to record location: At ' . $params->timePassed . ' seconds passed.';
			 $this->messages[] =  'At ' . $params->timePassed . ' seconds: Registration was failed';
		}
		else
		{
			$_SESSION['lastLat'] = $params->lat;
			$_SESSION['lastLng'] = $params->lng;
			$_SESSION['lastChangeTime'] = $params->timePassed;
			$this->messages[] =  'At ' . $params->timePassed . ' seconds: Registered';
		}
		
	}
	

	/* check the difference between last recorded distance
		//* add distance option
	*/
	function checkDistance($lastLat, $lastLng, $params)
	{
		// 
		$miles = 
		( 3959 * acos( cos( deg2rad($lastLat) ) * cos( deg2rad( $params->lat ) ) * cos( deg2rad( $params->lng ) - deg2rad($lastLng) ) + sin( deg2rad($lastLat) ) * sin( deg2rad( $params->lat ) ) ) ) ;
		
		$feet = $miles * 5280;
		$this->messages[] = 'Distance change: ' . $feet . ' feet';

		return $feet;

	}
	
}
