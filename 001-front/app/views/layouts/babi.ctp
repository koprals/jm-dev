<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="1Qe7hpPkk8Mr1RSExrrAnTRwjOwBI8jzi3v2b3Eay4Y" />
<link rel="shortcut icon" href="<?php echo $this->webroot?>img/favicon.ico">
<meta http-equiv="X-XRDS-Location" content="<?php echo $settings['site_url']?>xrds.xml" />
<meta name="google-site-verification" content="oJ-xxOvJ__DGdYIScBaG3-ElEZxyB3WPW53PQgy_yio" />
<meta name="title" content="<?php echo $title_for_layout?>" />
<meta name="description" content="<?php echo $site_description?>" />
<meta name="keywords" content="<?php echo $site_keywords?>" />
<?php if($this->params['controller']=="Iklan" && $this->params['action']=="Detail"):?>
<meta property="og:title" content="<?php echo $title_for_layout?>" />
<meta property="og:description" content="<?php echo $site_description?>" />
<meta property="og:image" content="<?php echo $settings['showimages_url']."/ProductImage_127_80_".$product_img_id.".jpg?code=".$product_img_id."&prefix=_127_80&content=ProductImage&w=127&h=80"?>" />
<?php endif;?>
<title><?php echo $title_for_layout; ?></title>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28726499-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();


</script>

<!-- START BLOCK CSS -->
<?PHP echo $html->css('style_JM')?>
<?PHP echo $html->css('edit_aby')?>
<?php echo $html->css("prettyPhoto")?>
<!-- END BLOCK CSS -->


<!-- START BLOCK JAVASCRIPT -->
<?php echo $javascript->link("jquery.latest");?>
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

</body>
</html>