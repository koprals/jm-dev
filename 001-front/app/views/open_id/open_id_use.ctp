<?php echo $javascript->link('jquery.tools.min');?>
<script>
	$(document).ready(function(){
		//TOOTLTIPS
		$("a[id^=LoginVia]").tooltip({bounce: true,position: "top center",tipClass:"tooltip2"});
	});
</script>
<style>
.tooltip2 {
	display:none;
	background:transparent url(<?php echo $this->webroot?>img/black_arrow2.png);
	font-size:12px;
	font-family:Arial, Helvetica, sans-serif;
	height:30px;
	width:110px;
	color:#fff;	
	padding-top: 5px;
	border:0px solid black;
	text-align:center;
	margin-top:-38px;
}
</style>
<div class="size40 tengah" style="border:0px solid black;">
    <div class="text_title3 top30">
        <div class="line1">User Id telah digunakan.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto; border:0px solid black;">
        	<img src="<?php echo $settings['site_url']?>img/<?php echo $vendor?>_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Maaf user id anda di <?php echo ucfirst($vendor)?> telah digunakan member lain di <?php echo $settings['site_name']?>.<br /><br />
            Silahkan anda logout terlebih dahulu di  <?php echo ucfirst($vendor)?> , kemudian login kembali menggunakan id yang lain.
            
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>