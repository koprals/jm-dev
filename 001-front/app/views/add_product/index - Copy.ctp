<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.filestyle")?>

<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->

<script>
var fade_in 	= 	500;
var fade_out	= 	500;
var option		=	"";
var option2		=	"";
var tai			=	0;
if($.browser.msie)
{
	fade_in 	= 100;
	fade_out	= 3500;
}

function SelectCity(province_id,city_id)
{
	if(province_id.length>0)
	{

		$("#city_name").load("<?php echo $settings['site_url']?>Template/SelectCity/",{'province_id':province_id,'current':city_id,"model":'Product'});
	}
	else
	{
		$("#city_name").html('<select name="data[Product][city]" style="width: 160px;" class="text7" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>');	
	}
}


<?php if(!empty($profile['Profile']['province_id'])):?>
	$.getJSON("<?PHP echo $settings['site_url']?>Template/ProvinceName/<?php echo $profile['Profile']['province_id']?>",function(data){
		$("#province_name").html(data+'<?php echo $form->input("Product.province_id",array("type"=>"hidden","value"=>$profile['Profile']['province_id']))?>');
		
	});
	
	$.getJSON("<?PHP echo $settings['site_url']?>Template/CityName/<?php echo $profile['Profile']['city_id']?>",function(data){
		$("#city_name").html(data+'<?php echo $form->input("Product.city",array("type"=>"hidden","value"=>$profile['Profile']['city_id']))?>');
	});
<?php else:?>
	option	=	'<select name="data[Product][province_id]" style="width: 160px;" class="text7" label="false" onchange="SelectCity(this.value,0)" id="ProductProvince"><option value="">Pilih Propinsi</option>';
	$.getJSON("<?PHP echo $settings['site_url']?>Template/ProvinceList",function(data){
		$.each(data,function(i,item){
			option	+=	'<option value="'+i+'">'+item+'</option>';
			tai++
		});
		option	+=	'</select>';
		$("#province_name").html(option);
	});
	
	option2	=	'<select name="data[Product][city]" style="width: 160px;" class="text7" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>';
	
	
<?php endif;?>

<?php if(!empty($profile['Profile']['address'])):?>
	var alamat	=	'<?php echo $profile['Profile']['address'].$form->input("Product.address",array("type"=>"hidden","value"=>$profile['Profile']['address']))?>';
<?php else:?>
	var alamat	=	'<?php echo $form->textarea("Product.address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$profile['Profile']['address'],"title"=>"Masukkan alamat lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter."))?>';
<?php endif;?>


function FacebookShare()
{
	$("#facebook_share").toggleClass("facebook_share_off");
	if($("#ProductFacebookShare").val()==1)
	{
		$("#ProductFacebookShare").val(0);
	}
	else
	{
		$("#ProductFacebookShare").val(1);
		$.getJSON("<?php echo $settings['site_url']?>Users/CheckExtId/facebook",function(data){
			if(data.status == false)
			{
				openFacebook2();
			}
		});
	}
}

function TwitterShare()
{
	$("#twitter_share").toggleClass("twitter_share_off");
	if($("#ProductTwitterShare").val()==1)
	{
		$("#ProductTwitterShare").val(0);
	}
	else
	{
		$("#ProductTwitterShare").val(1);
		$.getJSON("<?php echo $settings['site_url']?>Users/CheckExtId/twitter",function(data){
			if(data.status == false)
			{
				openTwitter2();
			}
		});
	}
}

