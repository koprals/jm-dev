<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1">Sukses Registrasi.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto;">
        	<img src="<?php echo $settings['showimages_url']?>?code=<?php echo $user_id?>&prefix=_prevthumb&content=User&w=120&h=120" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Hallo <b><?php echo $fullname?></b>,<br /><br />
            User anda telah sepenuhnya aktif.<br /><br />
           Terimakasih anda telah mengaktifkan user anda. Saat ini anda sudah dapat login menggunakan email dan password anda.
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
<?php echo $this->requestAction("/Users/AvailableVendor",array('return'))?>
<?php echo $this->requestAction("/Users/OtherAction",array('return'))?>