<?php
class ServiceAdProductWaitingApprovalController extends AppController
{
	var $name 			=	'ServiceAdProductWaitingApproval';
	var $uses 			=	array('Service');
	var $serviceName	=	"ad_product_waiting_approval_after_editing";
	var $product_status	=	-2;
	var $lists			=	array();
	var $components		=	array('Action');
	var $mailName		=	"cron_product_waiting_approval_after_editing";
	var $setings;
	var $emailID;
	
	
	function Index()
	{
		$send	=	0;
		
		//CHECK SERVICE IS ACTIVE OR NOT
		if(!$this->IsSerciceActive() or !$this->GetProducts())
		{
			echo "0";
			exit;
		}
		
		//SET GENERAL SETTINGS
		$Setting				=	ClassRegistry::Init('Setting');
		$settings				=	$Setting->find('first');
		$set					=	$settings['Setting'];
		$this->settings			=	$set;
		
		//GET PRODUCT LISTS
		$link					=	$this->GenerateLink();
		
		if($link)
		{
			//SEND MAIL TO ADMIN
			$admin_mail			=	$this->settings['admin_mail'];
			$logo_url			=	$this->settings['logo_url'];
			$count				=	count($this->lists);
			$list_product		=	$link;
			$site_name			=	$this->settings['site_name'];
			$site_url			=	$this->settings['site_url'];
			$link				=	$this->settings['cms_url']."Service/DeactivateAdUserWaiting/0/{$this->serviceName}";
			$search				=	array('[logo_url]', '[count]', '[list_product]', '[site_name]', '[site_url]','[link]');
			$replace			=	array($logo_url, $count, $list_product, $site_name, $site_url,$link);
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
	
	function GetProducts()
	{
		$lists		=	array();
		$members	=	ClassRegistry::Init('Product');
		$data		=	$members->find("all",array(
							"conditions"	=>	array(
								"Product.productstatus_id"	=> $this->product_status
							),
							"order"	=>	array("Product.id DESC")
						));
		
		if($data==false)
		{
			return false;
		}
		
		foreach($data as $data)
		{
			$lists[]	=	array("id"	=>	$data['Product']['id'],"name"=>$data['Product']['contact_name']." (".$data['Parent']['name']." ".$data['Category']['name'].")");
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
		
		foreach($this->lists as $product)
		{
			$count++;
			$link	.=	$count.'. <a href="'.$this->settings['cms_url'].'Product/Edit/'.$product['id'].'/waiting_approval">'.$product['name'].'</a><br /><br />';
		}
		return $link;
	}
}
?>