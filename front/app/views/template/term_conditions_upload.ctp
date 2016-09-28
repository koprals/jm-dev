<!-- START BLOCK CSS -->
<?PHP echo $html->css('main_css')?>
<!-- END BLOCK CSS -->
<!-- START BLOCK JAVASCRIPT -->
<?PHP echo $javascript->link('jquery.latest')?>
<!-- END BLOCK JAVASCRIPT -->
<style>
html,body{
	font:12px arial, helvetica;
	color:#000000;
	text-align: left;
	margin: 0;
	background:#FFFFFF;
	padding:0;
	height:500px;
}
</style>


<div style="width:400px; border:1px solid #D3C8AA; margin:10px auto; height:500px;">
    <div class="main" style="height:50px;">
    	<div class="logo" style="border:0px solid black; padding-top:0px; margin-left:10px;">
            <a href="<?php echo $settings['site_url']?>">
                <img src="<?php echo $settings['site_url']?>img/logo_kcl.png" border="0">
            </a>
        </div>
    </div>
    <div class="line1" style="height:450px;">
    	<div class="line4" style="border:0px solid black;width:95%;">
        	<span class="text3" style="width:100%">Sarat dan Ketentuan<hr /></span>
         </div>
        <div class="line1">
        	<span class="text4" style="margin-left:10px;">
            	Anda setuju untuk mematuhi sarat dan ketentuan dari <?php echo $settings['site_name']?> untuk meng-upload photo, posting dan fitur-fitur yang ada di  <?php echo $settings['site_name']?>.
            </span>
            <div class="line1">
                <ol class="anjing">
                    <li>Semua foto/gambar yang di upload ke <b><?php echo $settings['site_name']?></b> bukan berasal dari web lain atau foto yang telah di patenkan oleh pihak tertentu.</li>
                    <li>Semua foto/gambar yang di upload ke <b><?php echo $settings['site_name']?></b> berhubungan dengan tema fitur.</li>
                    <li>Semua foto/gambar yang di upload ke <b><?php echo $settings['site_name']?></b> tidak bersifat melecehkan, mengancam atau melanggar hukum yang berlaku.</li>
                    <li>Semua foto/gambar yang di upload ke <b><?php echo $settings['site_name']?></b> tidak mengandung unsur pornografi atau yang berhubungan dengan seks atau mempromosikan materi yang berbahaya bagi anak di bawah umur.</li>
                    <li>Semua foto/gambar yang di upload ke <b><?php echo $settings['site_name']?></b> tidak bersifat provokasi terhadap ras,suku atau agama tertentu.</li>
                    <li>Semua foto/gambar yang telah di upload ke <b><?php echo $settings['site_name']?></b> dapat sewaktu waktu tidak dipublikasikan/diturunkan apabila tidak memenuhi sarat di atas.</li>
                </ol>
        	</div>
        </div>
    </div>
</div>