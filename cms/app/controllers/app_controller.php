<?php
ob_start();
session_start();
class AppController extends Controller
{
	var $components = array('Cookie','Session','Action','General');
	var $helpers 	= array('Form', 'Html', 'Javascript', 'Time','Ajax','Number','Text');
	
	function __curPageURL() {
		$pageURL = 'http';
		
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	function beforeFilter()
	{
		/**
		//REDIRECT PAGE IF DOMAIN NOT WWW
		$uri=	$this->__curPageURL();
		preg_match("/^(http:)\/\/(www\.)?(.*)/i",$uri, $matches);
		$valid					='http://cms.'.$matches[3];
		if($matches[2]!='cms.')
		{
			//$this->redirect($valid);
		}
		$this->layout			=	'index';
		**/

		/**
		//CHECK COOKIE LOGIN
		$admin_cookie	=	$this->Cookie->read("admin");
		
		if(empty($admin_cookie))
		{
			$this->redirect(array("controller"=>"AccessAdmin","action"=>"Login"));
		}
		else
		{
			$this->profile	=	$this->CheckProfile();
		}
		**/
		
		
		//SET GENERAL SETTINGS
		$this->loadModel('Setting');
		$settings		=	$this->Setting->find('first');
		$set			=	$settings['Setting'];
		$this->settings	=	$set;
		$this->set('settings',			$set);
		$this->set('title_for_layout',	$settings['Setting']['site_title']);
		$this->set('profile',$this->profile['Profile']);
	}
	
	function CheckProfile()
	{
		$id		=	$this->General->my_decrypt($this->Cookie->read("admin"));
		$this->loadModel('User');
		$find	=	$this->User->find('first',array(
						'conditions'	=>	array(
							'User.id'			=>	$id,
							'User.admintype_id'	=>	array(2,3)
						)
					));
		return $find;
	}
}
?>