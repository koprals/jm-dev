<?php if(!empty($data)):?>

<div class="line1">
    <div class="title3">Hubungi Kami</div>                        
</div>
<!-- Table kiri 1 -->
<div class="line1">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;">
        <tr class="table2" height="25">
            <td width="18%" class="table_text1" style="border:1px solid #D3C8AA;">Nama</td>
            <td width="26%" class="table_text1" style="border:1px solid #D3C8AA;">Email</td>
            <td width="28%" class="table_text1" style="border:1px solid #D3C8AA;">Kategori</td>
            <td width="28%" class="table_text1" style="border:1px solid #D3C8AA;">Tgl Input</td>
        </tr>
        
        <tr class="table3" style="border:1px solid #D3C8AA;" height="20">
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
        </tr>
        <?php foreach($data as $data):?>
        <tr bgcolor="#f6f6f6">
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['Contact']['from']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;">
            <?php echo $data['Contact']['email']?>
            </td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['ContactCategory']['name']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo date("d-M-Y H:i:s",strtotime($data['Contact']['created']))?></td>
        </tr>
        <?php endforeach;?>
    </table>
</div>
<div class="line1">
    <a href="<?php echo $settings['cms_url']?>Pesan/Saran" class="nav_6"><?php echo $c_saran?> Saran</a>
</div>
<div class="line1">
    <a href="<?php echo $settings['cms_url']?>Pesan/Testimoni" class="nav_6"><?php echo $c_testimoni?> Testimoni</a>
</div>
<div class="line1">
    <a href="<?php echo $settings['cms_url']?>Pesan/Pertanyaan" class="nav_6"><?php echo $c_pertanyaan?> Pertanyaan</a>
</div>
<div class="line1">
    <a href="<?php echo $settings['cms_url']?>Pesan/Index" class="nav_6">You have <?PHP echo $count?> message</a>
</div>
<script>
$(document).ready(function(){
	$("a[rel^='img-']").bind("click",function(e){
		e.preventDefault();
		$.prettyPhoto.open($(this).attr('href'));
	});
})
</script>
<?php endif;?>