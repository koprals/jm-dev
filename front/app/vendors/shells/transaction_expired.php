<?php
class TransactionExpiredShell extends Shell
{
	var $name 			=	'TransactionExpired';
	
	var $General;
	var $TransactionLog;
	
	function initialize()
	{
		$this->TransactionLog					=	ClassRegistry::init('TransactionLog');
	}
	
	function Main()
	{
		Configure::write("debug",3);
		$find	=	$this->TransactionLog->find("all",array(
						"conditions"	=>	array(
							'TransactionLog.expired < UNIX_TIMESTAMP()'
						)
					));
					
		$data	=	$this->TransactionLog->updateAll(
						array(
							'status'	=>	"'-3'"
						),
						array(
							'TransactionLog.expired < UNIX_TIMESTAMP()',
							'TransactionLog.status < '	=>	"1"
						)
					);
	}
}
?>