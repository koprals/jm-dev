<?php
class NewsAppController extends AppController
{
	var $name	=	"NewsApp";
	var $uses	=	null;
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"news_app_layout";
	}
	
	function Index()
	{
		$this->loadModel("NewsType");
		$menu	=	$this->NewsType->find("all",array(
						"conditions"	=>	array(
							"NewsType.status"	=>	1
						),
						"order"	=>	array("NewsType.name ASC")
					));
					
		$this->set(compact("menu"));
	}
	
	function ListCategory($news_type_id)
	{
		$this->loadModel("NewsCategory");
		$this->loadModel("NewsType");
		$fType	=	$this->NewsType->find("first",array(
						"conditions"	=>	array(
							"NewsType.id"	=>	$news_type_id
						),
						"fields"	=>	array("name")
					));				
		$type_name	=	$fType["NewsType"]["name"];
		
		$menu	=	$this->NewsCategory->find("all",array(
						"conditions"	=>	array(
							"NewsCategory.status"	=>	1,
							"NewsCategory.type_id"	=>	$news_type_id
						),
						"order"	=>	array("NewsCategory.id DESC")
					));
					
		$this->set(compact("menu","news_type_id","type_name"));
	}
	
	function AddCategory($news_type_id)
	{
		$this->loadModel("NewsCategory");
		$this->loadModel("NewsType");
		$this->loadModel("NewsLastUpdated");
		$fType	=	$this->NewsType->find("first",array(
						"conditions"	=>	array(
							"NewsType.id"	=>	$news_type_id
						),
						"fields"	=>	array("name")
					));				
		$type_name	=	$fType["NewsType"]["name"];
		if(!empty($this->data))
		{
			$this->data["NewsCategory"]["type_id"]	=	$news_type_id;
			$this->NewsCategory->save($this->data);
			$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListCategory",$news_type_id));
		}
		$this->set(compact("news_type_id","type_name"));
		
		$this->NewsLastUpdated->updateAll(array("modified"=>"unix_timestamp()"),array("id"=>1));
	}
	
	function AddSubcategory($category_id)
	{
		$this->loadModel("NewsCategory");
		$this->loadModel("NewsSubcategory");
		$this->loadModel("NewsLastUpdated");
		$fCategory	=	$this->NewsCategory->find("first",array(
							"conditions"	=>	array(
								"NewsCategory.id"	=>	$category_id
							),
							"fields"	=>	array("name","type_id")
						));
		$category_name	=	$fCategory["NewsCategory"]["name"];
		
		$webroot	=	WWW_ROOT. "news_app" .DS;
		
		if(!is_dir($webroot))
			mkdir($webroot,0777);
		
		if(!empty($this->data))
		{
		
			$this->data["NewsSubcategory"]["type_id"]			=	$fCategory["NewsCategory"]["type_id"];
			$this->data["NewsSubcategory"]["news_category_id"]	=	$category_id;
			$save												=	$this->NewsSubcategory->save($this->data);
			$subcategory_id		=	$this->NewsSubcategory->getLastInsertId();
			
			//UPLOAD
			$file				=	$this->data['NewsSubcategory']['photo'];
			$ext				=	pathinfo($file['name'],PATHINFO_EXTENSION);
			$file_name			=	md5($subcategory_id.time()).".".$ext;
				
			$upload				=	move_uploaded_file($file['tmp_name'],$webroot.$file_name);
		
			
			//UPDATE
			$update				=	$this->NewsSubcategory->updateAll(
										array(
											"images1"				=>	"'http://www.jualanmotor.com/news_app/".$file_name."'",
										),
										array(
											"NewsSubcategory.id"	=>	$subcategory_id
										)
									);
			$this->NewsLastUpdated->updateAll(array("modified"=>"unix_timestamp()"),array("id"=>1));
			$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListSubcategory",$category_id));
		}
		$this->set(compact("category_id","category_name"));	
	}
	
	function EditSubCategory($subcategory_id)
	{
		$this->loadModel("NewsSubcategory");
		$this->loadModel("NewsCategory");
		$this->loadModel("NewsLastUpdated");
		
		$fSubcategory	=	$this->NewsSubcategory->findById($subcategory_id);
		$list_category	=	$this->NewsCategory->find("list");
		$file_name		=	pathinfo($fSubcategory["NewsSubcategory"]["images1"],PATHINFO_BASENAME);
		$webroot		=	WWW_ROOT. "news_app" .DS;
		
		if($fSubcategory)
		{
			if(!empty($this->data))
			{
				$this->NewsSubcategory->set($this->data);
				$this->NewsSubcategory->save($this->data);
				
				if(!empty($this->data["NewsSubcategory"]["photo"]["name"]))
				{
					//UPLOAD
					@unlink($webroot.$file_name);
					$file				=	$this->data['NewsSubcategory']['photo'];
					$ext				=	pathinfo($file['name'],PATHINFO_EXTENSION);
					$file_name			=	md5($subcategory_id.time()).".".$ext;
					$upload				=	move_uploaded_file($file['tmp_name'],$webroot.$file_name);
					
					//UPDATE
					$update				=	$this->NewsSubcategory->updateAll(
												array(
													"images1"				=>	"'http://www.jualanmotor.com/news_app/".$file_name."'",
												),
												array(
													"NewsSubcategory.id"	=>	$subcategory_id
												)
											);
				}
				
				$this->NewsLastUpdated->updateAll(array("modified"=>"unix_timestamp()"),array("id"=>1));
				$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListSubcategory",$fSubcategory["NewsSubcategory"]["news_category_id"]));
			}
			else
			{
				$this->data	=	$fSubcategory;
				$this->NewsSubcategory->set($this->data);
			}
			$this->set(compact("fSubcategory"));
		}
		$this->set(compact("list_category"));
	}
	
	function ListSubcategory($category_id)
	{
		$this->loadModel("NewsSubcategory");
		$this->loadModel("NewsCategory");
		
		$fCategory	=	$this->NewsCategory->find("first",array(
							"conditions"	=>	array(
								"NewsCategory.id"	=>	$category_id
							),
							"fields"	=>	array("name","type_id")
						));
		$type_id		=	$fCategory["NewsCategory"]["type_id"];
		$category_name	=	$fCategory["NewsCategory"]["name"];;
		$menu			=	$this->NewsSubcategory->find("all",array(
								"conditions"		=>	array(
									"NewsSubcategory.status"			=>	1,
									"NewsSubcategory.news_category_id"	=>	$category_id
								),
								"order"	=>	array("NewsSubcategory.id DESC")
							));
					
		$this->set(compact("menu","category_id","type_id","category_name"));
	}
}
?>