<?php
class AdminLog extends AppModel
{
	var $name		= 'AdminLog';
	var $useTable 	= 'admin_logs';
	var $belongsTo 	= array(
		'Users' => array(
			'className' 	=> 'Users',
			'foreignKey' 	=> 'user_id'
		),
		'ActionTypes' => array(
			'className' 	=> 'ActionTypes',
			'foreignKey' 	=> 'actionID'
		)
	);
	
}

?>