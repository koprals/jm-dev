<?php
class ProfilController extends AppController
{
	var $name			=	"Profil";
	var $uses			=	null;
	var $helpers		=	array("Text","Number","General");
	
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function ListMember($current_city="all_cities")
	{
		$this->set("current_menu","dealer");
		$this->set("current_city",$current_city);
		
		$this->loadModel("ProvinceGroup");	
		$daftar_kota	=	array();
		$daftar_kota	=	$this->ProvinceGroup->DisplayProvinceGroup();
		$this->set("daftar_kota",$daftar_kota);
		$conditions["User.userstatus_id"]		=	1;
		$conditions["Company.companystatus_id"]	=	1;
		
		if($current_city!=="all_cities")
		{
			$display_city	=	$this->ProvinceGroup->findById($current_city);
			$display_title	=	$display_city['ProvinceGroup']['name'];
			$this->loadModel("Province");
			$city_id		=	$this->Province->find("list",array(
									"conditions"	=>	array(
										"Province.group_id"	=>	$current_city
									),
									"fields"		=>	array("Province.id")
								));
			
			$conditions["Company.city_id"]	=	$city_id;
		}
		else
		{
			$display_title	=	"SEMUA KOTA" ;
		}
		$title_for_layout	=	$this->settings['site_name'].": Daftar dealer motor baru dan bekas ".$display_title;
		$site_description	=	$title_for_layout;
		$site_keywords		=	implode(", ",explode(" ",$title_for_layout));
		
		$this->set(compact("data","title_for_layout","site_description","site_keywords"));
		
		$this->set("display_title",$display_title);
		
		//GET DATA
		$this->loadModel("Company");
		$this->Company->bindModel(
			array(
				"belongsTo" => array(
					'Province' => array(
						'className' 	=> 'Province',
						'foreignKey' 	=> 'city_id'
					)
				)
			),
			false
		);
		$this->paginate	=	array(
			"Company"	=>	array(
				"limit"			=>	10,
				"order"			=>	"Company.name",
				"conditions"	=>	$conditions
			)
		);
		$data	=	$this->paginate("Company");
		$this->set(compact("data"));
		$this->Company->unbindModel(array("belongsTo" => array('Province')),false);
	}
	
	function GetPhone($user_id)
	{
		$this->layout	=	"json";
		$this->loadModel("ExtendedPhone");
		$data	=	$this->ExtendedPhone->find("list",array(
						"conditions"	=>	array(
							"ExtendedPhone.user_id"	=>	$user_id,
							"ExtendedPhone.type"	=>	"2",
						),
						"fields"	=>	array("ExtendedPhone.phone")
					));
		
		$out	=	!empty($data) ? implode(", ",$data) : "";
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	
	function DetailProfile($user_id)
	{
		$this->loadModel("Profile");
		
		//DEFINE BACK URL
		$this->Session->write('back_url',$this->settings['site_url'].$this->params["url"]["url"]);
		
		$this->Profile->bindModel(
			array(
				"belongsTo" => array(
					'Company' => array(
						'className' 	=> 'Company',
						'foreignKey' 	=> false,
						"conditions"	=> "Profile.user_id = Company.user_id"
					),
					'Province' => array(
						'className' 	=> 'Province',
						'foreignKey' 	=> 'city_id'
					)
				)
			)
		);
		
		$data	=	$this->Profile->find("first",array(
						"conditions"	=>	array(
							"Profile.user_id"			=>	$user_id,
							"User.userstatus_id"		=>	1
						)
					));
		
		if($data)
		{
			$this->loadModel("ExtendedPhone");
			
			$extend	=	$this->ExtendedPhone->find("list",array(
							"conditions"	=>	array(
								"ExtendedPhone.type"		=>	1,
								"ExtendedPhone.user_id"		=>	$data["Profile"]["user_id"]
							),
							"fields"	=>	array("ExtendedPhone.phone")
						));
			$extend_phone	=	(!empty($extend)) ? ", ".implode(", ",$extend) : "";
			$this->set(compact("data","extend_phone"));
		}
	}
	
	function SendMessageProfile()
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>array("key"=>"from","status"=>"false","value"=>"Detail member tidak ditemukan"));
		$err				=	array();
		
