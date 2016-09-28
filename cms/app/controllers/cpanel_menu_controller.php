<?php
class CpanelMenuController extends AppController
{
	var $name	=	"CpanelMenu";
	var $uses	=	array('CpanelMenu');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','cms_menu');
		$this->set('child_code','cpanel_menu');	
	}
	
	function Index()
	{
		$this->set('child_code','cpanel_menu');
		$this->Session->delete('SearchCpanelMenu');
		$this->Session->delete('CpanelMenu.cond_search');
	}
	
	function ListMenu()
	{
		$this->layout	=	"ajax";
		$viewpage				=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		
		$this->CpanelMenu->bindModel(
                array('belongsTo' => array(
						'Parent'	=>	array(
							'className' 	=> 'CpanelMenu',
							'foreignKey' 	=> 'parent_id'
						)
			)), false
        );
		
		//DEFINE FILTERING
		$cond_search	=	array();
		if(!empty($this->data))
		{
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["CpanelMenu.id"]		=	$this->data['Search']['id'];
				$this->Session->write("SearchCpanelMenu.id",$this->data['Search']['id']);
			}
			if(!empty($this->data['Search']['code']))
			{
				$cond_search["CpanelMenu.code LIKE "]		=	"%".$this->data['Search']['code']."%";
				$this->Session->write("SearchCpanelMenu.code",$this->data['Search']['code']);
			}
			if(!empty($this->data['Search']['name']))
			{
				$cond_search["CpanelMenu.name LIKE "]		=	"%".$this->data['Search']['name']."%";
				$this->Session->write("SearchCpanelMenu.name",$this->data['Search']['name']);
			}
			if(!empty($this->data['Search']['parent']))
			{
				$cond_search["Parent.name LIKE "]		=	"%".$this->data['Search']['parent']."%";
				$this->Session->write("SearchCpanelMenu.parent",$this->data['Search']['parent']);
			}
			if(!empty($this->data['Search']['link']))
			{
				$cond_search["CpanelMenu.url LIKE "]		=	"%".$this->data['Search']['link']."%";
				$this->Session->write("SearchCpanelMenu.link",$this->data['Search']['link']);
			}
			if(!empty($this->data['Search']['target']))
			{
				$cond_search["CpanelMenu.target LIKE "]		=	"%".$this->data['Search']['target']."%";
				$this->Session->write("SearchCpanelMenu.target",$this->data['Search']['target']);
			}
			$this->Session->write("CpanelMenu.cond_search",$cond_search);
		}
		
		//DELETE SESSION
		if($this->data['Search']['reset']=="1")
		{
			$this->Session->delete('SearchCpanelMenu');
			$this->Session->delete('CpanelMenu.cond_search');
			unset($this->data);
		}
		//SET VALUE
		$this->set("id",$this->Session->read("SearchCpanelMenu.id"));
		$this->set("code",$this->Session->read("SearchCpanelMenu.code"));
		$this->set("name",$this->Session->read("SearchCpanelMenu.name"));
		$this->set("parent",$this->Session->read("SearchCpanelMenu.parent"));
		$this->set("link",$this->Session->read("SearchCpanelMenu.link"));
		$this->set("target",$this->Session->read("SearchCpanelMenu.target"));

		$filter_paginate	=	array('CpanelMenu.parent_id IS NOT NULL','CpanelMenu.status' => 1);
		$this->paginate	=	array(
			'CpanelMenu'	=>	array(
				'limit'	=> $viewpage,
				'order'	=> array('CpanelMenu.lft ASC')
			)
		);
		$ses_cond		= $this->Session->read("CpanelMenu.cond_search");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('CpanelMenu',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['CpanelMenu']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['CpanelMenu']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
	}
	
	function SelectAll()
	{
		$this->layout		=	"json";
		$filter_paginate	=	array('CpanelMenu.parent_id IS NOT NULL','CpanelMenu.status' => 1);
		$ses_cond		= $this->Session->read("CpanelMenu.cond_search");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data				= 	$this->CpanelMenu->find('all',array(
									'conditions'	=>	array_merge($filter_paginate,$cond_search),
									'fields'		=>	array('CpanelMenu.id')
								));
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	function Add($id="",$direction="")
	{
		
		//SET DEFAULT VALUE	
		$cat_id		=	"";
		$cat_name	=	"";
		$url		=	"";
		$code		=	"";
		$merchant_only	=	0;
		
		//UPDATE CpanelMenu IF NOT EMPTY ID
		if(!empty($direction) && !empty($id))
		{
			if($direction=="up")
			{
				$this->CpanelMenu->moveUp($id, 1);
			}
			elseif($direction=="down")
			{
				$this->CpanelMenu->moveDown($id, 1);
			}
			$this->redirect(array("controller"=>"Menu","action"=>"Add",$id));
		}
		
		//CHECK DATA FIRST
		$find	=	$this->CpanelMenu->find('first');
		if($find==false)
		{
			$data['CpanelMenu']['name']	=	"TOP";
			$data['CpanelMenu']['status']	=	"1";
			$this->CpanelMenu->save($data);
		}
		
		//CHEK IF ISSET id
		if(!empty($id))
		{
			$find	=	$this->CpanelMenu->findById($id);
			if($find)
			{
				$cat_id			=	$find['CpanelMenu']['parent_id'];
				$cat_name		=	$find['CpanelMenu']['name'];
				$url			=	$find['CpanelMenu']['url'];
				$code			=	$find['CpanelMenu']['code'];
				$merchant_only	=	$find['CpanelMenu']['merchant_only'];
			}
			
		}
		$data		=	$this->CpanelMenu->generatetreelist(array('CpanelMenu.status'=>'1'),null, null, $spacer = '----');
		$this->set(compact("data","cat_id","id","cat_name","url","code","merchant_only"));
	}
	
	function ProcessAddMenus()
	{
		$this->layout	=	"json";
		
		//SET DEFAULT VALUE
		$err			=	array();
		
		if(!empty($this->data))
		{
			$this->CpanelMenu->set($this->data);
			if($this->CpanelMenu->validates())
			{
				$this->data['CpanelMenu']['name']	=	trim($this->data['CpanelMenu']['name']);
				$this->data['CpanelMenu']['url']	=	trim($this->data['CpanelMenu']['url']);
				$this->CpanelMenu->save($this->data,false);
				$out	=	array("status"	=>	true,"error"=>	"Data telah tersimpan.");
			}
			else
			{
				$error	=	$this->CpanelMenu->invalidFields();
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
		$id					=	$this->data['CpanelMenu']['id'];
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"Maaf terdapat beberapa data yang tidak ter delete :\n";
		$data_delete		=	"Data telah di delete :\n";
		$item				=	"";
		$tr_id				=	array();
		
		
		foreach($id as $menu_id)
		{
			$detail				=	$this->CpanelMenu->findById($menu_id);
			$this->CpanelMenu->id	=	$menu_id;
			$delete				=	$this->CpanelMenu->delete();
			$count++;
			if($delete==false)
			{
				$err++;
			}
			else
			{
				$tr_id[]	=	$id;
			}
			$item	.= $count.". Menu : ".$detail['CpanelMenu']['name']."\n";
		}
		$message			=	($err > 0 ) ? $data_not_delete.$item : $data_delete.$item;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
}
?>