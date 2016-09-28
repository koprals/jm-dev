<?php
class Profile extends AppModel
{
	var $name		=	"Profile";
	var $useTable	=	"profiles";
	
	var $belongsTo 	= array(
		'User' => array(
			'className' 	=> 'User',
			'foreignKey' 	=> 'user_id'
		)
	);
}
?>