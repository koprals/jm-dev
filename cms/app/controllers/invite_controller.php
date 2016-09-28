<?php 
class InviteController extends AppController
{
	var $name		=	"Invite";
	var $uses		=	array('Invitation');
	var $components	=	array('Action','General');
	var $helpers	=	array('General');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','invite');
		$this->layout	=	"new";
	}
	
	function Index()
	{
		$this->set('child_code','daftar_invite');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Invite');
	}
	
	function Add($id=null)
	{
		$this->set('child_code','daftar_invite');
		$this->set('id',$id);
		if(!is_null($id))
		{
			$this->loadModel("Invitation");
			$this->data	=	$this->Invitation->findById($id);
			$this->data['Invitation']['password']	=	$this->General->my_decrypt($this->data['Invitation']['password']);
			$this->Invitation->set($this->data);
		}
	}
	
	function ListItem($liststatus="all")
	{
		$this->layout			=	"ajax";
		$viewpage				=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order					=	array('Invitation.id DESC');
		
		//DISPLAY USER STATUS ID
		$this->loadModel('Invitation');
		
		//DEFINE FIELDS
		$fields				=	array(
									'Invitation.*'
								);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('CondSearch.Invitation');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'Invitation.name LIKE'		=> "%$val%",
											'Invitation.username LIKE'		=> "%$val%",
											'Invitation.email LIKE'	=> "%$val%"
										)
									);
				}
			}
			
			$OR['OR'][]			=	"MATCH (Invitation.name, Invitation.username, Invitation.email) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			
			array_push($fields,"MATCH (Invitation.name, Invitation.username, Invitation.email) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("CondSearch.Invitation",$cond_search);
			$order			=	array('score DESC','Invitation.created DESC');
		}
		
		//DEFINE FILTERING
		$cond_search	=	array();
		if(!empty($this->data))
		{
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["Invitation.id"]		=	$this->data['Search']['id'];
			}
			
			
			if(!empty($this->data['Search']['name']))
			{
				$cond_search["Invitation.name LIKE "]		=	"%".str_replace(" ","",$this->data['Search']['name'])."%";
			}
			
			if(!empty($this->data['Search']['username']))
			{
				$cond_search["Invitation.username LIKE "]		=	"%".str_replace(" ","",$this->data['Search']['username'])."%";
			}
			
			if(!empty($this->data['Search']['tgl_input']))
			{
				$string		=	explode("s.d",str_replace(" ","",$this->data['Search']['tgl_input']));
				
				
				if(count($string) > 1)
				{
					$date1		=	strtotime($string[0]);
					$date2		=	strtotime($string[1]);
					if($date1 > $date2)
					{
						$date1		=	$date2;
						$date2		=	strtotime($string[0]);
					}
					
					$cond_search["Invitation.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
					$cond_search["Invitation.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				
			}
			
			if(!empty($this->data['Search']['email']))
			{
				$cond_search["Invitation.email LIKE "]				=	"%".$this->data['Search']['email']."%";
			}
			
			if(!empty($this->data['Search']['password']))
			{
				$cond_search["Invitation.password LIKE"]			=	"%".$this->General->my_encrypt($this->data['Search']['email'])."%";
			}
			
			if($this->data['Search']['comming']!="")
			{
				$cond_search["Invitation.comming"]					=	$this->data['Search']['comming'];
			}
			$this->Session->write("CondSearch.Invitation",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.Invitation');
			unset($this->data);
		}
		
		$cond_search		=	array();
		$filter_paginate	=	array();
		$this->paginate	=	array(
			'Invitation'	=>	array(
				'limit'		=> $viewpage,
				'order'		=> $order,
				"fields"	=>	$fields
			)
		);
		$ses_cond		= $this->Session->read("CondSearch.Invitation");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('Invitation',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['Invitation']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['Invitation']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
	}
	
	//Yth blablabla, kami  mengundang anda utk melakukan ujicoba/testing terhadap portal kami sebelum kami launching.
	function ProcessAdd()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$this->loadModel("Invitation");
		
		$this->Invitation->set($this->data);
		$this->Invitation->DefaultValidate();
		
		if($this->Invitation->validates())
		{
			//SAVE
			$this->data['Invitation']['password']	=	$this->General->my_encrypt($this->data['Invitation']['password']);
			$this->Invitation->save($this->data);
			$last_id	=	$this->Invitation->getLastInsertId();
			
			if($this->data["Invitation"]["kirim_pesan"] == "1")
			{
				//SEND MESSAGE
				$html		=	str_replace("&quot;","",$this->data['Invitation']['message']);
				$searchSub	=	array('[site_name]');
				$replaceSub	=	array($this->settings['site_name']);
				
				$send		=	$this->Action->EmailSend("invite",$this->data["Invitation"]["email"],$search=array(),$replace=array(),$searchSub,$replaceSub,'Invitation',$last_id,$html);
			}
			$out	=	array("status"=>true,"error"=>"Undangan telah dikirim.");
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
	
	function GetMessage()
	{
		$this->layout		=	"ajax";
		$id					=	$_GET['id'];
		$this->loadModel("Invitation");
		$data				=	$this->Invitation->findById($id);
		
		if(!empty($data))
		{
			$html			=	$data["Invitation"]["message"];
		}
		else
		{
			$site_name		=	$this->settings['site_name'];
			$site_url		=	$this->settings['site_url'];
			$logo_url		=	$this->settings['logo_url'];
			$name			=	$_GET['name'];
			$username		=	$_GET['username'];
			$password		=	$_GET['password'];
			$support_mail	=	$this->settings["support_mail"];
			$link			=	$this->settings["site_url"]."Us/Contact";
			$search			=	array('[name]','[username]','[password]','[link]','[site_name]','[site_url]','[logo_url]','[support_mail]');
			$replace		=	array($name,$username,$password,$link,$site_name,$site_url,$logo_url,$support_mail);
			$html			=	$this->Action->generateHTMLEMail("invite",$search, $replace);
		}
		
		$this->set("html",$html);
	}
}
?>