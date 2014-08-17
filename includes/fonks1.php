<?php

/////////////////////////////////////////////////////

// quick and temp (***) echo for developers
FUNCTION echo1($str1, $str2) {
 echo "<h4>****" . $str1 . ": " . $str2 . "</h4>";
}


//////////////////

// applying mysql_real_escape_string to array elements
FUNCTION myescape1($arr) {	

	$es = new mysql3();
	$arr2 = array();

	foreach ($arr as $key=>$val)  {
	  $cval = stripslashes($val);
	  $arr2[$key] = mysqli->real_escape_string($cval); 
	}

	return $arr2;

} // myescape
