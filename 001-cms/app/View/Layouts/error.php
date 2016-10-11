<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title><?php echo $title_for_layout; ?></title>

<?php

//FAVICON
echo $this->Html->meta('icon',$this->webroot."img/favicon.png",array("type"=>"png"));

//************ CSS NEEDED ****************//
echo $this->Html->css("main");
//************ CSS NEEDED ****************//


?>
</head>

<body class="nobg loginPage">
<body class="nobg errorPage">

<!-- Top fixed navigation -->
<div class="topNav">
    <div class="wrapper">
        <div class="welcome">
			<a href="#" title="">
				<img src="<?php echo $this->webroot ?>img/userPic.png" alt="" />
			</a>
			<span>
				<?php echo $profile["Admin"]["fullname"]?>
			</span>
		</div>
        <div class="userNav">
			<ul>
				<li style="float:right;">
					<a href="<?php echo $settings["cms_url"]?>Account/Logout" title="">
						<img src="<?php echo $this->webroot ?>img/icons/topnav/logout.png" alt="" />
						<span>Logout</span>
					</a>
				</li>
			</ul>
		</div>
    </div>
</div>

<!-- Main content wrapper -->
<div class="errorWrapper">
    <span class="sadEmo"></span>
    <span class="errorTitle">Ahh, something went wrong here :(</span>
    <span class="errorNum">404</span>
    <span class="errorDesc">Oops! Sorry, an error has occured. Page not found!</span>
    <a href="<?php echo $settings["cms_url"]?>" class="button dredB"><span>Back to dashboard</span></a>
</div>
</body>
</body>
</html>
