<?php
class CpanelController extends AppController
{
	var $name	=	"Cpanel";
	var $uses	=	null;

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	
	function UpdateProfile()
	{
		
		$this->Session->write('back_url',$this->settings['site_url'].'Cpanel/UpdateProfile');
		$this->set("active_code","edit_profile");
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DEFINE EXTENDED PHONE
		$this->loadModel('ExtendedPhone');
		$ext_phone	=	$this->ExtendedPhone->find("all",array(
							'conditions'	=>	array(
								'ExtendedPhone.user_id'	=>	$this->user_id,
								'ExtendedPhone.type'	=>	1
							),
							'order'	=>	array('ExtendedPhone.id ASC')
						));
		$this->set(compact("ext_phone"));
	}
	
	function ProcessUpdateProfile()
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		App::import('Sanitize');
		$err				=	array();
		$invalidBookFields	=	array();
		
		if(empty($this->user_id))
		{
			$out				=	array("status"=>true,"error"=>$this->settings['site_url'].'Users/Login');
			$this->set("data",$out);
			$this->render(false);
			return;
		}
		
		if(!empty($this->data))
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
			
			
			//VALIDATES
			$this->loadModel('User');
			$this->User->set($this->data);
			$this->User->InitiateValidate();
			
			if($this->User->validates() && empty($invalidBookFields))
			{
				//SAVE USER
				$user_id		=	$this->user_id;
				$request_mail	=	$this->data['User']['email'];
				$this->data['User']['email']		=	$this->profile['User']['email'];
				$save			=	$this->User->save($this->data,false);
				
				
				//SAVE PROFILE
				$this->loadModel('Profile');
				$this->data['User']['fullname']		=	Sanitize::html($this->data['User']['fullname']);
				$this->data['User']['address']		=	Sanitize::html($this->data['User']['address']);
				$profile	=	$this->Profile->saveAll(
									array(
										'id'			=>	$this->profile['Profile']['id'],
										'user_id'		=>	$this->user_id,
										'fullname'		=>	$this->data['User']['fullname'],
										'address'		=>	$this->data['User']['address'],
										'province_id'	=>	$this->data['User']['province'],
										'city_id'		=>	$this->data['User']['city'],
										'lat'			=>	$this->data['User']['lat'],
										'lng'			=>	$this->data['User']['lng'],
										'phone'			=>	trim($this->data['User']['phone']),
										'fax'			=>	!empty($this->data['User']['fax']) ? trim($this->data['User']['fax']) : NULL,
										'ym'			=>	!empty($this->data['User']['ym']) ? trim($this->data['User']['ym']) : NULL,
										'gender'		=>	!empty($this->data['User']['gender']) ? $this->data['User']['gender'] : NULL
									)
								);
				
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Cpanel/UpdateProfile');
				
				//SAVE Company
				if($this->data['User']['usertype_id']==2)
				{
					$this->loadModel('Company');
					$this->data['User']['cname']		=	Sanitize::html($this->data['User']['cname']);
					
					$company	=	$this->Company->saveAll(
										array(
											'id'				=>	$this->profile['Company']['id'],
											'user_id'			=>	$user_id,
											'name'				=>	$this->data['User']['cname'],
											'address'			=>	$this->data['User']['address'],
											'province_id'		=>	$this->data['User']['province'],
											'city_id'			=>	$this->data['User']['city'],
											'phone'				=>	$this->data['User']['phone'],
											'companystatus_id'	=>	1
										)
									);
					
				}
				
				//UPDATE PRODUCT
				$this->loadModel('Product');
				$updt_product	=	$this->Product->updateAll(
										array(
											'Product.contact_name'	=>	"'".$this->data['User']['fullname']."'",
											'Product.address'		=>	"'".$this->data['User']['address']."'",
											'Product.province_id'	=>	"'".$this->data['User']['province']."'",
											'Product.city_id'		=>	"'".$this->data['User']['city']."'",
											'Product.ym'			=>	"'".$this->data['User']['ym']."'",
											'Product.lat'			=>	"'".$this->data['User']['lat']."'",
											'Product.lng'			=>	"'".$this->data['User']['lng']."'"
										),
										array(
											'Product.user_id'		=>	$this->user_id,
											'Product.data_type'		=>	1,
										)
									);
				
				//UPDATE EXTENDED PHONE
				$this->loadModel('ExtendedPhone');
				$delete	=	$this->ExtendedPhone->deleteAll(array('ExtendedPhone.user_id'=>$user_id,'ExtendedPhone.type'=>1));
				foreach($this->data['ExtendedPhone'] as $k => $v)
				{
					$this->ExtendedPhone->create();
					$save	=	$this->ExtendedPhone->saveAll(
									array('phone'	=>	str_replace(" ","",trim($v['phone'])),'user_id'	=> $this->user_id,'type'=>1)
								);
				}
				
				//CHECK UPDATE EMAIL
				if($this->profile['User']['email']!=$request_mail)
				{
					//SEND EMAIL
					$this->loadModel('FPToken');
					$oldemail	= $this->profile['User']['email'];
					$token		= $this->FPToken->GetToken($request_mail,$this->user_id);
					$link 		= $this->settings['site_url'] . 'Cpanel/ChangeEmail/token:' . $token ."/email:" .$request_mail;
                	$imgsrc 	= $this->settings['logo_url'];
					$search 	= array('[logo_url]','[fullname]','[email]', '[request_email]','[link]','[site_name]','[site_url]');
                	$replace 	= array($this->settings['logo_url'],$this->profile['Profile']['fullname'],$oldemail,$request_mail, $link, $this->settings['site_name'],$this->settings['site_url']);
					$this->Action->EmailSend('change_email', $request_mail, $search, $replace);
					$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Cpanel/ChangeEmailSend');
				}
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
	
	function ChangeEmailSend()
	{
		$this->set("active_code","edit_profile");
		if(empty($this->user_id))
		{
			$this->Session->write('back_url',$this->settings['site_url'].'Cpanel/ChangeEmailSend');
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
	}
	
	function ChangeEmail()
	{
		$token		=	$this->params['named']['token'];
		$email		=	$this->params['named']['email'];
		
		$this->set("active_code","edit_profile");
		$this->loadModel('User');

		//CHECK DETAIL TOKEN
		$this->loadModel('FPToken');
		$validtoken		=	$this->FPToken->FindToken($email,$token,"ubah email");
		$detail_token	=	$this->FPToken->findByToken($token);
		
		
		if(!is_null($this->Cookie->read("userlogin")) and ($this->user_id !=  $detail_token['FPToken']['user_id'] ) )
		{
			$this->Cookie->delete('userlogin');
			$this->Session->delete('back_url');
			$this->redirect($this->settings['site_url'].'Cpanel/ChangeEmail/token:'.$token.'/email:'.$email);
		}
		
		
		if ($validtoken['status']===false)
		{
           $img 			= "<img src='".$this->settings['site_url']."img/error_ico.png'/> ";
		   $text_status		= $validtoken['msg'];
		   $status			=	false;
        }
		else
		{
			//CHECK EMAIL FIRST
			$chk			=	$this->User->CheckUsersExistsEmail($email);
			$detail			= 	$this->User->findById($validtoken['data']['user_id']);
			
			if($chk == false)
			{
				$img 			=	"<img src='".$this->settings['site_url']."img/error_ico.png'/> ";
		   		$text_status	=	($email!==$detail['User']['email']) ? "Maaf email ini telah digunakan oleh user lain." : "Email anda sudah digantikan oleh email ini.";
				 $status		=	false;
			}
			else
			{
				$img 			= "<img src='".$this->settings['site_url']."img/email_send_ico.png'/> ";
				$text_status	= "Email anda telah digantikan menjadi <b>".$email."</b>.<br><br>Mulai saat ini, email ini akan digunakan sebagai akses member anda.";
				 $status			=	true;
			}
		}
		
		if($status==true)
		{
			//UPDATE USERS EMAIL
			$update = $this->User->updateAll(
							array(
								'User.email'			=> "'" . $email . "'",
								'User.last_login'		=>	"'" .date("Y-m-d H:i:s"). "'"
							),
							array(
								'User.id' 				=> $detail['User']['id']
							)
					);
			
			//DELETE ALL USER WITH SAME EMAIL
			$this->User->updateAll(
				array(
					'User.userstatus_id'	=>	"'-10'"
				),
				array(
					'User.email'			=>	$email,
					'User.userstatus_id'	=>	0
				)
			);
			//SAVE USER ACTIONS
			$text = $this->Action->generateHTML("change_email", array('[username]'), array($detail['Profile']['username']), array("Anda"));
			$this->Action->save($detail['User']['id']);
				
			//UPDATE TOKEN
			$this->FPToken->UpdateToken($token,"1");
			
			//LOGINKAN USER
			$cookie	=	$this->Cookie->write('userlogin', $this->General->my_encrypt($detail["User"]["id"]),false,"1 days",$this->settings['site_url']);
			
			$this->Cookie->delete('rand_user');
			
			$rand	=	$this->Action->GetRandomUser();
			
		}
		$this->set(compact("img","text_status"));
	}
	
	function ProductIsSold($status)
	{
		$error			=	array();
		$token			=	$this->params['named']['token'];
		$arr_status		=	array("0","1");
		$status_after	=	"";
		
		$this->set("active_code","manage_products");
			
		//CHECK TOKEN FIRST
		$this->loadModel("EmailToken");
		$ftoken	=	$this->EmailToken->findByToken($token);
		
		if($ftoken==false or !in_array($status,$arr_status))
		{
			$error[]	=	"Maaf iklan anda tidak ditemukan, atau iklan anda sudah tidak aktif lagi";
		}
		
		if(empty($error))
		{
			if(empty($this->user_id))
			{
				$this->Session->write('back_url',$this->settings['site_url']."Cpanel/ProductIsSold/$status/token:{$token}");
				$this->redirect(array("controller"=>"Users","action"=>"Login"));	
			}
			
			//CHECK PRODUCT DETAIL
			$this->loadModel('Product');
			$fProduct	=	$this->Product->findById($ftoken['EmailToken']['product_id']);
			
			if($this->user_id != $ftoken['EmailToken']['user_id'] or $fProduct['Product']['productstatus_id']!=1)
			{
				$error[]	=	"Maaf iklan anda tidak ditemukan, atau iklan anda sudah tidak aktif lagi";	
			}
			else
			{
				//UPDATE PRODUCT
				$update	=	$this->Product->updateAll(
								array(
									"sold"			=>	"'".$status."'"
								),
								array(
									"Product.id"	=>	$ftoken['EmailToken']['product_id']
								)
							);
				
				$status_after	=	($status==1) ? "Sudah terjual" : "Belum terjual";
			}
		}
		$this->set("error",reset($error));
		$this->set("status_after",$status_after);
	}
	
	
	
	function ChangePassword()
	{
	}
	
	function ProcessChangePassword()
	{
		
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		$err				=	array();
		
		$this->loadModel("User");
		$this->data["User"]["password"]	=	$this->profile["User"]["password"];
		$this->User->ValidateChangePassword();
		$this->User->set($this->data);
		
		if($this->User->validates())
		{
			$this->User->updateAll(
				array(
					"password"	=>	"'".md5($this->data["User"]["newpassword"])."'"
				),
				array(
					 "User.id"	=>	$this->user_id
				)
			);
			$out		=	array("status"=>true,"error"=>"");	
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
		
		$this->set("data",$out);
		$this->render(false);
	}
}
?>