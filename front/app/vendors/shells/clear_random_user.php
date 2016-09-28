<?php
class ClearRandomUserShell extends Shell
{
	var $name 			=	'ClearRandomUser';
	var $uses 			=	array("RandomUser");
	var $General;
	
	function Main()
	{
		Configure::write("debug",3);
		$delete			=	$this->RandomUser->deleteAll("1 AND `RandomUser`.`user_id` IS NULL");
		echo $delete;
	}
}
?>