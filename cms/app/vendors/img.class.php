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
	
	
	function img($id, $prefix, $content, $w=100, $h=100,$percent=0,$type='img',$scale=0)
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
	}
	
	
	function create($CEK_PATH){
	
	//CHECK IF FILE EXISTS
		 if(is_file($CEK_PATH.'.jpg'))
		 {
			$this->ext 	  = "jpg";
		 } 
		 elseif(is_file($CEK_PATH.'.jpeg'))
		 {
			$this->ext 	  = "jpeg";
		 }
		 elseif(is_file($CEK_PATH.'.gif'))
		 {
			$this->ext 	  = "gif";
		 } 
		 elseif(is_file($CEK_PATH.'.png'))
		 {
			$this->ext 	  = "png";
		 } 
		 elseif(is_file($CEK_PATH.'.bmp'))
		 {
			$this->ext 	  = "bmp";
		 }
		 elseif(is_file($CEK_PATH.'.tiff'))
		 {
			$this->ext 	  = "tiff";
		 }
		 else
		 {
			$this->ext	 		= "";
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
		 //$this->showImg();
		 
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
			
		/*if type = rec*/
		if($this->type=='rec')
		{
			$endsize	=	3;
			$startsize	=	$endsize*3-1;
			$arcsize	=	$startsize*2+1;
			$background =   imagecreatetruecolor($imgInfo[0], $imgInfo[1]);
			
			imagecopymerge($background, $im, 0, 0, 0, 0,$imgInfo[0], $imgInfo[1], 100);
			$startx		=	$nWidth-1;
			$starty		=	($nHeight > $h) ? $h-1 : $nHeight-1;
			$im_temp 	= imagecreatetruecolor($nWidth, $nHeight);
			imagecopyresampled($im_temp, $background, 0, 0, 0, 0,$nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
		
			if($nHeight > $h)
			{
				$cropped	= imagecreatetruecolor($nWidth, $h);
				imagecopy($cropped, $im_temp, 0, 0, 0, 0, $nWidth, $nHeight);
				$im_temp	= $cropped;
			}
			
			
			$bg = imagecolorallocate($im_temp, hexdec(substr($this->backcolor,0,2)),hexdec(substr($this->backcolor,2,2)),hexdec(substr($this->backcolor,4,2)));
			
			// Top-left corner
			imagearc($im_temp, $startsize, $startsize, $arcsize, $arcsize, 180,270,$bg);
			imagefilltoborder($im_temp,0,0,$bg,$bg);
			
			// Top-right corner
			imagearc($im_temp, $startx-$startsize, $startsize,$arcsize, $arcsize, 270,360,$bg);
			imagefilltoborder($im_temp,$startx,0,$bg,$bg);
			
			// Bottom-left corner
			imagearc($im_temp, $startsize, $starty-$startsize,$arcsize, $arcsize, 90,180,$bg);
			imagefilltoborder($im_temp,0,$starty,$bg,$bg);
		
			// Bottom-right corner
			imagearc($im_temp, $startx-$startsize, $starty-$startsize,$arcsize, $arcsize, 0,90,$bg);
			imagefilltoborder($im_temp,$startx,$starty,$bg,$bg);
			
			$newImg =  $im_temp;
			/*
			$newImg =  imagecreatetruecolor($nWidth, $nHeight);
			
			imagecopyresampled($newImg, $im_temp, 0, 0, 0, 0, $nWidth,$nHeight,$startx, $starty);*/
			
		}
		else
		{
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			
			if($nHeight>$h && $croping==false && $h>0)
			{
				$newImg	= imagecreatetruecolor($nWidth, $h);
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
			
			imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
			//-- Crop
			if($croping==true)
			{
            	imagecopy($cropped, $newImg, 0, 0, $cropx, $cropy, $nWidth, $nHeight);
				$newImg	=	$cropped;
			}
		}
		
		$this->_sendHeader($imgInfo[2]);
		switch ($imgInfo[2]) {
		
			case 1: imagegif($newImg,$this->dest);
			
			case 2: imagejpeg($newImg,$this->dest,100);
	
			case 3: imagepng($newImg,$this->dest,9);
			
			default:  return false;
			
		}
	}
	
	function showImg()
	{
		$this->_sendHeader();
		echo(file_get_contents($this->dest));
		return $this->dest;
	}
	
	function _sendHeader(){
		if ($this->ext=="gif") {
			header('Content-Type: image/gif');
		}elseif ($this->ext=="jpg" || $this->ext=="jpeg") {
			header('Content-Type: image/jpeg');
		}elseif ($this->ext=="png") {
			header('Content-Type: image/png');
		}
	}
	
		
}
?>