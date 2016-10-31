<?php echo $this->requestAction("/Template/DaftarSubcategory/{$current_category_id}/{$current_city}/DaftarMotor",array("return"))?>
<?php echo $this->requestAction("/Template/DaftarKota/{$current_category_id}/{$current_city}/MotorGede",array("return"))?>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.hoverIntent.minified")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->

<script>
var fade_in = 500;
var fade_out = 500;
$(document).ready(function(){
	if($.browser.msie)
	{
		fade_in 	= 100;
		fade_out	= 3500;
	}
	jQuery.bt.options.ajaxLoading = '<div style="width:235px;height:250px;display:block;float:left;"><div style="color:#000000;font-size:12px;float:none;margin:50% auto;display:block; text-align:center;"><img src="<?php echo $this->webroot?>img/loading19_bak.gif" alt="loading19_bak.gif"/>&nbsp;Loading..</div></div>';
	jQuery.bt.options.closeWhenOthersOpen = true;
	$('a[rel^=img_thumb]').each(function(){
		$(this).bt({
			ajaxPath: ["$(this).attr('link')"],
			width: 237,
			positions: ['right'],
			cornerRadius: 0,
			strokeStyle: '#8a8a8a',
			fill: 'rgba(255, 255, 255, 1)',
			cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px','padding-top':'2px','padding-right':'1px','padding-left':'2px','padding-bottom':'1px'},
			shrinkToFit: true,
			hoverIntentOpts: {
				interval: 200,
				timeout: 2000
			  }
		});
	});
	
	$('a[rel^=dealer]').each(function(){
		$(this).bt({
			width: 237,
			positions: ['right'],
			cornerRadius: 2,
			strokeStyle: '#FFFFFF',
		 	fill: 'rgba(0, 0, 0, 1)',
			cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px'},
			shrinkToFit: true
		});
	});
});

function GotoPage(value)
{
	var val	=	parseInt(value);
	if(val > <?php echo $paginator->counter(array('format' => '%pages%'));?>)
	{
		val	=	<?php echo $paginator->counter(array('format' => '%pages%'));?>;
	}
	
	if(val<1)
	{
		val = 1
	}
	location.href	=	'<?php echo $settings['site_url']."MotorGede/".implode("/",$this->params['pass'])?>/page:'+val;
}

</script>
<?php echo $this->element('home_premium_content_category',array('cache' => false,"category_id"=>"{$current_category_id}","current_city"=>"{$current_city}"))?>
<?php if(!empty($data)):?>
<?php echo $paginator->options(array(
				'url'	=> implode("/",$this->params['pass'])
				));
?>

