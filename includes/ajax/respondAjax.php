<?php

//***
ini_set('display_errors', '1');

/*
	boolen $unsafe 
	array $messages are for user
	array errors are for admin only
	array $data is the requested data
	array $properties to wrap up all above except $errors
*/
abstract class respondAjax
{
	protected $unsafe = false;
	protected $messages;
	protected $errors;	
	protected $data;
	protected $properties = array();
	
	function __CONSTRUCT()
	{
		require_once(DOCROOT . '/includes/config4.php');
		require_once(DOCROOT . '/includes/classes/smallClasses.php');
		require_once(DOCROOT . '/includes/classes/sqlClasses.php');
		
		$getstr = stripslashes($_GET['params']);
		$gets = json_decode($getstr);
		
		$this->validate($gets);
	}
	
	// to be defined by each extended class
	protected function validate($gets)
	{
		// if(valid)
		$this->action($gets);
	}
	
	// to be defined by each extended class
	protected function action($gets)
	{

	}	

	
	public function getProperties()
	{
		$this->properties['unsafe'] = &$this->unsafe;
		$this->properties['messages'] = $this->messages;
		$this->properties['data'] = &$this->data;
		return $this->properties;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	function __DESTRUCT()
	{
		if(!empty($this->errors))
		{
			require_once(DOCROOT . '/includes/functions/firstPageFunctions.php');
			writeErrors($this->errors);
		}
	}
	
}	// end class respondAjax



