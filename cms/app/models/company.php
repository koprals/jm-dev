<?php
class Company extends AppModel
{
	var $name		=	"Company";
	var $useTable	=	"companies";
	var $belongsTo 	= array(
		'User' => array(
			'className' 	=> 'User',
			'foreignKey' 	=> 'user_id'
		)
	);
}
?>