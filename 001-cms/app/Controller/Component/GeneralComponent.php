<?php
class GeneralComponent extends Component 
{
	//AMAZON CREDENTIAL
	var $bucketName		=	 "";
	var $awsAccessKey 	= 	"";
	var $awsSecretKey 	= 	"";
	var $awsHost		= 	"";
	var $GCM_API_ACCESS	=	"AIzaSyDaYVB3ajHtGyKvDq--PcKPzIKtdT6pxcc";
	
	var $settings;
	
	public function GeneralComponent()
	{
		$Setting		=	ClassRegistry::Init("Setting");
		$setting		=	$Setting->find("first");	
		$this->settings	=	$setting["Setting"];
	}
	
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
	
	function UploadToCloud($file_source,$model_id,$model_name,$type,$mime_type, $file_ext = null)
	{
		App::import('Vendor','S3' ,array('file'=>'S3.php'));
		
		$s3			=	new S3($this->awsAccessKey, $this->awsSecretKey, true, "s3-ap-southeast-1.amazonaws.com");
		$input		=	$s3->inputFile($file_source, false);
		$ext		=	pathinfo($file_source,PATHINFO_EXTENSION);
		
		if($file_ext != null) {
			$obj		=	$s3->putObject($input,$this->bucketName, "contents/".$model_name."/".$model_id."/".$model_id."_".$type.".".$file_ext,S3::ACL_PUBLIC_READ,array(),array("Content-Type"=>$mime_type));
		} else {
			$obj		=	$s3->putObject($input,$this->bucketName, "contents/".$model_name."/".$model_id."/".$model_id."_".$type.".".$ext,S3::ACL_PUBLIC_READ,array(),array("Content-Type"=>$mime_type));
		}
		return $obj;
	}
	
	function getFromCloud($uri)
	{
		App::import('Vendor','S3' ,array('file'=>'S3.php'));
		$s3			=	new S3($this->awsAccessKey, $this->awsSecretKey, true, "s3-ap-southeast-1.amazonaws.com");
		if(($info = $s3->getObject($this->bucketName, $uri)) !== false) {
			$path_tmp = APP."tmp/cache/picture/";
			
			/*if(!is_dir($path_tmp)) {
				mkdir(APP."tmp/cache/picture", 0777);
			}
			$handle = fopen($path_tmp."kossa.jpg", "w+");
			if (fwrite($handle, $info->body) === FALSE) {
		        echo "Cannot write to file (kossa.jpg)";
		        exit;
		    } else {
			    echo "success";
		    }*/
		}
	}
	
