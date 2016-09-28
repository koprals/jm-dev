<?php
ini_set('display_errors',1);
define("PATH_CONTENT","D:/xampp/htdocs/toko-frontend/contents/");
define("PATH_NOPICT","D:/xampp/htdocs/toko-frontend/contents/images/nopict/200x200.png");
define("PATH_FONT","D:/xampp/htdocs/toko-frontend/app/webroot/");

require_once "class/img.class.php";
$watermark	=	(isset($_GET['watermark']) && in_array($_GET['watermark'],array(0,1)) ) ? $_GET['watermark'] : false;

$arrType	=	array('img','rec');
$percent	=	(isset($_GET['percent']) || $_GET['percent']!="") ? $_GET['percent'] : NULL;
$type		=	(isset($_GET['type']) && in_array($_GET['type'],$arrType)) ? $_GET['type'] : 'img';
$scale		=	(isset($_GET['scale']) || $_GET['scale']!="") ? $_GET['scale'] : 0;
$path		=	PATH_CONTENT.$_GET['path'];
$width		=	$_GET['w'];
$height		=	$_GET['h'];

$img 		= 	new img($width, $height,$percent,'img',0,$watermark);
$img->create($path);

$img 		= 	new img($_GET[code],$_GET[prefix],$_GET[content],$_GET[w],$_GET[h],$percent,$type,$scale);
$CEK_PATH 	= 	PATH_CONTENT."/".$img->content."/".$img->code."/".$img->code;
$img->create($CEK_PATH);
$img->showImg();

?>
