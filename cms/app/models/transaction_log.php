<?php
class TransactionLog extends AppModel
{
	var $name		= 'TransactionLog';
	
	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'SStatus'	=> '
			IF(TransactionLog.status=\'-2\',\'Waiting for confirmation\',
			IF(TransactionLog.status=\'-1\',\'User Confirm\',
			IF(TransactionLog.status=\'0\',\'Pending\',\'Success\'
			)))'
		);
	}
	
	function Bind1($reset = true)
	{
		$this->bindModel(
                array(
					'belongsTo'	=>	array(
						'PaymentMethod' => array(
							'className' 	=> 'PaymentMethod',
							'foreignKey' 	=> 'payment_method_id',
							"fields"		=>	array("PaymentMethod.name")
						),
						'User' => array(
							'className' 	=> 'User',
							'foreignKey' 	=> 'user_id',
							"fields"		=>	array("User.email")
						),
						'Profile' => array(
							'className' 	=> 'Profile',
							'foreignKey' 	=> false,
							"conditions"	=>	array("TransactionLog.user_id = Profile.user_id")
						),
					),
					'hasOne'	=>	array(
						'TransactionPendingLog' => array(
							'className' 	=> 'TransactionPendingLog',
							'foreignKey' 	=> 'transaction_log_id',
							"fields"		=>	array("TransactionPendingLog.message"),
							"order"			=>	array("TransactionPendingLog.id DESC")
						)
					)
				), $reset
        );
	}
	
	function ValidateEdit()
	{
		$this->validate 	= array(
			'id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Maaf id transaksi tidak ditemukan.'	
				)
			),
			'status' => array(
				"IsNoticeRquired"	=>	array(
					'rule'		=>	"IsNoticeRquired",
					'message'	=>	'Silahkan berikan alasan kepada user, alasan mengapa transaksi dipending'
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Pilih status pembayaran.'
				)
			),
			'notice' => array(
				"IsNoticeRquired"	=>	array(
					'rule'		=>	"IsNoticeRquired",
					'message'	=>	'Silahkan berikan alasan kepada user, alasan mengapa transaksi dipending'
				)
			)
		);
	}
	
	
	function IsNoticeRquired($fields	=	array())
	{
		foreach($fields as $k => $v)
		{
			$status	=	$this->data[$this->name]["status"];
			if($status == "0")
			{
				if(empty($v))
				return false;
			}
		}
		return true;
	}
	
	function GetInvoiceId() 
	{
		
		// Generate the Transaction Id
		$tmp_code = date("ymdHis").rand(1000, 9999);
		
		// Check if is already been used
		$ver	=	$this->find('first',array(
			'fields'		=> 'invoice_id',
			'conditions'	=> array('invoice_id' => $tmp_code)
		));
		
		if(is_array($ver))
		{
			$tmp_code = $this->GetTrxId();
		}
		return $tmp_code;
	}
	
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