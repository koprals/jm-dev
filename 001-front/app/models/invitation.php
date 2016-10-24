<?php
class Invitation extends AppModel
{
	function DefaultValidate()
	{
		$this->validate	=	array(
			'name' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Tuliskan nama yang akan diundang.'	
				),
			),
			'email' => array(
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda tidak benar, co: abyfajar@jualanmotor.com.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email yang akan diundang.'	
				),
			),
			'username' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan username yang akan diundang.'	
				),
			),
			'password' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password yang akan diundang.'	
				),
			),
			'message' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Tuliskan pesan anda.'	
				),
			)
		);
	}
	
	function ValidateLogin()
	{
		$this->validate	=	array(
			'username' => array(
				'is_exists' => array(
					'rule' => "is_exists",
					'message' => 'Maaf anda tidak ada dalam daftar undangan kami, masukkan email anda pada form "invite me" di bawah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan username anda.'	
				),
			),
			
			'password' => array(
				'password' => array(
					'rule' => "password",
					'message' => 'Maaf password anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password anda.'	
				),
			),
		);
	}
	
	function ValidateInvite()
	{
		$this->validate	=	array(
			'email' => array(
				'is_requested' => array(
					'rule' => "is_requested",
					'message' => 'Maaf anda telah mengirimkan permohonan untuk diundang menggunakan email ini sebelumnya.'	
				),
				'is_email_exists' => array(
					'rule' => "is_email_exists",
					'message' => 'Maaf email ini sudah masuk di daftar undangan kami, periksa folder spam email anda jika email dari kami tidak ada dalam daftar inbox anda atau email kami di support@jualanmotor.com untuk meminta bantuan admin kami.'	
				),
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
				),
			),
		);
	}
	
	
	function is_requested($fields	= array())
	{
		foreach($fields as $nama => $nilai)
		{
			$find	=	$this->find("first",array(
							"conditions"	=>	array(
								"LOWER(email)"	=>	strtolower($nilai),
								"message IS NULL"
							)
						));
			if($find)
			{
				return false;
			}
		}
		return true;
	}
	
	
	function is_email_exists($fields	= array())
	{
		foreach($fields as $nama => $nilai)
		{
			$find	=	$this->find("first",array(
							"conditions"	=>	array(
								"LOWER(email)"	=>	strtolower($nilai),
								"message IS NOT NULL"
							)
						));
			if($find)
			{
				return false;
			}
		}
		return true;
	}
	
	function is_exists($fields	= array())
	{
		foreach($fields as $nama => $nilai)
		{
			$find	=	$this->find("first",array(
							"conditions"	=>	array(
								"LOWER(username)"	=>	strtolower($nilai)
							)
						));
			if($find)
			{
				return true;
			}
		}
		return false;
	}
	
	function password($fields	= array())
	{
		$username	=	$this->data[$this->name]["username"];
		$find		=	$this->find("first",array(
							"conditions"	=>	array(
								"LOWER(username)"	=>	strtolower($username)
							)
						));
		App::Import("Component","General");
		$General	=	new GeneralComponent();
		if($find)
		{
			foreach($fields as $nama => $nilai)
			{
				if($General->my_encrypt($nilai) != $find[$this->name]["password"])
				{
					return false;
				}
			}
		}
		return true;
	}
}
?>