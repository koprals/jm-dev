<?php
class ServiceAdUserWaitingController extends AppController
{
	var $name 			=	'ServiceAdUserWaiting';
	var $uses 			=	array('Service');
	var $serviceName	=	"ad_user_waiting";
	var $user_status	=	0;
	var $lists			=	array();
	var $components		=	array('Action');
	var $mailName		=	"cron_ad_user_waiting";
	var $setings;
	
	function Index()
	{
		$send	=	0;
		//CHECK SERVICE IS ACTIVE OR NOT
		if(!$this->IsSerciceActive() or !$this->GetMembers())
		{
			echo "0";
			exit;
		}
		
		//SET GENERAL SETTINGS
		$Setting			=	ClassRegistry::Init('Setting');
		$settings			=	$Setting->find('first');
		$set				=	$settings['Setting'];
		$this->settings		=	$set;
		
		//GET MEMBER LISTS
		$link				=	$this->GenerateLink();
		
		if($link)
		{
			//SEND MAIL TO ADMIN
			$admin_mail			=	$this->settings['admin_mail'];
			$logo_url			=	$this->settings['logo_url'];
			$count				=	count($this->lists);
			$list_user			=	$link;
			$site_name			=	$this->settings['site_name'];
			$site_url			=	$this->settings['site_url'];
			$link				=	$this->settings['cms_url']."Service/DeactivateAdUserWaiting/0/{$this->serviceName}";
			$search				=	array('[logo_url]', '[count]', '[list_user]', '[site_name]', '[site_url]','[link]');
			$replace			=	array($logo_url, $count, $list_user, $site_name, $site_url,$link);
			$send				=	$this->Action->EmailSend($this->mailName, $admin_mail, $search, $replace);
		}
		
		
		if($send>=1)
		{
			echo "1";
		}
		else
		{
			echo "0";
		}
		$this->autoRender	=	false;
	}
	
	function IsSerciceActive()
	{
		$this->loadModel("Service");
		$find	=	$this->Service->findByCode($this->serviceName);
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
		
		if($data==false)
		{
			return false;
		}
		
		foreach($data as $data)
		{
			$lists[]	=	array("id"	=>	$data['User']['id'],"name"=>$data['User']['email']);
		}
		$this->lists	=	$lists;
		return true;
	}
	
	function GenerateLink()
	{
		$link	=	"";
		if(empty($this->lists))
		{
			return false;
		}
		$count	=	0;
		foreach($this->lists as $member)
		{
			$count++;
			$link	.=	$count.'. <a href="'.$this->settings['cms_url'].'Users/Add/'.$member['id'].'">'.$member['name'].'</a><br /><br />';
		}
		return $link;
	}
}
?>