<?php
class ProductHelper extends AppHelper
{
	var $name	=	"Product";
	
	function LinkStatus($name,$target="_self")
	{
		switch(strtolower($name))
		{
			case "waiting approval after editing":
				$contr	=	'AfterEditingProduct/Index';
				break;
			case "waiting approval":
				$contr	=	'WaitingApprovalProduct/Index';
				break;
			case "approve":
				$contr	=	'ApproveProduct/Index';
				break;
			case "editing required":
				$contr	=	'EditingRequiredProduct/Index';
				break;
			default : 
		}
		
		$link	=	'<a href="'.$this->webroot.$contr.'" target="'.$target.'" class="table_text1">'.$name.'</a>';
		return $link;
	}
	
	function BreadCrumb($arr_breadcrumb=array())
	{
		$breadcrumb	=	"";
		if(!empty($arr_breadcrumb))
		{
			foreach($arr_breadcrumb as $text=>$link)
			{
				if(reset($arr_breadcrumb) == $link)
				{
					$breadcrumb	.=	'<a href="'.$link.'" class="nav_2">'.$text.'</a>';
				}
				elseif(end($arr_breadcrumb)	==	$link)
				{
					$breadcrumb	.=	'<span class="text2">&raquo;</span><div class="text3">'.$text.'</div>';
				}
				else
				{
					$breadcrumb	.=	'<span class="text2">&raquo;</span><a href="'.$link.'" class="nav_2">'.$text.'</a>';
				}
			}
		}
		return $breadcrumb;
	}
}
?>