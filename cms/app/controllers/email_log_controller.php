<?php
class EmailLogController extends AppController
{
	var $name		=	"EmailLog";
	var $uses		=	array('EmailLog');
	var $components	=	array('Action','General');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','admin_user_management');
		$this->layout	=	"new";
	}
	
	function Index($user_id="all")
	{
		$this->set('child_code','user_list');
		$this->Session->delete('CondSearch.EmailLog');
		
		//EMAIL SETTINGS
		$this->loadModel('EmailSettings');
		$email_setting_id	=	$this->EmailSettings->find('list',array('order'=>"EmailSettings.name ASC"));
		$this->set("user_id",$user_id);
		$this->set("email_setting_id",$email_setting_id);
		
		//USERDATA
		$this->loadModel('User');
		$user				=	$this->User->findById($user_id);
		$this->set("email",$user['User']['email']);
	}
	
	function ListItem($user_id="all")
	{
		$this->layout		=	"ajax";
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order				=	array('EmailLog.id DESC');
		
		//DEFINE FIELDS
		$fields				=	array(
									'EmailLog.*',
									'EmailSettings.name'
								);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('CondSearch.EmailLog');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'EmailLog.fromtext LIKE'		=> "%$val%",
											'EmailLog.subject LIKE'			=> "%$val%",
											'EmailLog.text LIKE'			=> "%$val%"
										)
									);
				
				}
			}
			$OR['OR'][]			=	"MATCH (EmailLog.fromtext,EmailLog.subject,EmailLog.text) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			array_push($fields,"MATCH (EmailLog.fromtext,EmailLog.subject,EmailLog.text) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("CondSearch.EmailLog",$cond_search);
			$order			=	array('score DESC','EmailLog.id DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		if(!empty($this->data))
		{
			$this->Session->delete('CondSearch.EmailLog');
			
			
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["EmailLog.id"]					=	$this->data['Search']['id'];
			}
			
			if(!empty($this->data['Search']['tgl_input']))
			{
				$string		=	explode("s.d",str_replace(" ","",$this->data['Search']['tgl_input']));
				if(count($string) > 1)
				{
					$date1		=	strtotime($string[0]." 00:00:00");
					$date2		=	strtotime($string[1]." 23:59:59");
					if($date1 > $date2)
					{
						$date1		=	$date2;
						$date2		=	strtotime($string[0]);
					}
					
					$cond_search["EmailLog.last_send BETWEEN ? AND ?"]		=	array($date1,$date2);
				}
				else
					$cond_search["EmailLog.last_send BETWEEN ? AND ?"]		=	array(strtotime($string[0]." 00:00:00"),strtotime($string[0]." 23:59:59"));
			}
			
			if(!empty($this->data['Search']['email_setting_id']))
			{
				$cond_search["EmailLog.email_setting_id"]			=	$this->data['Search']['email_setting_id'];
			}
			if(!empty($this->data['Search']['from']))
			{
				$cond_search["EmailLog.from"]						=	$this->data['Search']['from'];
			}
			if(!empty($this->data['Search']['to']))
			{
				$cond_search["EmailLog.to"]							=	$this->data['Search']['to'];
			}
			if(!empty($this->data['Search']['subject']))
			{
				$cond_search["EmailLog.subject LIKE "]				=	"%".$this->data['Search']['subject']."%";
			}
			if($this->data['Search']['status']!="")
			{
				$cond_search["EmailLog.status"]						=	$this->data['Search']['status'];
			}
			$this->Session->write("CondSearch.EmailLog",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.EmailLog');
			unset($this->data);
		}
		
		//USERDATA
		$this->loadModel('User');
		$user				=	$this->User->findById($user_id);
		$cond_search		=	array();
		$filter_paginate	=	(preg_match('/^([0-9]+)$/',$user_id)) ?  array("OR"	=>	array('EmailLog.to' => $user['User']['email'],'EmailLog.from' => $user['User']['email'])) : array();
		$this->paginate		=	array(
			'EmailLog'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'fields'	=>	$fields
			)
		);
		
		$ses_cond			=	$this->Session->read("CondSearch.EmailLog");
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$data				=	$this->paginate('EmailLog',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['EmailLog']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['EmailLog']['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
		$this->set('user_id',$user_id);
		
		
	}
	
	function Detail($email_id=null,$user_id,$page=1)
	{
		$this->layout	=	"ajax";
		$data			=	$this->EmailLog->findById($email_id);
		if($data)
		{
			$back_url	=	$this->settings['cms_url']."EmailLog/ListItem/{$user_id}/page:{$page}";
			$this->set('back_url',$back_url);
		}
		$this->set(compact("data","back_url"));
	}			 
}
?>