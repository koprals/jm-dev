<?php echo $javascript->link("jquery.boxy")?>
<?php echo $html->css("boxy")?>
<script>
Boxy.DEFAULTS.title = 'Title';

function Ok()
{
	$.prettyPhoto.close();
	Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Email telah kami kirimkan.</span></div>",function(){},{title:'Email Verifikasi.'});
}
</script>


<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3 top10">
        <div class="line1">Email belum tervalidasi.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Email anda belum tervalidasi, silahkan klik link yang tersedia di email verifikasi yang kami kirimkan ketika anda pertama kali registrasi.<br /><br />
            Jika email tidak terdapat di dalam folder inbox, silahkan periksa folder spam anda.<br /><br />
            Atau <a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Users/ResendVerification?iframe=true&amp;width=510&amp;height=150')" class="text12 style1 red normal bold">klik di sini</a> untuk mencoba mengirim kembali email verifikasi ke email anda.<br /><br />
            Atau anda dapat mengirim email ke <b><?php echo $settings['support_mail']?></b> untuk mendapatkan bantuan dari admin kami.
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