<div class="line top40" style="border:0px solid black;">
	<!-- PAGING -->
	<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
    <div style="float:none; margin:auto; width:45%; border:0px solid black; margin-bottom:10px;">
    	<?php echo $form->create("Goto",array("onsubmit"=>"GotoPage($('#goto').val());return false;"))?>
        <div class="style1 text11 grey2 bold"><?php echo $paginator->counter(array('format' => 'Page <span class="red2">%page%</span> of %pages%'));?>
        	<?php echo $paginator->prev('<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;" alt="icon prev"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;" alt="icon prev"/>',array('tag'=>"a")); ?>
        	<?php echo $paginator->next('<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;" alt="icon next"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;" alt="icon next"/>',array('tag'=>"a")); ?>
            Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="3" onblur="return GotoPage($('#goto').val())" id="goto"/>
        </div>
        <?php echo $form->end();?>
    </div>
    <?php endif;?>
    <!-- END PAGING -->
    <div class="kiri size50 style1 grey2 text12 bottom5 top20" style="text-align:left; border:0px solid black; height:17px;">
        <span class="right10">Urutkan:</span>
        <span class="right10"><?php echo $paginator->sort('Merk', 'Parent.name',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Tipe', 'Category.name',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Harga', 'Product.price',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Tahun', 'Product.thn_pembuatan',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Km', 'Product.kilometer',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
    </div>
    <div class="kanan size45 style1 grey2 text11 bottom5 top20" style="text-align:right; border:0px solid black;">
        <span class="right10"><img src="<?php echo $this->webroot?>img/dealer_ico.gif" style="vertical-align:middle" class="right5" alt="icon dealer"/> Dealer</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/seller_ico.gif" style="vertical-align:middle" class="right5" alt="icon perorangan"/> Perorangan</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="vertical-align:middle" alt="icon view"/> Dilihat</span>
    </div>
	<?php $count=0;?>
    <?php foreach($data as $data):?>
    <?php $count++;?>
    <?php $style		=	($count%2!=0) ? 'style="margin-right:16px;"' : ""?>
    <?php $product_id	=	$data["Product"]["id"]?>
    <?php $name			=	$text->truncate(ucwords($data["Parent"]["name"]." ".$data["Category"]["name"]),20,array('ending'=>""));?>
    <?php $price		=	$number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>
    <?php $km			=	($data['Product']['condition_id']==1) ? ", Km : 0(baru)" : (empty($data['Product']['kilometer']) ? ", Km: Tdk ada informasi" : $number->format($data['Product']['kilometer'],array("thousands"=>".","before"=>", Km ","places"=>null,"after"=>null)))?>
    <?php $ym			=	(!empty($data["Product"]["ym"])) ? explode("@",$data["Product"]["ym"]) : "";?>
    <?php $ym			=	(is_array($ym)) ? $ym[0] : "";?>
    <?php $ico			=	($data['Product']['data_type']==1) ? $this->webroot."img/seller_ico.gif" : $this->webroot."img/dealer_ico.gif";?>
    <?php $uploader	=	($data['Product']['data_type']==1) ? "perorangan." : "dealer.";?>
    <?php $view			=	$number->format($data['Product']['view'],array("thousands"=>".","before"=>NULL,"places"=>null,"after"=>null))?>
    
    <div class="product" <?php echo $style;?> >
    	<div class="gambar" style="height:105px;border:0px solid black; margin-top:5px;">
        	<div class="kiri right8" style="border:0px solid black;width:110px;">
            	<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" link="<?php echo $settings['site_url']?>Template/HoverImg/<?php echo $data['Product']['id']?>" rel="img_thumb" id="hover_<?php echo $data['Product']['id']?>"><img src="<?php echo $settings['showimages_url']."?code=".$data['ProductImage']['id']."&prefix=_110_100&content=ProductImage&w=110&h=100"?>" border="0" alt="<?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]?>"/></a>
            </div>
            <div class="kiri" style="border:0px solid black;width:160px;">
            	<div class="line style1 text13 black1 bold"><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" class="style1 text13 black1 bold normal"><?php echo $name?></a></div>
                <div class="line style1 text11 grey2 top3">Th : <?php echo $data['Product']['thn_pembuatan']?><?php echo $km?></div>
                
                <div class="line style1 text11 grey2 top3"><?php echo $data['Province']['name']?></div>
                <?php if($data['Product']['sold']=="1"):?>
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
                        <div class="kiri"><a href="javascript:void(0)" title="Diupload oleh <?php echo $data['Product']['contact_name']?>" rel="dealer"><img src="<?php echo $ico?>" style="border:none"  alt="<?php echo $ico?>"/></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>
    <?php if($paginator->hasNext() or $paginator->hasPrev()):?>
	<!-- PAGING -->
    <div class="line size100">
        <div style="float:none; margin:auto; width:45%; border:0px solid black; margin-bottom:10px;">
            <div class="style1 text11 grey2 bold"><?php echo $paginator->counter(array('format' => 'Page <span class="red2">%page%</span> of %pages%'));?>
                <?php echo $paginator->prev('<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;" alt="icon prev"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;" alt="icon prev"/>',array('tag'=>"a")); ?>
               <?php echo $paginator->next('<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;" alt="icon next"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;" alt="icon next"/>',array('tag'=>"a")); ?>
                Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="7" onblur="return GotoPage($('#gotobottom').val())" id="gotobottom"/>
            </div>
        </div>
    </div>
    <!-- END PAGING -->
    <?php endif;?>
</div>
<?php else:?>
<div class="line top15" style="border:0px solid black;">
	<div class="product size100" style="width:100%;">
    	<div class="gambar" style="height:105px;border:0px solid black; margin-top:5px;">
        	<div class="kiri left10" style="width:auto; border:0px solid black;">
                <img src="<?php echo $settings['site_url']?>img/warning_big.png" alt="warning_big.png"/>
            </div>
            <div class="kiri size65 left20 style1 black1 text12 top10 bold">
                Maaf kami tidak menemukan iklan <?php echo $error_msg?>.
            </div>
		</div>
    </div>
