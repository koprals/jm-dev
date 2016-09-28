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
}
?>