<?php echo $javascript->link('jquery.tools.min');?>
<script>
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
		height: "25px"
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
		border:0px solid black;
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
<?php echo $this->requestAction("/Template/DaftarKota/{$current_category_id}/{$current_city}",array("return"))?>

<div class="line" style="border:0px solid black; margin-top:16px;">
	<!-- PAGING -->
    <div style="float:none; margin:auto; width:45%; border:0px solid black; margin-bottom:10px;">
    	<div class="style1 text11 grey2 bold">Page <span class="red2">1</span> of 400000
        	<a href=""><img src="<?php echo $this->webroot?>img/prev.jpg" border="0" style=" vertical-align:middle;"/></a>
        	<a href=""><img src="<?php echo $this->webroot?>img/next.jpg" border="0" style=" vertical-align:middle;"/></a>
            Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="7"/>
        </div>
    </div>
    <!-- END PAGING -->
    <!-- START LOOPING NEW PRODUCT -->
    <div style="margin-right:16px;" class="product" onmouseover="Over('#caption_1','#view_detail_1');" onmouseout="Out('#caption_1','#view_detail_1')">
        <div class="gambar">
            <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product.gif" width="253" height="133" border="0" style="margin:auto; display:block;"/></a>
        </div>
        <div style="position: absolute; top:12px; left:100px; display:none" id="view_detail_1">
        <a href=""><img src="<?php echo $this->webroot?>img/view_detail.png"  border="0"></a>
        </div>
        <div class="line" style="height:20px;">&nbsp;</div>
        <div class="descthumb">
        	<div class="line" style="position:relative;">
               <div style="border:0px solid black;position:absolute;bottom:-1px;width:100%;background-color:white;display:block;height:25px; overflow:hidden; color:#000;" id="caption_1">
               		<div class="left size40 top3" style="border:0px solid black;">
                    	<div class="style1 text13 black1 bold">Honda Megapro CW</div>
                        <div class="style1 text11 grey2 top5">Th : 2003</div>
                        <div class="style1 text11 grey2">KM : 200</div>
                        <div class="style1 text11 grey2">Bogor</div>
                    </div>
                    <div class="kanan size40 top3">
                        <div class="style1 text13 red2 bold" style="text-align:right;">Rp 999.999.000</div>
                        <div class="line top5">
                            <div class="kiri right5 top5"><a href="" title="Klik disini untuk memberikan komentar."><img src="<?php echo $this->webroot?>img/comment_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">1700</div>
                            <div class="kiri right5 top5"><a href="" title="Klik disini apabila anda menyukai iklan ini."><img src="<?php echo $this->webroot?>img/love_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">26</div>
                            <div class="kanan"><img src="<?php echo $this->webroot?>img/seller_ico.gif"/></div>
                            <div class="kanan top5">
                            <a href="ymsgr:sendIM?aby_labdb" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=aby_labdb&m=g&t=1"></a>
                            </div>
                        </div>
                    </div>
            	</div>
            </div>
        </div>
        <div class="line" style="height:2px;">&nbsp;</div>
    </div>
    <div class="product" onmouseover="Over('#caption_2','#view_detail_2');" onmouseout="Out('#caption_2','#view_detail_2')">
        <div class="gambar">
            <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product.gif" width="282" height="153" border="0"/></a>
        </div>
        <div style="position: absolute; top:12px; left:100px; display:none" id="view_detail_2">
        <a href=""><img src="<?php echo $this->webroot?>img/view_detail.png"  border="0"></a>
        </div>
        <div class="line" style="height:20px;">&nbsp;</div>
        <div class="descthumb">
        	<div class="line" style="position:relative;">
               <div style="border:0px solid black;position:absolute;bottom:-1px;width:100%;background-color:white;display:block;height:25px; overflow:hidden; color:#000;" id="caption_2">
               		<div class="left size40 top3" style="border:0px solid black;">
                    	<div class="style1 text13 black1 bold">Honda Megapro CW</div>
                        <div class="style1 text11 grey2 top5">Th 2003</div>
                        <div class="style1 text11 grey2">200 Km</div>
                        <div class="style1 text11 grey2">Bogor</div>
                    </div>
                    <div class="kanan size40 top3">
                        <div class="style1 text13 red2 bold" style="text-align:right;">Rp 999.999.000</div>
                        <div class="line top5">
                            <div class="kiri right5 top5"><a href="" title="Klik disini untuk memberikan komentar."><img src="<?php echo $this->webroot?>img/comment_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">1700</div>
                            <div class="kiri right5 top5"><a href="" title="Klik disini apabila anda menyukai iklan ini."><img src="<?php echo $this->webroot?>img/love_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">26</div>
                            <div class="kanan"><img src="<?php echo $this->webroot?>img/seller_ico.gif"/></div>
                            <div class="kanan top5">
                            <a href="ymsgr:sendIM?aby_labdb" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=aby_labdb&m=g&t=1"></a>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </div>
        <div class="line" style="height:2px;">&nbsp;</div>
    </div>
    <div style="margin-right:16px;" class="product" onmouseover="Over('#caption_3','#view_detail_3');" onmouseout="Out('#caption_3','#view_detail_3')">
        <div class="gambar">
            <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product.gif" width="283" height="153" border="0"/></a>
        </div>
        <div style="position: absolute; top:12px; left:100px; display:none" id="view_detail_3">
        <a href=""><img src="<?php echo $this->webroot?>img/view_detail.png"  border="0"></a>
        </div>
        <div class="line" style="height:20px;">&nbsp;</div>
        <div class="descthumb">
        	<div class="line" style="position:relative;">
               <div style="border:0px solid black;position:absolute;bottom:-1px;width:100%;background-color:white;display:block;height:25px; overflow:hidden; color:#000;" id="caption_3">
               		<div class="left size40 top3" style="border:0px solid black;">
                    	<div class="style1 text13 black1 bold">Honda Megapro CW</div>
                        <div class="style1 text11 grey2 top5">Th 2003</div>
                        <div class="style1 text11 grey2">200 Km</div>
                        <div class="style1 text11 grey2">Bogor</div>
                    </div>
                    <div class="kanan size40 top3">
                        <div class="style1 text13 red2 bold" style="text-align:right;">Rp 999.999.000</div>
                        <div class="line top5">
                            <div class="kiri right5 top5"><a href="" title="Klik disini untuk memberikan komentar."><img src="<?php echo $this->webroot?>img/comment_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">1700</div>
                            <div class="kiri right5 top5"><a href="" title="Klik disini apabila anda menyukai iklan ini."><img src="<?php echo $this->webroot?>img/love_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">26</div>
                            <div class="kanan"><img src="<?php echo $this->webroot?>img/seller_ico.gif"/></div>
                            <div class="kanan top5">
                            <a href="ymsgr:sendIM?aby_labdb" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=aby_labdb&m=g&t=1"></a>
                            </div>
                        </div>
                    </div>
               	</div>
            </div>
        </div>
        <div class="line" style="height:2px;">&nbsp;</div>
    </div>
    
    <div class="product" onmouseover="Over('#caption_4','#view_detail_4');" onmouseout="Out('#caption_4','#view_detail_4')">
        <div class="gambar">
            <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product.gif" width="270" height="153" border="0"/></a>
        </div>
        <div style="position: absolute; top:12px; left:100px; display:none" id="view_detail_4">
        <a href=""><img src="<?php echo $this->webroot?>img/view_detail.png"  border="0"></a>
        </div>
        <div class="line" style="height:20px;">&nbsp;</div>
        <div class="descthumb">
        	<div class="line" style="position:relative;">
               <div style="border:0px solid black;position:absolute;bottom:-1px;width:100%;background-color:white;display:block;height:25px; overflow:hidden; color:#000;" id="caption_4">
               		<div class="left size40 top3" style="border:0px solid black;">
                    	<div class="style1 text13 black1 bold">Honda Megapro CW</div>
                        <div class="style1 text11 grey2 top5">Th 2003</div>
                        <div class="style1 text11 grey2">200 Km</div>
                        <div class="style1 text11 grey2">Bogor</div>
                    </div>
                    <div class="kanan size40 top3">
                        <div class="style1 text13 red2 bold" style="text-align:right;">Rp 999.999.000</div>
                        <div class="line top5">
                            <div class="kiri right5 top5"><a href="" title="Klik disini untuk memberikan komentar."><img src="<?php echo $this->webroot?>img/comment_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">1700</div>
                            <div class="kiri right5 top5"><a href="" title="Klik disini apabila anda menyukai iklan ini."><img src="<?php echo $this->webroot?>img/love_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">26</div>
                            <div class="kanan"><img src="<?php echo $this->webroot?>img/seller_ico.gif"/></div>
                            <div class="kanan top5">
                            <a href="ymsgr:sendIM?aby_labdb" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=aby_labdb&m=g&t=1"></a>
                            </div>
                        </div>
                    </div>
               	</div>
            </div>
        </div>
        <div class="line" style="height:2px;">&nbsp;</div>
    </div>
    
    <div style="margin-right:16px;" class="product" onmouseover="Over('#caption_5','#view_detail_5');" onmouseout="Out('#caption_5','#view_detail_5')">
        <div class="gambar">
            <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product.gif" width="283" height="153" border="0"/></a>
        </div>
        <div style="position: absolute; top:12px; left:100px; display:none" id="view_detail_5">
        <a href=""><img src="<?php echo $this->webroot?>img/view_detail.png"  border="0"></a>
        </div>
        <div class="line" style="height:20px;">&nbsp;</div>
        <div class="descthumb">
        	<div class="line" style="position:relative;">
               <div style="border:0px solid black;position:absolute;bottom:-1px;width:100%;background-color:white;display:block;height:25px; overflow:hidden; color:#000;" id="caption_5">
               		<div class="left size40 top3" style="border:0px solid black;">
                    	<div class="style1 text13 black1 bold">Honda Megapro CW</div>
                        <div class="style1 text11 grey2 top5">Th 2003</div>
                        <div class="style1 text11 grey2">200 Km</div>
                        <div class="style1 text11 grey2">Bogor</div>
                    </div>
                    <div class="kanan size40 top3">
                        <div class="style1 text13 red2 bold" style="text-align:right;">Rp 999.999.000</div>
                        <div class="line top5">
                            <div class="kiri right5 top5"><a href="" title="Klik disini untuk memberikan komentar."><img src="<?php echo $this->webroot?>img/comment_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">1700</div>
                            <div class="kiri right5 top5"><a href="" title="Klik disini apabila anda menyukai iklan ini."><img src="<?php echo $this->webroot?>img/love_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">26</div>
                            <div class="kanan"><img src="<?php echo $this->webroot?>img/seller_ico.gif"/></div>
                            <div class="kanan top5">
                            <a href="ymsgr:sendIM?aby_labdb" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=aby_labdb&m=g&t=1"></a>
                            </div>
                        </div>
                    </div>
               	</div>
            </div>
        </div>
        <div class="line" style="height:2px;">&nbsp;</div>
    </div>
    
    <div class="product" onmouseover="Over('#caption_6','#view_detail_6');" onmouseout="Out('#caption_6','#view_detail_6')">
        <div class="gambar">
            <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product.gif" width="270" height="153" border="0"/></a>
        </div>
        <div style="position: absolute; top:12px; left:100px; display:none" id="view_detail_6">
        <a href=""><img src="<?php echo $this->webroot?>img/view_detail.png"  border="0"></a>
        </div>
        <div class="line" style="height:20px;">&nbsp;</div>
        <div class="descthumb">
        	<div class="line" style="position:relative;">
               <div style="border:0px solid black;position:absolute;bottom:-1px;width:100%;background-color:white;display:block;height:25px; overflow:hidden; color:#000;" id="caption_6">
               		<div class="left size40 top3" style="border:0px solid black;">
                    	<div class="style1 text13 black1 bold">Honda Megapro CW</div>
                        <div class="style1 text11 grey2 top5">Th 2003</div>
                        <div class="style1 text11 grey2">200 Km</div>
                        <div class="style1 text11 grey2">Bogor</div>
                    </div>
                    <div class="kanan size40 top3">
                        <div class="style1 text15 red2 bold" style="text-align:right;">Rp 999.999.000</div>
                        <div class="line top5">
                            <div class="kiri right5 top5"><a href="" title="Klik disini untuk memberikan komentar."><img src="<?php echo $this->webroot?>img/comment_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">1700</div>
                            <div class="kiri right5 top5"><a href="" title="Klik disini apabila anda menyukai iklan ini."><img src="<?php echo $this->webroot?>img/love_ico.gif" border="0"/></a></div>
                            <div class="kiri style1 text11 grey2 right5 top5">26</div>
                            <div class="kanan"><img src="<?php echo $this->webroot?>img/seller_ico.gif"/></div>
                            <div class="kanan top5">
                            <a href="ymsgr:sendIM?aby_labdb" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=aby_labdb&m=g&t=1"></a>
                            </div>
                        </div>
                    </div>
               	</div>
            </div>
        </div>
        <div class="line" style="height:2px;">&nbsp;</div>
    </div>
    <!-- END LOOPING NEW PRODUCT -->
    <!-- PAGING -->
    <div style="float:none; margin:auto; width:45%; border:0px solid black; margin-bottom:10px;">
    	<div class="style1 text11 grey2 bold">Page <span class="red2">1</span> of 400000
        	<a href=""><img src="<?php echo $this->webroot?>img/prev.jpg" border="0" style=" vertical-align:middle;"/></a>
        	<a href=""><img src="<?php echo $this->webroot?>img/next.jpg" border="0" style=" vertical-align:middle;"/></a>
            Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="7"/>
        </div>
    </div>
    <!-- END PAGING -->
