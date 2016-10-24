<?php if(!empty($data)):?>
<?php 
if(!empty($data['Profile']['address']))
{
	$data['Profile']['address']	=	str_replace(array(chr(10),chr(13),"\\n","\n"),array(' ',' ',' ',' '),$data['Profile']['address']);
}
if(!empty($data['Company']['address']))
{
	$data['Company']['address']	=	str_replace(array(chr(10),chr(13),"\\n","\n"),array(' ',' ',' ',' '),$data['Company']['address']);
}
?>
<script>
$(document).ready(function(){
	$("#list_item").load("<?php echo $settings['site_url']?>Profil/ListItemCompany/<?php echo $data["Company"]["user_id"]?>");
});
function onClickPage(el,divName)
{
	
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+250),top:(toppos+450)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}
</script>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">

<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">
                DETAIL DEALER
            </div>
        </div>
        <div class="tengah size98">
            <div class="kiri size100 top10">
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Nama Kontak
                    </div>
                    <div class="kiri size69">
                    	<div class="kiri">
                    		<a href="javascript:void(0)" style="border:none; outline:none" onClick="$.prettyPhoto.open('<?php echo $settings['showimages_url']?>?code=<?php echo $data['Company']['id']?>&prefix=_1000_600&content=Company&w=1000&h=600&nopict=noimages');"><img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data["Company"]["id"]?>&prefix=_121_84&content=Company&w=121&h=84" style="border:1px solid #cccccc; padding:2px;"/></a>
                        </div>
                        <div class="kiri left10 size60">
                        	<a href="<?php echo $settings['site_url']?>Profil/DetailProfile/<?php echo $data["Company"]["user_id"]?>/profil_<?php echo $general->seoUrl($data["Profile"]["fullname"])?>.html" class="kiri size100 style1 red2 normal text16 bold"> <?php echo $data["Profile"]["fullname"]?></a>
                            
                            <span class="kiri size15 style1 black1 normal text12 top5">Telp</span>
                            <span class="kiri size80 style1 black1 normal text12 top5">:&nbsp;&nbsp;<?php echo $data["Profile"]["phone"].$extend_phone?></span>
                            
                            <?php if(!empty($data["Profile"]["fax"])):?>
                            <span class="kiri size15 style1 black1 normal text12 top5">Fax</span>
                            <span class="kiri size80 style1 black1 normal text12 top5">:&nbsp;&nbsp;<?php echo $data["Profile"]["fax"]?></span>
                            <?php endif;?>
                            <?php if(!empty($data["Profile"]["ym"])):?>
                            <?php $ym	= explode("@",$data["Profile"]["ym"]);?>
                            
                            <span class="kiri size15 style1 black1 normal text12 top5">Ym</span>
                            
                            <a href="ymsgr:sendIM?<?php echo $ym[0]?>" class="kiri size80 left10 style1 black1 normal text12 top5"><img border="0" src="http://opi.yahoo.com/online?u=<?php echo $ym[0]?>&m=g&t=1"></a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Nama Dealer
                    </div>
                    <div class="kiri size69 style1 black2 text15 bold">
                    	<?php echo $data["Company"]["name"]?>
                    </div>
                </div>
                <?php if(!empty($data["Company"]["description"])):?>
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Deskripsi
                    </div>
                    <div class="kiri 100 style1 top20">
                    	<?php echo nl2br($description)?>
                    </div>
                </div>
                <?php endif;?>
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Alamat
                    </div>
                    <div class="kiri size69 style1 black2 text12">
                    	<?php echo $data["Company"]["address"]?>
                    </div>
                </div>
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Kota
                    </div>
                    <div class="kiri size69 style1 black2 text12">
                    	<?php echo $data["Province"]["name"]?>
                    </div>
                </div>
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Propinsi
                    </div>
                    <div class="kiri size69 style1 black2 text12">
                    	<?php echo $data["Province"]["province"]?>
                    </div>
                </div>
                
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Telp
                    </div>
                    <div class="kiri size69 style1 black2 text12">
                    	<?php echo $data["Company"]["phone"].$extend_phone?>
                    </div>
                </div>
                <?php if(!empty($data["Company"]["fax"])):?>
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Fax
                    </div>
                    <div class="kiri size69 style1 black2 text12">
                    	<?php echo $data["Company"]["fax"]?>
                    </div>
                </div>
                <?php endif;?>
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Email
                    </div>
                    <div class="kiri size69 style1 black2 text12">
                    	<?php echo $data["User"]["email"]?>
                    </div>
                </div>
                <?php if(!empty($data["Company"]["website"])):?>
                <div class="kiri size100" style="border-bottom:1px solid #cccccc; padding-bottom:10px; padding-top:10px;">
                	<div class="kiri size30 style1 black1 text12 bold">
                    	Website
                    </div>
                    <div class="kiri size69 style1 black2 text12">
                    	<a href="<?php echo $data["Company"]["website"]?>" class="style1 red2 text12 normal" target="_blank"><?php echo $data["Company"]["website"]?></a>
                    </div>
                </div>
                <?php endif;?>
               
                <?php if($data["Company"]["user_id"] == $profile["User"]["id"]):?>
                <div class="kiri size100 top10">
                <input type="button" name="button" value="EDIT PROFIL DEALER" class="tombol1" onclick="location.href='<?php echo $settings['site_url']?>Cpanel/CompanyProfile'" style="float:left"/>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<div id="list_item"></div>

