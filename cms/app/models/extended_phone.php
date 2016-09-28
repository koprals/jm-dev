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
					'message' => 'Minimum jumlah karakter telpon adalah 6 karakter.',
					'allowEmpty'	=>	true
				),
				'postal'	=> array(
					'rule' => array('postal','/^([0-9]+)$/'),
					'message' => 'No telpon harus berupa angka, <br>tidak boleh ada karakter ataupun spasi.',
					'allowEmpty'	=>	true
				)
			)
		);
}
?>