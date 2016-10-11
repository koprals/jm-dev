<?php
class HomeController extends AppController
{
	var $uses	=	NULL;
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('lft_menu_category_id',"1");
	}
	
	public function Index()
	{
		$acos			=	$this->Acl->Aco->find('threaded');
        $group_aro		=	$this->Acl->Aro->find('threaded',array('conditions'=>array('Aro.id'=>1)));
        $group_perms	=	Set::extract('{n}.Aco', $group_aro);
        $gpAco 			=	array();
        foreach($group_perms[0] as $value) {
            $gpAco[$value['id']] = $value;
        }
	}
	
	public function NewOrder()
	{
		$this->layout	=	"ajax";
		
		//GET NEW ORDER
		$this->loadModel("Order");
		$this->Order->BindAll2(false);
		$this->Order->User->BindImageContent(false);
		$Order			=	$this->Order->find("all",array(
								"order"			=>	"Order.id DESC",
								"recursive"		=>	2,
								"limit"			=>	5
							));
							
		pr($Order);
		$this->set(compact("Order"));
	}
	
	public function NewUser()
	{
		$this->layout	=	"ajax";
		
		//GET NEW USER
		$this->loadModel("User");
		$this->User->BindImageContent(false);
		$User			=	$this->User->find("all",array(
								"order"			=>	"User.id DESC",
								"recursive"		=>	2,
								"limit"			=>	5,
								"conditions"	=>	array(
									"User.user_type_id"	=>	1
								)
							));
							
		pr($Order);
		$this->set(compact("User"));
	}
	
	public function PaymentConfirmation()
	{
		$this->layout	=	"ajax";
		
		//GET NEW PAYMENT CONFIRMATION
		$this->loadModel("PaymentConfirmation");
		$this->PaymentConfirmation->BindDefault(false);
		$this->PaymentConfirmation->User->BindImageContent(false);
		$PaymentConfirmation			=	$this->PaymentConfirmation->find("all",array(
												"order"			=>	"PaymentConfirmation.id DESC",
												"recursive"		=>	2,
												"limit"			=>	5
											));
							
		pr($PaymentConfirmation);
		$this->set(compact("PaymentConfirmation"));
	}
	
	public function TaskStatus()
	{
		$this->layout	=	"ajax";
		
		//GET NEW ORDER
		$this->loadModel("Order");
		$this->Order->BindAll2(false);
		$this->Order->User->BindImageContent(false);
		$this->Order->MandorAssignment->BindUser(false);
		
		$Order			=	$this->Order->find("all",array(
								"order"			=>	"Order.id DESC",
								"recursive"		=>	2,
								"limit"			=>	5,
								"conditions"	=>	array(
									"OR"	=>	array(
										"Order.status"			=>	2,
										"Order.task_status_id"	=>	array(2,3,4,5)
									)
								)
							));
							
		pr($Order);
		$this->set(compact("Order"));
	}
}
?>