<!--- MAPS -->
<?php if(!empty($data["Company"]["lat"]) && !empty($data["Company"]["lng"])):?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA8yhzO290x2yvu-ZkKAaqXxS3j-2W9j6l-efqP9QMSB0CsF-OyhQp0_KuKz6HQBfUlhkRZl0MOChltg&sensor=true" type="text/javascript"></script>
<?php echo $javascript->link("extinfowindow")?>
<?php echo $html->css("redInfoWindow")?>

<div class="kiri size100 top30">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">
                MAP DEALER
            </div>
        </div>
        <div class="kiri size100" style="width:100%; height:250px" id="map_canvas">
        	
        </div>
	</div>
</div>
<script>

$(document).ready(function(){
initialize();
});


function initialize() {
var map 		=	new GMap2(document.getElementById("map_canvas"));
map.setMapType(G_NORMAL_MAP);
map.addControl(new GLargeMapControl());

var center 		=	new GLatLng('<?php echo $data["Company"]["lat"]?>','<?php echo $data["Company"]["lng"]?>');
map.setCenter(center, 15);
map.enableScrollWheelZoom();

var letteredIcon			=	new GIcon(G_DEFAULT_ICON);
letteredIcon.image			=	"<?php echo $this->webroot?>img/icn_paku.png";
letteredIcon.iconSize		=	new GSize(33, 31);
	
var w_marker 				=	new GMarker(new GLatLng('<?php echo $data["Company"]["lat"]?>','<?php echo $data["Company"]["lng"]?>'),{clickable:true, autoPan:false,icon:letteredIcon});
map.addOverlay(w_marker);

//WHEN MARKER CLICK
w_marker.openExtInfoWindow(
	map,
	"custom_info_window_red",
	out(),
	{
	  beakOffset: 3
	}
  );

GEvent.addListener(w_marker,"click", function() {
w_marker.openExtInfoWindow(
	map,
	"custom_info_window_red",
	out(),
	{
	  beakOffset: 3
	}
  );
});

//CLOSE POPUP
GEvent.addListener(map, 'extinfowindowopen', function(){
	$("#custom_info_window_red_close").click(function(){
		map.closeExtInfoWindow();
	});
});
	
}

