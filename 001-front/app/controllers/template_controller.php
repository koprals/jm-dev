<?php
class TemplateController extends AppController 
{

	var $name 		=	'Template';
	var $uses		=	null;
	var $helpers	=	array("Tree","General");
	
	function beforeFilter()
	{
		$this->layout	=	"ajax";
		parent::beforeFilter();
	}
	
	function HeaderMenu($current_menu=null)
	{
		//$rand	=	$this->Action->GetRandomUser();
		$this->set(compact("current_menu"));
		
		//LOAD MODE RAND USER
		$this->loadModel('RandomUser');
		$rand_detail	=	$this->RandomUser->findByRandId($rand);
		
		$rand_name	=	(empty($rand_detail['Profile']['id'])) ? $rand_detail['User']['email'] : $rand_detail['Profile']['fullname'];
		
		$this->set('rand_name',$rand_name);
		$this->set(compact("rand_detail"));
		
		//GET CATEGORY LIST
		if (($category = Cache::read('category_list')) === false)
		{
			$this->loadModel("Category");
			$category	=	$this->Category->DisplayCategorySearch();
			Cache::write('category_list', $category);
		}
		$this->set('category',$category);
		
		//GET PROVINCE GROUP
		if (($ProvinceGroup = Cache::read('group_list')) === false)
		{
			$this->loadModel("ProvinceGroup");
			$ProvinceGroup	=	$this->ProvinceGroup->DisplayProvinceGroup();
			Cache::write('group_list', $ProvinceGroup);
		}
		$this->set('ProvinceGroup',$ProvinceGroup);
		
		$this->render('header_menu');
	}
	
	function GetUrlSearch()
	{
		$category_id			=	$_GET['category_id'];
		$city_id				=	$_GET['city_id'];
		$city_name				=	$_GET['city_id'];
		$category_name			=	$_GET['category_id'];
		
		if($city_id !== "all_cities")
		{
			//GET CITY NAME
			$this->loadModel("ProvinceGroup");
			$group_name				=	$this->ProvinceGroup->findById($city_id);
			$city_name				=	$this->General->seoUrl($group_name['ProvinceGroup']['name']);
		}
		
		if($category_id	!==	"all_categories")
		{
			$this->loadModel("Category");
			$tree	=	$this->Category->getpath($category_id);
			unset($tree[0]);
			$display_category	=	"";
			foreach($tree as $tree)
			{
				$display_category	.=	$tree['Category']['name']." ";
			}
			$category_name			=	$this->General->seoUrl(substr($display_category,0,-1));
		}
		$seo_url			=	$category_name."_".$city_name.".html";
		$url				=	$this->settings['site_url']."DaftarMotor/".$category_id."/".$city_id."/".$seo_url;
		echo $url;
		$this->autoRender	=	false;
	}
	
	function HeaderMenuVerification($fullname)
	{
		//$rand	=	$this->Action->GetRandomUser();
		
		//LOAD MODE RAND USER
		$this->loadModel('RandomUser');
		$rand_detail	=	$this->RandomUser->findByRandId($rand);
		$this->set(compact("fullname"));
		$this->render('header_menu_verification');
	}

	function FooterMenu()
	{
	
	}
	
	function News()
	{
		$this->layout	=	"ajax";
		$this->loadModel("News");
		$data			=	$this->News->find("all",array(
								"conditions"	=>	array(
									"News.status"	=>	"1"
								),
								"order"	=>	array("News.id DESC"),
								"limit"	=> 5
							));
		$this->set(compact("data"));
	}
	
	function CheckLogin()
	{
		$this->layout		=	"json";
		$status				=	(empty($this->user_id) or is_null($this->user_id)) ? false : true;
		$msg				=	array("status"=>$status);
		$this->set("data",$msg);
		$this->render(false);
	}

