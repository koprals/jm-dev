<?php if(!empty($new)):?>
<div class="line top40">
    <div class="kiri size50 style1 grey2 text11" style="border:0px solid black">
        <span class="right10"><img src="<?php echo $this->webroot?>img/dealer_ico.gif" style="vertical-align:middle" class="right5" alt="icon dealer"/> Dealer</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/seller_ico.gif" style="vertical-align:middle" class="right5" alt="icon perorangan"/> Perorangan</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="vertical-align:middle" alt="icon dilihat"/> Dilihat</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/comment_ico.gif" style="vertical-align:middle" alt="icon komentar"/> Komentar</span>
    </div>
</div>
<div class="header_premium">
	<div class="red_carpet">PREMIUM</div>
	<div class="sudut_cp"></div>
</div>
<div class="line top10" style="border:0px solid black;">
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

<div class="product_premium" <?php echo $style?>>
	<div class="gambar_premium" style="height:100px">
		<div class="kiri right8" style="border:0px solid black;width:110px;">
			<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $new["Product"]["id"]."/".$general->seoUrl("motor dijual ".$new["Parent"]["name"]." ".$new["Category"]["name"]." (".$new["Product"]["thn_pembuatan"].") ".$new["ProvinceGroup"]["name"]).".html"?>" link="<?php echo $settings['site_url']?>Template/HoverImg/<?php echo $new['Product']['id']?>" rel="img_thumb" id="hover_premium_<?php echo $new['Product']['id']?>">
				<img src="<?php echo $settings['showimages_url']."?code=".$new['ProductImage']['id']."&prefix=_110_100&content=ProductImage&w=110&h=100"?>" border="0" alt="<?php echo $new["Parent"]["name"]." ".$new["Category"]["name"]." (".$new["Product"]["thn_pembuatan"].") ".$new["ProvinceGroup"]["name"]?>"/>
			</a>
		</div>
		<div class="kiri" style="border:0px solid black;width:160px;">
			<div class="line style1 text13 bold">
				<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $new["Product"]["id"]."/".$general->seoUrl("motor dijual ".$new["Parent"]["name"]." ".$new["Category"]["name"]." (".$new["Product"]["thn_pembuatan"].") ".$new["ProvinceGroup"]["name"]).".html"?>" class="style1 text13 black1 bold normal"><?php echo $name?></a>
			</div>
			<div class="line style1 text11 grey2 top3">Th : <?php echo $new['Product']['thn_pembuatan']?><?php echo $km?></div>
			
			<div class="line style1 text11 grey2 top3"><?php echo $new['Province']['name']?></div>
			<?php if($new['Product']['sold']=="1"):?>
			<div class="line style1 text18 red2 bold top5"><span style="text-decoration:line-through;"><?php echo $price?></span></div>
			<div class="line text12 bold black style1">(Terjual)</div>
			<?php else:?>
			 <div class="line style1 text18 red2 bold top5"><?php echo $price?></div>
			<?php endif;?>
							
			<div class="line top7">
				<?php if(!empty($ym)):?>
				<div class="kiri style1 text10 grey">
					<a href="ymsgr:sendIM?<?php echo $ym?>" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=<?php echo $ym?>&m=g&t=1" alt="icon YM"></a>
				</div>
				<?php endif;?>
				<div class="kanan style1 text11 grey">
					<div class="kiri right7 top3"><a href="javascript:void(0)" title="Dilihat sebanyak <?php echo $view?> orang"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="border:none; vertical-align:middle;" alt="album_views16.gif"/></a><?php echo $view?></div>
					<div class="kiri"><a href="javascript:void(0)" title="Diupload oleh <?php echo $new['Product']['contact_name']?>" rel="dealer"><img src="<?php echo $ico?>" style="border:none"  alt="<?php echo $ico?>"/></a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach;?>
	<!-- END LOOPING NEW PRODUCT -->
	<div class="garis_premium">&nbsp;</div>
</div>
<?php endif;?>