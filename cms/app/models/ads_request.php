<?php
class AdsRequest extends AppModel
{
	var $name		=	"AdsRequest";
	var $useTable	=	"ads_request";
	
	var $belongsTo 	= array(
		'AdsType' => array(
			'className' 	=> 'AdsType',
			'foreignKey' 	=> 'ads_type_id'
		)
	);
}
?>