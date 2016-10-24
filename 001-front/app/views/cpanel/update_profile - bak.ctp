<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->

<script>
var count		= 	0;
var arr			=	 new Array();
var fade_in 	= 	500;
var fade_out	= 	500;
if($.browser.msie)
{
	fade_in 	= 100;
	fade_out	= 3500;
}
$(document).ready(function(){
						   
	var count	= 	parseInt($("#UserCountPhone").val());
	SelectCity('<?php echo $profile['Profile']['province_id']?>','<?php echo $profile['Profile']['city_id']?>');
	<?php foreach($ext_phone as $k=>$v):?>
	arr[<?php echo $k?>]	=	"<?php echo $v['ExtendedPhone']['phone']?>";
	<?php endforeach;?>
	
	$("#UserFullname").watermark({watermarkText:'ex: Her Robby Fajar',watermarkCssClass:'user_watermark'});
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
	
	
	
	$("#UserPhone").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'user_watermark'});
	$("#UserFax").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'user_watermark'});
	
	$("#UserAddress").watermark({watermarkText:'ex: Jl Kedoya Timur No5',watermarkCssClass:'address_watermark'});
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
});


function SubmitUpdate()
{
	$("#UserUpdateProfileForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Cpanel/ProcessUpdateProfile",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19.gif"/>Please wait ..');
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
					location.href	=	data.error;
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
						scrool	=	"#err_"+item.key;
					}
					
				});
				$(document).scrollTo(scrool, 800);
			}
		}
	});
	return false;
}
	

function SelectCity(province_id,city_id)
{
	if(province_id.length>0)
	{

		$("#pilih_kota").load("<?php echo $settings['site_url']?>Template/SelectCity/",{'province_id':province_id,'current':city_id});
	}
	else
	{
		$("#pilih_kota").html('<select name="data[User][city]" style="width: 160px;" class="text7" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>');	
	}
}

function DeletePhone(id)
{
	$("#"+id).remove();
	count--;
	$("#add_phone").show(300);
	$("#UserCountPhone").val(parseInt($("#UserCountPhone").val())-1);
	$("span[rel^=aby_]").each(function(i){
		$(this).html("No Telp-"+parseInt(i+1));
	});
	$("input[rel^=inputaby_]").each(function(i){
		if(typeof(arr[i]) != 'undefined' && arr[i] != null)
		{
			$(this).val(arr[i]);
		}
	});
}

function AddPhone()
{
	count	=	$("#phone .line1:last-child").attr("rel");
	if(typeof(count) == 'undefined' && count == null)
	{
		count	=	0;
	}

	count++;
	var add		=	"";
	add			+=	'<div class="line1" style="margin-bottom:12px;" id="phone_'+count+'" rel="'+count+'">';
    add			+=	'<div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">';
    add			+=	'<span rel="aby_'+(count+1)+'">No Telp-'+(count+1)+':</span>';
    add			+=	'</div>';
    add			+=	'<div class="left" style="border:0px solid black; width:50%">';
	add			+=	'<input name="data[ExtendedPhone]['+count+'][phone]" class="user" type="text" rel="inputaby_'+count+'"><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none" onclick="DeletePhone(\'phone_'+count+'\')">[ delete phone ]</a>';
    add			+=	'<span style="margin-left:5px;" id="img_phone'+count+'"></span>';
	add			+=	'<span class="error" id="err_phone'+count+'"></span>';
    add			+=	'</div>';
    add			+=	'</div>';
	
	$("#UserCountPhone").val(parseInt($("#UserCountPhone").val())+1);
	var lastCount	=	$("#UserCountPhone").val();
	
	if(lastCount<3)
	{
		$("#phone").append(add);
		if(lastCount==2)
		{
			$("#add_phone").hide(300);
		}
	}
	else
	{
		$("#add_phone").hide(300);
	}
	$("span[rel^=aby_]").each(function(i){
		$(this).html("No Telp-"+parseInt(i+1));
	});
	$("input[rel^=inputaby_]").each(function(i){
		if(typeof(arr[i]) != 'undefined' && arr[i] != null)
		{
			$(this).val(arr[i]);
		}
	});
}

