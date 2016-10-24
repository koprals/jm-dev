<?php
	$error_mg_email		=	$form->error('User.email_login');
	$error_mg_password	=	$form->error('User.password_login');
	
	$mg_email		=	(!empty($this->data["User"]["email_login"]) && is_null($error_mg_email)) ? '<img src="'.$settings['site_url'].'img/check.png"/>' : ((isset($this->data["User"]["email_login"]) && !is_null($error_mg_email)) ? '<img src="'.$settings['site_url'].'img/icn_error.png"/>' : '' );
	
	
	$mg_password		=	(!empty($this->data["User"]["password_login"]) && is_null($error_mg_password)) ? '<img src="'.$settings['site_url'].'img/check.png"/>' : ((isset($this->data["User"]["password_login"]) && !is_null($error_mg_password)) ? '<img src="'.$settings['site_url'].'img/icn_error.png"/>' : '' );
?>
<?php echo $form->create("User",array("url"=>array('controller'=>'Users','action'=>"Login")))?>
<div class="line">
	<div class="size27 tengah">
    	<div class="style1 text17 bold red2 top50">SIGN IN</div>
        
        <div class="style1 text13 bold black2">Jika anda belum memiliki akun silahkan <a href="<?php echo $settings['site_url']?>Users/Register" class="style1 text13 red underline">klik disini</a> untuk registrasi.</div>
        <div class="line back3 size100 kiri rounded1 position1 top10" style="padding-bottom:10px;">
        	
            <div class="line">
            	<?php if(!empty($fByEmail)):?>
                <div class="line top20 bottom10" style="border:0px solid black;">
                    <div class="style1 text14 bold white align_center">Login sebagai:</div>
                    <?php echo $form->input("email_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"hidden",'error'=>false,'value'=>$fByEmail['User']['email']))?>
                    <div class="tengah size60" style="border:0px solid black;">
                    	<div class="line" style="border:0px solid black;">
                            <div class="kiri top10 left20">
                                <img src="<?php echo $settings['showimages_url']?>?code=<?php echo $fByEmail['User']['id']?>&prefix=_50_50&content=User&w=50&h=50" />
                            </div>
                            <div class="kiri size60 left5 top10 style1 white text12 bold" style="border:0px solid black; min-height:40px;">
                                <div class="kiri style1 white text12 bold size100"><?php echo $fByEmail['Profile']['fullname']?></div>
                                <div class="kiri style1 white text12 bold size100 top5"><?php echo $fByEmail['User']['email']?></div>
                                <div class="kiri size100 top5"><a href="<?php echo $settings['site_url']?>Users/Login?not=<?php echo $not?>" class="style1 red text12 bold normal">Not <?php echo $fByEmail['Profile']['fullname']?> ? </a></div>
                            </div>
                        </div>
                        <div class="line" style="border:0px solid black;">
                        	<div class="kiri left20 style1 white text12 bold"><?php echo $form->error('User.email_login',NULL,array("wrap"=>false))?></div>	
                        </div>
                    </div>
                </div>
                
                <?php else:?>
                <div class="line top20" style="border:0px solid black;">
                    <div class="style1 text14 bold white align_center">Email Address</div>
                    <div class="tengah size65" style="border:0px solid black;">
                    	<?php echo $form->input("email_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text",'error'=>false,"class"=>"kiri left30 input3 style1 black text12 size70"))?>
                    	<span class="kanan"><?php echo $mg_email?></span>
                		<span class="kiri style1 white text12 bold left30" style="text-decoration:blink;"><?php echo $form->error('User.email_login',NULL,array("wrap"=>false))?></span>
                    </div>
                </div>
                <?php endif;?>
                <div class="line top5">
                    <div class="style1 text14 bold white align_center">Password</div>
                    <div class="tengah size65" style="border:0px solid black;">
						<?php echo $form->input("password_login",array("class"=>"user","div"=>false,"label"=>false,"type"=>"password",'error'=>false,"class"=>"kiri left30 input3 style1 black text12 size70"))?>
                        <span class="kanan"><?php echo $mg_password?></span>
                        <span class="kiri style1 white text12 bold left30" style="text-decoration:blink;"><?php echo $form->error('User.password_login',NULL,array("wrap"=>false))?></span>
                    </div>
                </div>
                <div class="line top10">
                    <div class="style1 text12 white align_center">
                    	<?php echo $form->input("keep_login",array('type'=>'checkbox','label'=>" Tetap masuk<br />","value"=>"1","escape"=>false))?>
                        Lupa password ? <a href="<?php echo $settings['site_url']?>Users/ForgotPassword" class="red normal">Klik disini</a>
                    </div>
                </div>
                <div class="tengah size15">
                    <div class="line top10">
                        <input type="submit" name="button" id="button" value="SIGN IN" class="tombol1 tengah" />
                    </div>
                </div>
            </div>            
        </div>
        <div class="line top-20"><img src="<?PHP echo $this->webroot?>img/shadow.png" width="100%" /></div>
    </div>
</div>
<?php echo $form->end()?>

<div class="line">
	<div class="size27 tengah">
    	<div class="style1 text17 bold red2 top50">Atau Login Via:</div>
        <div class="style1 text13 bold black2">Pilih social media yang anda gunakan untuk login.</div>
    </div>
    <div class="size20 tengah">
    	<div class="line kiri top10">
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openFacebook()" title="Login via Facebook" id="LoginViaFacebook"><img src="<?php echo $this->webroot?>img/facebook_ico.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openTwitter()" title="Login via Twitter" id="LoginViaTwitter"><img src="<?php echo $this->webroot?>img/twitter_icon.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openYahoo()" title="Login via Yahoo" id="LoginViaYahoo"><img src="<?php echo $this->webroot?>img/yahoo_icon.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" title="Login via Google" id="LoginViaGoogle" onclick="openGoogle()"><img src="<?php echo $this->webroot?>img/google_icon.png" border="0"/></a>
        </div>
    </div>
</div>
