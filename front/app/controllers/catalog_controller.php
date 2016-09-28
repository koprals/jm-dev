<?php
class CatalogController extends AppController
{
	var $name	=	"Catalog";
	var $uses	=	array('Category');
	
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function Add()
	{
		
	}
}
?>