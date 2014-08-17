<?php
/*
	$response = array('unsafe', 'message', 'data')
	$errors are for admin only 
*/


//*** remove
set_error_handler('error_handler1');
ini_set('display_errors', '0');

session_start();

//*** remove
$_SESSION['repeatLocation'] = 1;	

if(empty($_GET['params']))
{
	//*** save security data
	exit();
}

$response = array();
if(empty($_SESSION['repeatLocation']))
{
	$_SESSION['repeatLocation'] = 1;
}
else
{
	$_SESSION['repeatLocation']++;
}
if($_SESSION['repeatOrder'] > 1000)
{
	$response['messages'][] = 'Tracking was stopped. Too many repeats for trial level.';
	$response['unsafe'] = true;
	$response['data'] = array();
	echo json_encode($response);
	exit;
}


chdir('../../');
DEFINE("DOCROOT", getcwd());
require_once('includes/ajax/respondAjax.php');
require_once('includes/ajax/respondSendLocationClass.php');

$oSendLocationRespond = new respondSendLocation;
$response = $oSendLocationRespond->getProperties();

echo json_encode($response);

//**
function error_handler1($errno, $error, $file, $line) 
{
    //***? echo '<br>index.php - error_handler1: ' . $error;
	$message = " ___ " . date('ymd-his') . " : ERROR: " . $errno . "\n" . "---" . $file . "\n" .  "---" . $line .  "\n" .  "---" . $error . "\n" . '___'  ;

	error_log($message , 3, DOCROOT . "/phpErrors.log");
} 


