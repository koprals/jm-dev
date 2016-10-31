
<script>
$(document).ready(function(){
						   
	
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
					  $("#err_login_"+item.key).animate({opacity: 1.0}, 3000);
					  $("#err_login_"+item.key).fadeOut('slow');
					  $("#img_login_"+item.key).animate({opacity: 1.0}, 3000);
					  $("#img_login_"+item.key).fadeOut('slow');
				  }
				  else if(item.status=="true")
				  {
					  $("#err_login_"+item.key).html("");
					  $("#img_login_"+item.key).html('<img src="<?php echo $this->webroot?>img/check.png">');
					  $("#img_login_"+item.key).animate({opacity: 1.0}, 3000);
					  $("#img_login_"+item.key).fadeOut('slow');
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
<div class="box_panel">
	<div class="line1" style=" margin-bottom:10px;">
        <div class="line4" style="border:0px solid black;">
            <span class="text3">Registrasi via <?php echo $vendor?></span>
        </div>
    </div>
    
    <div class="line1" style="margin-left:10px;">
    	<span class="text6" style="width:100%">Hallo <?php echo $fullname?> !</span>
        <span>Thanks for sign in via Facebook, please put your profile before continue.</span>
        <div class="line1" style="margin-top:30px;">
        	<?php echo $form->create("User",array("onsubmit return Register()"))?>
        	<div class="left" style="width:45%">
            	<div class="line1" style="border:0px solid black; margin-bottom:5px;">
                    <div class="text_user" style="margin-bottom:2px; width:100%">Fullname</div>
                    <?php echo $form->input("fullname",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>30,'error'=>false,"value"=>$fullname))?>
                    <span style="margin-left:5px" id="img_register_fullname"></span>
                    <span class="error" id="err_register_fullname"></span>
                </div>
            	<div class="line1" style="border:0px solid black; margin-bottom:5px;">
                    <div class="text_user" style="margin-bottom:2px; width:100%">Email</div>
                    <?php echo $form->input("email",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>100,'error'=>false,"value"=>$email,"readonly"=>$readonly,"style"=>"background-color:".$bg))?>
                    <span style="margin-left:5px" id="img_register_email"></span>
                    <span class="error" id="err_register_email"></span>
                </div>
                <div class="line1" style="border:0px solid black; margin-bottom:5px;">
                    <div class="text_user" style="margin-bottom:2px; width:100%">Password</div>
                    <?php echo $form->input("password",array("class"=>"user","div"=>false,"label"=>false,"type"=>"password","maxlength"=>20,'error'=>false))?>
                    <span style="margin-left:5px" id="img_register_password"></span>
                    <span class="error" id="err_register_password"></span>
                </div>
                <div class="line1" style="margin-bottom:5px;">
                    <div class="left" style="border:0px solid black; width:50%">
                        <?php echo $form->input("publish",array('type'=>'checkbox','label'=>"Publish to ".$vendor,"value"=>"1"))?>
                    </div>
                </div>
                <div class="line1" style="margin-top:5px; margin-bottom:30px;">
                    <div class="left" style="width:73%;">
                        <input type="submit" name="button" id="button" value="Register" class="btn_sign" onClick="return Register()"/>
                        <span class="font4" style="color:#000000;" id="loading_register"></span>
                    </div>
                </div>
            </div>
            <?php echo $form->end()?>
            <div class="left" style="width:5%; border-left:1px solid #999; height:200px;">
            	&nbsp;
            </div>
            <?php echo $form->create("Login",array("onsubmit"=>"return Login()"))?>
            <div class="left" style="width:45%;border:0px solid black;">
            	<div class="line1" style="border:0px solid black; margin-bottom:5px;">
                	<span>Already have a JualanMotor account?</span>
                </div>
                <div class="line1" style="border:0px solid black; margin-bottom:5px;">
                    <div class="text_user" style="margin-bottom:2px; width:100%">Email</div>
                    <?php echo $form->input("email_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>100,'error'=>false,"value"=>$email))?>
                    <span style="margin-left:5px" id="img_login_email_login"></span>
                    <span class="error" id="err_login_email_login"></span>
                </div>
                <div class="line1" style="border:0px solid black; margin-bottom:5px;">
                    <div class="text_user" style="margin-bottom:2px; width:100%">Password</div>
                    <?php echo $form->input("password_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"password","maxlength"=>20,'error'=>false))?>
                    <span style="margin-left:5px" id="img_login_password_login"></span>
                    <span class="error" id="err_login_password_login"></span>
                </div>
                <div class="line1" style="margin-bottom:5px;">
                    <div class="left" style="border:0px solid black; width:50%">
                        <?php echo $form->input("keep_login",array('type'=>'checkbox','label'=>"Keep me logged in","value"=>"1"))?>
                    </div>
                </div>
                <div class="line1" style="margin-top:5px; margin-bottom:30px;">
                    <div class="left" style="width:63%;">
                        <input type="submit" name="button" id="button" value="Login" class="btn_sign" onClick="return Login()"/>
                        <span class="font4" style="color:#000000;" id="loading_login"></span>
                    </div>
                </div>
            </div>
            <?php echo $form->end()?>
        </div>
    </div>
   
	
</div>