<?php
class TemplateController extends AppController
{
	public $components	=	array("Paginator");
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"ajax";
	}
	public function LeftMenu($lft_menu_category_id=1)
	{
		$this->loadModel("CmsMenu");
		$this->CmsMenu->BindDefault();
		$menu			=	$this->CmsMenu->find("all",array(
								'order' 		=> 	array('CmsMenu.sort asc'),
								"conditions"	=>	array(
									"CmsMenu.status"	=>	"1"
								)
							));
		$this->set(compact("menu","lft_menu_category_id"));
	}
	
	function LeftMenuSmall()
	{
		$this->loadModel("CmsMenu");
		$this->CmsMenu->BindDefault();
		$admin_level	=	$this->profile["User"]["user_level"];
		$menu			=	$this->CmsMenu->find("all",array(
								'order' => array('CmsMenu.sort asc'),
								"conditions"	=>	array(
									"CmsMenu.status"	=>	"1"
								)
							));
		$this->set(compact("menu","lft_menu_category_id"));
	}
}
?>