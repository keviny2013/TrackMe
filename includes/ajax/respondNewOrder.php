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
$_SESSION['repeatOrder'] = 1;	

if(empty($_GET['params']))
{
	//*** save security data
	exit();
}

$response = array();
if(empty($_SESSION['repeatOrder']))
{
	$_SESSION['repeatOrder'] = 1;
}
else
{
	$_SESSION['repeatOrder']++;
}
if($_SESSION['repeatOrder'] > 4)
{
	$response = array();
	$response['messages'][] = 'Too many orders.';
	$response['unsafe'] = true;
	$response['data'] = array();
	echo json_encode($response);
	exit;
}

chdir('../../');
DEFINE("DOCROOT", getcwd());

	
require_once(DOCROOT . '/includes/ajax/respondAjax.php');
require_once(DOCROOT . '/includes/ajax/respondNewOrderClass.php');

$oNewOrderRespond = new respondNewOrder;
$response = $oNewOrderRespond->getProperties();

echo json_encode($response);

//**
function error_handler1($errno, $error, $file, $line) 
{
    //***? echo '<br>index.php - error_handler1: ' . $error;
	$message = " ___ " . date('ymd-his') . " : ERROR: " . $errno . "\n\r" . "---" . $file . "\n\r" .  "---" . $line .  "\n\r" .  "---" . $error . "\n\r" . "___"  ;

	error_log($message , 3, DOCROOT . "/phpErrors.log");
} 