	function Category($category_id="all_categories",$current_city="all_cities",$controller)
	{
		$this->layout	=	"ajax";
		$category_id	=	($category_id!="all_categories" && !is_numeric($category_id)) ? "all_categories" : $category_id;
		
		//LOAD MODEL
		$this->loadModel("Category");
		
		$stuff					=	$this->Category->children($this->Category->GetTop(),false,NULL,NULL,NULL,1,1);
		$data					=	$this->Category->findById($category_id);
		$this->set("stuff",$stuff);
		$this->set("current_id",$category_id);
		$this->set("current_parent_id",$data['Category']['parent_id']);
		
		
		$city_name	=	$current_city;
		if($current_city != "all_cities")
		{
			//GET CITY NAME
			$this->loadModel("ProvinceGroup");
			$group_name		=	$this->ProvinceGroup->findById($current_city);
			$city_name		=	$group_name['ProvinceGroup']['name'];
		}
		
		$this->set("city_name",$city_name);
		$this->set("current_city",$current_city);
		
		$this->set(compact("controller"));
	}
	
	function SelectCity()
	{
		$this->layout	=	"ajax";
		$this->loadModel("Province");
		$province_id	=	$_POST['province_id'];
		$current		=	$_POST['current'];
		$model			=	empty($_POST['model']) ? "User" : $_POST['model'];
		$class			=	empty($_POST['class']) ? "input3 style1 black text12 size45 kiri" : $_POST['class'];
		$style			=	empty($_POST['style']) ? "width:160px" : $_POST['style'];
		
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
		$this->set("current", $current);
		$this->set("model", $model);
		$this->set("class", $class);
		$this->set("style", $style);
	}
	
	function CpanelMenu($active_code)
	{
		$this->layout	=	"ajax";
		$this->loadModel("CpanelMenu");
		$is_merchants	=	$this->profile['User']['usertype_id'];
		$is_admin		=	$this->profile['User']['admintype_id'];
		
		$conditions		=	($is_merchants==2) ?
								array(
										'CpanelMenu.status'	=>	1,
										'CpanelMenu.parent_id IS NOT NULL',
										'CpanelMenu.admin_only'		=>	0
									)
							:
								array(
										'CpanelMenu.status'			=>	1,
										'CpanelMenu.merchant_only'	=>	0,
										'CpanelMenu.parent_id IS NOT NULL',
										'CpanelMenu.admin_only'		=>	0
									)
							;
		
		if($is_admin == "2" ) unset($conditions['CpanelMenu.admin_only']);
		
		$data			=	$this->CpanelMenu->find('all',array(
								'conditions'	=>	$conditions,
								'order'	=>	array('CpanelMenu.lft ASC')
							));
		$this->set("data",$data);
		$this->set("active_code",$active_code);
	}
	
	function TermConditionsUpload()
	{
		$this->layout	=	"ajax";
	}
	
	function TermConditions()
	{
		$this->layout	=	"ajax";
	}
	
	
	function ProvinceName($province_id)
	{
		$this->layout	=	"json";
		$this->loadModel('Province');
		$data			=	$this->Province->findByProvinceId($province_id);
		$province_name	=	($data==false) ? "" : $data['Province']['province'];
		$this->set("data",$province_name);
		$this->render(false);
	}
	
	function CityName($city_id)
	{
		$this->layout	=	"json";
		$this->loadModel('Province');
		$data			=	$this->Province->findById($city_id);
		$city_name	=	($data==false) ? "" : $data['Province']['name'];
		$this->set("data",$city_name);
		$this->render(false);
	}
	
	function ProvinceList()
	{
		$this->layout	=	"json";
		$this->loadModel('Province');
		$province = $this->Province->DisplayProvince();
		$this->set("data",$province);
		$this->render(false);
	}
	
	function GetJsonProfile()
	{
		$this->layout	=	"json";
		$this->set("data",$this->profile);
		$this->render(false);
	}
	
	function Rounded()
	{
		$this->layout	=	"rounded";
	}
	
	function Map()
	{
		$this->layout	=	"ajax";
		$this->set("latitude","-6.233394646324949");
		$this->set("longitude","106.87774658203125");
		$this->set("zoom","14");
	}
	
	function MapProfile()
	{
		$this->layout	=	"ajax";
		$this->set("latitude","-6.233394646324949");
		$this->set("longitude","106.87774658203125");
		$this->set("zoom","14");
	}
	
