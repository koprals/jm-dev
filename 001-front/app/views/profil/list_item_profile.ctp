<?php if( !empty($data)):?>
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
	onClickPage('<?php echo $settings['site_url']."Profil/ListItemProfile/".$user_id?>/page:'+val,'#list_item');
}
</script>

<div class="text_title3 top40">
    <div class="line1">
        IKLAN MEMBER
    </div>
</div>
<?php echo $paginator->options(array(
		'url'	=> array(
			'controller'	=> 'Profil',
			'action'		=> 'ListItemProfile',
			$user_id
		),
		'onclick'=>"return onClickPage(this,'#list_item');")
	);
?>
<!-- PAGING -->
<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
<div style="float:none; margin:auto; width:45%; border:0px solid black; margin-bottom:10px;">
	<div class="kiri top10 size100">
		<?php echo $form->create("Goto",array("onsubmit"=>"GotoPage($('#goto').val());return false;"))?>
        <div class="style1 text11 grey2 bold"><?php echo $paginator->counter(array('format' => 'Page <span class="red2">%page%</span> of %pages%'));?>
            <?php echo $paginator->prev('<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;"/>',array('tag'=>"a")); ?>
            <?php echo $paginator->next('<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;"/>',array('tag'=>"a")); ?>
            Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="3" onblur="return GotoPage($('#goto').val())" id="goto"/>
        </div>
        <?php echo $form->end();?>
    </div>
</div>
<?php endif;?>
<!-- END PAGING -->
<div class="items kiri left5 top10" style="border:0px solid black;">
	<!-- START LOOPING YAMAHA -->
	<?php $i=0;?>
    <?php foreach($data as $data):?>
    <?php $i++;?>
    <?php $name			=	$text->truncate(ucwords($data["Parent"]["name"]." ".$data["Category"]["name"]),30,array('ending'=>""));?>
    <?php $price		=	$number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>
    <?php $km			=	($data['Product']['condition_id']==1) ? "Km : 0(baru)" : (empty($data['Product']['kilometer']) ? "Km: Tdk ada informasi" : $number->format($data['Product']['kilometer'],array("thousands"=>".","before"=>"Km ","places"=>null,"after"=>null)))?>
    <?php $ico			=	($data['Product']['data_type']==1) ? $this->webroot."img/seller_ico.gif" : $this->webroot."img/dealer_ico.gif";?>
    <div class="product_tiny" style="margin-right:6.5px; margin-bottom:6.5px;">
        <div class="gambar_tiny">
            <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]?>"><img src="<?php echo $settings['showimages_url']."?code=".$data['ProductImage']['id']."&prefix=_127_80&content=ProductImage&w=127&h=80"?>" border="0"/></a>
        </div>
        <div class="descthumb_tiny">
            <div class="style1 text11 black1 bold"><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]?>" class="normal style1 text11 black1 bold" title="<?php echo $name?>"><?php echo $text->truncate($name,20)?></a></div>
            <div class="style1 text13 red2 bold"><?php echo $price?></div>
            <div class="style1 text11 grey2">Th <?php echo $data['Product']['thn_pembuatan']?></div>
            <div class="style1 text11 grey2"><?php echo $km?></div>
            <div class="kiri style1 text11 grey2 size80"><?php echo $data['Province']['name']?></div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>