<script>
<?php if($ok==1):?>
	opener.location.href="<?php echo $settings['site_url']?>OpenId/Results";
<?php else:?>
	opener.window.open("<?php echo $settings['site_url']?>OpenId/Results","_blank");
<?php endif;?>
window.close();
</script>