$(document).ready(function(){
	$("#alamat").html(alamat);
	Tooltips("#tooltips_address");
	$("#city_name").html(option2);
	
	<?php if(!empty($profile['Company']['address'])):?>
	$('<a class="text8" href="javascript:void(0)" style="margin-left:10px;margin-right:10px; border:0px solid black; float:left; text-decoration:none" rel="help" id="tooltips_address" title="Alamat yang tertera sesuai dengan nama penjual yang anda pilih, jika anda menggunakan nama dealer sebagai nama penjual maka alamat yang tertera adalah alamat dealer begitupun sebaliknya."><img src="<?php echo $this->webroot?>img/help.png" border="0"></a>').insertAfter("#alamat");
	<?php endif;?>
	
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
	
	$("input.browse2").filestyle({ 
		  image: "<?php echo $this->webroot?>img/browse2.png",
		  imageheight : 30,
		  imagewidth : 112,
		  width : 0,
		  height : 30		  
	});

	$("#ProductAddress").jqEasyCounter({ 
		   'maxChars': <?php echo $settings['max_address_char']?>,
		   'maxCharsWarning': <?php echo $settings['max_address_char']-50?>,
		   'msgFontSize': '12px',
		   'msgFontColor': '#000',
		   'msgFontFamily': 'Arial',
		   'msgTextAlign': 'left',
		   'msgWarningColor': '#F00',
		   'msgAppendMethod': 'insertBefore',
		   'msgAppendSelector':'#charleft_address'     	  
	});
	$("#ProductDescription").jqEasyCounter({ 
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
	
	$("#ProductYm").watermark({watermarkText:'co: aby_labdb@yahoo.com',watermarkCssClass:'user_watermark'});
	$("#ProductNopol").watermark({watermarkText:'co: B 6929 UMB',watermarkCssClass:'user_watermark'});
	$("#ProductNewcategory").watermark({watermarkText:'co: Suzuki',watermarkCssClass:'user_watermark'});
	$("#ProductNewsubcategory").watermark({watermarkText:'co: Suzuki Satria',watermarkCssClass:'user_watermark'});
	$("#ProductThnPembuatan").watermark({watermarkText:'co: 2005',watermarkCssClass:'user_watermark'});
	$("#ProductKilometer").watermark({watermarkText:'co: 90',watermarkCssClass:'user_watermark'});
	$("#ProductDescription").watermark({watermarkText:'co: Masih mulus, baru dipakai satu bulan,tidak ada lecet...',watermarkCssClass:'address_watermark'});
	$("#ProductAddress").watermark({watermarkText:'co: Jl mawar01 No 34',watermarkCssClass:'address_watermark'});
	$("#ProductPrice").watermark({watermarkText:'co: 10000000',watermarkCssClass:'user_watermark'});
	<?php if(empty($profile['Profile']['phone'])):?>
	$("#ProductPhone").watermark({watermarkText:'co: 86377177',watermarkCssClass:'user_watermark'});
	<?php endif;?>
});

function Tooltips(element)
{
	$(element).bt({
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

function ChangeName()
{
	var html	=	$("#change_name").html();
	
	if(html=='[ gunakan nama dealer ]')
	{
		$("#change_name").html('[ gunakan nama akun ]');
		$("#ProductContactName").val('2');
		$("#span_name").html('<?php echo $profile['Company']['name']?>');
		
		
		<?php if(!empty($profile['Company']['address'])):?>
			$("#alamat").html('<?php echo $profile['Company']['address'].$form->input("Product.address",array("type"=>"hidden","value"=>$profile['Company']['address']))?>');
		<?php else:?>
			$("#alamat").html('<?php echo $form->textarea("Product.address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","title"=>"Masukkan alamat lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter."))?>');
			Tooltips('#ProductAddress');
			
		<?php endif;?>
		
		<?php if(!empty($profile['Company']['province_id'])):?>
			$.getJSON("<?PHP echo $settings['site_url']?>Template/ProvinceName/<?php echo $profile['Company']['province_id']?>",function(data){
				$("#province_name").html(data+'<?php echo $form->input("Product.province_id",array("type"=>"hidden","value"=>$profile['Company']['province_id']))?>');
			});
			$.getJSON("<?PHP echo $settings['site_url']?>Template/CityName/<?php echo $profile['Company']['city_id']?>",function(data){
				$("#city_name").html(data+'<?php echo $form->input("Product.city",array("type"=>"hidden","value"=>$profile['Company']['city_id']))?>');
			});
		<?php else:?>
			option	=	'<select name="data[Product][province_id]" style="width: 160px;" class="text7" label="false" onchange="SelectCity(this.value,0)" id="ProductProvince"><option value="">Pilih Propinsi</option>';
			$.getJSON("<?PHP echo $settings['site_url']?>Template/ProvinceList",function(data){
				$.each(data,function(i,item){
					option	+=	'<option value="'+i+'">'+item+'</option>';
					tai++
				});
				option	+=	'</select>';
				$("#province_name").html(option);
			});
			option2	=	'<select name="data[Product][city]" style="width: 160px;" class="text7" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>';
			$("#city_name").html(option2);
	
		<?php endif;?>
	}
	else
	{
		$("#change_name").html('[ gunakan nama dealer ]');
		$("#ProductContactName").val('1');
		$("#span_name").html('<?php echo $profile['Profile']['fullname']?>');
		<?php if(!empty($profile['Profile']['address'])):?>
			$("#alamat").html('<?php echo $profile['Profile']['address'].$form->input("Product.address",array("type"=>"hidden","value"=>$profile['Profile']['address']))?>');
		<?php else:?>
			$("#alamat").html('<?php echo $form->textarea("Product.address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$profile['Profile']['address'],"title"=>"Masukkan alamat lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter."))?>');
		<?php endif;?>
		
		<?php if(!empty($profile['Profile']['province_id'])):?>
			$.getJSON("<?PHP echo $settings['site_url']?>Template/ProvinceName/<?php echo $profile['Profile']['province_id']?>",function(data){
				$("#province_name").html(data+'<?php echo $form->input("Product.province_id",array("type"=>"hidden","value"=>$profile['Profile']['province_id']))?>');
			});
			$.getJSON("<?PHP echo $settings['site_url']?>Template/CityName/<?php echo $profile['Profile']['city_id']?>",function(data){
				$("#city_name").html(data+'<?php echo $form->input("Product.city",array("type"=>"hidden","value"=>$profile['Profile']['city_id']))?>');
			});
		<?php else:?>
			option	=	'<select name="data[Product][province_id]" style="width: 160px;" class="text7" label="false" onchange="SelectCity(this.value,0)" id="ProductProvince"><option value="">Pilih Propinsi</option>';
			$.getJSON("<?PHP echo $settings['site_url']?>Template/ProvinceList",function(data){
				$.each(data,function(i,item){
					option	+=	'<option value="'+i+'">'+item+'</option>';
					tai++
				});
				option	+=	'</select>';
				$("#province_name").html(option);
			});
			option2	=	'<select name="data[Product][city]" style="width: 160px;" class="text7" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>';
			$("#city_name").html(option2);
		<?php endif;?>
	}
	$("#ProductAddress").watermark({watermarkText:'co: Jl mawar01 No 34',watermarkCssClass:'address_watermark'});
	
	
	$("#ProductAddress").jqEasyCounter({ 
		   'maxChars': <?php echo $settings['max_address_char']?>,
		   'maxCharsWarning': <?php echo $settings['max_address_char']-50?>,
		   'msgFontSize': '12px',
		   'msgFontColor': '#000',
		   'msgFontFamily': 'Arial',
		   'msgTextAlign': 'left',
		   'msgWarningColor': '#F00',
		   'msgAppendMethod': 'insertBefore',
		   'msgAppendSelector':'#charleft_address'     	  
	});
}


function SubCat(parent_id)
{
	$.getJSON("<?php echo $settings['site_url']?>AddProduct/GetSubcategoryJson",
	{
		"parent_id":parent_id
	},function(data)
	{
		var option	=	'<option value="">Pilih tipe motor</option>';
		
		if(parent_id == "new")
		{
			$("#prod_request").val(1);
			
			$("#category_request").val(0);
			$("#category_name").val(1);
			
			$("#subcategory_request").val(0);
			$("#subcategory_name").val(1);
			
			$("#category").hide();
			$("#subcategory").hide();
			
			$("#new_sub_category").show();
			$("#new_category").show();
			
			$("#cancel_sub_category").hide();
		}
		else if(parent_id == 0)
		{
			$("#prod_request").val(0);
			
			$("#category_request").val(1);
			$("#category_name").val(0);
			
			$("#subcategory_request").val(1);
			$("#subcategory_name").val(0);

			$("#new_sub_category").hide();
			option	=	'<option value="0">Pilih tipe motor</option>';
		}
		else
		{
			$("#category_request").val(1);
			$("#category_name").val(0);
			
			$("#subcategory_request").val(1);
			$("#subcategory_name").val(0);
			
			option		+=	'<option value="new" style=" font-weight:bold">Tipe lainnya</option>';
			for(var i=0;i<data.length;i++)
			{
				option	+=	"<option value='"+data[i].Category.id+"'>"+data[i].Category.name+"</option>";
			}
		}
		
		if(data.length>0)
		{
			$("#subcategory").show();
			$("#subcat_id").html(option);
			
			$("#prod_request").val(0);
			$("#new_sub_category").hide();

			$("#subcategory_request").val(1);
			$("#subcategory_name").val(0);
			
		}
		else if(parent_id != 0)
		{
			$("#subcategory").hide();
	
			$("#new_sub_category").show();
			$("#prod_request").val(1);
		}
		else if(parent_id == 0)
		{
			$("#prod_request").val(1);
			$("#subcat_id").html(option);
			
			$("#subcategory_request").val(1);
			$("#subcategory_name").val(0);
		}
		
		if(data.length==0)
		{
			if(parent_id != 0)
			{
				$("#subcategory_request").val(0);
				$("#subcategory_name").val(1);
			}
			$("#cancel_sub_category").hide();
			option	=	'<option value="0">Pilih tipe motor</option>';
			$("#subcat_id").html(option);
		}
	});
}

function Item(cat_id)
{
	if(cat_id == "new")
	{
		$("#prod_request").val(1);
		
		$("#subcategory").hide();
		$("#new_sub_category").show();
		
		$("#subcategory_request").val(0);
		$("#subcategory_name").val(1);
		
		$("#cancel_sub_category").show();
	}
	else if(cat_id == 0)
	{
		$("#prod_request").val(0);
		$("#subcategory_request").val(1);
		$("#subcategory_name").val(0);
	}
	else
	{
		$("#subcategory_request").val(1);
		$("#subcategory_name").val(0);
	}
}
function CancelSubCategory()
{	
	$("#new_sub_category").hide();
	$("#subcategory").show();
	$("#generate_item_name").hide();
	$("#prod_request").val(0);
	$("#subcategory_request").val(1);
	$("#subcategory_name").val(0);
}
function CancelCategory()
{
	$("#category").show();
	$("#subcategory").show();
	$("#new_sub_category").hide();
	$("#new_category").hide();
	$("#generate_item_name").hide();
	$("#prod_request").val(0);
	
	option	=	'<option value="0">Pilih tipe motor</option>';
	$("#subcat_id").html(option);
	
	$("#category_request").val(1);
	$("#category_name").val(0);
	$("#subcategory_request").val(1);
	$("#subcategory_name").val(0);
}

function SelectCondition(value)
{
	if(value==1)
	{
		$("#nopol").html('<?php echo $form->input("Product.nopol",array("value"=>"-1","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:120px;","maxlength"=>9)).'<div  style="margin-top:5px;margin-bottom:5px;border:0px solid black;">Belum ada</div>'?>');
		$("#kilometer").html('<?php echo "0 Km".$form->input("Product.kilometer",array("class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","maxlength"=>5,"value"=>0))?>');
		$("#stnk").html('<?php echo $form->input("Product.stnk_id",array("value"=>"-1","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:120px;")).'Belum ada'?>');
		$("#bpkb").html('<?php echo $form->input("Product.bpkb_id",array("value"=>"-1","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:120px;")).'Belum ada'?>');
	}
	else
	{
		$("#nopol").html('<?php echo $form->input("Product.nopol",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","style"=>"width:120px;","maxlength"=>9))?>');
		$("#ProductNopol").watermark({watermarkText:'co: B 6929 UMB',watermarkCssClass:'user_watermark'});
		$("#kilometer").html('<?php echo $form->input("Product.kilometer",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>5))."&nbsp;Km"?>');
		$("#ProductKilometer").watermark({watermarkText:'co: 90',watermarkCssClass:'user_watermark'});
		$("#stnk").html('<?php echo $form->input("Product.stnk_id",array('options'=>$stnk,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false) )?>');
		$("#bpkb").html('<?php echo $form->input("Product.bpkb_id",array('options'=>$bpkb,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false) )?>');
	}
}

function Color(value)
{
	if(value=="new")
	{
		$("#color").html('<?php echo $form->input("Product.color",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text"))?><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="CancelColor()">[ cancel ]</a>');
		$("#ProductColor").watermark({watermarkText:'co: Merah Marun',watermarkCssClass:'user_watermark'});
	}
}

function CancelColor()
{
	$("#color").html('<select name="data[Product][color]" style="width: 160px;" class="text7" label="false" id="ProductColor" onchange="Color(this.value)"><option value="" selected="selected">Warna motor</option><option value="new" style="font-weight:bold">Lainnya</option><?php foreach($color as $k=>$v):?><option value="<?php echo $k?>"><?php echo $v?></option><?php endforeach;?></select>');
}

function UploadPhoto(id)
{
	$("#Form-"+id).ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>AddProduct/UploadTmp",
		type		: "POST",
		dataType	: "json",
		contentType	: "multipart/form-data",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#pict-"+id).hide();
			$("#LoadingMapPict-"+id).show();
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
			
			if(data.status==true)
			{
				
				$("#pict-"+id).attr("src","<?php echo $settings['showimages_url']?>?filename="+data.name+"&code=<?php echo $profile['User']['id']?>&prefix=_prevthumb&content=TmpProduct&w=128&h=128&time="+(new Date()).getTime()).load(function(){
					$("#LoadingMapPict-"+id).hide();
					$("#pict-"+id).show();
					$("#filename-"+id).val(data.filename);
					$("#delete-"+id).show(300);
					$("#primary-"+id).show(300);
					$("#radio-"+id).trigger("click");
					$("#radio_p-"+id).trigger("click");
					
					$(this).css('cursor','pointer');
					$(this).bind('click',function(){
						$.prettyPhoto.open("<?php echo $settings['showimages_url']?>?filename="+data.name+"&code=<?php echo $profile['User']['id']?>&prefix=_zoom&content=TmpProduct&w=500&h=500&time="+(new Date()).getTime());
					});
				});
			}
			else
			{
				alert(data.msg);
				$("#filename-"+id).val('');
				$("#delete-"+id).hide(300);
				$("#primary-"+id).hide(300);
				if($("#radio-"+id).is(':checked'))
				{
					$("#radio-"+id).attr({ "checked": false});
					$("#radio_p-"+id).attr({ "checked": false});
				}
				$("#pict-"+id).attr('src','<?php echo $settings['site_url']?>img/question.png');
				
				$("#pict-"+id).show();
				$("#LoadingMapPict-"+id).hide();
			
			}
		}
	});
	return false;
}


function SubmitAdd()
{
	$("#ProductIndexForm").ajaxSubmit({

		url			: "<?php echo $settings['site_url']?>AddProduct/PrcessAdd",
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
				$(document).scrollTo("#psng_ikln", 800);
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

function DeletePhoto(id)
{
	$("#pict-"+id).attr('src','<?php echo $settings['site_url']?>img/question.png?time='+(new Date()).getTime()).hide();
	$("#LoadingMapPict-"+id).show();
	var filename	=	$("#filename-"+id).val();
	$(this).css('cursor','pointer');
	$.getJSON("<?php echo $settings['site_url']?>AddProduct/DeletePhoto/",{'filename':filename},function(data){
		
		if(data.status == true)
		{
			var input	=	'<input name="data[Product][photo]" class="browse2" onchange="return UploadPhoto('+id+')" id="ProductPhoto-'+id+'" type="file"><input name="data[Product][arr]" type="hidden" value="'+id+'">';
			$("#div-"+id).html(input);
			
			$("#LoadingMapPict-"+id).hide();
			$("#pict-"+id).show();
			$("#filename-"+id).val('');
			$("#delete-"+id).hide();
			
			$("#primary-"+id).hide();
			$("#ProductPhoto-"+id).filestyle({ 
				  image: "<?php echo $this->webroot?>img/browse2.png",
				  imageheight : 30,
				  imagewidth : 112,
				  width : 0,
				  height : 30		  
			});
			if($("#radio-"+id).is(':checked'))
			{
				$("#radio-"+id).attr({ "checked": false});
				$("#radio_p-"+id).attr({ "checked": false});
			}
			
			$("#pict-"+id).css('cursor','default');
			$("#pict-"+id).unbind();
		}
		else
		{
			alert(data.msg);
			$("#pict-"+id).show();
			$("#LoadingMapPict-"+id).hide();
		}
	});
}

function IsCredits()
{
	var checked	=	$("#ProductIsCredit");
	
	if(checked.is(':checked')==true)
	{
		$("#is_credit").fadeIn(600);
	}
	else
	{
		$("#is_credit").fadeOut(600);	
	}
}

function openFacebook2()
{	
	$.getJSON("<?php echo $ROOT?>/OpenId/FacebookUrl/0",{'login_type':'popup'},function(data){
		$("#loadingloginvia").hide();
		if(data.status==true)
		{
			var load = window.open(data.uri,'','scrollbars=no,menubar=no,height=250,width=600,resizable=yes,toolbar=no,location=no,status=no');
			if(load==null || typeof(load)=="undefined")
			{
				alert('Please turn off your popup blocker first.');
			}
		}
		else
		{
			alert('ups,try later');
		}
	});
}

function openTwitter2() {
	$.getJSON("<?php echo $ROOT?>/OpenId/TwitterUrl/0",function(data){
		$("#loadingloginvia").hide();
		if(data.status==true)
		{
			var load = window.open(data.uri,'','scrollbars=no,menubar=no,height=400,width=800,resizable=yes,toolbar=no,location=no,status=no');
			if(load==null || typeof(load)=="undefined")
			{
				alert('Please turn off your popup blocker first.');
			}
		}
		else
		{
			alert('ups,try later');
		}
	});
}
</script>

<div id="output"></div>
<div class="box_panel" style="min-height:50px; margin-bottom:10px;">
	<div class="line1" style=" margin-bottom:10px;">
        <div class="line4" style="border:0px solid black;">
            <span class="text3" id="psng_ikln">Pasang Iklan Motor</span>
        </div>
    </div>
    <div class="line1" style="border:2px solid red; width:350px; margin-left:220px; margin-bottom:20px; padding-bottom:10px; display:none;"id="success">
    	<div class="left" style="width:40px; padding-top:0px; padding-left:10px;border:0px solid black;">
        	<img src="<?php echo $this->webroot?>img/check.png" style="margin-top:5px;">
        </div>
        <div class="left" style="width:270px;border:0px solid black">
        	<span class="text4" style="font-weight:bold">Sukses simpan data</span>
            <div class="line1" style="border:0px solid black">
                <ul style="list-style:none; margin:10px 0 0 -40px; color:#F00; text-decoration:blink">
                    <li>Mohon tunggu sebentar..</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="line1">
    	<?php echo $form->create("Product",array("onsubmit"=>"return SubmitAdd()"))?>
		<?php echo $form->input("request_prod",array("type"=>"hidden","id"=>"prod_request","value"=>0))?>
        <?php echo $form->input("category_request",array("type"=>"hidden","id"=>"category_request","value"=>1))?>
        <?php echo $form->input("category_name",array("type"=>"hidden","id"=>"category_name","value"=>0))?>
        <?php echo $form->input("subcategory_request",array("type"=>"hidden","id"=>"subcategory_request","value"=>1))?>
        <?php echo $form->input("subcategory_name",array("type"=>"hidden","id"=>"subcategory_name","value"=>0))?>
    	<div class="line1" style="border:0px solid black; margin-left:60px; margin-bottom:10px; width:80%">
            <span class="text6">Informasi Penjual</span>
            <div class="underline">&nbsp;</div>
        </div>
        <div class="line1" style="margin-bottom:12px; margin-top:20px;border:0px solid black;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Nama Penjual :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
            	<span style="float:left" id="span_name"><?php echo $profile['Profile']['fullname']?></span>
                <?php if(!empty($profile['Company']['name'])):?>
                <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="ChangeName()" id="change_name">[ gunakan nama dealer ]</a>
                
                <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Anda dapat memilih nama penjual yang akan ditampilkan, apakah dari nama profil anda ataukah dari nama dealer/perusahaan anda."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                
               	<?php endif;?>
            	<?php echo $form->input("contact_name",array("type"=>"hidden","value"=>1))?>
                <span style="margin-left:5px" id="img_contact_name"></span>
                <span class="error" id="err_contact_name"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> No Telp :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
             	 <?php if(!empty($phone) and is_array($phone)):?>
					 <?php if(count($phone)>1):?>
                     	<?php echo $form->select("phone",$phone,false,array("style"=>"width:160px;float:left","class"=>"text7","label"=>"false","escape"=>false,"multiple"=>true));?>
                      	<a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Tekan tombol <b>ctrl</b> kemudian <b>klik</b> nomor telp yang anda inginkan untuk menampilkan lebih dari satu nomor telp."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                 	<?php else:?>
                 		<?php echo $form->input("phone",array("type"=>"hidden","name"=>"data[Product][phone][]","class"=>"user","div"=>false,"label"=>false,"value"=>$profile['Profile']['phone'],"readonly"=>"readonly"))?>
                        <span style="word-wrap: break-word;border:0px solid black; float:left; width:auto; margin-top:4px;">
                        	<?php echo $profile['Profile']['phone']?>
                        </span>
                 	<?php endif;?>
                 <?php else:?>
                 	<?php echo $form->input("phone",array("name"=>"data[Product][phone][]","class"=>"user","div"=>false,"label"=>false,"type"=>"text","value"=>$profile['Profile']['phone']))?>
                  	<a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Massukkan nulai numerik(angka) untuk no.telp tanpa spasi dan karakter."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                 <?php endif;?>
                 <span style="margin-left:5px;" id="img_phone"></span>
                 <span class="error" id="err_phone"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Yahoo! Messenger :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <?php echo $form->input("ym",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","title"=>"Sangat di rekomendasikan untuk diisi, untuk memudahkan pembeli menghubungi anda."))?>
                 <span style="margin-left:5px;" id="img_ym"></span>
                 <span class="error" id="err_ym"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Alamat :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
                <span id="alamat" style="word-wrap: break-word;border:0px solid black; float:left; width:auto; margin-right:10px;"></span>
                <span style="margin-top:-5px; display:block;" id="img_address"></span>
                <span class="error" id="err_address"></span>
                <span class="text8" id="charleft_address" style="float:left; width:100%"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong>  Propinsi :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
        		<span id="province_name" style="word-wrap: break-word;border:0px solid black; float:left; width:auto; margin-right:10px;"></span>
                <span style="margin-top:-5px; display:block;" id="img_province_id" ></span>
                <span class="error" id="err_province_id"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong>  Kota :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
        		<span id="city_name"  style="word-wrap: break-word;border:0px solid black; float:left; width:auto; margin-right:10px;"></span>
                <span style="margin-top:-5px; display:block;" id="img_city"></span>
                <span class="error" id="err_city"></span>
            </div>
        </div>
        <div class="line1" style="margin-left:60px; margin-bottom:10px; border:0px solid black; width:80%">
            <span class="text6">Informasi Motor</span>
            <div class="underline">&nbsp;</div>
        </div>
        <div class="line1" style="margin-bottom:12px;margin-top:20px;" id="category">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Merk motor :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <select name="data[Product][cat_id]" style="width: 160px;" class="text7" label="false" id="CatId" onchange="SubCat(this.value)">
                 	<option value="0" selected="selected">Pilih merk motor</option>
                    <option value="new" style=" font-weight:bold">Merk lainnya</option>
                    <?php foreach($category as $k=>$v):?>
                        <option value="<?php echo $k?>"><?php echo $v?></option>
                    <?php endforeach;?>
                 </select>
                 <span style="margin-left:5px;" id="img_cat_id"></span>
                 <span class="error" id="err_cat_id"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px; display:none;margin-top:20px;" id="new_category">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Merk lainnya :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	<?php echo $form->input("Product.newcategory",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text"))?>
                <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" onClick="CancelCategory()">[ cancel ]</a>
            	<span style="margin-left:5px;" id="img_newcategory"></span>
                <span class="error" id="err_newcategory"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;" id="subcategory">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Tipe Motor :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
                <select name="data[Product][subcategory]" id="subcat_id" class="text7" style="width: 160px;" onchange="Item(this.value)">
                    <option value="">Pilih tipe motor</option>
                </select>
                <span style="margin-left:5px;" id="img_subcategory"></span>
                <span class="error" id="err_subcategory"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px; display:none" id="new_sub_category">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Tipe Motor :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	<?php echo $form->input("Product.newsubcategory",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text"))?>
                 <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none; display:none;" onclick="CancelSubCategory()" id="cancel_sub_category">[ cancel ]</a>
            	<span style="margin-left:5px;" id="img_newsubcategory"></span>
                <span class="error" id="err_newsubcategory"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Kondisi :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <?php echo $form->select("condition_id",$condition,2,array("style"=>"width:160px;float:left","class"=>"text7","label"=>"false","escape"=>false,"empty"=>false,"onchange"=>"SelectCondition(this.value)"));?>
                 <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Jika anda memilih kondisi baru, berarti motor yang anda jual adalah motor dalam kondisi belum memiliki plat nomor."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                 <span style="margin-left:5px;" id="img_condition_id"></span>
                 <span class="error" id="err_condition_id"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> No Pol :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <div id="nopol" style="word-wrap: break-word;border:0px solid black; float:left; width:auto; margin-right:10px;">
				 	<?php echo $form->input("nopol",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>9))?>
                 </div>
                 <span style="margin-left:5px;float:left; display:block;" id="img_nopol"></span>
                 <span class="error" id="err_nopol"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Tahun pembuatan :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <?php echo $form->input("thn_pembuatan",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","style"=>"width:120px;","maxlength"=>4))?>
                 <span style="margin-left:5px;" id="img_thn_pembuatan"></span>
                 <span class="error" id="err_thn_pembuatan"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Warna :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <span id="color">
                     <select name="data[Product][color]" style="width: 160px;" class="text7" label="false" id="ProductColor" onchange="Color(this.value)">
                        <option value="" selected="selected">Warna motor</option>
                        <option value="new" style="font-weight:bold">Lainnya</option>
                        <?php foreach($color as $k=>$v):?>
                            <option value="<?php echo $k?>"><?php echo $v?></option>
                        <?php endforeach;?>
                     </select>
                 </span>
                 <span style="margin-left:5px;" id="img_color"></span>
                 <span class="error" id="err_color"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Kilometer :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
            	<div id="kilometer" style="word-wrap: break-word;border:0px solid black; float:left; width:auto; margin-right:10px;">
                	<?php echo $form->input("kilometer",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>5))?>&nbsp;Km
                </div>
                <span style="margin-left:5px; float:left; margin-top:-5px" id="img_kilometer"></span>
                <span class="error" id="err_kilometer"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>Keterangan :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
            	<div style="word-wrap: break-word;border:0px solid black; float:left; width:auto; margin-right:10px;">
                	<?php echo $form->textarea("description",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","title"=>"Masukkan keterangan singkat mengenai motor anda. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter."))?>
                </div>
                <span tyle="margin-left:5px; float:left; margin-top:-5px" id="img_description"></span>
                <span class="error" id="err_description"></span>
                <span class="text8" id="charleft" style="float:left; width:100%"></span>
            </div>
        </div>
        <div class="line1" style="margin-left:60px; margin-bottom:10px; border:0px solid black; width:80%">
            <span class="text6">Surat-surat motor</span>
            <div class="underline">&nbsp;</div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>STNK :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
            	<div id="stnk">
                	<?php echo $form->input("stnk_id",array('options'=>$stnk,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false) )?>
                </div>
                
                <span style="margin-left:5px;" id="img_stnk_id"></span>
                <span class="error" id="err_stnk_id"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span>BPKB :</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-top:5px;">
            	<div id="bpkb">
                	<?php echo $form->input("bpkb_id",array('options'=>$bpkb,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false) )?>
                </div>
                <span style="margin-left:5px;" id="img_bpkb_id"></span>
                <span class="error" id="err_bpkb_id"></span>
            </div>
        </div>
        <div class="line1" style="margin-left:60px; margin-bottom:10px; border:0px solid black; width:80%">
            <span class="text6">Harga motor</span>
            <div class="underline">&nbsp;</div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                <span><strong style="color:#F00">*</strong> Harga</span>
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <?php echo $form->input("price",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","maxlength"=>13))?>
                 <span style="margin-left:5px;" id="img_price"></span>
                 <span class="error" id="err_price"></span>
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                &nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:50%;">
            	 <?php echo $form->checkbox("is_credit",array("value"=>1,"onclick"=>"IsCredits()"))?>
                 <label for="ProductIsCredit">Saya jual dengan harga kredit</label>
                 
            </div>
        </div>
        <div class="line1" style="margin-bottom:12px; display:none" id="is_credit">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                &nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:50%; padding-left:25px;">
                <div class="line1" style="margin-bottom:12px;">
                    <div class="left" style="border:0px solid black; width:35%; text-align:left; padding-top:5px;">
                      <strong style="color:#F00">*</strong> Angsuran pertama : 
                    </div>
                    <div class="left" style="border:0px solid black; width:50%;">
                          <?php echo $form->input("first_credit",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","style"=>"width:150px","maxlength"=>13))?>
                          <span style="margin-left:5px;" id="img_first_credit"></span>
                          <span class="error" id="err_first_credit"></span>
                    </div>
                </div>
                <div class="line1" style="margin-bottom:12px;">
                    <div class="left" style="border:0px solid black; width:35%; text-align:left; padding-top:5px;">
                      <strong style="color:#F00">*</strong> Jumlah angsuran : 
                    </div>
                    <div class="left" style="border:0px solid black; width:50%;">
                          <?php echo $form->input("credit_interval",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","style"=>"width:150px"))?>&nbsp;X (kali)
                          <span style="margin-left:5px;" id="img_credit_interval"></span>
                          <span class="error" id="err_credit_interval"></span>
                    </div>
                </div>
                <div class="line1" style="margin-bottom:12px;">
                    <div class="left" style="border:0px solid black; width:35%; text-align:left; padding-top:5px;">
                     <strong style="color:#F00">*</strong>  Nilai angsuran perbulan : 
                    </div>
                    <div class="left" style="border:0px solid black; width:50%;">
                          <?php echo $form->input("credit_per_month",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text","style"=>"width:150px","maxlength"=>13))?>
                          <span style="margin-left:5px;" id="img_credit_per_month"></span>
                          <span class="error" id="err_credit_per_month"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="line1" style="margin-left:60px; margin-bottom:10px; border:0px solid black; width:80%">
            <span class="text6">Foto</span>
            <div class="underline">&nbsp;</div>
            <input name="data[Product][primary]" value="" type="hidden"/>
            <span style="display:none;">
            <?php for($i=1;$i<=6;$i++):?>
			<?php echo $form->input("Product.filename.$i",array("id"=>"filename-$i","type"=>"hidden"))?>
            <input name="data[Product][primary]" type="radio" value="<?php echo $i?>" id="radio_p-<?php echo $i?>"/>
            <?php endfor;?>
            </span>
        </div>
        <?php echo $form->input("facebook_share",array("type"=>"hidden","value"=>0))?>
        <?php echo $form->input("twitter_share",array("type"=>"hidden","value"=>0))?>
        <?php echo $form->end()?>
        
        
        <div class="line1" style="margin-left:150px;border:0px solid black; width:480px;" id="photo">
        	<div class="line1" style="margin-bottom:20px;">
            	<span id="err_primary" class="error"></span>
            </div>
        	<?php for($i=1;$i<=6;$i++):?>
            <div class="left" style="margin-right:10px; width:150px;border:0px solid black; height:220px;">
            	<div class="image_box7" style="margin-left:0px; float: left;width:128px; height:128px">
                    <img src="<?php echo $this->webroot?>img/loading19.gif" style="margin:60px; display:none;" id="LoadingMapPict-<?php echo $i?>"/>
                    <img src="<?php echo $settings['site_url']?>img/question.png" id="pict-<?php echo $i?>"/>
                    	
                </div>
                <div class="line1" style="margin-left:5px; margin-top:10px;border:0px solid black;">
                	<?php echo $form->create("Product",array("id"=>"Form-$i","type"=>"file"))?>
                    <div id="div-<?php echo $i?>">
						<?php echo $form->input("Product.arr",array("id"=>"arr-$i","value"=>$i,"type"=>"hidden"))?>
                        <?php echo $form->file("Product.photo",array("id"=>"ProductPhoto-$i","class"=>"browse2","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto($i)"))?>
                    </div>
                    <?php echo $form->end()?>
                </div>
                <div class="text8" style="width:100%;margin-left:10px; display:none;" id="primary-<?php echo $i?>">
                	<label>
                        <input name="primary" type="radio" value="<?php echo $i?>" id="radio-<?php echo $i?>" onclick="$('#radio_p-<?php echo $i?>').attr('checked',true)"/>
                        Set as primary
                    </label>
                </div >
                <div class="text8" style="margin-left:15px;width:70px;">
                	<a href="javascript:void(0)" class="text8" style="color:#694b04;display:none;border:0px solid black;" id="delete-<?php echo $i?>" onclick="DeletePhoto('<?php echo $i?>')"><img src="<?php echo $this->webroot?>img/delete.png" border="0"/>&nbsp;&nbsp;&nbsp;Delete </a>
                </div>
            </div>
            <?php endfor;?>
            
        </div>
        <div class="line1" style="margin-left:60px; margin-bottom:10px; border:0px solid black; width:80%">
            <span class="text6">Share</span>
            <div class="underline">&nbsp;</div>
        </div>
        <div class="line1" style="margin-bottom:12px;">
            <div style="float:none; margin:auto; width:40%">
            	<a href="javascript:void(0)" class="facebook_share_on" id="facebook_share" onclick="FacebookShare()">&nbsp;</a>
                <a href="javascript:void(0)" class="twitter_share_on" id="twitter_share" onclick="TwitterShare()"> &nbsp;</a>
            </div>
        </div>
        <div class="line1" style="margin-bottom:52px; margin-top:20px;">
            <div class="left" style="border:0px solid black; width:40%; margin-right:15px; text-align:right; padding-top:5px;">
                &nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:50%">
                <input type="submit" name="button" id="button" value="Simpan" class="btn_sign" onClick="return SubmitAdd()"/>
                <span class="font4" style="color:#000000;" id="loading"></span>
            </div>
        </div>
    </div>
</div>

<div class="line1" style=" border:0px solid black">
<?php for($i=1;$i<=6;$i++):?>
<img src="<?php echo $settings['site_url']?>img/question.png" id="zoom-<?php echo $i?>" style="display:none"/>
<?php endfor;?>
</div>