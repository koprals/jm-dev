<?php if(!empty($data)):?>

<div class="line1">
    <div class="title3">Member</div>                        
</div>
<!-- Table kiri 1 -->
<div class="line1">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;">
        <tr class="table2" height="25">
            <td width="3%" class="table_text1" style="border:1px solid #D3C8AA;">ID</td>
            <td width="8%" class="table_text1" style="border:1px solid #D3C8AA;">Foto</td>
            <td width="19%" class="table_text1" style="border:1px solid #D3C8AA;">Nama</td>
            <td width="27%" class="table_text1" style="border:1px solid #D3C8AA;">Alamat</td>
            <td width="17%" class="table_text1" style="border:1px solid #D3C8AA;">Telp</td>
            <td width="26%" class="table_text1" style="border:1px solid #D3C8AA;">Tgl Gabung</td>
        </tr>
        
        <tr class="table3" style="border:1px solid #D3C8AA;" height="20">
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
        </tr>
        <?php foreach($data as $data):?>
        <tr bgcolor="#f6f6f6">
            <td class="table_text2" style="border:1px solid #D3C8AA;"><a href="<?php echo $settings['cms_url']?>Users/Add/<?php echo $data['User']['id']?>" target="_blank" class="table_text1"><?php echo $data['User']['id']?></a></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;">
            <a href="<?php echo $settings['showimages_url']."?code=".$data['User']['id']."&prefix=_big&content=User&w=500&h=500"?>" title="<?php echo $data['Profile']['fullname']?>" rel="img-<?php echo $data['User']['id']?>">
            <img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data['User']['id']?>&prefix=_tiny&content=User&w=50&h=50"style="cursor:pointer" border="0"/>
            </a>
            </td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><a href="<?php echo $settings['cms_url']?>Users/Add/<?php echo $data['User']['id']?>" target="_blank" class="table_text1"><?php echo $data['Profile']['fullname']?></a></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['Profile']['address']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['Profile']['phone']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo date("d-M-Y : H:i:s",strtotime($data['User']['created']))?></td>
        </tr>
        <?php endforeach;?>
    </table>
</div>
<div class="line1">
    <a href="<?php echo $settings['cms_url']?>Users/Index" class="nav_6">You have <?PHP echo $count?> Members</a>
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