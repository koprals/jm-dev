<?php
class ShowImagesController extends AppController
{
	var $name	=	"ShowImages";
	var $uses	=	null;
	
	function Index()
	{
		//SET GENERAL SETTINGS
		$this->loadModel('Setting');
		$settings		=	$this->Setting->find('first');
		define("PATH_CONTENT",$settings['Setting']['path_content']);
		define("PATH_NOPICT",(isset($_GET['nopict']) && $_GET['nopict']!="" ) ? PATH_CONTENT."nopict/".$_GET['nopict'] : PATH_CONTENT."nopict/nopict");
		define("PATH_FONT",$settings['Setting']['path_webroot']);
		
		
		$_GET['watermark']	=	(empty($_GET['watermark']) or !in_array($_GET['watermark'],array("0","1"))) ? "0" : $_GET['watermark'];
		App::import('Vendor','img' ,array('file'=>'img.class.php'));
		$img 		= 	new img($_GET['code'],$_GET['prefix'],$_GET['content'],$_GET['w'],$_GET['h'],$_GET['watermark']);
		$filename	=	!empty($_GET['filename']) ? $_GET['filename'] : $img->code;
		$CEK_PATH 	= 	PATH_CONTENT.$img->content."/".$img->code."/".$filename;
		
		$img->create($CEK_PATH);
		$this->autoRender	=	false;
	}
	
	function ImageLogs($imglog_id,$w=0,$h=0)
	{
		error_reporting(E_ALL ^ E_NOTICE);
		$this->loadModel('ProductImageLog');
		$detail	=	$this->ProductImageLog->findById($imglog_id);
		$this->autoRender	=	false;
		
		if($detail==false)
		{
			echo "File not found";
			return;
		}
		$blobcontents	=	$detail['ProductImageLog']['file'];
		$im 			=	imagecreatefromstring($blobcontents);
		$width 			=	imagesx($im);
		$height 		=	imagesy($im);
		$scale			=	0;
		$w				=	(int)$w;
		$h				=	(int)$h;
		
		if($w == 0 && $h > 0)
		{
		  $nHeight		= $h;
		  $diff 		= $height / $nHeight;
		  $nWidth 		= $width / $diff;
		}
		elseif($h == 0 && $w > 0)
		{
		  $nWidth 		= ($width<$w) ? $width : $w;
		  $diff 		= $width / $nWidth;
		  $nHeight		= $height / $diff;
		}
		elseif($h > 0 && $w > 0)
		{
		  $nWidth 		= ($width > $w ) ? $w : $width;
		  $diff 		= $width / $nWidth;
		  $nHeight		= $height / $diff;
		}
		elseif($h == 0 && $w == 0 && $scale > 0)
		{
		  $ratiox 		= $width / $height * $scale;
		  $ratioy 		= $height / $width * $scale;
			  
		  //-- Calculate resampling
		  $nHeight = ($width <= $height) ? $ratioy : $scale;
		  $nWidth = ($width <= $height) ? $scale : $ratiox;
		  
		  $croping		= true;
		}
		elseif($percent > 0)
		{
		  $nWidth  = round($width * $percent);
		  $nHeight = round($height * $percent);
		}
		
		
		//-- Calculate cropping (division by zero)
        $cropx  = ($nWidth - $scale != 0) ? ($nWidth - $scale) / 2 : 0;
        $cropy  = ($nHeight - $scale != 0) ? ($nHeight - $scale) / 2 : 0;
		if($croping==true)
		{
			$cropped = imagecreatetruecolor($scale, $scale);
		}
		
		$newImg = imagecreatetruecolor($nWidth, $nHeight);
		if($nHeight>$h && $croping==false && $h>0)
		{
			$newImg	= imagecreatetruecolor($nWidth, $h);
		}
		
		//IF PNG or GIF
		if($detail['ProductImageLog']['type']=="png" or $detail['ProductImageLog']['type']=="gif"){
			$trnprt_indx = imagecolortransparent($im);
			
			if ($trnprt_indx >= 0)
			{
				$trnprt_color	= imagecolorsforindex($im, $trnprt_indx);
				$trnprt_indx    = imagecolorallocate($newImg, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				imagefill($newImg, 0, 0, $trnprt_indx);
				imagecolortransparent($newImg, $trnprt_indx);
			} 
			elseif ($detail['ProductImageLog']['type']=="png")
			{
				imagealphablending($newImg, false);
				$color = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
				imagefill($newImg, 0, 0, $color);
				imagesavealpha($newImg, true);
				
			}
		}
		
		imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
		if($croping==true)
		{
			imagecopy($cropped, $newImg, 0, 0, $cropx, $cropy, $nWidth, $nHeight);
			$newImg	=	$cropped;
		}
		
		imagedestroy($im);
		header("Content-type: image/{$detail['ProductImageLog']['type']}");
		switch ($detail['ProductImageLog']['type'])
		{
			case 'gif': imagegif($newImg,null);
			case 'jpg': imagejpeg($newImg,null,100);
			case 'jpeg': imagejpeg($newImg,null,100);
			case 'png': imagepng($newImg,null,9);
			default:  return false;
		}
	}
}
?>