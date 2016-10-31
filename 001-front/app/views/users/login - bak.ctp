<style>
.backform
{
	background:url(<?php echo $this->webroot?>img/back_form2.gif) 50% 50% repeat;
	width:500px;
	height:500px;
	border:1px solid black;
}
</style>
<div class="backform">
</div>
<div class="box_panel">
	<div class="line1" style=" margin-bottom:10px;">
        <div class="line4" style="border:0px solid black;">
            <span class="text3">JualanMotor Login</span>
        </div>
    </div>
    <?php echo $form->create("User",array("url"=>array('controller'=>'Users','action'=>"Login")))?>
    <div class="line1">
    	<div class="line1" style="margin-bottom:12px;">
        	<div class="left" style="border:0px solid black; margin-left:190px; width:100%;">
            <span class="text4">Jika anda belum memiliki akun silahkan daftar di halaman <a href="<?php echo $settings['site_url']?>Users/Register" style="font-size:12px;">SignUp</a></span>
            </div>
        </div>
    	<div class="line1" style="margin-bottom:12px;">
        	<?php if(!empty($fByEmail)):?>
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong>Login as :</span>
                <?php echo $form->input("email_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"hidden",'error'=>false,'value'=>$fByEmail['User']['email']))?>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
            	<div class="left" style="width:50px;border:0px solid black;">
        			<img src="<?php echo $settings['showimages_url']?>?code=<?php echo $fByEmail['User']['id']?>&prefix=_tiny&content=User&w=50&h=50" />
                </div>
                <div class="left" style="width:200px;border:0px solid black; min-height:40px;">
                	<div class="line1" style="margin-top:-5px;">
                    	<span class="text4" style=" font-weight:bold;margin-left:10px;"><?php echo $fByEmail['Profile']['fullname']?></span>
                    </div>
                	
                    <div class="line1">
                    	<span class="text8" style=" font-weight:bold;margin-left:10px;margin-top:-5px;"><?php echo $fByEmail['User']['email']?></span>
                    </div>
                    
               	</div>
                <div class="line1" style="width:100%; border:0px solid black;">
                	<a href="<?php echo $settings['site_url']?>Users/Login?not=<?php echo $not?>" class="text4a">Not <?php echo $rand_name?> ? </a>
                </div>
                <div class="line1">
                    <span style="margin-left:5px" id="img_email_login"></span>
                    <span class="error" id="err_email_login"><?php echo $form->error('User.email_login')?></span>
                </div>
            </div>
            <?php else:?>
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong>Email :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("email_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text",'error'=>false))?>
                <span style="margin-left:5px" id="img_email_login"></span>
                <span class="error" id="err_email_login"><?php echo $form->error('User.email_login')?></span>
            </div>
            <?php endif;?>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong>Password :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("password_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"password","maxlength"=>10,'error'=>false))?>
                <span style="margin-left:5px" id="img_password_login"></span>
                <span class="error" id="err_password_login"><?php echo $form->error('User.password_login')?></span>
                
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                &nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:50%">
                <?php echo $form->input("keep_login",array('type'=>'checkbox','label'=>"Keep me logged in","value"=>"1"))?>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
        	<div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                &nbsp;
            </div>
        	<div class="left" style="text-align:left; width:50%; border:0px solid black;">
            	<input name="" type="image" src="<?php echo $this->webroot?>img/login_button.jpg">
                <div class="line1" style="margin-top:10px;"><a href="<?php echo $settings['site_url']?>Users/ForgotPassword" class="text4a">Forgot your password ?</a></div>
            </div>
        </div>
        <div class="line1" style="margin:10px 0 50px 0;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                Atau login via :
            </div>
            <div class="left" style="border:0px solid black; width:50%">
            	
                <a href="javascript:void(0)" style="margin-right:10px;" onclick="openFacebook()" title="Login via Facebook" id="LoginViaFacebook"><img src="<?php echo $this->webroot?>img/facebook_ico.png" border="0"/></a>
                <a href="javascript:void(0)" style="margin-right:10px;" onclick="openTwitter()" title="Login via Twitter" id="LoginViaTwitter"><img src="<?php echo $this->webroot?>img/twitter_icon.png" border="0"/></a>
                <a href="javascript:void(0)" style="margin-right:10px;" onclick="openYahoo()" title="Login via Yahoo" id="LoginViaYahoo"><img src="<?php echo $this->webroot?>img/yahoo_icon.png" border="0"/></a>
                <a href="javascript:void(0)" style="margin-right:10px;" title="Login via Google" id="LoginViaGoogle" onclick="openGoogle()"><img src="<?php echo $this->webroot?>img/google_icon.png" border="0"/></a>
            </div>
        </div>
    </div>
	<?php echo $form->end()?>
</div>