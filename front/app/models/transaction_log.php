<?php
class TransactionLog extends AppModel
{
	var $name		= 'TransactionLog';
	
	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'SStatus'	=> '
			IF(TransactionLog.status=\'-3\',\'Expired\',
			IF(TransactionLog.status=\'-2\',\'Waiting for confirmation\',
			IF(TransactionLog.status=\'-1\',\'User Confirm\',
			IF(TransactionLog.status=\'0\',\'Pending\',\'Success\'
			))))'
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
						)
					)
				), $reset
        );
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
}
?>