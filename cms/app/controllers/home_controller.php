<?php
class HomeController extends AppController
{
	var $name	=	"Home";
	var $uses	=	null;
	var $helpers	=	array('Number','Product');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"new";
	}
	
	function Info()
	{
		echo phpinfo();
		//var_dump(function_exists('curl_init'));
		$this->autoRender	=	false;
	}

	function Index()
	{
		$this->loadModel('Category');
	}
	
	function NewLayout()
	{
		$this->layout	=	"test";
	}
	
	function ProductRequest()
	{
		$this->layout	=	"ajax";
		$this->loadModel('Product');
		$this->loadModel('Category');
		
		//FIND DATA
		$data	=	$this->Product->find('all',array(
						'conditions'	=>	array(
							'Product.productstatus_id'	=>	array(0,-2)
						),
						'order'	=>	array('Product.modified Desc'),
						'limit'	=>	5
					));
		
		//FIND COUNT DATA WAITING APPROVAL
		$c_w_a	=	$this->Product->find('count',array(
						'conditions'	=>	array(
							array('Product.productstatus_id' => 0)
						)
					));
		
		//FIND COUNT DATA WAITING APPROVAL AFTER EDITING
		$c_w_aae	=	$this->Product->find('count',array(
							'conditions'	=>	array(
								array('Product.productstatus_id' => -2)
							)
						));
		
		//FIND COUNT DATA APPROVE
		$c_a	=	$this->Product->find('count',array(
							'conditions'	=>	array(
								array('Product.productstatus_id' => 1)
							)
						));
		
		//FIND COUNT DATA EDITING REQUIRED
		$c_e_r	=	$this->Product->find('count',array(
							'conditions'	=>	array(
								array('Product.productstatus_id' => -1)
							)
						));
		
		foreach($data as $k=>$v)
		{
			$detail_cat	=	$this->Category->GetCatAndSubcat($v['Product']['category_id']);
			$data[$k]['Product']['category']	=	$detail_cat[0];
			$data[$k]['Product']['subcategory']	=	$detail_cat[1];
		}
		$this->set(compact("data","count","c_w_a","c_w_aae","c_a","c_e_r"));
	}
	
	
	
	function MemberApproval()
	{
		$this->layout	=	"ajax";
		$this->loadModel('User');
		
		//FIND DATA
		$data	=	$this->User->find('all',array(
						'conditions'	=>	array(
							'User.userstatus_id > '	=>	-2
						),
						'order'	=>	array('User.id Desc'),
						'limit'	=>	5
					));
		
		//FIND COUNT DATA
		$count	=	$this->User->find('count',array(
						'conditions'	=>	array(
							'User.userstatus_id > '	=>	-2
						)
					));
		$this->set(compact("data","count"));
	}
	
	function ContactUs()
	{
		$this->layout	=	"ajax";
		$this->loadModel('Contact');
		
		//FIND DATA
		$data	=	$this->Contact->find('all',array(
						'order'	=>	array('Contact.id Desc'),
						'limit'	=>	5
					));
		
		//FIND COUNT DATA
		$count	=	$this->Contact->find('count');
		
		
		//COUNT DATA SARAN
		$c_saran	=	$this->Contact->find('count',array(
						'conditions'	=>	array(
							array('Contact.contact_category_id' => 1)
						)
					));
		
		//COUNT DATA TESTIMONI
		$c_testimoni	=	$this->Contact->find('count',array(
						'conditions'	=>	array(
							array('Contact.contact_category_id' => 2)
						)
					));
		
		//COUNT DATA PERTANYAAN
		$c_pertanyaan	=	$this->Contact->find('count',array(
						'conditions'	=>	array(
							array('Contact.contact_category_id' => 4)
						)
					));
		
		$this->set(compact("data","count","c_saran","c_testimoni","c_pertanyaan"));
	}
}
?>