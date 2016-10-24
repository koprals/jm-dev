<?php if(!empty($data)):?>
<div class="line">
    <div class="menu_kanan" style="border:none;">
        <div style="font-size:12px;background:none; padding:10px 0 0 10px">
            <span class="kiri"><a href="<?php echo $settings['site_url']?>MotorGede/all_categories/all_cities/motor_gede_dijual.html" class="style1 normal" style="color:#ffffff;text-shadow:0px 1px 3px #272727,0px 1px 3px #272727;">MOTOR GEDE (MOGE)</a></span>
        </div>
    </div>
    <div class="tengah size95">
    	<?php $count	=	0;?>
    	<?php foreach($data as $data):?>
        <?php $count++;?>
        <div class="kiri size100 left10 top5" style="border-bottom:1px solid #999999">
        	<?php if($count==1):?>
            <img src="<?php echo $settings['showimages_url']."/ProductImage_159_80_".$data['ProductImage']['id'].".jpg?code=".$data['ProductImage']['id']."&prefix=_159_80&content=ProductImage&w=159&h=80"?>" alt="<?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]?>"/>
            <?php endif;?>
            <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]."/".$general->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).".html"?>" title="<?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]?>" class="style1 normal text12 bold kiri top5 bottom5 red2"><?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].")"?><br><?php echo $number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?></a>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>