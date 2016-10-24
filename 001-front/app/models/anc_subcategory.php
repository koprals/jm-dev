<?php
class AncSubcategory extends AppModel
{
	var $name		=	"AncSubcategory";
	var $useTable	=	"anc_subcategory";
	var $order		=	"AncSubcategory.id DESC";
	var $belongsTo 	= array(
		'AncCategory' => array(
			'className' 	=>	'AncCategory',
			'foreignKey' 	=>	'anc_category_id'
		));
		
	var $hasMany	=	array(
							'AncFiles' => array(
								'className' 	=> 'AncFiles',
								'foreignKey' 	=> 'anc_subcategory_id'
							)
						);
						
	var $virtualFields = array(
		'Time1'		=> 'FROM_UNIXTIME(AncSubcategory.created,\'%d %b %Y\')'
	);
	
}
?>