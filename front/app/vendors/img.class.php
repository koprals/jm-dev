<?php
class img {
	
	var $code;
	var $temp;
	var $ext;
	var $source;
	var $prefix;
	var $content;
	var $nwidth;
	var $nheight;
	var $watermark;
	var $watermarkimg;
	function img($id, $prefix, $content, $w=100, $h=100,$watermark=0,$percent=0,$type='img',$scale=0)
	{
		$this->code 		= $id;
		$this->prefix 		= $prefix;
		$this->content 		= $content;
		$this->nwidth 		= (isset($w) && $w!="") ? $w : "0";
		$this->nheight 		= (isset($h) && $h!="") ? $h : "0";
		$this->percent		= $percent;
		$this->type			= $type;
		$this->backcolor	= 'ffffff';
		$this->scale		= $scale;
		$this->watermark	= $watermark;
		
	}
	
	function create($CEK_PATH)
	{
		$ext_array	=	array(
			".jpg"	=>	"jpg",
			".jpeg"	=>	"jpeg",
			".JPG"	=>	"JPG",
			".JPEG"	=>	"JPEG",
			".gif"	=>	"gif",
			".GIF"	=>	"GIF",
			".png"	=>	"png",
			".PNG"	=>	"PNG",
			".bmp"	=>	"bmp",
			".BMP"	=>	"BMP",
			".tiff"	=>	"tiff",
			".TIFF"	=>	"TIFF"
		);
		
		//CHECK IF FILE EXISTS
		$this->ext 	  = "";
		foreach($ext_array as $extention => $name)
		{
			if(is_file($CEK_PATH.$extention))
			{
			  $this->ext 	  = $name;
			  break;
			} 
		}
		
		if($this->ext!="") 
		{
		  $this->source 	= $CEK_PATH.".".$this->ext;
		  $this->dest		= $CEK_PATH.$this->prefix.".".$this->ext;
		}
		else
		{
		  $this->create(PATH_NOPICT);
		}
		if(!is_file($this->dest))
		{
		   $this->resize($this->source,$this->nwidth,$this->nheight,$this->dest,$this->percent); 
		}
		$this->showImg();
		return $this->ext;
	}
	
