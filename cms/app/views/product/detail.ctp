<script>

function CloseDetail()
{
	$("#detail").hide(300);
}
</script>
<div class="line1" style="margin-top:30px;">
  <div class="table1" id="table_detail">
        <div class="line2" style="float:left; width:105px; padding-left:10px; font-size:12px;">
            <b>Detil Iklan - ID : 56</b>
        </div>
        <div class="line2" style="float:right; width:50px; padding-left:10px;">
            <a href="javascript:void(0)" style="font-weight:bold; text-decoration:none; color:#000;" onclick="CloseDetail();">close <img src="<?php echo $this->webroot?>img/x_blue.gif" border="0"/></a>
        </div>
    </div>
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;">
    	<tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">ID</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $id?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">User ID</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $user_id?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Data Type</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $data_type?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Penjual</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $contact_name?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Merk</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $parent_name?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Tipe</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $category_name?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Telp</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $telp?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">YM</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $ym?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Alamat</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $address?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Propinsi</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $province_name?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Kota</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $city_name?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Kondisi</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $conditions?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Nopol</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $nopol?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Thn Pembuatan</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $thn_pembuatan?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Warna</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $color?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Kilometer</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $kilometer?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Deskripsi</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $description?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">STNK</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $stnk?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">BPKB</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $bpkb?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Harga</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $price?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Kredit</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $is_credit?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Angsuran Pertama</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $first_credit?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Jumlah Angsuran</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $credit_interval?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Angsuran per bulan</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $credit_per_month?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Status</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><b><?php echo $status?></b></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">TglInput</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $created?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Edit Terakhir</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $modified?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Diedit oleh</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $modified_by?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Tgl Disetujui</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $approved?></td>
    	</tr>
        <tr bgcolor="#f6f6f6" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Disetujui Oleh</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $approved_by?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Catatan</td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%"><?php echo $notice?></td>
    	</tr>
        <tr bgcolor="#ffffff" height="25">
          <td colspan="2" class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">Foto</td>
        </tr>
        <tr bgcolor="#ffffff" height="25">
            <td colspan="2" class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold">
				<div class="line3" style="border:0px solid black;width:500px; margin:auto; float:none;" id="photo">
                    <div class="line3" style="border:0px solid black; width:500px;" id="photo">
                        <?php foreach($img as $img):?>
                        <div class="left" style="margin-right:10px; width:150px;border:0px solid black; height:150px;">
                            <div class="image_box7" style="margin-left:0px; float: left;width:128px; height:128px;">
                                <a href="javascript:void(0)" onclick="$.prettyPhoto.open('<?php echo $settings['showimages_url']."?code=".$img['id']."&prefix=_zoom&content=ProductImage&w=500&h=500"?>');" rel="zoom" path='img-zoom-<?php echo $img['id']?>'>
                                    <img src="<?php echo $settings['showimages_url']."?code=".$img['id']."&prefix=_prevthumb&content=ProductImage&w=128&h=128"?>" border="0"/>
                                </a>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </td>
        </tr>
        <tr bgcolor="#ffffff" height="25">
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center; width:50%; font-weight:bold" colspan="2">
            	<a href="javascript:void(0)" style="font-weight:bold; text-decoration:none; color:#000;" onclick="$(document).scrollTo('#table_detail', 500);">Back to Top <img src="<?php echo $this->webroot?>img/up.png" border="0" style=" vertical-align:middle;"/></a>
            </td>
    	</tr>
    </table>
</div>