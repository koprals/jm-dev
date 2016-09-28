<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title_for_layout; ?></title>

<!-- START BLOCK CSS -->
<?PHP echo $html->css('main_css')?>
<?PHP echo $html->css('style')?>
<?php echo $html->css("prettyPhoto")?>
<!-- END BLOCK CSS -->


<!-- START BLOCK JAVASCRIPT -->
<?PHP echo $javascript->link('jquery.latest')?>
<?PHP echo $javascript->link('jquery.form')?>
<?php echo $javascript->link("jquery.prettyPhoto")?>

<!-- END BLOCK JAVASCRIPT -->
<script>
$(document).ready(function(){
	$(".gallery a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
});
</script>
</head>

<body>

<?php echo $this->element('header_menu',array('parent_code'=>$parent_code))?>
<div class="test-center">
	<div class="test-blue">
		<div class="test-white">
			<?php echo $content_for_layout; ?>
		</div>
	</div>
</div>

<?php echo $this->element('footer_menu')?>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>