</div>

<!-- OTHERS CATEGORY -->
<?php if(!empty($category_others)):?>
<div class="line top20" style="border:0px solid black;">
	<div class="line top20">
        <div class="kiri style1 black bold text14 bottom10 size50" style="border:0px solid black"><?php echo $category_others[0]['Parent']['name']?> dengan tipe lain di <?php echo $province_name?></div>
        <div class="kanan size45 style1 grey2 text11 bottom5" style="text-align:right;border:0px solid black">
            <span class="right10"><img src="<?php echo $this->webroot?>img/dealer_ico.gif" style="vertical-align:middle" class="right5" alt="icon dealer"/> Dealer</span>
            <span class="right10"><img src="<?php echo $this->webroot?>img/seller_ico.gif" style="vertical-align:middle" class="right5" alt="icon perorangan"/> Perorangan</span>
            <span class="right10"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="vertical-align:middle" alt="icon view"/> Dilihat</span>
        </div>
    </div>
	<?php $count=0;?>
    <?php foreach($category_others as $data):?>
    <?php $count++;?>
    <?php $style		=	($count%2!=0) ? 'style="margin-right:16px;"' : ""?>
    <?php $product_id	=	$data["Product"]["id"]?>
    <?php $name			=	$text->truncate(ucwords($data["Parent"]["name"]." ".$data["Category"]["name"]),20,array('ending'=>""));?>
    <?php $price		=	$number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>
    <?php $km			=	($data['Product']['condition_id']==1) ? ", Km : 0(baru)" : (empty($data['Product']['kilometer']) ? ", Km: Tdk ada informasi" : $number->format($data['Product']['kilometer'],array("thousands"=>".","before"=>", Km ","places"=>null,"after"=>null)))?>
    <?php $ym			=	(!empty($data["Product"]["ym"])) ? explode("@",$data["Product"]["ym"]) : "";?>
    <?php $ym			=	(is_array($ym)) ? $ym[0] : "";?>
    <?php $ico			=	($data['Product']['data_type']==1) ? $this->webroot."img/seller_ico.gif" : $this->webroot."img/dealer_ico.gif";?>
     <?php $uploader	=	($data['Product']['data_type']==1) ? "perorangan." : "dealer.";?>
	 <?php $view			=	$number->format($data['Product']['view'],array("thousands"=>".","before"=>NULL,"places"=>null,"after"=>null))?>
    <div class="product" <?php echo $style;?> >
        <div class="gambar" style="height:105px;border:0px solid black; margin-top:5px;">
            <div class="kiri right8" style="border:0px solid black;width:110px;">
                <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" link="<?php echo $settings['site_url']?>Template/HoverImg/<?php echo $data['Product']['id']?>" rel="img_thumb" id="hover_<?php echo $data['Product']['id']?>"><img src="<?php echo $settings['showimages_url']."?code=".$data['ProductImage']['id']."&prefix=_110_100&content=ProductImage&w=110&h=100"?>" border="0" alt="<?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]?>"/></a>
            </div>
            <div class="kiri" style="border:0px solid black;width:160px;">
                <div class="line style1 text13 black1 bold"><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" class="style1 text13 black1 bold normal"><?php echo $name?></a></div>
                <div class="line style1 text11 grey2 top3">Th : <?php echo $data['Product']['thn_pembuatan']?><?php echo $km?></div>
                
                <div class="line style1 text11 grey2 top3"><?php echo $data['Province']['name']?></div>
                <div class="line style1 text18 red2 bold top5"><?php echo $price?></div>
                
                <div class="line top7">
                    <?php if(!empty($ym)):?>
                    <div class="kiri style1 text10 grey">
                        <a href="ymsgr:sendIM?<?php echo $ym?>" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=<?php echo $ym?>&m=g&t=1" alt="icon YM"></a>
                    </div>
                    <?php endif;?>
                    <div class="kanan style1 text11 grey">
                        <div class="kiri right7 top3"><a href="javascript:void(0)"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="border:none; vertical-align:middle;" alt="album_views16.gif"/></a><?php echo $view?></div>
                        <div class="kiri"><a href="javascript:void(0)" title="Diupload oleh <?php echo $uploader?>" rel="dealer"><img src="<?php echo $ico?>" style="border:none" alt="<?php echo $ico?>"/></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<!-- OTHERS CATEGORY -->

