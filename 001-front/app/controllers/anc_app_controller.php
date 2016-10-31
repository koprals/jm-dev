<?php
class AncAppController extends AppController
{
	var $name		=	"AncApp";
	var $uses		=	null;
	var $helpers	=	array("Time","Number");
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"news_app_layout";
	}
	
	function Index()
	{
		$this->loadModel("AncCategory");
		$menu	=	$this->AncCategory->find("all",array(
						"conditions"	=>	array(
							"AncCategory.status"	=>	1
						),
						"order"	=>	array("AncCategory.id ASC")
					));
					
		$this->set(compact("menu"));
	}
	
	function ListSubcategory($category_id)
	{
		$this->loadModel("AncSubcategory");
		$this->loadModel("AncCategory");
		
		$fCategory	=	$this->AncCategory->find("first",array(
							"conditions"	=>	array(
								"AncCategory.id"	=>	$category_id
							),
							"fields"	=>	array("name")
						));
		
		$category_name	=	$fCategory["AncCategory"]["name"];
		$menu			=	$this->AncSubcategory->find("all",array(
								"conditions"		=>	array(
									"AncSubcategory.status"			=>	1,
									"AncSubcategory.anc_category_id"	=>	$category_id
								),
								"order"	=>	array("AncSubcategory.id DESC")
							));
					
		$this->set(compact("menu","category_id","category_name"));
	}
	
	
	function AddSubcategory($category_id)
	{
		App::import('Sanitize');
		$this->loadModel("AncSubcategory");
		$this->loadModel("AncCategory");
		$this->loadModel("AncLastUpdate");
		$this->loadModel("AncFiles");
		
		$fCategory	=	$this->AncCategory->find("first",array(
							"conditions"	=>	array(
								"AncCategory.id"	=>	$category_id
							),
							"fields"	=>	array("name")
						));
		$category_name	=	$fCategory["AncCategory"]["name"];
		
		$webroot	=	WWW_ROOT. "anc_app" .DS;
		
		if(!is_dir($webroot))
			mkdir($webroot,0777);
		
		if(!empty($this->data))
		{
			$this->data["AncSubcategory"]["anc_category_id"]	=	$category_id;
			$this->data["AncSubcategory"]["description"]		=	Sanitize::html($this->data["AncSubcategory"]["description"]);
			
			$save												=	$this->AncSubcategory->save($this->data);
			$subcategory_id		=	$this->AncSubcategory->getLastInsertId();
			$array_files		=	$this->data["AncSubcategory"]["files"];
			
			foreach($array_files as $file)
			{
				//UPLOAD
				$extentions_allowed	=	array("jpg","gif","png","doc","docx","xls","xlsx","ppt","pdf");
				$ext				=	strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
				
				if(in_array($ext,$extentions_allowed))
				{
					$file_name			=	md5($subcategory_id.time().$file['name']).".".$ext;
					$upload				=	move_uploaded_file($file['tmp_name'],$webroot.$file_name);
					
					
					//UPDATE
					$this->AncFiles->create();
					$save				=	$this->AncFiles->saveAll(
												array(
													"anc_subcategory_id"	=>	$subcategory_id	,
													"filename"				=>	$file['name'],
													"path_location"			=>	$webroot.$file_name
												),
												array(
													"validate"	=>	false
												)
											);
				}
				$this->AncLastUpdate->updateAll(array("modified"=>"unix_timestamp()","subcategory_id"=>"'".$subcategory_id."'"),array("id"=>1));
			}
			
			if($this->data["AncSubcategory"]["send"]=="1") $this->__AncSendNotif($subcategory_id);
			$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListSubcategory",$category_id));
		}
		$this->set(compact("category_id","category_name"));	
	}
	
	function EditSubCategory($subcategory_id)
	{
		App::import('Sanitize');
		$this->loadModel("AncSubcategory");
		$this->loadModel("AncCategory");
		$this->loadModel("AncLastUpdate");
		$this->loadModel("AncFiles");
		
		$fSubcategory	=	$this->AncSubcategory->findById($subcategory_id);
		$list_category	=	$this->AncCategory->find("list");
		
		if($fSubcategory)
		{
			if(!empty($this->data))
			{
				$this->data["AncSubcategory"]["description"]		=	Sanitize::html($this->data["AncSubcategory"]["description"]);
				
				$this->AncSubcategory->set($this->data);
				$this->AncSubcategory->save($this->data);
				
				//DELETE DATA
				$delete_file		=	$this->data["AncSubcategory"]["delete"];
				if(!empty($delete_file))
				{
					foreach($delete_file as $delete_file)
					{
						$fAncFile		=	$this->AncFiles->findById($delete_file);
						$exists_file	=	$fAncFile['AncFiles']['path_location'];
						if(file_exists($exists_file)) @unlink($exists_file);
						$delete			=	$this->AncFiles->delete($delete_file);
					}
				}
				
				//UPLOAD FILES
				$array_files		=	$this->data["AncSubcategory"]["files"];
				if(!empty($array_files))
				{
					foreach($array_files as $k => $file)
					{
						//UPLOAD
						$extentions_allowed	=	array("jpg","gif","png","doc","docx","xls","xlsx","ppt","pdf");
						$ext				=	strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
						
						if(in_array($ext,$extentions_allowed) && !empty($file['name']))
						{
							$webroot		=	WWW_ROOT. "anc_app" .DS;
							$fAncFile		=	$this->AncFiles->findById($k);
							$exists_file	=	$fAncFile['AncFiles']['path_location'];
							if(file_exists($exists_file)) @unlink($exists_file);
							
							
							//UPLOAD
							$ext			=	pathinfo($file['name'],PATHINFO_EXTENSION);
							$file_name		=	md5($subcategory_id.time().$file['name']).".".$ext;
							$upload			=	move_uploaded_file($file['tmp_name'],$webroot.$file_name);
							
							//UPDATE
							$update			=	$this->AncFiles->updateAll(
													array(
														"filename"				=>	"'".$file['name']."'",
														"path_location"			=>	"'".$webroot.$file_name."'"
													),
													array(
														"AncFiles.id"	=>	$k
													)
												);
							
						}
					}
				}
				
				//UPLOAD ADD FILES
				$array_add_files		=	$this->data["AncSubcategory"]["addfiles"];
				if(!empty($array_add_files))
				{
					foreach($array_add_files as $k => $file)
					{
						//UPLOAD
						$extentions_allowed	=	array("jpg","gif","png","doc","docx","xls","xlsx","ppt","pdf");
						$ext				=	strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
						if(in_array($ext,$extentions_allowed) && !empty($file['name']))
						{
							//UPLOAD
							$webroot		=	WWW_ROOT. "anc_app" .DS;
							$ext			=	pathinfo($file['name'],PATHINFO_EXTENSION);
							$file_name		=	md5($subcategory_id.time().$file['name']).".".$ext;
							$upload			=	move_uploaded_file($file['tmp_name'],$webroot.$file_name);
							
							//ADD FILE TO DB
							$this->AncFiles->create();
							$save			=	$this->AncFiles->saveAll(
													array(
														"anc_subcategory_id"	=>	$subcategory_id,
														"filename"				=>	$file['name'],
														"path_location"			=>	$webroot.$file_name
													),
													array(
														"validate"				=>	false
													)
												);
						}
					}
				}
				
				$this->AncLastUpdate->updateAll(array("modified"=>"unix_timestamp()","subcategory_id"=>"'".$subcategory_id."'"),array("id"=>1));
				
				if($this->data["AncSubcategory"]["send"]=="1") $this->__AncSendNotif($subcategory_id);
				
				$this->redirect(array("controller"=>$this->params["controller"],"action"=>"ListSubcategory",$fSubcategory["AncSubcategory"]["anc_category_id"]));
			}
			else
			{
				$this->data	=	$fSubcategory;
				$this->data['AncSubcategory']['description']	=	html_entity_decode($this->data['AncSubcategory']['description']);
				$this->AncSubcategory->set($this->data);
			}
			$this->set(compact("fSubcategory"));
		}
		$this->set(compact("list_category"));
	}
	
	function Preview($subcategory_id)
	{
		$this->loadModel("AncSubcategory");
		$this->loadModel("AncFiles");
		$fAncSubcategory			=	$this->AncSubcategory->findById($subcategory_id);
		
		if(!empty($fAncSubcategory))
		{
			$this->set(compact("fAncSubcategory"));
		}
	}
	
	function DownloadFile($file_id)
	{
		
		if(empty($file_id)) exit;
		$this->loadModel("AncFiles");
		
		//CHEK DATA
		$fData	=	$this->AncFiles->findById($file_id);
		if(!file_exists($fData['AncFiles']['path_location'])) exit;
		
		//DOWNLOAD FILE
		$this->view = 'Media';
		$info			=	pathinfo($fData['AncFiles']['path_location']);
		$param_name		=	explode(".",$fData['AncFiles']['filename']);
		$params 		=	array(
			'id' 		=> $info["basename"],
			'name' 		=> $param_name[0],
			'download' 	=> true,
			'extension' => $info['extension'],
			'path' 		=> $info["dirname"]."/"
		);
		
		
		$this->set($params);
	}
	private function __readfile_chunked($filename, $retbytes = TRUE) {
		$buffer = '';
		$cnt =0;
		
		$handle = fopen($filename, 'rb');
		if ($handle === false) {
			return false;
		}
		while (!feof($handle)) {
			$buffer = fread($handle, CHUNK_SIZE);
			echo $buffer;
			ob_flush();
			flush();
			if ($retbytes) {
			$cnt += strlen($buffer);
			}
		}
		$status = fclose($handle);
		if ($retbytes && $status) {
			return $cnt; // return num. bytes delivered like readfile() does.
		}
		return $status;
	}
	function __AncSendNotif($subcategory_id,$alertmessage="New Update!")
	{
		$this->loadModel("AncUser");
		$status								=	false;
		$message							=	"Validate Failed";
		$code								=	"03";
		$data								=	array();
		$data["AncUser"]["message"]			=	$alertmessage;	
		$fUser								=	$this->AncUser->find("all");
		if(!empty($data) && !empty($fUser))
		{
			$reg_ids	=	array();
			foreach($fUser as $fUser)
			{
				$reg_ids[]	=	$fUser["AncUser"]["id"];
			}
			
			$url = 'https://android.googleapis.com/gcm/send';
			$fields =	array(
				'registration_ids' => $reg_ids,
				'data' => array(
					"price"				=>	$alertmessage,
					"subcategory_id"	=>	$subcategory_id
				),
			);
	 
			$headers = array(
				'Authorization: key=' . "AIzaSyA0xXPyLhBYr9iLwYhVh-WbPcXqZqwe11I",
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
			//echo $result;
		}
	}
}
?>