		if(!empty($this->data))
		{
			$profile_id		=	$this->data["Profile"]["profile_id"];
			$this->loadModel("Profile");
			$detail			=	$this->Profile->findById($profile_id);
			
			if($detail)
			{
				$this->data["Profile"]["to"]	=	$detail["User"]["email"];
				$this->Profile->ValidateSendPm();
				$this->Profile->set($this->data);
				
				if($this->Profile->validates())
				{
					$logo_url		=	$this->settings['logo_url'];
					$site_url		=	$this->settings['site_url'];
					$site_name		=	$this->settings['site_name'];
					$sender_name	=	$this->data['Profile']['from'];
					$comment		=	$this->data['Profile']['message'];
					$sender_mail	=	$this->data['Profile']['email'];
					$no_telp		=	(!empty($this->data['Profile']['telp'])) ? " atau melalui no telp: ".$this->data['Profile']['telp'] : "";
					
					$s_search		=	array("[subject]");
					$s_replace		=	array($this->data['Profile']['subject']);
					
					$search 		=	array('[logo_url]','[site_url]','[site_name]','[sender_name]','[comment]','[sender_mail]','[no_telp]');
                	
					$replace 		=	array($logo_url,$site_url,$site_name,$sender_name,$comment,$sender_mail,$no_telp);
				
					$this->Action->EmailSend('pm_dealer', $detail['User']['email'], $search, $replace,$s_search,$s_replace,"Product",$product_id);
				
					$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Iklan/Detail/'. $product_id);
				
				}
				else
				{
					$error	=	$this->Profile->InvalidFields();
					foreach($this->data['Profile'] as $k=>$v)
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
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function ListItemProfile($user_id)
	{
		$this->layout	=	"ajax";
		$this->loadModel("Product");
		$this->Product->unbindModel(array(
			"belongsTo"	=>	array(
				'Category',
				'Parent'
			)	
		));
		
		$this->Product->bindModel(
                array(
					'hasOne' => array(
						'ProductImage' => array(
							'className'		=> 'ProductImage',
							'foreignKey'	=> 'product_id',
							'conditions'	=> "ProductImage.is_primary = '1'"
						)
					),
					'belongsTo'	=>	array(
						'Province' => array(
							'className' 	=>	'Province',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Product.city_id = Province.id'
						)
					)
				), false
        );
		
		$this->paginate	=	array(
			"Product"	=>	array(
				"limit"			=>	8,
				"conditions"	=>	array(
					"Product.productstatus_id"		=>	1,
					"Product.productstatus_user"	=>	1,
					"Product.data_type"				=>	1,
					"Product.user_id"				=>	$user_id
				),
				"order"		=>	array("IF( Product.sold = '0', 1, 0) DESC,Product.price ASC,Product.id DESC"),
				"group"		=>	array("Product.id")
			)
		);
		$data	=	$this->paginate("Product");
		$this->set(compact("data","user_id"));
	}
	
	function DetailDealer($dealer_id)
	{
		$this->loadModel("Company");
		
		//DEFINE BACK URL
		$this->Session->write('back_url',$this->settings['site_url'].$this->params["url"]["url"]);
		
		$this->Company->bindModel(
			array(
				"belongsTo" => array(
					'Profile' => array(
						'className' 	=> 'Profile',
						'foreignKey' 	=> false,
						"conditions"	=> "Company.user_id = Profile.user_id"
					),
					'Province' => array(
						'className' 	=> 'Province',
						'foreignKey' 	=> 'city_id'
					)
				)
			)
		);
		
		$data	=	$this->Company->find("first",array(
						"conditions"	=>	array(
							"Company.id"			=>	$dealer_id,
							"User.userstatus_id"	=>	1
						)
					));
		
		if($data)
		{
			$this->loadModel("ExtendedPhone");
			
			$extend	=	$this->ExtendedPhone->find("list",array(
							"conditions"	=>	array(
								"ExtendedPhone.type"		=>	2,
								"ExtendedPhone.user_id"		=>	$data["Company"]["user_id"]
							),
							"fields"	=>	array("ExtendedPhone.phone")
						));
			$extend_phone	=	(!empty($extend)) ? ", ".implode(", ",$extend) : "";
			
			//BBCODES
			App::import('Vendor','Decoda' ,array('file'=>'decoda/Decoda.php'));
			$code 				= 	new Decoda();
			$code->addFilter(new DefaultFilter());
			$code->addFilter(new TextFilter());
			$code->addFilter(new UrlFilter());
			$code->addFilter(new ListFilter());
			$code->addFilter(new ImageFilter());
			$code->addHook(new EmoticonHook());
			
			$code->reset($data["Company"]["description"]);
			$description		=	$code->parse();
		
			$this->set(compact("data","extend_phone","description"));
		}
	}
	
	function ListItemCompany($user_id)
	{
		$this->layout	=	"ajax";
		$this->loadModel("Product");
		$this->Product->unbindModel(array(
			"belongsTo"	=>	array(
				'Category',
				'Parent'
			)	
		));
		
		$this->Product->bindModel(
                array(
					'hasOne' => array(
						'ProductImage' => array(
							'className'		=> 'ProductImage',
							'foreignKey'	=> 'product_id',
							'conditions'	=> "ProductImage.is_primary = '1'"
						)
					),
					'belongsTo'	=>	array(
						'Province' => array(
							'className' 	=>	'Province',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Product.city_id = Province.id'
						)
					)
				), false
        );
		
		$this->paginate	=	array(
			"Product"	=>	array(
				"limit"			=>	8,
				"conditions"	=>	array(
					"Product.productstatus_id"		=>	1,
					"Product.productstatus_user"	=>	1,
					"Product.data_type"				=>	2,
					"Product.user_id"				=>	$user_id
				),
				"order"		=>	array("IF( Product.sold = '0', 1, 0) DESC,Product.price ASC,Product.id DESC"),
				"group"		=>	array("Product.id")
			)
		);
		$data	=	$this->paginate("Product");
		$this->set(compact("data","user_id"));
	}
	
	function SendMessageDealer()
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>array("key"=>"from","status"=>"false","value"=>"Detail dealer tidak ditemukan"));
		$err				=	array();
		
		if(!empty($this->data))
		{
			$company_id		=	$this->data["Company"]["company_id"];
			$this->loadModel("Company");
			$detail			=	$this->Company->findById($company_id);
			
			if($detail)
			{
				$this->data["Company"]["to"]	=	$detail["User"]["email"];
				$this->Company->ValidateSendPm();
				$this->Company->set($this->data);
				
				if($this->Company->validates())
				{
					$logo_url		=	$this->settings['logo_url'];
					$site_url		=	$this->settings['site_url'];
					$site_name		=	$this->settings['site_name'];
					$sender_name	=	$this->data['Company']['from'];
					$comment		=	$this->data['Company']['message'];
					$sender_mail	=	$this->data['Company']['email'];
					$no_telp		=	(!empty($this->data['Company']['telp'])) ? " atau melalui no telp: ".$this->data['Company']['telp'] : "";
					
					$s_search		=	array("[subject]");
					$s_replace		=	array($this->data['Company']['subject']);
					
					$search 		=	array('[logo_url]','[site_url]','[site_name]','[sender_name]','[comment]','[sender_mail]','[no_telp]');
                	
					$replace 		=	array($logo_url,$site_url,$site_name,$sender_name,$comment,$sender_mail,$no_telp);
				
					$this->Action->EmailSend('pm_dealer', $detail['User']['email'], $search, $replace,$s_search,$s_replace,"Product",$product_id);
				
					$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Iklan/Detail/'. $product_id);
				
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
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
}
?>