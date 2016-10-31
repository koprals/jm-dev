<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.bt")?>



<?php echo $html->css("jquery.sceditor.min")?>
<?php echo $javascript->link("jquery.sceditor")?>
<?php echo $javascript->link("jquery.sceditor.bbcode")?>

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
var test;
$(document).ready(function(){				   
	var count	= 	parseInt($("#CompanyCountPhone").val());
	SelectCity('<?php echo $profile['Company']['province_id']?>','<?php echo $profile['Company']['city_id']?>');
	<?php foreach($ext_phone as $k=>$v):?>
	arr[<?php echo $k?>]	=	"<?php echo $v['ExtendedPhone']['phone']?>";
	<?php endforeach;?>
	
	$('input').each(function(){
		$(this).bt({
		  width: 230,
		  trigger: ['focus', 'blur'],
		  positions: ['right'],
		  cornerRadius: 7,
		  strokeStyle: '#FFFFFF',
		  fill: 'rgba(136, 136, 136, 1)',
		  cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px'},
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
	$("#CompanyName").watermark({watermarkText:'ex: Amie Jaya Motor',watermarkCssClass:'input3 style1 grey1 italic text12 size85 kiri'});
	$("#CompanyPhone").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'input3 style1 grey1 italic text12 size85 kiri'});
	$("#CompanyFax").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'input3 style1 grey1 italic text12 size85 kiri'});
	$("#CompanyAddress").watermark({watermarkText:'ex: Jl Kedoya Timur No5',watermarkCssClass:'input3 style1 grey1 italic text12 size85 kiri textarea'});
	$("#CompanyWebsite").watermark({watermarkText:'http://',watermarkCssClass:'user_watermark'});
	//$("#CompanyDescription").watermark({watermarkText:'ex: Dealer resmi motor Yamaha',watermarkCssClass:'input3 style1 grey1 italic text12 size85 kiri'});
	$("#CompanyAddress").jqEasyCounter({ 
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
	
	test = $("#CompanyDescription").sceditorBBCodePlugin({
		toolbar:	"bold,italic,underline,strike,subscript,superscript|left,center,right,justify|" +
				"font,size,color,removeformat|cut,copy,paste,pastetext|bulletlist,orderedlist|" +
				"undo,redo|link,unlink|emoticon|",
		emoticons:	{
		  dropdown: {
			  ":)": "<?php echo $settings['site_url']?>img/emoticons/smile.png",
			  ":angel:": "<?php echo $settings['site_url']?>img/emoticons/angel.png",
			  ":angry:": "<?php echo $settings['site_url']?>img/emoticons/angry.png",
			  "8-)": "<?php echo $settings['site_url']?>img/emoticons/cool.png",
			  ":'(": "<?php echo $settings['site_url']?>img/emoticons/cwy.png",
			  ":ermm:": "<?php echo $settings['site_url']?>img/emoticons/ermm.png",
			  ":D": "<?php echo $settings['site_url']?>img/emoticons/grin.png",
			  "<3": "<?php echo $settings['site_url']?>img/emoticons/heart.png",
			  ":(": "<?php echo $settings['site_url']?>img/emoticons/sad.png",
			  ":O": "<?php echo $settings['site_url']?>img/emoticons/shocked.png",
			  ":P": "<?php echo $settings['site_url']?>img/emoticons/tongue.png",
			  ";)": "<?php echo $settings['site_url']?>img/emoticons/wink.png"
		  },
		  more: {
			  ":alien:": "<?php echo $settings['site_url']?>img/emoticons/alien.png",
			  ":blink:": "<?php echo $settings['site_url']?>img/emoticons/blink.png",
			  ":blush:": "<?php echo $settings['site_url']?>img/emoticons/blush.png",
			  ":cheerful:": "<?php echo $settings['site_url']?>img/emoticons/cheerful.png",
			  ":devil:": "<?php echo $settings['site_url']?>img/emoticons/devil.png",
			  ":dizzy:": "<?php echo $settings['site_url']?>img/emoticons/dizzy.png",
			  ":getlost:": "<?php echo $settings['site_url']?>img/emoticons/getlost.png",
			  ":happy:": "<?php echo $settings['site_url']?>img/emoticons/happy.png",
			  ":kissing:": "<?php echo $settings['site_url']?>img/emoticons/kissing.png",
			  ":ninja:": "<?php echo $settings['site_url']?>img/emoticons/ninja.png",
			  ":pinch:": "<?php echo $settings['site_url']?>img/emoticons/pinch.png",
			  ":pouty:": "<?php echo $settings['site_url']?>img/emoticons/pouty.png",
			  ":sick:": "<?php echo $settings['site_url']?>img/emoticons/sick.png",
			  ":sideways:": "<?php echo $settings['site_url']?>img/emoticons/sideways.png",
			  ":silly:": "<?php echo $settings['site_url']?>img/emoticons/silly.png",
			  ":sleeping:": "<?php echo $settings['site_url']?>img/emoticons/sleeping.png",
			  ":unsure:": "<?php echo $settings['site_url']?>img/emoticons/unsure.png",
			  ":woot:": "<?php echo $settings['site_url']?>img/emoticons/w00t.png",
			  ":wassat:": "<?php echo $settings['site_url']?>img/emoticons/wassat.png"
		  },
		  hidden: {
			  ":whistling:": "<?php echo $settings['site_url']?>img/emoticons/whistling.png",
			  ":love:": "<?php echo $settings['site_url']?>img/emoticons/wub.png"
		  }
	  }
	});
});


function SubmitUpdate()
{
	$.sceditorBBCodePlugin.documentClickHandler;
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
						scrool	=	"#span_"+item.key;
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

		$("#pilih_kota").load("<?php echo $settings['site_url']?>Template/SelectCity/",{'province_id':province_id,'current':city_id,'model':'Company',"style":"width:85%"});
	}
	else
	{
		$("#pilih_kota").html('<select id="city" label="false" class="input3 style1 black text12 size45 kiri" style="width: 85%;" name="data[Company][city]"><option value="">Pilih Kota</option></select>');
		
	}
}

function DeletePhone(id)
{
	$("#"+id).remove();
	count--;
	$("#add_phone").show(300);
	$("#UserCountPhone").val(parseInt($("#CompanyCountPhone").val())-1);
	$("div[rel^=aby_]").each(function(i){
		$(this).html("No Telp-"+parseInt(i+1));
	});
	$("input[rel^=inputaby_]").each(function(i){
		if(typeof(arr[i]) != 'undefined' && arr[i] != null)
		{
			//$(this).val(arr[i]);
		}
	});
}

function AddPhone()
{
	count	=	$("#phone div[id^=phone]:last-child").attr("rel");
	
	if(typeof(count) == 'undefined' && count == null)
	{
		count	=	-1;
	}
	count++;
	var p		=	parseInt(count+1);
	var add		=	"";
	add			+=	'<div class="line top10" id="phone_'+count+'" rel="'+count+'">';
	add			+=	'<div class="style1 text14 bold white" rel="aby_'+count+'">No Telp-'+p+'</div>';
	add			+=	'<input name="data[ExtendedPhone]['+count+'][phone]" class="input3 style1 black text12 size70 kiri" type="text" rel="inputaby_'+count+'">';
	add			+=	'<a class="style1 white text12 bold normal left5" href="javascript:void(0)"  onclick="DeletePhone(\'phone_'+count+'\')">[ delete ]</a>';
	add			+=	'<span class="kiri left10" id="img_phone'+count+'"></span>';
	add			+=	'<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_phone'+count+'"></span>';
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
	
	$("div[rel^=aby_]").each(function(i){
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
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Dealer Profile</div>
        </div>
        <div class="line back3 size100 kiri position1 rounded2" style="padding-bottom:10px;" id="updt_cmpn">
        	<div class="size70 tengah" id="success"  style="display:none">
                <div class="kiri size100 style1 white top10 reounded_error">
                    <div class="kiri size100 left10 top10 bold text14 blink">Success Updated</div>
                    <div class="kiri size100 left10 top10 bold text12 bottom10">Please wait, we will refresh your page ...</div>
                </div>
            </div>
            <?php echo $form->create("Company",array("onsubmit"=>"return SubmitUpdate()"))?>
			<?php echo $form->input("count_phone",array("type"=>"hidden","value"=>count($ext_phone)))?>
            <?php echo $form->input("id",array("type"=>"hidden","value"=>$profile['Company']['id'],"readonly"=>true))?>
            <div class="tengah size45" style="border:0px solid black;">
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_name"><span class="text15">*</span> Nama Dealer :</div>
                    <?php echo $form->input("name",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"value"=>$profile['Company']['name'],"title"=>"Masukkan nama Dealer anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter.","class"=>"input3 style1 black text12 size85 kiri"))?>
                    <span class="kiri left10" id="img_name"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_name"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_address"><span class="text15">*</span> Alamat :</div>
                    <?php echo $form->textarea("address",array("div"=>false,"label"=>false,"value"=>$profile['Company']['address'],"title"=>"Masukkan alamat lengkap Dealer  di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter.","class"=>"input3 style1 black text12 size85 kiri textarea","style"=>"height:170px;width:500px;"))?>
                    <span class="kiri left10" id="img_address"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_address"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="charleft"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_province"><span class="text15">*</span> Propinsi :</div>
                    <?php echo $form->select("province",$province,$profile['Company']['province_id'],array("class"=>"input3 style1 black text12 size85 kiri","label"=>"false","escape"=>false,"empty"=>"Pilih Propinsi","onchange"=>"SelectCity(this.value,'".$profile['Company']['city_id']."')"));?>
                    <span class="kiri left10" id="img_province"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_province"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_city"><span class="text15">*</span> Kota :</div>
                    <div id="pilih_kota"><?php echo $form->select("city",array(""=>"Pilih Kota"),false,array("class"=>"input3 style1 black text12 size85 kiri","label"=>"false","escape"=>false,"empty"=>false));?></div>
                    
                    <span class="kiri left10" id="img_city"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_city"></span>
                </div>
                <div class="line top10">
                    <div class="kiri style1 text14 bold white">Peta :</div>
                    <div class="kiri style1 text14 bold white left10">
                    	<a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/MapDealer?iframe=true&amp;width=535&amp;height=380');" rel="help" title="Klik disni untuk mendapatkan posisi pasti dari alamat anda." style="margin-left:5px; float:left;"><img src="<?php echo $settings['site_url']?>img/maps_ico_tiny.png" style="border:0px solid black;" width="30"/></a>
                        <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Jika anda menentukan letak posisi anda, maka akan memudahkan penjual/pembeli menemukan alamat anda."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                        <?php echo $form->input("lat",array("type"=>"hidden","id"=>"CompanyLat","value"=>$profile['Company']['lat']))?>
                    	<?php echo $form->input("lng",array("type"=>"hidden","id"=>"CompanyLng","value"=>$profile['Company']['lng']))?>
                    </div>
                </div>
                <div class="line top10" id="phone">
                    <div class="line">
                        <div class="style1 text14 bold white" id="span_phone"><span class="text15">*</span> No Telp :</div>
                        <?php echo $form->input("phone",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain.","value"=>$profile['Company']['phone'],"class"=>"input3 style1 black text12 size70 kiri"))?>
                        <?php if(count($ext_phone)<2):?>
                            <a class="kiri style1 white text12 bold normal left5" href="javascript:void(0)"  onClick="AddPhone()" id="add_phone">[ add ]</a>
                        <?php else:?>
                            <a class="kiri style1 white text12 bold normal left5" href="javascript:void(0)"  onClick="AddPhone()" id="add_phone" style="display:none;">[ add ]</a>
                        <?php endif;?>
                        <span class="kiri left10" id="img_phone"></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_phone"></span>
                    </div>
                    <?php $count=0;if(!empty($ext_phone)):?>
                    <?php foreach($ext_phone as $ext_phone):?>
                    <div class="line top10" id="phone_<?php echo  $count?>" rel="<?php echo  $count?>">
                        <div class="style1 text14 bold white" rel="aby_<?php echo  $count?>" id="span_phone<?php echo  $count?>">No Telp-<?php echo  $count+1?></div>
                        <input name="data[ExtendedPhone][<?php echo  $count?>][phone]" class="input3 style1 black text12 size70 kiri" type="text" value="<?php echo $ext_phone['ExtendedPhone']['phone']?>" rel="inputaby_<?php echo  $count?>">
                        <a class="kiri style1 white text12 bold normal left5" href="javascript:void(0)"  onClick="DeletePhone('phone_<?php echo  $count?>')">[ delete ]</a>
                        <span class="kiri left10" id="img_phone<?php echo  $count?>"></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_phone<?php echo  $count?>"></span>
                    </div>
                    <?php $count++?>
                    <?php endforeach;?>
                    <?php endif;?>
            	</div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_fax">Fax</div>
                    <?php echo $form->input("fax",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>18,"value"=>$profile['Company']['fax'],"title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain.","class"=>"input3 style1 black text12 size85 kiri"))?>
                    <span class="kiri left10" id="img_fax"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_fax"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_website">Website</div>
                    <?php echo $form->input("website",array("div"=>false,"label"=>false,"type"=>"text","value"=>$profile['Company']['website'],"title"=>"Harap masukkan dalam format yang benar: http://www.domain.com .","class"=>"input3 style1 black text12 size85 kiri"))?>
                    <span class="kiri left10" id="img_website"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_website"></span>
                </div>
                <div class="line top10">
                    <div class="style1 text14 bold white" id="span_description"><span class="text15">*</span> Deskripsi :</div>
                    <?php echo $form->textarea("description",array("div"=>false,"label"=>false,"value"=>$profile['Company']['description'],"title"=>"Masukkan deskripsi singkat mengenai dealer anda.","class"=>"input3 style1 black text12 size85 kiri textarea","style"=>"height:300px;width:503px;"))?>
                    <span class="kiri left10" id="img_description"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_description"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="charleft_description"></span>
                </div>
                <div class="line top20">
                    <input type="submit" id="buttonsubmit" name="button" value="SUBMIT" class="tombol1" style="float:left"/>
                    <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                </div>
            </div>
            <?php echo $form->end()?>
        </div>
	</div>
</div>
