<?php
class JmApiController extends AppController
{
	var $settingvar;
	var $uses			=	null;
	var $components 	= array("Action","General");
	
	function beforeFilter()
	{
		define("ERR_00","Ok");
		define("ERR_01","Wrong username or password");
		define("ERR_02","Data not found");
		define("ERR_03","Validate Failed");
		define("ERR_04","Pengiriman kode verifikasi hanya dapat dilakukan maksimal sebanyak 3 kali. Anda tidak dapat mengirimkan kembali email verfikasi.");
		
		$this->autoRender = false;
		$username	=	$_REQUEST['username'];
		$password	=	$_REQUEST['password'];
		
		if(($username!=="elis" && $password!=="puspasarikeisha"))
		{
			echo json_encode(array("status"=>false,"message"=>ERR_01,"data"=>NULL,"code"=>"01"));	
			exit;
		}
		else
		{
			//SET GENERAL SETTINGS
			if (($settings = Cache::read('settings')) === false)
			{
				$this->loadModel('Setting');
				$settings			=	$this->Setting->find('first');
				Cache::write('settings', $settings);
			}
			$this->settingvar	=	$settings['Setting'];
		}
	}
	
	function Panin()
	{
		$out	=	array("status"=>false);
		echo json_encode($out);
		$this->autoRender	=	false;
	}

	function GetCategory()
	{
		//LOAD MODEL
		$this->loadModel("Category");
		$data	=	$this->Category->children($this->Category->GetTop(),false);
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		$this->autoRender	=	false;
	}
	
	function GetProvinceGroup()
	{
		//LOAD MODEL
		$this->loadModel("ProvinceGroup");
		$data	=	$this->ProvinceGroup->find("all",array(
						"conditions"	=>	array(
							"ProvinceGroup.pos > " => 0
						),
						"order"	=>	array(
							"ProvinceGroup.pos ASC"
						)
					));
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		
		$this->autoRender	=	false;
	}
	
