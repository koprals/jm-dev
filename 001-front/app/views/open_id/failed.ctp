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
        <div class="line1">Gagal koneksi ke <?php echo $vendor?>.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto; border:0px solid black;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Maaf kami tidak dapat terkoneksi dengan <?php echo ucfirst($vendor)?><br /><br />
            Silahkan <a href="javascript:void(0)" onClick="open<?php echo ucfirst($vendor)?>()" class="style1 red normal text12">klik disini </a> untuk mencobanya kembali.<br /><br />
            Atau gunakan sosial media lainnya yang tersedia.
            <div class="line kiri top40">
                <a href="javascript:void(0)" style="margin-right:10px;" onclick="openFacebook()" title="Login via Facebook" id="LoginViaFacebook"><img src="<?php echo $this->webroot?>img/facebook_ico.png" border="0"/></a>
                <a href="javascript:void(0)" style="margin-right:10px;" onclick="openTwitter()" title="Login via Twitter" id="LoginViaTwitter"><img src="<?php echo $this->webroot?>img/twitter_icon.png" border="0"/></a>
                <a href="javascript:void(0)" style="margin-right:10px;" onclick="openYahoo()" title="Login via Yahoo" id="LoginViaYahoo"><img src="<?php echo $this->webroot?>img/yahoo_icon.png" border="0"/></a>
                <a href="javascript:void(0)" style="margin-right:10px;" title="Login via Google" id="LoginViaGoogle" onclick="openGoogle()"><img src="<?php echo $this->webroot?>img/google_icon.png" border="0"/></a>
            </div>
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>