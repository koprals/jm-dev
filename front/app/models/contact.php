<?php
class Contact extends AppModel
{
	function ValidateSendPm()
	{
		$this->validate	=	array(
			'from' => array(
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Nama anda terlalu pendek, minimal jumlah karakter nama adalah 3 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Tuliskan nama anda.'	
				),
			),
			'email' => array(
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda tidak benar, co: abyfajar@jualanmotor.com.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
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