	function DaftarMotor($category_id=0,$province_id=0)
	{
		//LOAD MODEL
		$this->loadModel('Product');
		
		
		$category_id	=	($category_id == 0) ? "all_categories"	: $category_id; 
		$province_id	=	($province_id == 0) ? "all_cities"		: $province_id;
		
		//GET DATA
		$filtering			=	$this->Product->GetData($category_id,$province_id,"DaftarMotor");
		$this->paginate		=	array(
			'Product'	=>	array(
				'limit'			=>	10,
				'order'			=>	$filtering['order'],
				'group'			=>	array('Product.id'),
				'fields'		=>	$filtering['fields'],
				'conditions'	=>	$filtering['conditions'],
			)
		);
		
		$data					=	$this->paginate('Product');
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code,"pageCount"=>$this->params['paging']['Product']['pageCount'],"page"=>$this->params['paging']['Product']['page'],"nextPage"=>$this->params['paging']['Product']['nextPage']);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function MotorKredit($category_id=0,$province_id=0)
	{
		//LOAD MODEL
		$this->loadModel('Product');
		
		
		$category_id	=	($category_id == 0) ? "all_categories"	: $category_id; 
		$province_id	=	($province_id == 0) ? "all_cities"		: $province_id;
		
		//GET DATA
		$filtering			=	$this->Product->GetData($category_id,$province_id,"MotorKredit");
		$this->paginate		=	array(
			'Product'	=>	array(
				'limit'			=>	10,
				'order'			=>	$filtering['order'],
				'group'			=>	array('Product.id'),
				'fields'		=>	$filtering['fields'],
				'conditions'	=>	$filtering['conditions'],
			)
		);
		
		$data					=	$this->paginate('Product');
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code,"pageCount"=>$this->params['paging']['Product']['pageCount'],"page"=>$this->params['paging']['Product']['page'],"nextPage"=>$this->params['paging']['Product']['nextPage']);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function DaftarHarga($category_id=0)
	{
		//GET DATA
		$this->loadModel("DaftarHarga");
		$this->loadModel("Category");
		$category_id	=	($category_id == 0) ? "all_categories"	: $category_id; 
		
			
		//DEFINE CONDITIONS
		$conditions["DaftarHarga.productstatus_id"]		=	1;
		$conditions["DaftarHarga.productstatus_user"]	=	1;
		
		if($category_id!="all_categories")
		{
			$selected_id		=	array($category_id);
			$children			=	$this->Category->children($category_id,false,"Category.id");
			if(!empty($children))
			{
				foreach($children as $children)
				{
					$selected_id[]	=	$children["Category"]["id"];
				}
			}
			$conditions["Category.id"]	=	$selected_id;
		}
		
		$data				=	$this->DaftarHarga->find('all',array(
									'order'			=>	array("Parent.name","Category.name"),
									'group'			=>	array('Category.id','DaftarHarga.thn_pembuatan'),
									'fields'		=>	array("Parent.name","Category.name","Parent.id","Category.id","DaftarHarga.thn_pembuatan","DaftarHarga.MIN","DaftarHarga.MAX"),
									'conditions'	=>	$conditions
								));
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function TestNews()
	{
		$this->loadModel("NewsSubcategory");
		$this->loadModel("NewsCategory");
		$this->loadModel("NewsType");
		
		$data	=	$this->NewsCategory->find("all");
		
		/*$data	=	array();
		for($i=0;$i<2;$i++)
		{
			$data[$i]	=	array(
								"Group"	=>	array(
									"name"		=>	"Nasional-".$i,
									"child"		=>	array(
										array(
											"title"			=>	"Detik.com-".$i,
											"description"	=>	"Situs Berita Online dari detik.com-".$i
										),
										array(
											"title"			=>	"Detik.com-".$i,
											"description"	=>	"Situs Berita Online dari detik.com-".$i
										),
										array(
											"title"			=>	"Detik.com-".$i,
											"description"	=>	"Situs Berita Online dari detik.com-".$i
										),
										array(
											"title"			=>	"Detik.com-".$i,
											"description"	=>	"Situs Berita Online dari detik.com-".$i
										),

									)
								)
							);
		}*/
		$out	=	array("status"=>true,"message"=>ERR_00,"data"=>$data,"code"=>"00","update"=>"1350790628");
		
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	function LastUpdateNews()
	{
		$this->loadModel("NewsLastUpdated");
		$data	=	$this->NewsLastUpdated->find("first");
		
		$out	=	array("update"=>$data["NewsLastUpdated"]["modified"]);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function NewsSendNotif()
	{
		$this->loadModel("NewsUser");
		$status								=	false;
		$message							=	ERR_03;
		$code								=	"03";
		$data								=	array();
		$data["NewsUser"]["type"]			=	$type		=	(isset($_REQUEST["type"]) && in_array($_REQUEST["type"],array("1","2"))) ? $_REQUEST["type"] : "1";	
		$message							=	($type == "1") ? "New Update!" : "New Version!";
		
		$fUser								=	$this->NewsUser->find("all");
		
		if(!empty($data) && !empty($fUser))
		{
			$reg_ids	=	array();
			foreach($fUser as $fUser)
			{
				$reg_ids[]	=	$fUser["NewsUser"]["id"];
			}
			
			$url = 'https://android.googleapis.com/gcm/send';
			$fields =	array(
				'registration_ids' => $reg_ids,
				'data' => array(
					"message"	=>	$message,
					"type"		=>	$type
					),
			);
	 		
			$headers = array(
				'Authorization: key=' . "AIzaSyCSx6msAVNoln9GWvSrTIqqx2Rx3hw2q-s",
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
			echo $result;
		}
		/*$out	=	array("status"=>true,"message"=>$message,"data"=>$save,"code"=>$code);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}*/
		$this->autoRender	=	false;
	}
	
	function PhoneListApi()
	{
		$this->loadModel("PhonelistSubcategory");
		$this->loadModel("PhonelistCategory");
		$this->loadModel("PhonelistType");
		$this->loadModel("PhonelistLocation");
		$data		=	$this->PhonelistCategory->find("all",array('recursive' => 2));
		$location	=	$this->PhonelistLocation->find("all");
		
		$out		=	array("status"=>true,"message"=>ERR_00,"data"=>$data,"code"=>"00","update"=>"1350790628","location"=>$location);
		//header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function PhoneListRegister()
	{
		$this->loadModel("PhonelistUser");
		$status							=	false;
		$message						=	ERR_03;
		$code							=	"03";
		$data							=	array();
		$data["PhonelistUser"]["id"]	=	$_REQUEST["id"];	
		
		if(!empty($data))
		{
			$save	=	$this->PhonelistUser->save($data);
			if($save)
			{
				$status		=	true;
				$message	=	ERR_00;
				$code		=	"00";
			}
		}
		$out	=	array("status"=>true,"message"=>$message,"data"=>$save,"code"=>$code);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function PhoneListSendNotif()
	{
		$this->loadModel("PhonelistUser");
		$status								=	false;
		$message							=	ERR_03;
		$code								=	"03";
		$data								=	array();
		$data["NewsUser"]["type"]			=	$type		=	(isset($_REQUEST["type"]) && in_array($_REQUEST["type"],array("1","2"))) ? $_REQUEST["type"] : "1";	
		$message							=	($type == "1") ? "New Update!" : "New Version!";
		$fUser								=	$this->PhonelistUser->find("all");
		
		if(!empty($data) && !empty($fUser))
		{
			$reg_ids	=	array();
			foreach($fUser as $fUser)
			{
				$reg_ids[]	=	$fUser["PhonelistUser"]["id"];
			}
			
			$url = 'https://android.googleapis.com/gcm/send';
			$fields =	array(
				'registration_ids' => $reg_ids,
				'data' => array(
					"message"	=>	$message,
					"type"		=>	$type
				),
			);
	 
			$headers = array(
				'Authorization: key=' . "AIzaSyDgMbSt1z7URw2sSv1mixGQcPU4izSSrko",
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
			echo $result;
		}
		/*$out	=	array("status"=>true,"message"=>$message,"data"=>$save,"code"=>$code);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}*/
		$this->autoRender	=	false;
	}
	
	function LastUpdatePhonelist()
	{
		$this->loadModel("PhonelistLastUpdated");
		$data	=	$this->PhonelistLastUpdated->find("first");
		
		$out	=	array("update"=>$data["PhonelistLastUpdated"]["modified"]);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function AncLastUpdate()
	{
		$this->loadModel("AncLastUpdate");
		$data	=	$this->AncLastUpdate->find("first");
		
		$out	=	array("update"=>$data["AncLastUpdate"]["modified"],"subcategory_id"=>$data["AncLastUpdate"]["subcategory_id"]);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function AnctApi()
	{
		$this->loadModel("AncSubcategory");
		$data	=	$this->AncSubcategory->find("all");
		$out	=	array("status"=>true,"message"=>ERR_00,"data"=>$data,"code"=>"00","update"=>"1350790628");
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function AncRegister()
	{
		$this->loadModel("AncUser");
		$status							=	false;
		$message						=	ERR_03;
		$code							=	"03";
		$data							=	array();
		$data["AncUser"]["id"]			=	$_REQUEST["id"];	
		
		if(!empty($data))
		{
			$save	=	$this->AncUser->save($data);
			if($save)
			{
				$status		=	true;
				$message	=	ERR_00;
				$code		=	"00";
			}
		}
		$out	=	array("status"=>true,"message"=>$message,"data"=>$save,"code"=>$code);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function AncSendNotif()
	{
		$this->loadModel("AncUser");
		$status								=	false;
		$message							=	ERR_03;
		$code								=	"03";
		$data								=	array();
		$type								=	(isset($_REQUEST["type"]) && in_array($_REQUEST["type"],array("1","2"))) ? $_REQUEST["type"] : "1";	
		$message							=	($type == "1") ? "New Update!" : "New Version!";
		$fUser								=	$this->AncUser->find("all");
		
		if(!empty($fUser))
		{
			$reg_ids	=	array();
			foreach($fUser as $fUser)
			{
				$reg_ids[]	=	$fUser["AncUser"]["id"];
			}
			
			$url = 'https://android.googleapis.com/gcm/send';
			$fields =	array(
				'registration_ids' => $reg_ids,
				'data' => array(
					"message"				=>	$message,
					"subcategory_id"		=>	"122",
					"type"					=>	$type
				),
			);
	 
			$headers = array(
				'Authorization: key=' . "AIzaSyA0xXPyLhBYr9iLwYhVh-WbPcXqZqwe11I",
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
			echo $result;
		}
		/*$out	=	array("status"=>true,"message"=>$message,"data"=>$save,"code"=>$code);
		header("Content-type:application/json");
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}*/
		$this->autoRender	=	false;
	}
	
	function Dealer($current_city=0)
	{
		$current_city	=	($current_city == 0) ? "all_cities"		: $current_city;
		
		$this->loadModel("ProvinceGroup");	
		$daftar_kota	=	array();
		$daftar_kota	=	$this->ProvinceGroup->DisplayProvinceGroup();
		$conditions["User.userstatus_id"]		=	1;
		$conditions["Company.companystatus_id"]	=	1;
		
		if($current_city!=="all_cities")
		{
			$this->loadModel("Province");
			$city_id		=	$this->Province->find("list",array(
									"conditions"	=>	array(
										"Province.group_id"	=>	$current_city
									),
									"fields"		=>	array("Province.id")
								));
			
			$conditions["Company.city_id"]	=	$city_id;
		}
		
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
		$this->Company->unbindModel(array("belongsTo" => array('Province')),false);
		
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code,"pageCount"=>$this->params['paging']['Company']['pageCount'],"page"=>$this->params['paging']['Company']['page'],"nextPage"=>$this->params['paging']['Company']['nextPage']);
		
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function Login()
	{
		$data									=	array();
		$data['User']['email_login']			=	$_REQUEST['email_login'];
		$data['User']['password_login']			=	$_REQUEST['password_login'];
		
		
		$this->loadModel('User');
		$this->User->set($data);
		$this->User->validateLogin();
		$error									=	$this->User->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			$data		=	$this->User->CheckLogin();
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$code		=	"03";
			$data		=	false;
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function ListProvince()
	{
		//LOAD PROVINCE
		if (($data = Cache::read('province')) === false)
		{
			$this->loadModel('Province');
			$fprovince = $this->Province->find('all', array(
							'conditions' => array(
								'Province.status' => 1
							),
							'group'	=>	array('Province.province_id'),
							'order'	=>	array('Province.province ASC')
						));
			
			foreach ($fprovince as $k => $v) {
				$data[] = array("id"=>$v['Province']['province_id'],"name"=>$v['Province']['province']);
			}
			Cache::write('province', $data);
		}
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function ListCity()
	{
		$province_id	=	$_REQUEST['province_id'];
		//LOAD CITY
		if (($data = Cache::read('city_'.$province_id)) === false)
		{
			$this->loadModel('Province');
			$fprovince = $this->Province->find('all', array(
							'conditions' => array(
								'Province.status' 		=> 1,
								"Province.province_id"	=> $province_id
							),
							'order'	=>	array('Province.name ASC')
						));
			
			foreach ($fprovince as $k => $v) {
				$data[] = array("id"=>$v['Province']['id'],"name"=>$v['Province']['name']);
			}
			Cache::write('city_'.$province_id, $data);
		}
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function Register()
	{
		App::import('Sanitize');
		
		//LOAD MODEL
		$this->loadModel('User');
		
		$data['User']['fullname']			=	$_REQUEST['fullname'];
		$data['User']['address']			=	$_REQUEST['address'];
		$data['User']['province']			=	$_REQUEST['province'];
		$data['User']['city']				=	$_REQUEST['city'];
		$data['User']['phone']				=	$_REQUEST['phone'];
		$data['User']['email']				=	$_REQUEST['email'];
		$data['User']['password']			=	$_REQUEST['password_'];
		
		$data['User']['retype_password']	=	$_REQUEST['retype_password'];
		$data['User']['usertype_id']		=	$_REQUEST['usertype_id'];
		$data['User']['cname']				=	$_REQUEST['cname'];
		$data['User']['agree']				=	$_REQUEST['agree'];
		$data['User']['image']				=	$_REQUEST['image'];
		$this->data							=	$data;
		
		$this->User->set($this->data);
		$this->User->InitiateValidate();
		$error	=	$this->User->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			$data		=	NULL;
			
			//SAVE USER
			$this->data['User']['password'] = md5($this->data['User']['password']);
			$user		=	$this->User->save($this->data,false);
			$user_id	=	$this->User->getLastInsertId();
			
			//SAVE PROFILE
			$this->loadModel('Profile');
			$this->data['User']['fullname']		=	Sanitize::html($this->data['User']['fullname']);
			$this->data['User']['address']		=	Sanitize::html($this->data['User']['address']);
			$profile							=	$this->Profile->saveAll(
														array(
															'user_id'		=>	$user_id,
															'fullname'		=>	$this->data['User']['fullname'],
															'address'		=>	$this->data['User']['address'],
															'province_id'	=>	$this->data['User']['province'],
															'city_id'		=>	$this->data['User']['city'],
															'phone'			=>	$this->data['User']['phone']
														),false
													);
			
			$data		=	$this->User->findById($user_id);
			
			//SAVE Company
			if($this->data['User']['usertype_id']=="2")
			{
				$this->loadModel('Company');
				$this->data['User']['cname']		=	Sanitize::html($this->data['User']['cname']);
				$company	=	$this->Company->saveAll(
									array(
										'user_id'			=>	$user_id,
										'name'				=>	$this->data['User']['cname'],
										'address'			=>	$this->data['User']['address'],
										'province_id'		=>	$this->data['User']['province'],
										'city_id'			=>	$this->data['User']['city'],
										'phone'				=>	$this->data['User']['phone'],
										'companystatus_id'	=>	1
									)
								);
			}
			
			//SAVE PHOTO
			if(!empty($this->data['User']['image']))
			{	
				$source						=	base64_decode($this->data['User']['image']);
				$ext						=	"jpg";
				$cnt_user_dir				=	$this->settingvar['path_content']."User/";
				$cnt_userid_dir				=	$cnt_user_dir.$user_id."/";
				$destination				=	$cnt_userid_dir.$user_id.".".$ext;
				if(!is_dir($cnt_user_dir)) mkdir($cnt_user_dir,0777);
				if(!is_dir($cnt_userid_dir)) mkdir($cnt_userid_dir,0777);
				$handle 					=	fopen($destination, 'w');
				if (fwrite($handle, $source)) 
				{
					chmod($destination,0777);
				}
				fclose($handle);
			}
			
			//SEND VERIFICATION CODES
			$vcode		= $this->User->getValidation(trim($this->data['User']['email']));
			App::import('vendor', 'encryption_class');
			$encrypt	= new encryption;
			$param		= $encrypt->my_encrypt($user_id . "|" . $vcode);
			$link 		= $this->settingvar['site_url'] . "Users/Verification/param:" . $param;
			$search 	= array('[logo_url]', '[username]', '[site_name]', '[code]', '[link]', '[site_url]');
			$replace 	= array($this->settingvar['logo_url'], $this->data['User']['fullname'], $this->settingvar['site_name'], $vcode,$link,$this->settingvar['site_url']);
			$this->Action->EmailSave('regver_apps', $this->data['User']['email'], $search, $replace,"","","User",$user_id);
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$data		=	NULL;
			$code		=	"03";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function UserDetail()
	{
		$this->layout	=	"json";
		//DEFINE DATA INCOMING
		$this->loadModel('User');
		$user_id	=	$_REQUEST['user_id'];
		$data		=	$this->User->find('first',array(
							"conditions"	=>	array(
								"User.id"	=>	$user_id
							),
							"fields"		=>	array(
								"User.id",
								"User.usertype_id",
								"User.email",
								"User.created",
								"User.userstatus_id",
								"Usertype.name",
								"Userstatus.name",
								"Profile.fullname",
								"Profile.address",
								"Profile.gender",
								"Profile.province_id",
								"Profile.city_id",
								"Profile.phone",
								"Profile.fax",
								"Profile.lat",
								"Profile.lng",
								"Profile.ym",
								"Company.name",
								"Company.address",
								"Company.province_id",
								"Company.city_id",
								"Company.phone",
								"Company.fax",
								"Company.lat",
								"Company.lng"
							)
						));
						
		if($data)
		{
			$this->loadModel("Province");
			
			//CHECK FOTO
			$dir			=	$this->settingvar['path_content']."User/".$user_id."/";
			$have_photo		=	$this->General->CheckIsFilePhoto($dir);
			$fProvince	=	$this->Province->findById($data["Profile"]["city_id"]);
			$data["Profile"]["province_name"]	=	$fProvince["Province"]["province"];
			$data["Profile"]["city_name"]	=	$fProvince["Province"]["name"];
			$data["Profile"]["have_photo"]	=	$have_photo;
			$status		=	true;
			$message	=	"Ok";
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	"Maaf profil anda tidak ditemukan";
			$data		=	NULL;
			$code		=	"03";
		}
		
		$out		=	array("status"=>$status,"code"=>$code,"message"=>$message,"data"=>$data);
		$this->set("data",$out);
		$this->render(false);
	}
	
	function Verification()
	{
		$data									=	array();
		$data['ValidationCode']['user_id']		=	$user_id	=	$_REQUEST['user_id'];
		$data['ValidationCode']['code']			=	$_REQUEST['code'];
		
		//FILTERING DATA
		$this->loadModel('ValidationCode');
		$this->ValidationCode->set($data);
		$this->ValidationCode->ValidateByApps();
		$error			=	$this->ValidationCode->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			
			//UPDATE USER
			$this->loadModel('User');
			$data		=	$this->User->findById($user_id);
			$activated	=	date("Y-m-d H:i:s");
			$this->User->updateAll(
				array(
					'userstatus_id'	=>	"'1'",
					'activated'		=>	"'".$activated."'",
					'last_login'	=>	"'".date("Y-m-d H:i:s")."'"
				),
				array(
					'User.id'		=> $data['User']['id']
				)
			);
			
			//SAVE USER ACTIONS
			$text = $this->Action->generateHTML("register", array('[some]','[site_name]'), array($data['Profile']['fullname'],$this->settingvar['site_name']), array("Anda",$this->settingvar['site_name']));
			$this->Action->save($data["User"]["id"],array('chanel'=>'android'));
			
			
			$fullname	=	$data['Profile']['fullname'];
			$email		=	$data['User']['email'];
			$address	=	$data['Profile']['address'];
			$type		=	$data['Usertype']['name'];
			$search 	=	array('[fullname]', '[email]', '[address]', '[type]', '[date]','[site_name]','[site_url]');
			$replace 	=	array($fullname,$email,$address,$type,$activated,$this->settingvar['site_name'],$this->settingvar['site_url']);
			$this->Action->EmailSave('admin_alert_user_register_android', $this->settingvar['admin_mail'], $search, $replace);
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$code		=	"03";
			$data		=	false;
		}
		
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function ResendVerification()
	{
		$status		=	false;
		$message	=	ERR_03;
		$code		=	"03";
		$data		=	NULL;
		$attr_session						=	array();
		
		//DEFINE MODEL RESEND LOG
		$this->loadModel('ResendLog');
		
		//DEFINE DATA INCOMING
		$this->loadModel('User');
		$user_id		=	$_REQUEST['user_id'];
		$data			=	$this->User->findById($user_id);
		$this->data['User']['email_resend']		=	$data['User']['email'];
		$attr_session['ResendLog']['key_name']	=	$data['User']['email'];
		
		//INITIATE COUNTER
		$error			=	array();
		$email_id		=	$this->data['User']['email_resend'];
		$session		= 	$this->ResendLog->find('first',array(
								"conditions"	=>	array(
									"ResendLog.key_name"	=>	$data['User']['email'],
									"ResendLog.type"		=>	"1"
								)
							));
							
		if(!empty($session))
		{
			$attr_session['ResendLog']['id']	=	$session['ResendLog']['id'];
		}
		$COUNTER 		= 	empty($session) ? 0 : $session['ResendLog']['counter'];
		$LAST_HIT 		= 	$session['ResendLog']['last_hit'];
		$LAST_HIT 		= 	empty($LAST_HIT) ? time() : $LAST_HIT;
		$expired 		= 	mktime(date("H", $LAST_HIT) + 1, date("i", $LAST_HIT), date("s", $LAST_HIT), date("m", $LAST_HIT), date("d", $LAST_HIT), date("Y", $LAST_HIT));

		if (time() > $expired)
		{
			$this->ResendLog->create();
			$attr_session['ResendLog']['counter']	=	$COUNTER	=	0;
			$attr_session['ResendLog']['last_hit']	=	time();
			$update									=	$this->ResendLog->save($attr_session);
			$attr_session['ResendLog']['id']		=	$update['ResendLog']['id'];
		}
		
		
		if($COUNTER>2)
		{
			$message	=	ERR_04;
			$code		=	"04";
			$data		=	NULL;
		}
		else
		{
			$this->User->set($this->data);
			$this->User->InitiateResend();
			$error	=	$this->User->InvalidFields();
			
			if(empty($error))
			{
				$this->loadModel('EmailLog');
				$send		=	$this->Action->ResendEmailLog2($email_id,"android");
				$this->ResendLog->create();
				$attr_session['ResendLog']['counter']	=	$COUNTER + 1;
				$attr_session['ResendLog']['last_hit']	=	time();
				$update									=	$this->ResendLog->save($attr_session);
				$attr_session['ResendLog']['id']		=	$update['ResendLog']['id'];
				
				$status		=	true;
				$message	=	ERR_00;
				$code		=	"00";
				$data		=	$update;
			}
			else
			{
				$status		=	false;
				$message	=	$error;
				$code		=	"03";
				$data		=	NULL;
			}
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function ForgotPassword()
	{
		$data									=	array();
		$data['User']['email']		=	$email	=	$_REQUEST['email'];
		$attr_session							=	array();
		
		//DEFINE MODEL RESEND LOG
		$this->loadModel('ResendLog');
		
		//DEFINE DATA INCOMING
		$attr_session['ResendLog']['key_name']	=	$email;
		
		//INITIATE COUNTER
		$error			=	array();
		$session		= 	$this->ResendLog->find('first',array(
								"conditions"	=>	array(
									"ResendLog.key_name"	=>	$email,
									"ResendLog.type"		=>	"2"
								)
							));
							
		if(!empty($session))
		{
			$attr_session['ResendLog']['id']	=	$session['ResendLog']['id'];
		}
		$COUNTER 		= 	empty($session) ? 0 : $session['ResendLog']['counter'];
		$LAST_HIT 		= 	$session['ResendLog']['last_hit'];
		$LAST_HIT 		= 	empty($LAST_HIT) ? time() : $LAST_HIT;
		$expired 		= 	mktime(date("H", $LAST_HIT) + 1, date("i", $LAST_HIT), date("s", $LAST_HIT), date("m", $LAST_HIT), date("d", $LAST_HIT), date("Y", $LAST_HIT));

		if (time() > $expired)
		{
			$this->ResendLog->create();
			$attr_session['ResendLog']['type']		=	"2";
			$attr_session['ResendLog']['counter']	=	$COUNTER	=	0;
			$attr_session['ResendLog']['last_hit']	=	time();
			$update									=	$this->ResendLog->save($attr_session);
			$attr_session['ResendLog']['id']		=	$update['ResendLog']['id'];
		}
		
		//FILTERING DATA
		$this->loadModel('User');
		$this->User->set($data);
		$this->User->ValidateForgot();
		$error			=	$this->User->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			$data		=	NULL;
			
			if($COUNTER>2)
			{
				$status		=	false;
				$message	=	array("email"=>ERR_04);
				$code		=	"04";
				$data		=	NULL;
			}
			else
			{
				$this->ResendLog->create();
				$attr_session['ResendLog']['type']		=	"2";
				$attr_session['ResendLog']['counter']	=	$COUNTER + 1;
				$attr_session['ResendLog']['last_hit']	=	time();
				$update									=	$this->ResendLog->save($attr_session);
				$attr_session['ResendLog']['id']		=	$update['ResendLog']['id'];
				
				//GETTING TOKEN
				$this->loadModel('FPToken');
				$token		= $this->FPToken->GetTokenAndroid($email);
				$user		= $this->User->findByEmail($email);
				$link 		= $this->settingvar['site_url'] . 'Users/ForgotPassword/email:' . $email ."/token:" .$token;
				$imgsrc 	= $this->settingvar['logo_url'];
				$search 	= array('[logo_url]','[fullname]','[username]','[code]','[link]','[site_name]','[site_url]');
				$replace 	= array($this->settingvar['logo_url'],$user['Profile']['fullname'],$email,$token,$link,$this->settingvar['site_name'],$this->settingvar['site_url']);
				$this->Action->EmailSend('forgot_password_android', $email, $search, $replace);
				$out		=	array("status"=>true,"error"=>$this->settingvar['site_url'].'Users/ForgotPasswordSend');
			}
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$code		=	"03";
			$data		=	NULL;
		}	
		
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function ForgotPasswordChange()
	{
		$data								=	array();
		$data['User']['email']				=	$email				=	$_REQUEST['email'];
		$data['User']['token']				=	$token				=	$_REQUEST['token'];
		$data['User']['password']			=	$password			=	$_REQUEST['newpassword'];
		$data['User']['retype_password']	=	$retype_password	=	$_REQUEST['retype_password'];
		$this->data							=	$data;
		
		//FILTERING DATA
		$this->loadModel('User');
		$this->User->set($this->data	);
		$this->User->ValidateProcessForgotApps();
		$error			=	$this->User->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			
			//UPDATE USERS PASSWORD
			$update = $this->User->updateAll(
							array(
								'password' => "'" . md5($password) . "'"
							),
							array(
								'User.email' => $email
							)
					);
					
			$data			=	$this->User->findByEmail($email);
			
			//SAVE USER ACTIONS
			$text = $this->Action->generateHTML("signin", array('[username]'), array($data['Profile']['fullname']), array("Anda"));
			$this->Action->save($data['User']['id']);
				
			//UPDATE TOKEN
			$this->loadModel('FPToken');
			$this->FPToken->UpdateToken($token,"1");
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$code		=	"03";
			$data		=	NULL;
		}	
		
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function ChangePassword()
	{
		$data								=	array();
		$data['User']['id']					=	$user_id			=	$_REQUEST['user_id'];
		$data['User']['newpassword']		=	$newpassword		=	$_REQUEST['newpassword'];
		$data['User']['cnewpassword']		=	$cnewpassword		=	$_REQUEST['cnewpassword'];
		$this->data							=	$data;
		
		//FILTERING DATA
		$this->loadModel('User');
		$this->User->set($this->data);
		$this->User->ValidateChangePassword();
		$error			=	$this->User->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			
			//UPDATE USERS PASSWORD
			$user['User']['password']	=	md5($this->data["User"]["newpassword"]);
			$user['User']['id']			=	$user_id;
			$update 					=	$this->User->updateAll(
												array(
													'password' => "'" . md5($this->data["User"]["newpassword"]) . "'"
												),
												array(
													'User.id' => $user_id
												)
											);
			$data						=	$this->User->findById($user_id);
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$code		=	"03";
			$data		=	NULL;
		}	
		
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function UploadFoto()
	{
		$data								=	array();
		$data['User']['id']					=	$user_id			=	$_REQUEST['user_id'];
		$data['User']['image']				=	$image				=	$_REQUEST['image'];
		$this->data							=	$data;
		
		//FILTERING DATA
		$error	=	array();
		
		if(empty($image))
		{
			$error['foto']	=	"Silahkan pilih foto anda.";
		}
		elseif(empty($user_id))
		{
			$error['foto']	=	"Silahkan login terlebih dahulu.";
		}
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			//SAVE PHOTO
			if(!empty($this->data['User']['image']))
			{	
				$source						=	base64_decode($this->data['User']['image']);
				$ext						=	"jpg";
				$cnt_user_dir				=	$this->settingvar['path_content']."User/";
				$cnt_userid_dir				=	$cnt_user_dir.$user_id."/";
				$this->General->RmDir($cnt_userid_dir);
				$destination				=	$cnt_userid_dir.$user_id.".".$ext;
				if(!is_dir($cnt_user_dir)) 		mkdir($cnt_user_dir,0777);
				if(!is_dir($cnt_userid_dir)) 	mkdir($cnt_userid_dir,0777);
				$handle 					=	fopen($destination, 'w');
				if (fwrite($handle, $source)) 
				{
					chmod($destination,0777);
				}
				fclose($handle);
			}
			$data			=	NULL;
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$code		=	"03";
			$data		=	NULL;
		}	
		
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function UpdateProfile()
	{
		App::import('Sanitize');
		
		$data								=	array();
		$data['User']['id']					=	$user_id			=	$_REQUEST['user_id'];
		$data['User']['fullname']			=	$fullname			=	$_REQUEST['fullname'];
		$data['User']['gender']				=	$gender				=	$_REQUEST['gender'];
		
		$data['User']['address']			=	$address			=	$_REQUEST['address'];
		$data['User']['province']			=	$province			=	$_REQUEST['province'];
		$data['User']['city']				=	$city				=	$_REQUEST['city'];
		$data['User']['lat']				=	$lat				=	$_REQUEST['lat'];
		$data['User']['lng']				=	$lng				=	$_REQUEST['lng'];
		
		$data['User']['phone']				=	$phone				=	$_REQUEST['phone'];
		$data['User']['ym']					=	$ym					=	$_REQUEST['ym'];
		$data['User']['fax']				=	$fax				=	$_REQUEST['fax'];
		$this->data							=	$data;
		
		//FILTERING DATA
		$this->loadModel('User');
		$this->User->set($this->data);
		$this->User->InitiateValidate();
		$error			=	$this->User->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			
			//SAVE PROFILE
			$this->loadModel('Profile');
			$this->data['User']['fullname']		=	Sanitize::html($this->data['User']['fullname']);
			$this->data['User']['address']		=	Sanitize::html($this->data['User']['address']);
			$profile							=	$this->Profile->updateAll(
														array(
															'fullname'		=>	"'".$this->data['User']['fullname']."'",
															'address'		=>	"'".$this->data['User']['address']."'",
															'province_id'	=>	"'".$this->data['User']['province']."'",
															'city_id'		=>	"'".$this->data['User']['city']."'",
															'lat'			=>	$this->data['User']['lat'],
															'lng'			=>	$this->data['User']['lng'],
															'phone'			=>	"'".trim($this->data['User']['phone'])."'",
															'fax'			=>	!empty($this->data['User']['fax']) ? "'".trim($this->data['User']['fax'])."'" : NULL,
															'ym'			=>	!empty($this->data['User']['ym']) ? "'".trim($this->data['User']['ym'])."'" : NULL,
															'gender'		=>	!empty($this->data['User']['gender']) ? "'". $this->data['User']['gender']."'" : NULL
														),
														array(
															"User.id"		=>	$user_id
														)
													);
							
							
			//UPDATE PRODUCT
			$this->loadModel('Product');
			$updt_product	=	$this->Product->updateAll(
									array(
										'Product.contact_name'	=>	"'".$this->data['User']['fullname']."'",
										'Product.address'		=>	"'".$this->data['User']['address']."'",
										'Product.province_id'	=>	"'".$this->data['User']['province']."'",
										'Product.city_id'		=>	"'".$this->data['User']['city']."'",
										'Product.ym'			=>	"'".$this->data['User']['ym']."'",
										'Product.lat'			=>	"'".$this->data['User']['lat']."'",
										'Product.lng'			=>	"'".$this->data['User']['lng']."'"
									),
									array(
										'Product.user_id'		=>	$user_id
									)
								);
								
			$data			=	$this->User->findById($user_id);
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$code		=	"03";
			$data		=	NULL;
		}	
		
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function MerekMotor()
	{
		//LOAD MODEL
		$this->loadModel("Category");
		$data		=	$this->Category->find("all",array(
							"conditions"	=>	array(
								'Category.parent_id' => $this->Category->GetTop(),
								'Category.status'	 => 1
							),
							"order"	=>	array(
								"Category.name ASC"
							),
							"fields"	=>	array(
								"Category.id",
								"Category.name",
							)
						));
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		$this->autoRender	=	false;
	}
	
	function TipeMotor()
	{
		$cat_id			=	$_REQUEST['cat_id'];
		
		//LOAD MODEL
		$this->loadModel("Category");
		$data		=	$this->Category->find("all",array(
							"conditions"	=>	array(
								'Category.parent_id' => $cat_id,
								'Category.status'	 => 1
							),
							"order"	=>	array(
								"Category.name ASC"
							),
							"fields"	=>	array(
								"Category.id",
								"Category.name",
							)
						));
		
		if(!empty($data))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
		}
		else
		{
			$status		=	false;
			$message	=	ERR_02;
			$code		=	"02";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		$this->autoRender	=	false;
	}
	
	function AddProduct()
	{
		App::import('Sanitize');
		
		//LOAD MODEL
		$this->loadModel('Product');
		$this->loadModel('Category');
		
		$trans 								=	array(' ' => '', '.' => '', ',' => '');
		$data['Product']['user_id']			=	$user_id	=	$_REQUEST['user_id'];
		$data['Product']['category_id']		=	$_REQUEST['category_id'];
		$data['Product']['data_type']		=	$data_type	=	$_REQUEST['data_type'];
		$data['Product']['contact_name']	=	$_REQUEST['contact_name'];
		$data['Product']['phone']			=	$_REQUEST['phone'];
		$data['Product']['address']			=	$_REQUEST['address'];
		$data['Product']['province_id']		=	$_REQUEST['province_id'];
		$data['Product']['city_id']			=	$_REQUEST['city_id'];
		$data['Product']['condition_id']	=	$_REQUEST['condition_id'];
		$data['Product']['nopol']			=	($_REQUEST['condition_id']==1) ? -1 : strtoupper(Sanitize::html($_REQUEST['nopol']));
		
		$data['Product']['thn_pembuatan']	=	$_REQUEST['thn_pembuatan'];
		$data['Product']['color']			=	$_REQUEST['color'];
		$data['Product']['kilometer']		=	($_REQUEST['condition_id']==1) ? 0 : $_REQUEST['kilometer'];
		$data['Product']['stnk_id']			=	($_REQUEST['condition_id']==1) ? -1 : (int)$_REQUEST['stnk_id'];
		$data['Product']['bpkb_id']			=	($_REQUEST['condition_id']==1) ? -1 : (int)$_REQUEST['bpkb_id'];
		
		$data['Product']['description']		=	Sanitize::html($_REQUEST['description']);
		$data['Product']['is_credit']		=	$_REQUEST['is_credit'];
		$data['Product']['price']			=	strtr($_REQUEST['price'],$trans);
		$data['Product']['first_credit']	=	strtr($_REQUEST['first_credit'],$trans);
		$data['Product']['credit_interval']	=	strtr($_REQUEST['credit_interval'],$trans);
		$data['Product']['credit_per_month']=	strtr($_REQUEST['credit_per_month'],$trans);
		$data['Product']['modified_by']		=	"Owner(".$data['Product']['contact_name'].")";
		$data['Product']['seo_name']		=	$this->Category->GetSeoName($data['Product']['category_id']);
		$data['Product']['agree']			=	$_REQUEST['agree'];
		$data['Product']['image_str']		=	(!empty($_REQUEST['image_str'])) ? base64_decode($_REQUEST['image_str'])  : "";
		$this->data							=	$data;
		
		$this->Product->set($this->data);
		$this->Product->InitiateValidateOdp();
		$error	=	$this->Product->InvalidFields();
		
		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			$data		=	NULL;
			
			$this->loadModel("User");
			$user	=	$this->User->findById($user_id);
			
			
			if($data_type==1)
			{
				if(!empty($user['Profile']['lat']))
				{
					$this->data['Product']['lat']		=	$user['Profile']['lat'];
					$this->data['Product']['lng']		=	$user['Profile']['lng'];
				}
			}
			else
			{
				if(!empty($user['Company']['lat']))
				{
					$this->data['Product']['lat']		=	$user['Company']['lat'];
					$this->data['Product']['lng']		=	$user['Company']['lng'];
				}
			}
			
			$this->Product->create();
			$save		=	$this->Product->save($this->data,false);
			$product_id	=	$this->Product->getLastInsertId();
			
			// START UPDATE PROFILE
			if($data_type==1)
			{
				$this->loadModel('Profile');
				if(empty($user['Profile']['address']))
				{					
					$profile_update	=	$this->Profile->updateAll(
											array(
												'address'		=>	"'".$this->data['Product']['address']."'",
												'province_id'	=>	"'".$this->data['Product']['province_id']."'",
												'city_id'		=>	"'".$this->data['Product']['city']."'"
											),
											array(
												'Profile.id'	=>	$user['Profile']['id']
											)
										);
				}
				
				if(empty($user['Profile']['phone']))
				{
					$phone			=	explode(",",$this->data['Product']['phone']);
					$profile_update	=	$this->Profile->updateAll(
											array(
												'phone'			=>	"'".$phone[0]."'"
											),
											array(
												'Profile.id'	=>	$user['Profile']['id']
											)
										);
				}
			}
			// END UPDATE PROFILE
			// START UPDATE COMPANY PROFILE
			elseif($data_type==2)
			{
				$this->loadModel('Company');
				if(empty($user['Company']['address']))
				{
					
					$profile_update	=	$this->Company->updateAll(
											array(
												'address'		=>	"'".$this->data['Product']['address']."'",
												'province_id'	=>	"'".$this->data['Product']['province_id']."'",
												'city_id'		=>	"'".$this->data['Product']['city']."'"
											),
											array(
												'Company.id'	=>	$user['Company']['id']
											)
										);
				}
				if(empty($this->profile['Company']['phone']))
				{
					$phone			=	explode(",",$this->data['Product']['phone']);
					$profile_update	=	$this->Company->updateAll(
											array(
												'phone'			=>	"'".$phone[0]."'"
											),
											array(
												'Company.id'	=>	$user['Company']['id']
											)
										);
				}
			}
			// END UPDATE COMPANY PROFILE
			
			//SAVE FOTO
			$this->loadModel('ProductImage');
			$ROOT			=	$this->settingvar['path_content'];
			$path			=	$ROOT."ProductImage/";
			if(!is_dir($path)) mkdir($path,0777);
			if(!empty($this->data['Product']['image_str']))
			{
				$is_primary		=	"1";
				//$message = "masuk-a";
				$this->ProductImage->create();
				$this->ProductImage->saveAll(array(
					'product_id'	=>	$product_id,
					'status'		=>	0,
					'is_primary'	=>	$is_primary,
					'number'		=>	1
				));
				
				$image_id		=	$this->ProductImage->getLastInsertId();
				$tmp_id			=	$path.$image_id."/";
				if(!is_dir($tmp_id)) mkdir($tmp_id,0777);
				$targetFile		=	$tmp_id.$image_id.".jpg";
				$handle 		=	fopen($targetFile, 'w');
				if (fwrite($handle, $this->data['Product']['image_str'])) 
				{
					//$message = "masuk-b";
					chmod($targetFile,0777);
				}
				fclose($handle);
			}
			
			//SEND EMAIL TO SUPER ADMIN
			App::import('Helper', 'Number');
			$Number 		= 	new NumberHelper();
			$detail_cat		=	$this->Category->GetCatAndSubcat($this->data['Product']['category_id']);
			$category		=	$detail_cat[0];
			$sub_category	=	$detail_cat[1];
			$contact		=	$this->data['Product']['contact_name'];
			$address		=	$this->data['Product']['address'];
			$price			=	$Number->format($this->data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
			$date			=	date("d-m-Y H:i:s",strtotime($save['Product']['created']));
			$link			=	$this->settingvar['cms_url']."Product/Edit/".$product_id;
			
			$search 		=	array('[logo_url]','[category]', '[sub_category]', '[contact]','[address]','[price]','[date]','[link]','[site_name]','[site_url]');
			$replace 		=	array($this->settingvar['logo_url'],$category,$sub_category,$contact,$address,$price,$date,$link,$this->settingvar['site_name'],$this->settingvar['site_url']);
			
			$search_s 		=	array('[category]');
			$replace_s 		=	array($category."-".$sub_category);
			$this->Action->EmailSave('admin_alert_user_addproduct', $this->settingvar['admin_mail'], $search, $replace,$search_s,$replace_s,'Product',$product_id);
		}
		else
		{
			$status		=	false;
			$message	=	$error;
			$data		=	NULL;
			$code		=	"03";
		}
		
		$out	=	array("status"=>$status,"message"=>$message,"data"=>$data,"code"=>$code);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
}
?>