	function resize($img, $w, $h, $newfilename=null,$percent=0) {
	       
		if($percent > 1.5) $percent = 1;
		$scale			=	$this->scale;
		$newfilename = is_null($newfilename) ? $img : $newfilename;
		if(!file_exists($img))
		{
			return false;
		}
		
		//Check if GD extension is loaded
		if (!extension_loaded('gd') && !extension_loaded('gd2'))
		{
			trigger_error("GD is not loaded", E_USER_WARNING);
			return false;
		}

		//Get Image size info
		$imgInfo = getimagesize($img);
		
		switch ($imgInfo[2]) {
		
			case 1: $im = imagecreatefromgif($img); break; 
			
			case 2: $im = imagecreatefromjpeg($img);  break;
			
			case 3: $im = imagecreatefrompng($img); break;
			
			default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
		
		}
		
		//If image dimension is smaller, do not resize
		if($w == 0 && $h > 0)
		{
		  $nHeight		= $h;
		  $diff 		= $imgInfo[1] / $nHeight;
		  $nWidth 		= $imgInfo[0] / $diff;
		}
		elseif($h == 0 && $w > 0)
		{
		  $nWidth 		= ($imgInfo[0]<$w) ? $imgInfo[0] : $w;
		  $diff 		= $imgInfo[0] / $nWidth;
		  $nHeight		= $imgInfo[1] / $diff;
		}
		elseif($h > 0 && $w > 0)
		{
		  $nWidth 		= ($imgInfo[0] > $w ) ? $w : $imgInfo[0];
		  $diff 		= $imgInfo[0] / $nWidth;
		  $nHeight		= $imgInfo[1] / $diff;
		}
		elseif($h == 0 && $w == 0 && $scale > 0)
		{
		  $ratiox 		= $imgInfo[0] / $imgInfo[1] * $scale;
		  $ratioy 		= $imgInfo[1] / $imgInfo[0] * $scale;
			  
		  //-- Calculate resampling
		  $nHeight = ($imgInfo[0] <= $imgInfo[1]) ? $ratioy : $scale;
		  $nWidth = ($imgInfo[0] <= $imgInfo[1]) ? $scale : $ratiox;
		  
		  $croping		= true;
		}
		elseif($percent > 0)
		{
		  $nWidth  = round($imgInfo[0] * $percent);
		  $nHeight = round($imgInfo[1] * $percent);
		}

		//-- Calculate cropping (division by zero)
        $cropx  = ($nWidth - $scale != 0) ? ($nWidth - $scale) / 2 : 0;
        $cropy  = ($nHeight - $scale != 0) ? ($nHeight - $scale) / 2 : 0;
		if($croping==true)
		{
			$cropped = imagecreatetruecolor($scale, $scale);
		}
		
		
		$newImg		=	imagecreatetruecolor($w, $h);
		imagefill($newImg, 0, 0, imagecolorallocate($newImg,255,255,255));
		
		
		
		if($nHeight>$h && $croping==false && $h>0)
		{
			//$newImg	= imagecreatetruecolor($nWidth, $h);
		}
		
		if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
			$trnprt_indx = imagecolortransparent($im);
			
			if ($trnprt_indx >= 0) {
			
				// Get the original image's transparent color's RGB values
				$trnprt_color    = imagecolorsforindex($im, $trnprt_indx);
				
				// Allocate the same color in the new image resource
				$trnprt_indx    = imagecolorallocate($newImg, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				
				// Completely fill the background of the new image with allocated color.
				imagefill($newImg, 0, 0, $trnprt_indx);
				
				// Set the background color for new image to transparent
				imagecolortransparent($newImg, $trnprt_indx);
			} 
			elseif ($imgInfo[2] == IMAGETYPE_PNG)
			{
				
				// Turn off transparency blending (temporarily)
				imagealphablending($newImg, false);
				
				// Create a new transparent color for image
				$color = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
		   
				// Completely fill the background of the new image with allocated color.
				imagefill($newImg, 0, 0, $color);
		   
				// Restore transparency blending
				imagesavealpha($newImg, true);
			}
		
		}
		$twidth		=	($w>$nWidth) ? (($w-$nWidth)/2) : 0;
		$tHeight	=	($h>$nHeight) ? (($h-$nHeight)/2) : 0;
		$copy		=	imagecopyresampled($newImg, $im, $twidth, $tHeight, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
		
		
		if($this->watermark=="1")
		{
			//watermark
			$stamp			=	imagecreatefromjpeg($this->watermarkimg);
			$marge_right	=	0;
			$marge_bottom 	=	0;
			$sx 			=	imagesx($stamp);
			$sy 			=	imagesy($stamp);
			$w_r_x			=	($nWidth/282)*92.7075;
			$w_r_y			=	($nHeight/211.5)*13.0425;
			$w_x			=	$w-$twidth-$w_r_x;
			$w_y			=	$h-$tHeight-$w_r_y;
			imagecopyresampled($newImg, $stamp, $w_x,$w_y, 0, 0,$w_r_x,$w_r_y, imagesx($stamp), imagesy($stamp));
		}
		//var_dump($nWidth,$w_r_x,$nHeight,$w_r_y);exit;
		
		
		//-- Crop
		if($croping==true)
		{
			imagecopy($cropped, $newImg, 0, 0, $cropx, $cropy, $nWidth, $nHeight);
			$newImg	=	$cropped;
		}
		
		/*if($this->watermark=="1")
		{
			$black	= imagecolorallocate($newImg, 199,0,0);
			$font	= PATH_FONT.'BRITANIC.TTF';
			$w_text	= ($nWidth > $imgInfo[0]) ? $imgInfo[0] : $nWidth;
			$font_size = round((12 * $w_text)/128);
			$x	=	round($w_text/4);
			$y	=	round((70 * $w_text)/128);
			imagettftext($newImg, $font_size, 0, $x-5, $nHeight-7, $black, $font, "Terjual");
		}*/
		
		$this->_sendHeader($imgInfo[2]);

		switch ($imgInfo[2]) {
		
			case 1: 
				imagegif($newImg,$this->dest);
				break;
			
			case 2: 
				imagejpeg($newImg,$this->dest,100);
				break;
	
			case 3: 
				imagepng($newImg,$this->dest,9);
				break;
			
			/*case 1: imagegif($newImg,null);
			
			case 2: imagejpeg($newImg,null,100);
	
			case 3: imagepng($newImg,null,9);*/
			
			default:  return false;
			
		}
	}
	
	function showImg()
	{
		$this->_sendHeader();
		ob_clean();
	    flush();
	    readfile($this->dest);
		return $this->dest;
	}
	
	function _sendHeader(){
		header("Date: " . date("D, j M Y G:i:s ", filemtime($this->dest)) . 'GMT');
		if ($this->ext=="gif") {
			header('Content-Type: image/gif');
		}elseif ($this->ext=="jpg" || $this->ext=="jpeg") {
			header('Content-Type: image/jpeg');
		}elseif ($this->ext=="png") {
			header('Content-Type: image/png');
		}
		header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600*24*365*20) . " GMT");
		header("Cache-Control: max-age=86400, must-revalidate"); // HTTP/1.1
		header("Pragma: cache");        // HTTP/1.0
	}
	
		
}
?>