<?php
class RandomUser extends AppModel
{
	var $name			=	"RandomUser";
	var $useTable		=	"random_user";
	var $primaryKey		=	"rand_id";
	
	var $belongsTo 	= array(
		'User' => array(
			'className' 	=> 'User',
			'foreignKey' 	=> 'user_id'
		),
		'Profile' => array(
			'className' 	=> 'Profile',
			'foreignKey' 	=> false,
			'conditions'	=> 'Profile.user_id = RandomUser.user_id'
		)
	);
}
?>