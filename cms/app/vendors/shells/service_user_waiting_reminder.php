<?php
class ServiceUserWaitingReminderShell extends Shell
{
	var $name 			=	'ServiceUserWaitingReminder';
	var $uses 			=	array('Service');
	var $serviceName	=	"user_waiting_reminder";
	var $user_status	=	0;
	var $lists			=	array();
	var $mailName		=	"cron_user_waiting_reminder";
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
		
		
		//SEND MAIL TO ADMIN
		App::import('Component', 'Action');
		$Action 			=	& new ActionComponent();
			
		//CHECK SERVICE IS ACTIVE OR NOT
		if(!$this->IsSerciceActive() or !$this->GetMembers())
		{
			echo "0";
			exit;
		}
		
		$User	=	ClassRegistry::Init('User');
		
		foreach($this->lists as $members)
		{
			$to					=	$members['email'];
			$account_name		=	$members['name'];
			$user_id			=	$members['user_id'];
			$logo_url			=	$this->settings['logo_url'];
			$site_name			=	$this->settings['site_name'];
			$site_url			=	$this->settings['site_url'];
			$vcode				= 	$User->getValidation(trim($members['email']));
			App::import('vendor', 'encryption_class');
			$encrypt			= 	new encryption;
			$param				= 	$encrypt->my_encrypt($user_id . "|" . $vcode);
			$link 				= 	$this->settings['site_url'] . "Users/Verification/param:" . $param;
			$search				=	array('[logo_url]', '[account_name]', '[site_name]', '[site_url]','[link]');
			$replace			=	array($logo_url, $account_name,  $site_name, $site_url,$link);
			$search_subject		=	array('[account_name]', '[site_name]');
			$replace_subject	=	array($account_name,$site_name);
			$send				=	$Action->EmailSend($this->mailName, $to, $search, $replace,$search_subject,$replace_subject,"User",$members['user_id']);
			
		}
		
		echo "1";
		//$this->render(false);
	}
	
	function IsSerciceActive()
	{
		$Service	=	ClassRegistry::Init("Service");
		$find		=	$Service->findByCode($this->serviceName);
		if($find==false or $find['Service']['status']==0) return false;
		return true;
	}
	
	function GetMembers()
	{
		$lists		=	array();
		$members	=	ClassRegistry::Init('User');
		$data		=	$members->find("all",array(
							"conditions"	=>	array(
								"User.userstatus_id"	=>	$this->user_status
							)
						));
		
		if(empty($data))
		{
			return false;
		}

		$EmailLog		=	ClassRegistry::Init("EmailLog");
		foreach($data as $data)
		{
			//CHECK COUNT SEND DATA
			$COUNTLOG	=	$EmailLog->find("count",array(
								"conditions"	=>	array(
									"EmailLog.email_setting_id"	=>	$this->emailID,
									"EmailLog.to"				=>	$data['User']['email']
								)
							));
			
			if($COUNTLOG<3)
			{
				//CHECK LAST SEND
				$LASTSEND	=	$EmailLog->find('first',array(
									"conditions"	=>	array(
										"EmailLog.email_setting_id"	=>	$this->emailID,
										"EmailLog.to"				=>	$data['User']['email']
									),
									'order'			=>	array("EmailLog.last_send DESC")
								));
				
				$last_week	=	mktime(0,0,0,date("m"),date("d")-7,date("Y"));
				
				if($LASTSEND['EmailLog']['last_send'] < $last_week)
				{
					$account_name	=	(!empty($data['Profile']['fullname'])) ? $data['Profile']['fullname'] :  $data['User']['email'];
					$lists[]		=	array("user_id"	=>	$data['User']['id'],"name"=>$account_name,"email"=>$data['User']['email']);	
				}
			}
		}
		$this->lists	=	$lists;
		return true;
	}
}
?>