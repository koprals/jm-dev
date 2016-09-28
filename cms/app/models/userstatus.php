<?php
class Userstatus extends AppModel
{
	var $name		=	"Userstatus";
	var $usetable	=	"userstatus";
	
	function DisplayStatus()
	{
		$status = $this->find('list', array(
				 	'order'			=>	array('Userstatus.name ASC'),
					'conditions'	=>	array('Userstatus.id >' => -10)
				 ));
		return $status;
	}
}
?>