	function ResizeImageContent($source,$host,$model_id,$model_name,$type,$mime_type,$width,$height,$resizeType = 'cropResize')
	{
		$ext									=	pathinfo($source,PATHINFO_EXTENSION);
		$Content								=	ClassRegistry::Init("Content");
		
		$data									=	$Content->find("first",array(
														"conditions"	=>	array(
															"Content.model_id"		=>	$model_id,
															"Content.model"			=>	$model_name,
															"LOWER(Content.type)"	=>	strtolower($type),
														)
													));
												
		if(!empty($data))
		$Contents["Content"]["id"]				=	$data["Content"]["id"];					
		$Contents["Content"]["model"]			=	$model_name;
		$Contents["Content"]["model_id"]		=	$model_id;
		$Contents["Content"]["host"]			=	$host;
		$Contents["Content"]["mime_type"]		=	$mime_type;
		$Contents["Content"]["cloud"]			=	"0";
		
		$path_content	=	$this->settings['path_content'];
			if(!is_dir($path_content))mkdir($path_content,0777);
		
		$path_model		=	$path_content. $model_name . "/";
			if(!is_dir($path_model)) mkdir($path_model,0777);
			
		$path_model_id	=	$path_model . $model_id . "/";
			if(!is_dir($path_model_id))
				mkdir($path_model_id,0777);
			
		//RESIZE
		App::import('Vendor','upload' ,array('file'=>'class.upload.php'));
		$path_content							=	$path_model_id.$model_id.'_'.$type.".".$ext;
		@unlink($path_content);
		$img 									=	new upload($source);
		$img->file_new_name_body   				=	$model_id.'_'.$type;
		
		if($resizeType == 'cropResize') {
			
			$img->image_resize          		=	true;
			$img->image_ratio_crop      		=	true;
			$img->image_y               		=	$height;
			$img->image_x               		=	$width;
			
		} else if($resizeType == 'resizeMaxWidth') {
			
			$img->image_resize          		=	true;
			$img->image_ratio_y        			= 	true;
			$img->image_x               		=	$width;
		}
		else if($resizeType == 'cropRatio')
		{
			$img->image_resize          		=	true;
			$img->image_ratio		      		=	true;
			$img->image_y               		=	$height;
			$img->image_x               		=	$width;
		}
		else if($resizeType == 'cropFill')
		{
			$img->image_resize          		=	true;
			$img->image_ratio_fill	      		=	true;
			$img->image_y               		=	$height;
			$img->image_x               		=	$width;
		}
		
		$img->process($path_model_id);
		$Contents["Content"]["type"]			=	$type;
		$Contents["Content"]["url"]				=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		$Contents["Content"]["path"]			=	$path_content;
		
		if ($img->processed)
		{
			$Content->create();
			$Content->save($Contents);
			return true;
		}
		return false;
	}
	
	
	function UploadContent($model_id,$model_name,$type,$source)
	{
		$tmp_name							=	$source["name"];
		$tmp								=	$source["tmp_name"];
		$mime_type							=	$source["type"];
		$ext								=	pathinfo($tmp_name,PATHINFO_EXTENSION);
		$Content							=	ClassRegistry::Init("Content");
		$data								=	$Content->find("first",array(
													"conditions"	=>	array(
														"Content.model_id"		=>	$model_id,
														"Content.model"			=>	$model_name,
														"LOWER(Content.type)"	=>	strtolower($type),
													)
												));
		
		$path_tmp							=	ROOT.DS.'app'.DS.'tmp'.DS.'upload'.DS;
			if(!is_dir($path_tmp)) mkdir($path_tmp,0777);
			
		$ext								=	pathinfo($tmp_name,PATHINFO_EXTENSION);
		$tmp_file_name						=	md5(time());
		$tmp_images1_img					=	$path_tmp.$tmp_file_name.".".$ext;
		//exec('C:\\path\\to\\ffmpeg.exe -y -i file.mp3 -acodec libvorbis file.ogg');
												
		if(!empty($data))
			$Contents["Content"]["id"]			=	$data["Content"]["id"];	
							
		$Contents["Content"]["model"]		=	$model_name;
		$Contents["Content"]["model_id"]	=	$model_id;
		$Contents["Content"]["host"]		=	$this->settings["cms_url"];
		$Contents["Content"]["mime_type"]	=	$mime_type;
		$Contents["Content"]["cloud"]		=	"0";
		$Contents["Content"]["type"]		=	$type;
		$Contents["Content"]["url"]			=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		$Contents["Content"]["path"]		=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		
		$path_content	=	$this->settings['path_content'];
			if(!is_dir($path_content))mkdir($path_content,0777);
		
		$path_model		=	$path_content. $model_name . "/";
			if(!is_dir($path_model)) mkdir($path_model,0777);
			
		$path_model_id	=	$path_model . $model_id . "/";
			if(!is_dir($path_model_id)) mkdir($path_model_id,0777);
			
		$path_content						=	$path_model_id.$model_id.'_'.$type.".".$ext;
		$upload 							=	move_uploaded_file($tmp,$path_content);
		
		if($upload)
		{
			$Content->create();
			$Content->save($Contents);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function DeleteContent($model_id,$model_name)
	{
		$Content							=	ClassRegistry::Init("Content");
		$data								=	$Content->find("first",array(
													"conditions"	=>	array(
														"LOWER(Content.model_id)"		=>	$model_id,
														"LOWER(Content.model)"			=>	$model_name
													)
												));
		if(!empty($data))
		{
			$path_model_id					=	$this->settings["path_content"].ucfirst($model_name)."/".$model_id."/";
			$this->RmDir($path_model_id);
			$Content->deleteAll(array(
				"LOWER(Content.model_id)"		=>	$model_id,
				"LOWER(Content.model)"			=>	$model_name
			));
		}
	}
	
	function UploadContentBak($model_id,$model_name,$type,$source)
	{
		$tmp_name							=	$source["name"];
		$tmp								=	$source["tmp_name"];
		$mime_type							=	$source["type"];
		$ext								=	pathinfo($tmp_name,PATHINFO_EXTENSION);
		$Content							=	ClassRegistry::Init("Content");
		$data								=	$Content->find("first",array(
													"conditions"	=>	array(
														"Content.model_id"		=>	$model_id,
														"Content.model"			=>	$model_name,
														"LOWER(Content.type)"	=>	strtolower($type),
													)
												));
		
		$path_tmp							=	ROOT.DS.'app'.DS.'tmp'.DS.'upload'.DS;
			if(!is_dir($path_tmp)) mkdir($path_tmp,0777);
			
		$ext								=	pathinfo($tmp_name,PATHINFO_EXTENSION);
		$tmp_file_name						=	md5(time());
		$tmp_images1_img					=	$path_tmp.$tmp_file_name.".".$ext;
		$upload 							=	move_uploaded_file($tmp,$tmp_images1_img);
		
		//exec('C:\\path\\to\\ffmpeg.exe -y -i file.mp3 -acodec libvorbis file.ogg');
												
		if(!empty($data))
			$Contents["Content"]["id"]			=	$data["Content"]["id"];	
							
		$Contents["Content"]["model"]		=	$model_name;
		$Contents["Content"]["model_id"]	=	$model_id;
		$Contents["Content"]["host"]		=	$this->awsHost;
		$Contents["Content"]["mime_type"]	=	$mime_type;
		$Contents["Content"]["cloud"]		=	"1";
		$Contents["Content"]["type"]		=	$type;
		$Contents["Content"]["url"]			=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		$Contents["Content"]["path"]		=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		
		if($this->UploadToCloud($tmp_images1_img,$model_id,$model_name,$type,$mime_type, $ext)) 
		{
			
			$Content->create();
			$Content->save($Contents);
			
			//CENVERT TO OGG
			$path_ogg						=	$path_tmp.$tmp_file_name.".ogg";
			//exec('D:\\abyfolder\\aby\\ffmpeg\\ffmpeg\\bin\\ffmpeg.exe -y -i '.$tmp_images1_img.' -acodec libvorbis '.$path_ogg);
			//$this->UploadContentOgg($model_id,$model_name,$type."-ogg",$path_ogg);
			@unlink($tmp_images1_img);
			return true;
		} else {
			return false;
		}
	}
	function UploadContentOgg($model_id,$model_name,$type,$source)
	{
		$ext									=	"ogg";
		$mime_type								=	"audio/ogg";	
		if(!empty($data))
			$Contents["Content"]["id"]			=	$data["Content"]["id"];	
							
		$Contents["Content"]["model"]		=	$model_name;
		$Contents["Content"]["model_id"]	=	$model_id;
		$Contents["Content"]["host"]		=	$this->awsHost;
		$Contents["Content"]["mime_type"]	=	$mime_type;
		$Contents["Content"]["cloud"]		=	"1";
		$Contents["Content"]["type"]		=	$type;
		$Contents["Content"]["url"]			=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		$Contents["Content"]["path"]		=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		
		if($this->UploadToCloud($source,$model_id,$model_name,$type,$mime_type, $ext)) 
		{
			@unlink($source);
			$Content->create();
			$Content->save($Contents);
			return true;
		} else {
			return false;
		}
	}
	
	
	function ResizeImageContentCloud($source, $destination_path, $model_id, $model_name, $type = "original", $mime_type, $width, $height, $resizeType = 'cropResize') 
	{
		
		$ext								=	pathinfo($source,PATHINFO_EXTENSION);
		$Content							=	ClassRegistry::Init("Content");
		
		$data								=	$Content->find("first",array(
													"conditions"	=>	array(
														"Content.model_id"		=>	$model_id,
														"Content.model"			=>	$model_name,
														"LOWER(Content.type)"	=>	strtolower($type),
													)
												));
												
		if(!empty($data))
		$Contents["Content"]["id"]			=	$data["Content"]["id"];					
		$Contents["Content"]["model"]		=	$model_name;
		$Contents["Content"]["model_id"]	=	$model_id;
		$Contents["Content"]["host"]		=	$this->awsHost;
		$Contents["Content"]["mime_type"]	=	$mime_type;
		$Contents["Content"]["cloud"]		=	"1";
		
		App::import('Vendor','upload' ,array('file'=>'class.upload.php'));
		$img 								=	new upload($source);
		$img->file_new_name_body   			=	$model_id."_".$type;
		
		if($resizeType == 'cropResize') {
			$img->image_resize          		=	true;
			$img->image_ratio_crop      		=	true;
			$img->image_y               		=	$height;
			$img->image_x               		=	$width;	
		} else if($resizeType == 'resizeMaxWidth') {
			$img->image_resize          		=	true;
			$img->image_ratio_y        			= 	true;
			$img->image_x               		=	$width;
		}
		
		$img->process($destination_path);
		
		$Contents["Content"]["type"]		=	$type;
		$Contents["Content"]["url"]			=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		$Contents["Content"]["path"]		=	"contents/{$model_name}/{$model_id}/{$model_id}_{$type}.{$ext}";
		
		if ($img->processed) {
			
			$file_source = $destination_path.$model_id."_".$type.".".$ext;
			
			if($this->UploadToCloud($file_source,$model_id,$model_name,$type,$mime_type, $ext)) 
			{
				unlink($file_source);
				$Content->create();
				$Content->save($Contents);
				return true;
			} else {
				//return false;
			}
		} else {
			return false;
		}/**/
	}
	
	function PostCurl($url,$post,$header)
	{
		//TRY TO SEND PARAMETER
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$out = curl_exec($ch);
		curl_close($ch);
		return $out;
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
	
	function GetImageFromUrl($destination, $source)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch,CURLOPT_URL,$source);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result	=	curl_exec($ch);
		curl_close($ch);
		
		$handle = fopen($destination, 'wb');
		if($handle)
		{
			if (fwrite($handle, $result) === FALSE) 
			{
				return false;
			}
			fclose($handle);
			return true;
		}
		else
		{
			return false;
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