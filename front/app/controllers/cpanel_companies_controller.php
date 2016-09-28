<?php
class CpanelCompaniesController extends AppController
{
	var $name	=	"CpanelCompanies";
	var $uses	=	array('Company');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	
	function Index()
	{
		$this->Session->write('back_url',$this->settings['site_url'].'Cpanel/UpdateProfile');
		$this->set("active_code","company_profile");
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DEFINE EXTENDED PHONE
		$this->loadModel('ExtendedPhone');
		$ext_phone	=	$this->ExtendedPhone->find("all",array(
							'conditions'	=>	array(
								'ExtendedPhone.user_id'	=>	$this->user_id,
								'ExtendedPhone.type'	=>	2
							),
							'order'	=>	array('ExtendedPhone.id ASC')
						));
		$this->set(compact("ext_phone"));
	}
	
	function ProcessUpdateProfile()
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		App::import('Sanitize');
		$err				=	array();
		$invalidBookFields	=	array();
		
		if(empty($this->user_id))
		{
			$out				=	array("status"=>true,"error"=>$this->settings['site_url'].'Users/Login');
			$this->set("data",$out);
			$this->render(false);
			return;
		}
		
		if(!empty($this->data))
		{
			$invalidBookFields = array();
			$this->loadModel('ExtendedPhone');
			foreach($this->data['ExtendedPhone'] as $index => $ExtendedPhone)
			{
				$data = array('ExtendedPhone' => $ExtendedPhone);
				$data['ExtendedPhone']['phone']	=	trim($data['ExtendedPhone']['phone']);
				$this->ExtendedPhone->set($data);
				if (!$this->ExtendedPhone->validates())
				{
					$invalidBookFields[$index] = $this->ExtendedPhone->invalidFields();
					$err[]	=	array("key"=>"phone".$index,"status"=>"false","value" => $this->General->getArrayFirstIndex($this->ExtendedPhone->invalidFields()));
				}
				elseif(empty($ExtendedPhone['phone']))
				{
					$err[]	=	array("key"=>"phone".$index,"status"=>"blank","value"=>"");
				}
				elseif($this->ExtendedPhone->validates())
				{
					$err[]	=	array("key"=>"phone".$index,"status"=>"true","value"=>"");
				}
			}
			
			
			//VALIDATES
			$this->Company->set($this->data);
			$this->Company->InitiateValidate();
			
			if($this->Company->validates() && empty($invalidBookFields))
			{
				//SAVE Company
				$this->data['Company']['name']	=	Sanitize::html($this->data['Company']['name']);
				$user_id	=	$this->user_id;
				$company	=	$this->Company->saveAll(
									array(
										'id'				=>	$this->profile['Company']['id'],
										'user_id'			=>	$user_id,
										'name'				=>	$this->data['Company']['name'],
										'address'			=>	$this->data['Company']['address'],
										'province_id'		=>	$this->data['Company']['province'],
										'city_id'			=>	$this->data['Company']['city'],
										'lat'				=>	$this->data['Company']['lat'],
										'lng'				=>	$this->data['Company']['lng'],
										'phone'				=>	$this->data['Company']['phone'],
										'fax'				=>	$this->data['Company']['fax'],
										'website'			=>	$this->data['Company']['website'],
										'description'		=>	$this->data['Company']['description'],
									)
								);
				
				//UPDATE PRODUCT
				$this->loadModel('Product');
				$updt_product	=	$this->Product->updateAll(
										array(
											'Product.contact_name'	=>	"'".$this->data['Company']['name']."'",
											'Product.address'		=>	"'".$this->data['Company']['address']."'",
											'Product.province_id'	=>	"'".$this->data['Company']['province']."'",
											'Product.city_id'		=>	"'".$this->data['Company']['city']."'",
											'Product.ym'			=>	"'".$this->data['Company']['ym']."'",
											'Product.lat'			=>	"'".$this->data['Company']['lat']."'",
											'Product.lng'			=>	"'".$this->data['Company']['lng']."'"
										),
										array(
											'Product.user_id'		=>	$this->user_id,
											'Product.data_type'		=>	2,
										)
									);
				
				
				//UPDATE EXTENDED PHONE
				$this->loadModel('ExtendedPhone');
				$delete	=	$this->ExtendedPhone->deleteAll(array('ExtendedPhone.user_id'=>$user_id,'ExtendedPhone.type'=>2));
				foreach($this->data['ExtendedPhone'] as $k => $v)
				{
					$this->ExtendedPhone->create();
					$save	=	$this->ExtendedPhone->saveAll(
									array('phone'	=>	str_replace(" ","",trim($v['phone'])),'user_id'	=> $this->user_id,'type'=>2)
								);
				}
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Cpanel/CompanyProfile');
			}
			else
			{
				$error	=	$this->Company->InvalidFields();
				foreach($this->data['Company'] as $k=>$v)
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
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
}
?>