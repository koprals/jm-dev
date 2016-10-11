<?php
class ActionComponent extends Component 
{
	var $components 		=	array('Cookie','Session','General');
	var $GCM_API_ACCESS		=	"AIzaSyClrsnIbHAH7X5g1WQmMo1CUH2SzpvQkZU";
	
	/**
		*Info of email setting
	*/
	var $actionInfo;
	
	/**
		HTML result from generate text
	*/
	var $text;
	var $text2;
	
	var $logsID;
	
	
	function GetRandomUser()
	{
		/*//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}

		$this->Cookie->domain		=	$settings['Setting']['site_domain'];
		$this->Cookie->path			=	"/";
		$cookie_rand				=	$this->Cookie->read('rand_user');
		$userlogin					=	$this->General->my_decrypt($this->Cookie->read('userlogin'));
		$RandomUser					=	ClassRegistry::Init("RandomUser");
		
		if(!is_null($cookie_rand))
		{
			return $cookie_rand;	
		}
		else
		{
			$ip	=	$_SERVER['REMOTE_ADDR'];
		
			//CHEK IP FIRST
			$cond		=	(!is_null($userlogin)) ? 
							array(
								"RandomUser.user_id" 	=> $userlogin,
								"RandomUser.ip_address" => $ip
							) : 
							array(
								"RandomUser.ip_address "	=>	$ip,
								'RandomUser.user_id IS NULL'
							);
			
			
			$find		=	$RandomUser->find("first",array(
								'conditions'	=>	$cond
							));
			
			if($find==false)
			{
				$rand_user	=	$this->GenerateRandom();
				$this->Cookie->write('rand_user', $rand_user, false, 24*3600*30, $settings['Setting']['site_domain']);
				return $rand_user;
			}
			else
			{
				$rand_user	=	$find['RandomUser']['rand_id'];
				$this->Cookie->write('rand_user', $rand_user, false, 24*3600*30, $settings['Setting']['site_domain']);
				return $find['RandomUser']['rand_id'];
			}
		}*/
		return false;
	}
	
	function GenerateUserLogin($userlogin)
	{
		return true;
	}
	
	/*function GenerateRandom() 
	{
		$ip	=	$_SERVER['REMOTE_ADDR'];
		$RandomUser		=	ClassRegistry::Init("RandomUser");
		$userlogin		=	$this->General->my_decrypt($this->Cookie->read('userlogin'));
		
		// Generate the Transaction Id
		$rand_code = rand(1, 99999999);
		
		// Check if is already been used
		$total = $RandomUser->find('count',array(
			'conditions' => array(
				'RandomUser.rand_id' => $rand_code
			)
		));
		
		// Regenerate The Transaction Id already been used
		if ($total > 0) {
			$tmp_code = $this->GenerateRandom();
		}
		else
		{
			$cond	=	(!is_null($userlogin)) ? array('rand_id' => $rand_code,"ip_address"=>$ip,'user_id'=>$userlogin) : array('rand_id' => $rand_code,"ip_address"=>$ip);
			$RandomUser->save($cond);
			$tmp_code = $rand_code;
			
		}
		return $tmp_code;
	}*/
	
	
	//RANDOM USER IS AUTO INCREAMENT NOW
	function GenerateRandom() 
	{
		$ip	=	$_SERVER['REMOTE_ADDR'];
		$RandomUser		=	ClassRegistry::Init("RandomUser");
		$userlogin		=	$this->General->my_decrypt($this->Cookie->read('userlogin'));
		
		$RandomUser->create();
		$cond	=	(!is_null($userlogin)) ? array('rand_id' => $rand_code,"ip_address"=>$ip,'user_id'=>$userlogin) : array('rand_id' => $rand_code,"ip_address"=>$ip);
		$RandomUser->save($cond);
		$tmp_code = $RandomUser->getLastInsertId();
		return $tmp_code;
	}
	
