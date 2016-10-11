<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class ApiController extends AppController
{
	public $uses			=	NULL;
	public $settings 		=	false;
	public $debug 			=	false;
	public $components 		=	array("General");
	
	public $availableLocale	=	array(
		"en"	=>	"eng",
		"in"	=>	"ind"
	);
	
	public $lang;

	public function beforeFilter()
	{
		//prepare for logging
		/*$requestLog = "\n===========START===========\n";
		foreach($_REQUEST as $key => $requested) {
			$requestLog .= "request['".$key."'] = ".$requested."\n";
		}
		$requestLog .= "===========END===========\n";
		CakeLog::write('apiLog', $requestLog);*/

		$this->autoRender = false;
		$this->autoLayout = false;
		define("ERR_00",__("Success"));
		define("ERR_01",__("Wrong username or password"));
		define("ERR_02",__("Data not found"));
		define("ERR_03",__("Validate Failed"));
		define("ERR_04",__("Parameter Not Completed!"));
		define("ERR_05",__("Failed send verification code!"));
		$token			=	(isset($_REQUEST['token'])) ? $_REQUEST['token'] : "";
		$this->lang		=	(isset($_REQUEST['lang']) && array_key_exists($_REQUEST['lang'],$this->availableLocale)) ? $this->availableLocale[$_REQUEST['lang']] : "eng";

		Configure::write('Config.language',$this->lang);

		if($token !== "461fd77b-1f04-4cf9-a045-49fb07435913")
		{
			echo json_encode(array("status"=>false,"message"=>__("Invalid Token"),"data"=>NULL,"code"=>"01"));
			exit;
		}

		//SETTING
		$this->settings = Cache::read('settings', 'long');
		if(!$this->settings || (isset($_GET['debug']) && $_GET['debug'] == "1")) {

			$this->loadModel('Setting');
			$settings			=	$this->Setting->find('first');
			$this->settings		=	$settings['Setting'];
			Cache::write('settings', $this->settings, 'long');
		}
	}

	function GetDataNeeded()
	{
		$user_id			=	(isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : NULL;
		$now				=	date("Y-m-d H:i:s");

		//SETTINGS
		$Setting['Setting']			=	$this->settings;

		//CUSTOMER CIGARETTE BRAND
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			$this->loadModel("CigaretteBrand");
			$CigaretteBrand		=		$this->CigaretteBrand->find("all",array(
															"conditions"	=>	array(
																"CigaretteBrand.status"	=> "1"
															),
															"order"			=>	array(
																"CigaretteBrand.id ASC"
															)
														));
			Cache::write('CigaretteBrand', $CigaretteBrand, 'long');
		}
		else
		{
			$CigaretteBrand = Cache::read('CigaretteBrand', 'long');
			if(!$CigaretteBrand)
			{
				$this->loadModel("CigaretteBrand");
				$CigaretteBrand		=		$this->CigaretteBrand->find("all",array(
																"conditions"	=>	array(
																	"CigaretteBrand.status"	=> "1"
																),
																"order"			=>	array(
																	"CigaretteBrand.id ASC"
																)
															));
				Cache::write('CigaretteBrand', $CigaretteBrand, 'long');
			}
		}

		//CUSTOMER CIGARETTE BRAND PRODUCT
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			$this->loadModel("CigaretteBrandProduct");
			$CigaretteBrandProduct		=		$this->CigaretteBrandProduct->find("all",array(
																			"conditions"	=>	array(
																				"CigaretteBrandProduct.status"	=> "1"
																			),
																			"order"			=>	array(
																				"CigaretteBrandProduct.id ASC"
																			)
																		));
			Cache::write('CigaretteBrandProduct', $CigaretteBrandProduct, 'long');
		}
		else
		{
			$CigaretteBrandProduct = Cache::read('CigaretteBrandProduct', 'long');
			if(!$CigaretteBrandProduct)
			{
				$this->loadModel("CigaretteBrandProduct");
				$CigaretteBrandProduct		=		$this->CigaretteBrandProduct->find("all",array(
																				"conditions"	=>	array(
																					"CigaretteBrandProduct.status"	=> "1"
																				),
																				"order"			=>	array(
																					"CigaretteBrandProduct.id ASC"
																				)
																			));
				Cache::write('CigaretteBrandProduct', $CigaretteBrandProduct, 'long');
			}
		}
		
		//GET TOTAL VALID
		$this->loadModel("Customer");
		$TotalValid			=	$this->Customer->find("count",array(
									"conditions"	=>	array(
										"Customer.is_valid"	=>	"1",
										"Customer.user_id"	=>	$user_id
									)
								));
								
		$TotalNotValid		=	$this->Customer->find("count",array(
									"conditions"	=>	array(
										"Customer.is_valid"	=>	"0",
										"Customer.user_id"	=>	$user_id
									)
								));
								
		$this->loadModel("User");
		$this->User->BindImageContent(false);
		$User				=	$this->User->find("first",array(
									"conditions"	=>	array(
										"User.id"	=>	$user_id
									)
								));
		$out				=	array(
									"Setting"					=>	$Setting,
									"CigaretteBrand"			=>	$CigaretteBrand,
									"CigaretteBrandProduct"		=>	$CigaretteBrandProduct,
									"request"					=>	$_REQUEST,
									"TotalValid"				=>	$TotalValid,
									"TotalNotValid"				=>	$TotalNotValid,
									"User"						=>	$User
								);
		

		$json		=	json_encode($out);
		$this->response->type('json');
		$this->response->body($json);
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			pr($out);
		}
	}

	public function Login()
	{
		/************************SAVE LOG******************************/
		if(!isset($_REQUEST['checklogs']))
		{
			//$ba_id			=	$_REQUEST["ba_id"][0];
			$path_log		=	$this->settings['path_webroot']."LogApi/";
			if(!is_dir($path_log)) mkdir($path_log);

			$path_date		=	$path_log.date("Ymd")."/";
			if(!is_dir($path_date)) mkdir($path_date);

			$path_model		=	$path_date."Login/";
			if(!is_dir($path_model)) mkdir($path_model);

			/*if(empty($ba_id) or is_null($ba_id) or !isset($ba_id))
				$ba_id	=	"unknown";

			$path_ba		=	$path_model.$ba_id."/";
				if(!is_dir($path_ba)) mkdir($path_ba);*/

			$path_file		=	$path_model.date("H_i_s").".txt";
			$output			=	json_encode($_REQUEST);
			$handle 		=	fopen($path_file, 'wb');
			fwrite($handle, $output);
			fclose($handle);
		}
		/************************SAVE LOG******************************/

		$status									=	false;
		$message								=	ERR_04;
		$code									=	"04";
		$TotalValid								=	0;
		$TotalNotValid							=	0;
		
		$request["User"]["email"]				=	empty($_REQUEST["email"]) ? "" : $_REQUEST["email"];
		$request["User"]["password"]			=	empty($_REQUEST["password"]) ? "" : $_REQUEST["password"];
		$now									=	date("Y-m-d H:i:s");
		$this->loadModel('User');
		$this->User->VirtualFieldActivated();
		$this->User->set($request);
		$this->User->ValidateLogin();

		$error									=	$this->User->InvalidFields();

		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";

			$joins		=	array(
				array(
					"table"			=>	"contents",
					"alias"			=>	"Image",
					'type'			 => 'LEFT',
					"conditions"	=>	array(
						"Image.model_id	=	User.id",
						"Image.model	=	'User'",
						"Image.type		=	'small'"
					)
				)
			);

			$data		=	$this->User->find('first',array(
								"conditions"	=>	array(
									"User.email"		=>	$request["User"]["email"],
									"User.password"		=>	$this->General->my_encrypt($request["User"]["password"])
								),
								"joins"					=>	$joins,
								"fields"				=>	array(
									"User.*",
									"Image.*"
								)
							));
							
			//GET TOTAL VALID
			$this->loadModel("Customer");
			$TotalValid			=	$this->Customer->find("count",array(
										"conditions"	=>	array(
											"Customer.is_valid"	=>	"1",
											"Customer.user_id"	=>	$data["User"]["id"]
										)
									));
									
			$TotalNotValid		=	$this->Customer->find("count",array(
										"conditions"	=>	array(
											"Customer.is_valid"	=>	"0",
											"Customer.user_id"	=>	$data["User"]["id"]
										)
									));
										
			
		}
		else
		{
			$status		=	false;
			foreach($error as $k => $v)
			{
				$message	=	$v[0];
				break;
			}
			$code		=	"03";
			$data		=	null;
		}

		$out			=	array(
			"status"				=>	$status,
			"message"				=>	$message,
			"data"					=>	$data,
			"TotalValid"			=>	$TotalValid,
			"TotalNotValid"			=>	$TotalNotValid,
			"code"					=>	$code,
			"request"				=>	$_REQUEST,
			"file"					=>	$_FILES
		);
		
		
		$json		=	json_encode($out);
		$this->response->type('json');
		$this->response->body($json);
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			pr($out);
		}
	}
	
	public function AddNewCustomer()
	{
		/************************SAVE LOG******************************/
		if(!isset($_REQUEST['checklogs']))
		{
			$user_id		=	$_REQUEST["user_id"];
			$path_log		=	$this->settings['path_webroot']."LogApi/";
			if(!is_dir($path_log)) mkdir($path_log);

			$path_date		=	$path_log.date("Ymd")."/";
			if(!is_dir($path_date)) mkdir($path_date);

			$path_model		=	$path_date."Customer/";
			if(!is_dir($path_model)) mkdir($path_model);

			if(empty($user_id) or is_null($user_id) or !isset($user_id))
				$user_id	=	"unknown";

			$path_ba		=	$path_model.$user_id."/";
				if(!is_dir($path_ba)) mkdir($path_ba);

			$path_file		=	$path_ba.date("H_i_s").".txt";
			$output			=	json_encode($_REQUEST);
			$handle 		=	fopen($path_file, 'wb');
			fwrite($handle, $output);
			fclose($handle);
		}
		/************************SAVE LOG******************************/

		$status												=	false;
		$message											=	ERR_04;
		$code												=	"04";
		$TotalCustomerPerDate								=	0;
		$TotalValid											=	0;
		$TotalNotValid										=	0;
		
		$request["Customer"]["id"]							=	empty($_REQUEST["id"]) ? "" : $_REQUEST["id"];
		$request["Customer"]["user_id"]						=	empty($_REQUEST["user_id"]) ? "" : $_REQUEST["user_id"];
		$request["Customer"]["device_id"]					=	empty($_REQUEST["device_id"]) ? "" : $_REQUEST["device_id"];
		$request["Customer"]["device_model"]				=	empty($_REQUEST["device_model"]) ? "" : $_REQUEST["device_model"];
		$request["Customer"]["device_date"]					=	empty($_REQUEST["device_date"]) ? "" : $_REQUEST["device_date"];
		
		
		$request["Customer"]["name"]						=	empty($_REQUEST["name"]) ? "" : $_REQUEST["name"];
		$request["Customer"]["email"]						=	empty($_REQUEST["email"]) ? "" : $_REQUEST["email"];
		$request["Customer"]["mobile_phone"]				=	empty($_REQUEST["mobile_phone"]) ? "" : $_REQUEST["mobile_phone"];
		$request["Customer"]["gender"]						=	empty($_REQUEST["gender"]) ? "" : $_REQUEST["gender"];
		$request["Customer"]["cigarette_brand_id"]			=	empty($_REQUEST["cigarette_brand_id"]) ? "" : $_REQUEST["cigarette_brand_id"];
		$request["Customer"]["cigarette_brand_product_id"]	=	empty($_REQUEST["cigarette_brand_product_id"]) ? "" : $_REQUEST["cigarette_brand_product_id"];
		$request["Customer"]["instagram"]					=	empty($_REQUEST["instagram"]) ? "" : $_REQUEST["instagram"];
		
		
		$this->loadModel('Customer');
		$this->Customer->set($request);
		$this->Customer->ValidateAdd();

		$error												=	$this->Customer->InvalidFields();

		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			
			$this->Customer->saveAll($request,array("validate" => false));
			$data		=	$this->Customer->find("first",array(
								"conditions"	=>	array(
									"Customer.id"	=>	$request["Customer"]["id"]
								)
							));
							
			//GET TOTAL VALID
			$TotalValid			=	$this->Customer->find("count",array(
										"conditions"	=>	array(
											"Customer.is_valid"	=>	"1",
											"Customer.user_id"	=>	$request["Customer"]["user_id"]
										)
									));
									
			$TotalNotValid		=	$this->Customer->find("count",array(
										"conditions"	=>	array(
											"Customer.is_valid"	=>	"0",
											"Customer.user_id"	=>	$request["Customer"]["user_id"]
										)
									));
			
		}
		else
		{
			$status		=	false;
			foreach($error as $k => $v)
			{
				$message	=	$v[0];
				break;
			}
			$code		=	"03";
			$data		=	null;
		}

		$out			=	array(
			"status"				=>	$status,
			"message"				=>	$message,
			"data"					=>	$data,
			"code"					=>	$code,
			"request"				=>	$_REQUEST,
			"TotalValid"			=>	$TotalValid,
			"TotalNotValid"			=>	$TotalNotValid
			
		);
		
		
		$json		=	json_encode($out);
		$this->response->type('json');
		$this->response->body($json);
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			pr($out);
		}
	}
	
	public function CustomerPending()
	{
		/************************SAVE LOG******************************/
		if(!isset($_REQUEST['checklogs']))
		{
			$path_log		=	$this->settings['path_webroot']."LogApi/";
			if(!is_dir($path_log)) mkdir($path_log);

			$path_date		=	$path_log.date("Ymd")."/";
			if(!is_dir($path_date)) mkdir($path_date);

			$path_model		=	$path_date."CustomerPending/";
			if(!is_dir($path_model)) mkdir($path_model);

			$path_ba		=	$path_model."/";
				if(!is_dir($path_ba)) mkdir($path_ba);

			$path_file		=	$path_ba.date("H_i_s").".txt";
			$output			=	json_encode($_REQUEST);
			$handle 		=	fopen($path_file, 'wb');
			fwrite($handle, $output);
			fclose($handle);
		}
		/************************SAVE LOG******************************/

		$this->loadModel("Customer");
		
		
		$login_id		=	(isset($_REQUEST['login_id'])) ? $_REQUEST['login_id'] : "";
		$result			=	array("success"	=>	array(),"failed" => array());
		
		foreach($_REQUEST["id"] as $k => $value)
		{
			$request["Customer"]["id"]							=	empty($_REQUEST["id"][$k]) ? "" : $_REQUEST["id"][$k];
			$request["Customer"]["user_id"]						=	empty($_REQUEST["user_id"][$k]) ? "" : $_REQUEST["user_id"][$k];
			$request["Customer"]["device_id"]					=	empty($_REQUEST["device_id"][$k]) ? "" : $_REQUEST["device_id"][$k];
			$request["Customer"]["device_model"]				=	empty($_REQUEST["device_model"][$k]) ? "" : $_REQUEST["device_model"][$k];
			$request["Customer"]["device_date"]					=	empty($_REQUEST["device_date"][$k]) ? "" : $_REQUEST["device_date"][$k];
			
			
			$request["Customer"]["name"]						=	empty($_REQUEST["name"][$k]) ? "" : $_REQUEST["name"][$k];
			$request["Customer"]["email"]						=	empty($_REQUEST["email"][$k]) ? "" : $_REQUEST["email"][$k];
			$request["Customer"]["mobile_phone"]				=	empty($_REQUEST["mobile_phone"][$k]) ? "" : $_REQUEST["mobile_phone"][$k];
			$request["Customer"]["gender"]						=	empty($_REQUEST["gender"][$k]) ? "" : $_REQUEST["gender"][$k];
			$request["Customer"]["cigarette_brand_id"]			=	empty($_REQUEST["cigarette_brand_id"][$k]) ? "" : $_REQUEST["cigarette_brand_id"][$k];
			$request["Customer"]["cigarette_brand_product_id"]	=	empty($_REQUEST["cigarette_brand_product_id"][$k]) ? "" : $_REQUEST["cigarette_brand_product_id"][$k];
			$request["Customer"]["instagram"]					=	empty($_REQUEST["instagram"][$k]) ? "" : $_REQUEST["instagram"][$k];
		
			$this->Customer->ValidateAdd();
			$this->Customer->set($request);
			$error												=	$this->Customer->InvalidFields();
			
			if(empty($error))
			{
				/*
				$save	=	$this->Customer->saveAll(
					array(
						"id"							=>	$value,
						"user_id"						=>	$_REQUEST["user_id"][$k],
						"device_id"						=>	$_REQUEST["device_id"][$k],
						"device_model"					=>	$_REQUEST["device_model"][$k],
						"name"							=>	$_REQUEST["name"][$k],
						"email"							=>	$_REQUEST["email"][$k],
						"mobile_phone"					=>	$_REQUEST["mobile_phone"][$k],
						"gender"						=>	$_REQUEST["gender"][$k],
						"instagram"						=>	$_REQUEST["instagram"][$k],
						"cigarette_brand_id"			=>	$_REQUEST["cigarette_brand_id"][$k],
						"cigarette_brand_product_id"	=>	$_REQUEST["cigarette_brand_product_id"][$k],
						"device_date"					=>	$_REQUEST["device_date"][$k]
					)
				);
				*/
				$save	=	$this->Customer->saveAll($request,array("validate" => false));
				if($save == true)
				{
					$result["success"][]	=	array("id"	=>	$value);
				}
				else
				{
					$result["failed"][]		=	array("id"	=>	$value,"error" => "unknown");
				}
			}
			else
			{
				$errMsg	=	"unknown";
				foreach($error as $k => $v)
				{
					$errMsg	=	$v[0];
					break;
				}
				$result["failed"][]		=	array("id"	=>	$value,"error" =>$errMsg);
			}
		}
		
		/*
		$result		=	array(
							"success"	=>	array(
								array(
									"id"	=>	""
								)
							),
							"failed"		=>	array(
								array(
									"id"		=>	"1",
									"error"		=>	""
								)
							)
						)
		*/
		
		//GET TOTAL VALID
		$TotalValid			=	$this->Customer->find("count",array(
									"conditions"	=>	array(
										"Customer.is_valid"	=>	"1",
										"Customer.user_id"	=>	$login_id
									)
								));
								
		$TotalNotValid		=	$this->Customer->find("count",array(
									"conditions"	=>	array(
										"Customer.is_valid"	=>	"0",
										"Customer.user_id"	=>	$login_id
									)
								));
									
		$json		=	json_encode(array(
							"result" => $result, 
							"TotalValid" => $TotalValid,
							"TotalNotValid" => $TotalNotValid,
							"request"		=>	$_REQUEST
							));
							
		$this->response->type('json');
		$this->response->body($json);
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			pr($out);
		}
	}

	function GetValidCustomer()
	{
		$status		=	false;
		$message	=	ERR_03;
		$data		=	null;
		$code		=	"03";
		$user_id	=	$_REQUEST['user_id'];
		$page		=	(empty($_REQUEST['page'])) ? 1 : $_REQUEST['page'];
		$is_valid	=	(isset($_REQUEST['is_valid'])) ? (in_array($_REQUEST['is_valid'],array("0","1")) ? $_REQUEST['is_valid'] : "1") : "1";
		
		//GET LIST CUSTOMER
		$this->loadModel('Customer');
		$this->Customer->BindDefault(false);
		
		$conditions			=	array(
									"Customer.is_valid"	=>	$is_valid,
									"Customer.user_id"	=>	$user_id
								);
								
		$this->paginate		=	array(
				"Customer"	=>	array(
					"order"			=>	"Customer.id DESC",
					"page"			=>	$page,		
					"limit"			=>	10,
					"recursive"		=>	3,
					"conditions"	=>	$conditions
				)
			);
		
		
		try
		{
			$fCustomer		=	$this->paginate("Customer");
		}
		catch(NotFoundException $e)
		{
			$fCustomer		=	array();
		}

		if(empty($fCustomer))
		{
			$status		=	true;
			$message	=	ERR_02;
			$data		=	array();
			$code		=	"02";
		}
		else
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			$data		=	$fCustomer;
		}
		
		$out	=	array(
						"status"	=>	$status,
						"message"	=>	$message,
						"data"		=>	$data,
						"code"		=>	$code,
						"pageCount"	=>	$this->params['paging']['Customer']['pageCount'],
						"page"		=>	$this->params['paging']['Customer']['page'],
						"totalData"	=>	$this->params['paging']['Customer']['count'],
						"nextPage"	=>	$this->params['paging']['Customer']['nextPage'],
						"request"		=>	$_REQUEST
						);
		
		$json		=	json_encode($out);
		$this->response->type('json');
		$this->response->body($json);
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			pr($out);
		}
	}
	
	public function EditProfile()
	{
		/************************SAVE LOG******************************/
		if(!isset($_REQUEST['checklogs']))
		{
			$user_id		=	$_REQUEST["id"];
			$path_log		=	$this->settings['path_webroot']."LogApi/";
			if(!is_dir($path_log)) mkdir($path_log);

			$path_date		=	$path_log.date("Ymd")."/";
			if(!is_dir($path_date)) mkdir($path_date);

			$path_model		=	$path_date."EditProfile/";
			if(!is_dir($path_model)) mkdir($path_model);

			if(empty($user_id) or is_null($user_id) or !isset($user_id))
				$user_id	=	"unknown";

			$path_ba		=	$path_model.$user_id."/";
				if(!is_dir($path_ba)) mkdir($path_ba);

			$path_file		=	$path_ba.date("H_i_s").".txt";
			$output			=	json_encode($_REQUEST);
			$handle 		=	fopen($path_file, 'wb');
			fwrite($handle, $output);
			fclose($handle);
		}
		/************************SAVE LOG******************************/

		$status												=	false;
		$message											=	ERR_04;
		$code												=	"04";
		$TotalCustomerPerDate								=	0;
		$TotalValid											=	0;
		$TotalNotValid										=	0;
		
		$request["User"]["id"]								=	$ID	=	empty($_REQUEST["id"]) ? "" : $_REQUEST["id"];
		
		$request["User"]["name"]							=	empty($_REQUEST["name"]) ? "" : trim($_REQUEST["name"]);
		
		$request["User"]["email"]							=	empty($_REQUEST["email"]) ? "" : trim($_REQUEST["email"]);
		
		$this->loadModel('User');
		$this->User->BindImageContent(false);
		$this->User->set($request);
		$this->User->ValidateEditBA();

		$error												=	$this->User->InvalidFields();

		if(empty($error))
		{
			$status		=	true;
			$message	=	ERR_00;
			$code		=	"00";
			
			$this->User->saveAll($request,array("validate" => false));
			if(!empty($_FILES['images']['name']))
			{
				$tmp_name							=	$_FILES['images']["name"];
				$tmp								=	$_FILES['images']["tmp_name"];
				$mime_type							=	$_FILES['images']["type"];

				$path_tmp							=	ROOT.DS.'app'.DS.'tmp'.DS.'upload'.DS;
					if(!is_dir($path_tmp)) mkdir($path_tmp,0777);

				$ext								=	pathinfo($tmp_name,PATHINFO_EXTENSION);
				$tmp_file_name						=	md5(time());
				$tmp_images1_img					=	$path_tmp.$tmp_file_name.".".$ext;
				$upload 							=	move_uploaded_file($tmp,$tmp_images1_img);
				if($upload)
				{
					$resize							=	$this->General->ResizeImageContent(
															$tmp_images1_img,
															$this->settings["cms_url"],
															$ID,
															"User",
															"small",
															"image/jpg",
															600,
															600,
															"resizeMaxWidth"
														);

				}
				@unlink($tmp_images1_img);
			}
			
			$data		=	$this->User->find("first",array(
								"conditions"	=>	array(
									"User.id"	=>	$request["User"]["id"]
								)
							));
			
		}
		else
		{
			$status		=	false;
			foreach($error as $k => $v)
			{
				$message	=	$v[0];
				break;
			}
			$code		=	"03";
			$data		=	null;
		}

		$out			=	array(
			"status"				=>	$status,
			"message"				=>	$message,
			"data"					=>	$data,
			"code"					=>	$code,
			"request"				=>	$_REQUEST,
			"file"					=>	$_FILES
		);
		
		
		$json		=	json_encode($out);
		$this->response->type('json');
		$this->response->body($json);
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
		{
			pr($out);
		}
	}
}
?>
