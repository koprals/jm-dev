<?php $count = 1;?>
<div class="text_title1">
    <div class="line1"><h1 style="font:bold 15px Arial, Helvetica, sans-serif;color:#ffffff;text-shadow:0px 1px 3px #272727,0px 1px 3px #272727; margin-top:-5px;"><a href="<?php echo $settings["site_url"]?>DaftarHarga/all_categories/daftar_harga_motor.html">Daftar Harga</a> - <?php echo $display_title?></h1></div>
</div>
<div class="line back1 rounded2 bottom10" style="padding-bottom:10px;">
    <div class="line1">
    	<div class="line top10">
			<?php foreach($children as $children):?>
           
            <?php $name	=	(strtolower($children['Category']['parent_id'])==$top) ? $children['Category']['name'] : $children['Parent']['name']." ".$children['Category']['name'];?>
            <?php $link	=	"daftar_harga_motor-".$general->seoUrl($name).".html"?>
            
            <?php if($count%5==0):?>
                <a href="<?php echo $settings['site_url']?>DaftarHarga/<?php echo $children['Category']['id']?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18" title="<?PHP echo $children['Category']['name']?>"><?php echo $text->truncate($children['Category']['name'],15,array('ending'=>""))?></a>
            </div>
            <div class="line top10">
            <?php else:?>
            <a href="<?php echo $settings['site_url']?>DaftarHarga/<?php echo $children['Category']['id']?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18 right10" title="<?PHP echo $children['Category']['name']?>"><?php echo $text->truncate($children['Category']['name'],15,array('ending'=>""))?></a>
            <?php endif;?>
            
             <?php $count++;?>
            <?php endforeach;?>
        </div>
    </div>
</div>
