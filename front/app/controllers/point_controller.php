<?php
class PointController extends AppController
{
	var $name		=	"Point";
	var $uses		=	null;
	var $helpers	=	array("Number");

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	
	function BeliPoint()
	{
		$this->Session->write('back_url',$this->settings['site_url'].'Point/BeliPoint');
		$this->set("active_code","beli_point");
		
		if(empty($this->user_id))
		$this->redirect(array("controller"=>"Users","action" => "Login"));
		
		//GET POINT LIST
		$this->loadModel("Voucher");
		
		//FIND THE BIGGEST VALUE
		$big_id	=	NULL;
		$big	=	$this->Voucher->find("first",array(
			"conditions"	=>	array(
				"Voucher.status"	=>	1
			),
			"order"			=>	array(
				"Voucher.value"	=>	"DESC"
			)
		));
		if(!empty($big))
			$big_id	=	$big["Voucher"]["id"];
			
		
		$vouchers	=	$this->Voucher->find("all",array(
			"conditions"	=>	array(
				"Voucher.status"	=>	1,
				"Voucher.id !=	"	=>	$big_id
			),
			"order"			=>	array(
				"Voucher.value"	=>	"ASC"
			)
		));
		$this->set(compact("vouchers","big"));
		
		//GET USER POINT
		$this->loadModel("PointsHistory");
		$POINT	=	$this->PointsHistory->find("first",array(
						"conditions"	=>	array(
							"PointsHistory.user_id"	=>	$this->user_id
						),
						"order"	=>	array(
							"PointsHistory.id DESC"
						)
					));
		$user_point	=	(!empty($POINT)) ? $POINT["PointsHistory"]["points_after"] : 0;
		
		//DI HARDCODED DULU SEBELUM ADA PAYMENT LAIN
		$this->Session->write('Payment.payment_method_id',"1");
		$this->Session->write('aby',"aby");
		$this->set(compact("user_point"));
	}
	
