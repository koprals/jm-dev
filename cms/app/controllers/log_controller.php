<?php
class LogController extends AppController
{
	var $name		=	"Log";
	var $uses		=	null;
	var $components	=	array('Action');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','logs');
		$this->layout	=	"new";
	}
	
	function UserLog()
	{
		$this->set('child_code','user_logs');
		//SET ACTION TYPE
		$this->loadModel('ActionTypes');
		$actionID	=	$this->ActionTypes->find("list",array(
							"fields"		=>	array("id","name"),
							"order"			=>	array("name ASC")
						));
		$this->set("actionID",$actionID);
		$this->set("user_id","all");
	}
	
	function EmailLog()
	{
		$this->set('child_code','email_log');
		//EMAIL SETTINGS
		$this->loadModel('EmailSettings');
		$email_setting_id	=	$this->EmailSettings->find('list',array('order'=>"EmailSettings.name ASC"));
		$this->set("user_id",$user_id);
		$this->set("email_setting_id",$email_setting_id);
		
		//USERDATA
		$this->loadModel('User');
		$user				=	$this->User->findById($user_id);
		$this->set("email",$user['User']['email']);
		$this->set("user_id","all");
	}
	
	function PointLog()
	{
		$this->set('child_code','point_log');
		//SET ACTION TYPE
		$this->loadModel('ActionTypes');
		$actionID	=	$this->ActionTypes->find("list",array(
							"fields"		=>	array("id","name"),
							"order"			=>	array("name ASC")
						));
		$this->set("actionID",$actionID);
		$this->set("user_id","all");
	}
}
?>