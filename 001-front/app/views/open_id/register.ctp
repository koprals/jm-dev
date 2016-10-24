<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->

<script>
var fade_in = 500;
var fade_out = 500;
$(document).ready(function(){
	if($.browser.msie)
	{
		fade_in 	= 100;
		fade_out	= 3500;
	}
	$('input,textarea').each(function(){
		$(this).bt({
		  width: 230,
		  trigger: ['focus', 'blur'],
		  positions: ['right'],
		  cornerRadius: 7,
		  strokeStyle: '#FFFFFF',
		  fill: 'rgba(136, 136, 136, 1)',
		  showTip: function(box){
			$(box).fadeIn(fade_in);
		  },
		  hideTip: function(box, callback){
			$(box).animate({opacity: 0}, fade_out, callback);
		  },
		  cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px'},
		  shrinkToFit: true,
		  hoverIntentOpts: {
			interval: 0,
			timeout: 0
		  }
		});
	});
	$("#UserFullname").watermark({watermarkText:'ex: Her Robby Fajar',watermarkCssClass:'input3 style1 grey italic text12 size70 kiri'});
});

function Register()
{
	$("#UserResultsForm").ajaxSubmit({
	  url			: "<?php echo $settings['site_url']?>OpenId/UserRegister",
	  type			: "POST",
	  dataType		: "json",
	  clearForm		: false,
	  beforeSend	: function()
	  {
		  $("#loading_register").html('<img src="<?php echo $this->webroot?>img/loading19.gif"/>Please wait ..');
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
		  $("#loading_register").html('');
		  
		  $("span[id^=err_register_]").html('');
		  $("span[id^=img_register_]").html('');
		  
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
					  $("#err_register_"+item.key).html(item.value);
					  $("#img_register_"+item.key).html('<img src="<?php echo $this->webroot?>img/icn_error.png">');	
					  count++;
				  }
				  else if(item.status=="true")
				  {
					  $("#err_register_"+item.key).html("");
					  $("#img_register_"+item.key).html('<img src="<?php echo $this->webroot?>img/check.png">');
				  }
				  else if(item.status=="blank")
				  {
					  $("#err_register_"+item.key).html(""); 
				  }
			  });
		  }
	  }
  });
  return false;
}

function Login()
{
	$("#LoginResultsForm").ajaxSubmit({
	  url			: "<?php echo $settings['site_url']?>OpenId/UserLogin",
	  type			: "POST",
	  dataType		: "json",
	  clearForm		: false,
	  beforeSend	: function()
	  {
		  $("#loading_login").html('<img src="<?php echo $this->webroot?>img/loading19.gif"/>Please wait ..');
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
		  $("#loading_login").html('');
		  
		  $("span[id^=err_login_]").html('');
		  $("span[id^=img_login_]").html('');
		  
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
					  $("#err_login_"+item.key).html(item.value);
					  $("#img_login_"+item.key).html('<img src="<?php echo $this->webroot?>img/icn_error.png">');
					  count++;
				  }
				  else if(item.status=="true")
				  {
					  $("#err_login_"+item.key).html("");
					  $("#img_login_"+item.key).html('<img src="<?php echo $this->webroot?>img/check.png">');
				  }
				  else if(item.status=="blank")
				  {
					  $("#err_login_"+item.key).html(""); 
				  }
			  });
		  }
	  }
  });
  return false;
}

