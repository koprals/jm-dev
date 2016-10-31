<?php
class PhonelistAppController extends AppController
{
	var $name	=	"PhonelistApp";
	var $uses	=	null;
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"news_app_layout";
	}
	
	function Index()
	{
		$this->loadModel("PhonelistType");
		$menu	=	$this->PhonelistType->find("all",array(
						"conditions"	=>	array(
							"PhonelistType.status"	=>	1
						),
						"order"	=>	array("PhonelistType.name ASC")
					));
					
		$this->set(compact("menu"));
	}
	
	function ListCategory($phonelist_type_id)
	{
		$this->loadModel("PhonelistCategory");
		$this->loadModel("PhonelistType");
		$fType	=	$this->PhonelistType->find("first",array(
						"conditions"	=>	array(
							"PhonelistType.id"	=>	$phonelist_type_id
						),
						"fields"	=>	array("name")
					));				
		$type_name	=	$fType["PhonelistType"]["name"];
		
		$menu	=	$this->PhonelistCategory->find("all",array(
						"conditions"	=>	array(
							"PhonelistCategory.status"	=>	1,
							"PhonelistCategory.type_id"	=>	$phonelist_type_id
						),
						"order"	=>	array("PhonelistCategory.id DESC")
					));
					
		$this->set(compact("menu","phonelist_type_id","type_name"));
	}
	
	function AddCategory($phonelist_type_id)
	{
		$this->loadModel("PhonelistCategory");
		$this->loadModel("PhonelistType");
		$this->loadModel("PhonelistLastUpdated");
		$fType	=	$this->PhonelistType->find("first",array(
						"conditions"	=>	array(
							"PhonelistType.id"	=>	$phonelist_type_id
						),
						"fields"	=>	array("name")
					));				
		$type_name	=	$fType["PhonelistType"]["name"];
		if(!empty($this->data))
		{
			$this->data["PhonelistCategory"]["type_id"]	=	$phonelist_type_id;
			$this->PhonelistCategory->save($this->data);
			$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListCategory",$phonelist_type_id));
		}
		$this->set(compact("phonelist_type_id","type_name"));
		
		$this->PhonelistLastUpdated->updateAll(array("modified"=>"unix_timestamp()"),array("id"=>1));
	}
	
	function AddSubcategory($category_id)
	{
		$this->loadModel("PhonelistCategory");
		$this->loadModel("PhonelistSubcategory");
		$this->loadModel("PhonelistLastUpdated");
		$this->loadModel("PhonelistLocation");
		$locations	=	$this->PhonelistLocation->find("list");
		
		
		$fCategory	=	$this->PhonelistCategory->find("first",array(
							"conditions"	=>	array(
								"PhonelistCategory.id"	=>	$category_id
							),
							"fields"	=>	array("name","type_id")
						));
		$category_name	=	$fCategory["PhonelistCategory"]["name"];
		$webroot	=	WWW_ROOT. "phonelist_app" .DS;
		
		if(!is_dir($webroot))
			mkdir($webroot,0777);
		
		
		if(!empty($this->data))
		{
		
			$this->data["PhonelistSubcategory"]["type_id"]					=	$fCategory["PhonelistCategory"]["type_id"];
			$this->data["PhonelistSubcategory"]["phonelist_category_id"]	=	$category_id;
			$save															=	$this->PhonelistSubcategory->save($this->data);
			$subcategory_id		=	$this->PhonelistSubcategory->getLastInsertId();
			
			//UPLOAD
			$file				=	$this->data['PhonelistSubcategory']['photo'];
			$ext				=	pathinfo($file['name'],PATHINFO_EXTENSION);
			$file_name			=	md5($subcategory_id.time()).".".$ext;
				
			$upload				=	move_uploaded_file($file['tmp_name'],$webroot.$file_name);
		
			
			//UPDATE
			$update				=	$this->PhonelistSubcategory->updateAll(
										array(
											"images1"				=>	"'http://www.jualanmotor.com/phonelist_app/".$file_name."'",
										),
										array(
											"PhonelistSubcategory.id"	=>	$subcategory_id
										)
									);
			$this->PhonelistLastUpdated->updateAll(array("modified"=>"unix_timestamp()"),array("id"=>1));
			$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListSubcategory",$category_id));
		}
		$this->set(compact("category_id","category_name","locations"));	
	}
	
	function EditSubCategory($subcategory_id)
	{
		$this->loadModel("PhonelistSubcategory");
		$this->loadModel("PhonelistCategory");
		$this->loadModel("PhonelistLastUpdated");
		$this->loadModel("PhonelistLocation");
		$locations		=	$this->PhonelistLocation->find("list");
		
		$fSubcategory	=	$this->PhonelistSubcategory->findById($subcategory_id);
		$list_category	=	$this->PhonelistCategory->find("list");
		$file_name		=	pathinfo($fSubcategory["PhonelistSubcategory"]["images1"],PATHINFO_BASENAME);
		$webroot		=	WWW_ROOT. "phonelist_app" .DS;
		
		if($fSubcategory)
		{
			if(!empty($this->data))
			{
				$this->PhonelistSubcategory->set($this->data);
				$this->PhonelistSubcategory->save($this->data);
				
				if(!empty($this->data["PhonelistSubcategory"]["photo"]["name"]))
				{
					//UPLOAD
					@unlink($webroot.$file_name);
					$file				=	$this->data['PhonelistSubcategory']['photo'];
					$ext				=	pathinfo($file['name'],PATHINFO_EXTENSION);
					$file_name			=	md5($subcategory_id.time()).".".$ext;
					$upload				=	move_uploaded_file($file['tmp_name'],$webroot.$file_name);
					
					//UPDATE
					$update				=	$this->PhonelistSubcategory->updateAll(
												array(
													"images1"				=>	"'http://www.jualanmotor.com/phonelist_app/".$file_name."'",
												),
												array(
													"PhonelistSubcategory.id"	=>	$subcategory_id
												)
											);
				}
				
				$this->PhonelistLastUpdated->updateAll(array("modified"=>"unix_timestamp()"),array("id"=>1));
				$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListSubcategory",$fSubcategory["PhonelistSubcategory"]["phonelist_category_id"]));
			}
			else
			{
				$this->data	=	$fSubcategory;
				$this->PhonelistSubcategory->set($this->data);
			}
			$this->set(compact("fSubcategory"));
		}
		$this->set(compact("list_category","locations"));
	}
	
	function ListSubcategory($category_id)
	{
		$this->loadModel("PhonelistSubcategory");
		$this->loadModel("PhonelistCategory");
		
		$fCategory	=	$this->PhonelistCategory->find("first",array(
							"conditions"	=>	array(
								"PhonelistCategory.id"	=>	$category_id
							),
							"fields"	=>	array("name","type_id")
						));
		$type_id		=	$fCategory["PhonelistCategory"]["type_id"];
		$category_name	=	$fCategory["PhonelistCategory"]["name"];;
		$menu			=	$this->PhonelistSubcategory->find("all",array(
								"conditions"		=>	array(
									"PhonelistSubcategory.status"			=>	1,
									"PhonelistSubcategory.phonelist_category_id"	=>	$category_id
								),
								"order"	=>	array("PhonelistSubcategory.id DESC")
							));
					
		$this->set(compact("menu","category_id","type_id","category_name"));
	}
}
?>