<?php
class ResendMailShell extends Shell
{
	var $name 			=	'ResendMail';
	
	function initialize()
	{
		
		$this->EmailLog					=	ClassRegistry::init('EmailLog');
		$this->Setting					=	Cache::read('settings');
		
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$settings		=	$this->Setting->find('first');
			Cache::write('settings', $settings);
		}
		
		$this->settings	=	$settings['Setting'];
	}
	
	
	function Main()
	{
		Configure::write('debug', 3);
		//FLAG LOCATION
		$flag					=	$this->settings['path_web']."app/tmp/cache/cron/resend_mail.txt";
		$data					=	explode("|",trim(file_get_contents($flag)));
		$flag_status			=	$data[0];
		$last_update			=	(int)$data[1];
		
		if($flag_status == "1")
		{
			$long				=	(time()-$last_update)/60;
			if($long > 30)
			{
				if (is_writable($flag))
				{
					if (!$handle = fopen($flag, 'wb')) {
						 exit;
					}
					if (fwrite($handle, "0|".time()) === FALSE) {
						exit;
					}
					fclose($handle);
				}
			}
			exit;
		}
		
		if($flag_status == "0")
		{
			//WRITE FLAG 1 TO FILE
			if (is_writable($flag)) {
				if (!$handle = fopen($flag, 'wb')) {
					 exit;
				}
				
				if (fwrite($handle, "1|".time()) === FALSE) {
					exit;
				}
				fclose($handle);
			}
			
			$data	=	$this->EmailLog->find("all",array(
							"conditions"	=>	array(
								"EmailLog.status"				=>	0,
								"EmailLog.counting_sending < "	=>	2
							),
							"order"		=>	array("EmailLog.id ASC"),
							"limit"		=>	3
						));
						
			if(!empty($data))
			{
				
				App::import('Vendor','Swift' ,array('file'=>'swift/Swift.php'));
				App::import('Vendor','Swift_Connection_SMTP' ,array('file'=>'swift/Swift/Connection/SMTP.php'));
				
				$smtp		=	new Swift_Connection_SMTP("smtp.gmail.com", Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
				$smtp->setUsername("admin@jualanmotor.com");
				$smtp->setpassword("05011983");
				$swift 			= new Swift($smtp);
			
				
				foreach($data as $data)
				{
					var_dump("Masssuuk ".$data["EmailLog"]["id"]);
					$update	=	$this->EmailLog->updateAll(
									array(
										"last_send"			=>	"'".time()."'",
										"counting_sending"	=>	"'".($data["EmailLog"]["counting_sending"]+1)."'"
									),
									array(
										"EmailLog.id"	=>	$data["EmailLog"]["id"]
									)
								);
					var_dump("UPDATE ".$update);
					
					
					$subject	=	$data["EmailLog"]["subject"];
					$html		=	$data["EmailLog"]["text"];
					$to			=	$data["EmailLog"]["to"];
					$to			=	$data["EmailLog"]["to"];
					$from		=	$data["EmailLog"]["from"];
					$fromtext	=	$data["EmailLog"]["fromtext"];
					$message  	=	& new Swift_Message($subject, $html, "text/html");
					$send     	=	$swift->send($message, $to,  new Swift_Address($from,$fromtext));	
					if($send >= 1)
					{
						$update	=	$this->EmailLog->updateAll(
									array(
											"status"			=>	"'1'"
										),
										array(
											"EmailLog.id"	=>	$data["EmailLog"]["id"]
										)
									);
					}
				}
			}
			
			if (is_writable($flag)) {
				if (!$handle = fopen($flag, 'wb')) {
					 exit;
				}	
				if (fwrite($handle, "0|".time()) === FALSE) {
					exit;
				}
				fclose($handle);
			}
		}
	}
}

?>