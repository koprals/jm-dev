<?php
class EditingRequiredProductController extends AppController
{
	var $name	=	"EditingRequiredProduct";
	var $uses		=	array('Product');
	var $helpers	=	array('Time');
	var $components	=	array('Action');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','product_catalog');
		$this->set('child_code','editing_required');
		$this->layout	=	"new";
	}
	function Index()
	{
		$this->Session->delete('CondSearch.EditingRequired');
		
		//DEFINE CATEGORY
		$this->loadModel('Category');
        $category = $this->Category->DisplayCategory();
        $this->set("category", $category);
		
		//DEFINE CONDITIONS
        $condition = $this->Product->DisplayCondition();
        $this->set("condition", $condition);
		
		//DEFINE PRODUCT STATUS
		$this->loadModel("Productstatus");
		$product_statuses		=	$this->Productstatus->find("list",array("conditions"=>array('Productstatus.id >'=>-10),'order' =>	array('Productstatus.name ASC')));
		$this->set(compact("product_statuses"));
		
		//DISPLAY PROVINCE
		 $this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
	}
	
	function ListItem()
	{
		$this->layout		=	"ajax";
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order				=	array('Product.modified DESC');
		
		//DEFINE FIELDS
		$fields				=	array(
									'Product.*',
									'Category.*',
									'Parent.*',
									'Productstatus.id',
									'Productstatus.name',
									'Stnk.name',
									'Bpkb.name'
								);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('CondSearch.EditingRequired');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'Parent.name LIKE'		=> "%$val%",
											'Category.name LIKE'	=> "%$val%",
											'Product.nopol LIKE'	=> "%$val%"
										)
									);
				
				}
			}
			$OR['OR'][]			=	"MATCH (Parent.name, Category.name, Product.nopol) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			array_push($fields,"MATCH (Parent.name, Category.name, Product.nopol) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("CondSearch.EditingRequired",$cond_search);
			$order			=	array('score DESC','Product.created DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		//$cond_search	=	array();
		if(!empty($this->data))
		{
			$this->Session->delete('CondSearch.EditingRequired');
			$trans 					=	array(' ' => '', '.' => '', ',' => '');
			$this->data['Search']['price_from']	=	strtr($this->data['Search']['price_from'], $trans);
			$this->data['Search']['price_to']	=	strtr($this->data['Search']['price_to'], $trans);
			
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["Product.id"]					=	$this->data['Search']['id'];
			}
			if(!empty($this->data['Search']['parent_id']))
			{
				$cond_search['Parent.id']					=	$this->data['Search']['parent_id'];
			}
			if(!empty($this->data['Search']['category_id']))
			{
				$cond_search['Product.category_id']			=	$this->data['Search']['category_id'];
			}
			if(!empty($this->data['Search']['contact_name']))
			{
				$cond_search["Product.contact_name LIKE "]	=	"%".$this->data['Search']['contact_name']."%";
			}
			if(!empty($this->data['Search']['tgl_input']))
			{
				$string		=	explode("s.d",str_replace(" ","",$this->data['Search']['tgl_input']));
				
				
				if(count($string) > 1)
				{
					$date1		=	strtotime($string[0]);
					$date2		=	strtotime($string[1]);
					if($date1 > $date2)
					{
						$date1		=	$date2;
						$date2		=	strtotime($string[0]);
					}
					
					$cond_search["Product.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
					$cond_search["Product.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				
			}
			
			if(!empty($this->data['Search']['data_type']))
			{
				$cond_search["Product.data_type"]			=	$this->data['Search']['data_type'];
			}
			if(!empty($this->data['Search']['condition_id']))
			{
				$cond_search['Product.condition_id']		=	$this->data['Search']['condition_id'];
			}
			if(!empty($this->data['Search']['province_id']))
			{
				$cond_search['Product.province_id']			=	$this->data['Search']['province_id'];
			}
			if(!empty($this->data['Search']['city_id']))
			{
				$cond_search['Product.city_id']				=	$this->data['Search']['city_id'];
			}
			
			if(!empty($this->data['Search']['nopol']))
			{
				$cond_search["Product.nopol LIKE "]		=	"%".$this->data['Search']['nopol']."%";
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
				$cond_search["Product.price BETWEEN ? AND ?"]	=	array($price_from,$price_to);
			}
			if(!empty($this->data['Search']['color']))
			{
				$cond_search["Product.color LIKE "]				=	"%".$this->data['Search']['color']."%";
			}
			$this->Session->write("CondSearch.EditingRequired",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.EditingRequired');
			unset($this->data);
		}
		
		//DEFINE FILTERING
		$this->Product->bindModel(
                array('belongsTo' => array(
						'Stnk' => array(
							'className' 	=> 'Stnk',
							'foreignKey' 	=> 'stnk_id'
						),
						'Bpkb' => array(
							'className' 	=> 'Bpkb',
							'foreignKey' 	=> 'bpkb_id'
						)
			)), false
        );
		$cond_search		=	array();
		$filter_paginate	=	array('Product.productstatus_id' => -1);
		$this->paginate		=	array(
			'Product'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'group'		=>	array('Product.id'),
				'fields'	=>	$fields
			)
		);
		$ses_cond			=	$this->Session->read("CondSearch.EditingRequired");
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$data				=	$this->paginate('Product',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['Product']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['Product']['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
		
	}
}

?>