	function MapDealer()
	{
		$this->layout	=	"ajax";
		$this->set("latitude","-6.233394646324949");
		$this->set("longitude","106.87774658203125");
		$this->set("zoom","14");
	}
	
	function DaftarKotaDealer($current_city="all_cities")
	{
		$this->layout	=	"ajax";
		$this->loadModel("ProvinceGroup");	
		$daftar_kota	=	array();
		$daftar_kota	=	$this->ProvinceGroup->DisplayProvinceGroup();
		$this->set("daftar_kota",$daftar_kota);
		
		if($current_city!=="all_cities")
		{
			$display_city	=	$this->ProvinceGroup->findById($current_city);
			$display_title	=	$display_city['ProvinceGroup']['name'];
		}
		else
		{
			$display_title	=	"SEMUA KOTA" ;
		}
		
		$this->set("display_title",$display_title);
	}
	
	function DaftarKota($category_id="all_categories",$current_city="all_cities",$controller=null)
	{
		$this->layout	=	"ajax";
		$this->loadModel("ProvinceGroup");
		$daftar_kota	=	array();
		$daftar_kota	=	$this->ProvinceGroup->DisplayProvinceGroup();
		$this->set("daftar_kota",$daftar_kota);
		$this->set("category_id",$category_id);
		$this->set("current_city",$current_city);
		$display_category	=	"Semua Merk";
		$display_city		=	$current_city;
		$display_title		=	"";
		$arr_quer			=	array("MotorMurah"=>"Harga di bawah 9 Juta","MotorKredit"=>"Motor Kredit","MotorGede"=>"Motor Gede","MotorKlasik"=>"MotorKlasik");
		$arr_title			=	array();
		
		if($category_id!=="all_categories")
		{
			$this->loadModel("Category");
			$tree	=	$this->Category->getpath($category_id);
			unset($tree[0]);
			$display_category	=	"";
			foreach($tree as $tree)
			{
				$display_category	.=	$tree['Category']['name']." ";
			}
			$display_category	=	substr($display_category,0,-1);
			$display_title		=	$display_category;
			array_push($arr_title,$display_category);
		}
		
		if(array_key_exists($controller,$arr_quer)) array_push($arr_title,$arr_quer[$controller]);
		
		if($current_city!=="all_cities")
		{
			$display_city	=	$this->ProvinceGroup->findById($current_city);
			$display_city	=	$display_city['ProvinceGroup']['name'];
			$display_title	= 	implode(" - ",array_merge($arr_title,(array) $display_city));
		}
		else
		{
			$display_title	=	($controller=="Home") ? "PILIH KOTA" : implode(" - ",array_merge($arr_title,(array) "SEMUA KOTA"));
		}
		$this->set("display_category",$display_category);
		$this->set("display_city",$display_city);
		$this->set("display_title",$display_title);
		$this->set(compact("controller"));
	}
	
	function DaftarSubcategory($category_id="all_categories",$current_city="all_cities",$controller=null)
	{
		//GET ALL CATEGORY
		$this->loadModel("Category");
		$this->loadModel("ProvinceGroup");
		$display_city		=	$current_city;
		$parent_id			=	0;
		$children			=	array();
		$arr_quer			=	array("MotorMurah"=>"Harga di bawah 9 Juta","MotorKredit"=>"Motor Kredit","MotorGede"=>"Motor Gede","MotorKlasik"=>"MotorKlasik");
		$arr_title			=	array();
		
		if($category_id != "all_categories" && ((int) $category_id)!==0)
		{
			$CATEGORY	=	$this->Category->findById($category_id);
			$parent_id	=	($CATEGORY['Category']['parent_id'] == $this->Category->GetTop()) ? $category_id : $CATEGORY['Category']['parent_id'];
		}
		
		if($parent_id!=0)
		{
			$select_id	=	array($parent_id);
			$children	=	$this->Category->children($parent_id,false,NULL,NULL,NULL,1,1);
			$CATEGORY	=	$this->Category->findById($parent_id);
			
			if(!empty($children))
			{
				foreach($children as $data)
				{
					$select_id[]	=	$data["Category"]["id"];
				}
			}
		}
		array_push($arr_title,$CATEGORY['Category']['name']);
		if(array_key_exists($controller,$arr_quer)) array_push($arr_title,$arr_quer[$controller]);
		
		$display_title	=	implode(" - ",array_merge($arr_title,(array) "SEMUA TIPE"));
		
		if($current_city!=="all_cities")
		{
			$display_city	=	$this->ProvinceGroup->findById($current_city);
			$display_city	=	$display_city['ProvinceGroup']['name'];
		}
		
		$this->set("children",$children);
		$this->set("display_title",$display_title);
		$this->set("current_city",$current_city);
		$this->set("display_city",$display_city);
		$this->set("controller",$controller);
	}
	
