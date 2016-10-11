<?php
class AccountController extends AppController
{
	public $components		=	array("General","Acl");
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"login";
	}

	public function Login()
	{
		//var_dump($this->General->my_encrypt("G0m41d2015!!"));
		if(!empty($this->request->data))
		{
			$this->loadModel("Admin");
			$this->Admin->set($this->request->data);
			$this->Admin->ValidateAdmin();
			if($this->Admin->validates())
			{
				$data			=	$this->Admin->find("first",array(
										"conditions"	=>	array(
											"LOWER(Admin.username)"	=>	strtolower($this->request->data["Admin"]["username"])
										),
										"order"		=>	array(
											"Admin.id DESC"
										)
									));
				//CREATE COOKIE
				$user_id		=	$data['Admin']['id'];
				$this->Cookie->write('userlogin',	$this->General->my_encrypt($user_id),false,"1 days");
				$this->redirect($this->settings["cms_url"]);
			}
		}
	}
	
	public function Test()
	{
		
		App::import('Vendor','Swift' ,array('file'=>'lib/swift_required.php'));
		
		$transport = Swift_SmtpTransport::newInstance('localhost',25)
		  ->setUsername('customer@gomaid.co.id')
		  ->setPassword('customer123');
		$mailer 				=	Swift_Mailer::newInstance($transport);
		
		// To use the ArrayLogger
		$logger = new Swift_Plugins_Loggers_ArrayLogger();
		$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
		
		// Or to use the Echo Logger
		$logger = new Swift_Plugins_Loggers_EchoLogger();
		$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
		
		$message 				=	Swift_Message::newInstance("Test")
		  ->setFrom(array("customer@gomaid.co.id" => "Customer"))
		  ->setTo(array("abyfajar@gmail.com" => "AbyFajar"))
		  ->setBody("Testing email dari aby", 'text/html');
		$send 					= $mailer->send($message);
		echo $logger->dump();
	}
	
	public function Logout()
	{
		$this->Cookie->delete('userlogin');
		$this->Cookie->destroy();
		return $this->redirect($this->settings['cms_url']);
	}
}
?>