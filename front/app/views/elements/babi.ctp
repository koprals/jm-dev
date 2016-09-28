<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="1Qe7hpPkk8Mr1RSExrrAnTRwjOwBI8jzi3v2b3Eay4Y" />
<link rel="shortcut icon" href="<?php echo $this->webroot?>img/favicon.ico">
<meta http-equiv="X-XRDS-Location" content="<?php echo $settings['site_url']?>xrds.xml" />
<meta name="google-site-verification" content="oJ-xxOvJ__DGdYIScBaG3-ElEZxyB3WPW53PQgy_yio" />
<meta name="title" content="<?php echo $title_for_layout?>" />
<meta name="description" content="<?php echo $site_description?>" />
<meta name="keywords" content="<?php echo $site_keywords?>" />
<?php if($this->params['controller']=="Iklan" && $this->params['action']=="Detail"):?>
<meta property="og:title" content="<?php echo $title_for_layout?>" />
<meta property="og:description" content="<?php echo $site_description?>" />
<meta property="og:image" content="<?php echo $settings['showimages_url']."/ProductImage_127_80_".$product_img_id.".jpg?code=".$product_img_id."&prefix=_127_80&content=ProductImage&w=127&h=80"?>" />
<?php endif;?>
<title><?php echo $title_for_layout; ?></title>
</head>

<body>

<?php $current_menu			=	empty($current_menu) ? NULL : $current_menu;?>
<?php $current_category_id	=	empty($current_category_id) ? "all_categories" : $current_category_id;?>
<?php $current_city			=	empty($current_city) ?  "all_cities" : $current_city;?>
<?php echo $this->element('header_menu',array("current_menu"=>$current_menu))?>

</body>
</html>