<?php
class UserService extends AppModel
{
	var $name		=	"UserService";
	var $useTable	=	"user_services";
	
	var $belongsTo 	= array(
		'User' => array(
			'className' 	=> 'User',
			'foreignKey' 	=> 'user_id'
		),
		'Service' => array(
			'className' 	=> 'Service',
			'foreignKey' 	=> false,
			"conditions"	=> "Service.code = UserService.service_code"
		)
	);
}
?>