	function HoverImg($ID,$PM=0)
	{
		$this->layout	=	"ajax";
		
		//CHECK ID
		$this->loadModel('Product');
		$this->Product->unbindModel( array('belongsTo' => array('Productstatus','Category','Parent','User')) );
		$this->Product->bindModel(
                array(
					'hasOne' => array(
						'ProductImage' => array(
							'className'		=> 'ProductImage',
							'foreignKey'	=> 'product_id',
							'conditions'	=> "ProductImage.is_primary = '1'"
						)
					)
				), false
        );
		
		$data	=	$this->Product->findById($ID);
		
		$this->set(compact("data","PM"));
	}
	
	function DaftarHarga($category_id="all_categories")
	{
		$this->layout		=	"ajax";
		
		//GET DATA
		$this->loadModel("Category");
		$top				=	$this->Category->FindTop();
		$detail				=	$this->Category->findById($category_id);
		$display_title		=	($category_id=="all_categories") ? "Semua Merk Motor" : ($detail["Category"]["parent_id"]!=$top ? "<a href='".$this->settings['site_url']."DaftarHarga/".$detail["Category"]["parent_id"]."/daftar_harga_motor-".$this->General->seoUrl($detail["Parent"]["name"]).".html'>".$detail["Parent"]["name"]."</a> <a href='".$this->settings['site_url']."DaftarHarga/".$detail["Category"]["id"]."/daftar_harga_motor-".$this->General->seoUrl($detail["Parent"]["name"]." ".$detail["Category"]["name"]).".html'>".$detail["Category"]["name"]."</a>" : "<a href='".$this->settings['site_url']."DaftarHarga/".$detail["Category"]["id"]."/daftar_harga_motor-".$this->General->seoUrl($detail["Category"]["name"]).".html'>".$detail["Category"]["name"]."</a>");
		
		$parent_id			=	($category_id=="all_categories") ? $top : $category_id;
		$children			=	($this->Category->childcount($parent_id,true) > 0) ? $this->Category->children($parent_id,true,NULL,NULL,NULL,1,1) : $this->Category->children($detail["Category"]["parent_id"],true,NULL,NULL,NULL,1,1);
		
		$this->set(compact("display_title","children","top"));
	}
	
	function WhatsNew()
	{
		$this->layout	=	"ajax";
		
		App::import('Xml');
		$location		=	$this->settings['path_content']."rss/news.xml";
		$parsed_xml 	=	& new XML($location);
		$this->rss_item = $parsed_xml->toArray();
		$this->set('data', $this->rss_item['Rss']['Channel']['Item']);
	}
	
	function Testimonial()
	{
		$this->layout	=	"ajax";
		$this->loadModel("Contact");
		$data	=	$this->Contact->find("all",array(
						"conditions"	=>	array(
							"Contact.contact_category_id"	=>	"2",
							"Contact.publish"				=>	"1"
						),
						"order"			=>	array("Contact.id DESC"),
						"limit"			=>	5
					));
		
		$this->set(compact("data"));
	}
	
