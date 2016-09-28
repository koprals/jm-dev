<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title><?php echo $title_for_layout; ?></title>

<!-- START BLOCK CSS -->
<?PHP echo $html->css('style_JM')?>
<!-- END BLOCK CSS -->

<!-- START BLOCK JAVASCRIPT -->
<?PHP echo $javascript->link('jquery.latest')?>
<?PHP echo $javascript->link('jquery.form')?>
<!-- END BLOCK JAVASCRIPT -->
</head>
<body>
<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1 top30">Email Verifikasi.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="tengah size65" style="border:0px solid black;">
            <div class="style1 text14 bold white align_center top5">Kami telah mengirimkan email verifikasi kembali ke alamat <?php echo $email?>.</div>
		</div>
	</div>
</div>
</body>
</html>