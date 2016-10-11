<?php
class CmsMenu extends AppModel
{
	function BindDefault($reset	=	true)
	{
		$this->bindModel(array(
			"hasMany"	=>	array(
				"CmsSubmenu"	=>	array(
					"className"		=>	"CmsSubmenu",
					"foreignKey"	=>	"cms_menu_id",
					"conditions"	=>	array(
						"CmsSubmenu.status"	=>	"1"
					)
				)
			)
		),$reset);
	}
}
?>