<!-- OTHERS PROVINCE -->
<?php if(!empty($province_others)):?>
<div class="line top20" style="border:0px solid black;">
	<div class="line top20">
        <div class="kiri style1 black bold text14 bottom10 size50" style="border:0px solid black"><?php echo $category_name?> di kota lain.</div>
        <div class="kanan size45 style1 grey2 text11 bottom5" style="text-align:right;border:0px solid black">
            <span class="right10"><img src="<?php echo $this->webroot?>img/dealer_ico.gif" style="vertical-align:middle" class="right5" alt="icon dealer"/> Dealer</span>
            <span class="right10"><img src="<?php echo $this->webroot?>img/seller_ico.gif" style="vertical-align:middle" class="right5" alt="icon perorangan"/> Perorangan</span>
            <span class="right10"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="vertical-align:middle" alt="icon view"/> Dilihat</span>
        </div>
    </div>
	<?php $count=0;?>
    <?php foreach($province_others as $data):?>
    <?php $count++;?>
    <?php $style		=	($count%2!=0) ? 'style="margin-right:16px;"' : ""?>
    <?php $product_id	=	$data["Product"]["id"]?>
    <?php $name			=	$text->truncate(ucwords($data["Parent"]["name"]." ".$data["Category"]["name"]),20,array('ending'=>""));?>
    <?php $price		=	$number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>
    <?php $km			=	($data['Product']['condition_id']==1) ? ", Km : 0(baru)" : (empty($data['Product']['kilometer']) ? ", Km: Tdk ada informasi" : $number->format($data['Product']['kilometer'],array("thousands"=>".","before"=>", Km ","places"=>null,"after"=>null)))?>
    <?php $ym			=	(!empty($data["Product"]["ym"])) ? explode("@",$data["Product"]["ym"]) : "";?>
    <?php $ym			=	(is_array($ym)) ? $ym[0] : "";?>
    <?php $ico			=	($data['Product']['data_type']==1) ? $this->webroot."img/seller_ico.gif" : $this->webroot."img/dealer_ico.gif";?>
     <?php $uploader	=	($data['Product']['data_type']==1) ? "perorangan." : "dealer.";?>
	 <?php $view			=	$number->format($data['Product']['view'],array("thousands"=>".","before"=>NULL,"places"=>null,"after"=>null))?>
    <div class="product" <?php echo $style;?> >
        <div class="gambar" style="height:105px;border:0px solid black; margin-top:5px;">
            <div class="kiri right8" style="border:0px solid black;width:110px;">
                <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" link="<?php echo $settings['site_url']?>Template/HoverImg/<?php echo $data['Product']['id']?>" rel="img_thumb" id="hover_<?php echo $data['Product']['id']?>"><img src="<?php echo $settings['showimages_url']."?code=".$data['ProductImage']['id']."&prefix=_110_100&content=ProductImage&w=110&h=100"?>" border="0" alt="<?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]?>"/></a>
            </div>
            <div class="kiri" style="border:0px solid black;width:160px;">
                <div class="line style1 text13 black1 bold"><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" class="style1 text13 black1 bold normal"><?php echo $name?></a></div>
                <div class="line style1 text11 grey2 top3">Th : <?php echo $data['Product']['thn_pembuatan']?><?php echo $km?></div>
                
                <div class="line style1 text11 grey2 top3"><?php echo $data['Province']['name']?></div>
                <div class="line style1 text18 red2 bold top5"><?php echo $price?></div>
                
                <div class="line top7">
                    <?php if(!empty($ym)):?>
                    <div class="kiri style1 text10 grey">
                        <a href="ymsgr:sendIM?<?php echo $ym?>" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=<?php echo $ym?>&m=g&t=1" alt="icon YM"></a>
                    </div>
                    <?php endif;?>
                    <div class="kanan style1 text11 grey">
                        <div class="kiri right7 top3"><a href="javascript:void(0)"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="border:none; vertical-align:middle;" alt="album_views16.gif"/></a><?php echo $view?></div>
                        <div class="kiri"><a href="javascript:void(0)" title="Diupload oleh <?php echo $uploader?>" rel="dealer"><img src="<?php echo $ico?>" style="border:none" alt="<?php echo $ico?>"/></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<!-- OTHERS PROVINCE -->

<?php endif;?>