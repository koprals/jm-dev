<?php
class GeneralComponent extends Object 
{
	function CropSentence ($strText, $intLength, $strTrail) 
	{
		$wsCount = 0;
		$intTempSize = 0;
		$intTotalLen = 0;
		$intLength = $intLength - strlen($strTrail);
		$strTemp = "";
	
		if (strlen($strText) > $intLength) {
			$arrTemp = explode(" ", $strText);
			foreach ($arrTemp as $x) {
				if (strlen($strTemp) <= $intLength) $strTemp .= " " . $x;
			}
			$CropSentence = $strTemp . $strTrail;
		} else {
			$CropSentence = $strText;
		}
	
		return $CropSentence;
	}
	
	function copy_directory( $source, $destination ) {
		if ( is_dir( $source ) ) {
			@mkdir( $destination );
			$directory = dir( $source );
			while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
				if ( $readdirectory == '.' || $readdirectory == '..' ) {
					continue;
				}
				$PathDir = $source . '/' . $readdirectory; 
				if ( is_dir( $PathDir ) ) {
					$this->copy_directory( $PathDir, $destination . '/' . $readdirectory );
					continue;
				}
				copy( $PathDir, $destination . '/' . $readdirectory );
			}
	 
			$directory->close();
		}else {
			copy( $source, $destination );
		}
	}

	
	function RmDir($filepath){
		
		if (is_dir($filepath) && !is_link($filepath))
		{
			if ($dh = opendir($filepath))
			{
				while (($sf = readdir($dh)) !== false)
				{
					if ($sf == '.' || $sf == '..')
					{
						continue;
					}
					$filepath = (substr($filepath, -1) != "/")? $filepath."/":$filepath;
					if (!$this->RmDir($filepath.$sf))
					{
						echo ($filepath.$sf.' could not be deleted.');
					}
				}
				closedir($dh);
			}
			return rmdir($filepath);
		}
		return unlink($filepath);
	} 
	
	function getContent($destination, $source){
		$filename 	= $destination;
		$handle 	= fopen("$source", "rb");
		if($handle)
		{
	  		$somecontent = stream_get_contents($handle);
	  		fclose($handle);
	  		$handle = fopen($filename, 'wb');
	 
	  		if($handle)
			{
				if (fwrite($handle, $somecontent) === FALSE) 
				{
		   			$confirm = false;
		   			exit;
				}
				$confirm = true;
				fclose($handle);
	  		}
			else
			{
		 		$confirm = false;
		 		exit;
	  		}
		}
		return $confirm;
	}
	function getArrayFirstIndex($arr)
	{
		foreach ($arr as $key => $value)
		return $value;
	}
	
	 
	function json_encode($a=false)
	{
		if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
		
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		if (is_scalar($a))
		{
		  if (is_float($a))
		  {
			// Always use "." for floats.
			return floatval(str_replace(",", ".", strval($a)));
		  }
		
		  if (is_string($a))
		  {
			static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
			return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
		  }
		  else
			return $a;
		}
		$isList = true;
		for ($i = 0, reset($a); $i < count($a); $i++, next($a))
		{
		  if (key($a) !== $i)
		  {
			$isList = false;
			break;
		  }
		}
		$result = array();
		if ($isList)
		{
		  foreach ($a as $v) $result[] = $this->json_encode($v);
		  return '[' . join(',', $result) . ']';
		}
		else
		{
		  foreach ($a as $k => $v) $result[] = $this->json_encode($k).':'.$this->json_encode($v);
		  return '{' . join(',', $result) . '}';
		}
	}
	
	function checkExtContent($type,$code,$prefix="")
	{
		$dest	=	Configure::read('PATH_CONTENT')."images/".$type."/".$code."/";
		$prefix	=	empty($prefix) ? $code : $code.$prefix;
		
		if(!is_dir($dest))
		{
			return false;
		}
		else
		{
			if ($dh = opendir($dest))
			{
				while (($sf = readdir($dh)) !== false)
				{
					if ($sf == '.' || $sf == '..')
					{
						continue;
					}
					$sep	=	explode(".",$sf);
					if($sep[0]==$prefix)
					{
						return $sep[1] ;
					}
				}
				closedir($dh);
			}
			else
			{
				return false;
			}	
		}
	}
	
	function wordwrapText($text)
	{
		$split	=	explode(" ",$text);
		foreach($split as $split)
		{
			if(strlen($split) > 20 && strpos($split, 'http://') === false && strpos($word, 'www.') === false)
			{
				$arr = implode(" ",str_split($split, 20));
			}
			else
			{
				$arr = $split;
			}
			$out[]	=	$arr;
		}
		return implode(" ",$out);
	}
	
	function crop( $s, $srt, $len = NULL, $decode=true, $strict=false, $suffix = NULL )
	{
		if ( is_null($len) ){ $len = strlen( $s ); }
		
		$f = 'static $strlen=0; 
				if ( $strlen >= ' . $len . ' ) { return "><"; } 
				$html_str = html_entity_decode( $a[1] );
				$subsrt   = max(0, ('.$srt.'-$strlen));
				$sublen = ' . ( empty($strict)? '(' . $len . '-$strlen)' : 'max(@strpos( $html_str, "' . ($strict===2?'.':' ') . '", (' . $len . ' - $strlen + $subsrt - 1 )), ' . $len . ' - $strlen)' ) . ';
				$new_str = substr( $html_str, $subsrt,$sublen); 
				$new_str = wordwrap($new_str, 200, "<br>", 1);
				$strlen += $new_str_len = strlen( $new_str );
				$suffix = ' . (!empty( $suffix ) ? '($new_str_len===$sublen?"'.$suffix.'":"")' : '""' ) . ';
				
				return ">" . htmlentities($new_str, ENT_QUOTES, "UTF-8") . "$suffix<";';
		
		if($decode==false)
		{
			$gen = preg_replace( array( "#<[^/][^>]+>(?R)*</[^>]+>#", "#(<(b|h)r\s?/?>){2,}$#is"), "", trim( rtrim( ltrim( preg_replace_callback( "#>([^<]+)<#", create_function(
				'$a',
			  $f
			), ">$s<"  ), ">"), "<" ) ) );
		}
		else
		{
			$gen = html_entity_decode(preg_replace( array( "#<[^/][^>]+>(?R)*</[^>]+>#", "#(<(b|h)r\s?/?>){2,}$#is"), "", trim( rtrim( ltrim( preg_replace_callback( "#>([^<]+)<#", create_function(
				'$a',
			  $f
			), ">$s<"  ), ">"), "<" ) ) ));
			
		}
			
		$split	=	explode(" ",$gen);
		
		foreach($split as $split)
		{
			if(strlen($split) > 20 && strpos($split, 'http://') === false && strpos($split, 'www.') === false)
			{
				$arr = implode(" ",str_split($split, 20));
				
			}
			else
			{
				$arr = $split;
			}
			$out[]	=	$arr;
		}
		
		return implode(" ",$out);
		//return $gen;
	}
	
	function seoUrl($string)
	{
		//Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
		$string = strtolower($string);
		//Strip any unwanted characters
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
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
}
?>