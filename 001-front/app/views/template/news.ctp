<?php if(!empty($data)):?>
<div class="line" style="margin-top:10px;">
    <div class="menu_kanan" style="border:none;">
        <div style="font-size:12px;background:none; padding:10px 0 0 10px">
            <span class="kiri"><a href="<?php echo $settings['site_url']?>News/Index/" class="style1 normal" style="color:#ffffff;text-shadow:0px 1px 3px #272727,0px 1px 3px #272727;">KNALPOT</a></span>
        </div>
    </div>
    <div class="tengah size95">
    	<?php $count	=	0;?>
    	<?php foreach($data as $data):?>
        <?php $count++;?>
        <div class="kiri size100 left10 top5" style="border-bottom:1px solid #999999">
        	<?php if($count==1):?>
            <img src="<?php echo $settings['showimages_url']."/News_159_80_".$data['News']['id'].".jpg?code=".$data['News']['id']."&prefix=_159_80&content=News&w=159&h=80"?>" alt="<?php echo $data["News"]['title']?>"/>
            <?php endif;?>
            <a href="<?php echo $settings['site_url']?>News/Detail/<?php echo $data["News"]["id"]."/".$general->seoUrl($data["News"]["title"]).".html"?>" title="<?php echo $data["News"]["title"]?>" class="style1 normal text12 bold kiri top5 bottom5 red2"><?php echo $data["News"]["title"]?></a>
        </div>
        <?php endforeach;?>
		<div class="kiri size100 left10 top5" style="border-bottom:1px solid #999999">
            <a href="<?php echo $settings['site_url']?>News/Index/" title="JualanMotor Tips and Trik" class="style1 normal text12 bold kiri top5 bottom5 red2">[Lihat Semua]</a>
        </div>
    </div>
</div>
<?php endif;?>