<div class="box_panel">
	<div class="line1" style=" margin-bottom:10px;">
        <div class="line4" style="border:0px solid black;">
            <span class="text3">Validasi Email</span>
        </div>
    </div>
    <div class="line1" style="border:0px solid black;">
    	<div class="line1" style="border:0px solid black;margin-left:10px; width:97%">
            <span class="text4" >Hallo <b><?php echo $data['Profile']['fullname']?></b>, anda telah mengaktifkan user anda pada tanggal <b><?php echo date("d-M-Y H:i:s",strtotime($data['User']['activated']))?></b>. <br><br>Untuk saat ini anda sudah dapat menggunakan email dan password anda untuk login di <?php echo $settings['site_title']?>.</span>
        </div>
    </div>
    
</div>