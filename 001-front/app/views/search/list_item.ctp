<?php if(!empty($data) && $reset != "1"):?>
<script>
var fade_in = 500;
var fade_out = 500;
$(document).ready(function(){
	if($.browser.msie)
	{
		fade_in 	= 100;
		fade_out	= 3500;
	}
	jQuery.bt.options.ajaxLoading = '<div style="width:235px;height:250px;display:block;float:left;"><div style="color:#000000;font-size:12px;float:none;margin:50% auto;display:block; text-align:center;"><img src="<?php echo $this->webroot?>img/loading19_bak.gif" />&nbsp;Loading..</div></div>';
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
</script>
<script>
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
	onClickPage('<?php echo $settings['site_url']."Search/ListItem"?>/page:'+val,'#list_item');
}
</script>
<?php echo $paginator->options(array(
				'url'	=> array(
					'controller'	=> 'Search',
					'action'		=> 'ListItem'
				),
				'onclick'=>"return onClickPage(this,'#list_item');")
			);
?>
<div class="line top40" style="border:0px solid black;">
	<!-- PAGING -->
	<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
    <div style="float:none; margin:auto; width:45%; border:0px solid black; margin-bottom:10px;">
    	<?php echo $form->create("Goto",array("onsubmit"=>"GotoPage($('#goto').val());return false;"))?>
        <div class="style1 text11 grey2 bold"><?php echo $paginator->counter(array('format' => 'Page <span class="red2">%page%</span> of %pages%'));?>
        	<?php echo $paginator->prev('<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;"/>',array('tag'=>"a")); ?>
        	<?php echo $paginator->next('<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;"/>',array('tag'=>"a")); ?>
            Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="3" onblur="return GotoPage($('#goto').val())" id="goto"/>
        </div>
        <?php echo $form->end();?>
    </div>
    <?php endif;?>
    <!-- END PAGING -->
    <div class="kiri size50 style1 grey2 text12 bottom5 top20" style="text-align:left; border:0px solid black;">
        <span class="right10">Urutkan:</span>
        <span class="right10"><?php echo $paginator->sort('Merk', 'Parent.name',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Tipe', 'Category.name',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Harga', 'Product.price',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Tahun', 'Product.thn_pembuatan',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
        <span class="right10"><?php echo $paginator->sort('Km', 'Product.kilometer',array('class'=>'style1 grey2 text12 normal bold','escape'=>false,'current'=>'current_sort'));?></span>
    </div>
    <div class="kanan size45 style1 grey2 text11 bottom5 top20" style="text-align:right; border:0px solid black;">
        <span class="right10"><img src="<?php echo $this->webroot?>img/dealer_ico.gif" style="vertical-align:middle" class="right5"/> Dealer</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/seller_ico.gif" style="vertical-align:middle" class="right5"/> Perorangan</span>
        <span class="right10"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="vertical-align:middle"/> Dilihat</span>
    </div>
    
    <?php $count=0;?>
    <?php foreach($data as $data):?>
    <?php $count++;?>
    <?php $style		=	($count%2!=0) ? 'style="margin-right:16px;"' : ""?>
    <?php $product_id	=	$data["Product"]["id"]?>
    <?php $name			=	$text->truncate(ucwords($data["Parent"]["name"]." ".$data["Category"]["name"]),20,array('ending'=>""));?>
    <?php $price		=	$number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>
    <?php $view			=	$number->format($data['Product']['view'],array("thousands"=>".","before"=>NULL,"places"=>null,"after"=>null))?>
    
    <?php $km			=	($data['Product']['condition_id']==1) ? ", Km : 0(baru)" : (empty($data['Product']['kilometer']) ? ", Km: Tdk ada informasi" : $number->format($data['Product']['kilometer'],array("thousands"=>".","before"=>", Km ","places"=>null,"after"=>null)))?>
    <?php $ym			=	(!empty($data["Product"]["ym"])) ? explode("@",$data["Product"]["ym"]) : "";?>
    <?php $ym			=	(is_array($ym)) ? $ym[0] : "";?>
    <?php $ico			=	($data['Product']['data_type']==1) ? $this->webroot."img/seller_ico.gif" : $this->webroot."img/dealer_ico.gif";?>
     <?php $uploader	=	($data['Product']['data_type']==1) ? "perorangan." : "dealer.";?>
    
    
    <div class="product" <?php echo $style;?> >
    	<div class="gambar" style="height:105px;border:0px solid black; margin-top:5px;">
        	<div class="kiri right8" style="border:0px solid black;width:110px;">
            	<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]?>" link="<?php echo $settings['site_url']?>Template/HoverImg/<?php echo $data['Product']['id']?>" rel="img_thumb" id="hover_<?php echo $data['Product']['id']?>"><img src="<?php echo $settings['showimages_url']."?code=".$data['ProductImage']['id']."&prefix=_thumbsmall2&content=ProductImage&w=110&h=100"?>" border="0"/></a>
            </div>
            <div class="kiri" style="border:0px solid black;width:160px;">
            	<div class="line style1 text13 black1 bold"><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]?>" class="style1 text13 black1 bold normal"><?php echo $name?></a></div>
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
                        <a href="ymsgr:sendIM?<?php echo $ym?>" style="margin-top:5px;"><img border="0" src="http://opi.yahoo.com/online?u=<?php echo $ym?>&m=g&t=1"></a>
                    </div>
                    <?php endif;?>
                    <div class="kanan style1 text11 grey">
                    	<div class="kiri right7 top3"><a href="javascript:void(0)" title="Dilihat sebanyak <?php echo $view?> orang"><img src="<?php echo $this->webroot?>img/album_views16.gif" style="border:none; vertical-align:middle;"/></a><?php echo $view?></div>
                        <div class="kiri"><a href="javascript:void(0)" title="Diupload oleh <?php echo $uploader?>" rel="dealer"><img src="<?php echo $ico?>" style="border:none"/></a></div>
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
        	<?php echo $form->create("Goto",array("onsubmit"=>"GotoPage($('#gotobottom').val());return false;"))?>
            <div class="style1 text11 grey2 bold"><?php echo $paginator->counter(array('format' => 'Page <span class="red2">%page%</span> of %pages%'));?>
                <?php echo $paginator->prev('<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;"/>',array('tag'=>"a")); ?>
               <?php echo $paginator->next('<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;"/>',array('tag'=>"a")); ?>
                Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="7" onblur="return GotoPage($('#gotobottom').val())" id="gotobottom"/>
            </div>
            <?php echo $form->end()?>
        </div>
    </div>
    <!-- END PAGING -->
    <?php endif;?>
    
</div>
<?php elseif( $reset == "0"):?>
<div class="line top10" style="border:0px solid black;">
	<div class="product size100" style="width:100%;">
    	<div class="gambar" style="height:105px;border:0px solid black; margin-top:5px;">
        	<div class="kiri left10" style="width:auto; border:0px solid black;">
                <img src="<?php echo $settings['site_url']?>img/warning_big.png" />
            </div>
            <div class="kiri size65 left20 style1 black1 text12 top10 bold">
                Maaf kami tidak menemukan iklan yang anda cari, coba cari dengan kriteria lain.
            </div>
		</div>
    </div>
</div>
<?php endif;?>