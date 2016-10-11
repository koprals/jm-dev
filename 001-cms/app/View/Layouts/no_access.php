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

//BLOCK CSS
echo $this->fetch('css');

//************ JS NEEDED ******************/
echo $this->Html->script(array(
	"jquery-1.7.2.min",
	"jquery-ui-1.8.21.custom.min",
	"/js/plugins/spinner/jquery.mousewheel.js",

	"/js/globalize/globalize.js",
	"/js/globalize/globalize.culture.de-DE.js",
	"/js/globalize/globalize.culture.ja-JP.js",

	"/js/plugins/charts/excanvas.min.js",
	"/js/plugins/charts/jquery.flot.js",
	"/js/plugins/charts/jquery.flot.orderBars.js",
	"/js/plugins/charts/jquery.flot.pie.js",
	"/js/plugins/charts/jquery.flot.resize.js",
	"/js/plugins/charts/jquery.sparkline.min.js",

	"/js/plugins/forms/uniform.js",
	"/js/plugins/forms/jquery.cleditor.js",
	"/js/plugins/forms/jquery.validationEngine-en.js",
	"/js/plugins/forms/jquery.validationEngine.js",
	"/js/plugins/forms/jquery.tagsinput.min.js",
	"/js/plugins/forms/jquery.autosize.js",
	"/js/plugins/forms/jquery.maskedinput.min.js",
	"/js/plugins/forms/jquery.dualListBox.js",
	"/js/plugins/forms/jquery.inputlimiter.min.js",
	"/js/plugins/forms/chosen.jquery.min.js",

	"/js/plugins/wizard/jquery.form.js",
	"/js/plugins/wizard/jquery.validate.min.js",
	"/js/plugins/wizard/jquery.form.wizard.js",
	"/js/plugins/uploader/plupload.js",
	"/js/plugins/uploader/plupload.html5.js",
	"/js/plugins/uploader/plupload.html4.js",
	"/js/plugins/uploader/jquery.plupload.queue.js",
	"/js/plugins/tables/datatable.js",
	"/js/plugins/tables/tablesort.min.js",
	"/js/plugins/tables/resizable.min.js",
	"/js/plugins/ui/jquery.tipsy.js",
	"/js/plugins/ui/jquery.collapsible.min.js",
	"/js/plugins/ui/jquery.prettyPhoto.js",
	"/js/plugins/ui/jquery.progress.js",
	"/js/plugins/ui/jquery.timeentry.min.js",
	"/js/plugins/ui/jquery.colorpicker.js",
	"/js/plugins/ui/jquery.jgrowl.js",
	"/js/plugins/ui/jquery.breadcrumbs.js",
	"/js/plugins/ui/jquery.sourcerer.js",
	"/js/plugins/jquery.fullcalendar.js",
	"/js/plugins/jquery.elfinder.js",
	"/js/jquery-ui.multidatespicker.js",
	"/js/custom.js",
	"/js/jquery.timeentry.js",
	"/js/jquery.jCounter-0.1.4.js"
));
//************ JS NEEDED ******************/

//BLOCK JAVASCRIPT
echo $this->fetch('script');


?>
</head>

<body>
	<!-- Left side content -->
	<div id="leftSide">
		<div class="logo" style="text-align:center;">
			<a href="javascript:void(0)">
				<img src="<?php echo $this->webroot ?>img/client_logo_white.png" alt="" width="150"/>
			</a>
		</div>
		<div class="sidebarSep mt0"></div>

		<?php
			echo $this->element('leftnavigation',array("lft_menu_category_id"=>$lft_menu_category_id));
		?>

	</div>
	<!-- Right side -->
	<div id="rightSide">
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
						<li>
							<a href="<?php echo $settings["cms_url"]?>Account/Logout" title="">
								<img src="<?php echo $this->webroot ?>img/icons/topnav/logout.png" alt="" />
								<span>Logout</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- Responsive header -->
		<div class="resp">
			<div class="respHead">
				<a href="<?php echo $settings["cms_url"]?>" title="">
					<img src="<?php echo $this->webroot ?>img/loginLogo.png" alt="" />
				</a>
			</div>
			<?php echo $this->element('leftnavigation_small'); ?>
		</div>
		<div class="line mt30"></div>
		<div class="wrapper">
			<div class="nNote nFailure hideit">
				<p><strong>No Access: </strong>Sorry you dont have privileges to access this page</p>
			</div>
		</div>
		<div class="line mt30"></div>

	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
