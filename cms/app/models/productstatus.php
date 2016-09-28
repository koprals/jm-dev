<?php
class Productstatus extends AppModel
{
	var $name		=	"Productstatus";
	
	
	function DisplayStatus()
	{
		$status = $this->find('list', array(
				 	'order'			=>	array('Productstatus.name ASC')
				 ));
		return $status;
	}
	
	function GetNameStatus($id)
	{
		$fstatus = $this->findById($id);
		return $fstatus['Productstatus']['name'];
	}
}
?>