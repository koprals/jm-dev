<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.scrollTo")?>
<script>
	$(document).ready(function(){
		$("#UserEmail").watermark({watermarkText:'ex: abyfajar@gmail.com',watermarkCssClass:'kiri input3 style1 black text12 size50 italic grey2'});
	});
	function showRecaptcha() {
			Recaptcha.reload();
	}
	
	function UserForgotPasswordForm()
	{
		$("#UserForgotPasswordForm").ajaxSubmit({
			url			: "<?php echo $settings['site_url']?>Users/ProcessForgotPassword",
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
				alert("Maaf terjadi kesalahan pada server kami, mohon coba beberapa saat lagi.");
			},
			success		: function(data)
			{
				$("#output").html(data);
				$("#LoadingPict").hide(300);
				
				$("span[id^=err]").html('');
				$("span[id^=img]").html('');
				
				showRecaptcha();
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
							scrool	=	"#err_"+item.key;
						}
						
					});
					
				}
			}
		});
		return false;
	}
	
</script>
<div id="output"></div>
<?php echo $form->create("User")?>
<div class="size45 tengah" style="border:0px solid black;">
    <div class="text_title3 top30">
        <div class="line1">Lupa password anda ?</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10 top20" style="width:auto;">
        	<img src="<?php echo $settings['site_url']?>img/change_password.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	<div class="line style1 text12 bold white kiri"><?php echo $text_status?></div>
            
        	<div class="line style1 text14 bold white kiri top20">Email Address</div>
        	<div class="line top5">
            	<?php echo $form->input("email",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text",'error'=>false,"class"=>"kiri input3 style1 black text12 size50"))?>
                <span class="kiri left10" id="img_email"></span>
                <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_email"></span>
            </div>
            
            <div class="line style1 text14 bold white kiri top20">Masukkan kode captcha</div>
            <div class="line top5" id="span_captcha">
                <div class="kiri" style="width:315px;border:0px solid black;">
                	<?php echo $captchaTool->show(); ?>
                </div>
                <span class="kiri left15 size10" id="img_captcha"></span>
                <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_captcha"></span>
            </div>
            <div class="line top10">
                <input type="button" name="button" value="Send" class="tombol1" onclick="return UserForgotPasswordForm();" style="float:left"/>
                <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPict" style="float:left; display:none; margin:5px 0 0 5px;"/>
                
            </div>
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
<?php echo $form->end()?>