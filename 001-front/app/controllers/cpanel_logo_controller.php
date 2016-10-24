<?php
class CpanelLogoController extends AppController
{
	var $name	=	"CpanelLogo";
	var $uses	=	array('Company');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	
	function Index()
	{
		$this->Session->write('back_url',$this->settings['site_url'].'Cpanel/UploadLogo');
		$this->set("active_code","upload_logo");
		
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
	}
	
	function ProcessUpload()
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		if(empty($this->user_id))
		{
			$out				=	array("status"=>false,"error"=>"Maaf login anda telah expired, harap anda login kembali.");
			$this->set("data",$out);
			$this->render(false);
			return;
		}
		
		
		if($this->data)
		{
			//LOAD MODEL
			$this->Company->set($this->data);
			$this->Company->ValidateUpload();
			
			if($this->Company->validates())
			{
				//SAVE IMAGES
				if(!empty($this->data['Company']['photo']['name']))
				{
					$com_id				=	$this->profile['Company']['id'];
					$cnt_user_dir		=	$this->settings['path_content']."Company/";
					$cnt_userid_dir		=	$cnt_user_dir.$com_id."/";
					$this->General->RmDir($cnt_userid_dir);
					$info				=	pathinfo($this->data['Company']['photo']['name']);
					$destination		=	$cnt_userid_dir.$com_id.".".$info['extension'];
					
					if(!is_dir($cnt_user_dir)) mkdir($cnt_user_dir,0777);
					if(!is_dir($cnt_userid_dir)) mkdir($cnt_userid_dir,0777);
					copy($this->data['Company']['photo']['tmp_name'],$destination);
					
				}
				$out	=	array("status"=>true,"error"=>$this->settings['site_url'].'Cpanel/UploadLogo');
			}
			else
			{
				$error	=	$this->Company->InvalidFields();
				$out	=	array("status"=>false,"error"=>$this->General->getArrayFirstIndex($error));
			}
		}
		$this->set("data",$out);
		$this->render(false);
	}
	
	function UploadTmp()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$err			=	array();
		
		if(!empty($this->data))
		{
			$this->loadModel('Company');
			
			$this->Company->set($this->data);
			$this->Company->ValidatePhoto();
			
			if($this->Company->validates())
			{
				//GET VITUAL ID FOR USER
				$rand			=	$this->Action->GetRandomUser();
				
				//DEFINE NEEDED VARIABLE
				$ROOT			=	$this->settings['path_content'];
				$info 			= 	pathinfo($this->data['Company']["photo"]['name']);
				$type			= 	strtolower($info['extension']);
				
				//GENERATE FOLDER
				$tmpuser		=	$ROOT."RandomUser/";
				if(!is_dir($tmpuser)) mkdir($tmpuser,0777);
				
				$tmpuser_id		=	$tmpuser.$rand."/";
				if(is_dir($tmpuser_id)) $this->General->RmDir($tmpuser_id);
				if(!is_dir($tmpuser_id)) mkdir($tmpuser_id,0777);
				
				$targetFile		=	$tmpuser_id.$rand.".".$type;
				
				//UPLOAD FILES
				$tempFile 				= 	$this->data['Company']["photo"]['tmp_name'];
				$upload					=	move_uploaded_file($tempFile,$targetFile);
				$showimg				=	$this->settings['showimages_url']."?code=".$rand."&prefix=_prevthumb&content=RandomUser&w=120&h=120";
				
				$out					=	array("status" => true,"error" =>$rand,"name_file"=>$this->data['Company']["photo"]['name']);
			}
			else
			{
				$error	=	$this->Company->invalidFields();
				foreach($error as $k=>$v)
				{
					$err =	$v;
					break;
				}
				$out					=	array("status" => false,"error" =>$err,"name_file"=>$this->data['Company']["photo"]['name']);
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
}
?>