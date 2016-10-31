<?php
ob_start();
session_start();

class AppController extends Controller
{
	var $components = array('Cookie','Session','Action','General');
	var $helpers 	= array('Form', 'Html', 'Javascript', 'Time','Ajax','Number','Text','Cache');
	var $profile;
	var $admin;


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
		$this->layout			=	'index';

		/**
		//REDIRECT PAGE IF DOMAIN NOT WWW
		$uri=	$this->__curPageURL();
		preg_match("/^(http:)\/\/(www\.|cdn\.)?(.*)/i",$uri, $matches);
		$valid					='http://www.'.$matches[3];

		if($matches[2]!='www.' and $matches[2]!='cdn.')
		{
			$this->redirect($valid);
		}
		**/

		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$this->loadModel('Setting');
			$settings		=	$this->Setting->find('first');
			Cache::write('settings', $settings);
		}


		$this->settings	=	$settings['Setting'];
		$this->set('settings',			$settings['Setting']);
		$this->set('title_for_layout',	$settings['Setting']['site_title']);
		$this->set('site_description',	$settings['Setting']['site_description']);

		$this->set('is_login',"0");
		$this->set('site_keywords',$settings['Setting']['site_keywords']);

		$this->Cookie->domain		=	$this->settings['site_domain'];
		$this->Cookie->path			=	"/";

		//CHECK ADMIN LOGIN
		/*$inviter_cookie	=	$this->Cookie->read("inviter");

		if(empty($inviter_cookie) && $this->params['controller']!="Inviter" && is_null($this->params["requested"]))
		{
			$session_back	=	$this->Session->read('back_url');
            $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
			$this->Cookie->write('back_url',$back_url,false,3600,$this->settings['site_domain']);
			$this->redirect($this->settings['site_url']."Inviter/Login");
		}*/

		//CHECK USER LOGN
		if(!is_null($this->Cookie->read("userlogin")))
		{
			$this->profile	=	$this->CheckProfile();
			if($this->profile['User']['userstatus_id']!=="1")
			{
				$this->Cookie->delete("userlogin");
				$this->redirect($this->settings['site_url']);
			}
			$this->user_id	=	$this->profile['User']['id'];
			$this->set('profile',$this->profile);
			$this->set('is_login',"1");

			$display_name	=	(empty($this->profile['Profile']['id'])) ? $this->profile['User']['email'] : $this->profile['Profile']['fullname'];
			$this->set('display_name',$display_name);
		}
	}

	function CheckProfile()
	{
		$id		=	$this->General->my_decrypt($this->Cookie->read("userlogin"));

		$extid	=	array();
		$this->loadModel('User');

		$find	=	$this->User->find('first',array(
						'conditions'	=>	array(
							'User.id'		=>	$id
						)
					));


		//CHECK EXTERNAL ID
		$this->loadModel("Extid");
		$this->Extid->unbindModel(array("belongsTo"=>array("Users")));
		$fextid	=	$this->Extid->find("all",array(
						"conditions"	=>	array(
							"Extid.user_id"	=>	$id
						),
						'fields'	=>	array('Extid.extName')
					));
		if($fextid)
		{
			foreach($fextid as $fextid)
			{
				$extid[]	=	$fextid["Extid"]['extName'];
			}
		}
		$find['extid']		=	$extid;
		return $find;
	}
}
?>
