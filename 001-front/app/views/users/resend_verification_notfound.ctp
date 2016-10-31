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
<script>
function SubmitForm()
{
	$("#ResendForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Users/ProcessResendVerification",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#LoadingPict").show(300);
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert(textStatus);
		},
		success		: function(data)
		{
			$("#output").html(data);
			$("#LoadingPict").hide(300);
			
			$("span[id^=err]").html('');
			$("span[id^=img]").html('');
			
			if(data.status==true)
			{
				parent.Ok();
			}
			else
			{
				var err		=	data.error;
				var scrool	=	"";
				var count	=	0;
				
				$.each(err, function(i, item){
					if(item.status=="false")
					{
						$("#err_"+item.key).html(item.value);
						$("#img_"+item.key).html('<img src="<?php echo $this->webroot?>img/icn_error.png">');	
						
					}
					else if(item.status=="true")
					{
						$("#err_"+item.key).html("");
						$("#img_"+item.key).html('<img src="<?php echo $this->webroot?>img/check.png">');
						
					}
					else if(item.status=="blank")
					{
						$("#err_"+item.key).html("");
						
					}
				});
			}
		}
	});
	return false;
}
</script>

<div id="output"></div>
<?php echo $form->create('User',array("onsubmit"=>"return SubmitForm()","id"=>"ResendForm","style"=>"margin:0"))?>
<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1 top30">Email Verifikasi.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="tengah size65" style="border:0px solid black;">
            <div class="style1 text14 bold white align_center top5">Email Address</div>
            <div class="tengah size65" style="border:0px solid black;">
                <?php echo $form->input("email_resend",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text",'error'=>false,"class"=>"kiri input3 style1 black text12 size100"))?>
                <span class="kiri style1 white text12 bold left30" style="text-decoration:blink;" id="err_email_resend"></span>
            </div>
        </div>
        <div class="tengah size15" style="border:0px solid black;">
        	<div class="kiri size100 top5" style="border:0px solid black;">
                <input type="submit" value="Kirim" class="tombol1 kiri"/>
                <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPict" style="float:left; display:none; margin:5px 0 0 5px;"/>
            </div>
        </div>
    </div>
</div>
<?php $form->end()?>
</body>
</html>
