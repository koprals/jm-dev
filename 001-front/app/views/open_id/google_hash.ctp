<script>
var quer	=	location.hash.substring(1);
var direct	=	"<?php echo $settings['site_url']?>OpenId/GoogleResults/?"+quer;
location.href	=	direct;
</script>