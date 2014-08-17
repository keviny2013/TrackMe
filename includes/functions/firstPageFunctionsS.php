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
		if(!empty($_COOKIES[$fld]))
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
