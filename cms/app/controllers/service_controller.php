<?php
class ServiceController extends AppController
{
	var $name 			=	'Service';
	var $uses 			=	array('Service');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','service');
		$this->layout	=	"new";
	}
	
	function DeactivateAdUserWaiting($status=null,$code)
	{
		$error	=	"";
		
		if(!empty($this->profile) and in_array($status,array("0","1")))
		{
			$update		=	$this->Service->updateAll(
								array(
									'status'		=>	"'{$status}'"
								),
								array(
									"Service.code"	=>	"{$code}"
								)
							);
			$status_msg	=	($status==1) ? "Service telah dihidupkan!." : "Service telah dimatikan.";
		}
	
		
		if(empty($this->profile))
		{
			$error	=	"Maaf login anda telah expired.";
		}
		
		if(!in_array($status,array("0","1")))
		{
			$error	=	"Maaf parameter status anda salah!.";
		}
		$this->set(compact("error","status_msg"));
	}
}
?>