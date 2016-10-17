<?php

App::uses('Controller', 'Controller');
class AppController extends Controller
{
	var $settings;
	public $helpers		=	array("Form","Js","Session");
	public $profile;
	public $components	=	array(
		"General",
		"Session",
		"Cookie",
		"Acl",
		'Action'
	);


	public $access;
	public $super_admin_id;
	public $ext = '.php';

	public function beforeFilter()
	{
		$this->layout	=	"main";

		//SET GENERAL SETTINGS
		$this->loadModel('Setting');
		$settings			=	$this->Setting->find('first');
		$this->settings		=	$settings['Setting'];
		$this->set('settings',		$settings['Setting']);

		//GET CONTROLLER AND ACTION NAME
		$controller			=	strtolower($this->params["controller"]);
		$action				=	strtolower($this->params["action"]);
		$c_allowed			=	array("account","template");//LIST OF CONTROlLER THAT SHOULD NOT REDIRECT
		$a_allowed			=	array("login","register");//LIST OF ACTION THAT SHOULD NOT REDIRECT

		//CHECK ADMIN COOKIE
		$userlogin		=	$this->Cookie->read("userlogin");
		if(empty($userlogin))
		{
			if(!in_array($controller,$c_allowed) and !in_array($action,$a_allowed))
			{
				$this->redirect(array("controller"=>"Account","action"=>"Login"));
			}
		}
		else
		{
			$this->profile	=	$this->CheckProfile();
			if(empty($this->profile))
			{
				$this->Cookie->delete("userlogin");
				$this->redirect($this->settings["cms_url"]);
			}
		}
		$this->set('profile',$this->profile);
		$this->set('access',$this->access);
		$this->set('lft_menu_category_id',"1");
	}

	function CheckProfile()
	{
		$id		=	$this->General->my_decrypt($this->Cookie->read("userlogin"));
		$this->loadModel('Admin');
		$this->Admin->BindDefault();
		$this->Admin->BindImageContent(false);
		$find	=	$this->Admin->find('first',array(
						'conditions'	=>	array(
							'Admin.id'		=>	$id
						),
						"recursive"		=>	2
					));

		//CHECK PRIVILEGES
		$this->loadModel("AroAco");
		$aro_id			=	$find["Admin"]["aro_id"];
		$fPrevilidges	=	$this->AroAco->find("all",array(
									"conditions"	=>	array(
										"AroAco.aro_id"	=>	$aro_id
									)
								));
		if(!empty($fPrevilidges))
		{
			foreach($fPrevilidges as $fPrevilidges)
			{
				$access[$fPrevilidges["AroAco"]["aco_id"]]["_read"]		=	$fPrevilidges["AroAco"]["_read"];
				$access[$fPrevilidges["AroAco"]["aco_id"]]["_create"]	=	$fPrevilidges["AroAco"]["_create"];
				$access[$fPrevilidges["AroAco"]["aco_id"]]["_update"]	=	$fPrevilidges["AroAco"]["_update"];
				$access[$fPrevilidges["AroAco"]["aco_id"]]["_delete"]	=	$fPrevilidges["AroAco"]["_delete"];
			}
			$this->access	=	$access;
		}

		//CHECK SUPERADMIN ID
		$this->loadModel("MyAro");
		$aro		=	$this->MyAro->find("first",array(
							"conditions"	=>	array(
								"MyAro.parent_id"	=>	NULL
							)
						));

		$superadmin	=	$this->Admin->find('first',array(
							'conditions'	=>	array(
								'Admin.aro_id'		=>	$aro["MyAro"]["id"]
							)
						));
		$this->super_admin_id	=	$superadmin["Admin"]["id"];

		return $find;
	}
}
