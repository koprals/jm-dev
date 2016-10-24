<?php
class ManageProductsController extends AppController
{
	var $name		=	"ManageProducts";
	var $uses		=	array('Product');
	
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	
	function Index($tab_active=0)
	{
		$this->Session->write('back_url',$this->settings['site_url'].$this->params["url"]["url"]);
		$this->set("active_code","manage_products");
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
		
		//DEFINE STATUS
		$this->loadModel('Productstatus');
		$status	=	$this->Productstatus->ShowStatus();
		$this->set(compact("status"));
		
		//DEFINE CATEGORY
		$this->loadModel('Category');
        $category = $this->Category->DisplayCategory();
        $this->set("category", $category);
		
		//DEFINE CONDITIONS
		$this->loadModel('Product');
        $condition = $this->Product->DisplayCondition();
        $this->set("condition", $condition);
		
		$this->Session->delete('SearchProduct');
		$this->Session->delete('Cond.Product');
		$this->set("tab_active",$tab_active);
		//$this->render("index-lama");
	}
	
	function ListItem($product_status,$reset=0)
	{
		$this->loadModel('Category');
		$this->layout	=	"ajax";
		$viewpage		=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order			=	array('Product.created DESC');
		
		//DEFINE FIELDS
		$fields			=	array(
								'Product.*',
								'Category.name',
								'Parent.name',
								'Productstatus.id',
								'ProductImage.id'
							);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('Cond.Product');
			
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
			$this->Session->write("Cond.Product",$cond_search);
			$order			=	array('score DESC','Product.created DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		if(!empty($this->data))
		{
			$this->Session->delete('Cond.Product');
			$trans 					=	array(' ' => '', '.' => '', ',' => '');
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
			
			if(!empty($this->data['Search']['condition_id']))
			{
				$cond_search['Product.condition_id']	=	$this->data['Search']['condition_id'];
			}
			if(!empty($this->data['Search']['nopol']))
			{
				$cond_search["Product.nopol LIKE "]		=	"%".$this->data['Search']['nopol']."%";
			}
			
			if(!empty($this->data['Search']['color']))
			{
				$cond_search["Product.color LIKE "]		=	"%".$this->data['Search']['color']."%";
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
				$cond_search["Product.price BETWEEN ? AND ?"]		=	array($price_from,$price_to);
			}
			$this->Session->write("Cond.Product",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1" or $reset==1)
		{
			$this->Session->delete('Cond.Product');
			unset($this->data);
		}
		
		//LOAD MODEL PRODUCT
		$this->loadModel('Product');
		$this->Product->bindModel(
                array('hasOne' => array(
						'ProductImage' => array(
							'className'		=> 'ProductImage',
							'foreignKey'	=> 'product_id',
							'conditions'	=> "ProductImage.is_primary = '1'"
						)
			)), false
        );
		$viewpage			=	empty($this->params['named']['limit']) ? 10 : $this->params['named']['limit'];
		
		//DEFINE FILTERING
		$cond_search		=	array();
		$is_sold			=	($product_status=="sold") ? array('Product.productstatus_id !='	=>	-10, 'Product.sold'	=>	"1") : array('Product.productstatus_id'	=>	$product_status) ;
		$filter_paginate	=	($product_status!=="100") ?
								array_merge(
								array(
									  'Product.user_id' 			=>  $this->user_id,
									  'Product.productstatus_user'  =>  1
							    ),$is_sold) : 
								array(
									  'Product.productstatus_id != '=>	-10,
									  'Product.user_id' 			=>  $this->user_id,
									  'Product.productstatus_user'	=> 	1
							    );
		
		$this->paginate	=	array(
			'Product'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'group'		=>	array('Product.id'),
				'cache'		=>	false,
				'fields'	=>	$fields
			)
		);
		$ses_cond		= $this->Session->read("Cond.Product");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('Product',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['Product']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['Product']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set(compact('data','product_status','page','viewpage'));
	}
	
	
	function SelectAll($product_status)
	{
		$this->layout		=	"json";
		$filter_paginate	=	($product_status!=="100") ? array('Product.productstatus_id'	=>	$product_status,'Product.user_id' => $this->user_id) : array('Product.productstatus_id !='	=>	-10,'Product.user_id' => $this->user_id);
		
		$ses_cond		= $this->Session->read("Cond.Product");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->Product->find('all',array(
									'conditions'	=>	array_merge($filter_paginate,$cond_search),
									'fields'		=>	array('Product.id'),
									'group'			=>	array('Product.id')
								));
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	
	function LoadStatusJson()
	{
		$this->layout	=	"json";
		
		$this->loadModel('Productstatus');
		$data	=	$this->Productstatus->ShowStatus();
		$this->set("data",$data);
		$this->render(false);
	}
	
	function GetSumStatus($id)
	{
		$this->layout		=	"json";
		$is_sold			=	($id=="sold") ? array('Product.productstatus_id !='	=>	-10, 'Product.sold'	=>	"1") : array('Product.productstatus_id'	=>	$id) ;
		$cond				=	($id!=="100") ?
								array_merge(
								array(
									  'Product.user_id' 			=>  $this->user_id,
									  'Product.productstatus_user'  =>  1
							    ),$is_sold) : 
								array(
									  'Product.productstatus_id != '=>	-10,
									  'Product.user_id' 			=>  $this->user_id,
									  'Product.productstatus_user'	=> 	1
							    );
								
		$this->loadModel('Product');
		$data	=	$this->Product->find("count",array(
						'conditions'	=>	$cond
					));
		$this->set("data",$data);
		$this->render(false);
	}
	
	function Delete()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terdelete :</b><br><br>";
		$data_delete		=	"<b>Data telah didelete :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $product_id)
		{
			$detail				=	$this->Product->find('first',array(
										'conditions'	=>	array(
											'Product.id'		=>	$product_id,
											'Product.user_id'	=>	$this->user_id
										)
									));
			
			
			
			if($detail)
			{
				$delete				=	$this->Product->updateAll(
											array(
												'Product.productstatus_user'	=>	0,
												'Product.modified'				=>	"'".date("Y-m-d H:i:s")."'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.".".$detail['Parent']['name']." - ".$detail['Category']['name']."<br>";
				}
				else
				{
					//DELETE CACHE
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
					
					
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.".".$detail['Parent']['name']." - ".$detail['Category']['name']."<br>";
					}
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$detail				=	$this->Product->find('first',array(
										'conditions'	=>	array(
											'Product.id'		=>	end($id),
											'Product.user_id'	=>	$this->user_id
										)
									));
			
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.".".$detail['Parent']['name']." - ".$detail['Category']['name'];
		}
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function Sold()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terupdate :</b><br><br>";
		$data_delete		=	"<b>Data telah diupdate :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $product_id)
		{
			$detail				=	$this->Product->find('first',array(
										'conditions'	=>	array(
											'Product.id'				=>	$product_id,
											'Product.user_id'			=>	$this->user_id
										)
									));
			
			$count++;
			if($detail['Product']['productstatus_id']==1)
			{
				$update				=	$this->Product->updateAll(
											array(
												'Product.sold'					=>	"'1'",
												'Product.modified'				=>	"'".date("Y-m-d H:i:s")."'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				if($update==false)
				{
					$err++;
					$item_notdelete	.= $count.". ".$detail['Parent']['name']." - ".$detail['Category']['name']."<br>";
				}
				else
				{
					//DELETE CACHE
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
					
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". ".$detail['Parent']['name']." - ".$detail['Category']['name']."<br>";
					}
				}
			}
			else
			{
				$err++;
				$item_notdelete	.= $count.". ".$detail['Parent']['name']." - ".$detail['Category']['name']." (Iklan masih dalam status '".$detail['Productstatus']['name']."')<br><br>";	
			}
		}
		
		if(count($tr_id)>7)
		{
			$detail				=	$this->Product->find('first',array(
										'conditions'	=>	array(
											'Product.id'		=>	end($id),
											'Product.user_id'	=>	$this->user_id
										)
									));
			
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.".".$detail['Parent']['name']." - ".$detail['Category']['name'];
		}
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
}
?>