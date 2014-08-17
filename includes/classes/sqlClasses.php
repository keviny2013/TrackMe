<?php

/*
	running select querries
	DB connection through mysqli
*/
class selectQry
{
	protected $unsafe = false;
	protected $messages;
	protected $errors;	
	protected $results;
	protected $properties = array();
	
	function __CONSTRUCT($params)	
	{
		if(!$params['qry'])
		{
			$this->errors[] = 'class selectQry: Missing parameter: qry ';
			return;
		}
		
		$this->properties = array();
		$this->properties['results'] = array();
		$this->properties['messages'] = array();
		$this->errors = array();		
		
		$mysqli = new mysqli(MYHOST, MYUSER4, MYPASS4, MYDB);
		$mysqli->set_charset("utf8");
		
		/* check connection */
		if ($mysqli->connect_errno) {
			$this->errors[] = "Connection failed";
			//** sendMail('SQL Connection failed', $mysqli->connect_error)
			return;
		}

		/* Select queries return a resultset */
		if ($data = $mysqli->query($params['qry'])) 
		{
			while ($row = $data->fetch_array()) {
				$this->properties['results'][] = $row;
			} 

			/* free result set */
			$data->close();
		}
		else
		{
			$this->errors[] = $mysqli->error;
		}
		
		$mysqli->close();
    }
	
	public function getProperties()
	{
		$this->properties['unsafe'] = &$this->unsafe;
		$this->properties['messages'] = &$this->messages;
		$this->properties['results'] = &$this->results;
		return $this->properties;
	}
	
	function __DESTRUCT()
	{
		if(!empty($this->errors))
		{
			require_once(DOCROOT . '/includes/functions/firstPageFunctions.php');
			writeErrors($this->errors);
		}
	}
	
}	// end class selectQry


/*
	running select querries
	DB connection through mysqli
*/
class insertQry
{
	protected $unsafe = false;
	protected $messages;
	protected $errors;	
	protected $results;
	protected $properties = array();
	
	function __CONSTRUCT($params)	
	{
		if(!$params['qry'])
		{
			$this->errors[] = 'class selectQry: Missing parameter: qry ';
			return;
		}
		
		$mysqli = new mysqli(MYHOST, MYUSER4, MYPASS4, MYDB);
		$mysqli->set_charset("utf8");
		
		/* check connection */
		if ($mysqli->connect_errno) {
			$this->errors[] = "Connection failed";
			//** sendMail('SQL Connection failed', $mysqli->connect_error)
			return;
		}

		/* Select queries return a resultset */
		if ($mysqli->query($params['qry'])) 
		{
			$this->results['newId'] = $mysqli->insert_id;
		}
		else
		{
			$this->results['newId'] = -1;
			$this->errors[] = $mysqli->error;
		}
		
		$mysqli->close();
    }
	
	public function getProperties()
	{
		$this->properties['unsafe'] = &$this->unsafe;
		$this->properties['messages'] = &$this->messages;
		$this->properties['results'] = &$this->results;
		return $this->properties;
	}
	
	function __DESTRUCT()
	{
		if(!empty($this->errors))
		{
			require_once(DOCROOT . '/includes/functions/firstPageFunctions.php');
			writeErrors($this->errors);
		}
	}
	
}	// end class insertQry