</div>


<div class="line" style="border:0px solid black; margin-top:16px;">

	<!-- YAMAHA MURAH -->
    <div class="line kiri style1 black bold text14">YAMAHA MURAH</div>
    <div class="flowpanes" style="border:0px solid black;" id="yamaha_carousel">
    	<div class="items">
        
        	<div class="aby" style=" border:0px solid black; display:block; float:left; width:700px;">
                <!-- START LOOPING YAMAHA -->
                <?php for($i=1;$i<8;$i++):?>
                <div class="product_tiny">
                    <div class="gambar_tiny">
                        <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
                    </div>
                    <div class="descthumb_tiny">
                        <div class="style1 text11 black1 bold">Honda Megapro CW - <?php echo $i?></div>
                        <div class="style1 text13 red2 bold">Rp 999.999</div>
                        <div class="style1 text11 grey2">Th 2003</div>
                        <div class="style1 text11 grey2">KM 1000</div>
                        <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                        <div class="kanan style1 text11 grey2 size15">
                        <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
                    </div>
                </div>
                <?php if($i%4==0):?>
                </div>
                <div class="aby" style=" border:0px solid black; display:block; float:left; width:700px;">
                <?php endif;?>
                <?php endfor;?>
                <!-- END LOOPING YAMAHA-->
            </div>
            
    	</div>
    </div>
    <div class="kanan size50 top5" style="text-align:right;">
    	<div class="style1 text11 grey2 bold">Honda <span class="red2">1</span> of 400000
        	<a href="javascript:void(0)" id="prev"><img src="<?php echo $this->webroot?>img/prev.jpg" border="0" style=" vertical-align:middle;"/></a>
        	<a  href="javascript:void(0)" id="next"><img src="<?php echo $this->webroot?>img/next.jpg" border="0" style=" vertical-align:middle;"/></a>
    	</div>
    </div>
    <!-- YAMAHA MURAH -->
    
    
    <!-- HONDA MURAH -->
    <div class="line kiri style1 black bold text14">HONDA MURAH</div>
    <div class="kiri size100 top5" style="border:0px solid black;">
    	<!-- START LOOPING HONDA -->
    	<div class="product_tiny">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <div class="product_tiny">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <div class="product_tiny">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <div class="product_tiny" style="margin-right:0px;">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <!-- END LOOPING HONDA-->
    </div>
    <div class="kanan size50 top5" style="text-align:right;">
    	<div class="style1 text11 grey2 bold">Honda <span class="red2">1</span> of 400000
        	<a href=""><img src="<?php echo $this->webroot?>img/prev.jpg" border="0" style=" vertical-align:middle;"/></a>
        	<a href=""><img src="<?php echo $this->webroot?>img/next.jpg" border="0" style=" vertical-align:middle;"/></a>
    	</div>
    </div>
    <!-- HONDA MURAH -->
    
    
    <!-- SUZUKI MURAH -->
    <div class="line kiri style1 black bold text14">SUZUKI MURAH</div>
    <div class="kiri size100 top5" style="border:0px solid black;">
    	<!-- START LOOPING SUZUKI -->
    	<div class="product_tiny">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <div class="product_tiny">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <div class="product_tiny">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <div class="product_tiny" style="margin-right:0px;">
        	<div class="gambar_tiny">
                <a href=""><img src="<?php echo $settings['site_url']?>img/sample_product_tiny.gif" style="width:100%" border="0"/></a>
            </div>
            <div class="descthumb_tiny">
            	<div class="style1 text11 black1 bold">Honda Megapro CW</div>
                <div class="style1 text13 red2 bold">Rp 999.999</div>
                <div class="style1 text11 grey2">Th 2003</div>
                <div class="style1 text11 grey2">KM 1000</div>
                <div class="kiri style1 text11 grey2 size50">Irian Jaya</div>
                <div class="kanan style1 text11 grey2 size15">
                <a href=""><img src="<?php echo $settings['site_url']?>img/dealer_ico.gif" border="0" style="vertical-align:middle; margin-top:-3px;"/></a></div>
            </div>
        </div>
        <!-- END LOOPING SUZUKI-->
    </div>
    <div class="kanan size50 top5" style="text-align:right;">
    	<div class="style1 text11 grey2 bold">Suzuki <span class="red2">1</span> of 400000
        	<a href=""><img src="<?php echo $this->webroot?>img/prev.jpg" border="0" style=" vertical-align:middle;"/></a>
        	<a href=""><img src="<?php echo $this->webroot?>img/next.jpg" border="0" style=" vertical-align:middle;"/></a>
    	</div>
    </div>
    <!-- SUZUKI MURAH -->
</div>

<script>
// wait until document is fully scriptable
$(function() {
	$("#yamaha_carousel").scrollable({ 
		circular: true, 
		mousewheel: false,
		next:'#next',
		prev:'#prev',
		speed:800,
		onSeek:function(){
			var page =	this.getIndex();
		}
	}).
	autoscroll({ 
		autoplay: true,
		interval: 5000 
	});
	
});
</script>