<div class="main" style="height:78px;">
	<div class="fixbox" style="border:0px solid black;">
    	<div class="line1" style="border:0px solid black; width:100%; margin-top:5px;">
        	<div class="left" style="width:49%;border:0px solid black;">
            	<?php if($is_login=="0" && !empty($rand_detail['User']['id'])):?>
           		<span class="text7">Welcome <?php echo $rand_name?>, <a href="<?php echo $settings['site_url']?>Users/Login" class="text7">Sign In</a> (Not <a href="<?php echo $settings['site_url']?>Users/DeleteRandUser" class="text7">you</a>?)</span>
                <?php endif;?>
            </div>
            <div class="right" style="width:49%;border:0px solid black;">
            	<div class="regsign-area" style="border:0px solid black; margin-top:0px;">
					<?php if($is_login=="0"):?>
                    <a href="<?php echo $settings['site_url']?>Users/Login" class="reg">Sign In</a>&nbsp;|&nbsp;<a href="<?php echo $settings['site_url']?>Users/Register" class="reg">Sign Up</a>
                    
                    <?php else:?>
                    <span class="reg">Hai,</span> <a href="<?php echo $settings['site_url']?>Cpanel/UpdateProfile" class="reg"><?php echo $display_name?></a>&nbsp;|&nbsp;<a href="<?php echo $settings['site_url']?>Users/LogOut" class="reg">Log Out</a>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div class="line1" style="border:0px solid black; width:100%; margin-top:5px;">
            <div class="logo" style="border:0px solid black; padding-top:0px;">
                <a href="<?php echo $settings['site_url']?>">
                    <img src="<?php echo $this->webroot?>img/newlogo.png" border="0">
                </a>
            </div>
           
            <?php if($is_login=="0"):?>
            <div class="right" style="border:0px solid black; width:145px;">
                <div class="regsign-area" style="width:110px;;border:0px solid black; margin-top:-15px;">
                    <a href="<?php echo $settings['site_url']?>Users/ForgotPassword" class="text4a">Forgot Password ?</a>
                </div>
                <div class="line1" style="margin-top:15px;">
                	<a href="javascript:void(0)" style="margin-right:10px;" onclick="openFacebook()" title="Login via Facebook" id="LoginKclFacebook"><img src="<?php echo $this->webroot?>img/facebook_24.png" border="0"/></a>
                    <a href="javascript:void(0)" style="margin-right:10px;" onclick="openTwitter()" title="Login via Twitter" id="LoginKclTwitter"><img src="<?php echo $this->webroot?>img/twitter_24.png" border="0"/></a>
                    <a href="javascript:void(0)" style="margin-right:10px;" onclick="openYahoo()" title="Login via Yahoo" id="LoginKclYahoo"><img src="<?php echo $this->webroot?>img/yahoo_24.png" border="0"/></a>
                    <a href="javascript:void(0)" style="margin-right:10px;" onclick="openGoogle()" title="Login via Google" id="LoginKclGoogle"><img src="<?php echo $this->webroot?>img/google_24.png" border="0"/></a>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="menu-area">
	<ul class="menu_header">
        <li><a href="#tab1" class="active">Home</a></li>
        <li><a href="#tab2">Jual Motor</a></li>
        <li><a href="#tab3">Cari Motor</a></li>
        <li><a href="#tab4">Contact Us</a></li>
        <li><a href="#tab4">Faq</a></li>
        <li><a href="#tab3">Resources</a></li>
    </ul>
    <div class="search_area">
    	<div class="center_search">
        	<div class="bungkus">
       			<input name="" type="text" class="search"/>
            </div>
            <div class="bungkus_cari">
            	<input name="" type="button" class="btn_search" value="Cari"/>
            </div>
      	</div>
    </div>
</div>