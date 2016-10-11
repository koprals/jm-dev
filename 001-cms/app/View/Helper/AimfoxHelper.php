<?php
App::uses('AppHelper', 'View/Helper');

class AimfoxHelper extends AppHelper {

	public $helpers = array('Html');
	
	public function IsEmptyText($text)
	{
		$text	=	trim($text);
		if(empty($text))
			return "-";
		else
			return $text;
	}
}
