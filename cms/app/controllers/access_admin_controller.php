<?php
class AccessAdminController extends AppController
{
	var $name		=	"AccessAdmin";
	var $uses		=	null;
	var $helpers	=	array("Form");
	var $settings	=	array();
	var $components = array('Cookie','Session','Action','General');
	
	function beforeFilter()
	{
		$this->layout	=	"access_admin";
		
		//SET GENERAL SETTINGS
		$this->loadModel('Setting');
		$settings		=	$this->Setting->find('first');
		$this->settings	=	$settings['Setting'];
		$this->Cookie->domain	=	$this->settings['site_domain'];
	}

	
	function Login()
	{
		if(!empty($this->data))
		{
			$this->loadModel("User");
			$this->User->set($this->data);
			$this->User->validateAdmin();
			
			if($this->User->validates())
			{
				$fByEmail		=	$this->User->findByEmail($this->data['User']['email_login']);
				$session_back	=	$this->Cookie->read('back_url');
				
                $back_url		=	isset($session_back) ? $session_back : $this->settings['cms_url'];
				$this->Cookie->write("admin",$this->General->my_encrypt($fByEmail['User']['id']), false,$this->settings['site_domain']);
				$this->redirect($back_url);
			}
		}
	}
	
	
	function Logout()
	{
		$this->Cookie->destroy();
		$this->Session->destroy();
		$this->redirect($this->settings['cms_url']);
	}
}
?>