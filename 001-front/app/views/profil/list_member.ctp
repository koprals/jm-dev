<style>
#pagination-digg a{
	border:1px solid grey;
}
#pagination-digg a:hover
{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
	border:1px solid #D3D1D1;
}
#pagination-digg .current{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
}
</style>
<div class="text_title1">
    <div class="line1">Daftar Dealer - <?php echo strtoupper($display_title)?></div>
</div>

<?php $count = 1;?>
<div class="kiri size100 back1 rounded2" style="padding-bottom:10px; border:0px solid black;">
    <div class="line1">
    	<div class="line top10">
			<?php foreach($daftar_kota as $id => $nama):?>
            <?php $link	=	"daftar_dealer_motor-".$general->seoUrl($nama).".html"?>
             
            <?php if($count%5==0):?>
                    <a href="<?php echo $settings['site_url']?>Profil/ListMember/<?php echo $id?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18"><?php echo $nama?></a>
                </div>
                <?php if($count==20):?>
                <div class="line top10" id="more_open" onclick="$('#more').show(300);$('#more_open').hide();" style=" cursor:pointer;">
                    <div  style="float:none; display:block; margin:auto; width:120px; text-align:center; border:0px solid black;">
                        <span class="style1 text12 white bold normal">Lihat kota lainnya</span>
                    </div>
                </div>
                <div class="line" style="display:none" id="more">
                <?php endif;?>
                <div class="line top10">
            <?php else:?>
                <a href="<?php echo $settings['site_url']?>Profil/ListMember/<?php echo $id?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18 right10"><?php echo $nama?></a>
            <?php endif;?>
            <?php $count++;?>
            <?php endforeach;?>
            </div>
            <div  style="float:none; display:block; margin:auto; width:100px; text-align:center; border:0px solid black;">
            	<a href="javascript:void(0)" onclick="$('#more').hide(300);$('#more_open').show(300);"><img src="<?php echo $this->webroot?>img/up_red.gif" style=" margin-top:-1px; border:none;"/></a>
            </div>
        </div>
    </div>
	<?php if(!empty($data)):?>
	<?php echo $paginator->options(array(
                    'url'	=> implode("/",$this->params['pass'])
                    ));
    ?>
    <div class="tengah size95" style="border:0px solid black">
    	<div class="kiri size100 top50" style="border:0px solid black">
        	<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
            <div class="kiri bottom5 size100 top20">
                <div class="paging-box" style="width:auto;float:right; border:0px solid black;">
                    <ul id="pagination-digg" style="border:0px solid black; margin-left:-40px;">
                        <?php  $paginator->counter(array('format' => 'Page %page% of %pages%'));?>
                        <?php echo $paginator->prev("Prev",array('class'=>'next',"escape"=>false),'Prev',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
                        <?php echo $paginator->numbers(array('modulus'=>4,'separator'=>null,'class'=>'navigasi1','span'=>false,'current'=>'current')); ?>
                        <?php echo $paginator->next("Next",array('class'=>'next',"escape"=>false),'Next',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
                    </ul>  
                </div>
            </div>
            <?php endif;?>
        	<!-- LOOPING -->
            <?php foreach($data as $data) :?>
            <div class="kiri size100" style="background-image:url(<?php echo $this->webroot?>img/garis2.gif); background-position:bottom; background-repeat:repeat-x;">
                <div class="kiri size100 top10 bottom10">
                    <div class="kiri">
                        <img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data["Company"]["id"]?>&prefix=_121_84&content=Company&w=121&h=84" style="border:1px solid #ffffff; padding:2px;"/>
                    </div>
                    <div class="kiri size70 left20">
                        <a href="<?PHP echo $settings['site_url']?>Profil/DetailDealer/<?php echo $data["Company"]["id"]?>/detail_delaer_<?php echo $general->seoUrl($data["Company"]["name"])?>.html" class="kiri style1 black2 text15 bold normal size100 bottom5" style=" outline:none;"><?php echo $data["Company"]["name"]?></a>
                        
                        <span class="kiri style1 white text12 size100 bold">Alamat:</span>
                        <span class="kiri style1 white text12 size100 bottom5" style="border:0px solid black;word-wrap: break-word;"><?php echo $data["Company"]["address"]." ".$data["Province"]["name"].", ".$data["Province"]["province"]?></span>
                        <span class="kiri style1 white text12 size100 bold">No Telp:</span>
                        <span class="kiri style1 white text12 size100" rel="<?php echo $data["Company"]["user_id"]?>" id="phone_<?php echo $data["Company"]["id"]?>" style="border:0px solid black;word-wrap: break-word;"><?php echo $data["Company"]["phone"]?></span>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
            <!-- LOOPING -->
        </div>
    </div>
    <?php endif;?>
</div>

<script>
$(document).ready(function(){
	$("span[id^=phone]").each(function(){
		var id		=	$(this).attr("rel");
		var span	=	$(this);
		$.getJSON("<?php echo $settings["site_url"]?>Profil/GetPhone/"+id,function(data){
			if(data.length > 0) span.html(span.html() + ", " + data);
		});
	});
});
</script>

<?php if(empty($data)):?>
<div class="size100 kiri top10" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1">Daftar dealer tidak ditemukan.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Maaf daftar dealer motor yang anda cari tidak kami temukan.<br /><br />
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
<?php endif;?>