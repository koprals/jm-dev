<?php
class UsersController extends AppController
{
	var $name	=	"Users";
	var $uses	=	null;
	var $components = array('Recaptcha.Captcha' => array(
           'private_key'	=> "6LcM_MQSAAAAADfgmPNQnZkP6s6gBk5T9vR2yymc",
           'public_key'		=> "6LcM_MQSAAAAAEfA5vaAIBkI4sWxnJ601AEj_tL-",
		   'config'			=>	array('theme'=>'white')
		   )
    );
	var $helpers = array('Recaptcha.CaptchaTool');
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function Register()
	{
		$this->layout = "login";
		
		//LOAD PROVINCE MODEL
        $this->loadModel('Province');
		$this->loadModel('RandomUser');
		$this->loadModel('Profile');
		
		
		$this->Cookie->delete('rand_user');
		
		//DISPLAY PROVINCE
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//GET VITUAL ID FOR USER
		$rand			=	$this->Action->GetRandomUser();
	}
	
	
	function ProcessRegister()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$err			=	array();
		App::import('Sanitize');

		if(!empty($this->data))
		{
			//LOAD MODEL
			$this->loadModel('User');
			$this->data['User']['max_photo_upload']	=	$this->settings['max_photo_upload'];
			$this->data['User']['captcha'] 		= $_POST['recaptcha_response_field'];
			$this->User->set($this->data);
			$this->User->InitiateValidate();
			$captcha	=	$this->Captcha->validate();
			$err_c		=	array();

			if (empty($_POST['recaptcha_response_field'])) {
                $err_c = array("captcha" => "Masukkan kode captcha.");
            } elseif (!$captcha) {
                $err_c = array("captcha" => "Kode captcha anda salah.");
            }
			
			$error	=	array_merge($this->User->InvalidFields(),$err_c);
			
			if(empty($error))
			{
				//SAVE USER
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
										'phone'			=>	$this->data['User']['phone'],
										'lat'			=>	$this->data['User']['lat'],
										'lng'			=>	$this->data['User']['lng']
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
											'companystatus_id'	=>	1
										)
									);
					
				}
				
				//SEND VERIFICATION CODES
				$vcode		= $this->User->getValidation(trim($this->data['User']['email']));
				App::import('vendor', 'encryption_class');
       			$encrypt	= new encryption;
				$param		= $encrypt->my_encrypt($user_id . "|" . $vcode);
				
				
				$link 		= $this->settings['site_url'] . "Users/Verification/param:" . $param;
				
				$search 	= array('[logo_url]', '[username]', '[site_name]', '[link]', '[site_url]');
                $replace 	= array($this->settings['logo_url'], $this->data['User']['fullname'], $this->settings['site_name'], $link,$this->settings['site_url']);
				$this->Action->EmailSend('regver', $this->data['User']['email'], $search, $replace,"","","User",$user_id);
				$this->loadModel('EmailLog');
				$this->Session->write("Email.email_log_id",$this->EmailLog->getLastInsertId());
				
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
				$out	=	array("status"=>true,"error"=>$this->settings['site_url'].'Users/SuccessRegister/');
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
	
	function SuccessRegister()
	{
		$this->layout = "login";			
	}
	
	function SeeEmailSuspend()
	{
		$this->layout	=	"ajax";
		
		//IMPORT ENCRYPTION CLASS
		App::import('vendor', 	'encryption_class');
		$encrypt 				= 	new encryption;
		
		if(isset($this->params['named']['emaillog_id']))
		{
			if(!empty($this->params['emaillog_id']))
			{
				$this->params['named']['emaillog_id']	= implode("/",array($this->params['named']['emaillog_id'],implode("/",$this->params['emaillog_id'])));	
			}
		}
		$emaillog_id					= 	$encrypt->my_decrypt($this->params['named']['emaillog_id']);
		
		//CHECK EMAIL LOG ID
		$this->loadModel('EmailLog');
		$data	=	$this->EmailLog->findById($emaillog_id);
		$this->set("html",$data['EmailLog']['text']);
	}
	
	function ResendVerification()
	{
		$this->layout	=	"ajax";
		$email_id		=	$this->Session->read('Email.email_log_id');
		$this->loadModel('EmailLog');
		
		//$ses_del	=	$this->Session->delete("send_counter");
		//$ses_del	=	$this->Session->delete("last_hit");
		
		
		//IF SESSION NOT FOUND
		if(empty($email_id))
		{
			$this->render("resend_verification_notfound");
			return;
		}
		
		//CHECK COUNTER SEND
		$data			=	$this->EmailLog->findById($email_id);
		$COUNTER		= 	$this->Session->read("send_counter." . $data['EmailLog']['to']);
		$COUNTER 		= 	empty($COUNTER) ? 0 : $COUNTER;
		$LAST_HIT 		= 	$this->Session->read("last_hit." .$data['EmailLog']['to']);
		$LAST_HIT 		= 	empty($LAST_HIT) ? time() : $LAST_HIT;
		
		$expired 		= 	mktime(date("H", $LAST_HIT) + 1, date("i", $LAST_HIT), date("s", $LAST_HIT), date("m", $LAST_HIT), date("d", $LAST_HIT), date("Y", $LAST_HIT));

		if (time() > $expired)
		{
			$this->Session->write("send_counter." . $data['EmailLog']['to'], 0);
			$this->Session->write("last_hit." . $data['EmailLog']['to'], time());
		}
		
		
		if($COUNTER>2)
		{
			$this->Session->delete('Email.email_log_id');
			$this->render("resend_verification_notfound");
			return;
		}
		
		$send	=	$this->Action->ResendEmailLog($email_id);
		$this->set("email",$data['EmailLog']['to']);
		if($send==false or $send<1)
		{
			$this->render("resend_verification_notfound");
			return;
		}
		else
		{
			 $this->Session->write("send_counter." . $data['EmailLog']['to'], ($COUNTER + 1));
			 $this->Session->write("last_hit." . $data['EmailLog']['to'], time());
		}
	}
	
	function ProcessResendVerification()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$err			=	array();

		if(!empty($this->data))
		{
			$email_id		=	$this->data['User']['email_resend'];
			
			$COUNTER		= 	$this->Session->read("send_counter." . $email_id);
			$COUNTER 		= 	empty($COUNTER) ? 0 : $COUNTER;
			$LAST_HIT 		= 	$this->Session->read("last_hit." . $email_id);
			$LAST_HIT 		= 	empty($LAST_HIT) ? time() : $LAST_HIT;
			
			$expired 		= 	mktime(date("H", $LAST_HIT) + 1, date("i", $LAST_HIT), date("s", $LAST_HIT), date("m", $LAST_HIT), date("d", $LAST_HIT), date("Y", $LAST_HIT));
	
			if (time() > $expired)
			{
				$this->Session->write("send_counter." . $email_id, 0);
				$this->Session->write("last_hit." . $email_id, time());
			}
			
			if($COUNTER>2)
			{
				$out			=	array("status"=>false,"error"=>array(array("key"=>"email_resend","status"=>"false","value"=>"Anda hanya diijinkan untuk mengirim email verifikasi sebanyak 3 kali.")));
				$this->set("data",$out);
				$this->Session->delete('Email.email_log_id');
				$this->render(false);
				return;
			}
			
			//LOAD MODEL
			$this->loadModel('User');
			$this->User->set($this->data);
			$this->User->InitiateResend();
			$error		=	$this->User->InvalidFields();

			if(empty($error))
			{
				
				$out			=	array("status"=>true,"error"=>"");
				$this->loadModel('EmailLog');
				$data	=	$this->EmailLog->findById($email_id);
				$send	=	$this->Action->ResendEmailLog2($email_id);
				
				if($send==false or $send<1)
				{
					$out			=	array("status"=>false,"error"=>array(array("key"=>"email_resend","status"=>"false","value"=>"Maaf email tidak terkirim, cobalah beberapa saat lagi")));
				}
				else
				{
					 $this->Session->write("send_counter." . $email_id, ($COUNTER + 1));
                     $this->Session->write("last_hit." . $email_id, time());
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
	
	function AvailableVendor()
	{
		$this->layout	=	"ajax";
		$this->loadModel("AvailableVendor");
		$data	=	$this->AvailableVendor->find("all",array(
						'order'	=>	array('AvailableVendor.name ASC')
					));
		$this->set('data',$data);
	}
	
	function Verification()
	{
		
		//IMPORT ENCRYPTION CLASS
		App::import('vendor', 	'encryption_class');
		$encrypt 				= 	new encryption;
		if(isset($this->params['named']['param']))
		{
			if(!empty($this->params['pass']))
			{
				$this->params['named']['param']	= implode("/",array($this->params['named']['param'],implode("/",$this->params['pass'])));	
			}
		}
		
		$param					= 	explode("|",$encrypt->my_decrypt(str_replace(" ","+",$this->params['named']['param'])));
		$user_id				=	$param[0];
		
		$this->Session->write('back_url',$this->settings['site_url'].'Users/Verification/param:'.$this->params['named']['param']);
		
		//LOAD MODEL USERS
		$this->loadModel('User');
		$this->loadModel('Company');
		
		if(empty($this->params['named']['param']))
		{
			$this->render('no_access');
			return;
		}
		
		//CHECK USER DETAIL
		$find			=	$this->User->findById($user_id);
		$fullname		=	(!empty($find['Profile']['fullname'])) ? $find['Profile']['fullname'] : $email;
		if($_GET['debug']=="aby")
		{	
			var_dump($param);
			exit;
		}
		$this->set('fullname',$fullname);
		
		if(!is_null($this->Cookie->read("userlogin")) && $this->user_id != $user_id)
		{
			$this->Cookie->delete('userlogin');
			$this->Session->delete('back_url');
			//$this->redirect($this->settings['site_url'].'Users/Verification/param:'.$this->params['named']['param']);
		}
		
		$this->set("data",$find);
		
		if($find['User']['userstatus_id'] == 1)
		{
			$cookie_rand	=	$this->Cookie->read('rand_user');
			$this->set('user_id',$find['User']['id']);
			$this->set('activated',$find['User']['activated']);
			$this->render('user_already_active');
			return;
		}
		
		if($find ==	false)
		{
			$this->render('data_not_found');
			return;		
		}
		
		
		$this->layout	=	"verification";
		
		//UPDATE USER
		$this->User->updateAll(
			array(
				'userstatus_id'	=>	"'1'",
				'activated'		=>	"'".date("Y-m-d H:i:s")."'",
				'last_login'	=>	"'".date("Y-m-d H:i:s")."'"
			),
			array(
				'User.id'		=> $find['User']['id']
			)
		);
			
		//DELETE ALL USER WITH SAME EMAIL
		$this->User->updateAll(
			array(
				'userstatus_id'			=>	"'-10'"
			),
			array(
				'User.email'			=>	$find['User']['email'],
				'User.userstatus_id'	=>	0
			)
		);
		
		$this->Company->updateAll(
			array(
				'companystatus_id'		=>	"'-10'"
			),
			array(
				'User.id'				=> $find['User']['id']
			)
		);
		
		//UPDATE RANDOM USER
		$this->loadModel('RandomUser');
		$cookie_rand	=	$this->Cookie->read('rand_user');
		$this->RandomUser->updateAll(
			array(
				'user_id'			=>	"'".$find['User']['id']."'"
			),
			array(
				'rand_id'			=>	$cookie_rand,
				"user_id IS NULL"
			)
		);
		
		//CREATE COOKIE
		$this->Cookie->write('userlogin',	$this->General->my_encrypt($find["User"]["id"]),false,"1 days",$this->settings['site_url']);
		
		//GET VITUAL ID FOR USER
		$this->Cookie->delete('rand_user');
		$rand	=	$this->Action->GetRandomUser();
		
		//SAVE USER ACTIONS
		$text = $this->Action->generateHTML("register", array('[some]','[site_name]'), array($find['Profile']['fullname'],$this->settings['site_name']), array("Anda",$this->settings['site_name']));
        $this->Action->save($find["User"]["id"]);
		
		$find		=	$this->User->findById($user_id);
		$fullname	=	$find['Profile']['fullname'];
		$email		=	$find['User']['email'];
		$address	=	$find['Profile']['address'];
		$type		=	$find['Usertype']['name'];
		$date		=	date("d-M-Y",strtotime($find['User']['activated']));
		$search 	=	array('[fullname]', '[email]', '[address]', '[type]', '[date]','[site_name]','[site_url]');
        $replace 	=	array($fullname,$email,$address,$type,$date,$this->settings['site_name'],$this->settings['site_url']);
		$this->Action->EmailSend('admin_alert_user_register', $this->settings['admin_mail'], $search, $replace);
		
		
		//LOAD MODEL VOUCHERS
		$this->loadModel('Voucher');
		$this->set('profile',$find);
		
	}
	
	
	function DeleteRandUser()
	{
		$this->Cookie->delete('rand_user');
		$session_back	=	$this->Session->read('back_url');
        $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
		$this->redirect($back_url);
		$this->autoRender	=	false;
	}
	
	function Login()
	{
		$this->layout = "login";
		$this->loadModel('User');
		$this->set("fByEmail","");
		$this->set("not",0);
		
		//LOAD MODEL RAND USER
		$this->loadModel('RandomUser');
		$rand			=	$this->Action->GetRandomUser();
		$rand_detail	=	$this->RandomUser->findByRandId($rand);
		if(!empty($rand_detail['User']['email']))
		{
			$fByEmail	=	$this->User->findByEmail($rand_detail['User']['email']);
			$rand_name	=	(empty($fByEmail['Profile']['id'])) ? $fByEmail['User']['email'] : $fByEmail['Profile']['fullname'];
			
			$this->set('rand_name',$rand_name);
			$this->set("fByEmail",$fByEmail);
			$this->set("not",1);
		}
		
		if($_GET['not']==1)
		{
			$this->Cookie->delete('rand_user');
			$cookie	=	$this->Cookie->read('rand_user');
			$this->redirect(array('controller'=>'Users','action'=>'Login'));
		}
		
		if(!empty($this->data))
		{
			//CHEK USER STATUS FIRST
			$chek_user	=	$this->User->CheckUserByEmail($this->data['User']['email_login']);
			if($chek_user)
			{
				if($chek_user['User']['userstatus_id'] == 0)
				{
					$this->redirect(array("controller"=>"Users","action"=>"WaitingEmailConfirm"));
				}
				elseif($chek_user['User']['userstatus_id'] == -2)
				{
					$this->redirect(array("controller"=>"Users","action"=>"SuspendUser/email:".$this->data['User']['email_login']));
				}
			}
			
			$this->User->set($this->data);
			$this->User->validateLogin();
			
			if(!$this->User->validates())
			{
				$fByEmail	=	$this->User->findByEmail($this->data['User']['email_login']);
				
				if($fByEmail)
				{
					$this->set("fByEmail",$fByEmail);
				}
			}
			else
			{
				if ($this->data['User']['keep_login'] == "1") 
				{
					$expired = "365 days";
				} else {
					$expired = "1 days";
				}
				$data			=	$this->User->CheckLogin();
				$session_back	=	$this->Session->read('back_url');
                $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
				$create_cookie	=	$this->Cookie->write('userlogin', $this->General->my_encrypt($data['User']['id']), false, $expired,$this->settings['site_url']);
				
				//UPDATE LAST LOGIN
				$this->Action->LastLogin($data['User']['id']);
				
				//GET VITUAL ID FOR USER
				$this->Cookie->delete('rand_user');
				$rand	=	$this->Action->GetRandomUser();
				
				//SAVE USER ACTIONS
				$text = $this->Action->generateHTML("signin", array('[username]'), array($data['Profile']['fullname']), array("Anda"));
				$this->Action->save($data['User']['id']);
				
				$this->redirect($back_url);
			}
		}
	}
	
	
	function WaitingEmailConfirm()
	{
		
	}
	
	function SuspendUser()
	{
		$this->loadModel('User');
		$chek_user	=	$this->User->CheckUserByEmail($this->params['named']['email']);
		
		if($chek_user['User']['userstatus_id'] != -2)
		{
			$this->redirect(array("controller"=>"Home","action"=>"Index"));	
		}
		
		$this->loadModel('EmailLog');
		$data		= 	$this->EmailLog->find('first',array(
							'conditions'	=>	array(
								'EmailLog.model'			=>	'User',
								'EmailLog.model_id'			=>	$chek_user['User']['id'],
								'EmailSettings.name'		=>	array('admin_user_suspend2','admin_user_suspend')
							 ),
							'order'	=>	array('EmailLog.id DESC')	
						));
		
		App::import('vendor', 'encryption_class');
		$encrypt	= new encryption;
		$param		= $encrypt->my_encrypt($data['EmailLog']['id']);
		if($data==false)
		{
			$this->redirect(array("controller"=>"Home","action"=>"Index"));	
		}
		$this->set("emaillog_id",$param);
	}
	
	function LogOut()
	{
		$this->Cookie->delete('userlogin');
		$this->Session->delete('back_url');
		$this->redirect($this->settings['site_url']);		
		$this->autoRender	=	false;
	}
	
	
	function InviteFriends()
	{
		$this->Session->write('back_url',$this->settings['site_url'].'Users/InviteFriends');
		$this->loadModel("InvitedLog");
	}
	
	function FriendLists()
	{
		$session_back	=	$this->Session->read('back_url');
        $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
		$this->set("back_url",$back_url);
		
		if(!empty($_POST))
		{
			//OPEN INVITER
			App::import('Vendor','openinviter' ,array('file'=>'OpenInviter/openinviter.php'));
			$inviter		=	new OpenInviter();
			$oi_services	=	$inviter->getPlugins();
			
			$ers			=	array();
			$this->set("error","");
			$_POST['email_box']	=	trim($_POST['email_box']);

			$username		=	!empty($this->profile['Profile']['fullname']) ? $this->profile['Profile']['fullname']."(".$_POST['email_box'].")" : $_POST['email_box'];

			$this->set("username",$username);
			
			if (isset($oi_services['email'][$_POST['provider_box']])) $plugType='email';
			elseif (isset($oi_services['social'][$_POST['provider_box']])) $plugType='social';
			else $plugType='';
			$this->set("plugType",$plugType);

			//DEFINE ERROR
			if (empty($_POST['email_box']))
				$ers['email']="Email missing !";
			if (empty($_POST['password_box']))
				$ers['password']="Password missing !";
			if (empty($_POST['provider_box']))
				$ers['provider']="Provider missing !";
				
				
			//IF VALIDATES
			
			if (count($ers)==0)
			{
				$inviter->startPlugin($_POST['provider_box']);
				$internal=$inviter->getInternalError();
				if ($internal)
				{
					$ers['inviter']	=	$internal;
				}
				elseif (!$inviter->login($_POST['email_box'],$_POST['password_box']))
				{
					$internal=$inviter->getInternalError();
					$ers['login']=($internal?$internal:"Login failed. Please check the email and password you have provided and try again later !");
				}
				elseif (false===$contacts=$inviter->getMyContacts())
				{
					$ers['contacts']="Unable to get contacts !";
				}
				elseif(empty($contacts))
				{	
					$ers['contacts']="Your contact is empty";
				}
				else
				{
					$oi_session_id	=	$inviter->plugin->getSessionID();
					$_POST['message_box']	=	'';
				}
				
				$this->loadModel("InvitedLog");
				
				foreach($contacts as $k => $v)
				{
					$exists	=	$this->InvitedLog->AlreadyRegister($v,$_POST['provider_box']);
					
					if($exists)
					{
						unset($contacts[$k]);
					}
				}
				if(empty($contacts) && empty($ers['login']))
				{
					$ers['login']	=	"Everyone on this contact list is already on ".$this->settings['site_name']." or has already been invited. Please try another social media.";
				}
				$this->set('provider_box',$_POST['provider_box']);
				$this->set(compact("oi_session_id"));
				$this->set(compact("contacts"));
			}
			
			foreach($ers as $ers)
			{
				$this->set("error",$ers);
				break;
			}	
		}
	}
	
	function SuccessInvite()
	{
		$session_back	=	$this->Session->read('back_url');
        $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
		$this->set("back_url",$back_url);
	}
	
	
	function __GenerateMessage($provider_box,$message)
	{
		$header		=	"Anda diundang ke ".$this->settings['site_url']." .";
		
		if(strtolower($provider_box)=="twitter")
		{
			$lenght		=	strlen($header.$message);
			$msglen		=	135 - $lenght;
			
			if($msglen>0)
			{
				$msg		=	$header." ".substr($message,0,$msglen);
			}
			else
			{
				$msg		=	$header;
			}
			
			$message	=	array(
									'subject'	=>	$header,
									'body'		=>	$msg,
									'attachment'=>	"\n\rAttached message: \n\r".$msg
								);
		}
		else
		{
			$message		=	array(
									'subject'	=>	$header,
									'body'		=>	$header.$message,
									'attachment'=>	"\n\rAttached message: \n\r".$header.$message
								);
		}
		return $message;
	}
	
	function SendInviter()
	{
		$this->layout	=	"json";
		$this->loadModel("InvitedLog");
		App::import('Vendor','openinviter' ,array('file'=>'OpenInviter/openinviter.php'));
		$inviter		=	new OpenInviter();
		$oi_services	=	$inviter->getPlugins();
		
		$out	=	array("status" => false,"error"=>"");
		
		if(!empty($_POST['check']) & !empty($_POST['messages']))
		{
			if (isset($oi_services['email'][$_POST['provider_box']])) $plugType='email';
			elseif (isset($oi_services['social'][$_POST['provider_box']])) $plugType='social';

			$inviter->startPlugin($_POST['provider_box']);
			$message	=	$this->__GenerateMessage($_POST['provider_box'],$_POST['messages']);
			
			$inviter->logout();
			$inviter_name	=	$_POST['username'];
			
			if($plugType=="social")
			{
				$invite_sent_cound	=	0;
				foreach($_POST['check'] as $email=>$name)
				{
					$sendMessage	=	$inviter->sendMessageByOne($_POST['oi_session_id'],$message,$email);
					if($sendMessage>=1)
					{
						$invite_sent_cound++;
						$save	= $this->InvitedLog->SaveLog($name,$_POST['provider_box'],$this->user_id);
					}
				}
			}
			elseif($plugType=="email")
			{
				$invite_sent_cound	=	0;
				foreach($_POST['check'] as $email=>$name)
				{
					//SEND VERIFICATION CODES
					$inviter	= $_POST['username'];
					$message	= $_POST['messages'];
					$search 	= array('[logo_url]', '[name]', '[site_name]', '[inviter]', '[site_url]','[messages]');
					$replace 	= array($this->settings['logo_url'],$name, $this->settings['site_name'], $inviter_name,$this->settings['site_url'],$message);
					$search_s	= array('[site_name]');
					$replace_s	= array($this->settings['site_name']);
					$send		= $this->Action->EmailSend('invite_friends', $email, $search, $replace,$search_s,$replace_s);
					if($send>=1)
					{
						$save	= $this->InvitedLog->SaveLog($name,$_POST['provider_box'],$this->user_id);
						$invite_sent_cound++;
					}
				}
			}
			
			if($invite_sent_cound > 0)
			{
				if(!empty($this->user_id))
				{
					//SAVE USER ACTIONS
					$point		=	10 * $invite_sent_cound;
					$text = $this->Action->generateHTML("invite_friends", array('[some]','[site_name]'), array($this->profile['Profile']['fullname'],$this->settings['site_name']), array("Anda",$this->settings['site_name']));
					$this->Action->save($this->user_id,array('userValue'=>$point));
				}
				$out	=	array("status" => true,"error"=>$this->settings['site_url']."Users/SuccessInvite");
			}
			else
			{
				$out	=	array("status" => false,"error"=>"Maaf email gagal terkirim.");
			}
		}
		elseif(empty($_POST['check']))
		{
			$out	=	array("status" => false,"error"=>"Silahkan checklist kontak anda.");
		}
		elseif(empty($_POST['messages']))
		{
			$out	=	array("status" => false,"error"=>"Silahkan masukkan pesan anda.");
		}
		
		$this->set("data",$out);
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
			$this->data['User']['max_photo_upload']	=	$this->settings['max_photo_upload'];
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
	
	function ForgotPassword()
	{
		$this->layout = "login";
		$this->loadModel("User");
		if(!empty($this->user_id))
		{
			$session_back	=	$this->Session->read('back_url');
            $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
			$this->redirect($back_url);	
		}
		
		//IMPORT ENCRYPTION CLASS
        App::import('vendor', 'encryption_class');
        $encrypt = new encryption;
		
        if(!empty($this->params['named']['token']) OR !empty($this->params['named']['email']))
		{
            $token		=	$this->params['named']['token'];
			$email		=	$this->params['named']['email'];
			
			//CHEK USER STATUS FIRST
			$chek_user	=	$this->User->CheckUserByEmail($email);
			if($chek_user)
			{
				if($chek_user['User']['userstatus_id'] == -2)
				{
					$this->redirect(array("controller"=>"Users","action"=>"SuspendUser/email:".$email));
				}
			}
			
			//CHECK DETAIL TOKEN
			$this->loadModel('FPToken');
			$validtoken	=	$this->FPToken->FindToken($email,$token);
        }
        
		$text_status	=	"Kami akan mengirimkan instruksi ulang sandi ke alamat email yang terkait dengan account Anda.";
		
		if ($validtoken['status']===false)
		{
           $text_status = "<img src='".$this->settings['site_url']."img/icn_error.png' style='vertical-align:middle'/> ".$validtoken['msg'];
		  
        }
		elseif($validtoken['status']===true)
		{
			$this->redirect(array('controller' => 'Users', 'action' => 'ChangePassword/email:'.$email.'/token:'.$token));
		}
		
		
		$this->set(compact("status","text_status"));
	}
	
	function ProcessForgotPassword()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		$err			=	array();
		
		App::import('Sanitize');
		if(!empty($this->data))
		{
			//LOAD MODEL
			$this->loadModel('User');
			$this->data['User']['captcha'] 		= $_POST['recaptcha_response_field'];
			$this->data	=	Sanitize::clean($this->data);
			
			$this->User->set($this->data);
			$this->User->ValidateForgot();
			$captcha	=	$this->Captcha->validate();
			$err_c		=	array();
			
			if (empty($_POST['recaptcha_response_field'])) {
                $err_c = array("captcha" => "Masukkan kode captcha.");
            } elseif (!$captcha) {
                $err_c = array("captcha" => "Kode captcha anda salah.");
            }
			
			$error	=	array_merge($this->User->InvalidFields(),$err_c);
			
			if(empty($error))
			{
				//GETTING TOKEN
				$this->loadModel('FPToken');
				$email 		= $this->data['User']['email'];
				$token		= $this->FPToken->GetToken($email);
				$user		= $this->User->findByEmail($email);
				$link 		= $this->settings['site_url'] . 'Users/ForgotPassword/email:' . $email ."/token:" .$token;
                $imgsrc 	= $this->settings['logo_url '];
				$search 	= array('[logo_url]','[fullname]','[username]', '[link]','[site_name]','[site_url]');
                $replace 	= array($this->settings['logo_url'],$user['Profile']['fullname'],$this->data['User']['email'], $link,$this->settings['site_name'],$this->settings['site_url']);
                $this->Action->EmailSend('forgot_password', $this->data['User']['email'], $search, $replace);
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Users/ForgotPasswordSend');
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
			$this->set("data",$out);
			$this->render(false);
		}
	}
	
	function ForgotPasswordSend()
	{
		$this->layout = "login";	
	}
	
	function ChangePassword()
	{
		$this->layout = "login";
		$token		=	$this->params['named']['token'];
		$email		=	$this->params['named']['email'];
		
		$this->set("token",$token);
		$this->set("email",$email);
	}
	
	function ProcessChangePassword()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		
		if(!empty($this->data))
		{
			//LOAD MODEL
			$this->loadModel('User');
			
			//IMPORT ENCRYPTION CLASS
			App::import('vendor', 'encryption_class');
			$encrypt = new encryption;
		
			//CLEANING DATA
			App::import('Sanitize');
			$this->data	=	Sanitize::clean($this->data);
			
			$this->User->set($this->data);
			$this->User->ValidateProcessForgot();
			
			
			if($this->User->validates())
			{
				$email	=	$this->data['User']['email'];
				$token	=	$this->data['User']['token'];
				
				//UPDATE USERS PASSWORD
                $update = $this->User->updateAll(
                                array(
                                    'password' => "'" . md5($this->data['User']['password']) . "'"
                                ),
                                array(
                                    'User.email' => $email
                                )
                		);
				
				$expired 		= "1 days";
				$data			=	$this->User->findByEmail($email);
				$session_back	=	$this->Session->read('back_url');
                $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
				$this->Cookie->write('userlogin', $this->General->my_encrypt($data['User']['id']), false, $expired,$this->settings['site_url']);
				
				//GET VITUAL ID FOR USER
				$this->Cookie->delete('rand_user');
				$rand	=	$this->Action->GetRandomUser();
				
				//SAVE USER ACTIONS
				$text = $this->Action->generateHTML("signin", array('[username]'), array($data['Profile']['fullname']), array("Anda"));
				$this->Action->save($data['User']['id']);
				
				//DELETE COOKIES
				$this->Cookie->delete('reminder');
				$out		=	array("status"=>true,"error"=>$back_url);
				
				//UPDATE TOKEN
				$this->loadModel('FPToken');
				$this->FPToken->UpdateToken($token,"1");
				
			}
			else
			{
				
				$error	=	$this->User->InvalidFields();
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
	
	function CheckExtId($vendor)
	{
		$this->layout	=	"json";
		
		$this->loadModel('Extid');
		$chk	=	$this->Extid->find('first',array(
						'conditions'	=>	array(
							'Extid.user_id'		=>	$this->user_id,
							'LOWER(Extid.extName)'		=>	strtolower($vendor)
						)
					));
		if($chk)
		{
			$out	=	array("status"	=>	true);
		}
		else
		{
			$out	=	array("status"	=>	false);
		}
		$this->set("data",$out);
		$this->render(false);
	}
	
	function OtherAction()
	{
		$this->layout	=	"ajax";
	}
}
?>