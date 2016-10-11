<?php
class AccessControllListController extends AppController
{
	var $ControllerName		=	"AccessControllList";
	var $ModelName			=	"AroAco";
	var $helpers			=	array("Text","Aimfox");

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set("ControllerName",$this->ControllerName);
		$this->set("ModelName",$this->ModelName);
		$this->set('lft_menu_category_id',"2");

		//CHECK PRIVILEGES
		$this->loadModel("MyAco");
		$find					=	$this->MyAco->find("first",array(
										"conditions"	=>	array(
											"LOWER(MyAco.alias)"	=>	strtolower("Permission")
										)
									));
		$this->aco_id			=	$find["MyAco"]["id"];
		$this->set("aco_id",$this->aco_id);
	}

	function Index($ID=NULL,$page=1,$viewpage=50)
	{
		if(($ID == $this->super_admin_id && $this->profile["Admin"]["id"] != $this->super_admin_id) or $this->access[$this->aco_id]["_read"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}

		$this->loadModel("MyAco");
		$this->loadModel("MyAro");
		$this->loadModel("AroAco");

		//GET DETAIL ARO
		$detailAro	=	$this->MyAro->find("first",array(
							"conditions"	=>	array(
								"MyAro.id"	=>	$ID
							)
						));
		if(empty($detailAro))
		{
			$this->layout	=	"error";
			return;
		}

		//GET DETAIL PARENT
		$top				=	$this->MyAco->find("first",array(
										"conditions"	=>	array(
											"MyAco.parent_id"	=>	NULL
										)
									));
		$lft				=	$top["MyAco"]["lft"];
		$rght				=	$top["MyAco"]["rght"];

		//DEFINE NEWS ACO
		$data		=	$this->MyAco->generateTreeList2(array("lft > "=>"$lft","rght < "=>"$rght"),"{n}.MyAco.id","{n}.MyAco.alias","&raquo; ");

		if(!empty($this->request->data))
		{
			$post	=	$this->request->data["AroAco"];
			$this->AroAco->deleteAll(array(
				"AroAco.aro_id"	=>	$ID
			));

			foreach($post as $k => $v)
			{
				$AroAco["AroAco"]["aro_id"]		=	$ID;
				$AroAco["AroAco"]["aco_id"]		=	$k;
				$AroAco["AroAco"]["_create"]	=	$v["_create"];
				$AroAco["AroAco"]["_read"]		=	$v["_read"];
				$AroAco["AroAco"]["_update"]	=	$v["_update"];
				$AroAco["AroAco"]["_delete"]	=	$v["_delete"];
				$this->AroAco->create();
				$this->AroAco->save($AroAco);
			}

			$this->Session->setFlash(
				'<p>Access Controll has been saved</p>',
				'default',
				array(
					'class' => 'nNote nSuccess hideit',
				)
			);
			$this->redirect(array("action"=>"Index",$ID,$page=1,$viewpage=50));
		}
		else
		{
			$find	=	$this->AroAco->find("all",array(
							"conditions"	=>	array(
								"AroAco.aro_id"	=>	$ID
							)
						));
			foreach($find as $find)
			{
				$this->request->data["AroAco"][$find["AroAco"]["aco_id"]]["_read"]		=	$find["AroAco"]["_read"];
				$this->request->data["AroAco"][$find["AroAco"]["aco_id"]]["_create"]	=	$find["AroAco"]["_create"];
				$this->request->data["AroAco"][$find["AroAco"]["aco_id"]]["_update"]	=	$find["AroAco"]["_update"];
				$this->request->data["AroAco"][$find["AroAco"]["aco_id"]]["_delete"]	=	$find["AroAco"]["_delete"];
			}
		}

		$this->set(compact("ID","data","top","page","viewpage","detailAro"));
	}
}
?>
