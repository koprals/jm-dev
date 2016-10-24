<?php 
class SearchController extends AppController
{
	var $components = array('Cookie');
	var $name		=	"Search";
	var $uses		=	null;
	var $helpers	=	array("Text","Number");
	
	function beforeFilter()
	{
		parent::beforeFilter();
		
	}
	
	function Index()
	{
		//DEFINE CATEGORY
		$this->loadModel('Category');
        $category = $this->Category->DisplayCategory();
        $this->set("category", $category);
		
		//DEFINE CITY
		$this->loadModel("ProvinceGroup");
		$ProvinceGroup	=	$this->ProvinceGroup->DisplayProvinceGroup();
		unset($ProvinceGroup["all_cities"]);
		$this->set('ProvinceGroup',$ProvinceGroup);
		
		//DEFINE CONDITIONS
		$this->loadModel('Product');
        $condition = $this->Product->DisplayCondition();
        $this->set("condition", $condition);
		
		//DEFINE STNK
		$this->loadModel('Stnk');
		$stnk = $this->Stnk->DisplayStnk();
        $this->set("stnk", $stnk);
		
		$this->Session->delete('Search.Product');
	}
	
	function ListItem()
	{
		$this->loadModel('Category');
		$this->layout	=	"ajax";
		$viewpage		=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order			=	(empty($this->params['named']['sort'])) ? array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC") : array("IF( Product.sold = '0', 1, 0) DESC,".$this->params['named']['sort']." ".$this->params['named']['direction']);
		unset($this->params['named']['sort']);
		unset($this->params['named']['direction']);
		
		
		//DEFINE FIELDS
		$fields			=	array(
								"Product.id",
								"Product.price",
								"Product.is_credit",
								"Product.thn_pembuatan",
								"Product.condition_id",
								"Product.kilometer",
								"Product.sold",
								"Product.data_type",
								"Product.ym",
								"Product.contact_name",
								"Product.view",
								"Category.name",
								"Parent.name",
								"Province.name",
								"Province.province",
								"ProductImage.id"
							);
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		$reset	=	$this->data["Search"]["reset"];
		if(!empty($this->data))
		{
			$this->Session->delete('Search.Product');
			$trans 								=	array(' ' => '', '.' => '', ',' => '');
			$this->data['Search']['price_from']	=	strtr($this->data['Search']['price_from'], $trans);
			$this->data['Search']['price_to']	=	strtr($this->data['Search']['price_to'], $trans);
			
			if(!empty($this->data['Search']['parent_id']))
			{
				$cond_search['Parent.id']	=	$this->data['Search']['parent_id'];
			}
			
			if(!empty($this->data['Search']['category_id']))
			{
				$cond_search['Product.category_id']	=	$this->data['Search']['category_id'];
			}
			
			if(!empty($this->data['Search']['group_id']))
			{
				$this->loadModel("Province");
				$city_id	=	$this->Province->find("list",array("fields"=>"Province.id","conditions"=>array("Province.group_id"=>$this->data['Search']['group_id'])));
				
				$cond_search['Product.city_id']	=	$city_id;
			}
			
			if(!empty($this->data['Search']['color']))
			{
				$cond_search["Product.color LIKE "]		=	"%".$this->data['Search']['color']."%";
			}
			if(!empty($this->data['Search']['price_from']) && empty($this->data['Search']['price_to']))
			{
				$cond_search["Product.price >= "]		=	$this->data['Search']['price_from'];
			}
			
			if(empty($this->data['Search']['price_from']) && !empty($this->data['Search']['price_to']))
			{
				$cond_search["Product.price <= "]		=	$this->data['Search']['price_to'];
			}
			
			if(!empty($this->data['Search']['price_from']) && !empty($this->data['Search']['price_to']))
			{
				$price_from		=	$this->data['Search']['price_from'];
				$price_to		=	$this->data['Search']['price_to'];
				if($this->data['Search']['price_to'] < $this->data['Search']['price_from'])
				{
					$price_to		=	$this->data['Search']['price_from'];
					$price_from		=	$this->data['Search']['price_to'];
				}
				$cond_search["Product.price BETWEEN ? AND ?"]		=	array($price_from,$price_to);
			}
			
			if(!empty($this->data['Search']['condition_id']))
			{
				$cond_search['Product.condition_id']	=	$this->data['Search']['condition_id'];
			}
			
			if(!empty($this->data['Search']['stnk_id']))
			{
				$cond_search['Product.stnk_id']	=	($this->data['Search']['stnk_id']=="1") ? array(1,2) : 3;
			}
			
			if(!empty($this->data['Search']['bpkb_id']))
			{
				$cond_search['Product.bpkb_id']	=	$this->data['Search']['bpkb_id'];
			}
			
			
			if(strlen($this->data['Search']['is_credit'])>0)
			{
				$cond_search['Product.is_credit']	=	$this->data['Search']['is_credit'];
			}
			
			if(!empty($this->data['Search']['thn_from']) && empty($this->data['Search']['thn_to']))
			{
				$cond_search["Product.thn_pembuatan >= "]		=	$this->data['Search']['thn_from'];
			}
			
			if(empty($this->data['Search']['thn_from']) && !empty($this->data['Search']['thn_to']))
			{
				$cond_search["Product.thn_pembuatan <= "]		=	$this->data['Search']['thn_to'];
			}
			
			if(!empty($this->data['Search']['thn_from']) && !empty($this->data['Search']['thn_to']))
			{
				$thn_from	=	$this->data['Search']['thn_from'];
				$thn_to		=	$this->data['Search']['thn_to'];
				if($this->data['Search']['thn_to'] < $this->data['Search']['thn_from'])
				{
					$thn_to		=	$this->data['Search']['thn_from'];
					$thn_from	=	$this->data['Search']['thn_to'];
				}
				$cond_search["Product.thn_pembuatan BETWEEN ? AND ?"]		=	array($thn_from,$thn_to);
			}
			
			if(!empty($this->data['Search']['km_from']) && empty($this->data['Search']['km_to']))
			{
				$cond_search["Product.kilometer >= "]		=	$this->data['Search']['km_from'];
			}
			
			if(empty($this->data['Search']['km_from']) && !empty($this->data['Search']['km_to']))
			{
				$cond_search["Product.kilometer <= "]		=	$this->data['Search']['km_to'];
			}
			
			if(!empty($this->data['Search']['km_from']) && !empty($this->data['Search']['km_to']))
			{
				$km_from	=	$this->data['Search']['km_from'];
				$km_to		=	$this->data['Search']['km_to'];
				if($this->data['Search']['km_to'] < $this->data['Search']['km_from'])
				{
					$km_to		=	$this->data['Search']['km_from'];
					$km_from	=	$this->data['Search']['km_to'];
				}
				$cond_search["Product.kilometer BETWEEN ? AND ?"]		=	array($km_from,$km_to);
			}
			
			$this->Session->write("Search.Product",$cond_search);
		}//END DEFINE QUERY
		
		
		//DELETE SESSION
		if($this->data["Search"]['reset']=="1")
		{			
			$this->Session->delete('Search.Product');
			unset($this->data);
		}
		
		//PAGING DATA
		$cond_search		=	array();
		$filter_paginate	=	array(
									  'Product.productstatus_id'	=>	1,
									  'Product.productstatus_user'	=> 	1
								);
		
		$this->loadModel("Product");
		$this->Product->BindUnbind();
		$this->paginate	=	array(
			'Product'	=>	array(
				'limit'		=>	12,
				'order'		=>	$order,
				'group'		=>	array('Product.id'),
				'fields'	=>	$fields
			)
		);
		
		$ses_cond		= $this->Session->read("Search.Product");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('Product',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['Product']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['Product']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set(compact("data","reset"));
		
	}
}
?>