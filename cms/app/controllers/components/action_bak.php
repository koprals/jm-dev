<?php
class ActionComponent extends Object 
{
	var $components = array('Cookie','Session');
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
		$cookie_rand	=	$this->Cookie->read('rand_user');
		$userlogin		=	$this->Cookie->read('userlogin');
		
		$RandomUser		=	ClassRegistry::Init("RandomUser");
		$Setting		=	ClassRegistry::Init("Setting");
		$settings		=	$Setting->find('first');
		

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
								"ip_address "	=>	$ip,
								'user_id IS NULL'
							);
							
			$find		=	$RandomUser->find("first",array(
								'conditions'	=>	$cond
							));
			
			if(empty($find))
			{
				$rand_user	=	$this->GenerateRandom();
				$this->Cookie->write('rand_user', $rand_user, false, 24*3600*30, "http://www.jualmotor.com");
				return $rand_user;
			}
			else
			{
				$rand_user	=	$find['RandomUser']['rand_id'];
				$this->Cookie->write('rand_user', $rand_user, false, 24*3600*30, "http://www.jualmotor.com");
				return $find['RandomUser']['rand_id'];
			}
		}
	}
	
	
	function GenerateUserLogin($userlogin)
	{
		return true;
	}
	
	function GenerateRandom() 
	{
		$ip	=	$_SERVER['REMOTE_ADDR'];
		$RandomUser		=	ClassRegistry::Init("RandomUser");
		$userlogin		=	$this->Cookie->read('userlogin');
		
		// Generate the Transaction Id
		$rand_code = rand(1, 99999);
		
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
	
	function EmailSend($emailID,$to,$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),$attachment=false,$filename=false)
	{
		App::import('Vendor','Swift' ,array('file'=>'swift/Swift.php'));
		App::import('Vendor','Swift_Connection_SMTP' ,array('file'=>'swift/Swift/Connection/SMTP.php'));
		$menuInstance 			= ClassRegistry::init('EmailSettings');
		
		$mail					= $menuInstance->find('first',array(
			'conditions' => array('name' => $emailID)
		));	
	    $html		=	$this->generateHTMLEMail($emailID,$search,$replace);
		
		
		$subject	=	(count($searchSub)>0) ? $this->generateSubjectEMail($emailID,$searchSub,$replaceSub) : $mail['EmailSettings']['subject'];
		$emailLogID	=	$this->saveEMailLog($emailID,$to,$html,$subject,$mail['EmailSettings']['from'],$mail['EmailSettings']['fromtext']);
		
		/*
		$smtp			=	new Swift_Connection_SMTP("smtp.gmail.com", Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
		$smtp->setUsername("abyfajar@gmail.com");
		$smtp->setpassword("05011983");

		$swift 		= new Swift($smtp);
		$message  	= & new Swift_Message($subject, $html, "text/html");
		
		if($attachment)
		{
			$message->attach(new Swift_Message_Part($html, "text/html"));
			$message->attach(new Swift_Message_Attachment(file_get_contents($attachment), $filename, "application/pdf"));
		}
		
		if(Configure::read('ONLINE')==true)
		{
			$send     	= $swift->send($message, $to,  new Swift_Address($mail['EmailSettings']['from'],$mail['EmailSettings']['fromtext']));
			
		}
		else
		{
       		$send = 1;
		}*/
		$send = 1;
	
		if($send>=1)
		{
			$this->updateEmailLog($emailLogID);
		}
		return $send;
	}
	
	function generateSubjectEMail($emailID,$search = "", $replace = "")
	{
		$menuInstance 			= ClassRegistry::init('EmailSettings');
		$mail					= $menuInstance->find('first',array(
		'conditions' => array('name' => $emailID)
		));		
		
		// GET MAIL TEXT
			$mail_text = $mail['EmailSettings']['subject'];
			
		// LOOP THROUGH PLACEHOLDER REPLACEMENT ARRAY
			$text = str_replace($search, $replace, $mail_text);
			return $text;
	}
	
	function generateHTMLEMail($emailID,$search = "", $replace = "")
	{
		$menuInstance 			= ClassRegistry::init('EmailSettings');
		$mail					= $menuInstance->find('first',array(
		'conditions' => array('name' => $emailID)
		));		
		
		// GET MAIL TEXT
			$mail_text = $mail['EmailSettings']['email_setting'];
			
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
	
	function saveEMailLog($emailID,$to,$text,$subject="",$from="",$fromtext="")
	{
		$emailLog 			= ClassRegistry::init('EmailLog');
               
		$save				= $emailLog->save(array(
			'to'	=> $to,
			'text'		=> $text,
			'subject'	=> $subject,
			'from'		=> $from,
			'fromtext'	=> $fromtext
			));
			
        $emailLog->create();
		return $emailLog->getLastInsertId();
	}

	function generateHTML($action_name,$search = "", $replace = "",$replaceown = "")
	{
		/**
			CONNECT TO ActionTypes MODEL
				action type table
		*/
		$actionTypes 			= ClassRegistry::init('ActionTypes');
		
		// GET ACTION TYPE INFO
			$action	=	$actionTypes->find('first',array('conditions'=>array('name'=>$action_name)));
			$this->actionInfo		=	$action;
			
		// GET ACTION TEXT
			$action_text = $action['ActionTypes']["text"];
			
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
						'user_id' 		=>	$admin_id,
						'actionID' 		=>	$this->actionInfo['ActionTypes']['id'],
						'actionText' 	=>	$this->text,
						'actionTextOwn'	=>	$this->text2
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
									'extValue'	=>	null
								);
					
		$options			=	array_merge($default_option,$option);
		
		/**
			CONNECT TO ActionTypes MODEL
				action type table
		*/
		$userLog 			= ClassRegistry::init('UserLogs');
		$USERS 				= ClassRegistry::init('User');
		$POINTS				= ClassRegistry::init('PointsHistory');
		$TYPES				= ClassRegistry::init('ActionTypes');
		$userValue			= ($options['userValue']==null) ? $this->actionInfo['ActionTypes']['points'] : $options['userValue'];
		$extValue			= ($options['extValue']==null) ? $this->actionInfo['ActionTypes']['ext_points'] : $options['extValue'];
		
		
		//CHECK LAST USER VALUE
		$lastUser			= $POINTS->find('first',array(
									'conditions'	=> array(
										'PointsHistory.user_id'		=> $userID
									),
									'order'	=> 'PointsHistory.id DESC'
								  ));
					  
		if($options['base_on']	=== 'time')
		{
			//CHECK ACTION BEFORE
			$dataBefore			= $userLog->find('first',array(
									'conditions'	=> array(
										'UserLogs.user_id'		=> $userID,
										'UserLogs.actionID'		=> $this->actionInfo['ActionTypes']['id']
									)
									));
	
			if(is_array($dataBefore))
			{
				if($this->actionInfo['ActionTypes']['after'] > 0 )
				{
					$addPoints			= mktime(date("H",$dataBefore['UserLogs']['modified'])+$this->actionInfo['ActionTypes']['after'],date("i",$dataBefore['UserLogs']['modified']),date("s",$dataBefore['UserLogs']['modified']),date("m",$dataBefore['UserLogs']['modified']),date("d",$dataBefore['UserLogs']['modified']),date("Y",$dataBefore['UserLogs']['modified']));
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
		
		$points_before			=  ($lastUser==false) ? 0 : $lastUser['PointsHistory']['points_after'];
		$points_after			=  $points_before + $userValue;
		
		//SAVE TO USERS LOG
		$save	=	$userLog->save(array(
						'user_id' 		=>	$userID,
						'actionID' 		=>	$this->actionInfo['ActionTypes']['id'],
						'actionText' 	=>	$this->text,
						'actionTextOwn'	=>	$this->text2
					));
					$userLog->create();
		$logsID	=	$userLog->getLastInsertId();
		
		//SAVE TO POINTS HISTORY
		$savePoints	=	$POINTS->save(array(
							'user_id' 		=>	$userID,
							'value'			=>	$userValue,
							'points_before'	=>	$points_before,
							'points_after'	=>	$points_after,
							'ref_table'		=>	'UserLogs',
							'ref_id'		=>	$logsID
						));
						$POINTS->create();
					
				
		//UPDATE USERS POINTS
		$update	=	$USERS->updateAll(
						array(
							'points'	=> "'".$points_after."'"
						),
						array(
							'user.id'	=> $userID
						)
					);
	
		if( !is_null($options['extID']) )
		{
			//CHECK LAST EXT USER  VALUE
			$lastExt			= $POINTS->find('first',array(
										'conditions'	=> array(
											'PointsHistory.user_id'		=> $options['extID']
										),
										'order'	=> 'PointsHistory.id DESC'
									  ));
									  
			$points_before	=  ($lastExt==false) ? 0 : $lastExt['PointsHistory']['points_after'];
			$points_after	=  $points_before + $extValue;
							  
			//SAVE TO POINTS HISTORY
			$savePoints	=	$POINTS->save(array(
								'user_id' 		=>	$options['extID'],
								'value'			=>	$extValue,
								'points_before'	=>	$points_before,
								'points_after'	=>	$points_after,
								'ref_table'		=>	'UserLogs',
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
				$this->ShareTwitter($text,$userID);
			}
			elseif($find["Extid"]["extName"]=="yahoo")
			{
				$this->ShareYahoo($text,$userID);
			}
		}
		
	}
	
	function ShareFacebook($text,$userID,$linktext,$linkurl)
	{
		
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
			$facebook	=	new Facebook(APPID, APP_SECRET);
			$LINK		=	array(array("text"=>$linktext,"href"=>$linkurl));
			
			$attachment = array (
				'name'			=> $linktext,
				'href'			=> $linkurl,
				'description'	=> $text,
				/*'media'			=> array(
									array(
										'type'	=> 'image',
										'src'	=> html_root('gifts/'.$gift->image),
										'href' 	=> fb_root('my_gifts')
									)
								)*/
			);

			$test		=	$facebook->api_client->stream_publish($text, $attachment, $LINK, $target_id = null,$find['Extid']['extID']);
		}
	}
	
	
	function ShareTwitter($text,$userID)
	{
		
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
		    $connection 		= new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,$find['Extid']['oauth_token'],$find['Extid']['oauth_token_secret']);
		    $post				= $connection->post('statuses/update', array('status' => $text));
		}
	}
	
	function ShareYahoo($text,$userID)
	{
		
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
		    $connection 		= new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,$find['Extid']['oauth_token'],$find['Extid']['oauth_token_secret']);
		    $post				= $connection->post('statuses/update', array('status' => $text));
		}
	}
	
	function bumpLog($userID)
	{
		$userLog 			= ClassRegistry::init('UserLogs');
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