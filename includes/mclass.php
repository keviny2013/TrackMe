<?php
// MySql classes: mysql3, idu3, select3
// other calsses: sort1, list11

///////////////////////////////////////

// CLASS mysql3
class mysql3 { 		// $nresult is global

var $host = "mysql902.ixwebhosting.com";  
var $dbs = "C33142_weekly";  
var $user = "C33142_level3";  
var $way = "Level_3";  
var $clerror = "";
var $qry = "";

function mysql3() {
global $cdb, $link, $clerror;

$link = mysql_connect($this->host,  $this->user, $this->way);
if (!$link) { 
 // echo "<h5>Mysql error1: ". mysql_error() . "</h5>";  
 $clerror =  "ERROR: Could not connect to Mysql";
}

$cdb = mysql_select_db($this->dbs);
if (!$cdb) { 
  // echo "<h5>Mysql error2: ". mysql_error() . "</h5>";  
 $clerror = "ERROR: Could not select the database";
} 

mysql_set_charset('utf8');

}  // end function mysql3


function action2($qry) {   // for queries without database connection
global $cdb, $link, $clerror;

if ($clerror) {  return $clerror;  }

$this->qry = $qry;

$result = mysql_query($this->qry);

// echo "<br><br>Query-: " . $this->qry. "<br><br>";

if ($result)  {   return mysql_fetch_array($result);  }
else  {  
	return  "ERROR: Query failed: " . $this->qry . "<br><br>";     
// echo mysql_error();    
}

}  // end action2


}  // end class mysql3

// end CLASS mysql3

///////////////////////////////////////////////////

// CLASS IDU3 > INSERT-DELETE-UPDATE

class idu3 extends mysql3 {   // for INSERT-DELETE-UPDATE queries 


function action2($qry) {
global $cdb, $link, $clerror;

if ($clerror) {  return $clerror;  }

$this->qry = $qry;
mysql_query($this->qry);

if (mysql_error())   {
 $msg =  date("m-d-y") ." \r \n ERRROR at " . $_SERVER["PHP_SELF"] . " \r \n Qry: " . $qry . " \r \n Mysql error: ". mysql_error() . " \n"; 
 $msg = str_replace("\n.", "\n..", $msg);
// $scr1 .=  "<h5>$msg</h5>";   // * deactivate
$subj = "Mysql IDU error at weeklyzip.com";
  mail1($subj, $msg);
}

   return mysql_affected_rows(); 

}  // end action2

}  // end class idu3

// end CLASS IDU3

//////////////////////////////////////////////////////////////////////

// CLASS SELECT3

class select3  extends mysql3 {


function action2($qry) {
global $nresult;

if ($clerror) {  return $clerror;  }

$nresult = 0;
$this->sql = array();

$this->qry = $qry;
$result = mysql_query($this->qry);

if (mysql_error()) { 
$msg =   "ERRROR at " . $_SERVER["PHP_SELF"] . " \r \n Qry: " . $qry . " \r\n Mysql error: ". mysql_error(); 
$msg = str_replace("\n.", "\n..", $msg);
// echo "<h5>$msg</h5>"; 
$subj = "Mysql select3 error at weeklyzip.com";
  mail1($subj, $msg);
return;
}

$nresult = mysql_num_rows($result);

// echo "Qry-select3: " . $this->qry . "<br>";
// echo "<br>Number of Mysql rows: " .  mysql_num_rows($result) .  "<br>";

if (!$nresult)  {   return;  }

while($row = mysql_fetch_array($result)) {
    $this->sql[] = $row;
}

mysql_free_result($result);

return $this->sql;

}  // end action2

}  // end class select3

// end CLASS SELECT3

////////////////////////


///////////////////////////////
?>
