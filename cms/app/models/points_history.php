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
		),
		'ActionTypes' => array(
			'className' 	=> 'ActionTypes',
			'foreignKey' 	=> false,
			'conditions'	=> "UserLogs.actionID = ActionTypes.id"
		)
	);
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
	    $parameters = compact('conditions');
	    $this->recursive = $recursive;
	    $count = $this->find('count', array_merge($parameters, $extra));
	    if (isset($extra['group'])) {
	    	$count = $this->find('all', array_merge($parameters, $extra));
	        $count = $this->getAffectedRows();
	    }
	   
	    return $count;
	}
	
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
	    
		
		if(empty($order)){
	        $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
	    }
		
	    $group = $extra['group'];
	    return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
	}
}
?>