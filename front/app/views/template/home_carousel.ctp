<script>
$(document).ready(function(){
	//CAROUSEL YAMAHA
	$("#carousel_yamaha").load("<?php echo $settings['site_url']?>Home/Carousel/yamaha",{"category_name":"yamaha"});
	
	//CAROUSEL HONDA
	$("#carousel_honda").load("<?php echo $settings['site_url']?>Home/Carousel/honda",{"category_name":"honda"});
	
	//CAROUSEL SUZUKI
	$("#carousel_suzuki").load("<?php echo $settings['site_url']?>Home/Carousel",{"category_name":"suzuki"});
	
	//CAROUSEL KAWASAKI
	$("#carousel_kawasaki").load("<?php echo $settings['site_url']?>Home/Carousel",{"category_name":"kawasaki"});
	
});
</script>
<div class="line" style="border:0px solid black; margin-top:16px;">
    <!-- YAMAHA MURAH -->
    <div id="carousel_yamaha">
    </div>
    <!-- YAMAHA MURAH -->
    <!-- HONDA MURAH -->
    <div id="carousel_honda">
    </div>
    <!-- HONDA MURAH -->
    <!-- SUZUKI MURAH -->
    <div id="carousel_suzuki">
    </div>
    <!-- SUZUKI MURAH -->
    <!-- KAWASAKI MURAH -->
    <div id="carousel_kawasaki">
    </div>
    <!-- KAWASAKI MURAH -->
    </div>
</div>