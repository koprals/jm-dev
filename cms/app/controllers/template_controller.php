<?php
class TemplateController extends AppController 
{

	var $name 		= 'Template';
	var $uses		=	null;
	var $components	=	array('Action','General');
	var $helpers	=	array('Product','Tree');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"ajax";
	}
	
	
	
	function HeaderMenu($parent_code="dashboard")
	{
		//LOAD MODEL MENU
		$this->loadModel('CmsMenu');
		
		//FIND TOP
		$TOP	=	$this->CmsMenu->findByName('TOP');
		$data	=	$this->CmsMenu->children($TOP['CmsMenu']['id'],true);
		
		$this->set(compact("data","parent_code"));
	}

	function FooterMenu()
	{
	
	}
	
	function GetJsonProfile($user_id)
	{
		$this->layout	=	"json";
		$this->loadModel('Profile');
		$data			=	$this->Profile->findByUserId($user_id);
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	function Map()
	{
		$this->layout	=	"ajax";
		$this->set("latitude","-6.233394646324949");
		$this->set("longitude","106.87774658203125");
		$this->set("zoom","14");
	}
	
	function MapProfile($user_id)
	{
		$this->layout	=	"ajax";
		$this->loadModel('Profile');
		$data			=	$this->Profile->findByUserId($user_id);
		$this->set("latitude","-6.233394646324949");
		$this->set("longitude","106.87774658203125");
		$this->set("profile",$data);
		$this->set("zoom","14");
	}
	
	function SideLeft($parent_code,$child_code)
	{
		//LOAD MODEL
		$this->loadModel('CmsMenu');
		
		//FIND DATA
		$parent	=	$this->CmsMenu->findByCode($parent_code);
		$data	=	$this->CmsMenu->children($parent['CmsMenu']['id'],false);
		
		
		$child	=	$this->CmsMenu->findByCode($child_code);
		$tree	=	$this->CmsMenu->getpath($child['CmsMenu']['id']);
		unset($tree[0]);
		$breadcrumb	=	"";
		
		foreach($tree as $k =>$val)
		{
			if($k == 1)
			{
				$breadcrumb	.=	'<a href="'.$this->settings['cms_url'].$val['CmsMenu']['url'].'" class="nav_2">'.$val['CmsMenu']['name'].'</a>';
				
			}
			elseif($k == count($tree))
			{
				$breadcrumb	.=	'<span class="text2">&raquo;</span><div class="text3">'.$val['CmsMenu']['name'].'</div>';
			}
			else
			{
				$breadcrumb	.=	'<span class="text2">&raquo;</span><a href="'.$this->settings['cms_url'].$val['CmsMenu']['url'].'" class="nav_2">'.$val['CmsMenu']['name'].'</a>';
			}
		}
		
		$this->set("data",$data);
		$this->set("parent_name",$parent['CmsMenu']['name']);
		$this->set("child_code",$child_code);
		$this->set("breadcrumb",$breadcrumb);
		$this->set("current_id",$child['CmsMenu']['id']);
		
	}
	
	function SelectCity()
	{
		$this->layout	=	"ajax";
		$this->loadModel("Province");
		$province_id	=	$_POST['province_id'];
		$city_id		=	$_POST['city_id'];
		$model			=	!empty($_POST['model']) ? ucfirst($_POST['model']) : "User";
		$class			=	!empty($_POST['class']) ? strtolower($_POST['class']) : "sel1";
		$empty			=	isset($_POST['empty']) ? $_POST['empty'] : false;
		$style			=	($class=="sel1") ? "" : "float:left;width:91%;";
		//LOAD PROVINCE MODEL
        $fprovince = $this->Province->find('all', array(
                    'conditions' => array(
                        'Province.status' 				=> 1,
						'Province.	province_id'		=>	$province_id
                    ),
					'order'	=>	array('Province.name ASC')
                ));
        foreach ($fprovince as $k => $v) {
            $city[$v['Province']['id']] = $v['Province']['name'];
        }
        $this->set("city", $city);
		$this->set("selected", $city_id);
		$this->set("model", $model);
		$this->set("class", $class);
		$this->set("empty", $empty);
		$this->set("style", $style);
	}
	
	function UserLeftMenu($user_id,$active_code="")
	{
		$this->loadModel("User");
		$data	=	$this->User->find('first',array(
						'conditions'	=>	array(
							'User.id'				=>	$user_id,
							'User.userstatus_id != '	=>	-10
						)
					));
		$this->set(compact("user_id","data","active_code"));
		$this->layout	=	"ajax";
	}
	
	function SideLeftEmail()
	{
		$this->layout	=	"ajax";
	}
	
	function GetStatusMessageUser()
	{
		$this->layout	=	"ajax";
		$type_msg		=	$_GET['type'];
		$user_id		=	$_GET['user_id'];
		
		//GET PRODUCT DETAIL
		$this->loadModel('User');
		$user		=	$this->User->findById($user_id);
		
		switch($type_msg)
		{
			case "-1" : 
				$html			=	$this->GetHtmlBlockedUser($user);
				break;
			case "-2" : 
				$html			=	$this->GetHtmlSuspend($user);
				break;
			case "-10" : 
				$html			=	$this->GetHtmlUserDeleted($user);
				break;
			default: 
				$html			=	"";
			default: 
				$html			=	"";
		}
		$this->set("html",$html);
	}
	
	function GetStatusMessage()
	{
		$this->layout	=	"ajax";
		$type_msg		=	$_GET['type'];
		$product_id		=	$_GET['product_id'];
		
		//GET PRODUCT DETAIL
		$this->loadModel('Product');
		$product		=	$this->Product->findById($product_id);
		
		switch($type_msg)
		{
			case "-1" : 
				$html			=	$this->GetHtmlEditing($product);
				break;
			case "1" : 
				$html			=	$this->GetHtmlApprove($product);
				break;
			case "-10" : 
				$html			=	$this->GetHtmlDeleted($product);
				break;
			default: 
				$html			=	"";
		}
		$this->set("html",$html);
	}
	
	
	function GetHtmlEditing($product)
	{
		App::import('Helper', 'Number');
		$Number 		= 	new NumberHelper();
		$emailID		=	"admin_editing_required";
		$logo_url		=	$this->settings['logo_url'];
		$contact_name	=	$product['Product']['contact_name'];
		$category		=	$product['Parent']['name'];
		$sub_category	=	$product['Category']['name'];
		$contact		=	$product['Product']['contact_name'];
		$address		=	$product['Product']['address'];
		$price			=	$Number->format($product['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		$date			=	date("d-M-Y H:i:s",strtotime($product['Product']['created']));
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		
		$link			=	$this->settings['site_url'].'EditProduct/Index/'.$product['Product']['id'];
		
		$search			=	array('[logo_url]','[contact_name]','[category]','[sub_category]','[contact]','[address]','[price]','[date]','[site_name]','[site_url]','[link]');
		$replace		=	array($logo_url,$contact_name,$category,$sub_category,$contact,$address,$price,$date,$site_name,$site_url,$link);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	function GetHtmlDeleted($product)
	{
		App::import('Helper', 'Number');
		$Number 		= 	new NumberHelper();
		$emailID		=	"admin_product_deleted";
		$logo_url		=	$this->settings['logo_url'];
		$contact_name	=	$product['Product']['contact_name'];
		$category		=	$product['Parent']['name'];
		$sub_category	=	$product['Category']['name'];
		$contact		=	$product['Product']['contact_name'];
		$address		=	$product['Product']['address'];
		$price			=	$Number->format($product['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		$link			=	$this->settings['site_url'].'Product/Deactivated/'.$product['Product']['id'];
		$date			=	date("d-M-Y H:i:s",strtotime($product['Product']['created']));
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		$search			=	array('[logo_url]','[contact_name]','[category]','[sub_category]','[contact]','[address]','[price]','[date]','[site_name]','[site_url]','[link]');
		$replace		=	array($logo_url,$contact_name,$category,$sub_category,$contact,$address,$price,$date,$site_name,$site_url,$link);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	function GetHtmlApprove($product)
	{
		App::import('Helper', 'Number');
		$Number 		= 	new NumberHelper();
		$emailID		=	"admin_product_approval";
		$logo_url		=	$this->settings['logo_url'];
		$contact_name	=	$product['Product']['contact_name'];
		$category		=	$product['Parent']['name'];
		$sub_category	=	$product['Category']['name'];
		$contact		=	$product['Product']['contact_name'];
		$address		=	$product['Product']['address'];
		$price			=	$Number->format($product['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		$date			=	date("d-M-Y H:i:s",strtotime($product['Product']['created']));
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		$cms_url		=	$this->settings['cms_url'];
		$link			=	$site_url."Iklan/Detail/".$product['Product']['id']."/".$product['Product']['seo_name'];
		$link_edit		=	$site_url."ManageProducts/Index/".$product['Product']['id'];
		$link_add		=	$site_url."Cpanel/AddProduct";
		
		$search			=	array('[logo_url]','[contact_name]','[category]','[sub_category]','[contact]','[address]','[price]','[date]','[site_name]','[site_url]','[cms_url]','[link]','[link_edit]','[link_add]');
		$replace		=	array($logo_url,$contact_name,$category,$sub_category,$contact,$address,$price,$date,$site_name,$site_url,$cms_url,$link,$link_edit,$link_add);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	
	function GetHtmlBlockedUser($user)
	{
		App::import('vendor', 'encryption_class');
		
		$EmailLog		=	ClassRegistry::Init("EmailLog");
		$USERS			=	ClassRegistry::Init("User");
		
		$DETAIL_EMAIL	=	$EmailLog->find('first',array(
								'conditions'	=>	array(
									"OR"	=>	array(
										array(
											'EmailLog.model'			=>	'User',
											'EmailLog.model_id'			=>	$user['User']['id']
										),
										'EmailLog.to'					=>	$user['User']['email']
									),
									'EmailSettings.name'		=>	"regver"	
								)
							));
		
		$emailID		=	"admin_user_blocked";
		$logo_url		=	$this->settings['logo_url'];
		$fullname		=	$user['Profile']['fullname'];
		$last_send		=	date("d-M-Y H:i:s",$DETAIL_EMAIL['EmailLog']['last_send']);
		$email			=	$user['User']['email'];
		$created		=	date("d-M-Y H:i:s",strtotime($user['User']['created']));
		
		$address		=	$user['Profile']['address'];
		$phone			=	$user['Profile']['phone'];
		$type			=	($user['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
		$vcode			=   $USERS->getValidation(trim($email));
		
		$encrypt		= 	new encryption;
		$param			= 	$encrypt->my_encrypt($user['User']['id'] . "|" . $vcode);
		$link 			= 	$this->settings['site_url'] . "Users/Verification/param:" . $param;
		
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		
		$search			=	array('[logo_url]','[fullname]','[last_send]','[email]','[created]','[address]','[phone]','[type]','[site_name]','[site_url]','[link]');
		
		$replace		=	array($logo_url,$fullname,$last_send,$email,$created,$address,$phone,$type,$site_name,$site_url,$link);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	
	function GetHtmlSuspend($user)
	{
		$emailID		=	"admin_user_suspend";
		$logo_url		=	$this->settings['logo_url'];
		$fullname		=	$user['Profile']['fullname'];
		$email			=	$user['User']['email'];
		$created		=	date("d-M-Y H:i:s",strtotime($user['User']['created']));
		
		$address		=	$user['Profile']['address'];
		$phone			=	$user['Profile']['phone'];
		$type			=	($user['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
		
		
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		
		$search			=	array('[logo_url]','[fullname]','[email]','[created]','[address]','[phone]','[type]','[site_name]','[site_url]');
		
		$replace		=	array($logo_url,$fullname,$email,$created,$address,$phone,$type,$site_name,$site_url);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	function GetHtmlUserDeleted($user)
	{
		App::import('vendor', 'encryption_class');
		
		$EmailLog		=	ClassRegistry::Init("EmailLog");
		$USERS			=	ClassRegistry::Init("User");
		
		$DETAIL_EMAIL	=	$EmailLog->find('first',array(
								'conditions'	=>	array(
									"OR"	=>	array(
										array(
											'EmailLog.model'			=>	'User',
											'EmailLog.model_id'			=>	$user['User']['id']
										),
										'EmailLog.to'					=>	$user['User']['email']
									),
									'EmailSettings.name'		=>	"regver"	
								)
							));
		
		$emailID		=	"admin_user_blocked";
		$logo_url		=	$this->settings['logo_url'];
		$fullname		=	$user['Profile']['fullname'];
		$last_send		=	date("d-M-Y H:i:s",$DETAIL_EMAIL['EmailLog']['last_send']);
		$email			=	$user['User']['email'];
		$created		=	date("d-M-Y H:i:s",strtotime($user['User']['created']));
		
		$address		=	$user['Profile']['address'];
		$phone			=	$user['Profile']['phone'];
		$type			=	($user['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
		$vcode			=   $USERS->getValidation(trim($email));
		
		$encrypt		= 	new encryption;
		$param			= 	$encrypt->my_encrypt($user['User']['id'] . "|" . $vcode);
		$link 			= 	$this->settings['site_url'] . "Users/Verification/param:" . $param;
		
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		
		$search			=	array('[logo_url]','[fullname]','[last_send]','[email]','[created]','[address]','[phone]','[type]','[site_name]','[site_url]','[link]');
		
		$replace		=	array($logo_url,$fullname,$last_send,$email,$created,$address,$phone,$type,$site_name,$site_url,$link);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
}
?>