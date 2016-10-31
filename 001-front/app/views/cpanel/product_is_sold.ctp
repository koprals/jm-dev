<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1">Perubahan Status Iklan.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<?php if(!empty($error)):?>
            <div class="kiri left10 top10" style="width:auto;">
                <img src="<?php echo $settings['site_url']?>img/error_ico.png" />
            </div>
            <div class="kiri size65 top10 style1 white text12 top10 bold left10">
				<?php echo $error?>
            </div>
        <?php else:?>
            <div class="kiri left10 top10" style="width:auto;">
                <img src="<?php echo $settings['site_url']?>img/check_big.png" />
            </div>
            <div class="kiri size65 top10 style1 white text12 top10 bold left10">
                Iklan anda telah kami ubah statusnya menjadi <span class="bold text14">"<?php echo $status_after?>"</span>. Terimakasih.
            </div>
        <?php endif;?>
    </div>
    <div class="line">&nbsp;</div>
</div>