function ChangeEmail()
{
	var str	=	'';
	str		+=	'<?php echo $form->input("User.email",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","value"=>$profile['User']['email'],"title"=>"Masukkan alamat email anda di sini. Harap masukkan email dengan format standar : name@domain.com,<br>name.subname@domain.com"))?><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="CancelChangeEmail()">[ cancel ]</a>';
	str		+=	'<span style="margin-left:5px;" id="img_email"></span>';
    str		+=	'<span class="error" id="err_email"></span>';
	$("#change_email").html(str);
	$('#UserEmail').bt({
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
}

function CancelChangeEmail()
{
	var str	=	'';
	str		+=	'<div class="font4" style="width:120px;border:0px solid black; float:left;"><?php echo $profile['User']['email']?></div><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="ChangeEmail()">[ change email ]</a><?php echo $form->input("User.email",array("type"=>"hidden","value"=>$profile['User']['email'],"readonly"=>true))?>';
	$("#change_email").html(str);
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
</script>
<div id="output"></div>

<div class="box_panel" style="min-height:50px; margin-bottom:10px;">
	<div class="line1" style=" margin-bottom:10px;">
        <div class="line4" style="border:0px solid black;">
            <span class="text3" id="updt_prfl">Update My Profile</span>
        </div>
    </div>
    <div class="line1" style="border:2px solid red; width:350px; margin-left:220px; margin-bottom:20px; padding-bottom:10px; display:none;"id="success">
    	<div class="left" style="width:40px; padding-top:0px; padding-left:10px;border:0px solid black;">
        	<img src="<?php echo $this->webroot?>img/check.png" style="margin-top:5px;">
        </div>
        <div class="left" style="width:270px;border:0px solid black">
        	<span class="text4" style="font-weight:bold">Success Updated</span>
            <div class="line1" style="border:0px solid black">
                <ul style="list-style:none; margin:10px 0 0 -40px; color:#F00; text-decoration:blink">
                    <li>Please wait, we will refresh your page ...</li>
                </ul>
            </div>
        </div>
    </div>
    <?php echo $form->create("User",array("onsubmit"=>"return SubmitUpdate()"))?>
    <?php echo $form->input("count_phone",array("type"=>"hidden","value"=>count($ext_phone)))?>
    <?php echo $form->input("id",array("type"=>"hidden","value"=>$profile['User']['id'],"readonly"=>true))?>
    <div class="line1">
    	<div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Nama Lengkap :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("fullname",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"value"=>$profile['Profile']['fullname'],"title"=>"Masukkan nama lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter."))?>
                <span style="margin-left:5px" id="img_fullname"></span>
                <span class="error" id="err_fullname"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Gender:</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		 <?php echo $form->input("gender",array('options'=>array('Pria'=>"&nbsp;Pria",'Wanita'=>"&nbsp;Wanita"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$profile['Profile']['gender']) )?>
                 <span class="error" id="err_user_type_id"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Alamat</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		 <?php echo $form->textarea("address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$profile['Profile']['address'],"title"=>"Masukkan alamat lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter."))?>
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
        		 <?php echo $form->select("province",$province,$profile['Profile']['province_id'],array("style"=>"width:160px","class"=>"text7","label"=>"false","escape"=>false,"empty"=>"Pilih Propinsi","onchange"=>"SelectCity(this.value,'".$profile['Profile']['city_id']."')"));?>
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
                 	<a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/MapProfile?iframe=true&amp;width=535&amp;height=380');" rel="help" title="Klik disni untuk mendapatkan posisi pasti dari alamat anda." style="margin-left:5px; float:left;"><img src="<?php echo $settings['site_url']?>img/maps_ico_tiny.png" style="border:0px solid black;" width="30"/></a>
                    <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Jika anda menentukan letak posisi anda, maka profil anda akan muncul di peta pencarian."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                    
                    <?php echo $form->input("lat",array("type"=>"hidden","id"=>"UserLat","value"=>$profile['Profile']['lat']))?>
                    <?php echo $form->input("lng",array("type"=>"hidden","id"=>"UserLng","value"=>$profile['Profile']['lng']))?>
                 </div>
                 <div class="line1" id="span_lat_lng"></div>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Alamat Email :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%" id="change_email">
            	<div class="font4" style="width:120px;border:0px solid black; float:left;"><?php echo $profile['User']['email']?></div><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="ChangeEmail()">[ change email ]</a>
                <?php echo $form->input("email",array("type"=>"hidden","value"=>$profile['User']['email'],"readonly"=>true))?>
            </div>
        </div>
        <div  class="line1" id="phone">
            <div class="line1" style="margin-bottom:12px;">
                <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                    <span><strong>*</strong> No Telp :</span>
                </div>
                <div class="left" style="border:0px solid black; width:50%">
                    <?php echo $form->input("phone",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","value"=>$profile['Profile']['phone'],"title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain."))?>
                    <?php if(count($ext_phone)<2):?>
                    	<a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none;display:block" onClick="AddPhone()" id="add_phone">[ add phone ]</a>
                    <?php else:?>
                    	<a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none;display:none" onClick="AddPhone()" id="add_phone">[ add phone ]</a>
                    <?php endif;?>
                    <span style="margin-left:5px;" id="img_phone"></span>
                    <span class="error" id="err_phone"></span>
                </div>
            </div>
            <?php $count=0;if(!empty($ext_phone)):?>
            <?php foreach($ext_phone as $ext_phone):?>
          	<div class="line1" style="margin-bottom:12px;" id="phone_<?php echo  $count?>" rel="<?php echo  $count?>">
            	<div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                	<span rel="aby_<?php echo  $count?>">No Telp-<?php echo  $count+1?></span>
                </div>
                <div class="left" style="border:0px solid black; width:50%">
                	<input name="data[ExtendedPhone][<?php echo  $count?>][phone]" class="user" type="text" value="<?php echo $ext_phone['ExtendedPhone']['phone']?>" rel="inputaby_<?php echo  $count?>"><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none" onclick="DeletePhone('phone_<?php echo  $count?>')">[ delete phone ]
                	</a>
                    <span style="margin-left:5px;" id="img_phone<?php echo  $count?>"></span>
                    <span class="error" id="err_phone<?php echo  $count?>"></span>
                </div>
            </div>
            <?php $count++?>
            <?php endforeach;?>
            <?php endif;?>
       	</div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Fax</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
            	<?php echo $form->input("fax",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>18,"value"=>$profile['Profile']['fax'],"title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain."))?>
                <span style="margin-left:5px;" id="img_fax"></span>
                <span class="error" id="err_fax"></span>
            </div>
        </div>
        
        <?php if($profile['User']['usertype_id']==1):?>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Tipe Member :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
            	<?php echo $form->input("usertype_id",array('options'=>array('1'=>"&nbsp;Perorangan",'2'=>"&nbsp;Dealer/Perusahaan/Distributor"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$profile['User']['usertype_id'],"onclick"=>"ChooseTypeMember(this.value)") )?>
                <span style="margin-left:5px;" id="img_usertype_id"></span>
                <span class="error" id="err_usertype_id"></span>
            </div>
        </div>
        <div id="dealer" class="line1" style="display:none;margin-bottom:12px;">
            <div class="line1" style="margin-top:10px;">
                <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                    <span><strong>*</strong> Nama Dealer/Perusahaan/Distributor</span>
                </div>
                <div class="left" style="border:0px solid black; width:50%">
                    <?php echo $form->input("cname",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>30))?>
                    <span style="margin-left:5px;" id="img_cname"></span>
                    <span class="error" id="err_cname"></span>
                </div>
            </div>
        </div>
        <?php endif;?>
        <div class="line1" style="margin-bottom:52px; margin-top:20px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
           		&nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:50%">
            	<input type="submit" name="button" id="button" value="Update" class="btn_sign" onClick="return SubmitUpdate()"/>
            	<span class="font4" style="color:#000000;" id="loading"></span>
            </div>
        </div>
  	</div>
    <?php echo $form->end()?>
</div>