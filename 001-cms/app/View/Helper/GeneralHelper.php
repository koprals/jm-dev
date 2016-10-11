<?php
if (!class_exists('TextHelper')) {
	App::import('Helper', 'Text');
}
class GeneralHelper extends AppHelper
{
	var $options	=	array(
							'button' 	=> 	true,
							'action'	=>	'javascript:void(0)',
							'span'		=>	false
						);
	
	function truncate($text,$length,$options=array())
	{
		$options		=	array_merge($this->options,$options);
		$TextHelper		=	new TextHelper();
		$return			=	"";
			
		if(!empty($text))
		{
			if(strlen($text) > $length)
			{
				$return		.=	($options['span'] == true) ? "<span id='show_".$options['id']."'>" : "";
				$return		.=	$TextHelper->truncate($text,$length,$options);
				$return		.=	($options['button'] == true) ? '<input type="button" value="open" onclick="'.$options['action'].'"/>': '';
				$return		.=	($options['span'] == true) ? "</span>" : "";
				$return		.=	($options['span'] == true) ? "<span id='hide_".$options['id']."' style='display:none;'>" : "";
				$return		.=	($options['span'] == true) ? nl2br(chunk_split($text,$length,"\r\n")) : "";
				$return		.=	($options['button'] == true) ? '<br /><input type="button" value="close" onclick="'.$options['action'].'"/>': '';
				$return		.=	($options['span'] == true) ? "</span>" : "";
			}
			else
			{
				$return 	=	nl2br($text);
			}
		}
		return $return;
	}
	
	
	function my_encrypt($string, $key="aby") {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
	
		return base64_encode($result);
	}
	
	function my_decrypt($string, $key="aby") {
		$result = '';
		$string = base64_decode($string);
	
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result; 
	}
	
	function IsEmptyVal($string,$default_val="-") {
		if(strlen($string)>0 && trim($string) != "" && trim($string) != "<br>")
		{
			return $string;
		}
		return $default_val; 
	} 
	
	function AuthorName($author=array()) {
		
		$result	=	"";
		$count	=	0;
		$sum	=	count($author);
		foreach($author as $author)
		{
			$count++;
			
			if($count == ($sum-1))
				$result	.=	$author["Author"]["name"]." & " ;
			else
				$result	.=	$author["Author"]["name"].", " ;
			
		}
		return substr($result,0,-2); 
	}
}
?>