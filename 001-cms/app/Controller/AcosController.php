<?php
class AcosController extends AppController
{
	var $ControllerName		=	"Acos";
	var $ModelName			=	"MyAco";
	var $helpers			=	array("Text","Aimfox");
	var $uses				=	"MyAco";
	var $aco_id;
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set("ControllerName",$this->ControllerName);
		$this->set("ModelName",$this->ModelName);
		$this->set('lft_menu_category_id',"3");
		
		//CHECK PRIVILEGES
		$this->loadModel("MyAco");
		$find					=	$this->MyAco->find("first",array(
										"conditions"	=>	array(
											"LOWER(MyAco.alias)"	=>	strtolower("Module_Object")
										)
									));
		$this->aco_id			=	$find["MyAco"]["id"];
		$this->set("aco_id",$this->aco_id);
	}
	
	function Index($page=1,$viewpage=50)
	{
		if($this->access[$this->aco_id]["_read"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}
		$this->Session->delete("Search.".$this->ControllerName);
		$this->Session->delete('Search.'.$this->ControllerName.'Operand');
		$this->Session->delete('Search.'.$this->ControllerName.'ViewPage');
		$this->Session->delete('Search.'.$this->ControllerName.'Sort');
		$this->Session->delete('Search.'.$this->ControllerName.'Page');
		$this->Session->delete('Search.'.$this->ControllerName.'Conditions');
		$this->Session->delete('Search.'.$this->ControllerName.'parent_id');
		
		//DEFINE NEWS CATEGORY
		$parent_id_list	=	$this->{$this->ModelName}->generateTreeList();
		$this->set(compact("page","viewpage","parent_id_list"));
	}
	
	function ListItem()
	{
		$this->layout	=	"ajax";
		
		if($this->access[$this->aco_id]["_read"] != "1")
		{
			$data			=	array();
			$this->set(compact("data"));
			return;
		}
		
		$this->loadModel($this->ModelName);
		$this->{$this->ModelName}->BindDefault(false);
		$this->{$this->ModelName}->VirtualFieldActivated();
		
		//GET TOP ID
		if(!empty($this->params['named']['parent_id']))
			$this->Session->write('Search.'.$this->ControllerName.'parent_id',$this->params['named']['parent_id']);
			
		$parent_id				=	$this->Session->read('Search.'.$this->ControllerName.'parent_id');
		if(is_null($parent_id))
		{
			$top				=	$this->{$this->ModelName}->find("first",array(
										"conditions"	=>	array(
											"{$this->ModelName}.parent_id"	=>	NULL
										)
									));
			$parent_id			=	$top[$this->ModelName]["id"];
			$this->Session->write('Search.'.$this->ControllerName.'parent_id',$parent_id);
		}
		else
		{
			$parent_id			=	$this->Session->read('Search.'.$this->ControllerName.'parent_id');
		}
		
		$check					=	$this->{$this->ModelName}->find("first",array(
										"conditions"	=>	array(
											"{$this->ModelName}.id"	=>	$parent_id
										)
									));
		
								
		//DEFINE LAYOUT, LIMIT AND OPERAND
		$viewpage			=	empty($this->params['named']['limit']) ? 50 : $this->params['named']['limit'];
		$order				=	array("{$this->ModelName}.alias" => "ASC");
		$operand			=	"AND";
		
		//DEFINE SEARCH DATA
		if(!empty($this->request->data))
		{
			$cond_search	=	array();
			$operand		=	$this->request->data[$this->ModelName]['operator'];
			$this->Session->delete('Search.'.$this->ControllerName);
			
			if(!empty($this->request->data['Search']['id']))
			{
				$cond_search["{$this->ModelName}.id"]					=	$this->data['Search']['id'];
			}
			
			if(!empty($this->request->data['Search']['name']))
			{
				$cond_search["{$this->ModelName}.name LIKE "]			=	"%".$this->data['Search']['name']."%";
			}
			
			if(!empty($this->request->data['Search']['parent_id']))
			{
				$cond_search["{$this->ModelName}.parent_id"]			=	$this->data['Search']['parent_id'];
			}
			
			if($this->request->data["Search"]['reset']=="0")
			{
				$this->Session->write("Search.".$this->ControllerName,$cond_search);
				$this->Session->write('Search.'.$this->ControllerName.'Operand',$operand);
			}
		}
		
		$this->Session->write('Search.'.$this->ControllerName.'Viewpage',$viewpage);
		$this->Session->write('Search.'.$this->ControllerName.'Sort',(empty($this->params['named']['sort']) or !isset($this->params['named']['sort'])) ? $order : $this->params['named']['sort']." ".$this->params['named']['direction']);
		
		$cond_search		=	array();
		$filter_paginate	=	array(
									"{$this->ModelName}.parent_id"	=>	$parent_id
								);
		$this->paginate		=	array(
									"{$this->ModelName}"	=>	array(
										"order"				=>	$order,
										'limit'				=>	$viewpage
									)
								);
		
		$ses_cond			=	$this->Session->read("Search.".$this->ControllerName);
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$ses_operand		=	$this->Session->read("Search.".$this->ControllerName."Operand");
		$operand			=	isset($ses_operand) ? $ses_operand : "AND";
		$merge_cond			=	empty($cond_search) ? $filter_paginate : array_merge($filter_paginate,array($operand => $cond_search) );
		$data				=	$this->paginate("{$this->ModelName}",$merge_cond);
		
		$this->Session->write('Search.'.$this->ControllerName.'Conditions',$merge_cond);
		
		if(isset($this->params['named']['page']) && $this->params['named']['page'] > $this->params['paging'][$this->ModelName]['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging'][$this->ModelName]['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->Session->write('Search.'.$this->ControllerName.'Page',$page);
		$this->set(compact('data','page','viewpage','check',"parent_id"));
	}
	
	
	function Excel()
	{
		if($this->access[$this->aco_id]["_read"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}
		
		if(isset($_GET['debug']) && $_GET['debug'] == "1")
			$this->layout		=	"ajax";
		else
			$this->layout		=	"xls";
		
		$this->response->type(array('xls' => 'application/vnd.ms-excel'));
    	$this->response->type('xls');
	
		$this->{$this->ModelName}->BindDefault(false);
		$this->{$this->ModelName}->VirtualFieldActivated();
		
		$order				=	$this->Session->read("Search.".$this->ControllerName."Sort");
		$viewpage			=	$this->Session->read("Search.".$this->ControllerName."Viewpage");
		$page				=	$this->Session->read("Search.".$this->ControllerName."Page");
		$conditions			=	$this->Session->read("Search.".$this->ControllerName."Conditions");
		
		$this->paginate		=	array(
									"{$this->ModelName}"	=>	array(
										"order"				=>	$order,
										"limit"				=>	$viewpage,
										"conditions"		=>	$conditions,
										"page"				=>	$page
									)
								);
		$data				=	$this->paginate("{$this->ModelName}",$conditions);
		$title				=	$this->ModelName;
		$filename			=	$this->ModelName."_".date("dMY");
		$this->set(compact("data","title","page","viewpage","filename"));
	}
	
	function Add()
	{
		if($this->access[$this->aco_id]["_create"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}
		
		//DEFINE NEWS CATEGORY
		$parent_id_list	=	$this->{$this->ModelName}->generateTreeList(null,"{n}.{$this->ModelName}.id","{n}.{$this->ModelName}.alias");
		
		if(!empty($this->request->data))
		{
			$this->{$this->ModelName}->set($this->request->data);
			$this->{$this->ModelName}->ValidateAdd();
			if($this->{$this->ModelName}->validates())
			{
				$save	=	$this->{$this->ModelName}->save($this->request->data);
				$ID		=	$this->{$this->ModelName}->getLastInsertId();
				$this->redirect(array("action"=>"SuccessAdd",$ID));
			}//END IF VALIDATE
		}//END IF NOT EMPTY
		
		$this->set(compact("parent_id_list"));
	}
	
	function Edit($ID=NULL,$page=1,$viewpage=50)
	{
		if($this->access[$this->aco_id]["_update"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}
		
		$this->loadModel($this->ModelName);
		$this->{$this->ModelName}->VirtualFieldActivated();
		
		//DEFINE NEWS CATEGORY
		$parent_id_list	=	$this->{$this->ModelName}->generateTreeList(null,"{n}.{$this->ModelName}.id","{n}.{$this->ModelName}.alias");
		$detail 			=	$this->{$this->ModelName}->find('first', array(
									'conditions' => array(
										"{$this->ModelName}.id"		=>	$ID
									)
								));
								
		if(empty($detail))
		{
			$this->layout	=	"error";
			return;
		}
				
		if (empty($this->data))
		{
			$this->data = $detail;
		}
		else
		{
			$this->{$this->ModelName}->set($this->data);
			$this->{$this->ModelName}->ValidateEdit();
			
			if($this->{$this->ModelName}->validates())
			{
				$save		=	$this->{$this->ModelName}->save($this->data,false);
				$this->redirect(array('action' => 'SuccessEdit', $ID,$page,$viewpage));
			}
		}
		$this->set(compact("ID","detail","parent_id_list","page","viewpage"));
	}
	
	function View($ID=NULL)
	{
		if($this->access[$this->aco_id]["_read"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}
		
		$this->loadModel($this->ModelName);
		$this->{$this->ModelName}->BindImageBig(false);
		$this->{$this->ModelName}->VirtualFieldActivated();
		
		$detail = $this->{$this->ControllerName}->find('first', array(
			'conditions' => array(
				"{$this->ControllerName}.id"		=>	$ID
			)
		));
		if(empty($detail))
		{
			$this->layout	=	"ajax";
			$this->set(compact("ID","data"));
			$this->render("/errors/error404");
			return;
		}
		$this->set(compact("ID","detail"));
	}
	
	function ChangeStatus($ID=NULL,$status)
	{
		if($this->access[$this->aco_id]["_update"] != "1")
		{
			echo json_encode(array("data"=>array("message"=>"No privileges")));
			$this->autoRender	=	false;
			return;
		}
		
		$detail = $this->{$this->ModelName}->find('first', array(
			'conditions' => array(
				"{$this->ModelName}.id"		=>	$ID
			)
		));
		
		if(empty($detail))
		{
			$message	=	"Item not found.";
		}
		else
		{
			$data[$this->ModelName]["id"]		=	$ID;
			$data[$this->ModelName]["status"]	=	$status;
			$this->{$this->ModelName}->save($data);
			$message	=	"Data has updated.";
		}
		
		echo json_encode(array("data"=>array("message"=>$message)));
		$this->autoRender	=	false;
	}
	
	function Delete($ID=NULL)
	{
		if($this->access[$this->aco_id]["_delete"] != "1")
		{
			echo json_encode(array("data"=>array("message"=>"No privileges")));
			$this->autoRender	=	false;
			return;
		}
		
		$detail = $this->{$this->ModelName}->find('first', array(
			'conditions' => array(
				"{$this->ModelName}.id"		=>	$ID
			)
		));
		
		if(empty($detail))
		{
			$message	=	"Item not found.";
		}
		else
		{
			$this->loadModel("AroAco");
			$this->AroAco->deleteAll(array(
				"AroAco.aco_id"	=>	$ID
			));
			
			$this->{$this->ModelName}->removeFromTree($ID,true);
			$message	=	"Data has deleted.";
		}
		
		echo json_encode(array("data"=>array("message"=>$message)));
		$this->autoRender	=	false;
	}
	
	function SuccessAdd($ID=NULL)
	{
		$data = $this->{$this->ModelName}->find('first', array(
			'conditions' => array(
				"{$this->ModelName}.id"		=> $ID
			)
		));
		if(empty($data))
		{
			$this->layout	=	"ajax";
			$this->render("/errors/error404");
		}
		$this->set(compact("ID"));
	}
	
	function SuccessEdit($ID=NULL,$page=1,$viewpage=50)
	{
		$data = $this->{$this->ModelName}->find('first', array(
			'conditions' => array(
				"{$this->ModelName}.id" 		=> $ID
			)
		));
		
		if(empty($data))
		{
			$this->layout	=	"ajax";
			$this->render("/errors/error404");
		}
		$this->set(compact("ID","page","viewpage"));
	}
}
?>