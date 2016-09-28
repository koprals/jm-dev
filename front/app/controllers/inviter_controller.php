<?php
class InviterController extends AppController
{
	var $uses	=	NULL;
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function Login()
	{
		$this->layout	=	"ajax";
	}
	
	function ProcessLogin()
	{
		$this->loadModel("Invitation");
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		$err				=	array();
		
		
		$this->Invitation->ValidateLogin();
		$this->Invitation->set($this->data);
			
		if($this->Invitation->validates())
		{
			$session_back	=	$this->Session->read('back_url');
            $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
			$this->Cookie->write('inviter',$this->data["Invitation"]["username"],false,3600*60*24*350,$this->settings['site_domain']);
			
			$this->Invitation->updateAll(
				array(
					"comming"		=>	"'1'",
					"comming_date "	=>	"'".date("Y-m-d H:i:s")."'",
				),
				array(
					"LOWER(Invitation.username)"	=>	strtolower($this->data["Invitation"]["username"])
				)
			);
			$out				=	array("status"=>true,"error"=>$back_url);
		}
		else
		{
			$error	=	$this->Invitation->InvalidFields();
			foreach($this->data['Invitation'] as $k=>$v)
			{
				if(array_key_exists($k,$error))
				{
					$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);
				}
				elseif(empty($v) OR (is_array($v) AND empty($v["name"])))
				{
					$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
				}
				else
				{
					$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
				}
			}
			$out	=	array("status"=>false,"error"=>$err);
		}
			
		$this->set("data",$out);
		$this->render(false);
	}
	
	
	function ProcessInvite()
	{
		$this->loadModel("Invitation");
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		$err				=	array();
		
		
		$this->Invitation->ValidateInvite();
		$this->Invitation->set($this->data);
			
		if($this->Invitation->validates())
		{
			$this->Invitation->create();
			$save	=	$this->Invitation->save($this->data);
			$id		=	$this->Invitation->getLastInsertId();
			
			//EMAIL KE ADMIN, TERDAPAT SESEORANG YANG INGIN DIUNDANG
			$logo_url		=	$this->settings['logo_url'];
			$site_url		=	$this->settings['site_url'];
			$site_name		=	$this->settings['site_name'];
			$email			=	$this->data["Invitation"]["email"];
			$link			=	$this->settings['cms_url']."Invite/Add/".$id;
			
			
			$s_search		=	array("[email]",'[site_name]');
			$s_replace		=	array($email,$site_name);
				
			$search 		=	array('[logo_url]','[site_url]','[site_name]','[email]','[link]');
            $replace 		=	array($logo_url,$site_url,$site_name,$email,$link);
			
			$this->Action->EmailSend('invite_request', $this->settings['admin_mail'], $search, $replace, $s_search, $s_replace);
			
			$out			=	array("status"=>true,"error"=>"");
		}
		else
		{
			$error	=	$this->Invitation->InvalidFields();
			foreach($this->data['Invitation'] as $k=>$v)
			{
				if(array_key_exists($k,$error))
				{
					$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);
				}
				elseif(empty($v) OR (is_array($v) AND empty($v["name"])))
				{
					$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
				}
				else
				{
					$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
				}
			}
			$out	=	array("status"=>false,"error"=>$err);
		}
			
		$this->set("data",$out);
		$this->render(false);
	}
}
?>