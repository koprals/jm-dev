<?php if(!empty($error)):?>
    <div class="alert">
        <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
        <?php echo $error?>
    </div>
<?php else:?>
    <div class="alert_success">
    	<img src="<?php echo $this->webroot?>img/check.png" style=" vertical-align:middle;"/> Layanan untuk update profil telah kami hentikan. Terimakasih.
    </div>
<?php endif;?>