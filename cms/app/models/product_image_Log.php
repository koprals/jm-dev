<?php
class ProductImageLog extends AppModel
{
	var $name		= 'ProductImageLog';
	
	function GetImages($producteditlog_id)
	{
		$img	=	array();
		$data	=	$this->find("all",array(
			'conditions'	=>	array(
				'ProductImageLog.producteditlog_id'	=>	$producteditlog_id
			),
			'fields'	=>	array('ProductImageLog.id')
		));
		return $data;
	}
}
?>