function out()
{
	var a='';
	a +='\
	<div class="kiri size100" style="height:90px;border:0px solid black;">\
		<div class="kiri left5" style="width:80px; border:0px solid black;">\
			<img src="<?php echo $settings['showimages_url']."?code=".$data["Company"]["id"]?>&prefix=_70_70&content=Company&w=70&h=70" style="border:1px solid #cccccc">\
		</div>\
		<div class="kiri" style="width:145px; border:0px solid black;">\
			<div class="kiri size100"><a href="" class="style1 text12 black1 bold normal"><?php echo $data["Company"]["name"]?></a></div>\
			<div class="kiri size100 style1 text11 black1 normal"><?php echo strip_tags(htmlentities($data["Company"]["address"],ENT_QUOTES))?></div>\
			<div class="kiri size100 style1 text11 black1 normal"><?php echo htmlentities($data["Province"]["name"], ENT_QUOTES)?></div>\
			<div class="kiri size100 style1 text11 black1 normal"><?php echo htmlentities($data["Province"]["province"], ENT_QUOTES)?></div>\
		</div>\
	</div>';
	return a;
}


</script>
<?php endif;?>
<!-- MAPS -->

<!-- *****************************************SEND MESSAGE *************************************** -->
<?php if($data["Company"]["user_id"] !== $profile["User"]["id"]):?>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.hoverIntent.minified")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<?php echo $javascript->link("jquery.scrollTo")?>
<script>
var fade_in = 500;
var fade_out = 500;
$(document).ready(function(){
	if($.browser.msie)
	{
		fade_in 	= 100;
		fade_out	= 3500;
	}
	jQuery.bt.options.ajaxLoading = '<div style="width:235px;height:250px;display:block;float:left;"><div style="color:#000000;font-size:12px;float:none;margin:50% auto;display:block; text-align:center;"><img src="<?php echo $this->webroot?>img/loading19_bak.gif" />&nbsp;Loading..</div></div>';
	jQuery.bt.options.closeWhenOthersOpen = true;
	$('a[rel^=img_thumb]').each(function(){
		$(this).bt({
			ajaxPath: ["$(this).attr('link')"],
			width: 237,
			positions: ['right'],
			cornerRadius: 0,
			strokeStyle: '#8a8a8a',
			fill: 'rgba(255, 255, 255, 1)',
			cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px','padding-top':'2px','padding-right':'1px','padding-left':'2px','padding-bottom':'1px'},
			shrinkToFit: true,
			hoverIntentOpts: {
				interval: 200,
				timeout: 2000
			  }
		});
	});
	
	$('input,textarea').each(function(){
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
});

function SubmitPm()
{
	$("#SendPmForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Profil/SendMessageDealer",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19.gif" style="float:left; display:block;vertical-align:middle; margin-right:5px;"/>Please wait...');
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
					
				});
				
				$("#CompanyMessage").val('');
				$("span[id^=err]").html('');
				$("span[id^=img]").html('');
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
</script>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3 top30">
            <div class="line1">Kirim Email</div>
        </div>
        <div class="line size100 kiri position1" style="padding-bottom:10px;" id="updt_prfl">
        	<div class="size70 tengah" id="success"  style="display:none">
                <div class="kiri size100 style1 top10">
                    <div class="kiri size100 left10 top10 bold text14 blink red2">Pesan Anda telah terkirim</div>
                </div>
            </div>
            <?php echo $form->create("Company",array("onsubmit"=>"return SubmitPm()","id"=>"SendPmForm"))?>
             <?php echo $form->input("company_id",array("type"=>"hidden","readonly"=>true,"value"=>$data["Company"]["id"]))?>
            <div class="kiri size95 left10" style="border:0px solid black;">
                <div class="line top20">
                    <div class="kiri size30 style1 black1 bold text13" style="border:0px solid black">
                    	Ke
                    </div>
                    <div class="kiri style1 text14 bold white" style="border:0px solid black">
                    	<img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data["Company"]["id"]?>&prefix=_50_50&content=Company&w=50&h=50" id="UserPhoto" style="border:1px solid #cccccc; padding:2px;"/>
                    </div>
                     <div class="kiri style1 text14 bold black1 left5" style="border:0px solid black">
                    	<?php echo $data['Company']["name"]?><br>
                        <span class="unbold">(<?php echo $data['User']["email"]?>)</span><br>
                    </div>
                </div>
                
                <div class="kiri size100  top15">
                    <div class="kiri size30 style1 black1 bold text13 top5" id="span_from"><span class="text15">*</span> Dari</div>
                    <div class="kiri size69">
                    	
                        <?php echo $form->input("from",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama anda disini. Maksimal ".$settings['max_name_char']." karakter","class"=>"input7 style1 black1 text12 size80 kiri","value"=>$display_name))?>
                        
                        <span class="kiri left10" id="img_from"></span>
                        <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_from"></span>
                    </div>
                </div>
                <div class="kiri size100  top15">
                    <div class="kiri size30 style1 black1 bold text13 top5" id="span_email"><span class="text15">*</span> Email</div>
                    <div class="kiri size69">
                        <?php echo $form->input("email",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Berikan email anda agar pemasang iklan dapat menghubungi anda","class"=>"input7 style1 black1 text12 size80 kiri","value"=>$profile["User"]["email"]))?>
                        <span class="kiri left10" id="img_email"></span>
                        <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_email"></span>
                    </div>
                </div>
                <div class="kiri size100  top15">
                    <div class="kiri size30 style1 black1 bold text13 top5" id="span_telp"> NoTelp</div>
                    <div class="kiri size69">
                        <?php echo $form->input("telp",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan no telp anda agar dapat dihubungi","class"=>"input7 style1 black1 text12 size80 kiri","maxlength"=>30,"value"=>$profile["Profile"]["phone"]))?>
                        <span class="kiri left10" id="img_telp"></span>
                        <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_telp"></span>
                    </div>
                </div>
                <div class="kiri size100 top15">
                    <div class="kiri size30 style1 black1 bold text13 top5" id="span_subject"><span class="text15">*</span> Perihal</div>
                    <div class="kiri size69">
                        <?php echo $form->input("subject",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Berikan subjek untuk pesan anda.","class"=>"input7 style1 black1 text12 size80 kiri" ,"value"=>"Saya mau beli motor dari dealer anda."))?>
                        <span class="kiri left10" id="img_subject"></span>
                        <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_subject"></span>
                    </div>
                </div>
                <div class="kiri size100 top15" style="border:0px solid black;">
                    <div class="kiri size30 style1 black1 bold text13 top5" id="span_message" style="border:0px solid black;"><span class="text15">*</span> Isi Pesan</div>
                    <div class="kiri size69" style="border:0px solid black;">
                    	<div class="style1 white text12 size98 kiri">
                            <?php echo $form->textarea("message",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan isi pesan anda.","class"=>"input7 style1 black1 text12 size100 kiri textarea","style"=>"height:200px;"))?>
                        </div>
                        <div class="kiri size5" id="img_message" style="border:0px solid black;"></div>
                    	<div class="kiri style1 red2 text12 bold left10 size70" style="text-decoration:blink;" id="err_message"></div>
                    </div>
                </div>
                <div class="line top20 bottom10">
                    <input type="button" name="button" value="KIRIM EMAIL" class="tombol1" onclick="SubmitPm()" style="float:left"/>
                    <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                </div>
            </div>
            <?php echo $form->end()?>
		</div>
	</div>
</div>
<?php endif;?>
<!-- *****************************************SEND MESSAGE *************************************** -->


<?php else:?>
<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1">Detail dealer tidak ditemukan.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Maaf detail dealer yang anda cari tidak kami temukan.<br /><br />
            Mungkin dealer yang anda cari telah dihapus oleh pemasang iklan, <br /><br />atau telah di hapus oleh admin kami.<br /><br />
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
<?php endif;?>