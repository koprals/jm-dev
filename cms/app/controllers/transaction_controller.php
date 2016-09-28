<?php
class TransactionController extends AppController
{
	var $name	=	"Transaction";
	var $uses	=	null;
	var $helper	=	array("Time");
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','transaction');
		$this->set('child_code','transaction_list');
		$this->layout	=	"new";
	}
	
	function Index()
	{
		//LIST STATUS
		$status			=	array("-2"=>"Waiting for payment","-1"=>"User Confirm","0"=>"Pending","1"=>"Admin Confirm");
		
		//LIST VOUCHER
		$this->loadModel("Voucher");
		$voucher_id		=	$this->Voucher->find("list",array(
								"conditions"	=>	array(
									"status"	=>	1
								)
							));
		
		//LIST PAYMENT METHOD
		$this->loadModel("PaymentMethod");
		$payment_method_id	=	$this->PaymentMethod->find("list",array(
								"conditions"	=>	array(
									"status"	=>	1
								)
							));
		$this->set(compact("status","voucher_id","payment_method_id"));
	}
	
	function ListTransaction()
	{
		$this->layout		=	"ajax";
		
		$this->loadModel("TransactionLog");
		$this->TransactionLog->Bind1(false);
		$this->TransactionLog->VirtualFieldActivated();
		
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order				=	array('TransactionLog.id DESC');
		
		//DEFINE FIELDS
		$fields				=	array(
									'TransactionLog.*',
									'PaymentMethod.name',
									"Profile.fullname",
									"User.email"
								);
								
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('TransactionLog.cond_search');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'User.email LIKE'					=> "%$val%",
											'Profile.fullname LIKE'				=> "%$val%",
											'TransactionLog.invoice_id LIKE'	=> "%$val%",
										)
									);
				}
			}
			
			$OR['OR'][]			=	"MATCH (User.email, Profile.fullname,TransactionLog.invoice_id) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			array_push($fields,"MATCH (User.email, Profile.fullname,TransactionLog.invoice_id) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("TransactionLog.cond_search",$cond_search);
			$order			=	array('score DESC','TransactionLog.id DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		if(!empty($this->data))
		{
			$this->Session->delete('TransactionLog.cond_search');
			$trans 					=	array(' ' => '', '.' => '', ',' => '');
			$this->data['Search']['price_from']	=	strtr($this->data['Search']['price_from'], $trans);
			$this->data['Search']['price_to']	=	strtr($this->data['Search']['price_to'], $trans);
			
			if(!empty($this->data['Search']['invoice_id']))
			{
				$cond_search["TransactionLog.invoice_id"]					=	$this->data['Search']['invoice_id'];
			}
			
			if(!empty($this->data['Search']['fullname']))
			{
				$cond_search["Profile.fullname LIKE "]				=	"%".$this->data['Search']['fullname']."%";
			}
			
			if(!empty($this->data['Search']['email']))
			{
				$cond_search["User.email LIKE "]					=	"%".$this->data['Search']['email']."%";
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
					$cond_search["Product.created BETWEEN ? AND ?"]		=	array(strtotime(date("Y-m-d",$date1)." 00:00:00"),strtotime(date("Y-m-d",$date2)." 23:59:59"));
					
				}
				else
					$cond_search["Product.created BETWEEN ? AND ?"]		=	array(strtotime(date("Y-m-d",strtotime($string[0]))." 00:00:00"),strtotime(date("Y-m-d",strtotime($string[0]))." 23:59:59"));
				
			}
			
			if(!empty($this->data['Search']['status']))
			{
				$cond_search["TransactionLog.status"]					=	$this->data['Search']['status'];
			}
			
			if(!empty($this->data['Search']['voucher_id']))
			{
				$cond_search["TransactionLog.voucher_id"]				=	$this->data['Search']['voucher_id'];
			}
			
			if(!empty($this->data['Search']['payment_method_id']))
			{
				$cond_search["TransactionLog.payment_method_id"]		=	$this->data['Search']['payment_method_id'];
			}
			
			$this->Session->write("TransactionLog.cond_search",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('TransactionLog.cond_search');
			unset($this->data);
		}
		
		$cond_search		=	array();
		$filter_paginate	=	array();
		$this->paginate		=	array(
			'TransactionLog'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'fields'	=>	$fields
			)
		);
		
		$ses_cond			=	$this->Session->read("TransactionLog.cond_search");
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$data				=	$this->paginate('TransactionLog',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['TransactionLog']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['TransactionLog']['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
	}
	
	function DetailTransaction($transaction_log_id)
	{
		$this->loadModel("TransactionLog");
		$this->TransactionLog->Bind1(false);
		$this->TransactionLog->VirtualFieldActivated();
		$confirmation	=	"";
		
		
		//GET DATA
		$data	=	$this->TransactionLog->findById($transaction_log_id);
		
		//GET MESSAGE
		if($data['TransactionLog']["status"] == "-1" or $data['TransactionLog']["status"] == "0")
		{
			$this->loadModel("Confirmation");
			$confirmation	=	$this->Confirmation->find("first",array(
									"conditions"	=>	array(
										"Confirmation.transaction_log_id"	=>	$data["TransactionLog"]["id"]
									),
									"order"	=>	array(
										"Confirmation.id DESC"
									)
								));
			
		}
		
		$this->set(compact("data","confirmation"));
	}
	
	function ProcessEdit()
	{
		$status		=	false;
		$message	=	"Data not found";
		$data		=	null;
		
		//START PROCESS
		$this->loadModel("Confirmation");
		$this->loadModel("TransactionLog");
		$this->TransactionLog->Bind1(false);
		
		if(!empty($_REQUEST["data"]))
		{
			App::import('Sanitize');
			$this->data				=	$_REQUEST["data"];
			$this->TransactionLog->set($this->data);
			$this->TransactionLog->ValidateEdit();
			$error					=	$this->TransactionLog->InvalidFields();
			if(empty($error))
			{
				$status		=	true;
				$data		=	NULL;
				
				$fTransaction	=	$this->TransactionLog->find("first",array(
										"conditions"	=>	array(
											"TransactionLog.id"	=>	$this->data["TransactionLog"]["id"])
									));
				
				
				if($this->data["TransactionLog"]["status"] == "1")
				{
					$message													=	"JmPoin telah berhasil di tambahkan.";
					
					//UPDATE TRANSACTION LOG
					$this->data["TransactionLog"]["admin_id"]					=	$this->profile["User"]["id"];
					$this->data["TransactionLog"]["basic_price_confirm"]		=	$fTransaction["TransactionLog"]["basic_price"];
					$this->data["TransactionLog"]["requested_price_confirm"]	=	$fTransaction["TransactionLog"]["requested_price"];
					$this->data["TransactionLog"]["extra_confirm"]				=	0;
					$this->data["TransactionLog"]["tax_confirm"]				=	0;
					$this->data["TransactionLog"]["total_confirm"]				=	$fTransaction["TransactionLog"]["total"];
					$this->TransactionLog->save($this->data);
					
					//UPDATE POINT LOG
					$text = $this->Action->generateHTML("user_topup_jmpoin", array('[username]','[poin]','[invoice_id]'), array($fTransaction["Profile"]["fullname"],$fTransaction["TransactionLog"]["voucher_value"],$fTransaction["TransactionLog"]["invoice_id"]), array("Anda",$fTransaction["TransactionLog"]["voucher_value"],$fTransaction["TransactionLog"]["invoice_id"]));
					
					$option	=	array(
						"userValue"	=>	$fTransaction["TransactionLog"]["voucher_value"]
					);
					$this->Action->save($fTransaction['TransactionLog']['user_id'],$option);
					
					//UPADATE USER
					$this->User->updateAll(
						array(
							"points"	=>	"points+".$fTransaction["TransactionLog"]["voucher_value"]
						),
						array(
							"User.id"	=>	$fTransaction['TransactionLog']['user_id']
						)
					);
					
					//EMAIL TO USER
					$html		=	str_replace("&quot;","",$this->data['TransactionLog']['pesan']);
					$send		=	$this->Action->EmailSend("admin_transaction_success",$fTransaction['User']['email'],array(),array(),array(),array(),'TransactionLog',$fTransaction['TransactionLog']['id'],$html);
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_approve_transaction", array('[adminname]','[transaction_log_id]'), array($this->profile['Profile']['fullname'],$fTransaction['TransactionLog']['id']), array("Anda",$fTransaction['TransactionLog']['id']) );
					$this->Action->saveAdminLog($this->profile['User']['id']);
					
					//UPDATE CONFIRMATION
					$confirmation["Confirmation"]["id"]			=	$this->data['TransactionLog']['confirm_id'];
					$confirmation["Confirmation"]["status"]		=	"1";
					$confirmation["Confirmation"]["admin_id"]	=	$this->profile['User']['id'];
					$this->Confirmation->save($confirmation);
				}
				elseif($this->data["TransactionLog"]["status"] == "0")
				{
					$message	=	"JmPoin gagal ditambahkan.";
					
					//UPDATE TRANSACTION LOG
					$this->data["TransactionLog"]["admin_id"]					=	$this->profile["User"]["id"];
					$this->TransactionLog->save($this->data);
					
					//SAVE TO CONFIRMATION LOG
					$this->loadModel("TransactionPendingLog");
					$pending["TransactionPendingLog"]["transaction_log_id"]		=	$fTransaction["TransactionLog"]["id"];
					$pending["TransactionPendingLog"]["confirmation_id"]		=	$this->data['TransactionLog']['confirm_id'];
					$pending["TransactionPendingLog"]["user_id"]				=	$fTransaction["TransactionLog"]["user_id"];
					$pending["TransactionPendingLog"]["admin_id"]				=	$this->profile['User']['id'];
					$pending["TransactionPendingLog"]["message"]				=	$this->data['TransactionLog']['notice'];
					
					$save														=	$this->TransactionPendingLog->save($pending);
					
					//EMAIL TO USER
					$html		=	str_replace("&quot;","",$this->data['TransactionLog']['pesan']);
					$send		=	$this->Action->EmailSend("admin_transaction_pending",$fTransaction['User']['email'],array(),array(),array(),array(),'TransactionLog',$fTransaction['TransactionLog']['id'],$html);
					
					//SAVE ADMIN ACTIONS
					$text = $this->Action->generateHTML("admin_pending_transaction", array('[adminname]','[transaction_log_id]'), array($this->profile['Profile']['fullname'],$fTransaction['TransactionLog']['id']), array("Anda",$fTransaction['TransactionLog']['id']) );
					$this->Action->saveAdminLog($this->profile['User']['id']);
					
					//UPDATE CONFIRMATION
					$confirmation["Confirmation"]["id"]							=	$this->data['TransactionLog']['confirm_id'];
					$confirmation["Confirmation"]["status"]						=	"1";
					$confirmation["Confirmation"]["admin_id"]					=	$this->profile['User']['id'];
					$this->Confirmation->save($confirmation);
				}
			}
			else
			{
				$err	=	array();
				foreach($this->data['TransactionLog'] as $k=>$v)
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
				$status		=	false;
				$message	=	$err;
				$data		=	NULL;
			}
		}
		$out		=	array("status"=>$status,"message"=>$message,"data"=>$data);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	
	function GetStatusMessage()
	{
		$this->layout	=	"ajax";
		$status					=	$_GET['status'];
		$transaction_log_id		=	$_GET['transaction_log_id'];
		$confirmation_id		=	$_GET['confirmation_id'];
		
		//GET TRANSACTION DETAIL
		$this->loadModel('TransactionLog');
		$this->TransactionLog->Bind1(true);
		$transaction			=	$this->TransactionLog->findById($transaction_log_id);
		
		//GET CONFIRMATION DETAIL
		$this->loadModel('Confirmation');
		$confirmation			=	$this->Confirmation->findById($confirmation_id);
		
		switch($status)
		{
			case "0" : 
				$html			=	$this->GetHtmlPendingTransaction($transaction,$confirmation);
				break;
			case "1" :
				$html			=	$this->GetHtmlSuccessTransaction($transaction,$confirmation);
				break;
			default: 
				$html			=	"";
		}
		$this->set("html",$html);
	}
	
	function GetHtmlPendingTransaction($transaction,$confirmation)
	{
		App::import('Helper', 'Number');
		$number 			= 	new NumberHelper();
		
		$emailID			=	"admin_transaction_pending";
		$site_url			=	$this->settings['site_url'];
		$fullname			=	$transaction["Profile"]["fullname"];
		$voucher_value		=	$transaction["TransactionLog"]["voucher_value"];
		$invoice_id			=	$transaction["TransactionLog"]["invoice_id"];
		$tgl_pemesanan		=	date("d-M-Y",$transaction["TransactionLog"]["created"]);
		$batas_pembayaran	=	date("d-M-Y",$transaction["TransactionLog"]["expired"]);
		$metode_pembayaran	=	$transaction["PaymentMethod"]["name"];
		$total				=	$number->format($transaction['TransactionLog']['total'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>",-"));
		
		$tgl_konfirmasi		=	date("d-M-Y",$confirmation["Confirmation"]["created"]);
		$bank_asal			=	$confirmation["Confirmation"]["bank_name"];
		$bank_account_name	=	$confirmation["Confirmation"]["bank_account_name"];
		$message			=	$confirmation["Confirmation"]["message"];
		
		$existing_transfer_value		=	"[please admin input]";
		$required_transfer_value		=	$total;
		$link_konfirmasi_pembayaran		=	$this->settings['site_url']."Point/KonfirmasiPembayaran";
		
		$email_support		=	$this->settings['admin_mail'];
		$telp_support		=	"(021) 704 406 93";
		$site_name			=	$this->settings['site_name'];
		
		$search			=	array('[site_url]','[fullname]','[voucher_value]','[invoice_id]','[tgl_pemesanan]','[batas_pembayaran]','[metode_pembayaran]','[total]','[tgl_konfirmasi]','[bank_asal]','[bank_account_name]','[message]','[existing_transfer_value]','[required_transfer_value]','[link_konfirmasi_pembayaran]','[email_support]','[telp_support]','[site_name]');
		
		$replace		=	array($site_url,$fullname,$voucher_value,$invoice_id,$tgl_pemesanan,$batas_pembayaran,$metode_pembayaran,$total,$tgl_konfirmasi,$bank_asal,$bank_account_name,$message,$existing_transfer_value,$required_transfer_value,$link_konfirmasi_pembayaran,$email_support,$telp_support,$site_name);
		
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	function GetHtmlSuccessTransaction($transaction,$confirmation)
	{
		App::import('Helper', 'Number');
		$number 			= 	new NumberHelper();
		
		$emailID			=	"admin_transaction_success";
		$site_url			=	$this->settings['site_url'];
		$fullname			=	$transaction["Profile"]["fullname"];
		$voucher_value		=	$transaction["TransactionLog"]["voucher_value"];
		$email_support		=	$this->settings['admin_mail'];
		$telp_support		=	"(021) 704 406 93";
		$site_name			=	$this->settings['site_name'];
		
		$search				=	array('[site_url]','[fullname]','[voucher_value]','[email_support]','[telp_support]','[site_name]');
		
		$replace			=	array($site_url,$fullname,$voucher_value,$email_support,$telp_support,$site_name);
		
		$html				=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
}
?>