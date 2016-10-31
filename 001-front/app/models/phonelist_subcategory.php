<?php
class PhonelistSubcategory extends AppModel
{
	var $name		=	"PhonelistSubcategory";
	var $useTable	=	"phonelist_subcategory";
	
	var $belongsTo 	= 	array(
							'PhonelistLocation' => array(
								'className' 	=> 'PhonelistLocation',
								'foreignKey' 	=> 'phonelist_location_id',
								"useTable"		=> 'phonelist_location'
							)
						);
}
?>