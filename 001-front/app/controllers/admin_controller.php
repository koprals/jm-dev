<?php
class AdminController extends AppController
{
	var $name			=	"Admin";
	var $uses			=	null;
	var $helpers		=	array("General");
	var $components		=	array("General");
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$is_admin		=	$this->profile['User']['admintype_id'];
		if($is_admin == "1")
		{
			$this->redirect(array("controller"=>"Home","action"=>"Index"));
		}
		$this->layout	=	"cpanel";
	}
	
	
	function ListNews()
	{
		$this->Session->write('back_url',$this->settings['site_url'].'Admin/ListNews');
		$this->set("active_code","list_news");
	}
	
	function ListItemNews()
	{
		$this->layout	=	"ajax";
		$this->loadModel("News");
		
		$this->News->VirtualFieldActivated();
		$this->paginate	=	array(
								"News"	=>	array(
									"limit"			=>	5,
									"order"			=>	array("News.id DESC"),
									"conditions"	=>	array(
															"News.status"	=>	1
														)
								)
							);
		$data				=	$this->paginate('News');
		$this->set(compact("data"));
	}
	
	function AddNews()
	{
		$this->set("active_code","list_news");
	}
	
	function PrcessAddNews()
	{
		App::import('Sanitize');
		$this->loadModel("News");
		$this->layout					=	"json";
		$out							=	array("status"=>false,"error"=>"");
		
		if(empty($this->user_id))
		{
			$out				=	array("status"=>true,"error"=>$this->settings['site_url'].'Users/Login');
			$this->set("data",$out);
			$this->render(false);
			return;
		}
		
		if(!empty($this->data))
		{
			$this->News->set($this->data);
			$this->News->InititateValidate();
			if($this->News->validates())
			{
				$this->data['News']['description']		=	Sanitize::html($this->data['News']['description']);
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Admin/AddNewsThanks');
				
				$this->News->create();
				$this->News->save($this->data,false);
				$news_id	=	$this->News->getLastInsertId();
				
				$ext			=	strtolower(pathinfo($this->data['News']['photo']['name'],PATHINFO_EXTENSION));
				$ROOT			=	$this->settings['path_content'];
				$path			=	$ROOT."News/";
				$path_news		=	$path.$news_id."/";
				
				
				$file_name		=	$news_id.".".$ext;
				$target_file	=	$path_news.$file_name;
				if(!is_dir($path)) mkdir($path,0777);
				if(!is_dir($path_news)) mkdir($path_news,0777);
				$tempFile 				= 	$this->data['News']["photo"]['tmp_name'];
				$upload					=	move_uploaded_file($tempFile,$target_file);
				@unlink($this->settings['path_web'].'app/tmp/cache/views/element__news');
			}
			else
			{
				$error	=	$this->News->InvalidFields();
				foreach($this->data['News'] as $k=>$v)
				{
					if(array_key_exists($k,$error))
					{
						$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);
					}
					elseif( empty($v) OR (is_array($v) AND isset($v["name"]) AND empty($v["name"])) )
					{
						$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
					}
					else
					{
						$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
					}
				}
				$out	=	array("status"=>false,"error"=>$err);
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function EditNews($news_id)
	{
		$this->loadModel("News");
		$data	=	$this->News->findById($news_id);
		
		if(!empty($data))
		{
			$this->data	=	$data;
		}
		$this->set(compact("data"));
	}
	
	function PrcessEditNews()
	{
		App::import('Sanitize');
		$this->loadModel("News");
		$this->layout					=	"json";
		$out							=	array("status"=>false,"error"=>"");
		
		if(empty($this->user_id))
		{
			$out				=	array("status"=>true,"error"=>$this->settings['site_url'].'Users/Login');
			$this->set("data",$out);
			$this->render(false);
			return;
		}
		
		if(!empty($this->data))
		{
			$this->News->set($this->data);
			$this->News->InititateValidateEdit();
			if($this->News->validates())
			{
				$this->data['News']['description']		=	Sanitize::html($this->data['News']['description']);
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Admin/EditNewsThanks');
				
				$this->News->create();
				$this->News->save($this->data,false);
				$news_id	=	$this->data["News"]["id"];
				
				if(!empty($this->data['News']['photo']['name']))
				{
					$ext			=	strtolower(pathinfo($this->data['News']['photo']['name'],PATHINFO_EXTENSION));
					$ROOT			=	$this->settings['path_content'];
					$path			=	$ROOT."News/";
					$path_news		=	$path.$news_id."/";
					$file_name		=	$news_id.".".$ext;
					$target_file	=	$path_news.$file_name;
					
					$this->General->RmDir($path_news);
					if(!is_dir($path)) mkdir($path,0777);
					if(!is_dir($path_news)) mkdir($path_news,0777);
					
					$tempFile 				= 	$this->data['News']["photo"]['tmp_name'];
					$upload					=	move_uploaded_file($tempFile,$target_file);
				}
				@unlink($this->settings['path_web'].'app/tmp/cache/views/element__news');
			}
			else
			{
				$error	=	$this->News->InvalidFields();
				foreach($this->data['News'] as $k=>$v)
				{
					if(array_key_exists($k,$error))
					{
						$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);
					}
					elseif( empty($v) OR (is_array($v) AND isset($v["name"]) AND empty($v["name"])) )
					{
						$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
					}
					else
					{
						$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
					}
				}
				$out	=	array("status"=>false,"error"=>$err);
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function AddNewsThanks()
	{
	}
	function EditNewsThanks()
	{
	}
	function DeleteNews($news_id)
	{
		$this->loadModel("News");
		$delete	=	$this->News->deleteAll(array("News.id"=>$news_id));
		
		$ROOT			=	$this->settings['path_content'];
		$path			=	$ROOT."News/";
		$path_news		=	$path.$news_id."/";
		$this->General->RmDir($path_news);
		@unlink($this->settings['path_web'].'app/tmp/cache/views/element__news');
		$this->autoRender	=	false;
	}
}
?>