	function BeliPointProcess()
	{
		$this->loadModel("Voucher");
		$status		=	false;
		$message	=	"Failed Save";
		$data		=	NULL;
		if(!empty($_REQUEST))
		{
			$this->data							=	$_REQUEST["data"];
			$this->data["Voucher"]["user_id"]	=	$this->user_id;
			$this->Voucher->set($this->data);
			$this->Voucher->ValidateBeliPoin();
			$error	=	$this->Voucher->InvalidFields();
			
			
			if(empty($error))
			{
				$this->loadModel("TransactionLog");
				
				//GET DETAIL PAYMENT
				$this->loadModel("PaymentMethod");
				$payment_id			=	$this->Session->read('Payment.payment_method_id');
				$payment			=	$this->PaymentMethod->findById($payment_id);
				
				
				//GET DETAIL VOUCHER
				$voucher_id			=	$this->data["Voucher"]["id"];
				$detail_voucher		=	$this->Voucher->findById($voucher_id);
				
				
				//GET PRICE
				$basic_price		=	$detail_voucher["Voucher"]["price"];
				$requested_price	=	$detail_voucher["Voucher"]["price"];
				$voucher_value		=	$detail_voucher["Voucher"]["value"];
				$totaltax			=	$payment['PaymentMethod']['tax']	*	$requested_price;
				$totalextra			=	$payment['PaymentMethod']['extra']	*	($totaltax + $requested_price);
				$total				=	$requested_price + $totaltax + $totalextra;
			
				
				//GET ACTION ID
				$this->loadModel("ActionTypes");
				$action				=	$this->ActionTypes->findByName("user_topup_jmpoin");
				$action_type_id		=	$action["ActionTypes"]["id"];
				
				$status				=	true;
				$message			=	"Success";
				$invoice_id			=	$this->TransactionLog->GetInvoiceId();
				$created			=	mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
				$expired			=	mktime(date("H"),date("i"),date("s"),date("m"),date("d")+3,date("Y"));
				$save				=	$this->TransactionLog->save(
										array(
											'invoice_id'			=>	$invoice_id,
											'user_id'				=>	$this->user_id,
											'payment_method_id'		=>	$payment_id,
											'action_type_id'		=>	$action_type_id,
											'voucher_id'			=>	$voucher_id,
											'voucher_value'			=>	$voucher_value,
											'basic_price'			=>	$basic_price,
											"requested_price"		=>	$requested_price,
											"tax"					=>	$totaltax,
											"extra"					=>	$totalextra,
											"total"					=>	$total,
											"created"				=>	$created,
											"expired"				=>	$expired
										 ),
										array(
											"validate"=>false
										));
				$transaction_id		=	$this->TransactionLog->getLastInsertId();
				$this->Cookie->write('Payment.transaction_id',$this->General->my_encrypt($transaction_id),600,false);
				
				
				//SEND INVOICE MAIL TO USER
				$fullname			=	$this->profile['Profile']['fullname'];
				$site_url			=	$this->settings['site_url'];
				$site_name			=	$this->settings['site_name'];
				$tgl_pemesanan		=	date("d-M-Y",$created);
				$batas_pembayaran	=	date("d-M-Y",$expired);
				$metode_pembayaran	=	$payment['PaymentMethod']["name"];
				$email_support		=	$this->settings['admin_mail'];
				$telp_support		=	"(021) 704 406 93";
				$link_konfirmasi_pembayaran		=	$this->settings['site_url']."Point/KonfirmasiPembayaran";
				
				$search 	= array('[fullname]', '[site_url]', '[site_name]', '[voucher_value]', '[invoice_id]', '[tgl_pemesanan]', '[total]', '[batas_pembayaran]', '[metode_pembayaran]', '[link_konfirmasi_pembayaran]','[email_support]','[telp_support]');
                $replace 	= array($fullname, $site_url, $site_name, $voucher_value, $invoice_id, $tgl_pemesanan, "Rp ".number_format($total,0,"","."), $batas_pembayaran, $metode_pembayaran, $link_konfirmasi_pembayaran,$email_support,$telp_support);
				$this->Action->EmailSave('user_invoice', $this->profile['User']['email'], $search, $replace,"","","TransactionLog",$transaction_id);
			}
			else
			{
				$message	=	reset($error);
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
	
	function KonfirmasiBeliPoin()
	{
		$this->set("active_code","beli_point");
		$transaction_id		=	$this->Cookie->read('Payment.transaction_id');
		$transaction_id		=	$this->General->my_decrypt($transaction_id);
		
		//JIKA SESSION TRANSACTION TELAH HABIS
		if(empty($transaction_id))
		{
			$this->redirect(array("controller"=>"Point","action"=>"BeliPoint"));
		}
		
		//JIKA USER BELUM LOGIN
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));
		}
		
		/*//FIND TRANSACTION ID*/
		$this->loadModel("TransactionLog");
		$this->TransactionLog->Bind1();
		$data	=	$this->TransactionLog->findById($transaction_id);
		
		//JIKA TRANSAKSI BERSTATUS TIDAK SAMA DENGAN WAITING FOR PAYMENT
		if(empty($data) or ((int)$data['TransactionLog']['status']) > -2 or $data['TransactionLog']['user_id'] != $this->user_id)
		{
			$this->redirect(array("controller"=>"Point","action"=>"BeliPoint"));
		}
		
		$this->set(compact("data"));
	}
	
	
	function KonfirmasiPembayaran($selected_trx_id = "")
	{
		$this->set("active_code","konfirmasi_pembayaran");
		$this->Session->write('back_url',$this->settings['site_url'].'Point/KonfirmasiPembayaran');
		
		//JIKA USER BELUM LOGIN
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));
		}
		
		//GET LIST INVOICE
		$this->loadModel("TransactionLog");
		$this->TransactionLog->Bind1();
		
		$list	=	$this->TransactionLog->find("all",array(
						"conditions"	=>	array(
							"TransactionLog.status"		=>	array("-2","0"),
							"TransactionLog.user_id"	=>	$this->user_id,
							"TransactionLog.expired > "	=>	time()
						),
						"order"	=>	array("TransactionLog.id DESC")
					));
					
		if(empty($list))
		{
			$this->render("konfirmasi_pembayaran_failed");
			return;
		}
		
		foreach($list as $k => $v)
		{
			$transactions[$v['TransactionLog']['id']]	=	$v['TransactionLog']['invoice_id']." - ".$v['TransactionLog']['voucher_value']." POIN";
		}
		$this->set(compact("transactions","selected_trx_id"));
	}
	
	function GetTotalRequestedPayment()
	{
		$status		=	false;
		$message	=	"Data not found";
		$data		=	null;
		
		//FIND TOTAL
		$this->loadModel("TransactionLog");
		$transaction_id	=	$_GET['trx_id'];
		$fTotal			=	$this->TransactionLog->findById($transaction_id);
		
		if(!empty($fTotal))
		{
			$status		=	true;
			$message	=	"Success";
			$data		=	"Rp ".number_format($fTotal['TransactionLog']['total'],0,"",".").",-";
		}
		
		$out		=	array("status"=>$status,"message"=>$message,"data"=>$data);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function SeeNotice($transaction_id)
	{
		$status		=	false;
		$message	=	"Data not found";
		$data		=	null;
		
		$this->loadModel("TransactionPendingLog");
		$data		=	$this->TransactionPendingLog->find("first",array(
							"conditions"	=>	array(
								"TransactionPendingLog.transaction_log_id"	=>	$transaction_id
							),
							"fields"	=>	array(
								"TransactionPendingLog.message"
							),
							"order"	=>	array(
								"TransactionPendingLog.id DESC"
							)
						));
		if(!empty($data))
		{
			$status		=	true;
			$message	=	nl2br($data["TransactionPendingLog"]["message"]);
		}
		$out		=	array("status"=>$status,"message"=>$message,"data"=>$data);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function GetPaymentMethod()
	{
		$status		=	false;
		$message	=	"Data not found";
		$data		=	null;
		
		//FIND TOTAL
		$this->loadModel("TransactionLog");
		$this->TransactionLog->Bind1();
		
		$transaction_id	=	$_GET['trx_id'];
		$fPayment			=	$this->TransactionLog->findById($transaction_id);
		
		if(!empty($fPayment))
		{
			$status		=	true;
			$message	=	"Success";
			$data		=	$fPayment['PaymentMethod']["name"];
		}
		
		$out		=	array("status"=>$status,"message"=>$message,"data"=>$data);
		echo json_encode($out);
		if($_GET['debug'])
		{
			$this->render('sql');
		}
		$this->autoRender	=	false;
	}
	
	function KonfirmasiPembayaranProcess()
	{
		$status		=	false;
		$message	=	"Data not found";
		$data		=	null;
		
		//START PROCESS
		$this->loadModel("Confirmation");
		$this->loadModel("TransactionLog");
		$this->TransactionLog->Bind1();
		
		if(!empty($_REQUEST["data"]))
		{
			App::import('Sanitize');
			$this->data								=	$_REQUEST["data"];
			$this->data["Confirmation"]["user_id"]	=	$this->user_id;
			$this->data['Confirmation']['message']	=	Sanitize::html($this->data['Confirmation']['message']);
			
			//GET DETAIL TRANSACTION
			$trx_id									=	$this->data["Confirmation"]["transaction_log_id"];
			$fTransaction							=	$this->TransactionLog->findById($trx_id);
			$this->data['Confirmation']['transfer_required_value']	=	$fTransaction["TransactionLog"]["total"];
			
			$this->Confirmation->set($this->data);
			$this->Confirmation->DefaultValidate();
			$error	=	$this->Confirmation->InvalidFields();
			
			if(empty($error))
			{
				//SAVE
				$save		=	$this->Confirmation->save(
									array(
										"transaction_log_id"		=>	$this->data["Confirmation"]["transaction_log_id"],
										"user_id"					=>	$this->user_id,
										"transfer_date"				=>	$this->data['Confirmation']['transfer_date'],
										"transfer_required_value"	=>	$this->data['Confirmation']['transfer_required_value'],
										"bank_name"					=>	$this->data['Confirmation']['bank_name'],
										"bank_account_name"			=>	$this->data['Confirmation']['bank_account_name'],
										"message"					=>	$this->data['Confirmation']['message']
									),
									array("validate"	=>	false)
								);
				$confirmation_id	=	$this->Confirmation->getLastInsertId();
				
				//UPDATE TRANSACTION LOG
				$save		=	$this->TransactionLog->updateAll(
									array(
										"status"			=>	"'-1'"
									),
									array(
										"TransactionLog.id"	=>	$this->data["Confirmation"]["transaction_log_id"]
									)
								);
				
				
				//SEND MAIL TO ADMIN
				$logo_url			=	$this->settings['logo_url'];
				$fullname			=	$this->profile['Profile']['fullname'];
				$email				=	$this->profile['User']['email'];
				
				$invoice_id			=	$fTransaction["TransactionLog"]["invoice_id"];
				$voucher_value		=	$fTransaction["TransactionLog"]["voucher_value"];
				$total				=	$fTransaction["TransactionLog"]["total"];
				
				$tgl_pembelian		=	date("d-M-Y",$fTransaction["TransactionLog"]["created"]);
				$batas_pembayaran	=	date("d-M-Y",$fTransaction["TransactionLog"]["expired"]);
				$metode_pembayaran	=	$fTransaction['PaymentMethod']["name"];
				
				$site_name			=	$this->settings['site_name'];
				$site_url			=	$this->settings['site_url'];
				
				$link_konfirmasi_pembayaran		=	$this->settings['site_url']."Point/KonfirmasiPembayaran";
				
				$search 	= array('[logo_url]','[fullname]','[email]','[invoice_id]','[voucher_value]','[total]','[tgl_pembelian]','[batas_pembayaran]','[metode_pembayaran]','[site_name]','[site_url]');
                $replace 	= array($logo_url,$fullname,$email,$invoice_id,$voucher_value,number_format($total,0,"","."),$tgl_pembelian,$batas_pembayaran,$metode_pembayaran,$site_name,$site_url);
				
				
				$this->Action->EmailSave('admin_alert_payment_confirmation', $this->settings['admin_mail'], $search, $replace,"","","Confirmation",$confirmation_id);
				
				
				if(!empty($save))
				{
					$message	=	array("url"=>$this->settings['site_url']."Point/KonfirmasiPembayaranSuccess");
					$data		=	NULL;
					$status		=	true;
				}
				else
				{
					$message	=	array("user_id"=>"Failed to save");
					$data		=	NULL;
					$status		=	false;
				}
			}
			else
			{
				$err	=	array();
				foreach($this->data['Confirmation'] as $k=>$v)
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
				$message	=	$err;
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
	
	function KonfirmasiPembayaranSuccess()
	{
		
	}
	
	function TransactionHistory($tab_active=0)
	{
		$this->Session->write('back_url',$this->settings['site_url'].$this->params["url"]["url"]);
		$this->set("active_code","transaction_history");
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
		
		$status	=	array("-3"=>"expired","-2"=>"Waiting for confirmation","-1"=>"User Confirm","0"=>"Pending","1"=>"Success");
		
		$this->Session->delete('SearchTransactionLog');
		$this->Session->delete('Cond.TransactionLog');
		
		
		$this->loadModel("Voucher");
		$voucher	=	$this->Voucher->find("list",array(
							"conditions"	=>	array(
								"Voucher.status"	=>	1
							),
							"order"	=>	array(
								"Voucher.value ASC"
							)
						));
		$this->set(compact("status","tab_active","voucher"));
	}
	
	
	function TransactionHistoryList($status,$reset=0)
	{
		$this->layout	=	"ajax";
		$this->loadModel("TransactionLog");
		$this->TransactionLog->VirtualFieldActivated();
		$viewpage		=	empty($this->params['named']['limit']) ? 3 : $this->params['named']['limit'];
		$order			=	array('TransactionLog.id DESC');
		$fields			=	array("TransactionLog.*");
		
		//DELETE SESSION
		
		if($_POST['reset']=="1" or $reset==1)
		{
			$this->Session->delete('Cond.TransactionLog');
			unset($this->data);
		}
		
		//DEFINE FILTERING
		$cond_search		=	array();
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		if(!empty($this->data))
		{
			$this->Session->delete('Cond.TransactionLog');
			if(!empty($this->data['Search']['invoice_id']))
			{
				$cond_search['TransactionLog.invoice_id']	=	$this->data['Search']['invoice_id'];
			}
			
			if(!empty($this->data['Search']['voucher_id']))
			{
				$cond_search['TransactionLog.voucher_id']	=	$this->data['Search']['voucher_id'];
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
					
					$cond_search["TransactionLog.created BETWEEN ? AND ?"]		=	array(strtotime(date("Y-m-d",$date1)." 00:00:00"),strtotime(date("Y-m-d",$date2)." 23:59:59"));
				}
				else
					$cond_search["Product.created BETWEEN ? AND ?"]		=	array(strtotime(date("Y-m-d",strtotime($string[0]))." 00:00:00"),strtotime(date("Y-m-d",strtotime($string[0]))." 23:59:59"));
				
			}
			$this->Session->write("Cond.TransactionLog",$cond_search);
		}
		
		$filter_paginate	=	($status == "100") ?
								array(
									  'TransactionLog.user_id' 			=>  $this->user_id
							    ) : 
								array(
									  
									  'TransactionLog.user_id' 		=>  $this->user_id,
									  'TransactionLog.status'		=> 	$status
							    );
								
		/*$filter_paginate	=	($status == "-2" or $status == "0" or $status == "100") ?
								array_merge(
									array(
										'TransactionLog.expired > UNIX_TIMESTAMP()',
									),
									$filter_paginate
								)
								: 
								$filter_paginate;*/
		
		//DATA
		$this->paginate		=	array(
			'TransactionLog'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'fields'	=>	$fields
			)
		);
		
		$ses_cond		= $this->Session->read("Cond.TransactionLog");
		$cond_search	= isset($ses_cond) ? $ses_cond : array();
		$data			= $this->paginate('TransactionLog',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['TransactionLog']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['TransactionLog']['pageCount'];
		}
		
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set(compact("data","status","page","viewpage"));
	}
	
	function GetSumStatus($status)
	{
		$this->layout		=	"json";
		
		$cond				=	($status == "100") ?
								array(
									  'TransactionLog.user_id' 			=>  $this->user_id
							    ) : 
								array(
									  
									  'TransactionLog.user_id' 		=>  $this->user_id,
									  'TransactionLog.status'		=> 	$status
							    );
		
		/*$cond				=	($status == "-2" or $status == "0" or $status == "100") ?
								array_merge(
									array(
										'TransactionLog.expired > UNIX_TIMESTAMP()',
									),
									$cond
								)
								: 
								$cond;*/
								
											
		$this->loadModel('TransactionLog');
		$data	=	$this->TransactionLog->find("count",array(
						'conditions'	=>	$cond
					));
		$this->set("data",$data);
		$this->render(false);
	}
}
?>