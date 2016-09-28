<?php if(!empty($new)):?>

<div class="line top40">
    <div class="kiri size50 style1 grey2 text11" style="border:0px solid black">
        <span class="right10"><img src="<?php echo $this->webroot?>img/dealer_ico.gif" style="vertical-align:middle" class="right5" alt="icon dealer"/> Dealer</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/seller_ico.gif" style="vertical-align:middle" class="right5" alt="icon perorangan"/> Perorangan</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="vertical-align:middle" alt="icon dilihat"/> Dilihat</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/comment_ico.gif" style="vertical-align:middle" alt="icon komentar"/> Komentar</span>
    </div>
    <div class="kanan size20" style="border:0px solid black; text-align:right;">
        <a href="<?php echo $settings['site_url']?>DaftarMotor/all_categories/all_cities/all-categories_semua-kota.html" class="style1 red text12 bold normal">Selengkapnya <img src="<?php echo $this->webroot?>img/admin_arrowright.gif" style="vertical-align:middle; border:none; margin-left:5px;" alt="selengkapnya"/></a>
    </div>
</div>
<div class="header_premium">
	<div class="red_carpet">PREMIUM</div>
	<div class="sudut_cp"></div>
</div>
<div class="line top10" style="border:0px solid black">
    <!-- START LOOPING NEW PRODUCT -->
	<?php $count=0;?>
	<?php foreach($new as $new):?>
	<?php $count++;?>
	<?php $style		=	($count%2!=0) ? 'style="margin-right:16px;"' : ""?>
	<?php $product_id	=	$new["Product"]["id"]?>
	<?php $name			=	$text->truncate(ucwords($new["Parent"]["name"]." ".$new["Category"]["name"]),30,array('ending'=>""));?>
	<?php $price		=	$number->format($new['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>
	<?php $km			=	($new['Product']['condition_id']==1) ? "Km : 0(baru)" : (empty($new['Product']['kilometer']) ? "Km: Tdk ada informasi" : $number->format($new['Product']['kilometer'],array("thousands"=>".","before"=>"Km ","places"=>null,"after"=>null)))?>
	
	<?php $ym			=	(!empty($new["Product"]["ym"])) ? explode("@",$new["Product"]["ym"]) : "";?>
	<?php $ym			=	(is_array($ym)) ? $ym[0] : "";?>
	<?php $ico			=	($new['Product']['data_type']==1) ? $this->webroot."img/seller_ico.gif" : $this->webroot."img/dealer_ico.gif";?>
	<?php $view			=	$number->format($new['Product']['view'],array("thousands"=>".","before"=>NULL,"places"=>null,"after"=>null))?>
	<?php $comment			=	$number->format($new['Product']['comment'],array("thousands"=>".","before"=>NULL,"places"=>null,"after"=>null))?>
	
	<div class="product_premium" <?php echo $style?> onmouseover="Over('#caption_<?php echo $product_id?>','#view_detail_<?php echo $product_id?>');" onmouseout="Out('#caption_<?php echo $product_id?>','#view_detail_<?php echo $product_id?>')" >
        <div class="gambar_premium">
            <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $product_id."/".$general->seoUrl("motor dijual ".$new["Parent"]["name"]." ".$new["Category"]["name"]." (".$new["Product"]["thn_pembuatan"].") ".$new["ProvinceGroup"]["name"]).".html"?>">
				<img src="<?php echo $settings['showimages_url']."/ProductImage_282_153_".$new['ProductImage']['id'].".jpg?code=".$new['ProductImage']['id']."&prefix=_282_153&content=ProductImage&w=282&h=153"?>" border="0" alt="<?php echo $new["Parent"]["name"]." ".$new["Category"]["name"]." (".$new["Product"]["thn_pembuatan"].") ".$new["ProvinceGroup"]["name"]?>"/>
			</a>
        </div>
        <div style="position: absolute; top:12px; left:100px; display:none" id="view_detail_<?php echo $product_id?>">
        	<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $product_id."/".$general->seoUrl("motor dijual ".$new["Parent"]["name"]." ".$new["Category"]["name"]." (".$new["Product"]["thn_pembuatan"].") ".$new["ProvinceGroup"]["name"]).".html"?>">
				<img src="<?php echo $this->webroot?>img/view_detail.png"  border="0" alt="view detail">
			</a>
        </div>
        <div class="line" style="height:20px;">&nbsp;</div>
        <div class="descthumb">
        	<div class="line" style="position:relative;">
               <div class="descrip_premium" id="caption_<?php echo $product_id?>">
               		<div class="left size40 top3" style="border:0px solid black;">
                    	<div class="style1 text13 black1 bold"><?php echo $name?></div>
                        <div class="style1 text11 grey2 top5">Th <?php echo $new['Product']['thn_pembuatan']?></div>
                        <div class="style1 text11 grey2"><?php echo $km?></div>
                        <div class="style1 text11 grey2"><?php echo $new['Province']['name']?></div>
                    </div>
                    <div class="kanan size40 top3">
                        <div class="style1 text13 red2 bold" style="text-align:right;">
							<?php echo $price?>
						</div>
						
                        <div class="line top5">
                            <div class="kiri right5 top5">
								<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $new['Product']['id']."/".$general->seoUrl("motor dijual ".$new["Parent"]["name"]." ".$new["Category"]["name"]." (".$new["Product"]["thn_pembuatan"].") ".$new["ProvinceGroup"]["name"]).".html"?>" title="Klik disini untuk memberikan komentar.">
									<img src="<?php echo $this->webroot?>img/comment_ico.gif" border="0" alt="icon comment"/>
								</a>
							</div>
                            <div class="kiri style1 text11 grey2 right7 top5">
								<?php echo $comment?>
							</div>
                            <div class="kiri top5">
								<a href="javascript:void(0)"><img src="<?php echo $this->webroot?>img/album_views16.gif" border="0" alt="icon view"/></a>
							</div>
                            <div class="kiri style1 text11 grey2 right5 top5"><?php echo $view?></div>
                            <div class="kanan">
                            	<a href="javascript:void(0)" title="<?php echo $new['Product']['contact_name']?>">
									<img src="<?php echo $ico?>" style="border:none" alt="<?php echo $new['Product']['contact_name']?>"/>
								</a>
                            </div>
                          	<?php if(!empty($ym)):?>
                            <div class="kanan top5">
                            	<a href="ymsgr:sendIM?<?php echo $ym?>" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=<?php echo $ym?>&m=g&t=1" alt="ym <?php echo $ym?>"></a>
                            </div>
                            <?php endif;?>
                    	</div>
                    </div>
               </div>
            </div>
        </div>
        <div class="line" style="height:2px;">&nbsp;</div>
    </div>
    <?php endforeach;?>
    <!-- END LOOPING NEW PRODUCT -->    
</div>
<div class="garis_premium">&nbsp;</div>
<?php endif;?>