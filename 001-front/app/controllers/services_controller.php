<?php
class ServicesController extends AppController
{
	var $name	=	"Services";
	var $uses	=	null;
	
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	
	function Stop()
	{
		$this->set("active_code","edit_profile");
		
		if(isset($this->params['named']['token']))
		{
			if(!empty($this->params['token']))
			{
				$this->params['named']['token']	= implode("/",array($this->params['named']['token'],implode("/",$this->params['token'])));	
			}
		}
		
		App::import('vendor', 'encryption_class');
       	$encrypt	= new encryption;
		
		if(empty($this->user_id))
		{
			$this->Session->write('back_url',$this->settings['site_url']."Service/Stop/token:".$this->params['named']['token']);
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
		$token			=	$encrypt->my_decrypt($this->params['named']['token']);
		$data			=	explode("|",$token);
		$user_id		=	$data[1];
		$service_code	=	$data[0];
		$error			=	array();
		
		
		//LOAD MODEL USER SERVICE
		$this->loadModel('UserService');
		if($this->user_id != $user_id)
		{
			$error[]		=	"Maaf anda tidak memiliki layanan ini.";
		}
		else
		{
			$chk_service	=	$this->UserService->find('first',array(
									"conditions"	=>	array(
										"UserService.user_id"		=>	$user_id,
										"UserService.service_code"	=>	$service_code
									)
								));
			if($chk_service	== false)
			{
				$error[]		=	"Maaf anda tidak memiliki layanan ini.";
			}
			else
			{
				$update			=	$this->UserService->updateAll(
										array(
											"active"	=>	"'0'"
										),
										array(
											"UserService.id"	=>	$chk_service["UserService"]["id"]
										)
									);
			}
		}
		$this->set("error",reset($error));
	}
}
?>