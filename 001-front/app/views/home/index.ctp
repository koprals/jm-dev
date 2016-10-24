<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.tools.min")?>
<script>
$(document).ready(function(){
});
function Over(selector,viewdetail)
{
	//$(selector).slideToggle(700);
	$("#test").empty();
	
	$("#test").html($(selector).html());
	
	var h	=	$("#test").height();
	$(selector).stop().animate({ 
		height: h
    }, function() {
		$(viewdetail).fadeIn(100);
	}
);
}
function Out(selector,viewdetail)
{
	//$(selector).hide("slide", { direction: "down" }, 1000);
	
	$(selector).stop().animate({ 
		height: "22px"
    }, function() {
		$(viewdetail).fadeOut(100);
	});
	
}
</script>

<style>
	/* override the root element to enable scrolling */
	.flowpanes {
		position:relative;
		overflow:hidden;
		clear:both;
		height:173px;
		border:1px solid black;
		float:left;
		width:100%;
		margin-top:5px;
	}
	/* our additional wrapper element for the items */
	.flowpanes .items {
		width:20000em;
		position:absolute;
		clear:both;
		margin:0;
		padding:0;
		
	}
</style>

<div id="test" style="display:none;"></div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- banner top -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-1940919734939265"
     data-ad-slot="5089954832"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<?php echo $this->element('home_daftar_kota',array('cache' => array('time' => '+7 day')))?>
<?php echo $this->element('home_premium_content',array('cache' => false))?>

<div class="line" style="border:0px solid black; margin-top:16px;">
    <!-- YAMAHA MURAH -->
    <div id="carousel_yamaha">
    	<?php echo $this->element('home_carousel',array('cache' => array('time' => '10 minutes','key'=>'yamaha'),"category_name"=>"yamaha"))?>
    </div>
    <!-- YAMAHA MURAH -->
    <!-- HONDA MURAH -->
    <div id="carousel_honda">
    	<?php echo $this->element('home_carousel',array('cache' => array('time' => '10 minutes','key'=>'honda'),"category_name"=>"honda"))?>
    </div>
    <!-- HONDA MURAH -->
    <!-- SUZUKI MURAH -->
    <div id="carousel_suzuki">
    	<?php echo $this->element('home_carousel',array('cache' => array('time' => '10 minutes','key'=>'suzuki'),"category_name"=>"suzuki"))?>
    </div>
    <!-- SUZUKI MURAH -->
    <!-- KAWASAKI MURAH -->
    <div id="carousel_kawasaki">
    	<?php echo $this->element('home_carousel',array('cache' => array('time' => '10 minutes','key'=>'kawasaki'),"category_name"=>"kawasaki"))?>
    </div>
    <!-- KAWASAKI MURAH -->
	<!-- BAJAJ MURAH -->
    <div id="carousel_bajaj">
    	<?php echo $this->element('home_carousel',array('cache' => array('time' => '10 minutes','key'=>'bajaj'),"category_name"=>"bajaj"))?>
    </div>
    <!-- BAJAJ MURAH -->
	<!-- PIAGIO MURAH -->
    <div id="carousel_piaggio">
    	<?php echo $this->element('home_carousel',array('cache' => array('time' => '10 minutes','key'=>'piaggio'),"category_name"=>"piaggio"))?>
    </div>
    <!-- PIAGIO MURAH -->
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>