	function MemberOfTheMonth()
	{
		$this->layout	=	"ajax";
		
		//GET END ACTIVIT MONTH
		$this->loadModel("UserLogs");
		$last			=	$this->UserLogs->find("first",array(
								"order"	=>	array(
									"UserLogs.created DESC"
								),
								"fields"	=>	array("UserLogs.created")
							));
		
		$date			=	strtotime($last["UserLogs"]["created"]);
		$first_date		=	date('Y-m-01 00:00:00',$date); // hard-coded '01' for first day
		$end_date		=	date('Y-m-t 23:59:59',$date);
		
		$data			=	$this->UserLogs->find("first",array(
								"fields"	=>	array(
									"UserLogs.user_id",
									"COUNT(UserLogs.user_id) as TOTAL"
								),
								"order"	=>	array(
									"TOTAL DESC"
								),
								"conditions"	=>	array(
									"UserLogs.created BETWEEN ? AND ?"	=>	array($first_date,$end_date)
								),
								"group"		=>	array("UserLogs.user_id")
							));
		
		$this->loadModel("User");
		$findProfile	=	$this->User->findById($data["UserLogs"]["user_id"]);
		$this->set(compact("findProfile"));
	}
	
	function SimulasiKredit()
	{
		$this->layout	=	"ajax";
	}
	
	function BanyakDicari()
	{
		$this->layout	=	"ajax";
	}
	function HomeCarousel()
	{
		$this->layout	=	"ajax";
	}
	
