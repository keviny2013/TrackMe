<?php

/*
	validates params
	adds new device to DB if params does not have deviceid
	//** apply order limitations
	adds new order request to DB.orders table
	properties will be used to respond ajax
*/		
class respondNewOrder extends respondAjax
{
	protected function validate($params)
	{

		$timePattern = '[0-9]{6}[\-][0-9]{6}';
		$deviceidPattern = '[0-9]{0,12}';
		$timeLimitPattern = '[0-9]{1,5}';
		$trackingIntervalPattern = '[0-9]{1,4}';
		$latPattern = '([\-]{0,1}\d{1,5}.\d{1,20})';

		if( !preg_match("/^" . $timePattern . "$/" , $params->formattedStartTime) )   
		{
			//** security issue
			$this->unsafe = true;
			$this->errors[] = 'startTime "' . $params->formattedStartTime . '" is out of pattern in: ' . 'ajax/respondNewOrderClass.php';
			return;
		}
		if( !preg_match("/^" . $timeLimitPattern . "$/" , $params->timeLimit) )   
		{
			//** security issue
			$this->unsafe = true;
			$this->errors[] = 'timeLimit: "' . $params->timeLimit . '" is out of pattern in: ' . 'ajax/respondNewOrderClass.php';
			return;
		}		
		elseif( !preg_match("/^" . $trackingIntervalPattern . "$/" , $params->trackingInterval) )   
		{
			//** security issue
			$this->unsafe = true;
			$this->errors[] = 'trackingInterval "' . $params->trackingInterval . '" is out of pattern in: ' . 'ajax/respondNewOrderClass.php';
			return;
		}
		elseif( !preg_match("/^" . $latPattern . "$/" , $params->lat) || !preg_match("/^" . $latPattern . "$/" , $params->lng) )   
		{
			//** security issue
			$this->unsafe = true;
			$this->errors[] = 'lat and/or lng is out of pattern in: ' . 'ajax/respondNewOrderClass.php';
			return;
		}
		
		// check deviceid
		if(!$params->deviceid)
		{
			$params->deviceid = $this->addDevice($params);
		}
		if(!$params->deviceid)
		{
			return;
		}
		$this->action($params);
		
		
	}
	
	
	// add new device to DB devices table
	protected function addDevice($params)
	{		
		$qry = "INSERT INTO `devices` (`dateAdded`, `platform`, `height`, `width`) VALUES ( '" . $params->formattedStartTime . "',  '" . $params->platform . "','" . $params->height . "','" . $params->width ."')";
	
		$params = array('qry' => $qry);
		$oInsert = new insertQry($params);
		$aInsertProperties = $oInsert->getProperties();
		unset($oInsert);
		if(!empty($aInsertProperties['messages']))
		{
			$this->messages[] = $aInsertProperties['messages'];
		}		

		if(!empty($aInsertProperties['results']) && !empty($aInsertProperties['results']['newId']) && ($aInsertProperties['results']['newId'] > 0))
		{
			$this->data['deviceid'] = $aInsertProperties['results']['newId'];
			return $this->data['deviceid'];
		}
	}
	
	/*
		adding new order to orders table
	*/
	protected function action($params)
	{
		$qry = "insert into `orders` (`deviceid`, `timeLimit`, `trackingInterval`, `startTime`, `startLat`, `startLng`) values ( '" . $params->deviceid . "', 
		'" . $params->timeLimit . "',
		'" . $params->trackingInterval . "',
		'" . $params->formattedStartTime . "',
		'" . $params->lat . "',
		'" . $params->lng . "')";
		
		$insertParams = array('qry' => $qry);
		$oInsert = new insertQry($insertParams);
		$aInsertProperties = $oInsert->getProperties();

		if(!empty($aInsertProperties['messages']))
		{
			$this->messages[] = $aInsertProperties['messages'];
		}		

		if(!empty($aInsertProperties['results']))
		{
			$this->data['startTime'] = $params->formattedStartTime;
		
			$this->data['orderid'] = $aInsertProperties['results']['newId']; 
		}
		else
		{
			$this->errors[] = 'Insert qry was failed: ajax/respondNewOrder.php - Line-115';
		}
		
		
	}
}
