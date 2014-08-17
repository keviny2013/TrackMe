<?php
/*
	sending mails
	//** work on headers and multiusers, 
*/
class sendMail
{
	protected $receiver = 'admin@weeklyzip.com';
	protected $sender = 'messenger@weeklyzip';
	protected $subject = 'Default subject';
	protected $message = 'Default message';
	protected $headers = 'Default headers';
	
	function __CONSTRUCT($params)
	{
		if($params['receiver'])
		{
			$this->$receiver = $params['receiver'];
		}
		if($params['sender'])
		{
			$this->$sender = $params['sender'];
		}
		if($params['subject'])
		{
			$this->$subject = $params['subject'];
		}
		if($params['message'])
		{
			$this->$message = $params['message'];
		}
		if($params['headers'])
		{
			$this->$headers = $params['headers'];
		}		
		

		// mail($this->subject, $this->message, $this->headers, $this->sender);

	}
	
}	// end sendMail


