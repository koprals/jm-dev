<?php
class UsController extends AppController
{
	var $components = array('Cookie');
	var $name		=	"Us";
	var $uses		=	null;
	var $helpers	=	array("Text","Number","General");
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function Contact()
	{
		//DEFINE BACK URL
		$this->Session->write('back_url',$this->settings['site_url'].$this->params["url"]["url"]);
		
		//DEFINE CATEGORY
		$this->loadModel("ContactCategory");
		$contact_category_id	=	$this->ContactCategory->find("list");
		$this->set(compact("contact_category_id"));
	}
	
	function ProcessContact()
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		$err				=	array();
		App::import('Sanitize');
		$this->loadModel("Contact");
		$this->Contact->set($this->data);
		$this->Contact->ValidateSendPm();
		
		if($this->Contact->validates())
		{
			$rand_id								=	$this->Action->GetRandomUser();
			$this->data['Contact']['message']		=	Sanitize::html($this->data['Contact']['message']);
			$this->data['Contact']['rand_id']		=	$rand_id;
			$this->data['Contact']['user_id']		=	$this->user_id;
			$this->Contact->create();
			$this->Contact->save($this->data);
			$contact_id								=	$this->Contact->getLastInsertId();
			$out									=	array("status"=>true,"error"=>$this->settings['site_url']."Us/SuccessSend");
			
			//CLEAR CACHE TESTIMONI
			@unlink(CACHE.'views'.DS.'element__testimonial');
			
			//SEND MESSAGE TO ADMIN
			$this->loadModel("ContactCategory");
			$category		=	$this->ContactCategory->findById($this->data['Contact']['contact_category_id']);
			$logo_url		=	$this->settings['logo_url'];
			$site_url		=	$this->settings['site_url'];
			$site_name		=	$this->settings['site_name'];
			$sender_name	=	$this->data['Contact']['from'];
			$comment		=	$this->data['Contact']['message'];
			$sender_email	=	$this->data['Contact']['email'];
			$sender_phone	=	!empty($this->data['Contact']['telp']) ? $this->data['Contact']['telp'] : "-";
			$category_name	=	$category['ContactCategory']['name'];
			$link			=	$this->settings['cms_url']."Pesan/Edit/".$contact_id;
			$s_search		=	array("[sender_name]");
			$s_replace		=	array($sender_name);
			$search 		=	array('[logo_url]','[site_url]','[site_name]','[sender_name]','[comment]','[sender_email]','[sender_phone]','[category_name]','[link]');
			
			$replace 		=	array($logo_url,$site_url,$site_name,$sender_name,$comment,$sender_email,$sender_phone,$category_name,$link);
			
			$this->Action->EmailSend('admin_alert_user_contactus', $this->settings["admin_mail"], $search, $replace,$s_search,$s_replace,"Contact",$contact_id);
			
		}
		else
		{
			$error	=	$this->Contact->InvalidFields();
			foreach($this->data['Contact'] as $k=>$v)
			{
				if(array_key_exists($k,$error))
				{
					$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);
				}
				elseif(empty($v) OR (is_array($v) AND empty($v["name"])))
				{
					$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
				}
				else
				{
					$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
				}
			}
			$out	=	array("status"=>false,"error"=>$err);
		}
		$this->set("data",$out);
		$this->render(false);
	}
	
	function SuccessSend()
	{
	
	}
	
	function Testimoni($id)
	{
		$this->loadModel("Contact");
		$this->paginate	=	array(
			"Contact"	=>	array(
				"limit"			=>	12,
				"conditions"	=>	array(
					"Contact.contact_category_id"	=>	"2",
					"Contact.publish"				=>	"1"
				),
				"order"			=>	array("if(Contact.id='{$id}',1,0) DESC","Contact.id DESC")
			)
		);
		$data	=	$this->paginate("Contact");
		$this->set(compact("data","jml","id"));
	}
	
	function ProccessAddTestimoni()
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		$err				=	array();
		App::import('Sanitize');
		$this->loadModel("Contact");
		$this->Contact->set($this->data);
		$this->Contact->ValidateSendPm();
		
		if($this->Contact->validates())
		{
			$rand_id											=	$this->Action->GetRandomUser();
			$this->data['Contact']['message']					=	Sanitize::html($this->data['Contact']['message']);
			$this->data['Contact']['rand_id']					=	$rand_id;
			$this->data['Contact']['user_id']					=	$this->user_id;
			$this->data['Contact']['contact_category_id']		=	"2";
			$this->data['Contact']['from']						=	empty($this->data['Contact']['from']) ? $this->profile["Profile"]["fullname"] : $this->data['Contact']['from'];
			$this->data['Contact']['email']						=	empty($this->data['Contact']['email']) ? $this->profile["User"]["email"] : $this->data['Contact']['email'];
			
			$this->Contact->create();
			$this->Contact->save($this->data);
			$contact_id											=	$this->Contact->getLastInsertId();
			$out												=	array("status"=>true,"error"=>$this->settings['site_url']."Us/SuccessSend");
			
			//SEND MESSAGE TO ADMIN
			$this->loadModel("ContactCategory");
			$category		=	$this->ContactCategory->findById($this->data['Contact']['contact_category_id']);
		
			$logo_url		=	$this->settings['logo_url'];
			$site_url		=	$this->settings['site_url'];
			$site_name		=	$this->settings['site_name'];
			$sender_name	=	$this->data['Contact']['from'];
			$comment		=	$this->data['Contact']['message'];
			$sender_email	=	$this->data['Contact']['email'];
			$sender_phone	=	!empty($this->data['Contact']['telp']) ? $this->data['Contact']['telp'] : "-";
			$category_name	=	$category['ContactCategory']['name'];
			$link			=	$this->settings['cms_url']."Pesan/Edit/".$contact_id;
			$s_search		=	array("[sender_name]");
			$s_replace		=	array($sender_name);
			$search 		=	array('[logo_url]','[site_url]','[site_name]','[sender_name]','[comment]','[sender_email]','[sender_phone]','[category_name]','[link]');
			
			$replace 		=	array($logo_url,$site_url,$site_name,$sender_name,$comment,$sender_email,$sender_phone,$category_name,$link);
			
			$this->Action->EmailSend('admin_alert_user_contactus', $this->settings["admin_mail"], $search, $replace,$s_search,$s_replace,"Contact",$contact_id);
			
			//CLEAR CACHE TESTIMONI
			@unlink(CACHE.'views'.DS.'element__testimonial');
			
		}
		else
		{
			$error	=	$this->Contact->InvalidFields();
			foreach($this->data['Contact'] as $k=>$v)
			{
				if(array_key_exists($k,$error))
				{
					$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);
				}
				elseif(empty($v) OR (is_array($v) AND empty($v["name"])))
				{
					$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
				}
				else
				{
					$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
				}
			}
			$out	=	array("status"=>false,"error"=>$err);
		}
		$this->set("data",$out);
		$this->render(false);
	}
}
?>