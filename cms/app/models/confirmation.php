<?php
class Confirmation extends AppModel
{
	var $name	=	"Confirmation";
	
	function DefaultValidate()
	{
		$this->validate 	= array(
			'transaction_log_id' => array(
				'UserIsOwner' => array(
					'rule' => "UserIsOwner",
					'message' => 'Maaf transaksi anda tidak ditemukan.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Maaf kode invoice anda tidak ditemukan.'	
				)
			),
			'user_id' => array(
				'IsUserActive' => array(
					'rule'		=>	"IsUserActive",
					'message'	=>	'Maaf user anda belum aktif, aktifkan user anda dengan mengklik link yang terdapat pada email verifikasi yang kami kirimkan melalui email anda atau hubungi customer service kami di admin@jualanmotor.com.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Maaf session anda telah habis, silahkan login kembali.'	
				)
			),
			'transfer_date' => array(
				'BiggerThanNow' => array(
					'rule' => "BiggerThanNow",
					'message' => 'Maaf format tanggal transfer anda salah.'	
				),
				'date' => array(
					'rule' => "date",
					'message' => 'Maaf format tanggal transfer anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan anda masukkan tanggal anda mentranfer.'	
				)
			),
			'transfer_required_value' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukka nilai yang harus di transfer.'	
				)
			),
			"bank_name" => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan anda masukkan nama bank tempat anda mentransfer.'	
				)
			),
			"bank_account_name" => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan anda masukkan nama account bank tempat anda mentransfer.'	
				)
			)
		);
	}
	
	
	
	function UserIsOwner($fields	=	array())
	{
		$TransactionLog	=	ClassRegistry::Init("TransactionLog");
		
		foreach($fields as $k => $v)
		{
			$user_id			=	$this->data[$this->name]["user_id"];
			$trx_id				=	$v;
			$fTransactionLog	=	$TransactionLog->find("first",array(
										"conditions"	=>	array(
											"TransactionLog.id"				=>	$trx_id,
											"TransactionLog.user_id"		=>	$user_id,
											"TransactionLog.status"			=>	array("-2","0")
										)
									));
									
			if(empty($fTransactionLog))
			{
				return false;
			}
		}
		return true;
	}
	
	function IsUserActive($fields	=	array())
	{
		$User	=	ClassRegistry::Init("User");
		
		foreach($fields as $k => $v)
		{
			$user_id			=	$v;
			$fUser				=	$User->find("first",array(
										"conditions"	=>	array(
											"User.id"						=>	$user_id,
											"User.userstatus_id"			=>	"1",
										)
									));
									
			if(empty($fUser))
			{
				return false;
			}
		}
		return true;
	}
	
	function BiggerThanNow($fields	=	array())
	{
		foreach($fields as $k => $v)
		{
			$transfer_date			=	strtotime($v);
			if($transfer_date > time())
			{
				return false;
			}
		}
		return true;
	}
}
?>