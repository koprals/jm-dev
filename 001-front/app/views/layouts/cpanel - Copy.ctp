<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="google-site-verification" content="1Qe7hpPkk8Mr1RSExrrAnTRwjOwBI8jzi3v2b3Eay4Y" />
<link rel="icon" type="image/gif" href="<?php echo $this->webroot?>img/favico.gif">
<meta http-equiv="X-XRDS-Location" content="http://www.pure-technology.net/xrds.xml" />
<title><?php echo $title_for_layout; ?></title>
<!-- START BLOCK CSS -->
<?PHP echo $html->css('main_css')?>
<?php echo $html->css("prettyPhoto")?>
<!-- END BLOCK CSS -->


<!-- START BLOCK JAVASCRIPT -->
<?PHP echo $javascript->link('jquery.latest')?>
<?PHP echo $javascript->link('jquery.form')?>
<?php echo $javascript->link("jquery.prettyPhoto")?>
<?php echo $javascript->link("popuplibs")?>
<?php echo $javascript->link('jquery.tools.min');?>
<?php echo $javascript->link('jquery.corner.js');?>
<?php echo $javascript->link('curvycorners.src');?>
<script>
if($.browser.msie)
{
	var curvyCornersVerbose = false;
	addEvent(window, 'load', initCorners);
	function initCorners()
	{
		var settings = {
			tl: { radius: 4 },
			tr: { radius: 4 },
			bl: { radius: 4 },
			br: { radius: 4 },
			antiAlias: true
		}
		curvyCorners(settings, ".line4");
	}
}
</script>
<!-- END BLOCK JAVASCRIPT -->

<script>
$(document).ready(function(){
	$(".gallery a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
});

function openFacebook()
{	
	if($("#LoginViaFacebook").html() !== null)
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

<div id="wrap">
	<div id="main">
		<?php echo $this->element('header_menu')?>
        <div class="container">
            <div class="parent_container">
                <div class="line0" style="border:0px solid black;">
                    <div class="side_left">
                        <?php echo $this->requestAction("/Template/CpanelMenu/".$active_code,array("return"))?>
                    </div>
                    <div class="side_center2">
                        <?php echo $content_for_layout; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->element('footer_menu')?>
<?php echo $this->element('sql_dump'); ?>
<img src='<?php echo $this->webroot ?>img/ajax-loader.gif' style="display:none" id="loadingloginvia"/>

</body>
</html>