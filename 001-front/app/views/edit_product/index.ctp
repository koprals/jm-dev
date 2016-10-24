<?php 
if(!empty($profile['Profile']['address']))
{
	$profile['Profile']['address']	=	str_replace(array(chr(10),chr(13),"\\n","\n"),array(' ',' ',' ',' '),$profile['Profile']['address']);
}
if(!empty($profile['Company']['address']))
{
	$profile['Company']['address']	=	str_replace(array(chr(10),chr(13),"\\n","\n"),array(' ',' ',' ',' '),$profile['Company']['address']);
}
$data['Product']['address']	=	str_replace(array(chr(10),chr(13),"\\n","\n"),array(' ',' ',' ',' '),$data['Product']['address']);

?>
<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.filestyle")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<?php echo $html->css("jquery.sceditor.min")?>
<?php echo $javascript->link("jquery.sceditor")?>
<?php echo $javascript->link("jquery.sceditor.bbcode")?>

<div id="output"></div>
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


var alamat	=	'<?php echo $data['Product']['address'].$form->input("Product.address",array("type"=>"hidden","value"=>$data['Product']['address']))?>';

$(document).ready(function(){
	var user_point	= <?php echo $user_point?>;
	var chk_point	=	0;
	
	$("input[id^=chk_]").bind("click",function(){
	
		if($(this).is(':checked')==true)
		{
			chk_point		=	parseInt($(this).attr("point"));	
			user_point		-=	chk_point;
			$("#chk_hide_"+$(this).val()).attr("checked","checked");
		}
		else
		{
			chk_point		=	parseInt($(this).attr("point"));	
			user_point		+=	chk_point;
			$("#chk_hide_"+$(this).val()).attr("checked","");
		}
		$("#user_point").html(user_point);
		if(user_point <=0 )
		{
			alert("Maaf point anda tidak mencukupi!, ayo tingkatkan lagi JmPoint anda.");
		}
	});
	
	$("#alamat").html(alamat);
	Tooltips("#tooltips_address");
	$("#city_name").html(option2);
	SubCat('<?php echo $data['Parent']['id']?>','<?php echo $data['Category']['id']?>');
	
	
	<?php if(!empty($profile['Company']['address'])):?>
	$('<a class="text8" href="javascript:void(0)" style="margin-left:10px;margin-right:10px; border:0px solid black; float:left; text-decoration:none" rel="help" id="tooltips_address" title="Alamat yang tertera sesuai dengan nama penjual yang anda pilih, jika anda menggunakan nama dealer sebagai nama penjual maka alamat yang tertera adalah alamat dealer begitupun sebaliknya."><img src="<?php echo $this->webroot?>img/help.png" border="0"></a>').insertAfter("#alamat");
	<?php endif;?>
	
	$('a[rel^=help]').each(function(){
		$(this).bt({
		  width: 230,
		  trigger: ['hover'],
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
		   'msgFontColor': '#ffffff',
		   'msgFontFamily': 'Arial',
		   'msgTextAlign': 'left',
		   'msgWarningColor': '#F00',
		   'msgAppendMethod': 'insertBefore',
		   'msgAppendSelector':'#charleft_address'     	  
	});
	
	
	$("#ProductYm").watermark({watermarkText:'co: aby_labdb@yahoo.com',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
	$("#ProductNopol").watermark({watermarkText:'co: B 6929 UMB',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
	$("#ProductNewcategory").watermark({watermarkText:'co: Suzuki',watermarkCssClass:'input3 style1 grey italic text12 size69 kiri'});
	$("#ProductNewsubcategory").watermark({watermarkText:'co: Suzuki Satria',watermarkCssClass:'input3 style1 grey italic text12 size69 kiri'});
	$("#ProductThnPembuatan").watermark({watermarkText:'co: 2005',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
	$("#ProductKilometer").watermark({watermarkText:'co: 90',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
	//$("#ProductDescription").watermark({watermarkText:'co: Masih mulus, baru dipakai satu bulan,tidak ada lecet...',watermarkCssClass:'input3 style1 grey italic text12 size70 kiri textarea'});
	
	$("#ProductAddress").watermark({watermarkText:'co: Jl mawar01 No 34',watermarkCssClass:'address_watermark'});
	$("#ProductPrice").watermark({watermarkText:'co: 10000000',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
	<?php if(empty($profile['Profile']['phone'])):?>
	$("#ProductPhone").watermark({watermarkText:'co: 86377177',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
	<?php endif;?>
	
	SelectCondition('<?php echo $data['Product']['condition_id']?>');
	
	$("#ProductDescription").sceditorBBCodePlugin({
		toolbar:	"bold,italic,underline,strike,subscript,superscript|left,center,right,justify|" +
				"font,size,color,removeformat|cut,copy,paste,pastetext|bulletlist,orderedlist|" +
				"undo,redo|link,unlink|emoticon|" + "source",
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


function SelectTipe(parent_id,category_id)
{
	if(parent_id.length>0)
	{
		$("#tipe_motor").load("<?php echo $settings['site_url']?>EditProduct/SelectType/",{'parent_id':parent_id,'category_id':category_id});
	}
	else
	{
		$("#tipe_motor").html('<select name="data[Product][subcategory]" id="subcat_id" class="text7" style="width: 160px;" onchange="Item(this.value)"><option value="">Pilih tipe motor</option></select>');	
	}
}

function Tooltips(element)
{
	$(element).bt({
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
}

function ChangeName()
{
	var html	=	$("#change_name").html();
	
	if(html=='[ gunakan data dealer ]')
	{
		$("#change_name").html('[ gunakan data akun ]');
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
		$("#change_name").html('[ gunakan data dealer ]');
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
		   'msgFontColor': '#ffffff',
		   'msgFontFamily': 'Arial',
		   'msgTextAlign': 'left',
		   'msgWarningColor': '#F00',
		   'msgAppendMethod': 'insertBefore',
		   'msgAppendSelector':'#charleft_address'     	  
	});
}


function SubCat(parent_id,category_id)
{
	if(parent_id != "new" && parent_id != 0)
	{
		$("#subcat_id").html('<option value="">Mohon tunggu..</option>');
	}
	
	$.getJSON("<?php echo $settings['site_url']?>EditProduct/GetSubcategoryJson",
	{
		"parent_id":parent_id,
		'category_id':category_id
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
				var selected	=	(data[i].Category.id == category_id) ? "selected='selected'" : "";
				option	+=	"<option value='"+data[i].Category.id+"' "+selected+">"+data[i].Category.name+"</option>";
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
	$("#subcat_id").val('<?php echo $data['Category']['id']?>');
	
}
function CancelCategory()
{
	$("#category").show();
	$("#subcategory").show();
	$("#new_sub_category").hide();
	$("#new_category").hide();
	$("#generate_item_name").hide();
	$("#prod_request").val(0);
	
	//option	=	'<option value="0">Pilih tipe motor</option>';
	//$("#subcat_id").html(option);
	
	SubCat('<?php echo $data['Parent']['id']?>','<?php echo $data['Category']['id']?>');
	$("#CatId").val('<?php echo $data['Parent']['id']?>');
	$("#category_request").val(1);
	$("#category_name").val(0);
	$("#subcategory_request").val(1);
	$("#subcategory_name").val(0);
}

function SelectCondition(value)
{
	if(value==1)
	{
		
		$("#nopol").html('<?php echo $form->input("Product.nopol",array("value"=>"-1","class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:120px;","maxlength"=>9,"readonly"=>"readonly")).'Belum ada'?>');
		$("#kilometer").html('<?php echo "0 Km".$form->input("Product.kilometer",array("class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","maxlength"=>5,"value"=>0,"readonly"=>"readonly"))?>');
		$("#stnk").html('<?php echo $form->input("Product.stnk_id",array("value"=>"-1","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:120px;","readonly"=>"readonly")).'Belum ada'?>');
		$("#bpkb").html('<?php echo $form->input("Product.bpkb_id",array("value"=>"-1","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:120px;","readonly"=>"readonly")).'Belum ada'?>');
	}
	else
	{
		$("#nopol").html('<?php echo $form->input("Product.nopol",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>9,"value"=>$data['Product']['nopol']))?>');
		$("#ProductNopol").watermark({watermarkText:'co: B 6929 UMB',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
		$("#kilometer").html('<?php echo $form->input("Product.kilometer",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>5,"value"=>$data['Product']['kilometer']))."&nbsp;Km"?>');
		$("#ProductKilometer").watermark({watermarkText:'co: 90',watermarkCssClass:'input3 style1 black text12 size50 kiri'});
		$("#stnk").html('<?php echo $form->input("Product.stnk_id",array('options'=>$stnk,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['stnk_id']) )?>');
		$("#bpkb").html('<?php echo $form->input("Product.bpkb_id",array('options'=>$bpkb,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['bpkb_id']) )?>');
	}
}

function Color(value)
{
	if(value=="new")
	{
		$("#color").html('<?php echo $form->input("Product.color",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text"))?><a class="kiri style1 white text12 bold left10 top3 normal" href="javascript:void(0)" onClick="CancelColor()">[ cancel ]</a>');
		$("#ProductColor").watermark({watermarkText:'co: Merah Marun',watermarkCssClass:'input3 style1 grey italic text12 size50 kiri'});
	}
}

function CancelColor()
{
	$("#color").html('<select name="data[Product][color]" class="input3 style1 black text12 size70 kiri" label="false" id="ProductColor" onchange="Color(this.value)"><option value="" selected="selected">Warna motor</option><option value="new" style="font-weight:bold">Lainnya</option><?php foreach($color as $k=>$v):?><?php $selected	=	($k==$data['Product']['color']) ? 'selected="selected"' : ""?><option value="<?php echo $k?>" <?php echo $selected?>><?php echo $v?></option><?php endforeach;?></select>');
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
					$("#restore-"+id).show();
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


function SubmitEdit()
{
	//ProcessEdit();
	$.sceditorBBCodePlugin.documentClickHandler;
	$.getJSON("<?php echo $settings['site_url']?>Template/CheckLogin",function(data){
		if(data.status==false)
		{
			Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Maaf session login anda telah habis, silahkan login kembali atau refresh halaman ini.</span></div>",function(){window.location.reload()},{title:'Login expired.'});
		}
		else
		{
			ProcessEdit();
		}
	});
}

function ProcessEdit()
{
	$("#ProductIndexForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>EditProduct/PrcessEdit",
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
function IsNumeric(data){
    return parseFloat(data)==data;
}


function DeletePhoto(id)
{
	$("#pict-"+id).attr('src','<?php echo $settings['site_url']?>img/question.png?time='+(new Date()).getTime()).hide();
	$("#LoadingMapPict-"+id).show();
	var filename	=	$("#filename-"+id).val();
	$(this).css('cursor','pointer');
	
	$("#deleteimg-"+id).val(1);
	$("#restore-"+id).show();

	if(IsNumeric(filename)==false && filename.length>0)
	{
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
				$("#pict-"+id).removeAttr('onclick');
			}
			else
			{
				alert(data.msg);
				$("#pict-"+id).show();
				$("#LoadingMapPict-"+id).hide();
			}
		});
	}
	else
	{
		$("#LoadingMapPict-"+id).hide();
		$("#pict-"+id).show();
		$("#filename-"+id).val('');
		$("#delete-"+id).hide();
		$("#primary-"+id).hide();
		if($("#radio-"+id).is(':checked'))
		{
			$("#radio-"+id).attr({ "checked": false});
			$("#radio_p-"+id).attr({ "checked": false});
		}
		
		$("#pict-"+id).css('cursor','default');
		$("#pict-"+id).unbind();
		$("#pict-"+id).removeAttr('onclick');
	}
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

function Restore(id,img_id,is_primary)
{
	$("#deleteimg-"+id).val(0);
	$("#filename-"+id).val(img_id);
	$("#delete-"+id).show(300);
	$("#primary-"+id).show(300);
	$("#restore-"+id).hide(300);
	if(is_primary=="1")
	{
		$("#radio-"+id).trigger("click");
		$("#radio_p-"+id).trigger("click");
	}
	
	$("#pict-"+id).attr("src","<?php echo $settings['showimages_url']?>?code="+img_id+"&prefix=_prevthumb&content=ProductImage&w=128&h=128&time="+(new Date()).getTime());
	
	$("#pict-"+id).css('cursor','pointer');
	$("#pict-"+id).unbind();
	$("#pict-"+id).bind('click',function(){
		$.prettyPhoto.open("<?php echo $settings['showimages_url']?>?code="+img_id+"&prefix=_zoom&content=ProductImage&w=500&h=500"+(new Date()).getTime());
	});
}

function FacebookShare()
{
	
	if($("#ProductFacebookShare").val()==1)
	{
		$("#ProductFacebookShare").val(0);
		$("#facebook_share").removeClass();
		$("#facebook_share").addClass('facebook_share_on');
	}
	else
	{
		$("#ProductFacebookShare").val(1);
		$("#facebook_share").removeClass();
		$("#facebook_share").addClass('facebook_share_off');
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
	if($("#ProductTwitterShare").val()==1)
	{
		$("#ProductTwitterShare").val(0);
		$("#twitter_share").removeClass();
		$("#twitter_share").addClass('twitter_share_off');
	}
	else
	{
		$("#ProductTwitterShare").val(1);
		$("#twitter_share").removeClass();
		$("#twitter_share").addClass('twitter_share_on');
		$.getJSON("<?php echo $settings['site_url']?>Users/CheckExtId/twitter",function(data){
			if(data.status == false)
			{
				openTwitter2();
			}
		});
	}
}
function KlikAgree()
{
	if($("#agree_palsu").is(':checked')==true)
	{
		$("#agree").attr("checked",true);
	}
	else
	{
		$("#agree").attr("checked",false);
		
	}
}

</script>
<style>
.table_title
{
	text-align:center;
	font-weight:bold;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFFFFF;
	border-bottom:1px solid #dedede;
}
.table_row
{
	text-align:left;
	font-weight:normal;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#FFFFFF;
	height:100px;
}

.bg_white
{
	background-color:#FFFFFF;
}
.bg_grey
{
	background-color:#c5c5c5;
}

</style>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Edit Iklan</div>
        </div>
        <div class="line back3 size100 kiri position1 rounded2" style="padding-bottom:10px;" id="psng_ikln">
        	<?php if(!empty($error)):?>
            <div class="size80 tengah" style="display:block">
                <div class="kiri size100 style1 white top10 reounded_error">
                    <div class="kiri size100 left10 top10 bold text14 blink">Error</div>
                    <div class="kiri size100 left10 top10 bold text12 bottom10"><?php echo $error?></div>
                </div>
            </div>
            <?php else:?>
			<div class="size80 tengah" id="success"  style="display:none">
                <div class="kiri size100 style1 white top10 reounded_error">
                    <div class="kiri size100 left10 top10 bold text14 blink">Success Save</div>
                    <div class="kiri size100 left10 top10 bold text12 bottom10">Please wait, we will refresh your page ...</div>
                </div>
            </div>
            
            <div class="tengah size65">
            	<?php echo $form->create("Product",array("onsubmit"=>"return SubmitEdit()"))?>
				<?php echo $form->input("request_prod",array("type"=>"hidden","id"=>"prod_request","value"=>0))?>
                <?php echo $form->input("category_request",array("type"=>"hidden","id"=>"category_request","value"=>1))?>
                <?php echo $form->input("category_name",array("type"=>"hidden","id"=>"category_name","value"=>0))?>
                <?php echo $form->input("subcategory_request",array("type"=>"hidden","id"=>"subcategory_request","value"=>1))?>
                <?php echo $form->input("subcategory_name",array("type"=>"hidden","id"=>"subcategory_name","value"=>0))?>
                <?php echo $form->input("id",array("type"=>"hidden","id"=>"subcategory_name","value"=>$product_id))?>
                
                <?php $facebook_val	=	($data['Product']['facebook_share']==1 && in_array("facebook",$profile['extid'])) ? 1:  0?>
                <?php $twitter_val	=	($data['Product']['twitter_share']==1 && in_array("twitter",$profile['extid'])) ? 1 :  0?>
                        
                <?php echo $form->input("facebook_share",array("type"=>"hidden","value"=>$facebook_val))?>
                <?php echo $form->input("twitter_share",array("type"=>"hidden","value"=>$twitter_val))?>
                <div class="line top10 style1 bold white text17" style="border-bottom:1px solid white;">
                	Informasi Penjual
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_contact_name"><span class="text15">*</span> Nama Penjual :</div>
                    <div class="kiri style1 white text12 bold top3" id="span_name"><?php echo $data['Product']['contact_name']?></div>
                    <?php if($data['Product']['data_type']==1):?>
						<?php if(!empty($profile['Company']['name'])):?>
                            <a class="kiri style1 white text12 bold left10 top3 normal" href="javascript:void(0)" onClick="ChangeName()" id="change_name">[ gunakan data dealer ]</a>
                            <a class="kiri top3" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Anda dapat memilih data yang akan ditampilkan, apakah dari data profil anda ataukah dari data dealer anda."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                        <?php endif;?>
                    <?php else:?>
                    	<a class="kiri style1 white text12 bold left10 top3 normal" href="javascript:void(0)" onClick="ChangeName()" id="change_name">[ gunakan nama akun ]</a>
                    <?php endif;?>
                    <?php echo $form->input("contact_name",array("type"=>"hidden","value"=>$data['Product']['data_type']))?>
                    <span class="kiri left10" id="img_contact_name"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_contact_name"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_phone"><span class="text15">*</span> No Telp :</div>
					<?php if(!empty($phone) and is_array($phone)):?>
                    	<?php if(count($phone)>1):?>
                        	<?php echo $form->select("phone",$phone,$productphone,array("style"=>"height:70px;","class"=>"input3 style1 black text12 size70 kiri","label"=>"false","escape"=>false,"multiple"=>true));?>
                            <a class="kiri top3" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Tekan tombol <b>ctrl</b> kemudian <b>klik</b> nomor telp yang anda inginkan untuk menampilkan lebih dari satu nomor telp."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                        <?php else:?>
                        	<?php echo $form->input("phone",array("type"=>"hidden","name"=>"data[Product][phone][]","class"=>"input3 style1 black text12 size70 kiri","div"=>false,"label"=>false,"value"=>$data['Product']['phone'],"readonly"=>"readonly"))?>
                            <div class="kiri style1 white text12 bold left10 top3"><?php echo $data['Product']['phone']?></div>
                        <?php endif;?>
                    <?php else:?>
						<?php echo $form->input("phone",array("name"=>"data[Product][phone][]","class"=>"input3 style1 black text12 size70 kiri","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['phone'],"title"=>"Harap masukkan dalam format angka, tidak mengandung huruf, spasi ataupun karakter lain."))?>
                        <a class="kiri top3" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="Massukkan nulai numerik(angka) untuk no.telp tanpa spasi dan karakter."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                    
                    <?php endif;?>
                    <span class="kiri left10" id="img_phone"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_phone"></span>
                </div>
                <?php if(empty($profile['Profile']['ym'])):?>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_ym">Yahoo! Messenger :</div>
                    <?php echo $form->input("ym",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text","title"=>"Sangat di rekomendasikan untuk diisi, untuk memudahkan pembeli menghubungi anda."))?>
                    <span class="kiri left10" id="img_ym"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_ym"></span>
                </div>
                <?php endif;?>
                <div class="line top60 style1 bold white text17" style="border-bottom:1px solid white;">
                	Informasi Motor
                </div>
                <div class="line top20" id="category">
                    <div class="style1 text14 bold white" id="span_cat_id"><span class="text15">*</span> Merk motor :</div>
                    <select name="data[Product][cat_id]" class="input3 style1 black text12 size70 kiri" label="false" id="CatId" onchange="SubCat(this.value)">
                        <option value="0" selected="selected">Pilih merk motor</option>
                        <option value="new" style=" font-weight:bold">Merk lainnya</option>
                        <?php foreach($category as $k=>$v):?>
                            <?php $selected	=	($k==$data['Parent']['id']) ? "selected='selected'" : ""?>
                            <option value="<?php echo $k?>" <?php echo $selected?>><?php echo $v?></option>
                        <?php endforeach;?>
                     </select>
                    <span class="kiri left10" id="img_cat_id"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_cat_id"></span>
                </div>
                <div class="line top20" id="new_category" style="display:none;">
                    <div class="style1 text14 bold white" id="span_newcategory"><span class="text15">*</span> Merk lainnya :</div>
                    <?php echo $form->input("Product.newcategory",array("class"=>"input3 style1 black text12 input3 style1 black text12 size69 kiri kiri","div"=>false,"label"=>false,"type"=>"text"))?><a class="kiri style1 white text12 bold left10 top3 normal" href="javascript:void(0)"  onClick="CancelCategory()">[ cancel ]</a>
                    <span class="kiri left10" id="img_newcategory"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_newcategory"></span>
                </div>
                <div class="line top20" id="subcategory">
                    <div class="style1 text14 bold white" id="span_subcategory"><span class="text15">*</span> Tipe Motor :</div>
                    <select name="data[Product][subcategory]" id="subcat_id" class="input3 style1 black text12 input3 style1 black text12 size70 kiri kiri" onchange="Item(this.value)">
                        <option value="">Pilih tipe motor</option>
                    </select>
                    <span class="kiri left10" id="img_subcategory"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_subcategory"></span>
                </div>
                <div class="line top20" id="new_sub_category" style="display:none;">
                    <div class="style1 text14 bold white" id="span_newsubcategory"><span class="text15">*</span> Tipe Motor :</div>
                    <?php echo $form->input("Product.newsubcategory",array("class"=>"input3 style1 black text12 input3 style1 black text12 size69 kiri kiri","div"=>false,"label"=>false,"type"=>"text"))?><a class="kiri style1 white text12 bold left10 top3 normal" href="javascript:void(0)"  onClick="CancelSubCategory()" id="cancel_sub_category">[ cancel ]</a>
                    <span class="kiri left10" id="img_newsubcategory"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_newsubcategory"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_condition_id"><span class="text15">*</span> Kondisi :</div>
                     <?php echo $form->select("condition_id",$condition,$data['Product']['condition_id'],array("class"=>"input3 style1 black text12 input3 style1 black text12 size70 kiri kiri","label"=>"false","escape"=>false,"empty"=>false,"onchange"=>"SelectCondition(this.value)"));?>
                     <a class="kiri top3 normal left10" href="javascript:void(0)" rel="help" title="Jika anda memilih kondisi baru, berarti motor yang anda jual adalah motor dalam kondisi belum memiliki plat nomor."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                     
                    <span class="kiri left10" id="img_condition_id"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_condition_id"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_nopol"><span class="text15">*</span> No Pol :</div>
                     <div id="nopol" class="style1 white text12 bold"><?php echo $form->input("nopol",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>9,"value"=>$data['Product']['nopol']))?></div>
                    <span class="kiri left10" id="img_nopol"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_nopol"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_thn_pembuatan"><span class="text15">*</span>Tahun pembuatan :</div>
                    <?php echo $form->input("thn_pembuatan",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"value"=>$data['Product']['thn_pembuatan']))?>
                    <span class="kiri left10" id="img_thn_pembuatan"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_thn_pembuatan"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_color"><span class="text15">*</span>Warna :</div>
                    <span id="color">
                    <select name="data[Product][color]" class="input3 style1 black text12 input3 style1 black text12 size70 kiri kiri" label="false" id="ProductColor" onchange="Color(this.value)">
                        <option value="" selected="selected">Warna motor</option>
                        <option value="new" style="font-weight:bold">Lainnya</option>
                        <?php foreach($color as $k=>$v):?>
                        <?php $selected	=	($k==$data['Product']['color']) ? "selected='selected'" : ""?>
                        <option value="<?php echo $k?>" <?php echo $selected?>><?php echo $v?></option>
                        <?php endforeach;?>
                    </select>
                    </span>
                    <span class="kiri left10" id="img_color"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_color"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_kilometer">Kilometer :</div>
                    <div id="kilometer" class="style1 white text12 bold"><?php echo $form->input("kilometer",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>6,"value"=>$data['Product']['km']))?>&nbsp;Km</div>
                    <span class="kiri left10" id="img_kilometer"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_kilometer"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_description">Keterangan :</div>
                    <?php echo $form->textarea("description",array("label"=>false,"div"=>false,"error"=>false,"class"=>"input3 style1 black text12 size70 kiri textarea","value"=>$data['Product']['description'],"style"=>"height:300px;width:550px;"))?>
                    <span class="kiri left10" id="img_description"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_description"></span>
                    
                    
                </div>
                
                <div class="line top60 style1 bold white text17" style="border-bottom:1px solid white;">
                	Surat-surat motor
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_stnk_id">STNK :</div>
                    <div id="stnk" class="style1 white text12 bold">
						<?php echo $form->input("stnk_id",array('options'=>$stnk,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['stnk_id']) )?>
                    </div>
                    <span class="kiri left10" id="img_stnk_id"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_stnk_id"></span>
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_bpkb_id">BPKB :</div>
                    <div id="bpkb" class="style1 white text12 bold">
						<?php echo $form->input("bpkb_id",array('options'=>$bpkb,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['bpkb_id']) )?>
                    </div>
                    <span class="kiri left10" id="img_bpkb_id"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_bpkb_id"></span>
                </div>
                <div class="line top60 style1 bold white text17" style="border-bottom:1px solid white;">
                	Harga motor
                </div>
                <div class="line top20">
                    <div class="style1 text14 bold white" id="span_price"><span class="text15">*</span>Harga :</div>
                    
					<?php echo $form->input("price",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>13,"value"=>$number->format($data['Product']['price'],array("zero"=>"","thousands"=>".","before"=>null,"places"=>null,"after"=>null)) ))?>
                    
                    <span class="kiri left10" id="img_price"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_price"></span>
                </div>
                
                <?php $checked = 	($data['Product']['is_credit']==1) ? "checked" : "" ?>
                <?php $display = 	($data['Product']['is_credit']==1) ? "block" : "none" ?>
                
                <div class="line top10"> <?php echo $form->checkbox("is_credit",array("value"=>1,"onclick"=>"IsCredits()","checked"=>$checked))?>
                    <label for="ProductIsCredit">Saya jual dengan harga kredit</label></div>
               
                <div class="kiri top20 left50 size80" style="display:<?php echo $display?>;" id="is_credit">
                	<div class="line">
                        <div class="kiri style1 text14 bold white size38" id="span_first_credit"><span class="text15">*</span>Angsuran pertama :</div>
                        
						<div class="kiri size48">
							<?php echo $form->input("first_credit",array("class"=>"input3 style1 black text12 kiri size100","div"=>false,"label"=>false,"type"=>"text","maxlength"=>13,"value"=>$number->format($data['Product']['first_credit'],array("zero"=>"","thousands"=>".","before"=>null,"places"=>null,"after"=>null))))?>
                            <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_first_credit"></span>
                        </div>
                        <span class="kiri left10" id="img_first_credit"></span>
                    </div>
                    <div class="line top10 style1 text12 white">
                        <div class="kiri style1 text14 bold white size38" id="span_credit_interval"><span class="text15">*</span>Jumlah angsuran :</div>
                        <div class="kiri size48">
							<?php echo $form->input("credit_interval",array("class"=>"input3 style1 black text12 size100 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>3,"value"=>$data['Product']['credit_interval']))?>
                            <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_credit_interval"></span>
                        </div>
                        <span class="kiri left10">&nbsp;X (kali)</span>
                        <span class="kiri left10" id="img_credit_interval"></span>
                    </div>
                    <div class="line top10 style1 text12 white">
                        <div class="kiri style1 text14 bold white size38" id="span_credit_per_month"><span class="text15">*</span>Nilai angsuran perbulan : </div>
                        <div class="kiri size48">
							<?php echo $form->input("credit_per_month",array("class"=>"input3 style1 black text12 size100 kiri","div"=>false,"label"=>false,"type"=>"text","maxlength"=>13,"value"=>$number->format($data['Product']['credit_per_month'],array("zero"=>"","thousands"=>".","before"=>null,"places"=>null,"after"=>null))))?>
                            <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_credit_per_month"></span>
                        </div>
                        <span class="kiri left10" id="img_credit_per_month"></span>
                    </div>
                </div>
                <div class="line top60 style1 bold white text17" style="border-bottom:1px solid white;">
                	Foto
                    
                    <input name="data[Product][primary]" value="" type="hidden"/>
                    <span style="display:none;">
                    <?php for($i=1;$i<=6;$i++):?>
                        <input name="data[Product][delete][<?php echo $i?>]" value="0" type="text" id="deleteimg-<?php echo $i?>"/>
                        <?php if(!empty($img[$i]['id'])):?>
                            <?php echo $form->input("Product.filename.$i",array("id"=>"filename-$i","type"=>"hidden","value"=>$img[$i]['id']))?>
                        <?php else:?>
                            <?php echo $form->input("Product.filename.$i",array("id"=>"filename-$i","type"=>"hidden"))?>
                        <?php endif;?>
                        <?php if(!empty($img[$i]['id']) && $img[$i]['is_primary']==1):?>
                            <input name="data[Product][primary]" type="radio" value="<?php echo $i?>" id="radio_p-<?php echo $i?>" checked="checked"/>
                        <?php else:?>
                            <input name="data[Product][primary]" type="radio" value="<?php echo $i?>" id="radio_p-<?php echo $i?>"/>
                        <?php endif;?>
                    <?php endfor;?>
                    </span>
                    <?php echo $form->input("agree",array('type'=>'checkbox','div'=>false,'label'=>false,"value"=>"1","escape"=>false,"id"=>"agree","style"=>"display:none"))?>
                </div>
                <div class="kiri top10 line">
                    <span class="kiri style1 white text12 bold" style="text-decoration:blink;" id="err_primary"></span>
                    <span class="kiri left10 top-5" id="img_primary"></span>
                </div>
				<div style="display:none;">
				<?php foreach($Ads as $Ads1):?>
					<input type="checkbox" name="data[AdsType][id][]" id="chk_hide_<?php echo $Ads1["AdsType"]["id"]?>" value="<?php echo $Ads1["AdsType"]["id"]?>"/>
				<?php endforeach;?>
				</div>
                <?php echo $form->end()?>
                <div class="tengah size100">
                    <div class="kiri left14" style="border:0px solid black;">
                    	<?php for($i=1;$i<=6;$i++):?>
                        <?php $checked	=	(!empty($img[$i]['id']) && $img[$i]['is_primary']==1) ? "checked='checked'" : "" ?>
                        <div class="foto_upload">
                            <div class="foto_upload_image">
                            	<img src="<?php echo $this->webroot?>img/loading19.gif" style="margin:60px; display:none;" id="LoadingMapPict-<?php echo $i?>"/>
                                <?php if(!empty($img[$i]['id'])):?>
                                    <img src="<?php echo $settings['showimages_url']."?code=".$img[$i]['id']."&prefix=_prevthumb&content=ProductImage&w=128&h=128"?>" onclick="$.prettyPhoto.open('<?php echo $settings['showimages_url']."?code=".$img[$i]['id']."&prefix=_zoom&content=ProductImage&w=500&h=500"?>');" id="pict-<?php echo $i?>" style="cursor:pointer"/>
                                    <?php $display = "block";?>
                                <?php else:?>
                                	<?php $display = "none";?>
                    				<img src="<?php echo $settings['site_url']?>img/question.png" id="pict-<?php echo $i?>"/>
                                 <?php endif;?>
                            </div>
                            <div class="tengah size79">
                            	<div class="kiri top10 line">
									<?php echo $form->create("Product",array("id"=>"Form-$i","type"=>"file"))?>
                                    <div id="div-<?php echo $i?>" style="border:0px solid black;">
                                        <?php echo $form->input("Product.arr",array("id"=>"arr-$i","value"=>$i,"type"=>"hidden"))?>
                                        <?php echo $form->file("Product.photo",array("id"=>"ProductPhoto-$i","class"=>"browse2","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto($i)"))?>
                                    </div>
                                    <?php echo $form->end()?>
                                </div>
                                <div class="kiri top10 line">
                                    <label style="display:<?php echo $display?>;" id="primary-<?php echo $i?>">
                                        <input name="primary" type="radio" value="<?php echo $i?>" id="radio-<?php echo $i?>" onclick="$('#radio_p-<?php echo $i?>').attr('checked',true)" <?php echo $checked?>/>
                                        Set as primary
                                    </label>
                                </div>
                                <div class="kiri top3 line left5">
                                	<?php if(!empty($img[$i]['id'])):?>
                                    
                                    	<a href="javascript:void(0)" class="style1 white text12 normal" style="display:<?php echo $display?>;border:0px solid black;" id="delete-<?php echo $i?>" onclick="DeletePhoto('<?php echo $i?>')"><img src="<?php echo $this->webroot?>img/delete.png" border="0"/>&nbsp;&nbsp;&nbsp;Delete </a>
                                        
                        				<a href="javascript:void(0)" class="style1 white text12 normal kiri top5" style="display:none;border:0px solid black;" id="restore-<?php echo $i?>" onclick="Restore('<?php echo $i?>','<?php echo $img[$i]['id']?>','<?php echo $img[$i]['is_primary']?>')"><img src="<?php echo $this->webroot?>img/page_arrow_left.gif" border="0"/>&nbsp;&nbsp;Restore </a>
                                    <?php else:?>
                                    
                                    	<a href="javascript:void(0)" class="style1 white text12 normal" style="display:<?php echo $display?>;" id="delete-<?php echo $i?>" onclick="DeletePhoto('<?php echo $i?>')"><img src="<?php echo $this->webroot?>img/delete.png" border="0"/>&nbsp;&nbsp;&nbsp;Delete </a>
                                        
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <?php endfor;?>
                    </div>
                </div>
                <div class="line style1 bold white text17" style="border-bottom:1px solid white;">
                	Share
                </div>
                <div class="tengah size60" style="border:0px solid black;">
                    <div class="kiri">&nbsp;</div>
					<div class="kiri"  style="border:0px solid black; margin-top:10px;">
                    	<?php $facebook_class	=	($data['Product']['facebook_share']==1 && in_array("facebook",$profile['extid'])) ? "facebook_share_off" :  "facebook_share_on"?>
                <?php $twitter_class	=	($data['Product']['twitter_share']==1 && in_array("twitter",$profile['extid'])) ? "twitter_share_off" :  "twitter_share_on"?>
                        <a href="javascript:void(0)" class="<?php echo $facebook_class?>" id="facebook_share" onclick="FacebookShare()">&nbsp;</a>
                        <a href="javascript:void(0)" class="<?php echo $twitter_class?>" id="twitter_share" onclick="TwitterShare()"> &nbsp;</a>
                    </div>
                </div>
				<div class="line style1 bold white text17 top60" style="border-bottom:1px solid white;">
                	Promo JmPoint
                </div>
				<div class="line top20">
					<div class="size100 rounded4 tengah top20" style="background-color:#595959; padding:10px;">
						<div class="normal style1 white size100 text12">
							Promokan iklan anda dengan optimal, dengan membeli JmPoint iklan anda akan lebih sering tampil di <?php echo $settings['site_name']?> dan semakin banyak dilihat oleh para calon pembeli.
						</div>
					</div>
					<div class="line top20">
						<div class="size100 tengah">
							<div class="style1 white size100 text14 kiri bottom10 bold">JmPoin Anda saat ini:</div>
						</div>
					</div>
					<div class="line">
						<div class="size100 tengah">
							<div class="kiri rounded4 text20 white style1 bold right10" style=" min-width:60px;background-color:#595959; padding:10px; text-align:center" id="user_point">
								<?php echo $user_point?>
							</div>
							<div class="kiri size80 text12 white style1">
								<input type="button" name="button" value="&nbsp;&nbsp;&nbsp;BELI JmPoint&nbsp;&nbsp;&nbsp;" class="tombol1" onClick="window.open('<?php echo $settings['site_url']?>Point/BeliPoint','_blank')"style="float:left"/>
							</div>
						</div>
					</div>
				</div>
				<div class="kiri top20" style="width:108%;">
					<div class="rounded4 tengah top20" style="background-color:#595959; padding:10px;width:108%;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
							<tr class="table_title">
								<td width="5%" height="28">&nbsp;</td>
								<td width="21%">Nama</td>
								<td width="50%">Deskripsi</td>
								<td width="11%">JmPoint</td>
								<td width="13%">Waktu</td>
							</tr>
							<?php $count=0;?>
							<?php foreach($Ads as $Ads):?>
							<?php $count++;?>
							<?php $disabled	=	($Ads["AdsType"]["disabled"]=="true") ? 'disabled="disabled"' : ""?>
							<?php $strike	=	($Ads["AdsType"]["disabled"]=="true") ? 'style="text-decoration:line-through"' : ""?>
							<?php $bg	=	($count%2 == 0) ? "bg_white" : "bg_grey" ;?>
							<tr class="table_row" <?php echo $strike?>>
								<td><input type="checkbox" name="data[AdsType][id][]" id="chk_<?php echo $Ads["AdsType"]["id"]?>" value="<?php echo $Ads["AdsType"]["id"]?>" <?php echo $disabled?> point="<?php echo $Ads["AdsType"]["point"]?>"/></td>
								<td><label for="chk_<?php echo $Ads["AdsType"]["id"]?>"><?php echo $Ads["AdsType"]["title"]?></label></td>
								<td><?php echo $Ads["AdsType"]["description"]?></td>
								<td style="text-align:center;"><?php echo $Ads["AdsType"]["point"]?></td>
								<td style="text-align:center;"><?php echo $Ads["AdsType"]["days"]?> Hari</td>
							</tr>
							<?php endforeach;?>
						</table>
					</div>
					<div class="kiri size100 top10 bottom10">
						<span class="kiri left10" id="img_request_point"></span>
						<span class="kiri style1 white text12 bold" style="text-decoration:blink;" id="err_request_point"></span>
					</div>
				</div>
                <div class="kiri" style="border:0px solid black;">
                    <div class="kiri">&nbsp;</div>
                    <div class="kiri style1 text12 white" style="margin-top:20px;">
                    	
                    	<div class="kiri size100" id="span_agree" style="border:0px solid black;">
                        	
                            <span class="kiri style1 white text12 bold" style="text-decoration:blink;" id="err_agree"></span>
                            <span class="kiri left10 top-5" id="img_agree"></span>
                        </div>
                        <label for="agree_palsu" onclick="KlikAgree()"><input name="" type="checkbox" value="" id="agree_palsu"  style="margin-left:-1px;"/>Saya setuju dengan </label><a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/TermConditions?iframe=true&amp;width=400&amp;height=440');" class="red normal">perjanjian </a><label for="agree_palsu" onclick="KlikAgree()"><?php echo $settings['site_name']?> </label>
                        
                    </div>
                </div>
                <div class="tengah size20">
                	<div class="kiri">&nbsp;</div>
                    <div class="kiri top50 bottom20" style="margin-top:20px;">
                        <input type="button" name="button" value="&nbsp;&nbsp;&nbsp;SUBMIT&nbsp;&nbsp;&nbsp;" class="tombol1" onClick="return SubmitEdit()" style="float:left"/>
                         <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                    </div>
                </div>
            </div>
            <div class="line1" style=" border:0px solid black">
				<?php for($i=1;$i<=6;$i++):?>
                <img src="<?php echo $settings['site_url']?>img/question.png" id="zoom-<?php echo $i?>" style="display:none"/>
                <?php endfor;?>
            </div>
            <?php endif;?>
		</div>
	</div>
</div>