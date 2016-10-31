<?php
class UserLogs extends AppModel
{
	var $name		= 'UserLogs';
	var $useTable 	= 'user_logs';
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
	var $hasOne = array(
		'PointsHistory' 		=> array(
	        'className'    	=> 'PointsHistory',
			'foreignKey'	=> 'ref_id',
			'conditions'	=> "PointsHistory.ref_table='UserLogs'",
	        'dependent'    	=>  true
	    ),
	);
}

?>