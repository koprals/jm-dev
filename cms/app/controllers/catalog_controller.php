<?php
class CatalogController extends AppController
{
	var $name	=	"Catalog";
	var $uses	=	array('Category');

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','list_category');
		$this->layout	=	"new";
	}
	
	function Index()
	{
		$this->Session->delete('SearchCategory');
		$this->Session->delete('Category.cond_search');
	}
	
	function ListItem()
	{
		$this->layout		=	"ajax";
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		
		$this->Category->bindModel(
                array('belongsTo' => array(
						'Parent' => array(
							'className' 	=>	'Category',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Category.parent_id = Parent.id'
						)
			)), false
        );
		
		//DEFINE FILTERING
		$cond_search	=	array();
		if(!empty($this->data))
		{
			if(!empty($this->data['Search']['parent_id']))
			{
				$list = $this->Category->children($this->data['Search']['parent_id'], true, array('Category.id'));
				$cat_arr = array();
				foreach ($list as $list)
				{
					$cat_arr[] = $list['Category']['id'];
				}
				$cat_arr[] 						=	$this->data['Search']['parent_id'];
				$cond_search["Category.id"]		=	$cat_arr;
			}
			
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["Category.id"]		=	$this->data['Search']['id'];
				$this->Session->write("SearchCategory.id",$this->data['Search']['id']);
			}
			
			if(!empty($this->data['Search']['name']))
			{
				$cond_search["Category.name LIKE "]		=	"%".$this->data['Search']['name']."%";
				$this->Session->write("SearchCategory.name",$this->data['Search']['name']);
			}
			if(!empty($this->data['Search']['parent']))
			{
				$cond_search["Parent.name LIKE "]		=	"%".$this->data['Search']['parent']."%";
				$this->Session->write("SearchCategory.parent",$this->data['Search']['parent']);
			}
			if(!empty($this->data['Search']['link']))
			{
				$cond_search["Category.url LIKE "]		=	"%".$this->data['Search']['link']."%";
				$this->Session->write("SearchCategory.link",$this->data['Search']['link']);
			}
			if(!empty($this->data['Search']['target']))
			{
				$cond_search["Category.target LIKE "]		=	"%".$this->data['Search']['target']."%";
				$this->Session->write("SearchCategory.target",$this->data['Search']['target']);
			}
			$this->Session->write("Category.cond_search",$cond_search);
		}
		
		//DELETE SESSION
		if($this->data['Search']['reset']=="1")
		{
			$this->Session->delete('SearchCategory');
			$this->Session->delete('Category.cond_search');
			unset($this->data);
		}
		//SET VALUE
		$this->set("id",$this->Session->read("SearchCategory.id"));
		$this->set("name",$this->Session->read("SearchCategory.name"));
		$this->set("parent",$this->Session->read("SearchCategory.parent"));
		$this->set("link",$this->Session->read("SearchCategory.link"));
		$this->set("target",$this->Session->read("SearchCategory.target"));
		
		
		$filter_paginate	=	array('Category.parent_id IS NOT NULL','Category.status' => 1);
		$this->paginate	=	array(
			'Category'	=>	array(
				'limit'	=> $viewpage,
				'order'	=> array('Category.lft ASC')
			)
		);
		$ses_cond		= $this->Session->read("Category.cond_search");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('Category',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['Category']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['Category']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
	}
	
	function SelectAll()
	{
		$this->layout		=	"json";
		$this->Category->bindModel(
                array('belongsTo' => array(
						'Parent' => array(
							'className' 	=>	'Category',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Category.parent_id = Parent.id'
						)
			)), false
        );
		$filter_paginate	=	array('Category.parent_id IS NOT NULL','Category.status' => 1);
		$ses_cond			= $this->Session->read("Category.cond_search");
		$cond_search		= isset($ses_cond) ? $ses_cond : array();
		$data				=	$this->Category->find('all',array(
									'conditions'	=>	array_merge($filter_paginate,$cond_search),
									'fields'		=>	array('Category.id')
								));
		$this->set("data",$data);
		$this->render(false);
	}
	
	function Delete()
	{
		$this->layout		=	"json";
		$id					=	$this->data['Category']['id'];
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"Maaf terdapat beberapa data yang tidak ter delete :\n";
		$data_delete		=	"Data telah di delete :\n";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $cat_id)
		{
			$detail				=	$this->Category->findById($cat_id);
			$delete				=	$this->Category->updateAll(
										array(
											'Category.status'		=>	-2
										),
										array(
											'Category.id'			=>	$cat_id
										)
									);
			
			$count++;
			if($delete==false)
			{
				$err++;
				$item_notdelete	.= $count.". Category : ID-".$detail['Category']['id']."\n";
			}
			else
			{
				$tr_id[]		=	$cat_id;
				if(count($tr_id)<7)
				{
					$item_delete	.= $count.". Category : ID-".$detail['Category']['id']."\n";
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........\n........\n";
			$item_delete	.= $count.". Category : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_delete_category", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		@unlink($this->settings['path_web'].'app/tmp/cache/cake_category_list');
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function Add($id="",$direction="")
	{
		//SET DEFAULT VALUE	
		$cat_id		=	"";
		$cat_name	=	"";
		
		
		//UPDATE CATEGORY IF NOT EMPTY ID
		if(!empty($direction) && !empty($id))
		{
			if($direction=="up")
			{
				$this->Category->moveUp($id, 1);
			}
			elseif($direction=="down")
			{
				$this->Category->moveDown($id, 1);
			}
			$this->redirect(array("controller"=>"Catalog","action"=>"Add",$id));
		}
		
		//CHECK DATA FIRST
		$find	=	$this->Category->find('first');
		if($find==false)
		{
			$data['Category']['name']	=	"TOP";
			$data['Category']['status']	=	"1";
			$this->Category->save($data);
		}
		
		//CHEK IF ISSET id
		if(!empty($id))
		{
			$find	=	$this->Category->findById($id);
			if($find)
			{
				$cat_id		=	$find['Category']['parent_id'];
				$cat_name	=	$find['Category']['name'];
			}
		}
		$data		=	$this->Category->generatetreelist(array('Category.status'=>'1'),null, null, $spacer = '----');
		
		$this->set(compact("data","cat_id","id","cat_name"));
	}
	
	function ProcessAddCategories()
	{
		$this->layout	=	"json";
		
		//SET DEFAULT VALUE
		$err			=	array();
		
		if(!empty($this->data))
		{
			$this->Category->set($this->data);
			if($this->Category->validates())
			{
				$this->data['Category']['name']	=	trim($this->data['Category']['name']);
				$this->Category->save($this->data,false);
				$out	=	array("status"	=>	true,"error"=>	"Data telah tersimpan.");
			}
			else
			{
				$error	=	$this->Category->invalidFields();
				foreach($error as $k=>$v)
				{
					$err[]	=	array("key"	=>	$k,"value"	=>	$v);
				}
				$out	=	array("status"	=>	false,"error"=>	$err);
			}
		}
		@unlink($this->settings['path_web'].'app/tmp/cache/cake_category_list');
		$this->set("data",$out);
		$this->render(false);
	}
	
	function ListCategory()
	{
		
	}
	
	function ListCategoryAjax()
	{
		$this->layout	=	"ajax";
		
		$this->paginate	=	array(
			"Category"	=>	array(
				"limit"	=>	1000,
				"order"	=>	array("Category.lft ASC","Category.name ASC")
			)
		);
		$data	=	$this->paginate("Category");
		$this->set("data",$data);
	}
	
	function getnodes()
	{
		// retrieve the node id that Ext JS posts via ajax
		$this->layout		=	"ajax";
		$parent 			=	(intval($this->params['form']['node'])==0) ? $this->Category->GetTop() : $this->params['form']['node'];
		$this->Category->Behaviors->detach('Tree');
		$this->Category->Behaviors->attach('Tree',array('scope'=>"`Category`.`status` = 1"));
		$nodes = $this->Category->children($parent, true);
		// send the nodes to our view
		$this->set(compact('nodes'));
	}
	
	function reorder()
	{
		
		// retrieve the node instructions from javascript
		// delta is the difference in position (1 = next node, -1 = previous node)
		
		$node = intval($this->params['form']['node']);
		$delta = intval($this->params['form']['delta']);
		
		if ($delta > 0) {
			$this->Category->movedown($node, abs($delta));
		} elseif ($delta < 0) {
			$this->Category->moveup($node, abs($delta));
		}
		
		// send success response
		exit('1');
		
	}
	
	function reparent(){
		
		$node = intval($this->params['form']['node']);
		$parent = intval($this->params['form']['parent']);
		$position = intval($this->params['form']['position']);
		
		// save the Category node with the new parent id
		// this will move the Category node to the bottom of the parent list
		
		$this->Category->id = $node;
		$this->Category->saveField('parent_id', $parent);
		
		// If position == 0, then we move it straight to the top
		// otherwise we calculate the distance to move ($delta).
		// We have to check if $delta > 0 before moving due to a bug
		// in the tree behaviour (https://trac.cakephp.org/ticket/4037)
		
		if ($position == 0) {
			$this->Category->moveup($node, true);
		} else {
			$count = $this->Category->childcount($parent, true);
			$delta = $count-$position-1;
			if ($delta > 0) {
				$this->Category->moveup($node, $delta);
			}
		}
		
		// send success response
		exit('1');
		
	}
}
?>