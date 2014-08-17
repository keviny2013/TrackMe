<?php

	/*
		Add:  Carriers lookup for cell phone number. (https://www.carrierlookup.com) 
		Add to database: URL of SMS Gateway for cell phone carriers
		
		
	*/
	
	if (!empty($_POST['sendMessage']) )
	{
	
// var_dump('Post: ' , $_POST);
var_dump('get: ' , $_GET);
	
		$post = securePost($_POST);	
		if (isset($post['phoneNumber'])) 
		{
			$phoneNumber = $post['phoneNumber'];
		}
		else
		{
			$phoneNumber = '';
		}
		if (isset($post['carrier'])) 
		{
			$carrier = $post['carrier'];
		}
		else
		{
			$carrier = '';
		}		
		if (isset($post['smsMessage'])) 
		{		
			$smsMessage= $post['smsMessage'];
		}
		else
		{
			$smsMessage = '';
		}

		$headers = 'From: ' . 'WeeklyZip';
		$to = '';
		if ($carrier == 'att')
		{
			$to = $phoneNumber . 'txt';
		}
		elseif ($carrier == 'tmobile')
		{
			$to = $phoneNumber . 'tmomail.net';
		}
		elseif ($carrier == 'verizon')
		{
			$to = $phoneNumber . '@vtext.com';
		}		
		
		// mail($to, '', $smsMessage, $headers);
		

		
	}
	else
	{
		$phoneNumber = '5165165161';
		$smsMessage= 'Write your message here.';
	}
	
?>

<!DOCTYPE html>
 <html>
 <head>
   <meta charset="utf-8" />
   <style>
		body {
			 margin: 0;
			 padding: 3em 0;
			 color: #fff;
			 background: #0080d2;
			 font-family: Georgia, Times New Roman, serif;
		}

		#container {
			 width: 600px;
			 background: #fff;
			 color: #555;
			 border: 3px solid #ccc;
			 -webkit-border-radius: 10px;
			 -moz-border-radius: 10px;
			 -ms-border-radius: 10px;
			 border-radius: 10px;
			 border-top: 3px solid #ddd;
			 padding: 1em 2em;
			 margin: 0 auto;
			 -webkit-box-shadow: 3px 7px 5px #000;
			 -moz-box-shadow: 3px 7px 5px #000;
			 -ms-box-shadow: 3px 7px 5px #000;
			 box-shadow: 3px 7px 5px #000;
		}

		ul {
		 list-style: none;
		 padding: 0;
		}

		ul > li {
		 padding: 0.12em 1em
		}

		label {
		 display: block;
		 float: left;
		 width: 130px;
		}

		input, textarea {
		 font-family: Georgia, Serif;
		}
		   
   </style>
   
  </head>
  <body>
   <div id="container">
    <h1>Sending SMS with PHP</h1>
    <form action="" method="post">
     <ul>
      <li>
       <label for="phoneNumber">Phone Number</label>
       <input type="text" name="phoneNumber" id="phoneNumber" placeholder="<?php echo $phoneNumber; ?>" /></li>
      <li>
      <label for="carrier">Carrier</label>
       <select name="carrier" id="carrier"  />
		<option></option>
			<option value="att">AT&amp;T</option>
			<option value="tmobile">Tmobile</option>
			<option value="verizon">Verizon</option>
	   </select>
      </li>
      <li>
       <label for="smsMessage">Message</label>
       <textarea name="smsMessage" id="smsMessage" cols="45" rows="15" placeholder="<?php echo $smsMessage; ?>" ></textarea>
      </li>
     <li><input type="submit" name="sendMessage" id="sendMessage" value="Send Message" /></li>
    </ul>
   </form>
  </div>
  
<?php
	//** temp, fake function
	function securePost($cPost)
	{
		return $cPost;
	}
 
?>
 


 </body>
</html>
