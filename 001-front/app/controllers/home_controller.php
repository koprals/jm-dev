<?php
class HomeController extends AppController
{
	var $components = array('Cookie');
	var $name		=	"Home";
	var $uses		=	null;
	var $helpers	=	array("Text","Number","General");
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function Index()
	{
		$this->set("current_category_id","all_categories");
		$this->set("current_city","all_cities");
	}
	
	function TestDelete()
	{
		$this->General->RmDir('/home/jualanmotor/catch-all/aby/');

	}
	
	function DaftarMotor($category_id="all_categories",$current_city="all_cities")
	{
		$this->set("current_menu","iklan_terbaru");
		$this->loadModel("Product");
		$this->Session->write('back_url',$this->settings['site_url'].$this->params["url"]["url"]);

		//DEFINE DATA TO VIEW
		$this->set("current_category_id",$category_id);
		$this->set("current_city",$current_city);
		
		//GET DATA
		$filtering			=	$this->Product->GetData($category_id,$current_city,"DaftarMotor");
		$this->paginate		=	array(
			'Product'	=>	array(
				'limit'			=>	20,
				'order'			=>	$filtering['order'],
				'group'			=>	array('Product.id'),
				//'fields'		=>	$filtering['fields'],
				'conditions'	=>	$filtering['conditions'],
			)
		);
		$data				=	$this->paginate('Product');
		
		if($_GET["debug"]==1)
		{
			$this->General->RmDir($this->settings['path_web'].'app/tmp/cache/pagination/product/');
			//var_dump($this->params);
		}
		/*echo"<pre>";
		print_r($data);
		echo"</pre>";*/
		
		
		$title_for_layout	=	$filtering['title'];
		$site_description	=	"Jual motor, cari motor bekas atau baru. " . $filtering['title'] . " Ratusan iklan jual beli motor baru dan bekas terbaru diiklankan setiap harinya.";
		$site_keywords		=	$filtering['keywords'].",".implode(", ",explode(" ",$title_for_layout));
		$this->set(compact("data","title_for_layout","site_keywords","site_description"));

		if(empty($data))
		{
			$error_msg			=	$this->Product->NotFoundMsg($category_id,$current_city);
			$this->set(compact("error_msg"));
			
			$category_others	=	$this->Product->GetCategoryOthers($category_id,$current_city,$type="DaftarMotor");
			$this->set(compact("category_others"));
			
			$province_others	=	$this->Product->GetProvinceOthers($category_id,$current_city,$type="DaftarMotor");
			$this->set(compact("province_others"));
			
			//DEFINE CATEGORY
			$this->loadModel("Category");
			$cat			=	$this->Category->findById($category_id);
			$category_name	=	($cat['Category']['parent_id'] == $this->Category->GetTop()) ? $cat['Category']['name'] : $cat['Parent']['name']." ".$cat['Category']['name'];
			$this->set(compact("category_name"));
			
			//PROVINCE NAME
			$this->loadModel("ProvinceGroup");
			$province			=	$this->ProvinceGroup->findById($current_city);
			$province_name		=	$province['ProvinceGroup']['name'];
			$this->set(compact("province_name"));
		}
	}
	
	function Carousel($category_name)
	{
		$this->layout	=	"ajax";
		//$category_name	=	$_POST["category_name"];
		
		//LOAD MODEL
		$this->loadModel('Product');
		$data	=	$this->Product->Carousel($category_name);
		$data	=	(count($data)<4) ? array() : $data;
		$page	=	(count($data)<=4) ? 1 : ceil(count($data)/4);
		
		/*echo"<pre>";
		print_r($data);
		echo"</pre>";*/
		$this->set(compact("data","page","category_name"));
	}
}
?>