	function getContent($destination, $source){
		$filename 	= $destination;
		$handle 	= fopen("$source", "rb");
		
		if($handle)
		{
	  		$somecontent = stream_get_contents($handle);
			
	  		fclose($handle);
	  		$handle = fopen($filename, 'wb');
	 
	  		if($handle)
			{
				if (fwrite($handle, $somecontent) === FALSE) 
				{
		   			$confirm = false;
		   			exit;
				}
				$confirm = true;
				fclose($handle);
	  		}
			else
			{
		 		$confirm = false;
		 		exit;
	  		}
		}
		return $confirm;
	}
	
	
	function SendNotification(
		$user_id,
		$model,
		$model_id,
		$notification_name,
		$order_status_id,
		$search				=	array(),
		$replace			=	array(),
		$search_title		=	array(),
		$replace_title		=	array(),
		$order_id			=	NULL,
		$task_status_id		=	NULL
	)
	{
		
		$NotificationSetting		=	ClassRegistry::Init("NotificationSetting");
		$NotificationLog			=	ClassRegistry::Init("NotificationLog");
		$MemberGcm					=	ClassRegistry::Init("MemberGcm");
		
		$NotificationSettingDetail	=	$NotificationSetting->find("first",array(
											"conditions"	=>	array(
												"LOWER(NotificationSetting.name)"	=>	strtolower($notification_name)
											)
										));
		$gcm						=	$MemberGcm->find("first",array(
											"conditions"	=>	array(
												"MemberGcm.user_id"	=>	$user_id
											)
										));
		
		$notification_setting_id	=	$NotificationSettingDetail['NotificationSetting']['id'];			
		$title						=	str_replace($search,$replace,$NotificationSettingDetail['NotificationSetting']['title']);
		$ticker						=	$title;
		$message					=	str_replace($search,$replace,$NotificationSettingDetail['NotificationSetting']['message']);
		$gcm_id						=	(!empty($gcm) && !empty($gcm['MemberGcm']['gcm_id'])) ? $gcm['MemberGcm']['gcm_id'] : "";
		$created					=	date("Y-m-d H:i:s");
		$NotificationLog->saveAll(
			array(
				
				"user_id"						=>		$user_id,
				"notification_setting_id"		=>		$notification_setting_id,
				"model"							=>		$model,
				"model_id"						=>		$model_id,
				"title"							=>		$title,
				"message"						=>		$message,
				"ticker"						=>		$ticker,
				"gcm_id"						=>		$gcm_id,
				"has_read"						=>		"0",
				"status"						=>		"0",
				"created"						=>		$created,
				"order_id"						=>		$order_id,
				"order_status_id"				=>		$order_status_id,
				"task_status_id"				=>		$task_status_id
				
			),
			array(
				"validate"						=>	false
			)
		);
		$notification_log_id		=	$NotificationLog->getLastInsertId();
		$not_read_message			=	$NotificationLog->find("count",array(
											"conditions"	=>	array(
												"NotificationLog.user_id"	=>	$user_id,
												"NotificationLog.has_read"	=>	"0"
											)
										));		
		/************* KIRIM KE GCM *************/
		if(!empty($gcm_id))
		{
			$url 						= 'https://android.googleapis.com/gcm/send';
			$fields =	array(
				'registration_ids'						=>	array($gcm_id),
				'data' => array(
					"notification_log_id"				=>	$notification_log_id,
					"user_id"							=>	$user_id,
					"notification_setting_id"			=>	$notification_setting_id,
					"model"								=>	$model,
					"model_id"							=>	$model_id,
					"title"								=>	$title,
					"message"							=>	$message,
					"ticker"							=>	$ticker,
					"created"							=>	$created,
					"has_read"							=>	"0",
					"not_read_message"					=>	$not_read_message,
					"order_status_id"					=>	$order_status_id,
					"order_id"							=>	$order_id,
					"task_status_id"					=>	$task_status_id
				)
			);
		
			$headers = array(
				'Authorization: key=' . $this->GCM_API_ACCESS,
				'Content-Type: application/json'
			);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result 	= 	curl_exec($ch);
			$out		=	json_decode($result,true);
			
			if ($result !== FALSE)
			{
				if($out["success"] > 0)
				{
					$NotificationLog->updateAll(
						array(
							"gcm_response"			=>	"'".$result."'",
							"status"				=>	"1"
						),
						array(
							"NotificationLog.id"	=>	$notification_log_id
						)
					);
					return array("status" => false,"response"=>$result);
				}
				else
				{
					$NotificationLog->updateAll(
						array(
							"gcm_response"			=>	"'".$result."'",
							"status"				=>	"-1"
						),
						array(
							"NotificationLog.id"	=>	$notification_log_id
						)
					);
					return array("status" => true,"response"=>$result);
				}
			}
			else
			{
				$NotificationLog->updateAll(
					array(
						"gcm_response"			=>	"'".$result."'",
						"status"				=>	"-1"
					),
					array(
						"NotificationLog.id"	=>	$notification_log_id
					)
				);
				return array("status" => false,"response"=>$result);
			}
			curl_close($ch);
		}
		/************* KIRIM KE GCM *************/
	}
	
