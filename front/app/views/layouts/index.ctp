<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="1Qe7hpPkk8Mr1RSExrrAnTRwjOwBI8jzi3v2b3Eay4Y" />
<meta name="author" content="Aby Fajar" />
<meta http-equiv="cache-control" content="public" />
<meta http-equiv="expires" content="Mon, 22 Jul 2013 11:12:01 GMT" />
<meta http-equiv="X-XRDS-Location" content="<?php echo $settings['site_url']?>xrds.xml" />
<meta http-equiv="content-language" content="ll-cc" />
<meta name="google-site-verification" content="oJ-xxOvJ__DGdYIScBaG3-ElEZxyB3WPW53PQgy_yio" />
<meta name="title" content="<?php echo $title_for_layout?>" />
<meta name="description" content="<?php echo $site_description?>" />
<meta name="keywords" content="<?php echo $site_keywords?>" />

<link rel="shortcut icon" href="<?php echo $this->webroot?>img/favicon.ico" />
<?php if($this->params['controller']=="Iklan" && $this->params['action']=="Detail"):?>
<meta property="og:title" content="<?php echo $title_for_layout?>" />
<meta property="og:description" content="<?php echo $site_description?>" />
<meta property="og:image" content="<?php echo $settings['showimages_url']."/ProductImage_127_80_".$product_img_id.".jpg?code=".$product_img_id."&prefix=_127_80&content=ProductImage&w=127&h=80"?>" />
<?php elseif($this->params['controller']=="News" && $this->params['action']=="Detail"):?>
<meta property="og:title" content="<?php echo $title_for_layout?>" />
<meta property="og:description" content="<?php echo $site_description?>" />
<meta property="og:image" content="<?php echo $settings['showimages_url']."/News_127_80_".$news_img_id.".jpg?code=".$news_img_id."&prefix=_127_80&content=News&w=127&h=80"?>" />
<?php endif;?>
<title><?php echo $title_for_layout; ?></title>
<script type="text/javascript" src="http://cdn.innity.net/admanager.js"></script>


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

<script type="text/javascript">
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


<style type="text/css">
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
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=204483222952385";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php $current_menu			=	empty($current_menu) ? NULL : $current_menu;?>
<?php $current_category_id	=	empty($current_category_id) ? "all_categories" : $current_category_id;?>
<?php $current_city			=	empty($current_city) ?  "all_cities" : $current_city;?>


<?php echo $this->element('header_menu',array("current_menu"=>$current_menu))?>
<!-- Body -->
<div class="line top10">
	<div class="body_all">
    	<!-- Kiri -->
        <div class="kiri size18 top-47" style="border:0px solid black;"> 
        	
			<!-- TREE CATEGORY -->
            <?php //var_dump($current_category_id,$current_city,$this->params['controller']);?>
	     <?php echo $this->requestAction("/Template/Category/{$current_category_id}/{$current_city}/".$this->params['controller'],array("return"))?>
            <!-- TREE CATEGORY -->
            
            <!-- MEMBER OF THE MONTH
            <?php //echo $this->element('member_of_the_month',array('cache' => array('time' => '+1 day')))?>
            MEMBER OF THE MONTH -->
            
            <!--  WHATS NEW -->
            <?php echo $this->element('whats_new',array('cache' => array('time' => '1 hours')))?>
            <!--  WHATS NEW -->
            
            <!--  Testimonial -->
            <?php echo $this->element('testimonial',array('cache' => array('time' => '+1 day')))?>
            <!--  Testimonial -->
            
            <!-- FACEBOOK -->
            <div class="kiri top10" style="border:0px solid #cccccc;width:100px;float:left; margin-left:-20px;">
            	<div class="fb-like-box" data-href="http://www.facebook.com/JualanMotorCom" data-width="100" data-height="326" data-show-faces="true" data-stream="false" data-header="false"></div>
            </div>
            <!-- FACEBOOK -->
        </div>
        <!-- End Kiri -->
        <!-- Kanan -->
        <div class="kanan size18">
        
	    	 <!-- MOGE -->
            <?php echo $this->element('moge',array('cache' => array('time' => '10 minutes')))?>
            <!-- MOGE -->
            
            <!-- KLASIK -->
            <?php echo $this->element('klasik',array('cache' => array('time' => '10 minutes')))?>
            <!-- KLASIK -->
			
			<!-- NEWS -->
            <?php echo $this->element('news')?>
			<!-- NEWS -->
			
            <!--  Simulasi -->
            <?php if($this->params["controller"]."/".$this->params["action"] != "Iklan/SimulasiKredit"):?>
            	<?php echo $this->element('simulasi_kredit',array('cache' => array('time' => '+7 day')))?>
            <?php endif;?>
            <!--  Simulasi -->
            
      </div>
      <!-- End Kanan -->
      <!-- Tengah -->
      <div class="tengah size63" style="border:0px solid black;">
	  	<?php echo $content_for_layout; ?>
      </div>
      <!-- End Tengah -->
    </div>
</div>
<!-- End Body -->

<?php echo $this->element('footer_menu',array('cache' => array('time' => '+7 day')))?>
<?php echo $this->element('sql_dump'); ?>
<img src='<?php echo $this->webroot ?>img/ajax-loader.gif' style="display:none" id="loadingloginvia" alt="ajax-loader.gif"/>
</body>
</html>