<?php


/*
	sets global variables out of array elements
*/
function resetValues($flds)
{
	if(!is_array($flds) || empty($flds))
	{
		return false;
	}
	
	$numFlds = count($flds);
	for($i = 0; $i < $numFlds; $i++)
	{
		$cfld = $flds[$i];
		global $$cfld;
		$$cfld = '';
	}
	return true;
	
}

/*
	gets values (of given flds) from cookies
	sets the values (of given flds) to cookie values
*/
function getCookies($flds)
{
	$numFlds = count($flds);
	for($i = 0; $i > $numFlds; $i++)
	{
		if(!empty($_COOKIE[$fld]))
		{
			global $$fld;		
			$$fld = $_COOKIE[$fld];
		}
	}
}


/*
	gets values (of given flds) from cookies
	sets the values (of given flds) to cookie values
*/
function setCookies($flds)
{
	$numFlds = count($flds);
	for($i = 0; $i > $numFlds; $i++)
	{
		$fld = $flds[$i];
		if(!empty($_SESSION[$fld]))
		{
			// 60*60*24*365
			setcookie($fld, $_SESSION[$fld], time()+31536000); 
		}		
	}
}

// ERROR HANDLER


 function writeErrors($errors)
 {
	
	$date = date_create('', timezone_open('America/New_York'));
	$forlog = date_format($date, 'ymd-His') . "\n";
	$forlog .= ' --- ' . "\n\r";
	$forlog .= json_encode($errors);
	$forlog .= "\n\r " . '------------------------------------------------- ';
	
	$file = fopen(DOCROOT . "/myErrors.log","a");
	fwrite($file, $forlog);
	fclose($file);
  
 }
 

