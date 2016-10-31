<?php
class NewsCategory extends AppModel
{
	var $useTable	=	"news_category";
	var $hasMany	=	array(
							'NewsSubcategory' => array(
								'className' 	=> 'NewsSubcategory',
								'foreignKey' 	=> 'news_category_id'
							)
						);
						
	var $belongsTo 	= 	array(
							'NewsTypeId' => array(
								'className' 	=> 'NewsTypeId',
								'foreignKey' 	=> 'type_id',
								"useTable"		=>	"news_type_id"
							)
						);
}
?>