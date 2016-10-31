<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

<?php echo $javascript->link("jquery.tools.min.js");?>
<?php echo $javascript->link("jquery.slideViewer");?>
<?php echo $javascript->link("jquery.timers.js");?>
<?php echo $html->css("svwp_style.css");?>
<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->


<!-- MAP -->
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA8yhzO290x2yvu-ZkKAaqXxS3j-2W9j6l-efqP9QMSB0CsF-OyhQp0_KuKz6HQBfUlhkRZl0MOChltg&sensor=true" type="text/javascript"></script>
<?php echo $javascript->link("extinfowindow")?>
<?php echo $html->css("redInfoWindow")?>
<style>
.vertical {
	position:relative;
	overflow:hidden;
	height: 220px;
	border:0px solid #ddd;
}

.items {	
	position:absolute;
	height:20000em;	
	margin: 0px;
	width:100%;
}
.items div{
	display:block;
	float:left;
	width:100%;
	border:0px solid black;
}
.items li{
	background:url(<?php echo $this->webroot?>img/back_li.gif) repeat-x left;
	display:block;
	border:0px solid black;
	min-height:29px;
	height:auto;
	width:100%;
	float:left;
}
.items li a{
	background:url(<?php echo $this->webroot?>img/spot.gif) no-repeat left;
	padding-left:25px;
	color:#AC0202;
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	text-decoration:none;
	font-weight:bold;
	border:0px solid black;
}
.items li a:hover{
	text-decoration:underline;
}
</style>

<!--[if lt IE 8]><style>
.wraptocenter_gambar span {
    display: inline-block;
    height: 100%;
}
</style><![endif]-->

