<?php 
class ExtendedPhone extends AppModel
{
	var $name	=	"ExtendedPhone";
	var $belongsTo 	= array(
		'User' => array(
			'className' 	=> 'User',
			'foreignKey' 	=> 'user_id'
		)
	);
	
	var $validate 	= array(
			'phone' => array(
				'minLength' => array(
					'rule' => array('minLength',6),
					'message' => 'Minimum jumlah karakter telpon adalah 6 karakter.'
				),
				'postal'	=> array(
					'rule' => array('postal','/^([0-9]+)$/'),
					'message' => 'No telpon harus berupa angka, <br>tidak boleh ada karakter ataupun spasi.'
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan no telp anda.'	
				)
			)
		);
	
	function GetAllPhone($profile)
	{
		//DEFINE PHONE
		$phone	=	array();
		$phone0	=	array();
		$phone1	=	$profile['Profile']['phone'];
		$phone2	=	$this->find("list",array(
						'conditions'	=>	array(
							'ExtendedPhone.user_id'	=>	$profile['Profile']['user_id']
						),
						'fields'	=>	array('phone')
					));
		$phone3	=	$profile['Company']['phone'];
		
		if(!empty($phone1))
		{
			$phone0	=	array_merge($phone0,array($phone1));
		}
		if(!empty($phone2))
		{
			$phone0	=	array_merge($phone0,$phone2);
		}
		if(!empty($phone3))
		{
			$phone0	=	array_merge($phone0,array($phone3));
		}
		
		foreach($phone0 as $phone0)
		{
			$phone["$phone0 "]	= $phone0;
		}
		return $phone;
	}
}
?>