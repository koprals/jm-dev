<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="1Qe7hpPkk8Mr1RSExrrAnTRwjOwBI8jzi3v2b3Eay4Y" />
<link rel="icon" type="image/gif" href="<?php echo $this->webroot?>img/favico.gif">
<meta http-equiv="X-XRDS-Location" content="<?php echo $settings['site_url']?>xrds.xml" />
<meta name="google-site-verification" content="oJ-xxOvJ__DGdYIScBaG3-ElEZxyB3WPW53PQgy_yio" />
<meta name="title" content="<?php echo $title_for_layout?>" />
<meta name="description" content="<?php echo $title_for_layout?>" />
<meta name="keywords" content="<?php echo $keywords?>" />
<title><?php echo $title_for_layout; ?></title>

<!-- START BLOCK CSS -->
<?PHP echo $html->css('style_JM')?>
<?PHP echo $html->css('edit_aby')?>
<?php echo $html->css("prettyPhoto")?>
<!-- END BLOCK CSS -->


<!-- START BLOCK JAVASCRIPT -->
<?PHP echo $javascript->link('jquery.latest')?>
<?PHP echo $javascript->link('jquery.selectbox-0.6.1')?>
<?PHP echo $javascript->link('jquery.form')?>
<?php echo $javascript->link("jquery.prettyPhoto")?>
<?php echo $javascript->link("popuplibs")?>
<!-- END BLOCK JAVASCRIPT -->

