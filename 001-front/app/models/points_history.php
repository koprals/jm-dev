<?php
class PointsHistory extends AppModel
{
	var $name		= 'PointsHistory';
	var $useTable 	= 'points_history';
	
	var $belongsTo 	= array(
		'Users' => array(
			'className' 	=> 'Users',
			'foreignKey' 	=> 'user_id'
		),
		'UserLogs' => array(
			'className' 	=> 'UserLogs',
			'foreignKey' 	=> 'ref_id',
			'conditions'	=> "PointsHistory.ref_table='UserLogs'"
		)
	);
}
?>