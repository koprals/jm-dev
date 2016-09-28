<?php
class ServiceUserUpdateProfileShell extends Shell
{
	var $name 			=	'ServiceUserUpdateProfile';
	var $uses 			=	array('Service');
	var $serviceName	=	"user_update_profile";
	var $sold			=	1;
	var $lists			=	array();
	var $mailName		=	"cron_user_update_profile";
	var $setings;
	var $emailID;
	
	
	function Main()
	{
		$this->layout	=	"ajax";
		
		//SET GENERAL SETTINGS
		$Setting			=	ClassRegistry::Init('Setting');
		$settings			=	$Setting->find('first');
		$set				=	$settings['Setting'];
		$this->settings		=	$set;
		
		//CHECK EMAIL SETTINGS DETAIL
		$email_settings		=	ClassRegistry::Init("EmailSettings");
		$emailData			=	$email_settings->findByName($this->mailName);
		$this->emailID		=	$emailData['EmailSettings']['id'];
		
		//CHECK SERVICE IS ACTIVE OR NOT
		if(!$this->IsSerciceActive() or !$this->GetMember())
		{
			echo "0";
			exit;
		}
		
		App::import('Component', 'Action');
		$Action 			=	& new ActionComponent();
		
		App::import('Helper', 'Time');
		$Time 			= 	new TimeHelper();
		
		App::import('vendor', 'encryption_class');
       	$encrypt	= new encryption;
		
		foreach($this->lists as $data)
		{
			$to					=	$data['email'];
			$account_name		=	$data['name'];
			$user_id			=	$data['user_id'];
			$logo_url			=	$this->settings['logo_url'];
			$site_name			=	$this->settings['site_name'];
			$site_url			=	$this->settings['site_url'];
			$cms_url			=	$this->settings['cms_url'];
			$date				=	date("d-M-Y",strtotime($data['activated']));
			$ago				=	$Time->timeAgoInWords(strtotime($data['activated']));
			
			$link_updateprofil	=	$this->settings['site_url']."Cpanel/UpdateProfile";
			$link_uploadphoto	=	$this->settings['site_url']."Cpanel/UploadPhoto";
			$link_invitefriend	=	$this->settings['site_url']."Users/InviteFriends";
			$link_edit			=	$this->settings['site_url']."ManageProducts/Index";
			$link_add			=	$this->settings['site_url']."Cpanel/AddProduct";
			
			$link_servicestop	=	$this->settings['site_url']."Services/Stop/token:".$encrypt->my_encrypt($this->serviceName . "|" . $data['user_id']);
			
			$search				=	array('[logo_url]', '[account_name]', '[site_name]', '[site_url]', '[cms_url]', '[date]', '[ago]', '[link_updateprofil]', '[link_uploadphoto]', '[link_invitefriend]', '[link_edit]', '[link_add]', '[link_servicestop]');
			$replace			=	array($logo_url, $account_name, $site_name, $site_url, $cms_url, $date, $ago, $link_updateprofil, $link_uploadphoto, $link_invitefriend, $link_edit, $link_add, $link_servicestop);
			$search_subject		=	array('[account_name]');
			$replace_subject	=	array($account_name);
			$send				=	$Action->EmailSend($this->mailName, $to, $search, $replace,$search_subject,$replace_subject,"User",$user_id);
		}
		echo "1";
		exit;
	}
	
	function IsSerciceActive()
	{
		$Service	=	ClassRegistry::Init("Service");
		$find		=	$Service->findByCode($this->serviceName);
		if($find==false or $find['Service']['status']==0) return false;
		return true;
	}
	
	function GetMember()
	{
		$User			=	ClassRegistry::Init("User");
		$UService		=	ClassRegistry::Init("UserService");
		$EmailLog		=	ClassRegistry::Init("EmailLog");
		$last_month		=	mktime(0,0,0,date("m")-1,date("d"),date("Y"));
		$last_2days		=	mktime(0,0,0,date("m"),date("d")-2,date("Y"));
		
		
		//CHECK FOR USER NOT HAVE PROFILE
		$data	=	$User->find("all",array(
						"conditions"	=>	array(
							"OR"	=>	array(
								"Profile.id IS NULL",
								"Profile.address IS NULL"
							)
						)
					));
		
		
		if(empty($data))
		{
			return false;
		}
		
		foreach($data as $data)
		{
			//CHECK INTO USER SERVICE
			$fUser	=	$UService->findByUserId($data['User']['id']);
			
			if($fUser	==	false)
			{
				$UService->create();
				$UService->saveAll(array(
					'user_id'		=>	$data['User']['id'],
					'service_code'	=>	$this->serviceName,
					'active'		=>	1
				));
				
				if(strtotime($data['User']['activated']) < $last_2days)
				{
					$lists[]	=	array(
										"email"			=>	$data['User']['email'],
										"name"			=>	(empty($data['Profile']['fullname'])) ? $data['User']['email'] : $data['Profile']['fullname'],
										"user_id"		=>	$data['User']['id'],
										"activated"		=>	$data['User']['activated'],
									);
				}
			}
			elseif($fUser['UserService']['active']	==	1)
			{
				
				//CHECK COUNT SEND DATA
				$COUNTLOG	=	$EmailLog->find("count",array(
									"conditions"	=>	array(
										"EmailLog.email_setting_id"	=>	$this->emailID,
										"EmailLog.to"				=>	$data['User']['email'],
									)
								));
				
				if($COUNTLOG < 10)
				{
					//CHECK COUNT SEND DATA
					$SEND	=	$EmailLog->find("first",array(
									"conditions"	=>	array(
										"EmailLog.email_setting_id"	=>	$this->emailID,
										"EmailLog.to"				=>	$data['User']['email']
									)
								));
					
					$last_send		=	($SEND==false) ? strtotime($data['User']['activated']) : $SEND['EmailLog']['last_send'];
					
					if($last_send < $last_month)
					{
						$lists[]	=	array(
											"email"			=>	$data['User']['email'],
											"name"			=>	(empty($data['Profile']['fullname'])) ? $data['User']['email'] : $data['Profile']['fullname'],
											"activated"		=>	$data['User']['activated'],
											"user_id"		=>	$data['User']['id'],
										);
					}
				}
			}
		}
		
		
		if(empty($lists)) return false;
		$this->lists	=	$lists;
		return true;
	}
}
?>