<script>
$(document).ready(function(){
	$(".gallery a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
});

function openFacebook()
{	
	if($("#LoginViaFacebook"))
	{
		var pos	=	$("#LoginViaFacebook").offset();
		$("#loadingloginvia").show();
		$("#loadingloginvia").offset({ top: (pos.top-17), left: (pos.left)+12 });
		$("#loadingloginvia").css("z-index",104);
	}
	$.getJSON("<?php echo $ROOT?>/OpenId/FacebookUrl",{'login_type':'popup'},function(data){
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

function __openGoogle()
{
	var url	=	'https://accounts.google.com/o/oauth2/auth?';
	url		+=	'client_id=<?php echo $settings['google_client_id']?>';
	url		+=	'&redirect_uri=<?php echo $settings['google_callback']?>';
	url		+=	'&scope=https://www.google.com/m8/feeds/';
	url		+=	'&response_type=code';
	
	var load = window.open(url,'','scrollbars=no,menubar=no,height=400,width=400,resizable=yes,toolbar=no,location=no,status=no');
	if(load==null || typeof(load)=="undefined")
	{
		alert('Please turn off your popup blocker first.');
	}
}

function openGoogle()
{
	var pos	=	$("#LoginViaGoogle").offset();
	$("#loadingloginvia").show();
	$("#loadingloginvia").offset({ top: (pos.top-17), left: (pos.left)+12 });
	$("#loadingloginvia").css("z-index",104);
	
	var extensions = { 
	'openid.ns.ax' 				: 'http://openid.net/srv/ax/1.0',
	'openid.ax.mode' 			: 'fetch_request',
	'openid.ax.type.email'		: 'http://axschema.org/contact/email',
	'openid.ax.type.first'		: 'http://axschema.org/namePerson/first',
	'openid.ax.type.last'		: 'http://axschema.org/namePerson/last',
	'openid.ax.type.country'	: 'http://axschema.org/contact/country/home',
	'openid.ax.type.lang'		: 'http://axschema.org/pref/language',
	'openid.ax.type.web'		: 'http://axschema.org/contact/web/default',
	'openid.ax.required'		: 'email,first,last,country,lang,web',
	'openid.ns.oauth'			: 'http://specs.openid.net/extensions/oauth/1.0',
	'openid.oauth.consumer'		: '<?php echo $settings['site_url']?>',
	'openid.oauth.scope'		: 'http://www.google.com/m8/feeds/' ,
	'openid.ui.icon'			: 'true'
	} ;

	var googleOpener = popupManager.createPopupOpener(
	 { 'realm'				: '<?php echo $settings['site_url']?>',
		'opEndpoint'		: 'https://www.google.com/accounts/o8/ud',
		'returnToUrl'		: '<?php echo $settings['site_url']?>/OpenId/Google?login_type=popup',
		'onCloseHandler'	: function(){},
		'shouldEncodeUrls'	: true,
		'extensions'		: extensions
	});
	var load = window.open(googleOpener.genHtml(),'','scrollbars=no,menubar=no,height=400,width=800,resizable=yes,toolbar=no,location=no,status=no');
	if(load==null || typeof(load)=="undefined")
	{
		alert('Please turn off your popup blocker first.');
	}
	$("#loadingloginvia").hide();
}

function openTwitter() {
	var pos	=	$("#LoginViaTwitter").offset();
	$("#loadingloginvia").show();
	$("#loadingloginvia").offset({ top: (pos.top-17), left: (pos.left)+12 });
	$("#loadingloginvia").css("z-index",104);
	
	$.getJSON("<?php echo $ROOT?>/OpenId/TwitterUrl",function(data){
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

function openYahoo() {
	var pos	=	$("#LoginViaYahoo").offset();
	$("#loadingloginvia").show();
	$("#loadingloginvia").offset({ top: (pos.top-17), left: (pos.left)+12 });
	$("#loadingloginvia").css("z-index",104);
	
	$.getJSON("<?php echo $ROOT?>/OpenId/YahooUrl",function(data){
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
<style>
.tooltip2 {
	display:none;
	background:transparent url(<?php echo $this->webroot?>img/black_arrow2.png);
	font-size:12px;
	font-family:Arial, Helvetica, sans-serif;
	height:30px;
	width:110px;
	color:#fff;	
	padding-top: 5px;
	border:0px solid black;
	text-align:center;
	margin-top:-38px;
}
</style>
</head>

<body>
<?php $current_menu			=	empty($current_menu) ? NULL : $current_menu;?>
<?php $current_category_id	=	empty($current_category_id) ? "all_categories" : $current_category_id;?>
<?php $current_city			=	empty($current_city) ?  "all_cities" : $current_city;?>


<?php echo $this->element('header_menu',array("current_menu"=>$current_menu))?>
<!-- Body -->
<div class="line top10">
	<div class="body_all">
    	<!-- Kiri -->
        <div class="kiri size18 top-47"> 
        	<?php echo $this->requestAction("/Template/Category/{$current_category_id}/{$current_city}/".$this->params['controller'],array("return"))?>
            <div class="line">
            	<div class="line">
                 	<img src="<?php echo $settings['site_url']?>img/dealer_month.png" class="left10 position1 size82 bottom10">
                </div>
                <div class="line top-32">
                    <div class="back3 size82 left10 rounded2 top-10" style="padding:20px 0px 10px 0px;">
                        <div class="box1 left5 bottom10"></div>
                        <div class="style1 text12 white align_center bold">MAHIR MOTOR</div>
                        <div class="style1 text11 white align_center">Most active dealer</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Kiri -->
        <!-- Kanan -->
        <div class="kanan size18"> 
        	<div class="menu_kanan">
            	<div>POPULER</div>
            	<ul class="menu-right">		
                	<li><a href="<?php echo $settings['site_url']?>MotorMurah/100/all_cities/motor_harga_di_bawah_7_juta_yamaha.html">Yamaha <span>< 7 juta</span></a></li>
                    <li><a href="<?php echo $settings['site_url']?>MotorMurah/102/all_cities/motor_harga_di_bawah_7_juta_honda.html">Honda <span>< 7 juta</span></a></li>	
                    <li><a href="<?php echo $settings['site_url']?>MotorMurah/101/all_cities/motor_harga_di_bawah_7_juta_kawasaki.html">Kawasaki <span>< 7 juta</span></a></li>
                    <li><a href="<?php echo $settings['site_url']?>MotorMurah/99/all_cities/motor_harga_di_bawah_7_juta_suzuki.html">Suzuki <span>< 7 juta</span></a></li>
                    <li style="padding-bottom:10px;"><a href="<?php echo $settings['site_url']?>MotorMurah/103/all_cities/motor_harga_di_bawah_7_juta_bajaj.html">Bajaj <span>< 7 juta</span></a></li>
            	</ul>
            </div>
            <div class="line" style="margin-top:10px;">
            	<div class="menu_kanan" style="border:none;">
                    <div style="font-size:12px;line-height:10px;background:none;">
                    	<img src="img/icn_simulasi.png" style="float:left;margin:0 3px 0 -30px;" />
                    	SIMULASI KREDIT MOTOR
                    </div>
                    <ul class="menu-right" style="padding-bottom:10px;">			
                    	<span class="line1 style1 white text11">Perhitungan kredit ini hanya merupakan estimasi</span>
                        <span class="line1">
                        	<span class="style1 white bold text12 top5 line">Harga Motor</span>
                        	<input type="text" name="textfield" id="textfield" class="input2 style1 white text12 size90" />
                            <span class="style1 white bold text12 top3 line">Uang muka</span>
                        	<input type="text" name="textfield" id="textfield" class="input2 style1 white text12 size90" />
                            <span class="style1 white bold text12 top3 line">Administrasi</span>
                        	<input type="text" name="textfield" id="textfield" class="input2 style1 white text12 size90" />
                            <span class="style1 white bold text12 top3 line">Jangka waktu</span>
                        	<select id="abdul" class="rounded1 size95 style1 white text12 input2" style="cursor:pointer;">
                                <option>---</option>
                                <option>1 Tahun</option>
                                <option>2 Tahun</option>
                                <option>3 Tahun</option>
                                <option>4 Tahun</option>
                                <option>5 Tahun</option>
                            </select>
                            <span class="style1 white bold text12 top3 line">Bunga /tahun</span>
                            <input type="text" name="textfield" id="textfield" class="input2 style1 white text12 size90 bottom10" />         
                            <span class="top3"> 
                        		<input type="submit" name="button" id="button" value="HITUNG" class="tombol1 tengah" />
                            </span> 
                        </span>                        
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Kanan -->
        <!-- Tengah -->
        	<div class="tengah size63">
            	<?php echo $content_for_layout; ?>
            </div>
        <!-- End Tengah -->
    </div>
</div>
<!-- End Body -->

<?php echo $this->element('footer_menu')?>
<?php echo $this->element('sql_dump'); ?>
<img src='<?php echo $this->webroot ?>img/ajax-loader.gif' style="display:none" id="loadingloginvia"/>
</body>
</html>