<?php 
class PesanController extends AppController
{
	var $name		=	"Pesan";
	var $uses		=	array('Contact');
	var $components	=	array('Action','General');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','contact');
		$this->layout	=	"new";
	}
	
	
	function Index()
	{
		$this->set('child_code','daftar_pesan');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Contact');
	}
	
	function Saran()
	{
		$this->set('child_code','kritik_dan_saran');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Contact');
	}
	
	function Testimoni()
	{
		$this->set('child_code','testimoni');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Contact');
	}
	
	function LaporanGalat()
	{
		$this->set('child_code','lapor_galat');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Contact');
	}
	
	function Pertanyaan()
	{
		$this->set('child_code','pertanyaan');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Contact');
	}
	
	function LaporanIklanSpam()
	{
		$this->set('child_code','lapor_iklan_spam');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Contact');
	}
	
	function ListItem($liststatus="all")
	{
		$this->layout	=	"ajax";
		$viewpage				=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order					=	array('Contact.id DESC');
		$this->set("liststatus", $liststatus);
		
		//DISPLAY USER STATUS ID
		$this->loadModel('Contact');
		
		if($liststatus!="all")
		{	
			$cond			=	 array("ContactCategory.id" => $liststatus);
		}
		
		//DEFINE FIELDS
		$fields				=	array(
									'Contact.*',
									'ContactCategory.name',
									'ContactCategory.id',
								);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('CondSearch.Contact');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'Contact.from LIKE'		=> "%$val%",
											'Contact.email LIKE'	=> "%$val%",
											'Contact.message LIKE'	=> "%$val%"
										)
									);
				}
			}
			
			$OR['OR'][]			=	"MATCH (Contact.from, Contact.email, Contact.message) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			
			array_push($fields,"MATCH (Contact.from, Contact.email, Contact.message) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("CondSearch.Contact",$cond_search);
			$order			=	array('score DESC','Contact.created DESC');
		}
		
		//DEFINE FILTERING
		$cond_search	=	array();
		if(!empty($this->data))
		{
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["Contact.id"]		=	$this->data['Search']['id'];
			}
			
			if(!empty($this->data['Search']['from']))
			{
				$cond_search["Contact.from LIKE "]		=	"%".str_replace(" ","",$this->data['Search']['from'])."%";
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
					
					$cond_search["Contact.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
					$cond_search["Contact.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				
			}
			if(!empty($this->data['Search']['email']))
			{
				$cond_search["Contact.email LIKE "]			=	"%".$this->data['Search']['email']."%";
			}
			
			if(!empty($this->data['Search']['phone']))
			{
				$cond_search["Contact.phone  LIKE "]				=	"%".$this->data['Search']['phone']."%";
			}
			
			if(!empty($this->data['Search']['message']))
			{
				$cond_search["Contact.message LIKE "]		=	"%".str_replace(" ","",$this->data['Search']['message'])."%";
			}
			
			if($this->data['Search']['response']!="")
			{
				$cond_search["Contact.response"]		=	$this->data['Search']['response'];
			}
			if($this->data['Search']['publish']!="")
			{
				$cond_search["Contact.publish"]			=	$this->data['Search']['publish'];
			}
			$this->Session->write("CondSearch.Contact",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.Contact');
			unset($this->data);
		}
		
		$cond_search		=	array();
		$filter_paginate	=	($liststatus=="all") ? array() : array('ContactCategory.id' => $liststatus);
		$this->paginate	=	array(
			'Contact'	=>	array(
				'limit'		=> $viewpage,
				'order'		=> $order,
				"fields"	=>	$fields
			)
		);
		$ses_cond		= $this->Session->read("CondSearch.Contact");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('Contact',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['Contact']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['Contact']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
	}
	
	function GetFullText($ID)
	{
		$this->layout	=	"json";
		$data			=	$this->Contact->findById($ID);
		$this->set("data",$data['Contact']['message']);
		$this->render(false);
	}
	
	function GetTruncateText($ID)
	{
		//IMPORT HELPERS
		App::Import('Helper','Text');
		$text	=	new TextHelper();
		
		$this->layout	=	"json";
		$data			=	$this->Contact->findById($ID);
		
		if(strlen($data['Contact']['message']) > 20 )
		{
			$text		=	$text->truncate($data['Contact']['message'],20,array('ending'=>"..<br>"));
		}
		else
		{
			$text		= nl2br($data['Contact']['message']);
		}
		$this->set("data",$text);
		$this->render(false);
	}
	
	function Edit($ID)
	{
		$this->set('child_code','daftar_pesan');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.Contact');
		
		$this->loadModel("Contact");
		$data	=	$this->Contact->findById($ID);
		
		if($data)
		{
			$this->loadModel("ContactCategory");
			$contact_category_id	=	$this->ContactCategory->find("list");
			$arr_bread_crumb		=	array(1=>"kritik_dan_saran",2=>"testimoni",3=>"lapor_galat",4=>"pertanyaan",5=>"lapor_iklan_spam");
			$this->set(compact("data","contact_category_id"));
			$this->set('child_code',$arr_bread_crumb[$data["Contact"]["contact_category_id"]]);
		}
	}
	
	function ProcessEdit()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		App::import('Sanitize');
		
		$this->loadModel("Contact");
		$detail					=	$this->Contact->findById($this->data['Contact']['id']);
		$this->Contact->set($this->data);
		$this->Contact->ValidateSendPm();
		
		if($this->Contact->validates())
		{
			//SEND MESSAGE
			if($this->data["Contact"]["kirim_pesan"] == "1")
			{
				$this->data["Contact"]["response"]		=	"1";
				$this->data["Contact"]["response_date"]	=	date("Y-m-d H:i:s");
				$this->data["Contact"]["response_by"]	=	$this->profile["Profile"]["fullname"];
			}
			
			//SAVE 
			$this->Contact->save($this->data);
			
			if($this->data["Contact"]["kirim_pesan"] == "1")
			{
				$this->data['ResponseLog']['contact_id']	=	$this->data['Contact']['id'] ;
				$this->data['ResponseLog']['admin_id']		=	$this->profile['User']['id'] ;
				$this->data['ResponseLog']['message']		=	$html ;
				$this->loadModel("ResponseLog");
				$this->ResponseLog->create();
				$this->ResponseLog->save($this->data);
				
				//SEND MESSAGE
				$html		=	str_replace("&quot;","",$this->data['Contact']['response_message']);
				$send		=	$this->Action->EmailSend("contact",$detail["Contact"]["email"],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'Contact',$detail["Contact"]["id"],$html);
			
			}
			$out	=	array("status"=>true,"error"=>"Response telah dikirim.");
			
			//CLEAR CACHE
			@unlink($this->settings['path_web'].'app/tmp/cache/views/element__testimonial');
		}
		else
		{
			$error	=	$this->Contact->InvalidFields();
			foreach($this->data['Contact'] as $k=>$v)
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
		$contact_id			=	$_GET['contact_id'];
		$this->loadModel("Contact");
		$data				=	$this->Contact->findById($contact_id);
		
		if($data["Contact"]["response"]=="1")
		{
			$html			=	$data["Contact"]["response_message"];
		}
		else
		{
			$sender_name	=	$data["Contact"]["from"];
			$category_name	=	$data["ContactCategory"]["name"];
			$site_name		=	$this->settings['site_name'];
			$site_url		=	$this->settings['site_url'];
			$logo_url		=	$this->settings['logo_url'];
			$message		=	$data["Contact"]["message"];
			$search			=	array('[sender_name]','[category_name]','[site_name]','[site_url]','[logo_url]','[message]');
			$replace		=	array($sender_name,$category_name,$site_name,$site_url,$logo_url,$message);
			$html			=	$this->Action->generateHTMLEMail("contact",$search, $replace);
			
		}
		
		$this->set("html",$html);
	}
}
?>