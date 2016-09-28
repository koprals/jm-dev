<?php
class MenuController extends AppController
{
	var $name	=	"Menu";
	var $uses	=	array('CmsMenu');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','cms_menu');
		$this->layout	=	"new";
	}
	
	function Index()
	{
		$this->set('child_code','list_menu');
		$this->Session->delete('SearchCmsMenu');
		$this->Session->delete('CmsMenu.cond_search');
	}
	
	function ListMenu()
	{
		$this->layout	=	"ajax";
		$viewpage				=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		
		$this->CmsMenu->bindModel(
                array('belongsTo' => array(
						'Parent'	=>	array(
							'className' 	=> 'CmsMenu',
							'foreignKey' 	=> 'parent_id'
						)
			)), false
        );
		
		//DEFINE FILTERING
		$cond_search	=	array();
		
		
			
		if(!empty($this->data))
		{
			if(!empty($this->data['Search']['parent_id']))
			{
				$list = $this->CmsMenu->children($this->data['Search']['parent_id'], true, array('CmsMenu.id'));
				$cat_arr = array();
				foreach ($list as $list)
				{
					$cat_arr[] = $list['CmsMenu']['id'];
				}
				$cat_arr[] 						=	$this->data['Search']['parent_id'];
				$cond_search["CmsMenu.id"]		=	$cat_arr;
			}
		
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["CmsMenu.id"]		=	$this->data['Search']['id'];
				$this->Session->write("SearchCmsMenu.id",$this->data['Search']['id']);
			}
			if(!empty($this->data['Search']['code']))
			{
				$cond_search["CmsMenu.code LIKE "]		=	"%".$this->data['Search']['code']."%";
				$this->Session->write("SearchCmsMenu.code",$this->data['Search']['code']);
			}
			if(!empty($this->data['Search']['name']))
			{
				$cond_search["CmsMenu.name LIKE "]		=	"%".$this->data['Search']['name']."%";
				$this->Session->write("SearchCmsMenu.name",$this->data['Search']['name']);
			}
			if(!empty($this->data['Search']['parent']))
			{
				$cond_search["Parent.name LIKE "]		=	"%".$this->data['Search']['parent']."%";
				$this->Session->write("SearchCmsMenu.parent",$this->data['Search']['parent']);
			}
			if(!empty($this->data['Search']['link']))
			{
				$cond_search["CmsMenu.url LIKE "]		=	"%".$this->data['Search']['link']."%";
				$this->Session->write("SearchCmsMenu.link",$this->data['Search']['link']);
			}
			if(!empty($this->data['Search']['target']))
			{
				$cond_search["CmsMenu.target LIKE "]		=	"%".$this->data['Search']['target']."%";
				$this->Session->write("SearchCmsMenu.target",$this->data['Search']['target']);
			}
			$this->Session->write("CmsMenu.cond_search",$cond_search);
		}
		
		
		//DELETE SESSION
		if($this->data['Search']['reset']=="1")
		{
			$this->Session->delete('SearchCmsMenu');
			$this->Session->delete('CmsMenu.cond_search');
			unset($this->data);
		}
		//SET VALUE
		$this->set("id",$this->Session->read("SearchCmsMenu.id"));
		$this->set("code",$this->Session->read("SearchCmsMenu.code"));
		$this->set("name",$this->Session->read("SearchCmsMenu.name"));
		$this->set("parent",$this->Session->read("SearchCmsMenu.parent"));
		$this->set("link",$this->Session->read("SearchCmsMenu.link"));
		$this->set("target",$this->Session->read("SearchCmsMenu.target"));
		
		
		$filter_paginate	=	array('CmsMenu.parent_id IS NOT NULL','CmsMenu.status' => 1);
		$this->paginate	=	array(
			'CmsMenu'	=>	array(
				'limit'	=> $viewpage,
				'order'	=> array('CmsMenu.lft ASC')
			)
		);
		
		$ses_cond		= $this->Session->read("CmsMenu.cond_search");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('CmsMenu',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['CmsMenu']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['CmsMenu']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
		
	}
	
	function SelectAll()
	{
		$this->layout		=	"json";
		$filter_paginate	=	array('CmsMenu.parent_id IS NOT NULL','CmsMenu.status' => 1);
		$ses_cond			= 	$this->Session->read("CmsMenu.cond_search");
		$cond_search		= 	isset($ses_cond) ? $ses_cond : array();
		$data				= 	$this->CmsMenu->find('all',array(
									'conditions'	=>	array_merge($filter_paginate,$cond_search),
									'fields'		=>	array('CmsMenu.id')
								));
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	function Add($id="",$direction="")
	{
		$this->set('child_code','list_menu');
		
		//SET DEFAULT VALUE	
		$cat_id		=	"";
		$cat_name	=	"";
		$url		=	"";
		$code		=	"";
		
		//UPDATE CmsMenu IF NOT EMPTY ID
		if(!empty($direction) && !empty($id))
		{
			if($direction=="up")
			{
				$this->CmsMenu->moveUp($id, 1);
			}
			elseif($direction=="down")
			{
				$this->CmsMenu->moveDown($id, 1);
			}
			$this->redirect(array("controller"=>"Menu","action"=>"Add",$id));
		}
		
		//CHECK DATA FIRST
		$find	=	$this->CmsMenu->find('first');
		if($find==false)
		{
			$data['CmsMenu']['name']	=	"TOP";
			$data['CmsMenu']['status']	=	"1";
			$this->CmsMenu->save($data);
		}
		
		//CHEK IF ISSET id
		if(!empty($id))
		{
			$find	=	$this->CmsMenu->findById($id);
			if($find)
			{
				$cat_id		=	$find['CmsMenu']['parent_id'];
				$cat_name	=	$find['CmsMenu']['name'];
				$url		=	$find['CmsMenu']['url'];
				$code		=	$find['CmsMenu']['code'];
			}
			
		}
		$data		=	$this->CmsMenu->generatetreelist(array('CmsMenu.status'=>'1'),null, null, $spacer = '----');
		$this->set(compact("data","cat_id","id","cat_name","url","code"));
	}
	
	function ProcessAddMenus()
	{
		$this->layout	=	"json";
		
		//SET DEFAULT VALUE
		$err			=	array();
		
		if(!empty($this->data))
		{
			$this->CmsMenu->set($this->data);
			if($this->CmsMenu->validates())
			{
				$this->data['CmsMenu']['name']	=	trim($this->data['CmsMenu']['name']);
				$this->data['CmsMenu']['url']	=	trim($this->data['CmsMenu']['url']);
				$this->CmsMenu->save($this->data,false);
				$out	=	array("status"	=>	true,"error"=>	"Data telah tersimpan.");
			}
			else
			{
				$error	=	$this->CmsMenu->invalidFields();
				foreach($error as $k=>$v)
				{
					$err[]	=	array("key"	=>	$k,"value"	=>	$v);
				}
				$out	=	array("status"	=>	false,"error"=>	$err);
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function Delete()
	{
		$this->layout		=	"json";
		$id					=	$this->data['CmsMenu']['id'];
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"Maaf terdapat beberapa data yang tidak ter delete :\n";
		$data_delete		=	"Data telah di delete :\n";
		$item				=	"";
		$tr_id				=	array();
		
		
		foreach($id as $menu_id)
		{
			$detail				=	$this->CmsMenu->findById($menu_id);
			$this->CmsMenu->id	=	$menu_id;
			$delete				=	$this->CmsMenu->delete();
			$count++;
			if($delete==false)
			{
				$err++;
			}
			else
			{
				$tr_id[]	=	$id;
			}
			$item	.= $count.". Menu : ".$detail['CmsMenu']['name']."\n";
		}
		$message			=	($err > 0 ) ? $data_not_delete.$item : $data_delete.$item;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function getnodes()
	{
		// retrieve the node id that Ext JS posts via ajax
		$this->layout		=	"ajax";
		
		$parent 			=	(intval($this->params['form']['node'])==0) ? $this->CmsMenu->GetTop() : $this->params['form']['node'];
		
		
		// find all the nodes underneath the parent node defined above
		// the second parameter (true) means we only want direct children
		$nodes = $this->CmsMenu->children($parent, true);
		
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
			$this->CmsMenu->movedown($node, abs($delta));
		} elseif ($delta < 0) {
			$this->CmsMenu->moveup($node, abs($delta));
		}
		
		// send success response
		exit('1');
		
	}
	
	function reparent(){
		
		$node = intval($this->params['form']['node']);
		$parent = intval($this->params['form']['parent']);
		$position = intval($this->params['form']['position']);
		
		// save the CmsMenu node with the new parent id
		// this will move the CmsMenu node to the bottom of the parent list
		
		$this->CmsMenu->id = $node;
		$this->CmsMenu->saveField('parent_id', $parent);
		
		// If position == 0, then we move it straight to the top
		// otherwise we calculate the distance to move ($delta).
		// We have to check if $delta > 0 before moving due to a bug
		// in the tree behaviour (https://trac.cakephp.org/ticket/4037)
		
		if ($position == 0) {
			$this->CmsMenu->moveup($node, true);
		} else {
			$count = $this->CmsMenu->childcount($parent, true);
			$delta = $count-$position-1;
			if ($delta > 0) {
				$this->CmsMenu->moveup($node, $delta);
			}
		}
		
		// send success response
		exit('1');
		
	} 
}
?>