	function Moge()
	{
		$this->layout	=	"ajax";
		$this->loadModel('Product');
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
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						)
					)
				), false
        );
		
		$count			=	$this->Product->find("count",array(
								"conditions"	=>	array(
									"Product.productstatus_id"		=>	1,
									"Product.productstatus_user"	=>	1,
									'Product.sold'					=> '0',
									'Product.show_panel_moge'		=> '0',
									"OR"	=>	array(
										"Category.is_moge"			=>	"1",
										"Parent.is_moge"			=>	"1"
									)
								)
							));
		
		$refresh				=	0;
		if($count<6) $refresh	=	1;
		
		$data			=	$this->Product->find("all",array(
								"conditions"	=>	array(
									"Product.productstatus_id"		=>	1,
									"Product.productstatus_user"	=>	1,
									'Product.sold'					=> '0',
									'Product.show_panel_moge'		=> '0',
									"OR"	=>	array(
										"Category.is_moge"			=>	"1",
										"Parent.is_moge"			=>	"1"
									)
									
								),
								"order"		=>	array("rand()"),
								"limit"		=>	6,
								"fields"	=>	array(
									"Product.id",
									"Product.price",
									"Product.thn_pembuatan",
									"Category.name",
									"Parent.name",
									"ProvinceGroup.name",
									"ProductImage.id"
								)
							));
		foreach($data as $cid)
		{
			$id[]	=	$cid['Product']['id'];
		}
		
		
		//UPDATE MOGE
		$update_all	=	$this->Product->updateAll(
								array(
									"show_panel_moge"	=>	"'1'"
								),
								array(
									'Product.id'		=>	$id
								)
							);
		
		if($refresh == 1)
		{
			$update_all	=	$this->Product->updateAll(
								array(
									"show_panel_moge"	=>	"'0'"
								)
							);
		}
		$this->set(compact("data"));
	}
	
	function Klasik()
	{
		$this->layout	=	"ajax";
		$this->loadModel('Product');
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
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						)
					)
				), false
        );
		
		$count			=	$this->Product->find("count",array(
								"conditions"	=>	array(
									"Product.productstatus_id"		=>	1,
									"Product.productstatus_user"	=>	1,
									'Product.sold'					=> '0',
									'Product.show_panel_moge'		=> '0',
									'Product.show_panel_klasik'		=> '0',
									"OR"	=>	array(
										"Category.is_moge"			=>	"1",
										"Parent.is_moge"			=>	"1"
									)
									
								)
							));
		
		$refresh				=	0;
		if($count<6) $refresh	=	1;
		
		$data			=	$this->Product->find("all",array(
								"conditions"	=>	array(
									"Product.productstatus_id"		=>	1,
									"Product.productstatus_user"	=>	1,
									'Product.sold'					=> '0',
									'Product.thn_pembuatan <= '		=> (date("Y")-20),
									'Product.show_panel_klasik'		=> '0'
								),
								"order"		=>	array("rand()"),
								"limit"		=>	6,
								"fields"	=>	array(
									"Product.id",
									"Product.price",
									"Product.thn_pembuatan",
									"Category.name",
									"Parent.name",
									"ProvinceGroup.name",
									"ProductImage.id"
								)
							));
		
		foreach($data as $cid)
		{
			$id[]	=	$cid['Product']['id'];
		}
		
		
		//UPDATE MOGE
		$update_all	=	$this->Product->updateAll(
								array(
									"show_panel_klasik"	=>	"'1'"
								),
								array(
									'Product.id'		=>	$id
								)
							);
		
		if($refresh == 1)
		{
			$update_all	=	$this->Product->updateAll(
								array(
									"show_panel_klasik"	=>	"'0'"
								)
							);
		}						 
		$this->set(compact("data"));
	}
	
	function HomeLatestNew()
	{
		$this->layout	=	"ajax";
		
		//IKLAN TERBARU
		$this->loadModel('Product');
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
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						)
					)
				), false
        );
	  $new	=	$this->Product->find("all",array(
					  "conditions"	=>	array(
						  "Product.productstatus_id"		=>	1,
						  "Product.productstatus_user"	=>	1
					  ),
					  "fields"	=>	array(
										  "Product.id",
										  "Product.price",
										  "Product.thn_pembuatan",
										  "Product.condition_id",
										  "Product.kilometer",
										  "Product.sold",
										  "Product.data_type",
										  "Product.ym",
										  "Product.contact_name",
										  "Product.view",
										  "Product.comment",
										  "Category.name",
										  "Parent.name",
										  "Province.name",
										  "ProductImage.id",
										  "ProvinceGroup.name"
									  ),
					  "order"		=>	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC"),
					  "limit"		=>	8
				  ));
	  
		$this->set("new",$new);
		$this->set("current_category_id","all_categories");
		$this->set("current_city","all_cities");
	}
	
	function HomePremiumContent()
	{
		$this->layout	=	"ajax";
		
		//IKLAN TERBARU
		$this->loadModel('Product');
		$this->loadModel('AdsRequest');
		$this->Product->bindModel(
                array(
					'hasOne' => array(
						'ProductImage' => array(
							'className'		=> 'ProductImage',
							'foreignKey'	=> 'product_id',
							'conditions'	=> "ProductImage.is_primary = '1'"
						),
						'AdsRequest' => array(
							'className'		=> 'AdsRequest',
							'foreignKey'	=> 'product_id',
							'conditions'	=> array(
								"AdsRequest.ads_type_id"	=>	"1",
								"AdsRequest.status"			=>	"1",
								"AdsRequest.start_date <= "	=>	date("Y-m-d H:i:s"),
								"AdsRequest.end_date >= "	=>	date("Y-m-d H:i:s")
							)
						)
					),
					'belongsTo'	=>	array(
						'Province' => array(
							'className' 	=>	'Province',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Product.city_id = Province.id'
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						)
					)
				), false
        );
	  
	  
	  
	  $new	=	$this->Product->find("all",array(
					  "conditions"	=>	array(
						  "Product.productstatus_id"	=>	1,
						  "Product.productstatus_user"	=>	1,
						  "AdsRequest.id IS NOT NULL"
					  ),
					  "fields"	=>	array(
					  					  "AdsRequest.id",
										  "Product.id",
										  "Product.price",
										  "Product.thn_pembuatan",
										  "Product.condition_id",
										  "Product.kilometer",
										  "Product.sold",
										  "Product.data_type",
										  "Product.ym",
										  "Product.contact_name",
										  "Product.view",
										  "Product.comment",
										  "Category.name",
										  "Parent.name",
										  "Province.name",
										  "ProductImage.id",
										  "ProvinceGroup.name"
									  ),
					  "order"		=>	array("AdsRequest.view ASC"),
					  "limit"		=>	8
				  ));
	  	
		
		if(!empty($new))
		{
			foreach($new as $AdsRequest)
			{
				$update[]	=	$AdsRequest["AdsRequest"]["id"];
			}
			
			$UpdateAdsRequest	=	$this->AdsRequest->updateAll(
									array(
										"view"=>"AdsRequest.view + 1"
									),
									array(
										"AdsRequest.id"	=>	$update
									)
								);
		}
		$this->set("new",$new);
		$this->set("current_category_id","all_categories");
		$this->set("current_city","all_cities");
	}
	
	function HomeLatestProduct()
	{
		$this->layout	=	"ajax";
		
		//IKLAN TERBARU
		$this->loadModel('Product');
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
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						)
					)
				), false
        );
	  	$data	=	$this->Product->find("all",array(
					  "conditions"	=>	array(
						  "Product.productstatus_id"		=>	1,
						  "Product.productstatus_user"	=>	1
					  ),
					  "fields"	=>	array(
										  "Product.id",
										  "Product.price",
										  "Product.thn_pembuatan",
										  "Product.condition_id",
										  "Product.kilometer",
										  "Product.sold",
										  "Product.data_type",
										  "Product.ym",
										  "Product.contact_name",
										  "Product.view",
										  "Product.comment",
										  "Category.name",
										  "Parent.name",
										  "Province.name",
										  "ProductImage.id",
										  "ProvinceGroup.name"
									  ),
					  "order"		=>	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC"),
					  "limit"		=>	30
				  ));
	  
		$this->set("data",$data);
		$this->set("current_category_id","all_categories");
		$this->set("current_city","all_cities");
	}
	
	
	function HomePremiumContentCategory($category_id="all_categories",$current_city="all_cities")
	{
		$this->layout	=	"ajax";
		
		//DEFINE CONDITIONS
		$conditions										=	array();
		$conditions["Product.productstatus_id"]		=	"1";
		$conditions["Product.productstatus_user"]	=	"1";
		array_push($conditions,"AdsRequest.id IS NOT NULL");
	
		//GET CATEGORY SELECTED
		if($category_id != "all_categories" && ((int) $category_id)!==0)
		{
			$CATEGORY			=	ClassRegistry::Init("Category");
			$detail_category	=	$CATEGORY->findById($category_id);	
			$selected_id		=	array($category_id);
			$children			=	$CATEGORY->children($category_id,false,"Category.id");
			if(!empty($children))
			{
				foreach($children as $children)
				{
					$selected_id[]	=	$children["Category"]["id"];
				}
			}
			$conditions["Category.id"]	=	$selected_id;
		}
		
		//GET PROVINCE ID SELECTED
		if($current_city!=="all_cities" && ((int) $current_city)!==0)
		{
			$PROVINCE		=	ClassRegistry::Init("Province");
			$ProvinceGroup	=	ClassRegistry::Init("ProvinceGroup");
			$detail_pgroup	=	$ProvinceGroup->findById($current_city);
			$prov_lists		=	$PROVINCE->find("list",array(
									"conditions"	=>	array(
										"Province.group_id"	=>	$current_city
									),
									"fields"	=>	array("Province.id")
								));
			$conditions["Product.city_id"]	=	$prov_lists;
		}
		
		//IKLAN TERBARU
		$this->loadModel('Product');
		$this->loadModel('AdsRequest');
		$this->Product->bindModel(
                array(
					'hasOne' => array(
						'ProductImage' => array(
							'className'		=> 'ProductImage',
							'foreignKey'	=> 'product_id',
							'conditions'	=> "ProductImage.is_primary = '1'"
						),
						'AdsRequest' => array(
							'className'		=> 'AdsRequest',
							'foreignKey'	=> 'product_id',
							'conditions'	=> array(
								"AdsRequest.ads_type_id"	=>	"1",
								"AdsRequest.status"			=>	"1",
								"AdsRequest.start_date <= "	=>	date("Y-m-d H:i:s"),
								"AdsRequest.end_date >= "	=>	date("Y-m-d H:i:s")
							)
						)
					),
					'belongsTo'	=>	array(
						'Province' => array(
							'className' 	=>	'Province',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Product.city_id = Province.id'
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						)
					)
				), false
        );
		
		$new	=	$this->Product->find("all",array(
						  "conditions"	=>	$conditions,
						  "fields"	=>	array(
											  "AdsRequest.id",
											  "Product.id",
											  "Product.price",
											  "Product.thn_pembuatan",
											  "Product.condition_id",
											  "Product.kilometer",
											  "Product.sold",
											  "Product.data_type",
											  "Product.ym",
											  "Product.contact_name",
											  "Product.view",
											  "Product.comment",
											  "Category.name",
											  "Parent.name",
											  "Province.name",
											  "ProductImage.id",
											  "ProvinceGroup.name"
										  ),
						  "order"		=>	array("AdsRequest.view ASC"),
						  "limit"		=>	8
						));
		if(!empty($new))
		{
			foreach($new as $AdsRequest)
			{
				$update[]	=	$AdsRequest["AdsRequest"]["id"];
			}
			
			$UpdateAdsRequest	=	$this->AdsRequest->updateAll(
									array(
										"view"=>"AdsRequest.view + 1"
									),
									array(
										"AdsRequest.id"	=>	$update
									)
								);
		}
		$this->set("new",$new);
	}
	
	function CheckCredential()
	{
		$this->layout		=	"json";
		$status				=	(empty($this->user_id) or is_null($this->user_id)) ? false : true;
		$msg				=	array("status"=>$status,"data"=>$this->profile);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function SiteMap()
	{
		$this->layout 		=	"xml";
		
		//DEFINE IKLAN TERBARU
		$this->loadModel("Category");
		$top			=	$this->Category->GetTop();
		if (($cat_sitemap = Cache::read('cat_sitemap')) === false)
		{
			$cat_sitemap	=	$this->Category->children($top,false,NULL,NULL,NULL,1,1);
			Cache::write('cat_sitemap', $cat_sitemap);
		}
		
		
		//GET PROVINCE GROUP
		if (($ProvinceGroup = Cache::read('group_list')) === false)
		{
			$this->loadModel("ProvinceGroup");
			$ProvinceGroup	=	$this->ProvinceGroup->DisplayProvinceGroup();
			Cache::write('group_list', $ProvinceGroup);
		}
		
		
		//DAFTAR MOTOR
		if (($daftar_motor = Cache::read('daftar_motor')) === false)
		{
			foreach($ProvinceGroup as $key => $val)
			{
				foreach($cat_sitemap as $bangke)
				{
					$cat_name		=	($bangke["Category"]['parent_id']==$top) ? $bangke["Category"]["name"] : $bangke["Parent"]["name"]." ".$bangke["Category"]["name"];
					
					$daftar_motor[]	=	$this->settings['site_url']."DaftarMotor/".$key."/".$bangke["Category"]["id"]."/motor-dijual-".$this->General->seoUrl($cat_name)."-".$this->General->seoUrl($val).".html";
				}
			}
			Cache::write('daftar_motor', $daftar_motor);
		}
		
		//MOTOR MURAH
		if (($motor_murah = Cache::read('motor_murah')) === false)
		{
			foreach($ProvinceGroup as $key => $val)
			{
				foreach($cat_sitemap as $bangke)
				{
					$cat_name		=	($bangke["Category"]['parent_id']==$top) ? $bangke["Category"]["name"] : $bangke["Parent"]["name"]." ".$bangke["Category"]["name"];
					
					$motor_murah[]	=	$this->settings['site_url']."MotorMurah/".$key."/".$bangke["Category"]["id"]."/motor-harga-dibawah-9-juta-".$this->General->seoUrl($cat_name)."-".$this->General->seoUrl($val).".html";
				}
			}
			Cache::write('motor_murah', $motor_murah);
		}
		
		//DAFTAR MOTOR
		if (($motor_kredit = Cache::read('motor_kredit')) === false)
		{
			foreach($ProvinceGroup as $key => $val)
			{
				foreach($cat_sitemap as $bangke)
				{
					$cat_name		=	($bangke["Category"]['parent_id']==$top) ? $bangke["Category"]["name"] : $bangke["Parent"]["name"]." ".$bangke["Category"]["name"];
					
					$motor_kredit[]	=	$this->settings['site_url']."MotorKredit/".$key."/".$bangke["Category"]["id"]."/motor-kredit-".$this->General->seoUrl($cat_name)."-".$this->General->seoUrl($val).".html";
				}
			}
			Cache::write('motor_kredit', $motor_kredit);
		}
		$this->set(compact("motor_kredit","motor_murah","daftar_motor"));
	}
}
?>