<?php if(!empty($data)):?>
<div class="top10 size82 kiri">
    <div class="text_title5 left10">
        <div class="kiri left10 top3">WHAT'S NEW</div>
    </div>
    <div class="tengah size95">
		<?php $count = 0;?>
        <?php foreach($data as $data):?>
        <div class="kiri size100 left10" style="border-bottom:1px solid #999999">
            <a href="<?php echo $data['link']?>" class="style1 normal text12 bold kiri top5 bottom5 red2" target="_blank"><?php echo $data['title']?></a>
        </div>
        <?php $count++?>
        <?php if($count>5) break;?>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>

