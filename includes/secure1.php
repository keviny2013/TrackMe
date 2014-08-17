<?php


// ERROR HANDLER

FUNCTION error_handler1 ($errno, $error, $file, $line) {
global $scr1, $clabels;

// if ($errno > 8) {

 error_log("Error: [ERROR][$errno][$error][$file][$line]", 8, "errors@husnu.me","From---: messenger@".$_SESSION["w_domain"]);


// }

}  // end error_handler8


FUNCTION lang1($page, $lang)  {
global $nresult;

$qry1 = "select   `" . $lang . "` from `langs` where `page` = '" . $page . "' order by `n`  ";

// running query
 $sel1 = new select3();
 $sel1->action2($qry1);

// echo2($qry1);

if (!$nresult) {
 $sel1 = new select3();
 $sel1->action2($qry1);
	if (!$nresult) {  
	include_once("fonks1.php");
 	echo "<div class='error'> ERROR:  Sorry language error occurred in the system. </div>";    return;  
	}
}

$_REQUEST["w_langs"][$page] = $sel1->sql;


}  // end lang1


////////////  SECURITY

FUNCTION secure1 ($arr, $size) { 
// limits the size and characters with preg match and converts to htmlentities
// assumed PHP magicquotes are on, mysql escape is not used, use escape1() before mysql
 
global $lnk, $link, $scr1;

if (!$arr)  {  return;  }

 $_SESSION["w_safety"] = "";

$carr = implode($arr);
$carr = stripslashes($carr);

$nerr = 0;
$msg .= "<h4>SAFETY ISSUE</h4> Reported by: ". $_SESSION["w_www"] . " ... function secure1<br><br>";
$ncarr = strlen($carr);
// echo "ncarr: ", $ncarr;

if ($ncarr  >  $size)   { 
	$nerr++;  
	$cerr .= "Length of your entry is too long.";
	$msg .= "<b>Issue 1: User's post is bigger than allowed size: </b>".  $ncarr . " > " . $size . "<br>"; 
}

if (!preg_match("/^[a-zA-Z0-9 ,\*\+-@_\.'\(\)\r\n]+$/" , $carr))   {  
	$nerr++;  
	$cerr .= "Don't use excluded characters.";
	$msg .= "<b>" . $cerr . "</b><br> ";  
} 

if (strstr($carr,  "script"))   { 
	$nerr++;  
	$cerr .= "Please try again.";
	$msg .= "<b>Issue 3: Script is used. </b> <br>"; 
} 

if ($nerr)  { 
 echo1("ERROR: ", $cerr);
 $_SESSION["w_safety"] = "unsafe";
 unsecure1($arr, $msg); 

 $scr1 .= "<div class='error'> <center> <h4>ERROR:   Sorry an error is occurred!.. " . $cerr . "</h4> </div>";  
 $_POST = "";
 $_GET = "";
   return;
} 

$_SESSION["w_post1"] = $_POST;
$_SESSION["w_get1"] = $_GET;

// $arr = char1($arr);  

return $arr;

} // end secure1


/////////////

function UNSECURE1($arr, $msg)  {   //reports to admin and deletes POST

$subj = "Unsafe transaction at " . $_SESSION["w_www"];
$msg .= "_SERVER['REMOTE_ADDR']: " .$_SERVER['REMOTE_ADDR'] . "<br> ";
$msg .= "_SERVER['HTTP_REFERER: " . $_SERVER['HTTP_REFERER'] . "<br><br>";

$msg .= "<h5>SESSION variables: </h5>";

foreach ($_SESSION as $key=>$val)  {
  $msg .= $key . " : ". $val . "<br>";
} // end for

$msg .= "<h5>User's POST: </h5> ";
foreach ($arr as $key=>$val)  {
  if (is_array($val))  {   
	foreach ($val as $k=>$v)  {
	$msg .= $k . " : ". htmlspecialchars($v, UTF-8) . "<br>";   
	}
  }
  else {     $msg .= $key . " : ". htmlspecialchars($val, UTF-8) . "<br>";     }
} 

$msg .= " <br>End of message ";

mail1("Unsafe transaction", $msg);

unset($subj, $arr); 
$arr = "";

} // end unsecure1


///////////

FUNCTION char1($arr)  {   // html entities for arrays

if (!$arr)  {  return;  }

foreach($arr as $key=>$val)  {
	// $value = stripslashes($val);
	$value = htmlspecialchars($val, ENT_QUOTES);
	$arr[$key] = $value;
}

return $arr;

}  // end char1

///////////////////////

function mail1($konu, $message)  {

    $alici = "weeklyzip@husnu.me";
    $gon = "messenger@weeklyzip.com";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$headers .= 'To: weeklyzip<weeklyzip@husnu.me>' . "\r\n";
$headers .= 'From: messenger<messenger@weeklyzip.com>' . "\r\n";
// $headers .= 'Cc: Husnu<husnu@husnu.me>' . "\r\n";

 if (mail($alici, $konu, $message, $headers))  {  }
else {    mail($alici, $konu, $message, $headers);   }


} // end mail1

///////////////////////////



?>