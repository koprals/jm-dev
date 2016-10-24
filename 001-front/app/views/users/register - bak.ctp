<?php echo $javascript->link("jquery.filestyle")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<script>
	var fade_in = 500;
	var fade_out = 500;
	$(document).ready(function(){
		$("input.browse1").filestyle({ 
			  image: "<?php echo $this->webroot?>img/browse.png",
			  imageheight : 30,
			  imagewidth : 80,
			  width : 1,
			  height : 30		  
		});
		
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
			  strokeStyle: '#A9F826',
		 	 fill: 'rgba(205, 233, 159, 0.8)',
			  showTip: function(box){
				$(box).fadeIn(fade_in);
			  },
			  hideTip: function(box, callback){
				$(box).animate({opacity: 0}, fade_out, callback);
			  },
			  shrinkToFit: true,
			  hoverIntentOpts: {
				interval: 0,
				timeout: 0
			  }
			});
		});
		$("#UserFullname").watermark({watermarkText:'ex: Her Robby Fajar',watermarkCssClass:'user_watermark'});
		$("#UserAddress").watermark({watermarkText:'ex: Jl Kedoya Timur No5',watermarkCssClass:'address_watermark'});
		$("#UserPhone").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'user_watermark'});
		$("#UserEmail").watermark({watermarkText:'ex: abyfajar@gmail.com',watermarkCssClass:'user_watermark'});
		$("#UserCname").watermark({watermarkText:'ex: Panprisa Motor',watermarkCssClass:'user_watermark'});
		$("#UserAddress").jqEasyCounter({ 
			   'maxChars': <?php echo $settings['max_address_char']?>,
			   'maxCharsWarning': <?php echo $settings['max_address_char']-50?>,
			   'msgFontSize': '12px',
			   'msgFontColor': '#000',
			   'msgFontFamily': 'Arial',
			   'msgTextAlign': 'left',
			   'msgWarningColor': '#F00',
			   'msgAppendMethod': 'insertBefore',
			   'msgAppendSelector':'#charleft'     	  
		});
		
		//TOOTLTIPS
		$("a[id^=LoginVia]").tooltip({bounce: true,position: "top center",tipClass:"tooltip2"});
		
		$('a[rel^=help]').each(function(){
			$(this).bt({
			  width: 230,
			  trigger: ['hover'],
			  positions: ['right'],
			  cornerRadius: 7,
			  strokeStyle: '#A9F826',
			  fill: 'rgba(205, 233, 159, 0.8)',
			  showTip: function(box){
				$(box).fadeIn(fade_in);
			  },
			  hideTip: function(box, callback){
				$(box).animate({opacity: 0}, fade_out, callback);
			  },
			  shrinkToFit: true,
			  hoverIntentOpts: {
				interval: 0,
				timeout: 0
			  }
			});
		});
		
		
		
	});
	
	function showRecaptcha() {
			Recaptcha.reload();
	}
	
	function ChooseTypeMember(value)
	{
		$("#img_cname").html("");
			$("#err_cname").html("");
		if(value=="2")
		{
			$("#dealer").fadeIn(900);
		}
		else if(value=="1")
		{
			$("#dealer").fadeOut(900);
			
		}
	}
	
	function SelectCity(province_id)
	{
		if(province_id.length>0)
		{

			$("#pilih_kota").load("<?php echo $settings['site_url']?>Template/SelectCity/",{'province_id':province_id});
		}
		else
		{
			$("#pilih_kota").html('<select name="data[User][city]" style="width: 160px;" class="text7" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>');	
		}
	}
	
	
	function cancelUpload()
	{
		$("#cancelButton").fadeOut(300);
		$("#img_photo").html('');
		$("#err_photo").html('');
		
		$("#file_browse").html('<?php echo $form->file("User.photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?>');
		$("#name_file").html('');
		$("input.browse1").filestyle({ 
			  image: "<?php echo $this->webroot?>img/browse.png",
			  imageheight : 30,
			  imagewidth : 80,
			  width : 1,
			  height : 30		  
		});
		$("#UserPhoto").attr("src","<?php echo $this->webroot?>img/user.png");
		$("#agreement").hide(300);
	}
	
	function SubmitRegister()
	{
		$("#UserRegisterForm").ajaxSubmit({
			url			: "<?php echo $settings['site_url']?>Users/ProcessRegister",
			type		: "POST",
			dataType	: "json",
			contentType	: "multipart/form-data",
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
					$(document).scrollTo(scrool, 800);
				}
			}
		});
		return false;
	}
	
	function UploadPhoto()
	{
		$("#img_photo").html('');
		$("#err_photo").html('');
		$("#err_photo").html('');
		$("#UserRegisterForm").ajaxSubmit({
			url			: "<?php echo $settings['site_url']?>Users/UploadTmp",
			type		: "POST",
			dataType	: "json",
			contentType	: "multipart/form-data",
			clearForm	: false,
			beforeSend	: function()
			{
				$("#UserPhoto").hide();
				$("#LoadingPhoto").show();
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
				
				if(data.status==true)
				{
					$("#UserPhoto").attr("src","<?php echo $settings['showimages_url']?>?code="+data.error+"&prefix=_prevthumb&content=RandomUser&w=120&h=120&time"+(new Date()).getTime());
					$("#UserPhoto").load(function(){
						$(this).show();
						$("#LoadingPhoto").hide();
					});
					$("#cancelButton").fadeIn(300);
					$("#img_photo").html('<img src="<?php echo $this->webroot?>img/check.png" />');
					$("#name_file").html(data.name_file);
					$("#agreement").show(300);
				}
				else
				{
					$("#cancelButton").fadeIn(300);
					$("#img_photo").html('<img src="<?php echo $this->webroot?>img/icn_error.png" />');
					$("#UserPhoto").attr("src","<?php echo $this->webroot?>img/user.png");
					$("#LoadingPhoto").hide();
					$("#UserPhoto").show(300);
					$("#err_photo").html(data.error);
					$("#agreement").hide(300);
					$("#name_file").html(data.name_file);
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
            <span class="text3">Registrasi</span>
        </div>
    </div>
    <?php echo $form->create("User",array("type"=>"file"))?>
    <div class="line1">
    	<div class="line1" style="border:0px solid black; margin-left:60px; margin-bottom:10px;">
            <span class="text6">Informasi Data Pribadi</span>
        </div>
    	<div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Nama Lengkap :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("fullname",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter."))?>
                <span style="margin-left:5px" id="img_fullname"></span>
                <span class="error" id="err_fullname"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px; border:0px solid black;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Alamat</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		 <?php echo $form->textarea("address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","title"=>"Masukkan alamat lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter."))?>
                 <span style="margin-left:5px;" id="img_address"></span>
                 <span class="error" id="err_address" ></span>
                 <span class="text8" id="charleft" style="float:left; width:100%"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Propinsi :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
        		 <?php echo $form->select("province",$province,false,array("style"=>"width:160px","class"=>"text7","label"=>"false","escape"=>false,"empty"=>"Pilih Propinsi","onchange"=>"SelectCity(this.value)"));?>
                 <span style="margin-left:5px;" id="img_province"></span>
                 <span class="error" id="err_province"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;" >
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Kota :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%" >
        		 <div id="pilih_kota"><?php echo $form->select("city",array(""=>"Pilih Kota"),false,array("style"=>"width:160px","class"=>"text7","label"=>"false","escape"=>false,"empty"=>false));?></div>
                 <span class="error" id="err_city"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;" >
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Latitude/Longitude :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%" >
        		 <div id="lat/lon">
                 	<a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/Map?iframe=true&amp;width=535&amp;height=380');" rel="help" title="Klik disni untuk mendapatkan posisi pasti dari alamat anda." style="margin-left:5px; float:left;"><img src="<?php echo $settings['site_url']?>img/maps_ico_tiny.png" style="border:0px solid black;" width="30"/></a>
                    <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Jika anda menentukan letak posisi anda, maka profil anda akan muncul di peta pencarian."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                    
                    <?php echo $form->input("lat",array("type"=>"hidden","id"=>"UserLat","value"=>0))?>
                    <?php echo $form->input("lng",array("type"=>"hidden","id"=>"UserLng","value"=>0))?>
                 </div>
                 <div class="line1" id="span_lat_lng"></div>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> No Telp :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("phone",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain."))?>
                <span style="margin-left:5px;" id="img_phone"></span>
                <span class="error" id="err_phone"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Photo:</span>
            </div>
            <div class="left" style="border:0px solid black; width:55%">
            	<div class="left" style="width:120px; height:120px;">
                	<div class="left" style="border:1px solid #999999; width:120px;height:120px;">
                        <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPhoto" style="margin:50px auto auto 50px; display:none"/>
                        <img src="<?php echo $this->webroot?>img/user.png" id="UserPhoto"/>
                        
                    </div>
                    <div class="line1" style="margin-top:5px" id="name_file"></div>
                </div>
                <div class="left" style="width:190px; border:0px solid black; float:right">
                	<span id="file_browse">
						<?php echo $form->file("photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?>
                    </span>
                	<span style="margin-right:65px; float: right;" id="img_photo"></span>
                 	
                    <div class="line1" style="margin-left:10px; margin-top:10px; display:none;" id="cancelButton">
                    	<a href="javascript:void(0)" onclick="cancelUpload()" ><img src="<?php echo $this->webroot?>img/cancel_big.png" border="0"/></a>
                    </div>
                    <div class="line1" style="margin-top:20px;margin-left:10px;">
                    	<span class="text8">Accepted image format: .jpg .bmp .png. Size: <?php echo $number->toReadableSize($settings['max_photo_upload'])?></span>
                    </div>
                    
                </div>
                <div class="line1" style="margin-top:25px;">
                	<span class="error" id="err_photo"></span>
                </div>
                <div class="line1" style="margin-top:10px;border:0px solid black; display:none" id="agreement">
                    <div class="left" style="width:10px;border:0px solid black;">
                        <input name="data[User][agree]" id="agree_" value="" type="hidden">
                        <input name="data[User][agree]" value="1" id="agree" type="checkbox" style="margin-left:-2px;">
                    </div>
                    <div class="left" style="width:90%;border:0px solid black;margin-left:10px;">
                        <label class="text8" for="agree" style="width:100%;">
                        Saya menyatakan bahwa saya memiliki hak dan wewenang untuk mendistribusikan gambar ini dan bahwa hal itu tidak melanggar <a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/TermConditionsUpload?iframe=true&amp;width=450&amp;height=580')" class="text8">ketentuan dan sarat</a> yang berlaku.
                        </label>
                        <span style="margin-left:5px;" id="img_agree"></span>
                		<span class="error" id="err_agree"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="line1" style="border:0px solid black; margin-left:60px; margin-bottom:10px;">
            <span class="text6">Informasi Akses Member</span>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Email Address :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("email",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan alamat email anda di sini. Harap masukkan email dengan format standar : name@domain.com,<br>name.subname@domain.com"))?>
                <span style="margin-left:5px;" id="img_email"></span>
                <span class="error" id="err_email"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Password :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("password",array("class"=>"user","div"=>false,"label"=>false,"type"=>"password","maxlength"=>10))?>
                <span style="margin-left:5px;" id="img_password"></span>
                <span class="error" id="err_password" ></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Ulangi Password :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("retype_password",array("class"=>"user","div"=>false,"label"=>false,"type"=>"password","maxlength"=>10))?>
                <span style="margin-left:5px;" id="img_retype_password"></span>
                <span class="error" id="err_retype_password" ></span>
            </div>
        </div>
        
        <div class="line1" style="border:0px solid black; margin-left:60px; margin-bottom:10px;">
            <span class="text6">Tipe Member</span>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Tipe Member:</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		 <?php echo $form->input("usertype_id",array('options'=>array('1'=>"&nbsp;Perorangan",'2'=>"&nbsp;Dealer/Perusahaan/Distributor"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>"1","onclick"=>"ChooseTypeMember(this.value)") )?>
                 <span class="error" id="err_user_type_id"></span>
            </div>
            <div id="dealer" class="line1" style="display:none;">
            	<div class="line1" style="margin-top:10px;">
                    <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                        <span><strong>*</strong> Nama Dealer/Perusahaan/Distributor</span>
                    </div>
                    <div class="left" style="border:0px solid black; width:50%">
                        <?php echo $form->input("cname",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama Dealer/Perusahaan/Distributor  anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter."))?>
                        <span style="margin-left:5px;" id="img_cname"></span>
               			<span class="error" id="err_cname"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="line1" style="margin-top:20px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
               <span>Captcha code:</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
            	<?php echo $captchaTool->show(); ?>
               	<span class="error" id="err_captcha"></span>
            </div>
            <div class="left" style="border:0px solid black; width:5%;margin-left:10px;">
            	<span style="" id="img_captcha"></span>
            </div>
        </div>
        
        <div class="line1" style="margin:20px 0 50px 0;">
        	 <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                &nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:15%">
            	<a href="javascript:void(0)" onclick="SubmitRegister()"><img src="<?php echo $this->webroot?>img/save.gif" border="0"/></a>
                <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPict" style="float:right; display:none"/>
            </div>
        	
        </div>
    </div>
    <?php echo $form->end()?>
    <div class="line1">
    	<div style="border-top:1px dashed #999; width:90%; margin:auto;">&nbsp;</div>
    </div>
    <div class="line1" style="border:0px solid black; margin-left:60px; margin-bottom:10px;">
        <div class="left" style="width:30%">
        	<span class="text6">Or signup using:</span>
        </div>
        <div class="left" style="width:68%">
        	<a href="javascript:void(0)" style="margin-right:10px;" onclick="openFacebook()" title="Login via Facebook" id="LoginViaFacebook"><img src="<?php echo $this->webroot?>img/facebook_ico.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openTwitter()" title="Login via Twitter" id="LoginViaTwitter"><img src="<?php echo $this->webroot?>img/twitter_icon.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openYahoo()" title="Login via Yahoo" id="LoginViaYahoo"><img src="<?php echo $this->webroot?>img/yahoo_icon.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" title="Login via Google" id="LoginViaGoogle" onclick="openGoogle()"><img src="<?php echo $this->webroot?>img/google_icon.png" border="0"/></a>
        </div>
    </div>
</div>