<?php
class ServiceUserIsProductSoldController extends AppController
{
	var $name 			=	'ServiceUserIsProductSold';
	var $uses 			=	array('Service');
	var $serviceName	=	"user_product_is_sold";
	var $sold			=	1;
	var $lists			=	array();
	var $components		=	array('Action');
	var $mailName		=	"cron_product_is_sold";
	var $setings;
	var $emailID;
	
	
	function Index()
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
		if(!$this->IsSerciceActive() or !$this->GetProducts())
		{
			echo "0";
			exit;
		}
		
		foreach($this->lists as $data)
		{
			$to					=	$data['email'];
			$contact_name		=	$data['name'];
			$user_id			=	$data['user_id'];
			$logo_url			=	$this->settings['logo_url'];
			$site_name			=	$this->settings['site_name'];
			$site_url			=	$this->settings['site_url'];
			$cms_url			=	$this->settings['cms_url'];
			$link_activate		=	$data['link_activate'];
			$category			=	$data['category'];
			$sub_category		=	$data['sub_category'];
			$address			=	$data['address'];
			$price				=	$data['price'];
			$date				=	$data['date'];
			$link_edit			=	$data['link_edit'];
			$link_add			=	$data['link_add'];
			$product_name		=	$category." ".$sub_category;
			
			
			
			$search				=	array('[logo_url]', '[contact_name]', '[link_activate]', '[site_name]','[category]','[sub_category]','[contact_name]','[address]','[price]','[date]','[link_edit]','[link_add]','[site_url]','[cms_url]');
			
			$replace			=	array($logo_url, $contact_name, $link_activate, $site_name,$category,$sub_category,$contact_name,$address,$price,$date,$link_edit,$link_add,$site_url,$cms_url);
			
			$search_subject		=	array('[product_name]');
			$replace_subject	=	array($product_name);
			$send				=	$this->Action->EmailSend($this->mailName, $to, $search, $replace,$search_subject,$replace_subject,"User",$user_id);
		}
		echo "1";
		$this->render(false);
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
		App::import('Helper', 'Number');
		$Number 			= 	new NumberHelper();
		
		$lists		=	array();
		$product	=	ClassRegistry::Init('Product');
		$data		=	$product->find("all",array(
							"conditions"	=>	array(
								"Product.productstatus_id"		=>	1,
								"Product.productstatus_user"	=>	1,
								"Product.sold"					=>	0
							),
							"order"								=>	array('Product.id ASC')
						));
		
		
		if($data==false)
		{
			return false;
		}
		
		$EmailLog		=	ClassRegistry::Init("EmailLog");
		$EmailToken		=	ClassRegistry::Init("EmailToken");
		$User			=	ClassRegistry::Init("User");
		
		foreach($data as $data)
		{
			$member_email	=	$data['User']['email'];
			
			//CHECK CREDENTIAL USER
			$member			=	$User->findByEmail($member_email);
			
			//CHECK COUNT SEND DATA
			$COUNTLOG	=	$EmailLog->find("count",array(
								"conditions"	=>	array(
									"EmailLog.email_setting_id"	=>	$this->emailID,
									"OR"						=>	array(
										"EmailLog.to"				=>	$member_email,
										"AND"						=>	array(
											"EmailLog.model_id"		=>	$data['Product']['user_id'],
											"EmailLog.model"		=>	"User"
										)
									)
								)
							));
			
			if($COUNTLOG < 10)
			{
				//CHECK COUNT SEND DATA
				$SEND	=	$EmailLog->find("first",array(
								"conditions"	=>	array(
									"EmailLog.email_setting_id"	=>	$this->emailID,
									"OR"						=>	array(
										"EmailLog.to"				=>	$member_email,
										"AND"						=>	array(
											"EmailLog.model_id"		=>	$data['Product']['user_id'],
											"EmailLog.model"		=>	"User"
										)
									)
								)
							));
				
				$last_send		=	($SEND==false) ? strtotime($data['Product']['approved']) : $SEND['EmailLog']['last_send'];
				$last_week		=	mktime(0,0,0,date("m"),date("d")-7,date("Y"));
				$price			=	$Number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$token			=	$EmailToken->GetToken($data['Product']['user_id'],$data['Product']['id']);
				
				$link_activate	=	$this->settings['site_url']."Cpanel/ProductIsSold/1/token:".$token;
				$link_edit		=	$this->settings['site_url']."ManageProducts";
				$link_add		=	$this->settings['site_url']."Cpanel/AddProduct";
				
				
				if($last_send < $last_week)
				{
					$account_name	=	(!empty($member['Profile']['fullname'])) ? $member['Profile']['fullname'] :  $member['User']['email'];
					$lists[]		=	array(
											"user_id"		=>	$data['Product']['user_id'],
											"name"			=>	$data['Product']['contact_name'],
											"email"			=>	$member['User']['email'],
											"category"		=>	$data['Parent']['name'],
											"sub_category"	=>	$data['Category']['name'],
											"address"		=>	$data['Product']['address'],
											"price"			=>	$price,
											"date"			=>	date("d-M-Y H:i:s",strtotime($data['Product']['created'])),
											"link_activate"	=>	$link_activate,
											"link_edit"		=>	$link_edit,
											"link_add"		=>	$link_add,
										);	
				}
			}//END COUNT LOG
		}
		
		if(empty($lists)) return false;
		$this->lists	=	$lists;
		return true;
	}
}
?>