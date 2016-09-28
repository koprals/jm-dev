<?php
class ProductLogDetailController extends AppController
{
	var $name		=	"ProductLogDetail";
	var $uses		=	array('ProductEditLog');
	var $helpers	=	array('Time');
	var $components	=	array('Action');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','logs');
		$this->set('child_code','iklan_log');
		$this->layout	=	"new";
	}
	
	function Index($product_id="all")
	{
		$this->Session->delete('CondSearch.ProductEditLog');
		$this->set('product_id',$product_id);
		
		//DEFINE CATEGORY
		$this->loadModel('Category');
        $category = $this->Category->DisplayCategory();
        $this->set("category", $category);
		
		//DEFINE CONDITIONS
		$this->loadModel('Product');
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
	
	function ListItem($product_id="all")
	{
		$this->layout		=	"ajax";
		$this->set('product_id',$product_id);
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order				=	array('ProductEditLog.modified DESC');
		
		//DEFINE FIELDS
		$fields				=	array(
									'ProductEditLog.*',
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
			$this->Session->delete('CondSearch.ProductEditLog');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'Parent.name LIKE'		=> "%$val%",
											'Category.name LIKE'	=> "%$val%",
											'ProductEditLog.nopol LIKE'	=> "%$val%"
										)
									);
				
				}
			}
			$OR['OR'][]			=	"MATCH (Parent.name, Category.name, ProductEditLog.nopol) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			array_push($fields,"MATCH (Parent.name, Category.name, ProductEditLog.nopol) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->delete('CondSearch.ProductEditLog');
			$order			=	array('score DESC','ProductEditLog.modified DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		if(!empty($this->data))
		{
			$this->Session->delete('CondSearch.ProductEditLog');
			$trans 					=	array(' ' => '', '.' => '', ',' => '');
			$this->data['Search']['price_from']	=	strtr($this->data['Search']['price_from'], $trans);
			$this->data['Search']['price_to']	=	strtr($this->data['Search']['price_to'], $trans);
			
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["ProductEditLog.id"]					=	$this->data['Search']['id'];
			}
			if(!empty($this->data['Search']['parent_id']))
			{
				$cond_search['Parent.id']					=	$this->data['Search']['parent_id'];
			}
			if(!empty($this->data['Search']['category_id']))
			{
				$cond_search['ProductEditLog.category_id']			=	$this->data['Search']['category_id'];
			}
			if(!empty($this->data['Search']['contact_name']))
			{
				$cond_search["ProductEditLog.contact_name LIKE "]	=	"%".$this->data['Search']['contact_name']."%";
			}
			if(!empty($this->data['Search']['modified_by']))
			{
				$cond_search["ProductEditLog.modified_by LIKE "]	=	"%".$this->data['Search']['modified_by']."%";
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
					
					$cond_search["ProductEditLog.modified BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
					$cond_search["ProductEditLog.modified BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				
			}
			
			if(!empty($this->data['Search']['data_type']))
			{
				$cond_search["ProductEditLog.data_type"]			=	$this->data['Search']['data_type'];
			}
			
			if(!empty($this->data['Search']['status']))
			{
				$cond_search["ProductEditLog.productstatus_id"]	=	$this->data['Search']['status'];
			}
			if(!empty($this->data['Search']['condition_id']))
			{
				$cond_search['ProductEditLog.condition_id']		=	$this->data['Search']['condition_id'];
			}
			if(!empty($this->data['Search']['province_id']))
			{
				$cond_search['ProductEditLog.province_id']			=	$this->data['Search']['province_id'];
			}
			if(!empty($this->data['Search']['city_id']))
			{
				$cond_search['ProductEditLog.city_id']				=	$this->data['Search']['city_id'];

			}
			
			if(!empty($this->data['Search']['nopol']))
			{
				$cond_search["ProductEditLog.nopol LIKE "]		=	"%".$this->data['Search']['nopol']."%";
			}
			
			if(!empty($this->data['Search']['thn_from']) && empty($this->data['Search']['thn_to']))
			{
				$cond_search["ProductEditLog.thn_pembuatan >= "]		=	$this->data['Search']['thn_from'];
			}
			
			if(empty($this->data['Search']['thn_from']) && !empty($this->data['Search']['thn_to']))
			{
				$cond_search["ProductEditLog.thn_pembuatan <= "]		=	$this->data['Search']['thn_to'];
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
				$cond_search["ProductEditLog.thn_pembuatan BETWEEN ? AND ?"]		=	array($thn_from,$thn_to);
			}
			if(!empty($this->data['Search']['price_from']) && empty($this->data['Search']['price_to']))
			{
				$cond_search["ProductEditLog.price >= "]		=	$this->data['Search']['price_from'];
			}
			
			if(empty($this->data['Search']['price_from']) && !empty($this->data['Search']['price_to']))
			{
				$cond_search["ProductEditLog.price <= "]		=	$this->data['Search']['price_to'];
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
				$cond_search["ProductEditLog.price BETWEEN ? AND ?"]	=	array($price_from,$price_to);
			}
			if(!empty($this->data['Search']['color']))
			{
				$cond_search["ProductEditLog.color LIKE "]				=	"%".$this->data['Search']['color']."%";
			}
			$this->Session->write("Product.cond_search",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.ProductEditLog');
			unset($this->data);
		}
		
		//DEFINE FILTERING
		$cond_search		=	array();
		$filter_paginate	=	(preg_match('/^([0-9]+)$/',$product_id)) ? array('ProductEditLog.product_id' => $product_id) : array();
		
		$this->ProductEditLog->bindModel(
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
		$this->paginate		=	array(
			'ProductEditLog'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'group'		=>	array('ProductEditLog.id'),
				'fields'	=>	$fields
			)
		);
		$ses_cond			=	$this->Session->read('CondSearch.ProductEditLog');
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$data				=	$this->paginate('ProductEditLog',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['ProductEditLog']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['ProductEditLog']['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
	}
	
	function GetFullText($ID)
	{
		$this->layout	=	"json";
		$data			=	$this->ProductEditLog->findById($ID);
		$this->set("data",$data['ProductEditLog']['notice']);
		$this->render(false);
	}
	
	function GetTruncateText($ID)
	{
		//IMPORT HELPERS
		App::Import('Helper','Text');
		$text	=	new TextHelper();
		
		$this->layout	=	"json";
		$data			=	$this->ProductEditLog->findById($ID);
		
		if(strlen($data['ProductEditLog']['notice']) > 20 )
		{
			$text		=	$text->truncate($data['ProductEditLog']['notice'],20,array('ending'=>"..<br>"));
		}
		else
		{
			$text		= nl2br($data['ProductEditLog']['notice']);
		}
		$this->set("data",$text);
		$this->render(false);
	}
	function GetFullText2($ID)
	{
		$this->layout	=	"json";
		$data			=	$this->ProductEditLog->findById($ID);
		$this->set("data",$data['ProductEditLog']['text_modified']);
		$this->render(false);
	}
	
	function GetTruncateText2($ID)
	{
		//IMPORT HELPERS
		App::Import('Helper','Text');
		$text	=	new TextHelper();
		
		$this->layout	=	"json";
		$data			=	$this->ProductEditLog->findById($ID);
		
		if(strlen($data['ProductEditLog']['text_modified']) > 20 )
		{
			$text		=	$text->truncate($data['ProductEditLog']['text_modified'],20,array('ending'=>"..<br>"));
		}
		else
		{
			$text		= nl2br($data['ProductEditLog']['text_modified']);
		}
		$this->set("data",$text);
		$this->render(false);
	}
	
	
	
	function SelectAll($product_id)
	{
		$this->layout		=	"json";
		$filter_paginate	=	array('ProductEditLog.product_id' => $product_id);
		$ses_cond			= 	$this->Session->read("CondSearch.ProductEditLog");
		$cond_search		= 	isset($ses_cond) ? $ses_cond : array();
		$data				= 	$this->ProductEditLog->find('all',array(
									'conditions'	=>	array_merge($filter_paginate,$cond_search),
									'fields'		=>	array('ProductEditLog.id'),
									'group'			=>	array('ProductEditLog.id'),
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
		$product_id			=	"";
		
		foreach($id as $log_id)
		{
			
			$detail				=	$this->ProductEditLog->findById($log_id);
			$product_id			=	empty($product_id) ? $detail['ProductEditLog']['product_id'] : $product_id;
			
			if($detail)
			{
				$delete				=	$this->ProductEditLog->delete($log_id);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$log_id."<br>";
				}
				else
				{
					$tr_id[]		=	$log_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$log_id."<br>";
					}
				}
			}
		}
		
		//CHECK IF LOG IS EMPTY THEN SET HAVELOG='0' ON PRODUCT TABLE
		$totallog	=	$this->ProductEditLog->find('count',array(
							'conditions'	=>	array(
								'ProductEditLog.product_id'	=>	$product_id
							)
						));
		if($totallog==0)
		{
			$this->loadModel('Product');
			$update_have_log	=	$this->Product->updateAll(
										array(
											'Product.have_log'	=>	"'0'",
											'Product.notice'	=>	NULL
										),
										array(
											'Product.id'		=>	$product_id
										)
									);
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_delete_edit_product_log", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function DetailLog($ID)
	{
		$this->loadModel('Province');
		$this->loadModel('Stnk');
		$this->loadModel('Bpkb');
		$this->loadModel('Productstatus');
		
		App::import('Helper', 'Number');
		$Number 			= 	new NumberHelper();
		
		App::import('Helper', 'Time');
		$Time 				= 	new TimeHelper();
				
		$data				=	$this->ProductEditLog->findById($ID);
		
		//DEFINE PHOTO
		$this->loadModel('ProductImageLog');
		$img 				= $this->ProductImageLog->GetImages($ID);
        $this->set("img", $img);
		
		$id					=	$data['ProductEditLog']['id'];
		$user_id			=	$data['ProductEditLog']['user_id'];
		$data_type			=	($data['ProductEditLog']['data_type']==1) ? "Profile" : "Company";
		$contact_name		=	$data['ProductEditLog']['contact_name'];
		$parent_name		=	$data['Parent']['name'];
		$category_name		=	$data['Category']['name'];
		$telp				=	$data['ProductEditLog']['phone'];
		$ym					=	(!empty($data['ProductEditLog']['ym'])) ? $data['ProductEditLog']['ym'] : "-";
		$address			=	$data['ProductEditLog']['address'];
		$province_name		=	$this->Province->GetNameProvince($data['ProductEditLog']['province_id']);
		$city_name			=	$this->Province->GetNameCity($data['ProductEditLog']['city_id']);
		$conditions			=	($data['ProductEditLog']['condition_id']==2) ? "Baru" : "Bekas";
		$nopol				=	($data['ProductEditLog']['nopol']==-1) ? "-" : $data['ProductEditLog']['nopol'];
		$thn_pembuatan		=	$data['ProductEditLog']['thn_pembuatan'];
		$color				=	$data['ProductEditLog']['color'];
		$kilometer			=	$data['ProductEditLog']['kilometer'];
		$description		=	(empty($data['ProductEditLog']['description'])) ? "-" : $data['ProductEditLog']['description'];
		$stnk				=	$this->Stnk->GetStatusStnk($data['ProductEditLog']['stnk_id']);
		$bpkb				=	$this->Bpkb->GetStatusBpkb($data['ProductEditLog']['bpkb_id']);
		$price				=	$Number->format($data['ProductEditLog']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		
		$is_credit			=	($data['ProductEditLog']['is_credit']==1) ? "Ya" : "Tidak";
		$first_credit		=	($data['ProductEditLog']['is_credit']==1) ? $Number->format($data['ProductEditLog']['first_credit'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) : "-";
		$credit_interval	=	($data['ProductEditLog']['is_credit']==1) ? $Number->format($data['ProductEditLog']['credit_interval'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) : "-";
		$credit_per_month	=	($data['ProductEditLog']['is_credit']==1) ? $Number->format($data['ProductEditLog']['credit_per_month'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) : "-";
		$status				=	$this->Productstatus->GetNameStatus($data['ProductEditLog']['productstatus_id']);
		
		$modified			=	date("d-M-Y",strtotime($data['ProductEditLog']['modified']))." (".$Time->timeAgoInWords($data['ProductEditLog']['modified']).")";
		$modified_by		=	(!empty($data['ProductEditLog']['modified_by'])) ? $data['ProductEditLog']['modified_by'] : "-";
		
		$notice				=	(!empty($data['ProductEditLog']['notice'])) ? $data['ProductEditLog']['notice'] : "-";
		$product_id				=	$data['ProductEditLog']['product_id'];
		
		$this->set(compact("data","id","user_id","data_type","contact_name","parent_name","category_name","telp","ym","address","province_name","city_name","conditions","nopol","thn_pembuatan","color","kilometer","description","stnk","bpkb","price","is_credit","first_credit","credit_interval","credit_per_month","status","created","modified","modified_by","notice","product_id"));
		
		$this->layout = "ajax";	
	}
}
?>