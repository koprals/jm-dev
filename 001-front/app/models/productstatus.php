<?php
class Productstatus extends AppModel
{
	var $name		=	"Productstatus";
	
	
	function ShowStatus()
	{
		$data	=	$this->find("all",array(
						'conditions'	=>	array(
							'Productstatus.id !='	=>	-10
						),
						'order'	=>	array('Productstatus.name ASC')
					));
		return $data;
	}
}
?>