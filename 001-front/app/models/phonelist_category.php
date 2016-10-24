<?php
class PhonelistCategory extends AppModel
{
	var $name		=	"PhonelistCategory";
	var $useTable	=	"phonelist_category";
	
	var $hasMany	=	array(
							'PhonelistSubcategory' => array(
								'className' 	=> 'PhonelistSubcategory',
								'foreignKey' 	=> 'phonelist_category_id'
							)
						);
						
	var $belongsTo 	= 	array(
							'PhonelistTypeId' => array(
								'className' 	=> 'PhonelistTypeId',
								'foreignKey' 	=> 'type_id',
								"useTable"		=>	"phonelist_type_id"
							)
						);
}
?>