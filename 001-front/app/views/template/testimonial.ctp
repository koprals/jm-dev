<?php if(!empty($data)):?>
<div class="top10 size82 kiri">
    <div class="text_title5 left10">
        <div class="kiri left10 top3">TESTIMONIAL</div>
    </div>
    <div class="tengah size95">
    	<?php foreach($data as $data):?>
        <div class="kiri size100 left10" style="border-bottom:1px solid #999999">
            <a href="<?php echo $settings['site_url']?>Us/Testimoni/<?php echo $data["Contact"]["id"]?>" class="style1 normal text12 kiri top5 bottom5 black1"><span class="red2 right5"><?php echo $data["Contact"]["from"]?></span><?php echo $text->truncate($data["Contact"]["message"],50)?></a>
        </div>
        <?php endforeach;?>
        <div class="tengah size25" style="border:0px solid black;">
        	<input type="button" name="button" value="POST" class="tombol1 top10" onclick="location.href='<?php echo $settings['site_url']?>Us/Testimoni'"/>
        </div>
    </div>
</div>
<?php endif;?>