</script>
<div id="output"></div>
<div class="line top10">
    <div class="size70 tengah" style="border:0px solid black;">
        <div class="kiri size45 top30" style="border:0px solid black;">
        	<div class="line" style="height:75px;">
                <div class="style1 text17 bold red2">LOGIN VIA <?php echo $vendor?></div>
                <div class="style1 text13 bold black2 top5">Hallo <?php echo $fullname?> !</div>
                <div class="style1 text13 black2">Terimakasih anda telah login menggunakan <?php echo $vendor?>, harap isi profil anda dulu sebelum melanjutkan.</div>
            </div>
            <div class="text_title3 top10">
                <div class="line1">REGISTER. MUDAH & GRATIS KOK.</div>
            </div>
            <div class="line back3 size100 kiri position1 rounded2" style="padding-bottom:10px;">
                <div class="line1">
                    <?php echo $form->create("User",array("onsubmit"=>"return Register()"))?>
                        <div class="line top10">
                            <div class="style1 text14 bold white" id="span_fullname"><span class="text15">*</span> Nama Lengkap :</div>
                            <?php echo $form->input("fullname",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter.","class"=>"input3 style1 black text12 size70 kiri","value"=>$fullname,"autocomplete"=>false))?>
                            <span class="kiri left10" id="img_register_fullname"></span>
                            <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_register_fullname">
                            </span>
                        </div>
                        <div class="line top10">
                            <div class="style1 text14 bold white" id="span_email"><span class="text15">*</span>Email Address :</div>
                            <?php echo $form->input("email",array("div"=>false,"label"=>false,"type"=>"text","value"=>$email,"readonly"=>$readonly,"style"=>"background-color:".$bg,"class"=>"input3 style1 black text12 size70 kiri","autocomplete"=>"false"))?>
                            <span class="kiri left10" id="img_register_email"></span>
                            <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_register_email"></span>
                        </div>
                        <div class="line top10">
                            <div class="style1 text14 bold white" id="span_password"><span class="text15">*</span>Password :</div>
                            <?php echo $form->input("password",array("div"=>false,"label"=>false,"type"=>"password","maxlength"=>10,"class"=>"input3 style1 black text12 size70 kiri","autocomplete"=>false))?>
                            <span class="kiri left10" id="img_register_password"></span>
                            <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_register_password"></span>
                        </div>
                        <?php if(in_array(strtolower($vendor),array("facebook","twitter"))):?>
                        <div class="line top10" >
                            <div class="style1 text12 white">
                                <?php echo $form->input("publish",array('type'=>'checkbox','div'=>false,'label'=>false,"value"=>"1","escape"=>false))?>
                                <label for="UserPublish"><?php echo "Publish to ".$vendor ?></label>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="line" >
                            <div class="style1 text12 white">
                                <?php echo $form->input("agree",array('type'=>'checkbox','div'=>false,'label'=>false,"value"=>"1","escape"=>false,"id"=>"agree"))?>
                                <label for="agree">Saya setuju dengan </label><a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/TermConditions?iframe=true&amp;width=400&amp;height=440');" class="red normal">perjanjian</a><label for="agree"> <?php echo $settings['site_name']?></label><span class="style1 white text12 bold left10" style="text-decoration:blink;" id="img_register_agree"></span>
                                <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_register_agree"></span>
                            </div>
                        </div>
                        <div class="line top10">
                            <input type="button" name="button" value="REGISTER" class="tombol1" onclick="return Register()" style="float:left"/>
                            <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading_register"></div>
                        </div>
                    <?php echo $form->end()?>
                </div>
            </div>
            <div class="line top-25"><img src="<?PHP echo $this->webroot?>img/shadow.png" width="100%" /></div>
        </div>
        <div class="kiri size9" style="border-right:0px solid grey;">
            &nbsp;
        </div>
        <div class="kiri size45 top30" style="border:0px solid black;">
        	<div class="line" style="height:85px;">
                <div class="style1 text17 bold red2">Sudah punya akun ?</div>
                <div class="style1 text13 black2 top5">
                    Jika anda telah memiliki akun, silahkan masukkan akun email dan password anda disini.
                </div>
            </div>
            
            <div class="line back3 size100 kiri position1 rounded1" style="padding-bottom:10px;">
                <div class="line1">
                    <?php echo $form->create("Login",array("onsubmit"=>"return Login()"))?>
                        <div class="line top10">
                            <div class="style1 text14 bold white align_center right25">Email Address</div>
                            <div class="tengah size60" style="border:0px solid black;">
								<div class="kiri size85">
									<?php echo $form->input("email_login",array("div"=>false,"label"=>false,"type"=>"text","class"=>"input3 style1 black text12 size100 kiri","value"=>$email))?>
                                </div>
                                
                                <span class="kiri size9 left10" id="img_login_email_login"></span>
                                <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_login_email_login"></span>
                            </div>
                        </div>
                        <div class="line top20">
                            <div class="style1 text14 bold white align_center right25">Password</div>
                            <div class="tengah size60" style="border:0px solid black;">
								<div class="kiri size85">
									<?php echo $form->input("password_login",array("div"=>false,"label"=>false,"type"=>"text","class"=>"input3 style1 black text12 size100 kiri","value"=>$email,"type"=>"password"))?>
                                </div>
                                
                                <span class="kiri size9 left10" id="img_login_password_login"></span>
                                <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_login_password_login"></span>
                            </div>
                        </div>
                        <div class="line top10" >
                        	<div class="tengah size60" style="border:0px solid black;">
                                <div class="style1 text12 white">
                                    <?php echo $form->input("keep_login",array('type'=>'checkbox','div'=>false,'label'=>false,"value"=>"1","escape"=>false))?>
                                    <label for="LoginKeepLogin"><?php echo "Keep me logged in"?></label>
                                </div>
                            </div>
                        </div>
                        <div class="line top10">
                        	<div class="tengah size60" style="border:0px solid black;">
                                <div class="style1 text12 white">
                                    Lupa password ? <a href="<?php echo $settings['site_url']?>Users/ForgotPassword" class="red normal">Klik disini</a>
                                </div>
                            </div>
                        </div>
                        <div class="line top20">
                        	<div class="tengah size60" style="border:0px solid black;">
                                <input type="button" name="button" value="Login" class="tombol1" onclick="return Login()" style="float:left"/>
                                <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading_login"></div>
                            </div>
                        </div>
                        
                    <?php echo $form->end()?>
                </div>
            </div>
            <div class="line top-25"><img src="<?PHP echo $this->webroot?>img/shadow.png" width="100%" /></div>
        </div>
    </div>
    
</div>