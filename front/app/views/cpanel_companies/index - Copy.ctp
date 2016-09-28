<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->

<script>
var count	= 	0;
var arr			=	 new Array();
var fade_in 	= 	500;
var fade_out	= 	500;
if($.browser.msie)
{
	fade_in 	= 100;
	fade_out	= 3500;
}
$(document).ready(function(){				   
	var count	= 	parseInt($("#CompanyCountPhone").val());
	SelectCity('<?php echo $profile['Company']['province_id']?>','<?php echo $profile['Company']['city_id']?>');
	<?php foreach($ext_phone as $k=>$v):?>
	arr[<?php echo $k?>]	=	"<?php echo $v['ExtendedPhone']['phone']?>";
	<?php endforeach;?>
	
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
	$("#CompanyName").watermark({watermarkText:'ex: Amie Jaya Motor',watermarkCssClass:'user_watermark'});
	$("#CompanyPhone").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'user_watermark'});
	$("#CompanyFax").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'user_watermark'});
	$("#CompanyAddress").watermark({watermarkText:'ex: Jl Kedoya Timur No5',watermarkCssClass:'address_watermark'});
	$("#CompanyWebsite").watermark({watermarkText:'http://',watermarkCssClass:'user_watermark'});
	$("#CompanyDescription").watermark({watermarkText:'ex: Dealer resmi motor Yamaha',watermarkCssClass:'address_watermark'});
	$("#CompanyAddress").jqEasyCounter({ 
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
	
	$("#CompanyDescription").jqEasyCounter({ 
		   'maxChars': <?php echo $settings['max_address_char']?>,
		   'maxCharsWarning': <?php echo $settings['max_address_char']-50?>,
		   'msgFontSize': '12px',
		   'msgFontColor': '#000',
		   'msgFontFamily': 'Arial',
		   'msgTextAlign': 'left',
		   'msgWarningColor': '#F00',
		   'msgAppendMethod': 'insertBefore',
		   'msgAppendSelector':'#charleft_description'     	  
	});
});


function SubmitUpdate()
{
	$("#CompanyIndexForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>CpanelCompanies/ProcessUpdateProfile",
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
			$("span[id^=err]").html('')
			$("span[id^=img]").html('')
			
			if(data.status==true)
			{
				$(document).scrollTo("#updt_cmpn", 800);
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

		$("#pilih_kota").load("<?php echo $settings['site_url']?>Template/SelectCity/",{'province_id':province_id,'current':city_id,'model':'Company'});
	}
	else
	{
		$("#pilih_kota").html('<select name="data[Company][city]" style="width: 160px;" class="text7" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>');	
	}
}

function DeletePhone(id)
{
	$("#"+id).remove();
	count--;
	$("#add_phone").show(300);
	$("#CompanyCountPhone").val(parseInt($("#CompanyCountPhone").val())-1);
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
	
	$("#CompanyCountPhone").val(parseInt($("#CompanyCountPhone").val())+1);
	var lastCount	=	$("#CompanyCountPhone").val();
	
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
	str		+=	'<?php echo $form->input("Company.email",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","value"=>$profile['Company']['email']))?><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="CancelChangeEmail()">[ cancel ]</a>';
	str		+=	'<span style="margin-left:5px;" id="img_email"></span>';
    str		+=	'<span class="error" id="err_email"></span>';
	$("#change_email").html(str);
}

function CancelChangeEmail()
{
	var str	=	'';
	str		+=	'<div class="font4" style="width:120px;border:0px solid black; float:left;"><?php echo $profile['Company']['email']?></div><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="ChangeEmail()">[ change email ]</a><?php echo $form->input("Company.email",array("type"=>"hidden","value"=>$profile['Company']['email'],"readonly"=>true))?>';
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
            <span class="text3" id="updt_cmpn">Edit Company Profile</span>
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
    <?php echo $form->create("Company",array("onsubmit"=>"return SubmitUpdate()"))?>
    <?php echo $form->input("count_phone",array("type"=>"hidden","value"=>count($ext_phone)))?>
    <?php echo $form->input("id",array("type"=>"hidden","value"=>$profile['Company']['id'],"readonly"=>true))?>
    <div class="line1">
    	<div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong>Nama Dealer/Toko/Perusahaan :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		<?php echo $form->input("name",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"value"=>$profile['Company']['name'],"title"=>"Masukkan nama Dealer/Perusahaan/Distributor  anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter."))?>
                <span style="margin-left:5px" id="img_fullname"></span>
                <span class="error" id="err_fullname"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong>*</strong> Alamat</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		 <?php echo $form->textarea("address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$profile['Company']['address'],"title"=>"Masukkan alamat lengkap Dealer/Perusahaan/Distributor di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter."))?>
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
        		 <?php echo $form->select("province",$province,$profile['Company']['province_id'],array("style"=>"width:160px","class"=>"text7","label"=>"false","escape"=>false,"empty"=>"Pilih Propinsi","onchange"=>"SelectCity(this.value,'".$profile['Company']['city_id']."')"));?>
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
            	<?php echo $form->input("fax",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>18,"value"=>$profile['Company']['fax'],"title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain."))?>
                <span style="margin-left:5px;" id="img_fax"></span>
                <span class="error" id="err_fax"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Website</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
            	<?php echo $form->input("website",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","value"=>$profile['Company']['website'],"title"=>"Harap masukkan dalam format yang benar: http://www.domain.com ."))?>
                <span style="margin-left:5px;" id="img_website"></span>
                <span class="error" id="err_website"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Description</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%">
        		 <?php echo $form->textarea("description",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$profile['Company']['description'],"title"=>"Masukkan deskripsi singkat mengenai Toko anda."))?>
                 <span style="margin-left:5px;" id="img_description"></span>
                 <span class="error" id="err_description" ></span>
                 <span class="text8" id="charleft_description" style="float:left; width:100%"></span>
            </div>
        </div>
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