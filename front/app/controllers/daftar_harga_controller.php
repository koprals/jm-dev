<?php
class DaftarHargaController extends AppController
{
	var $name			=	"DaftarHarga";
	var $uses			=	null;
	var $helpers		=	array("Text","Number","General");
	
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function Index($category_id="all_categories")
	{
		//DEFINE DATA TO VIEW
		$this->set("current_menu","daftar_harga");
		$this->set("current_category_id",$category_id);
		
		//DEFINE BACK URL
		$this->Session->write('back_url',$this->settings['site_url'].$this->params["url"]["url"]);
		
		//GET DATA
		$this->loadModel("DaftarHarga");
		$this->loadModel("Category");
									
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
		
		$this->paginate		=	array(
			'DaftarHarga'	=>	array(
				'limit'			=>	30,
				'order'			=>	array("Parent.name","Category.name"),
				'group'			=>	array('Category.id','DaftarHarga.thn_pembuatan'),
				'fields'		=>	array("Parent.name","Category.name","Parent.id","Category.id","DaftarHarga.thn_pembuatan","MIN(DaftarHarga.price) as MIN","MAX(DaftarHarga.price) as MAX"),
				'conditions'	=>	$conditions,
			)
		);
		$data				=	$this->paginate('DaftarHarga');
		
		$title_for_layout	=	$this->settings['site_name'].": Daftar harga motor baru dan bekas.";
		$site_description	=	$title_for_layout;
		$site_keywords		=	implode(", ",explode(" ",$title_for_layout)).", ".$this->settings['site_keywords'];
		
		$this->set(compact("data","title_for_layout","site_description","site_keywords"));
		/*
			SELECT Parent.name, Category.name, Product.thn_pembuatan, MIN( Product.price ) , MAX( Product.price )
FROM products AS Product
LEFT JOIN categories AS Category ON ( Product.category_id = Category.id )
LEFT JOIN categories AS Parent ON ( Category.parent_id = Parent.id )
GROUP BY Category.id, Product.thn_pembuatan
ORDER BY Parent.name ASC , Category.name ASC
LIMIT 0 , 30
		*/
	}
}
?>