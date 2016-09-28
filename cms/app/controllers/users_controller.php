<?php
class UsersController extends AppController
{
	var $name	=	"Users";
	var $uses	=	array('User');
	var $components	=	array('Action','General');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','admin_user_management');
		$this->layout	=	"new";
	}
	
	function GetSumData()
	{
		$this->layout	=	"json";
		$type			=	urldecode($_GET['type']);
		$sum			=	"";
		
		switch(strtolower($type))
		{
			case "active";
				$conditions	=	array('User.userstatus_id' => 1);
				break;
			case "block(soft block)";
				$conditions	=	array('User.userstatus_id' => -1);
				break;
			case "suspend(hard block)";
				$conditions	=	array('User.userstatus_id' => -2);
				break;
			case "waiting email confirm";
				$conditions	=	array('User.userstatus_id' => 0);
				break;
		}
		
		if(!empty($conditions))
		{
			$sum	=	$this->User->find('count',array(
							'conditions'	=>	$conditions
						));
		}
		$this->set("data",(empty($sum)) ? "0" : $sum);
		$this->render(false);
	}
	
	function WaitingEmailConfirm()
	{
		$this->set('parent_code','admin_user_management');
		$this->set('child_code','user_waiting_email_confirm');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.User');
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DISPLAY STATUS
		$this->loadModel('Userstatus');
        $userstatus_id = $this->Userstatus->DisplayStatus();
        $this->set("userstatus_id", $userstatus_id);
	}
	
	function StatusBlock()
	{
		$this->set('parent_code','admin_user_management');
		$this->set('child_code','user_block');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.User');
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DISPLAY STATUS
		$this->loadModel('Userstatus');
        $userstatus_id = $this->Userstatus->DisplayStatus();
        $this->set("userstatus_id", $userstatus_id);
	}
	
	function StatusSuspend()
	{
		$this->set('parent_code','admin_user_management');
		$this->set('child_code','user_suspend');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.User');
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DISPLAY STATUS
		$this->loadModel('Userstatus');
        $userstatus_id = $this->Userstatus->DisplayStatus();
        $this->set("userstatus_id", $userstatus_id);
	}
	
	function ActiveUser()
	{
		$this->set('parent_code','admin_user_management');
		$this->set('child_code','user_active');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.User');
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DISPLAY STATUS
		$this->loadModel('Userstatus');
        $userstatus_id = $this->Userstatus->DisplayStatus();
        $this->set("userstatus_id", $userstatus_id);
	}
	
	function Index()
	{
		$this->set('child_code','user_list');
		$this->Session->delete('Search');
		$this->Session->delete('CondSearch.User');
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DISPLAY STATUS
		$this->loadModel('Userstatus');
        $userstatus_id = $this->Userstatus->DisplayStatus();
        $this->set("userstatus_id", $userstatus_id);
	}
	
	function ListItem($liststatus="all")
	{
		$this->layout	=	"ajax";
		$viewpage				=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order					=	array('User.id DESC');
		$this->set("liststatus", $liststatus);
		
		//DISPLAY USER STATUS ID
		$this->loadModel('Userstatus');
		
		if($liststatus!="all")
		{
			$combine_status	=	array(1=>array(-2),0=>array(1),-1=>array(1,-2,-1),-2=>array(1));
			$cond	=	 array("Userstatus.id" => $combine_status[$liststatus]);
			$userstatus_id = $this->Userstatus->find('list',array('conditions'=>$cond,"order"=>array("name ASC")));
			$this->set("userstatus_id", $userstatus_id);
		}
		
		//DEFINE FIELDS
		$fields				=	array(
									'User.*',
									'Profile.*',
									'Usertype.name',
									'Userstatus.name',
									'Province.name',
									'Province.province'
								);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('CondSearch.User');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'User.email LIKE'		=> "%$val%",
											'Profile.fullname LIKE'	=> "%$val%",
											'Profile.address LIKE'	=> "%$val%"
										)
									);
				
				}
			}
			$OR['OR'][]			=	"MATCH (User.email, Profile.fullname, Profile.address) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			
			array_push($fields,"MATCH (User.email, Profile.fullname, Profile.address) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("CondSearch.User",$cond_search);
			$order			=	array('score DESC','User.created DESC');
		}
		
		//DEFINE FILTERING
		$cond_search	=	array();
		if(!empty($this->data))
		{
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["User.id"]		=	$this->data['Search']['id'];
			}
			if(!empty($this->data['Search']['email']))
			{
				$cond_search["User.email LIKE "]		=	"%".str_replace(" ","",$this->data['Search']['email'])."%";
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
					
					$cond_search["User.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
					$cond_search["User.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				
			}
			
			if(!empty($this->data['Search']['fullname']))
			{
				$cond_search["Profile.fullname LIKE "]		=	"%".$this->data['Search']['fullname']."%";
			}
			
			if(!empty($this->data['Search']['gender']))
			{
				$cond_search["Profile.gender"]				=	$this->data['Search']['gender'];
			}
			
			if(!empty($this->data['Search']['address']))
			{
				$cond_search["Profile.address  LIKE "]				=	"%".$this->data['Search']['address']."%";
			}
			
			if(!empty($this->data['Search']['province_id']))
			{
				$cond_search['Profile.province_id']			=	$this->data['Search']['province_id'];
			}
			
			if(!empty($this->data['Search']['city_id']))
			{
				$cond_search['Profile.city_id']				=	$this->data['Search']['city_id'];
			}
			
			if(!empty($this->data['Search']['phone']))
			{
				$cond_search["Profile.phone  LIKE "]				=	"%".$this->data['Search']['phone']."%";
			}
			
			if(!empty($this->data['Search']['fax']))
			{
				$cond_search["Profile.fax  LIKE "]					=	"%".$this->data['Search']['fax']."%";
			}
			
			if(!empty($this->data['Search']['point_from']) && empty($this->data['Search']['point_to']))
			{
				$cond_search["User.points >= "]		=	$this->data['Search']['point_from'];
			}
			
			if(empty($this->data['Search']['point_from']) && !empty($this->data['Search']['point_to']))
			{
				$cond_search["User.points <= "]		=	$this->data['Search']['point_to'];
			}
			
			if(!empty($this->data['Search']['point_from']) && !empty($this->data['Search']['point_to']))
			{
				$point_from	=	$this->data['Search']['point_from'];
				$point_to		=	$this->data['Search']['point_to'];
				if($this->data['Search']['point_to'] < $this->data['Search']['point_from'])
				{
					$point_to		=	$this->data['Search']['point_from'];
					$point_from	=	$this->data['Search']['point_to'];
				}
				$cond_search["User.points BETWEEN ? AND ?"]		=	array($point_from,$point_to);
			}
			
			if(!empty($this->data['Search']['status']))
			{
				$cond_search['User.userstatus_id']				=	$this->data['Search']['status'];
			}
			
			$this->Session->write("CondSearch.User",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.User');
			unset($this->data);
		}
		
		
		//DEFINE FILTERING
		$this->User->bindModel(
                array('hasOne' => array(
						'Province' => array(
							'className' 	=> 'Province',
							'foreignKey' 	=> false,
							'conditions'	=>	'Profile.city_id = Province.id'
						)
			)), false
        );
		
		$cond_search		=	array();
		
		$filter_paginate	=	($liststatus=="all") ? array('User.userstatus_id >' => -10) : array('User.userstatus_id' => $liststatus);
		$this->paginate	=	array(
			'User'	=>	array(
				'limit'	=> $viewpage,
				'order'	=> $order,
				'group'	=> array('User.id'),
				'fields'	=>	$fields
			)
		);
		
		$ses_cond		= $this->Session->read("CondSearch.User");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('User',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['User']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['User']['pageCount'];
		}
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
		
	}
	
	function MessageBlocked($user_id)
	{
		$this->layout	=	"ajax";
		$this->set(compact('user_id'));
	}
	function MessageSuspend($user_id)
	{
		$this->layout	=	"ajax";
		$this->set(compact('user_id'));
	}
	function SelectAll($liststatus="all")
	{
		$this->layout		=	"json";
		
		$this->User->bindModel(
                array(
					'hasOne' => array(
						'Province' => array(
							'className' 	=> 'Province',
							'foreignKey' 	=> false,
							'conditions'	=>	'Profile.city_id = Province.id'
						)
					)
				), false
        );
		
		$filter_paginate	=	($liststatus=="all") ? array('User.userstatus_id >' => -10) : array('User.userstatus_id' => $liststatus);
		$ses_cond			= 	$this->Session->read("CondSearch.User");
		$cond_search		= 	isset($ses_cond) ? $ses_cond : array();
		$data				= 	$this->User->find('all',array(
									'conditions'	=>	array_merge($filter_paginate,$cond_search),
									'fields'		=>	array('User.id'),
									'group'	=>	array('User.id')
								));
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	function ResendVerification($user_id)
	{
		$this->layout	=	"ajax";
		$data			= 	$this->User->findById($user_id);
		
		if($data==false)
		{
			$this->render("resend_verification_failed");
			return;
		}
		
		if($data)
		{
			//SEND VERIFICATION CODES
			$vcode	= $this->User->getValidation(trim($data['User']['email']));
			App::import('vendor', 'encryption_class');
			$encrypt	= new encryption;
			$param		= $encrypt->my_encrypt($user_id . "|" . $vcode);
			$link 		= $this->settings['site_url'] . "Users/Verification/param:" . $param;
			
			$search 	= array('[logo_url]', '[username]', '[site_name]', '[link]', '[site_url]');
			$replace 	= array($this->settings['logo_url'], $data['Profile']['fullname'], $this->settings['site_name'], $link,$this->settings['site_url']);
			$send		=	$this->Action->EmailSend('regver', $data['User']['email'], $search, $replace,"","","User",$user_id);
			$this->set("email",$data['User']['email']);
			
			if($send<1)
			{
				$this->render("resend_verification_failed");
				return;
			}
		}
	}
	
	function Add($id=0)
	{
		$this->set('child_code','user_list');
		
		if($id!=0)
		{
			//CHECK USER DETAIL
			$this->loadModel('User');
			$data	=	$this->User->find('first',array('conditions'=>array('User.userstatus_id > ' => -10,'User.id' => $id )));
			$this->set("data",$data);
			
			//DEFINE EXTENDED PHONE
			$this->loadModel('ExtendedPhone');
			$ext_phone	=	$this->ExtendedPhone->find("all",array(
								'conditions'	=>	array(
									'ExtendedPhone.user_id'	=>	$id,
									'ExtendedPhone.type'	=>	1
								),
								'order'	=>	array('ExtendedPhone.id ASC')
							));
			$this->set(compact("ext_phone"));
		}
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DISPLAY ADMIN TYPE ID
		$this->loadModel('Admintype');
		$admintype_id = $this->Admintype->find('list',array(
							'conditions'	=>	array(
								'Admintype.id'	=>	array(1,3)
							)
						));
        $this->set("admintype_id", $admintype_id);
		
		//DISPLAY USER STATUS ID
		$this->loadModel('Userstatus');
		$combine_status	=	array(1=>array(1,-2),0=>array(1,0),-1=>array(1,-2,-1),-2=>array(1,-2));
		$cond	=	($id < 1) ? array("Userstatus.id" => array(0,1)) : array("Userstatus.id" => $combine_status[$data['User']['userstatus_id']]);
		$userstatus_id = $this->Userstatus->find('list',array('conditions'=>$cond,"order"=>array("name ASC")));
        $this->set("userstatus_id", $userstatus_id);
	}
	
	function AddUser()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$err			=	array();
		App::import('Sanitize');

		if(!empty($this->data))
		{
			//LOAD MODEL
			$this->loadModel('User');
			
			$this->User->set($this->data);
			$this->User->InitiateValidate();
			$error	=	$this->User->InvalidFields();
			
			//EXTENDER PHONE
			if(!empty($this->data['ExtendedPhone']))
			{
				$invalidBookFields = array();
				$this->loadModel('ExtendedPhone');
				foreach($this->data['ExtendedPhone'] as $index => $ExtendedPhone)
				{
					$data = array('ExtendedPhone' => $ExtendedPhone);
					$data['ExtendedPhone']['phone']	=	trim($data['ExtendedPhone']['phone']);
					$this->ExtendedPhone->set($data);
					if (!$this->ExtendedPhone->validates())
					{
						$invalidBookFields[$index] = $this->ExtendedPhone->invalidFields();
						$err[]	=	array("key"=>"phone".$index,"status"=>"false","value" => $this->General->getArrayFirstIndex($this->ExtendedPhone->invalidFields()));
					}
					elseif(empty($ExtendedPhone['phone']))
					{
						$err[]	=	array("key"=>"phone".$index,"status"=>"blank","value"=>"");
					}
					elseif($this->ExtendedPhone->validates())
					{
						$err[]	=	array("key"=>"phone".$index,"status"=>"true","value"=>"");
					}
				}
			}
			
			if(empty($error)  && empty($invalidBookFields) )
			{
				//SAVE USER
				$tmp_password					=	$this->data['User']['password'];
				$this->data['User']['password'] = md5($this->data['User']['password']);
				$user		=	$this->User->save($this->data,false);
				$user_id	=	$this->User->getLastInsertId();

				//SAVE PROFILE
				$this->loadModel('Profile');
				$this->data['User']['fullname']		=	Sanitize::html($this->data['User']['fullname']);
				$this->data['User']['address']		=	Sanitize::html($this->data['User']['address']);
				
				$profile	=	$this->Profile->saveAll(
									array(
										'user_id'		=>	$user_id,
										'fullname'		=>	$this->data['User']['fullname'],
										'address'		=>	$this->data['User']['address'],
										'province_id'	=>	$this->data['User']['province'],
										'city_id'		=>	$this->data['User']['city'],
										'lat'			=>	$this->data['User']['lat'],
										'lng'			=>	$this->data['User']['lng'],
										'phone'			=>	$this->data['User']['phone'],
									)
								);
				
				//SAVE Company
				if($this->data['User']['usertype_id']==2)
				{
					$this->loadModel('Company');
					$this->data['User']['cname']		=	Sanitize::html($this->data['User']['cname']);
					
					$company	=	$this->Company->saveAll(
										array(
											'user_id'		=>	$user_id,
											'name'			=>	$this->data['User']['cname'],
											'address'		=>	$this->data['User']['address'],
											'province_id'	=>	$this->data['User']['province'],
											'city_id'		=>	$this->data['User']['city'],
											'phone'			=>	$this->data['User']['phone'],
										)
									);
					
				}
				
				$vcode	= $this->User->getValidation(trim($this->data['User']['email']));
				$this->loadModel('Userstatus');
				$DetailStatus	=	$this->Userstatus->findById($this->data['User']['userstatus_id']);
				
				if($this->data['User']['userstatus_id']==0)
				{
					//SEND VERIFICATION CODES
					App::import('vendor', 'encryption_class');
					$encrypt	= new encryption;
					$param		= $encrypt->my_encrypt($user_id . "|" . $vcode);
					$link 		= $this->settings['site_url'] . "Users/Verification/param:" . $param;
					
					$search 	= array('[logo_url]', '[username]', '[site_name]', '[link]', '[site_url]');
					$replace 	= array($this->settings['logo_url'], $this->data['User']['fullname'], $this->settings['site_name'], $link,$this->settings['site_url']);
					$this->Action->EmailSend('regver', $this->data['User']['email'], $search, $replace);
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_add_user", array('[username]','[email]','[status]'), array($this->profile['Profile']['fullname'],trim($this->data['User']['email']),$DetailStatus['Userstatus']['name']), array("Anda",trim($this->data['User']['email']),$DetailStatus['Userstatus']['name']) );
					$this->Action->saveAdminLog($this->profile['User']['id']);
					
				}
				elseif($this->data['User']['userstatus_id']==1)
				{
					//UPDATE USER
					$this->User->updateAll(
						array(
							'userstatus_id'	=>	"'1'",
							'activated'		=>	"'".date("Y-m-d H:i:s")."'"
						),
						array(
							'User.id'		=>$user_id
						)
					);
					
					//DELETE ALL USER WITH SAME EMAIL
					$this->User->updateAll(
						array(
							'userstatus_id'			=>	"'-10'"
						),
						array(
							'User.email'			=>	trim($this->data['User']['email']),
							'User.userstatus_id'	=>	0
						)
					);
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_add_user", array('[username]','[email]','[status]'), array($this->profile['Profile']['fullname'],trim($this->data['User']['email']),$DetailStatus['Userstatus']['name']), array("Anda",trim($this->data['User']['email']),$DetailStatus['Userstatus']['name']) );
					$this->Action->saveAdminLog($this->profile['User']['id']);
					
					//SAVE USER ACTIONS
					$text = $this->Action->generateHTML("register", array('[some]','[site_name]'), array($this->data['User']['fullname'],$this->settings['site_name']), array("Anda",$this->settings['site_name']));
					$this->Action->save($user_id);
					
					
					//SEND EMAIL TO USER
					$find		=	$this->User->find('first',array(
										'conditions'	=>	array(
											'User.id'	=>	$user_id
										)
									));
					
					
					$emailsetting_name	=	"member_approval";
					$logo_url			=	$this->settings['logo_url'];
					$fullname			=	$find['Profile']['fullname'];
					$username			=	$this->data["User"]["email"];
					$password			=	$tmp_password;
					
					$site_name			=	$this->settings['site_name'];
					$link_login			=	$this->settings['site_url']."Users/Login";
					$link_profile		=	$this->settings['site_url']."Profil/DetailProfile/".$user_id;
					$link_edit			=	$this->settings['site_url']."Cpanel/UpdateProfile";
					$link_view			=	$this->settings['site_url']."DaftarMotor/all_categories/all_cities/motor_dijual.html";
					$link_c_password 	=	$this->settings['site_url']."Cpanel/ChangePassword";
					
					$cms_url			=	$this->settings['cms_url'];
					$link_add			=	$this->settings['site_url']."Cpanel/AddProduct";
					$site_url			=	$this->settings['site_url'];
					$email				=	$find['User']['email'];
					$created			=	date("d-M-Y H:i:s",strtotime($find['User']['created']));
					$address			=	$find['Profile']['address'];
					$phone				=	$find['Profile']['phone'];
					$type				=	($find['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
					$search 			=	array('[logo_url]', '[fullname]', '[username]', '[password]','[site_name]','[link_login]','[link_profile]','[link_edit]','[link_view]','[link_c_password]','[cms_url]','[link_add]','[site_url]' ,'[email]', '[created]','[address]','[phone]','[type]');
					$replace 			=	array($logo_url, $fullname, $username,$password,$site_name,$link_login,$link_profile,$link_edit,$link_view,$link_c_password,$cms_url,$link_add,$site_url ,$email, $created,$address,$phone,$type);
					$this->Action->EmailSend($emailsetting_name, $find['User']['email'], $search, $replace,$searchSub=array(),$replaceSub=array(),'User',$user_id);
					
					
					//EMAIL TO ADMIN
					$fullname	=	$find['Profile']['fullname'];
					$email		=	$find['User']['email'];
					$address	=	$find['Profile']['address'];
					$type		=	$find['Usertype']['name'];
					$date		=	date("d-M-Y",strtotime($find['User']['activated']));
					$search 	=	array('[fullname]', '[email]', '[address]', '[type]', '[date]','[site_name]','[site_url]');
					$replace 	=	array($fullname,$email,$address,$type,$date,$this->settings['site_name'],$this->settings['site_url']);
					$this->Action->EmailSend('admin_alert_user_register', $this->settings['admin_mail'], $search, $replace,$searchSub=array(),$replaceSub=array(),'User',$user_id);
				}
				
				//SAVE IMAGES
				if(!empty($this->data['User']['photo']['name']))
				{
					$cnt_user_dir		=	$this->settings['path_content']."User/";
					$cnt_userid_dir		=	$cnt_user_dir.$user_id."/";
					$info				=	pathinfo($this->data['User']['photo']['name']);
					$destination		=	$cnt_userid_dir.$user_id.".".$info['extension'];
					if(!is_dir($cnt_user_dir)) mkdir($cnt_user_dir,0777);
					if(!is_dir($cnt_userid_dir)) mkdir($cnt_userid_dir,0777);
					copy($this->data['User']['photo']['tmp_name'],$destination);
				}
				$out	=	array("status"=>true,"error"=>(!empty($this->data['User']['id']))?"Data telah terupdate.":"User telah di tambahkan." );
				
				//UPDATE EXTENDED PHONE
				if(!empty($this->data['ExtendedPhone']))
				{
					foreach($this->data['ExtendedPhone'] as $k => $v)
					{
						$this->ExtendedPhone->create();
						$save	=	$this->ExtendedPhone->saveAll(
										array(
											'phone'			=>	str_replace(" ","",trim($v['phone'])),
											'user_id'		=> $user_id
										)
									);
					}
				}
			}
			else
			{
				foreach($this->data['User'] as $k=>$v)
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
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function EditUser()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$err			=	array();
	
		App::import('Sanitize');
		if(!empty($this->data))
		{
			//LOAD MODEL
			$this->loadModel('User');
			$this->data['User']['email']			=	trim($this->data['User']['email']);
			$this->User->set($this->data);
			$this->User->InitiateValidate();
			$error	=	$this->User->InvalidFields();
			
			//EXTENDER PHONE
			$invalidBookFields = array();
			$this->loadModel('ExtendedPhone');
			foreach($this->data['ExtendedPhone'] as $index => $ExtendedPhone)
			{
				$data = array('ExtendedPhone' => $ExtendedPhone);
				$data['ExtendedPhone']['phone']	=	trim($data['ExtendedPhone']['phone']);
				$this->ExtendedPhone->set($data);
				if (!$this->ExtendedPhone->validates())
				{
					$invalidBookFields[$index] = $this->ExtendedPhone->invalidFields();
					$err[]	=	array("key"=>"phone".$index,"status"=>"false","value" => $this->General->getArrayFirstIndex($this->ExtendedPhone->invalidFields()));
				}
				elseif(empty($ExtendedPhone['phone']))
				{
					$err[]	=	array("key"=>"phone".$index,"status"=>"blank","value"=>"");
				}
				elseif($this->ExtendedPhone->validates())
				{
					$err[]	=	array("key"=>"phone".$index,"status"=>"true","value"=>"");
				}
			}
			
			if(empty($error) && empty($invalidBookFields) )
			{
				//SAVE USER
				$user_id	=	$this->data['User']['id'];
				$user_before=	$this->User->findById($user_id);
				$save		=	$this->User->updateAll(
									array(
										'User.usertype_id'	=>	$this->data['User']['usertype_id'],
										'User.admintype_id'	=>	$this->data['User']['admintype_id']
									),
									array(
										'User.id'			=>	$user_id
									)
								);
				$user		=	$this->User->findById($user_id);
				
				//SAVE PROFILE
				$this->loadModel('Profile');
				$this->data['User']['fullname']		=	Sanitize::html($this->data['User']['fullname']);
				$this->data['User']['address']		=	Sanitize::html($this->data['User']['address']);
				
				
				$profile	=	$this->Profile->saveAll(
									array(
										'id'			=>	$user['Profile']['id'],
										'user_id'		=>	$user_id,
										'fullname'		=>	$this->data['User']['fullname'],
										'address'		=>	$this->data['User']['address'],
										'province_id'	=>	$this->data['User']['province'],
										'city_id'		=>	$this->data['User']['city'],
										'lat'			=>	$this->data['User']['lat'],
										'lng'			=>	$this->data['User']['lng'],
										'phone'			=>	$this->data['User']['phone'],
										'fax'			=>	!empty($this->data['User']['fax']) ? trim($this->data['User']['fax']) : NULL,
										'gender'		=>	!empty($this->data['User']['gender']) ? $this->data['User']['gender'] : NULL
									)
								);
				
				//SAVE Company
				$this->loadModel('Company');
				if($this->data['User']['usertype_id']==2)
				{
					
					$this->data['User']['cname']		=	Sanitize::html($this->data['User']['cname']);
					$company							=	$this->Company->saveAll(
																array(
																	'id'				=>	!empty($user['Company']['id']) ? $user['Company']['id'] : NULL,
																	'user_id'			=>	$user_id,
																	'name'				=>	$this->data['User']['cname'],
																	'address'			=>	$this->data['User']['address'],
																	'province_id'		=>	$this->data['User']['province'],
																	'city_id'			=>	$this->data['User']['city'],
																	'phone'				=>	$this->data['User']['phone'],
																	'companystatus_id'	=>	$this->data['User']['userstatus_id']
																)
															);
					
				}
				else
				{
					$this->data['User']['cname']		=	Sanitize::html($this->data['User']['cname']);
					$company							=	$this->Company->saveAll(
																array(
																	'id'				=>	!empty($user['Company']['id']) ? $user['Company']['id'] : NULL,
																	'companystatus_id'	=>	-10
																)
															);
				}
				
				//SAVE IMAGES
				if(!empty($this->data['User']['photo']['name']))
				{
					$cnt_user_dir		=	$this->settings['path_content']."User/";
					$cnt_userid_dir		=	$cnt_user_dir.$user_id."/";
					$info				=	pathinfo($this->data['User']['photo']['name']);
					$destination		=	$cnt_userid_dir.$user_id.".".$info['extension'];
					if(!is_dir($cnt_user_dir)) mkdir($cnt_user_dir,0777);
					if(!is_dir($cnt_userid_dir)) mkdir($cnt_userid_dir,0777);
					copy($this->data['User']['photo']['tmp_name'],$destination);
				}
				
				//CHECK IF USER ADD POINT
				if($this->data['User']['is_add_point']=="1")
				{
					$point	=	$this->data['User']['add_point'];
					
					//SAVE USER ACTIONS
					$text = $this->Action->generateHTML("user_add_point", array('[username]','[site_name]'), array($this->data['User']['fullname'],$this->settings['site_name']), array("Anda",$this->settings['site_name']));
					$this->Action->save($user_id,array('userValue'=>$point));
					
					//UPADATE USER
					$this->User->updateAll(
						array(
							"points"	=>	"points+".$point
						),
						array(
							"User.id"	=>	$user_id
						)
					);
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_add_point", array('[username]','[admin_name]'), array($this->data['User']['fullname'],$this->profile['Profile']['fullname']), array($this->data['User']['fullname'],"Anda") );
					$this->Action->saveAdminLog($this->profile['User']['id']);
					
				}
				
				//CHECK IF USER DECREASE POINT
				if($this->data['User']['is_decrease_point']=="1")
				{
					$point	=	$this->data['User']['decrease_point'];
					
					//SAVE USER ACTIONS
					$text = $this->Action->generateHTML("user_decrease_point", array('[username]','[site_name]'), array($this->data['User']['fullname'],$this->settings['site_name']), array("Anda",$this->settings['site_name']));
					$this->Action->save($user_id,array('userValue'=>(-$point)));
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_decrease_point", array('[username]','[admin_name]'), array($this->data['User']['fullname'],$this->profile['Profile']['fullname']), array($this->data['User']['fullname'],"Anda") );
					$this->Action->saveAdminLog($this->profile['User']['id']);
					
				}
				
				//UPDATE EXTENDED PHONE
				$this->loadModel('ExtendedPhone');
				$delete	=	$this->ExtendedPhone->deleteAll(array('ExtendedPhone.user_id'=>$user_id,'ExtendedPhone.type'=>1));
				
				foreach($this->data['ExtendedPhone'] as $k => $v)
				{
					$this->ExtendedPhone->create();
					$save	=	$this->ExtendedPhone->saveAll(
									array(
										'phone'			=>	str_replace(" ","",trim($v['phone'])),
										'user_id'		=>  $user_id)
								);
				}
				
				
				//CHECK UPDATE STATUS
				if($user_before['User']['userstatus_id']==0 && $this->data['User']['userstatus_id']==1)
				{
					$active_date		=	date("Y-m-d H:i:s");
					//UPDATE USER
					$this->User->updateAll(
						array(
							'userstatus_id'	=>	"'1'",
							'activated'		=>	"'".$active_date."'"
						),
						array(
							'User.id'		=>	$user_id
						)
					);
					
					//DELETE ALL USER WITH SAME EMAIL
					$this->User->updateAll(
						array(
							'userstatus_id'			=>	"'-10'"
						),
						array(
							'User.email'			=>	trim($this->data['User']['email']),
							'User.userstatus_id'	=>	0
						)
					);
					
					//SAVE USER ACTIONS
					$text = $this->Action->generateHTML("register", array('[some]','[site_name]'), array($this->data['User']['fullname'],$this->settings['site_name']), array("Anda",$this->settings['site_name']));
					$this->Action->save($user_id);
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_deactive_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_id), array("Anda",$user_id) );
					$this->Action->saveAdminLog($this->profile['User']['id']);
			
					
					//SEND EMAIL TO USER
					$detail		=	$this->User->find('first',array(
										'conditions'	=>	array(
											'User.id'		=> $user_id
										)
									));
					
					$logo_url		=	$this->settings['logo_url'];
					$fullname		=	$detail['Profile']['fullname'];
					$site_name		=	$this->settings['site_name'];
					$link_login		=	$this->settings['site_url']."Users/Login";
					$link_profile	=	$this->settings['site_url']."Users/Profile";
					$link_edit		=	$this->settings['site_url']."Cpanel/UpdateProfile";
					$cms_url		=	$this->settings['cms_url'];
					$link_add		=	$this->settings['site_url']."Cpanel/AddProduct";
					$site_url		=	$this->settings['site_url'];
					$email			=	$detail['User']['email'];
					$search 		=	array('[logo_url]', '[fullname]', '[site_name]','[link_login]','[link_profile]','[link_edit]','[cms_url]','[link_add]','[site_url]');
					$replace 		=	array($logo_url, $fullname, $site_name,$link_login,$link_profile,$link_edit,$cms_url,$link_add,$site_url);
					
					$this->Action->EmailSend('member_approval', $detail['User']['email'], $search, $replace,$searchSub=array(),$replaceSub=array(),'User',$user_id);
					
				}
				elseif($user_before['User']['userstatus_id']==1 && $this->data['User']['userstatus_id']==0)//INI UNTUK RUBAH EMAIL USER
				{
					if($user_before['User']['email'] != $this->data['User']['email'])
					{
						//SEND EMAIL
						$this->loadModel('FPToken');
						$request_mail	= $this->data['User']['email'];
						$oldemail		= $user_before['User']['email'];
						$token			= $this->FPToken->GetToken($request_mail,$user_before['User']['id']);
						$link 			= $this->settings['site_url'] . 'Cpanel/ChangeEmail/token:' . $token ."/email:" .$request_mail;
						$imgsrc 		= $this->settings['logo_url'];
						$search 		= array('[logo_url]','[fullname]','[email]', '[request_email]','[link]','[site_name]','[site_url]');
						$replace 		= array($this->settings['logo_url'],$user_before['Profile']['fullname'],$oldemail,$request_mail, $link, $this->settings['site_name'],$this->settings['site_url']);
						$this->Action->EmailSend('change_email', $request_mail, $search, $replace);	
					}
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_cahngestatus_waiting_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_id), array("Anda",$user_id) );
					$this->Action->saveAdminLog($this->profile['User']['id']);
			
				}
				elseif($user_before['User']['userstatus_id']==1 && $this->data['User']['userstatus_id']==-2)
				{
					//UPDATE USER
					$this->User->updateAll(
						array(
							'userstatus_id'	=>	"'-2'"
						),
						array(
							'User.id'		=>	$user_id
						)
					);

					//SEND EMAIL TO USER
					if(!empty($this->data['User']['pesan']))
					{
						$html		=	str_replace("&quot;","",$this->data['User']['pesan']);
						$send		=	$this->Action->EmailSend("admin_user_suspend",$user_before['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'User',$user_before['User']['id'],$html);
					}
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_suspend_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_id), array("Anda",$user_id));
					$this->Action->saveAdminLog($this->profile['User']['id']);
				}
				elseif($user_before['User']['userstatus_id']==-2 && $this->data['User']['userstatus_id']==1)
				{
					//UPDATE USER
					$this->User->updateAll(
						array(
							'userstatus_id'	=>	"'1'"
						),
						array(
							'User.id'		=>	$user_id
						)
					);
					
					//SEND EMAIL TO USER
					$logo_url		=	$this->settings['logo_url'];
					$fullname		=	$user_before['Profile']['fullname'];
					$site_name		=	$this->settings['site_name'];
					$link_login		=	$this->settings['site_url']."Users/Login";
					$link_profile	=	$this->settings['site_url']."Users/Profile";
					$link_edit		=	$this->settings['site_url']."Cpanel/UpdateProfile";
					$cms_url		=	$this->settings['cms_url'];
					$link_add		=	$this->settings['site_url']."Cpanel/AddProduct";
					$email			=	$user_before['User']['email'];
					$created		=	date("d-M-Y H:i:s",strtotime($user_before['User']['created']));
					$address		=	$user_before['Profile']['address'];
					$phone			=	$user_before['Profile']['phone'];
					$type			=	($user_before['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
					
					$search 		= 	array('[logo_url]','[fullname]','[site_name]', '[link_login]','[link_profile]','[link_edit]','[cms_url]','[link_add]','[email]','[created]','[address]','[phone]','[type]');
					
					$replace 		= 	array($logo_url,$fullname,$site_name, $link_login,$link_profile,$link_edit,$cms_url,$link_add,$email,$created,$address,$phone,$type);
					$this->Action->EmailSend('admin_deactive_user',$user_before['User']['email'], $search, $replace);	
					
					//SAVE ADMIN ACTIONS
					$text 			=	$this->Action->generateHTML("admin_deactive_aftersuspend", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_id), array("Anda",$user_id));
					$this->Action->saveAdminLog($this->profile['User']['id']);
				}
				$out	=	array("status"=>true,"error"=>"Data user telah terupdate");
			}
			else
			{
				foreach($this->data['User'] as $k=>$v)
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
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function Block()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terblokir :</b><br><br>";
		$data_delete		=	"<b>Data telah diblokir :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $user_id)
		{
			$detail				=	$this->User->findById($user_id);
			if($detail)
			{
				$delete				=	$this->User->updateAll(
											array(
												'User.userstatus_id'		=>	-1,
												'User.modified'				=>	"'".date("Y-m-d H:i:s")."'"
											),
											array(
												'User.id'					=>	$user_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". User : ID-".$detail['User']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$user_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". User : ID-".$detail['User']['id']."<br>";
					}
					
					if(!empty($_POST['msg_editing_required']))
					{
						//SEND EMAIL
						$html		=	$_POST['msg_editing_required'];
						$send		=	$this->Action->EmailSend("admin_user_blocked",$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'User',$detail['User']['id'],$html);
					}
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.".  : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_blocked_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function BlockMulti()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terblokir :</b><br><br>";
		$data_delete		=	"<b>Data telah diblokir :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		$message			=	$_POST['msg_editing_required'];
		
		foreach($id as $user_id)
		{
			$detail				=	$this->User->findById($user_id);
			if($detail)
			{
				$delete				=	$this->User->updateAll(
											array(
												'User.userstatus_id'		=>	-1,
												'User.modified'				=>	"'".date("Y-m-d H:i:s")."'"
											),
											array(
												'User.id'				=>	$user_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". User : ID-".$detail['User']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$user_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". User : ID-".$detail['User']['id']."<br>";
					}
					
					if(!empty($_POST['msg_editing_required']))
					{
						//SEND EMAIL
						$html		=	$this->GetHtmlDeleted($detail,$message);
						$send		=	$this->Action->EmailSend("admin_user_blocked2",$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'User',$detail['User']['id'],$html);
					}
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". User : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_blocked_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function Suspend()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terblokir :</b><br><br>";
		$data_delete		=	"<b>Data telah diblokir :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		$message			=	$_POST['msg_editing_required'];
		foreach($id as $user_id)
		{
			$detail				=	$this->User->findById($user_id);
			if($detail)
			{
				$delete				=	$this->User->updateAll(
											array(
												'User.userstatus_id'		=>	-2,
												'User.modified'				=>	"'".date("Y-m-d H:i:s")."'"
											),
											array(
												'User.id'				=>	$user_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". User : ID-".$detail['User']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$user_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". User : ID-".$detail['User']['id']."<br>";
					}
					
					if(!empty($_POST['msg_editing_required']))
					{
						//SEND EMAIL
						$html		=	$_POST['msg_editing_required'];
						$send		=	$this->Action->EmailSend("admin_user_suspend",$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'User',$detail['User']['id'],$html);
					}
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". User : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_suspend_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function SuspendMulti()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terblokir :</b><br><br>";
		$data_delete		=	"<b>Data telah diblokir :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		$message			=	$_POST['msg_editing_required'];
		foreach($id as $user_id)
		{
			$detail				=	$this->User->findById($user_id);
			if($detail)
			{
				$delete				=	$this->User->updateAll(
											array(
												'User.userstatus_id'		=>	-2,
												'User.modified'				=>	"'".date("Y-m-d H:i:s")."'"
											),
											array(
												'User.id'				=>	$user_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". User : ID-".$detail['User']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$user_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". User : ID-".$detail['User']['id']."<br>";
					}
					
					if(!empty($_POST['msg_editing_required']))
					{
						//SEND EMAIL
						$html		=	$this->GetHtmlSuspend($detail,$message);
						$send		=	$this->Action->EmailSend("admin_user_suspend2",$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'User',$detail['User']['id'],$html);
					}
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". User : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_suspend_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function NotActive()
	{
		$this->layout		=	"json";
		$id					=	$this->data['User']['id'];
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"Maaf terdapat beberapa data yang tidak ter update :\n";
		$data_delete		=	"Data telah di update :\n";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $user_id)
		{
			
			$detail				=	$this->User->findById($user_id);
			$delete				=	$this->User->updateAll(
										array(
											'User.userstatus_id'	=>	-1
										),
										array(
											'User.id'				=>	$user_id
										)
									);
			
			//DELETE COMPANIES
			$this->loadModel('Company');
			$delete_companies	=	$this->Company->updateAll(
										array(
											'Company.companystatus_id'	=>	-1
										),
										array(
											'Company.user_id'			=>	$user_id
										)
									);
			$count++;
			if($delete==false)
			{
				$err++;
				$item_notdelete	.= $count.". User : ID-".$detail['User']['id']."\n";
			}
			else
			{
				$tr_id[]		=	$user_id;
				if(count($tr_id)<7)
				{
					$item_delete	.= $count.". User : ID-".$detail['User']['id']."\n";
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........\n........\n";
			$item_delete	.= $count.". User : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_nonactive_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}

	function Approve()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terupdate :</b><br><br>";
		$data_delete		=	"<b>Data telah diupdate :</b><br><br>";
		$message			=	"<b>Tidak ada data yang diupdate</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $user_id)
		{
			$detail				=	$this->User->findById($user_id);
			$find_same_email	=	$this->User->findByEmail($detail['User']['email'],array('conditions'=>array('User.userstatus_id' =>	1)));
			$update				= 	false;
			
			if(in_array($detail['User']['userstatus_id'],array(0,-2,1)) && $find_same_email==false)
			{
			
				$active_date		=	date("Y-m-d H:i:s");
				//UPDATE USER
				$update				=	$this->User->updateAll(
											array(
												'userstatus_id'	=>	"'1'",
												'activated'		=>	"'".$active_date."'"
											),
											array(
												'User.id'		=>	$user_id
											)
										);
			
				//DELETE ALL USER WITH SAME EMAIL
				$this->User->updateAll(
					array(
						'userstatus_id'			=>	"'-10'"
					),
					array(
						'User.email'			=>	trim($detail['User']['email']),
						'User.userstatus_id'	=>	0
					)
				);

				$count++;	
				if($update==false)
				{
					$err++;
					$item_notdelete	.= $count.". User : ID-".$detail['User']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$user_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". User : ID-".$detail['User']['id']."<br>";
					}
					
					//SAVE USER ACTIONS
					if($detail['User']['userstatus_id']==0)
					{
						$text = $this->Action->generateHTML("register", array('[some]','[site_name]'), array($detail['Profile']['fullname'],$this->settings['site_name']), array("Anda",$this->settings['site_name']));
						$this->Action->save($user_id);
					}
					
					//SEND EMAIL TO USER
					$emailsetting_name	=	($detail['User']['userstatus_id']==-2) ? "admin_deactive_user": "member_approval";
					$logo_url			=	$this->settings['logo_url'];
					$fullname			=	$detail['Profile']['fullname'];
					$site_name			=	$this->settings['site_name'];
					$link_login			=	$this->settings['site_url']."Users/Login";
					$link_profile		=	$this->settings['site_url']."Users/Profile";
					$link_edit			=	$this->settings['site_url']."Cpanel/UpdateProfile";
					$cms_url			=	$this->settings['cms_url'];
					$link_add			=	$this->settings['site_url']."Cpanel/AddProduct";
					$site_url			=	$this->settings['site_url'];
					$email				=	$detail['User']['email'];
					$created			=	date("d-M-Y H:i:s",strtotime($detail['User']['created']));
					$address			=	$detail['Profile']['address'];
					$phone				=	$detail['Profile']['phone'];
					$type				=	($detail['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
					$search 			=	array('[logo_url]', '[fullname]', '[site_name]','[link_login]','[link_profile]','[link_edit]','[cms_url]','[link_add]','[site_url]' ,'[email]', '[created]','[address]','[phone]','[type]');
					$replace 			=	array($logo_url, $fullname, $site_name,$link_login,$link_profile,$link_edit,$cms_url,$link_add,$site_url ,$email, $created,$address,$phone,$type);
					
					$this->Action->EmailSend($emailsetting_name, $detail['User']['email'], $search, $replace,$searchSub=array(),$replaceSub=array(),'User',$user_id);
				}
			}//ENIF(in_array($detail['User']['userstatus_id'],array(0,-1)) && $find_same_email==false)
		}//END FOREACH
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.".  : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$action_type	=	($detail['User']['userstatus_id']==-2) ? "admin_deactive_aftersuspend" : "admin_deactive_user";
			
			$text = $this->Action->generateHTML($action_type, array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
			$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		}
		
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}

	function Waiting()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa user yang tidak terkirim :</b><br><br>";
		$data_delete		=	"<b>Email telah dikirim :</b><br><br>";
		$message			=	"<b>Tidak ada email yang terkirim</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $user_id)
		{
			$detail				=	$this->User->findById($user_id);
			$send				=	0;
			$count++;
			if($detail['User']['userstatus_id']==0)
			{
				//SEND VERIFICATION CODES
				$vcode		= $this->User->getValidation($detail['User']['email']);
				App::import('vendor', 'encryption_class');
				$encrypt	= new encryption;
				$param		= $encrypt->my_encrypt($user_id . "|" . $vcode);
				$link 		= $this->settings['site_url'] . "Users/Verification/param:" . $param;
				
				$search 	= array('[logo_url]', '[username]', '[site_name]', '[link]', '[site_url]');
				$replace 	= array($this->settings['logo_url'], $detail['Profile']['fullname'], $this->settings['site_name'], $link,$this->settings['site_url']);
				$send	=	$this->Action->EmailSend('regver', $detail['User']['email'], $search, $replace);
			}
			
			if($send < 1)
			{
				$err++;
				$item_notdelete	.= $count.". User : ID-".$detail['User']['id']."<br>";
			}
			else
			{
				$tr_id[]		=	$user_id;
				if(count($tr_id)<7)
				{
					$item_delete	.= $count.". User : ID-".$detail['User']['id']."<br>";
				}
			}
		}//END FOREACH
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.".  : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text 			= 	$this->Action->generateHTML("admin_cahngestatus_waiting_user", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
			$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		}
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function GetUserStatusWaiting()
	{
		$this->layout	=	"json";
		
		//LOAD MODEL USER STATUS
		$this->loadModel("Userstatus");
		$data	=	$this->Userstatus->find('all',array(
						'conditions'	=>	array(
							'Userstatus.id'	=>	0
						),
						'order'	=>	array('Userstatus.name ASC')
					));
		$this->set("data",$data);
		$this->render(false);
	}
	
	function GetUserStatusEdit()
	{
		$this->layout	=	"json";
		
		//LOAD MODEL USER STATUS
		$this->loadModel("Userstatus");
		$data	=	$this->Userstatus->find('all',array(
						'conditions'	=>	array(
							'Userstatus.id > '	=>	-10
						),
						'order'	=>	array('Userstatus.name ASC')
					));
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	function GetAllUserStatus()
	{
		$this->layout	=	"json";
		
		//LOAD MODEL USER STATUS
		$this->loadModel("Userstatus");
		$data	=	$this->Userstatus->find('all',array(
						'order'	=>	array('Userstatus.name ASC')
					));
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	function UploadTmp()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$err			=	array();
		
		if(!empty($this->data))
		{
			$this->loadModel('User');
			$this->User->set($this->data);
			$this->User->ValidatePhoto();
			
			if($this->User->validates())
			{
				//GET VITUAL ID FOR USER
				$rand			=	$this->Action->GetRandomUser();
				
				//DEFINE NEEDED VARIABLE
				$ROOT			=	$this->settings['path_content'];
				$info 			= 	pathinfo($this->data['User']["photo"]['name']);
				$type			= 	strtolower($info['extension']);
				
				//GENERATE FOLDER
				$tmpuser		=	$ROOT."RandomUser/";
				if(!is_dir($tmpuser)) mkdir($tmpuser,0777);
				
				$tmpuser_id		=	$tmpuser.$rand."/";
				if(is_dir($tmpuser_id)) $this->General->RmDir($tmpuser_id);
				if(!is_dir($tmpuser_id)) mkdir($tmpuser_id,0777);
				
				$targetFile		=	$tmpuser_id.$rand.".".$type;
				
				//UPLOAD FILES
				$tempFile 				= 	$this->data['User']["photo"]['tmp_name'];
				$upload					=	move_uploaded_file($tempFile,$targetFile);
				$showimg				=	$this->settings['showimages_url']."?code=".$rand."&prefix=_prevthumb&content=RandomUser&w=120&h=120";
				
				$out					=	array("status" => true,"error" =>$rand,"name_file"=>$this->data['User']["photo"]['name']);
			}
			else
			{
				$error	=	$this->User->invalidFields();
				foreach($error as $k=>$v)
				{
					$err =	$v;
					break;
				}
				$out					=	array("status" => false,"error" =>$err,"name_file"=>$this->data['User']["photo"]['name']);
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function GetHtmlDeleted($user,$message)
	{
		App::import('vendor', 'encryption_class');
		
		$emailID		=	"admin_user_blocked2";
		$logo_url		=	$this->settings['logo_url'];
		$fullname		=	$user['Profile']['fullname'];
		$email			=	$user['User']['email'];
		$created		=	date("d-M-Y H:i:s",strtotime($user['User']['created']));
		
		$address		=	$user['Profile']['address'];
		$phone			=	$user['Profile']['phone'];
		$type			=	($user['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
		$vcode			=   $this->User->getValidation(trim($email));
		
		$encrypt		= 	new encryption;
		$param			= 	$encrypt->my_encrypt($user['User']['id'] . "|" . $vcode);
		$link 			= 	$this->settings['site_url'] . "Users/Verification/param:" . $param;
		
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		
		$search			=	array('[logo_url]','[fullname]','[message]','[email]','[created]','[address]','[phone]','[type]','[site_name]','[site_url]','[link]');
		
		$replace		=	array($logo_url,$fullname,$message,$email,$created,$address,$phone,$type,$site_name,$site_url,$link);
		
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	function GetHtmlSuspend($user,$message)
	{
		App::import('vendor', 'encryption_class');
		
		$emailID		=	"admin_user_suspend2";
		$logo_url		=	$this->settings['logo_url'];
		$fullname		=	$user['Profile']['fullname'];
		$email			=	$user['User']['email'];
		$created		=	date("d-M-Y H:i:s",strtotime($user['User']['created']));
		
		$address		=	$user['Profile']['address'];
		$phone			=	$user['Profile']['phone'];
		$type			=	($user['User']['usertype_id']==1) ? "Perorangan" : "Dealer/Perusahaan/Distributor";
		$vcode			=   $this->User->getValidation(trim($email));
		
		$encrypt		= 	new encryption;
		$param			= 	$encrypt->my_encrypt($user['User']['id'] . "|" . $vcode);
		$link 			= 	$this->settings['site_url'] . "Users/Verification/param:" . $param;
		
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		
		$search			=	array('[logo_url]','[fullname]','[message]','[email]','[created]','[address]','[phone]','[type]','[site_name]','[site_url]','[link]');
		
		$replace		=	array($logo_url,$fullname,$message,$email,$created,$address,$phone,$type,$site_name,$site_url,$link);
		
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
}
?>