	function EmailSend($emailID,$to,$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),$model=null,$model_id=null,$html="")
	{
		App::import('Vendor','Swift' ,array('file'=>'lib/swift_required.php'));		
		$menuInstance 			=	ClassRegistry::init('EmailSetting');
		$mail					=	$menuInstance->find('first',array(
			'conditions' => array('name' => $emailID)
		));	
	    	$html					=	$this->generateHTMLEMail($emailID,$search,$replace);
		$subject				=	(count($searchSub)>0) ? $this->generateSubjectEMail($emailID,$searchSub,$replaceSub) : $mail['EmailSetting']['subject'];
		$emailLogID				=	$this->saveEMailLog($emailID,$to,$html,$subject,$mail['EmailSetting']['from'],$mail['EmailSetting']['fromtext'],$model,$model_id);
		
		/*$transport = Swift_SmtpTransport::newInstance('mail.androidtinker.com',587)
		  ->setUsername('customer@androidtinker.com')
		  ->setPassword('customer001');
		$mailer 				=	Swift_Mailer::newInstance($transport);
		$message 				=	Swift_Message::newInstance($subject)
		  ->setFrom(array("customer@androidtinker.com" => $mail['EmailSetting']['fromtext']))
		  ->setTo(array($to => $to))
		  ->setBody($html, 'text/html');
		  
		$send 					= $mailer->send($message);
		*/
		$send 					= 1;
		
		if($send>=1)
		{
			$this->updateEmailLog($emailLogID);
		}
		return $send;
	}
	
	
	function EmailSave($emailID,$to,$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),$model=null,$model_id=null)
	{
		$menuInstance 			= ClassRegistry::init('EmailSetting');
		$mail					= $menuInstance->find('first',array(
			'conditions' => array('name' => $emailID)
		));	
	    $html					=	$this->generateHTMLEMail($emailID,$search,$replace);
		$subject				=	(count($searchSub)>0) ? $this->generateSubjectEMail($emailID,$searchSub,$replaceSub) : $mail['EmailSetting']['subject'];
		$emailLogID				=	$this->saveEMailLog($emailID,$to,$html,$subject,$mail['EmailSetting']['from'],$mail['EmailSetting']['fromtext'],$model,$model_id);
		
		return true;
	}
	
	function ResendEmailLog($id)
	{
		$emailLog	= ClassRegistry::init('EmailLog');
		$data		= $emailLog->findById($id);
		
		if($data==false)
		{
			return false;
		}
		App::import('Vendor','Swift' ,array('file'=>'swift/Swift.php'));
		App::import('Vendor','Swift_Connection_SMTP' ,array('file'=>'swift/Swift/Connection/SMTP.php'));
		
		$html		=	$data['EmailLog']['text'];
		$subject	=	$data['EmailLog']['subject'];
		$to			=	$data['EmailLog']['to'];
		$from		=	$data['EmailLog']['from'];
		$from_text	=	$data['EmailLog']['fromtext'];
		
		$smtp		=	new Swift_Connection_SMTP("smtp.gmail.com", Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
		$smtp->setUsername("admin@jualanmotor.com");
		$smtp->setpassword("coda123456");

		$swift 		= new Swift($smtp);
		$message  	= & new Swift_Message($subject, $html, "text/html");
		$send     	= $swift->send($message, $to,  new Swift_Address($from,$from_text));
		
		if($send>=1)
		{
			$this->updateEmailLog($id);
		}
		return $send;
	}
	
	function generateSubjectEMail($emailID,$search = "", $replace = "")
	{
		$menuInstance 			= ClassRegistry::init('EmailSetting');
		$mail					= $menuInstance->find('first',array(
		'conditions' => array('name' => $emailID)
		));		
		
		// GET MAIL TEXT
			$mail_text = $mail['EmailSetting']['subject'];
			
		// LOOP THROUGH PLACEHOLDER REPLACEMENT ARRAY
			$text = str_replace($search, $replace, $mail_text);
			return $text;
	}
	
	function generateHTMLEMail($emailID,$search = "", $replace = "")
	{
		$menuInstance 			= ClassRegistry::init('EmailSetting');
		$mail					= $menuInstance->find('first',array(
		'conditions' => array('name' => $emailID)
		));		
		
		// GET MAIL TEXT
			$mail_text = $mail['EmailSetting']['email_setting'];
			
		// LOOP THROUGH PLACEHOLDER REPLACEMENT ARRAY
			$text = str_replace($search, $replace, $mail_text);
			return $text;
	}

	function updateEmailLog($ID)
	{
		$emailLog 			= ClassRegistry::init('EmailLog');
		$update				= $emailLog->updateAll(
		array('EmailLog.status' => '1','last_send' => "'".time()."'"),
		array('EmailLog.id'	=> $ID)
		);
	}
	
	function saveEMailLog($emailID,$to,$text,$subject="",$from="",$fromtext="",$model=null,$model_id=null)
	{
		$emailLog 				=	ClassRegistry::init('EmailLog');
		$EmailSetting 			=	ClassRegistry::init('EmailSetting');
        $emailDetail			=	$EmailSetting->findByName($emailID);
		$save					=	$emailLog->save(array(
			'to'				=>	$to,
			'text'				=>	$text,
			'subject'			=>	$subject,
			'from'				=>	$from,
			'fromtext'			=>	$fromtext,
			'model'				=>	$model,
			'model_id'			=>	$model_id,
			'email_setting_id'	=>	$emailDetail['EmailSetting']['id'],
			'status'			=>	"0"
		));
		
        $emailLog->create();
		return $emailLog->getLastInsertId();
	}

	function generateHTML($action_name,$search = "", $replace = "",$replaceown = "")
	{
		/**
			CONNECT TO ActionType MODEL
				action type table
		*/
		$actionTypes 			= ClassRegistry::init('ActionType');
		
		// GET ACTION TYPE INFO
			$action	=	$actionTypes->find('first',array('conditions'=>array('name'=>$action_name)));
			$this->actionInfo		=	$action;
			
		// GET ACTION TEXT
			$action_text = $action['ActionType']["text"];
			
		// LOOP THROUGH PLACEHOLDER REPLACEMENT ARRAY
			$text 	= str_replace($search, $replace, $action_text);
			$text2 	= str_replace($search, $replaceown, $action_text);
			
			$this->text		=	$text;
			$this->text2	=	$text2;
			return $text;
	}
	function saveAdminLog($admin_id)
	{
		$adminLog 			= ClassRegistry::init('AdminLog');
		
		//SAVE TO USERS LOG
		$save	=	$adminLog->save(array(
						'user_id' 			=>	$admin_id,
						'action_type_id' 	=>	$this->actionInfo['ActionType']['id'],
						'actionText' 		=>	$this->text,
						'actionTextOwn'		=>	$this->text2
					));
		$adminLog->create();
					
	}
	
	function save($userID,$option=array())
	{
		
		$default_option		=	array(
									'base_on'	=> 'time',
									'cond'		=>  array(),
									'model'		=>  null,
									'extID'		=>	null,
									'dynamic'	=>	false,
									'userValue'	=>	null,
									'extValue'	=>	null,
									'chanel'	=>	'web'
								);
					
		$options			=	array_merge($default_option,$option);
		
		/**
			CONNECT TO ActionType MODEL
				action type table
		*/
		$userLog 			= ClassRegistry::init('UserLog');
		$USERS 				= ClassRegistry::init('User');
		$POINTS				= ClassRegistry::init('PointHistory');
		$TYPES				= ClassRegistry::init('ActionType');
		$userValue			= ($options['userValue']==null) ? $this->actionInfo['ActionType']['points'] : $options['userValue'];
		$extValue			= ($options['extValue']==null) ? $this->actionInfo['ActionType']['ext_points'] : $options['extValue'];
		
		
		//CHECK LAST USER VALUE
		$lastUser			= $POINTS->find('first',array(
									'conditions'	=> array(
										'PointHistory.user_id'		=> $userID
									),
									'order'	=> 'PointHistory.id DESC'
								  ));
		
				  
		if($options['base_on']	=== 'time')
		{
			//CHECK ACTION BEFORE
			$dataBefore			= $userLog->find('first',array(
									'conditions'	=> array(
										'UserLog.user_id'			=> $userID,
										'UserLog.action_type_id'	=> $this->actionInfo['ActionType']['id']
									),
									'order'							=> array("UserLog.id DESC")
									));
	
			if(is_array($dataBefore))
			{
				
				if($this->actionInfo['ActionType']['after'] > 0 )
				{
					$addPoints			= mktime(date("H",strtotime($dataBefore['UserLog']['modified']))+$this->actionInfo['ActionType']['after'],date("i",strtotime($dataBefore['UserLog']['modified'])),date("s",strtotime($dataBefore['UserLog']['modified'])),date("m",strtotime($dataBefore['UserLog']['modified'])),date("d",strtotime($dataBefore['UserLog']['modified'])),date("Y",strtotime($dataBefore['UserLog']['modified'])));
					$now				= time();
					if($addPoints>$now)
					{
						$userValue	=	0;
					}
				}
			}
			
		}
		elseif($options['base_on']	=== 'model' && !is_null($options['model']))
		{
			$model		=	$options['model'];			
			$MODELNAME	=	ClassRegistry::init($model);
			
			if($MODELNAME->hasField("userID"))
			{
				$condtions	=	array_merge($options['cond'],array(
								"$model.userID"	=> $userID
							));
			}
			else
			{
				$condtions	=	$options['cond'];	
			}
			
			$find		=	$MODELNAME->find('count',array(
								'conditions'	=> $condtions
							));
			if($find>0)
			{
				$userValue	=	0;
			}
		}
		
		if( !is_null($options['extID']) )
		{
			if($options['dynamic'] == false )
			{
				if($find>0)
				{
					$extValue	=	0;
				}
			}
		}
		
		$points_before			=  ($lastUser==false) ? 0 : $lastUser['PointHistory']['points_after'];
		$points_after			=  $points_before + $userValue;
		
		//SAVE TO USERS LOG
		$save	=	$userLog->save(array(
						'user_id' 		=>	$userID,
						'action_type_id'=>	$this->actionInfo['ActionType']['id'],
						'actionText' 	=>	$this->text,
						'actionTextOwn'	=>	$this->text2,
						'chanel'		=>	$options['chanel']
					));
					$userLog->create();
		$logsID	=	$userLog->getLastInsertId();
		
		//SAVE TO POINTS HISTORY
		$savePoints	=	$POINTS->save(array(
							'user_id' 		=>	$userID,
							'value'			=>	$userValue,
							'points_before'	=>	$points_before,
							'points_after'	=>	$points_after,
							'ref_table'		=>	'UserLog',
							'ref_id'		=>	$logsID
						));
						$POINTS->create();
					
				
		//UPDATE USERS POINTS
		$update	=	$USERS->updateAll(
						array(
							'points'	=> "'".$points_after."'"
						),
						array(
							'User.id'	=> $userID
						)
					);
	
		if( !is_null($options['extID']) )
		{
			//CHECK LAST EXT USER  VALUE
			$lastExt			= $POINTS->find('first',array(
										'conditions'	=> array(
											'PointHistory.user_id'		=> $options['extID']
										),
										'order'	=> 'PointHistory.id DESC'
									  ));
									  
			$points_before	=  ($lastExt==false) ? 0 : $lastExt['PointHistory']['points_after'];
			$points_after	=  $points_before + $extValue;
							  
			//SAVE TO POINTS HISTORY
			$savePoints	=	$POINTS->save(array(
								'user_id' 		=>	$options['extID'],
								'value'			=>	$extValue,
								'points_before'	=>	$points_before,
								'points_after'	=>	$points_after,
								'ref_table'		=>	'UserLog',
								'ref_id'		=>	$logsID
							));
							
							$POINTS->create();
				
							
			//UPDATE USERS POINTS
			$update	=	$USERS->updateAll(
							array(
								'points'	=> "'".$points_after."'"
							),
							array(
								'User.id'	=> $options['extID']
							)
						);
		}
		$this->logsID	=	$logsID;
	}
	
	function LastLogin($user_id)
	{
		$USER	=	ClassRegistry::Init('User');
		$update	=	$USER->updateAll(
						array(
							'last_login'	=>	"'".date("Y-m-d H:i:s")."'"
						),
						array(
							'User.id'	=>	$user_id
						)
					);
	}
	
	function ShareVendor($text,$userID,$linktext=null,$linkurl=null)
	{
		$this->Extid	=	ClassRegistry::Init("Extid");
		$find			=	$this->Extid->find('all',array(
								'conditions'	=> array(
									"Extid.user_id"	=> $userID
								),
								'fields'		=> array('Extid.extName','Extid.extID'),
								'group'			=> array("Extid.extName")
							));
							
		foreach($find as $find)
		{
			if($find["Extid"]["extName"]=="facebook")
			{
				$this->ShareFacebook($text,$userID,$linktext,$linkurl);
			}
			elseif($find["Extid"]["extName"]=="twitter")
			{
				$this->ShareTwitter($text,$userID,$linktext,$linkurl);
			}
		}
		
	}
	
	function ShareFacebook($text,$userID,$linktext,$linkurl)
	{
		
		$Setting		=	ClassRegistry::Init("Setting");
		$settings		=	$Setting->find('first');
                $settings		=	$settings['Setting'];
		if(!empty($text) && !empty($userID))
		{
			$this->Extid	=	ClassRegistry::Init("Extid");
			$find			=	$this->Extid->find('first',array(
									'conditions'	=> array(
										"Extid.user_id"	=> $userID,
										"Extid.extName"	=> "facebook"
									),
									'fields'		=> array('Extid.extName','Extid.extID')
								));
							
			App::import('Vendor','Facebook' ,array('file'=>'facebook/facebook.php'));
			$facebook	=	new Facebook($settings['facebook_app_key'], $settings['facebook_app_secret']);
			$LINK		=	array(array("text"=>$linktext,"href"=>$linkurl));
			
			$attachment = array (
				'name'			=> $linktext,
				'href'			=> $linkurl,
				'description'	=> $text
			);

			$test		=	$facebook->api_client->stream_publish($text, $attachment, $LINK, $target_id = null,$find['Extid']['extID']);
                        
		}
	}
	
	
	function ShareTwitter($text,$userID,$linktext,$linkurl)
	{
		
		$Setting		=	ClassRegistry::Init("Setting");
		$settings		=	$Setting->find('first');
                $settings		=	$settings['Setting'];
		if(!empty($text) && !empty($userID))
		{
			$this->Extid	=	ClassRegistry::Init("Extid");
			$find			=	$this->Extid->find('first',array(
									'conditions'	=> array(
										"Extid.user_id"	=> $userID,
										"Extid.extName"	=> "twitter"
									),
									'fields'		=> array('Extid.oauth_token','Extid.oauth_token_secret')
								));
								
			
					
			App::import('Vendor','TwitterOAuth' ,array('file'=>'twitteroauth/twitteroauth.php'));
		    $connection 		= new TwitterOAuth($settings['twitter_consumer_key'], $settings['twitter_consumer_secret'],$find['Extid']['oauth_token'],$find['Extid']['oauth_token_secret']);
		    $post				= $connection->post('statuses/update', array('status' => $text));
		}
	}
	
	function ShareYahoo($text,$userID,$linktext,$linkurl)
	{
		App::import('Vendor','Yahoo' ,array('file'=>'yahoo/Yahoo.inc'));
		$Setting		=	ClassRegistry::Init("Setting");
		$settings		=	$Setting->find('first');
        $settings		=	$settings['Setting'];
		
		$hasSession = YahooSession::hasSession($settings['yahoo_consumer_key'], $settings['yahoo_consumer_secret'], $settings['yahoo_app_id']);
		if($hasSession != FALSE)
		{
			$session 	= YahooSession::requireSession($settings['yahoo_consumer_key'], $settings['yahoo_consumer_secret'], $settings['yahoo_app_id']);
			$user = $session->getSessionedUser();
		
		   	// create an unique hash of the update data using md5
		   	$suid = md5($text.$linktext.$linkurl.time());
			
		   	// insert the update...
		   	$user->insertUpdate($suid, $text, $linkurl, $linktext);
			
			return true;
		}
		return false;
	}

	
	function bumpLog($userID)
	{
		$userLog 			= ClassRegistry::init('UserLog');
		$count				= $userLog->find('count',array('conditions'=>array('user_id' => $userID)));
		
		while($count >= 1000)
		{
			$find	=	$userLog->find('first',array('conditions'=>array('user_id' => $userID),'order'=>'created ASC'));
			foreach($find as $k=>$v)
			{
				$userLog->deleteAll(array("id"=>$v['id']));
			}
			$count--;
		}
	}
}
?>