<?php 
$price			=	$number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null));
$description	=	empty($data['Product']['description']) ? "Tidak ada informasi" : $data['Product']['description'];
$status			=	($data['Product']['sold']=="1")? "Terjual" : "Tersedia";
$condition		=	($data['Product']['condition_id']=="1")? "Baru" : "Bekas";
$km				=	(is_null($data['Product']['kilometer']) or empty($data['Product']['kilometer']) ) ? "Tidak ada informasi" : $number->format($data['Product']['kilometer'],array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
$stnk			=	empty($data['Product']['stnk_id']) ? "Tidak ada informasi" : $stnk["Stnk"]["name"];
$bpkb			=	empty($data['Product']['bpkb_id']) ? "Tidak ada informasi" : $bpkb["Bpkb"]["name"];
$dp				=	($data['Product']['is_credit']=="1") ? $number->format($data['Product']['first_credit'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null)) : "";
$ja				=	($data['Product']['is_credit']=="1") ? $number->format($data['Product']['credit_interval'],array("thousands"=>".","before"=>NULL,"places"=>null,"after"=>" x")) : "";
$ap				=	($data['Product']['is_credit']=="1") ? $number->format($data['Product']['credit_per_month'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null)) : "";
?>
<?php $ym			=	(!empty($data["Product"]["ym"])) ? explode("@",$data["Product"]["ym"]) : "";?>
<?php $ym			=	(is_array($ym)) ? $ym[0] : "";?>
    
<div class="kiri size50 style1 text12 grey2">
	<?php echo $bread_crumb['bread']?>
</div>
<div class="kanan size50 style1 text12 grey2" style="border:0px solid black;">
	<div class="kiri" style="width:80px;">
    	<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo (urlencode($settings['site_url']."Iklan/Detail/".$data["Product"]["id"]."/".$general->seoUrl("dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$price).".html"))?>&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:0px solid black; overflow:hidden; width:80px; height:23px;" allowTransparency="true"></iframe>
    </div>
    <div class="kiri" style="width:70px;">
    	<a  href="javascript:void(0);" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php echo (urlencode($settings['site_url']."Iklan/Detail/".$data["Product"]["id"]."/".$general->seoUrl("dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$price).".html"))?>','','scrollbars=no,menubar=no,height=400,width=700,resizable=yes,toolbar=no,location=no,status=no')" style="border:0px solid black; float:left; display:block; width:50px;"><img src="<?php echo $this->webroot?>img/share_fb.gif" border="none" /></a>
    </div>
    <div class="kiri" style="width:80px;">
    	<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo ($settings['site_url']."Iklan/Detail/".$data["Product"]["id"]."/".$general->seoUrl("dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$price).".html")?>" data-text="Dijual <?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data['ProvinceGroup']['name']." ".$price." Hub: ".$data["Product"]["phone"]?>" data-via="JualanMotor">Tweet</a>
    </div>
    <div class="kiri" style="width:60px;">
    	<a href="https://twitter.com/JualanMotor" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @JualanMotor</a>
	</div>
</div>
<div class="line kiri size50 style1 text17 black bold top10">
	<?php echo $data['Parent']['name']." - ".$data['Category']['name']." (".$data['Product']['thn_pembuatan'].") "?>
</div>
<div class="line kiri size50 style1 text12 grey2 top3">
	<?php echo $data['Category']['name']?>, <span class="bold black1"><?php echo $bread_crumb['city']?></span>, dilihat <span class="bold black1"><?php echo $data['Product']['view']?></span> kali
</div>
<!-- GAMBAR -->
<div class="line size100 top5" style="border:0px solid black; position:relative;">
	<div class="bandroll"><span class="style1 white text17 bold top10 kiri left40" style="border:0px solid black;width:160px;" ><?php echo $price?></span></div>
    <div id="basic" class="svwp">
        <ul>
        	<?php foreach($images as $img):?>
        	<li><img width="580" height="380" src="<?php echo $settings['showimages_url']."?code=".$img['ProductImage']['id']."&prefix=_big&content=ProductImage&w=580&h=380"?>"/></li>
            <?php endforeach;?>
            <!-- eccetera -->
        </ul>
    </div>
</div>
<!-- GAMBAR -->

<!-- DESCRIPTION -->
<div class="kiri size98 top20" style="border:1px solid #D5D5D5;padding:5px; min-height:50px;">
	<div class="line kiri style1 red2 text14 bold">DESKRIPSI</div>
    <div class="line kiri style1 grey2 text12 top5" style="word-wrap: break-word;">
    	<?php echo nl2br($description)?>
    </div>
</div>
<!-- https://twitter.com/about/resources/buttons#follow-->
<!-- DESCRIPTION -->

<!-- DETAIL PENJUAL DAN MOTOR -->
<div class="line top10">
    <div class="kiri size491">
        <div class="text_title4">
            <div class="kiri left10">DETAIL MOTOR</div>
        </div>
        <div class="size993 kiri rounded3" style="padding-bottom:10px;">
        	<div class="kiri size90 left10 top10">
            	<div class="kiri style1 black1 bold text14 size100">
                	<?php echo $data['Parent']['name']." - ".$data['Category']['name']?>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top20">
                	<div class="kiri size50">
                    	Status
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $status?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	Thn Pembuatan
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $data['Product']['thn_pembuatan']?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	Warna
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $data['Product']['color']?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	Kondisi
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $condition?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	Kilometer
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $km?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	STNK
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $stnk?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	BPKB
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $bpkb?>
                    </div>
                </div>
                <?php if($data['Product']['is_credit']=="1"):?>
                <div class="kiri size30 style1 text13 bold red2 top30">
                    Dijual Kredit
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	DP
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $dp?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	Jumlah Angsuran
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $ja?>
                    </div>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top7">
                	<div class="kiri size50">
                    	Angsuran per Bulan
                    </div>
                	<div class="kiri size50 unbold">
                    	: <?php echo $ap?>
                    </div>
                </div>
                <?php endif;?>
                <div class="kiri style1 red2 bold text20 size100 top20">
                	<?php echo $price?>
                </div>
            </div>
            <div class="kiri style1 red2 bold text20 size100 top20 left5">
            	<div class="kiri size100 style1 text14 red2 bottom10">Simulasi Kredit</div>
                <div class="kiri size20 rounded1" style="border:1px solid #bfbfbf;background:#5e5e5e; padding:3px;"><a href="<?php echo $settings['site_url']?>Iklan/SimulasiKredit/<?php echo $data['Product']['id']?>/?tenor=1" class="style1 text12 white normal">1 Tahun</a></div>
                <div class="kiri size20 rounded1 left5" style="border:1px solid #bfbfbf;background:#5e5e5e; padding:3px;"><a href="<?php echo $settings['site_url']?>Iklan/SimulasiKredit/<?php echo $data['Product']['id']?>/?tenor=2" class="style1 text12 white normal">2 Tahun</a></div>
                <div class="kiri size20 rounded1 left5" style="border:1px solid #bfbfbf;background:#5e5e5e; padding:3px;"><a href="<?php echo $settings['site_url']?>Iklan/SimulasiKredit/<?php echo $data['Product']['id']?>/?tenor=3" class="style1 text12 white normal">3 Tahun</a></div>
                <div class="kiri size20 rounded1 left5" style="border:1px solid #bfbfbf;background:#5e5e5e; padding:3px;"><a href="<?php echo $settings['site_url']?>Iklan/SimulasiKredit/<?php echo $data['Product']['id']?>/?tenor=4" class="style1 text12 white normal">4 Tahun</a></div>
            </div>
        </div>
    </div>
    <div class="kanan size491">
        <div class="text_title4">
            <div class="kiri left10">INFORMASI PENJUAL</div>
        </div>
        <?php $min_height	=	($data['Product']['is_credit']=="1") ? "408" : "296";?>
        <div class="size994 kiri rounded3" style="padding-bottom:10px;">
        	<div class="kiri size93 left10 top10" style=" min-height:<?php echo $min_height?>px;border:0px solid black;">
            	<div class="kiri style1 black1 bold text14 size100">
                	<?php
                    	$model		=	($data["Product"]["data_type"]=="1") ? "Profile" : "Dealer";
						$model_id	=	($data["Product"]["data_type"]=="1") ? $data["Product"]["user_id"] : $data["Company"]["id"];
					?>
                	<a href="<?php echo $settings['site_url']?>Profil/Detail<?php echo $model?>/<?php echo $model_id?>/<?php echo $general->seoUrl("profil_".$data['Product']['contact_name'])?>.html" class="tyle1 black1 bold text14 normal"><?php echo $data['Product']['contact_name']?></a>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top10">
                    Alamat
                </div>
                <div class="kiri style1 black1 text12 size100" style="word-wrap: break-word;">
                    <?php echo  $data['Product']['address']." ".$data['Province']['name'].", ".$data['Province']['province']?>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top10">
                    No Telp
                </div>
                <div class="kiri style1 black1 text12 size100" style="word-wrap: break-word;">
                    <?php echo  chunk_split($data['Product']['phone'],50,"<br />")?>
                </div>
                <div class="kiri style1 black1 bold text12 size100 top10">
                    Email
                </div>
                <div class="kiri style1 black1 text12 size100" style="word-wrap: break-word;">
                    <?php echo  chunk_split($data['User']['email'],50,"<br />")?>
                </div>
                <?php if($profile["User"]["id"]!=$data['Product']['user_id']):?>
                <div class="kiri size100 style1 black1 bold text13 top20" style="border:0px solid black;">
                	Hubungi penjual
                </div>
                <div class="kiri size100 top10 style1 black1 text11" style="border:0px solid black;">
                	<a href="<?php echo $settings['site_url']?>Iklan/SendMessage/<?php echo $data['Product']['id']?>" class="style1 black1 text12 normal kiri"><img src="<?php echo $this->webroot?>img/hub_seller_ico.gif" style="border:none; vertical-align:middle;"/>
                		<?php echo  chunk_split($data['User']['email'],50,"<br />")?>
                    </a>
                    <?php if(!empty($ym)):?>
                    	<a href="ymsgr:sendIM?<?php echo $ym?>" class="kanan" style="margin-top:3px"><img border="0" src="http://opi.yahoo.com/online?u=<?php echo $ym?>&m=g&t=1"></a>
                    <?php endif;?>
                </div>
                <?php elseif(($profile["User"]["id"]==$data['Product']['user_id']) and $data['Product']['sold']=="0"):?>
                	<a href="<?php echo $settings['site_url']?>EditProduct/Index/<?php echo $data['Product']['id']?>" class="style1 red2 normal text14 bold top20 kiri"><img src="<?php echo $this->webroot?>img/b_edit.gif" border="0" style="vertical-align:middle; margin-right:5px;"/ >Edit Iklan</a>
                <?php endif;?>
                <?php $model_id	=	($data['Product']['data_type']==1) ? $data['Product']['user_id'] : $data['Company']['id']?>
                <?php $model	=	($data['Product']['data_type']==1) ? "User" : "Company"?>
                
                
                	<div class="kiri size100 top15" style="text-align:center; border:0px solid black;">
                    	<img src="<?php echo $settings['showimages_url']?>?code=<?php echo $model_id?>&prefix=_thumb150x100&content=<?php echo $model?>&w=70&h=70" id="UserPhoto" style="border:1px solid #cccccc; padding:2px;"/>
                    </div>
                
            </div>
        </div>
    </div>
</div>
<!-- DETAIL PENJUAL DAN MOTOR -->

<div class="kiri size98 top10" style="border:1px solid #D5D5D5;padding:5px; min-height:50px;" id="lokasi">
	<div class="line kiri style1 red2 text14 bold">LOKASI</div>
    <div class="kiri size661 right5 top10" id="map_canvas" style="height:289px;">
    	&nbsp;
    </div>
    <div class="kiri size33 top10" id="motor_lain">
    	<div class="kiri size100 backgrey" style="height:30px;border:0px solid #605E5E;">
        	<div class="left10 style1 white bold text12 text_shadow top7">MOTOR LAINNYA DI <?php echo strtoupper($data['ProvinceGroup']['name'])?></div>
        </div>
        <div class="kiri size100 backgrey2" style="border:0px solid black; height:259px">
            <div class="vertical kiri size100" id="scroll">
            	<div class="items">
                    
                </div>
            </div>
            <div id="actions" class="tengah size25" style="border:0px solid black;">
                <div class="kiri top15">
                    <a class="prev" style="cursor:pointer;"><img src="<?php echo $this->webroot?>img/down_red2.gif"/></a>
                    <a class="next" style="cursor:pointer;"><img src="<?php echo $this->webroot?>img/up_red2.gif"/></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MAP -->

<!-- KOMENTAR -->
<div id="out"></div>
<div class="kiri size100 top10" style="border:1px solid #D5D5D5;min-height:150px;">
	<div class="kiri size100 backred" style="height:30px;">
    	<div class="style1 kiri left10 bold text13 white top5">KOMENTAR <span id="komentar"></span></div>
    </div>
     <div class="tengah size97">
    	<div class="kiri size100 top10">
        	<div id="list_comment"></div>
            <!-- FORM -->
            <?php echo $form->create("Comment",array("id"=>"FormComment","onsubmit"=>"return SubmitFormComment()"))?>
            <?php $top	=	"top20";?>
            <?php if($is_login=="0"):?>
            <div class="line top10">
            	<div class="style1 text13 bold black" id="span_name">Nama :</div>
                <?php echo $form->input("name",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter.","class"=>"input7 style1 black text12 size35 kiri"))?>
                <span class="kiri left10" id="img_name"></span>
                <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_name"></span>
            </div>
            <div class="line top5">
            	<div class="style1 text13 bold black" id="span_email">Email :</div>
                <?php echo $form->input("email",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan alamat email anda di sini. Harap masukkan email dengan format standar : name@domain.com,<br>name.subname@domain.com","class"=>"input7 style1 black text12 size35 kiri"))?>
                <span class="kiri left10" id="img_email"></span>
                <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_email"></span>
            </div>
            <?php $top	=	"top5";?>
            <?php endif;?>
            <div class="line <?php echo $top?>">
            	<div class="style1 text13 bold black" id="span_comment">Komentar :</div>
                 <?php echo $form->textarea("comment",array("div"=>false,"label"=>false,"title"=>"Masukkan komentar anda. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter.","class"=>"input3 style1 black text12 size65 kiri textarea"))?>
                <span class="kiri left10" id="img_comment"></span>
                <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_comment"></span>
            </div>
            <div class="line top10 bottom15">
                <input type="button" name="button" value="KIRIM" class="tombol1" onclick="SubmitFormComment()" style="float:left"/>
               
                <div class="kiri style1 black text12 left5 top5" style="display:block" id="loading"></div>
                
            </div>
            <?php echo $form->end();?>
            <!-- FORM -->
        </div>
    </div>
</div>
<!-- KOMENTAR -->
<?php $show_thumbnail	=	(count($images)>1) ? 'true' : 'false'?>
<script>
var fade_in = 500;
var fade_out = 500;
var map;
var markers 			= new Array();
var imgids 				= new Array();
var titles 				= new Array();
var prices 				= new Array();
var addresss			= new Array();
var product_ids			= new Array();
var first;
$(document).ready(function(){
	$.getJSON("<?php echo $settings['site_url']?>Iklan/GetOther/<?php echo $data['Parent']['id']?>/<?php echo $data['Province']['group_id']?>/<?php echo $data['Product']['id']?>",function(data){
		if(data.status==true)
		{
			/*jika datanya hanya satu maka motor lain dibuang*/
			if(data.data.length == 1)
			{
				$("#motor_lain").remove();
				$("#map_canvas").removeClass("size661");
				$("#map_canvas").addClass("size999");
			}
			$("#lokasi").show(300);
			
			var li	=	"";
			$.each(data.data,function(i, item){
				if(i==0)
				{
					MapDefine(item.Product.lat,item.Product.lng);
				}
				
				/* Buat agar popup yang keluar adalah pop dari id product detailnya*/
				if(item.Product.id=='<?php echo $data['Product']['id']?>')
				{
					first	=	item.Product.id;
				}
				
				/*Buat scroll disini*/
				li	+=	'<li><a href="javascript:void(0)" onclick="checkMarkers(\''+item.Product.id+'\')" title="'+item.Parent.name+' '+item.Category.name+' - '+formatCurrency(item.Product.price)+'">'+(item.Parent.name+' '+item.Category.name).substr(0,25)+'</a></li>';
				if((i%8==0 && i!=0 && data.data.length>1) || i == ((data.data.length)-1))
				{
					$(".items").append('<div>'+li+'</div>');
					li = "";
				}
				
				/*Buat marker disini*/
				map.addOverlay(createMarker(item.Product.id,item.Product.lat,item.Product.lng,item.Category.name,item.Product.price,item.ProductImages.id,item.Product.address));
			});
			
			
			/*Jika datanya lebih dari satu maka dibuatkan scroll*/
			if(data.data.length>1)
			{
				$("#scroll").scrollable({ vertical: true, mousewheel: true });
			}
			checkMarkers(first);
			
			//CLOSE POPUP
			GEvent.addListener(map, 'extinfowindowopen', function(){
				$("#custom_info_window_red_close").click(function(){
					map.closeExtInfoWindow();
				});
			});
		}
		else
		{
			$("#lokasi").hide(300);
		}
	});
});

function checkMarkers(id)
{
	markers[id].openExtInfoWindow(
	  map,
	  "custom_info_window_red",
	  out(id,imgids[id],titles[id],prices[id],addresss[id]),
	  {
		beakOffset: 3,
		noCloseOnClick:true,
		buttons:{close:{show:4}}
	  }
	);
	//CLOSE POPUP
	GEvent.addListener(map, 'extinfowindowopen', function(){
		$("#custom_info_window_red_close").click(function(){
			map.closeExtInfoWindow();
		});
	});
}
</script>
<script>
function MapDefine(lat,lng)
{
	if (GBrowserIsCompatible())
	{
		map 		=	new GMap2(document.getElementById("map_canvas"));
		map.setMapType(G_NORMAL_MAP);
		map.addControl(new GLargeMapControl());
		var center 	= new GLatLng(lat,lng);
		map.setCenter(center, 15);
		map.enableScrollWheelZoom();
	}
	else
	{
		alert("Maaf browser anda tidak kompatibel dengan fitur map.");
	}
}

function createMarker(id,lat,lng,title,price,imgid,address)
{
	var letteredIcon		=	new GIcon(G_DEFAULT_ICON);
	letteredIcon.image		=	"<?php echo $this->webroot?>img/icn_paku.png";
	letteredIcon.iconSize	=	new GSize(33, 31);
	var w_marker 			=	new GMarker(new GLatLng(lat,lng),{clickable:true, autoPan:false,icon:letteredIcon,title:title});
	markers[id]				=	w_marker;
	imgids[id]				=	imgid;
	titles[id]				=	title;
	prices[id]				=	price;
	addresss[id]			=	address;
	
	//WHEN MARKER CLICK
	GEvent.addListener(w_marker,"click", function() {
		w_marker.openExtInfoWindow(
		  map,
		  "custom_info_window_red",
		  out(id,imgid,title,price,address),
		  {
			beakOffset: 3
		  }
		);
	});
	$("#custom_info_window_red_close").bind("click",function(){map.closeExtInfoWindow();});
	return w_marker;
}

function out(product_id,imgid,title,price,address)
{
	var a='';
	a +='\
	<div class="kiri size100" style="height:90px;border:0px solid black;">\
		<div class="kiri left5" style="width:80px; border:0px solid black;">\
			<img src="<?php echo $settings['showimages_url']."?code="?>'+imgid+'&prefix=_thumb_70x70&content=ProductImage&w=70&h=70" style="border:1px solid #cccccc">\
		</div>\
		<div class="kiri" style="width:145px; border:0px solid black;">\
			<div class="kiri size100"><a href="<?php echo $settings['site_url']?>Iklan/Detail/'+product_id+'" class="style1 text12 black1 bold normal">'+title+'</a></div>\
			<div class="kiri size100"><a href="<?php echo $settings['site_url']?>Iklan/Detail/'+product_id+'" class="style1 text11 black1 normal">'+address+'</a></div>\
			<div class="kiri style1 text15 red2 bold size100 top5">'+formatCurrency(price)+'</div>\
		</div>\
	</div>';
	return a;
}
function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+'.'+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + 'Rp. ' + num);
}
</script>
<script>

	$("div#basic").slideViewerPro({
		thumbs: 4,
		thumbsVis:<?php echo $show_thumbnail?>,
		thumbsPercentReduction: 20,
		buttonsWidth:10,
		galBorderWidth: 0,
		galBorderColor: "aqua",
		thumbsTopMargin: 20,
		thumbsRightMargin: 27,
		thumbsBorderWidth: 3,
		thumbsActiveBorderColor: "#AC0202",
		thumbsBorderColor: "#cccccc",
		thumbsActiveBorderOpacity: 1,
		thumbsBorderOpacity: 1,
		buttonsTextColor: "#707070",
		leftButtonInner: "<img src='<?php echo $this->webroot?>img/larw.gif' />",
		rightButtonInner: "<img src='<?php echo $this->webroot?>img/rarw.gif' />",
		autoslide: true, 
		typo: false
	});

</script>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.scrollTo")?>
<script>
function onClickPage(el,divName)
{
	
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+100)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}


function SumComment()
{
	$.getJSON("<?php echo $settings['site_url']?>Iklan/SumComment/<?php echo $data['Product']['id']?>",function(data){
		if(data!==null)
		{
			$("#komentar").html("( "+data+" komentar )");
		}
	});
}

$(document).ready(function(){
	/*List Comment*/
	$("#list_comment").load("<?php echo $settings['site_url']?>Iklan/ListComment/<?php echo $data['Product']['id']?>",function(){
		SumComment();																										   });
	
	if($.browser.msie)
	{
		fade_in 	= 100;
		fade_out	= 3500;
	}
		
	/*BUAT TOOLTIPS*/					   
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
});
$("#CommentName").watermark({watermarkText:'co: Her Robby Fajar',watermarkCssClass:'input7 style1 grey1 text12 size35 kiri italic'});
$("#CommentEmail").watermark({watermarkText:'co: abyfajar@gmail.com',watermarkCssClass:'input7 style1 grey1 text12 size35 kiri italic'});
$("#CommentComment").watermark({watermarkText:'co: Jl Kedoya Timur No5',watermarkCssClass:'input3 style1 grey1 text12 size65 kiri italic textarea'});


function SubmitFormComment()
{
	$("#FormComment").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Iklan/AddComment/<?php echo $data['Product']['id']?>",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19_bak.gif" style="float:left; display:block;vertical-align:middle; margin-right:5px;"/>Mohon tunggu ..');
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
			$("#out").html(data);
			$("#loading").html('');
			$("span[id^=err]").html('');
			$("span[id^=img]").html('');
			
			if(data.status==true)
			{
				$("#success").fadeIn('slow');
				$("#success").animate({opacity: 1.0}, 3000);
				$("#list_comment").load("<?php echo $settings['site_url']?>Iklan/ListComment/<?php echo $data['Product']['id']?>",function(){
					SumComment();																														   				});
				$("#FormComment").clearForm();
				
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