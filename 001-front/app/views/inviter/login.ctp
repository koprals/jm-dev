<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="1Qe7hpPkk8Mr1RSExrrAnTRwjOwBI8jzi3v2b3Eay4Y" />
<link rel="shortcut icon" href="<?php echo $this->webroot?>img/favicon.ico">
<meta http-equiv="X-XRDS-Location" content="<?php echo $settings['site_url']?>xrds.xml" />
<meta name="google-site-verification" content="oJ-xxOvJ__DGdYIScBaG3-ElEZxyB3WPW53PQgy_yio" />
<meta name="title" content="<?php echo $title_for_layout?>" />
<meta name="description" content="<?php echo $title_for_layout?>" />
<meta name="keywords" content="<?php echo $keywords?>" />
<title><?php echo $title_for_layout; ?></title>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28726499-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- START BLOCK CSS -->
<?PHP echo $html->css('style_JM')?>
<!-- END BLOCK CSS -->


<!-- START BLOCK JAVASCRIPT -->
<?php echo $javascript->link("jquery.latest");?>
<?php echo $javascript->link("jquery.form")?>
<!-- END BLOCK JAVASCRIPT -->

<script>
function LoginForm()
{
	$("#LoginForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Inviter/ProcessLogin",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading").html('Please wait...');
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert("Maaf terjadi kesalahan pada server kami, mohon coba beberapa saat lagi.");
		},
		success		: function(data)
		{
			$("#output").html(data);
			$("#loading").html('');
			$("span[id^=err]").html('');
			$("span[id^=img]").html('');
			
			if(data.status==true)
			{
				location.href	=	data.error;
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
						count++;
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
					
					if(count==1 && item.status=="false")
					{
						scrool	=	"#span_"+item.key;
					}
					
				});
			}
		}
	});
	return false;
}

function InviteForm()
{
	$("#InviteForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Inviter/ProcessInvite",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading2").html('Please wait...');
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert("Maaf terjadi kesalahan pada server kami, mohon coba beberapa saat lagi.");
		},
		success		: function(data)
		{
			$("#output").html(data);
			$("#email").html('');
			$("span[id^=err]").html('');
			$("span[id^=img]").html('');
			$("#loading2").html('');
			if(data.status==true)
			{
				$("#form").html('<div class="kiri style1 white bold text12 size90 left20 top30 bottom20">Terimakasih anda telah mau ikut bergabung bersama kami, kami akan kirimkan anda email yang berisi informasi username dan password anda. Email akan kami kirim dalam waktu 1x24 jam.</div>');
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
						count++;
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
					
					if(count==1 && item.status=="false")
					{
						scrool	=	"#span_"+item.key;
					}
					
				});
				
			}
		}
	});
	return false;
}

</script>
<body>
<div class="line">
	<div class="size27 tengah">
    	<div class="style1 text17 bold red2 top50">INVITATION ONLY</div>
        <div class="style1 text13 bold black2">Jika anda telah mendapatkan email undangan dari kami, silahkan masukkan username dan password yang kami kirimkan ke email anda.</div>
        <div class="line back3 size100 kiri rounded1 position1 top10" style="padding-bottom:10px;">
            <div class="line">
                <?php echo $form->create("Invitation",array("id"=>"LoginForm","onsubmit"=>"return LoginForm()"))?>
                <div class="line top20" style="border:0px solid black;">
                    <div class="style1 text14 bold white align_center">Username</div>
                    <div class="tengah size65" style="border:0px solid black;">
                    	<?php echo $form->input("username",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text",'error'=>false,"class"=>"kiri left30 input3 style1 black text12 size70"))?>
                    	<span class="kanan" id="img_username"></span>
                		<span class="kiri style1 white text12 bold left30" style="text-decoration:blink;" id="err_username"></span>
                    </div>
                </div>
                <div class="line top5">
                    <div class="style1 text14 bold white align_center">Password</div>
                    <div class="tengah size65" style="border:0px solid black;">
						<?php echo $form->input("password",array("class"=>"user","div"=>false,"label"=>false,"type"=>"password",'error'=>false,"class"=>"kiri left30 input3 style1 black text12 size70"))?>
                        <span class="kanan" id="img_password"></span>
                        <span class="kiri style1 white text12 bold left30" style="text-decoration:blink;" id="err_password"></span>
                    </div>
                </div>
                
                <div class="tengah size15">
                    <div class="line top10">
                        <input type="submit" name="button" id="button" value="SIGN IN" class="tombol1 kiri"  style="float:left"/>
                    </div>
                </div>
                <div class="kiri style1 white text12 left5 top17" style="display:block" id="loading"></div>
                <?php echo $form->end()?>
            </div>            
        </div>
        <div class="line top-20"><img src="<?PHP echo $this->webroot?>img/shadow.png" width="100%" /></div>
        
        
        <div class="style1 text17 bold red2 top50 top20 kiri">INVITE ME ..</div>
        
        <div class="line back3 size100 kiri rounded1 position1 top10" style="padding-bottom:10px;">
            <div class="line">
                <div id="form">
            	<?php echo $form->create("Invitation",array("id"=>"InviteForm","onsubmit"=>"return InviteForm()"))?>
            	<div class="line top20" style="border:0px solid black;">
                    <div class="style1 text14 bold white align_center">Email</div>
                    <div class="tengah size65" style="border:0px solid black;">
                    	<?php echo $form->input("email",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text",'error'=>false,"class"=>"kiri left30 input3 style1 black text12 size70"))?>
                    	<span class="kanan" id="img_email"></span>
                		<span class="kiri style1 white text12 bold left30" style="text-decoration:blink;" id="err_email"></span>
                    </div>
                </div>
                <div class="tengah size15">
                    <div class="line top10">
                        <input type="submit" name="button" id="button" value="Invite" class="tombol1 tengah" />
                    </div>
                </div>
                <div class="kiri style1 white text12 left5 top17" style="display:block" id="loading2"></div>
                <?php echo $form->end()?>
                </div>
            </div>            
        </div>
        <div class="line top-20"><img src="<?PHP echo $this->webroot?>img/shadow.png" width="100%" /></div>
    </div>
</div>
</body>
</html>