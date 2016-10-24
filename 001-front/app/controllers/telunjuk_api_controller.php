<?php
class TelunjukApiController extends AppController
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
		
		if(($username!=="telunjuk" && $password!=="t31un7uk"))
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
				'fields'		=>	array_merge($filtering['fields'],array("Surl")),
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
}
?>