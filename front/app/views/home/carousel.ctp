<div class="kiri size100">
	<?php if(!empty($data)):?>
    <?php $test = 1;?>
    <div class="line kiri top20" style="border:0px solid black;">
    	<a href="<?php echo $settings['site_url']?>MotorMurah/<?php echo $data[0]['Category']['parent_id']?>/all_cities/motor_harga_di_bawah_7_juta_<?php echo $category_name?>.html" class="style1 black1 bold text14 normal" style="border:0px solid black;"><?php echo strtoupper($category_name)?></a>
	</div>
    <div class="flowpanes" style="border:0px solid black;" id="<?php echo $category_name?>_carousel">
        <div class="items">
            <div class="aby" style=" border:0px solid black; display:block; float:left; width:700px;">
                <!-- START LOOPING YAMAHA -->
                <?php $i=0;?>
				
                <?php foreach($data as $data):?>
				<?php $class	=	"product_tiny";?>
				<?php
					if(!empty($data["AdsRequest"]))
					{
						foreach($data["AdsRequest"] as $AdsRequest)
						{
							if($AdsRequest["ads_type_id"]=="2")
								$class	=	"product_new";
						}
					}
				?>
				
                <?php $i++;?>
                <?php $name			=	$text->truncate(ucwords($data["Parent"]["name"]." ".$data["Category"]["name"]),20,array('ending'=>""));?>
                <?php $price		=	$number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>
                <?php $km			=	($data['Product']['condition_id']==1) ? "Km : 0(baru)" : (empty($data['Product']['kilometer']) ? "Km: Tdk ada informasi" : $number->format($data['Product']['kilometer'],array("thousands"=>".","before"=>"Km ","places"=>null,"after"=>null)))?>
                <?php $ico			=	($data['Product']['data_type']==1) ? $this->webroot."img/seller_ico.gif" : $this->webroot."img/dealer_ico.gif";?>
                
                <div class="<?php echo $class?>">
                    <div class="gambar_tiny">
                        <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" title="<?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]?>"><img src="<?php echo $settings['showimages_url']."/ProductImage_127_80_".$data['ProductImage']['id'].".jpg?code=".$data['ProductImage']['id']."&prefix=_127_80&content=ProductImage&w=127&h=80"?>" border="0" alt="<?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]?>"/></a>
                    </div>
                    <div class="descthumb_tiny">
                        <div class="style1 text11 black1 bold"><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]?>" class="normal style1 text11 black1 bold"><?php echo $name?></a></div>
                        <div class="style1 text13 red2 bold"><?php echo $price?></div>
                        <div class="style1 text11 grey2">Th <?php echo $data['Product']['thn_pembuatan']?></div>
                        <div class="style1 text11 grey2"><?php echo $km?></div>
                        <div class="kiri style1 text11 grey2 size80"><?php echo $data['Province']['name']?></div>
                        <div class="kanan style1 text11 grey2 size15">
                        <a href="javascript:void(0)" title="<?php echo $data['Product']['contact_name']?>" rel="seller"><img src="<?php echo $ico?>" border="0" style="vertical-align:middle; margin-top:-3px;" alt="icon uploader"/></a></div>
                    </div>
                </div>
                <?php if($i%4==0):?>
                <?php $test++;?>
                </div>
                    <?php if($test<=$page):?>
                    <div class="aby" style="border:0px solid black; display:block; float:left; width:700px;">
                    <?php endif;?>
                <?php endif;?>
                
                <?php endforeach;?>
                <!-- END LOOPING YAMAHA-->
        </div>
    </div>
</div>
<div class="kiri size30 top10" style="border:0px solid black;"><a href="<?php echo $settings['site_url']?>DaftarMotor/<?php echo $data['Category']['parent_id']?>/all_cities/motor_<?php echo $category_name?>.html" class="style1 red bold text12 normal">Selengkapnya</a></div>
<?php if($page>1):?>
<div class="kanan size50 top5" style="text-align:right;border:0px solid black;">
    <div class="style1 text11 grey2 bold"><?php echo ucfirst($category_name)?> <span class="red2" id="page_crousel_<?php echo $category_name?>" >1</span> of <?php echo $page?>
        <a href="javascript:void(0)" id="prev_<?php echo $category_name?>"><img src="<?php echo $this->webroot?>img/prev.jpg" border="0" style=" vertical-align:middle;" alt="icon prev"/></a>
        <a  href="javascript:void(0)" id="next_<?php echo $category_name?>"><img src="<?php echo $this->webroot?>img/next.jpg" border="0" style=" vertical-align:middle;" alt="icon next"/></a>
    </div>
</div>
<?php endif;?>
<script>
$(function() {
	$("#<?php echo $category_name?>_carousel").scrollable({ 
		circular: true, 
		mousewheel: false,
		next:'#next_<?php echo $category_name?>',
		prev:'#prev_<?php echo $category_name?>',
		speed:800,
		onSeek:function(){
			
			var page	=	parseInt(this.getIndex()) +1;
			
			$("#page_crousel_<?php echo $category_name?>").html(page);
		}
	}).
	autoscroll({ 
		autoplay: false,
		interval: 7000 
	});
});
</script>
<?php endif;?>
