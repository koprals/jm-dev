<?php if(!empty($data)):?>
<div class="line1">
<a href="<?php echo $back_url?>" style="text-decoration:none; font-size:12px; font-weight:bold; color:#000;" onClick="return onClickPage(this,'#list_item')"><img src="<?php echo $settings['cms_url']?>img/admin_arrowleft.gif" width="16" height="16" style="border:none; vertical-align:middle;margin-right:5px; ">Back</a>
</div>
<?php echo $data['EmailLog']['text']?>
<?php else:?>
<div class="alert">
    <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
    Data tidak di temukan
</div>
<?php endif;?>