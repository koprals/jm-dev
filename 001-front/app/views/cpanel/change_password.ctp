<?php echo $javascript->link("jquery.scrollTo")?>
<script>
function SubmitChangePassword()
{
	$("#ChangePassword").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Cpanel/ProcessChangePassword",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19.gif" style="float:left; display:block;vertical-align:middle; margin-right:5px;"/>Please wait...');
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
				$(document).scrollTo("#updt_prfl", 800);
				$("#success").fadeIn('slow');
				$("#success").animate({opacity: 1.0}, 3000);
				$("#success").fadeOut('slow',function(){
					window.location.reload();
				});
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
				$(document).scrollTo(scrool, 800);
			}
		}
	});
	return false;
}
</script>


<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Ubah Password</div>
        </div>
        <div class="line back1 size100 kiri position1 rounded2" style="padding-bottom:10px;" id="updt_prfl">
        	<div class="size70 tengah" id="success"  style="display:none">
                <div class="kiri size100 style1 white top10 reounded_error">
                    <div class="kiri size100 left10 top10 bold text14 blink">Password Anda telah kami ubah</div>
                    <div class="kiri size100 left10 top10 bold text12 bottom10">Mohon tunggu, kami segarkan halaman anda.</div>
                </div>
            </div>
            <?php echo $form->create("User",array("onsubmit"=>"return SubmitChangePassword()","id"=>"ChangePassword"))?>
             
            <div class="kiri size95 left10" style="border:0px solid black;">
                <div class="kiri size100 top15">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_newpassword"><span class="text15">*</span> Passwor Baru</div>
                    <div class="kiri size70 left10">
                    	
                        <?php echo $form->input("newpassword",array("div"=>false,"label"=>false,"type"=>"password","class"=>"input2 style1 white text12 size50 kiri","autocomplete"=>"off"))?>
                        
                        <span class="kiri left10" id="img_newpassword"></span>
                        <span class="kiri style1 white text12 bold size50" style="text-decoration:blink; border:0px solid black;" id="err_newpassword"></span>
                    </div>
                </div>
                <div class="kiri size100 top15">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_cnewpassword"><span class="text15">*</span> Ulangi</div>
                    <div class="kiri size70 left10">
                    	
                        <?php echo $form->input("cnewpassword",array("div"=>false,"label"=>false,"type"=>"password","class"=>"input2 style1 white text12 size50 kiri"))?>
                        
                        <span class="kiri left10" id="img_cnewpassword"></span>
                        <span class="kiri style1 white text12 bold size50" style="text-decoration:blink;" id="err_cnewpassword"></span>
                    </div>
                </div>
                <div class="line top20 bottom10">
                    <input type="button" name="button" value="UBAH PASSWORD" class="tombol1" onclick="SubmitChangePassword()" style="float:left"/>
                    <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                </div>
			</div>
            <?php echo $form->end()?>
        </div>       
	</div>        
</div>