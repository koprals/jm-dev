<!-- Google Website Optimizer Tracking Script -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['gwo._setAccount', 'UA-30078414-1']);
  _gaq.push(['gwo._trackPageview', '/2399778843/goal']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<!-- End of Google Website Optimizer Tracking Script -->

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
			  image: "<?php echo $this->webroot?>img/browse1.png",
			  imageheight : 30,
			  imagewidth : 80,
			  width : 0,
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
		$("#UserFullname").watermark({watermarkText:'ex: Her Robby Fajar',watermarkCssClass:'input3 style1 grey1 italic text12 size45 kiri'});
		$("#UserAddress").watermark({watermarkText:'ex: Jl Kedoya Timur No5',watermarkCssClass:'input3 style1 grey1 italic text12 size80 kiri textarea'});
		$("#UserPhone").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'input3 style1 grey1 italic text12 size45 kiri'});
		$("#UserEmail").watermark({watermarkText:'ex: abyfajar@gmail.com',watermarkCssClass:'input3 style1 grey1 italic text12 size45 kiri'});
		$("#UserCname").watermark({watermarkText:'ex: Panprisa Motor',watermarkCssClass:'input3 style1 grey1 italic text12 size45 kiri'});
		$("#UserAddress").jqEasyCounter({ 
			   'maxChars': <?php echo $settings['max_address_char']?>,
			   'maxCharsWarning': <?php echo $settings['max_address_char']-50?>,
			   'msgFontSize': '12px',
			   'msgFontColor': '#ffffff',
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
			$("#pilih_kota").html('<select name="data[User][city]" style="width: 160px;" class="input3 style1 black text12 size45 kiri" label="false" id="city"><option value="" selected="selected">Mohon tunggu ..</option></select>');
			$("#pilih_kota").load("<?php echo $settings['site_url']?>Template/SelectCity/",{'province_id':province_id});
		}
		else
		{
			$("#pilih_kota").html('<select name="data[User][city]" style="width: 160px;" class="input3 style1 black text12 size45 kiri" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>');
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
			  image: "<?php echo $this->webroot?>img/browse1.png",
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
							scrool	=	"#span_"+item.key;
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
					$("#UserPhoto").attr("src","<?php echo $settings['showimages_url']?>?code="+data.error+"&prefix=_120_120&content=RandomUser&w=120&h=120&time"+(new Date()).getTime());
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
<?php echo $form->create("User",array("type"=>"file"))?>
<div class="line">
    <div class="size40 tengah">
    	<div class="style1 text17 bold red2 top50">REGISTER</div>
        
        <div class="style1 text13 bold black2">Untuk dapat menjual motor atau berkomunikasi dengan penjual, Anda diharuskan menjadi member dengan melakukan registrasi.</div>
        
        <div class="text_title3 top10">
            <div class="line1">REGISTER. MUDAH & GRATIS KOK.</div>
        </div>
        <div class="line back3 size100 kiri position1 rounded2" style="padding-bottom:10px;">
            <div class="line1">
            	<div class="line top20 style1 bold white text17" style="border-bottom:1px solid white;">
                	Informasi Data Pribadi
                </div>
                
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_fullname"><span class="text15">*</span> Nama Lengkap :</div>
                    <?php echo $form->input("fullname",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter.","class"=>"input3 style1 black text12 size45 kiri"))?>
                    <span class="kiri left10" id="img_fullname"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_fullname"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_address"><span class="text15">*</span> Alamat :</div>
                    <?php echo $form->textarea("address",array("div"=>false,"label"=>false,"title"=>"Masukkan alamat lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter.","class"=>"input3 style1 black text12 size80 kiri textarea","style"=>"height:100px;"))?>
                    <span class="kiri left10" id="img_address"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_address"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="charleft"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_province"><span class="text15">*</span> Propinsi :</div>
                    <?php echo $form->select("province",$province,false,array("style"=>"width:160px","class"=>"input3 style1 black text12 size45 kiri","label"=>"false","escape"=>false,"empty"=>"Pilih Propinsi","onchange"=>"SelectCity(this.value)"));?>
                    
                    <span class="kiri left10" id="img_province"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_province"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_city"><span class="text15">*</span> Kota :</div>
                    <div id="pilih_kota"><?php echo $form->select("city",array(""=>"Pilih Kota"),false,array("style"=>"width:160px","class"=>"input3 style1 black text12 size45 kiri","label"=>"false","escape"=>false,"empty"=>false));?></div>
                    
                    <span class="kiri left10" id="img_city"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_city"></span>
                </div>
                <div class="line top10">
                    <div class="kiri style1 text14 bold white">Peta :</div>
                    <div class="kiri style1 text14 bold white left10">
                    	<a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/Map?iframe=true&amp;width=535&amp;height=380');" rel="help" title="Klik disni untuk mendapatkan posisi pasti dari alamat anda." style="margin-left:5px; float:left;"><img src="<?php echo $settings['site_url']?>img/maps_ico_tiny.png" style="border:0px solid black;" width="30"/></a>
                        <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Jika anda menentukan letak posisi anda, maka akan memudahkan penjual/pembeli menemukan alamat anda."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                        <?php echo $form->input("lat",array("type"=>"hidden","id"=>"UserLat","value"=>0))?>
                    	<?php echo $form->input("lng",array("type"=>"hidden","id"=>"UserLng","value"=>0))?>
                    </div>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_phone"><span class="text15">*</span> No Telp :</div>
                    <?php echo $form->input("phone",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>15,"title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain.","class"=>"input3 style1 black text12 size45 kiri"))?>
                    <span class="kiri left10" id="img_phone"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_phone"></span>
                </div>
               <div class="line top10">
                    <div class="style1 text14 bold white" id="span_photo">Foto:</div>
                    <div class="kiri" style="width:120px; height:120px;">
                    	<div class="kiri" style="border:1px solid #999999; width:120px;height:120px; background-color:#FFF;">
                        	<img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPhoto" style="margin:50px auto auto 50px; display:none"/>
                        	 <img src="<?php echo $this->webroot?>img/user.png" id="UserPhoto"/>
                        </div>
                        <div class="line top5 style1 white text11" style="margin-top:5px" id="name_file"></div>
                    </div>
                    <div class="kiri left5" style="width:190px; border:0px solid black;">
                    	<span id="file_browse">
							<?php echo $form->file("photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?>
                        </span>
                        <span style="margin-right:65px; float: right;" class="style1 white text11" id="img_photo"></span>
                        
                        <div class="line" style="margin-left:10px; margin-top:10px; display:none;" id="cancelButton">
                            <a href="javascript:void(0)" onclick="cancelUpload()" ><img src="<?php echo $this->webroot?>img/cancel_big2.png" border="0"/></a>
                        </div>
                        <div class="line" style="margin-top:20px;margin-left:10px;">
                            <span class="style1 white text11">Accepted image format: .jpg .bmp .png. Size: <?php echo $number->toReadableSize($settings['max_photo_upload'])?></span>
                        </div>
                        <div class="line style1 white text11 top10 left10" style="text-decoration:blink;" id="err_photo">
                        </div>
                    </div>
                </div>
                
                <div class="line top30 style1 bold white text17" style="border-bottom:1px solid white;">
                	Informasi Akses Member
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_email"><span class="text15">*</span>Email Address :</div>
                    <?php echo $form->input("email",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan alamat email anda di sini. Harap masukkan email dengan format standar : name@domain.com,<br>name.subname@domain.com","class"=>"input3 style1 black text12 size45 kiri"))?>
                    <span class="kiri left10" id="img_email"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_email"></span>
                </div>
                 <div class="line top10">
                    <div class="style1 text14 bold white" id="span_password"><span class="text15">*</span>Password :</div>
                    <?php echo $form->input("password",array("div"=>false,"label"=>false,"type"=>"password","class"=>"input3 style1 black text12 size45 kiri"))?>
                    <span class="kiri left10" id="img_password"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_password"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_retype_password"><span class="text15">*</span>Ulangi Password :</div>
                    <?php echo $form->input("retype_password",array("div"=>false,"label"=>false,"type"=>"password","class"=>"input3 style1 black text12 size45 kiri"))?>
                    <span class="kiri left10" id="img_retype_password"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_retype_password"></span>
                </div>
                <div class="line top30 style1 bold white text17" style="border-bottom:1px solid white;">
                	Tipe Member
                </div>
                <div class="line top10" id="span_cname">
                    <?php echo $form->input("usertype_id",array('options'=>array('1'=>"&nbsp;Perorangan",'2'=>"&nbsp;Dealer"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>"1","onclick"=>"ChooseTypeMember(this.value)") )?>
                </div>
                <div id="dealer" class="line" style="display:none;">
                    <div class="line">
                        <div class="style1 text14 bold white"><span class="text15">*</span>Nama Dealer :</div>
                        <?php echo $form->input("cname",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"class"=>"input3 style1 black text12 size45 kiri","title"=>"Masukkan nama Dealer anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter."))?>
                        <span class="kiri left10" id="img_cname"></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_cname"></span>
                    </div>
                </div>
                <div class="line top40" id="span_captcha">
                	<div class="kiri size60">
                    <?php echo $captchaTool->show(); ?>
                    </div>
                    <span class="kiri left15 size10" id="img_captcha"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_captcha"></span>
                </div>
                <div class="line top10" >
                    <div class="style1 text12 white">
                    	<div class="kiri size100" id="span_agree" style="border:0px solid black;">
                        	
                            <span class="kiri style1 white text12 bold" style="text-decoration:blink;" id="err_agree"></span>
                            <span class="kiri left10 top-5" id="img_agree"></span>
                        </div>
                    	<?php echo $form->input("agree",array('type'=>'checkbox','div'=>false,'label'=>false,"value"=>"1","escape"=>false,"id"=>"agree"))?>
                        Saya setuju dengan <a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/TermConditions?iframe=true&amp;width=400&amp;height=440');" class="red normal">perjanjian</a> <?php echo $settings['site_name']?>
                    </div>
                </div>
                <div class="line top10">
                    <input type="button" name="button" value="REGISTER" class="tombol1" onclick="SubmitRegister()" style="float:left"/>
                    <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPict" style="float:left; display:none; margin:5px 0 0 5px;"/>
                    
                </div>
            </div>            
        </div>
        
    </div>
</div>
<?php echo $form->end()?>

<div class="line">
	<div class="size40 tengah">
    	<div class="style1 text17 bold red2 top50">Atau Register Via:</div>
        <div class="style1 text13 bold black2">Pilih social media yang anda gunakan untuk login.</div>
    </div>
    <div class="size40 tengah">
    	<div class="line kiri top10">
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openFacebook()" title="Login via Facebook" id="LoginViaFacebook"><img src="<?php echo $this->webroot?>img/facebook_ico.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openTwitter()" title="Login via Twitter" id="LoginViaTwitter"><img src="<?php echo $this->webroot?>img/twitter_icon.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" onclick="openYahoo()" title="Login via Yahoo" id="LoginViaYahoo"><img src="<?php echo $this->webroot?>img/yahoo_icon.png" border="0"/></a>
            <a href="javascript:void(0)" style="margin-right:10px;" title="Login via Google" id="LoginViaGoogle" onclick="openGoogle()"><img src="<?php echo $this->webroot?>img/google_icon.png" border="0"/></a>
        </div>
    </div>
</div>