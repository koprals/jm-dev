<?php
class CpanelPhotoController extends AppController
{
	var $name	=	"CpanelPhoto";
	var $uses	=	array('User');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	
	function Index()
	{
		$this->Session->write('back_url',$this->settings['site_url'].'Cpanel/CpanelPhoto');
		$this->set("active_code","upload_photo");
		
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
			$this->data['User']['max_photo_upload']	=	$this->settings['max_photo_upload'];
			$this->User->set($this->data);
			$this->User->ValidateUpload();
			
			if($this->User->validates())
			{
				//SAVE IMAGES
				if(!empty($this->data['User']['photo']['name']))
				{
					$user_id			=	$this->user_id;
					$cnt_user_dir		=	$this->settings['path_content']."User/";
					$cnt_userid_dir		=	$cnt_user_dir.$user_id."/";
					$this->General->RmDir($cnt_userid_dir);
					$info				=	pathinfo($this->data['User']['photo']['name']);
					$destination		=	$cnt_userid_dir.$user_id.".".$info['extension'];
					if(!is_dir($cnt_user_dir)) mkdir($cnt_user_dir,0777);
					if(!is_dir($cnt_userid_dir)) mkdir($cnt_userid_dir,0777);
					copy($this->data['User']['photo']['tmp_name'],$destination);
					
				}
				$out	=	array("status"=>true,"error"=>$this->settings['site_url'].'Cpanel/UploadPhoto');
			}
			else
			{
				$error	=	$this->User->InvalidFields();
				$out	=	array("status"=>false,"error"=>$this->General->getArrayFirstIndex($error));
			}
		}
		$this->set("data",$out);
		$this->render(false);
	}
}
?>