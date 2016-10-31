<table width="582" cellspacing="1" bgcolor="#ffffff" style="border:1px solid grey; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;">
    <tr style="background-color:#AC0202; font-weight:bold;">
        <td width="192" rowspan="2" align="center">Rincian Kredit</td>
        <td height="25" colspan="4" align="center">Jangka waktu kredit</td>
    </tr>
    <tr style="background-color:#5E5E5E; text-align:center; font-weight:bold;">
        <td width="93" height="21">11 Bulan</td>
        <td width="93">23 Bulan</td>
        <td width="93">35 Bulan</td>
        <td >47 Bulan</td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px; font-weight:bold;">Angsuran - Estimasi</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Harga Motor (Rp) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["harga"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["harga"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["harga"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["harga"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Uang Muka (<?php echo $this->data["Product"]["dppersen"]?>%) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Pokok Hutang /Plafon Kredit(Rp) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ph"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ph"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ph"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ph"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Suku Bunga Kredit (%) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $this->data["Product"]["bunga"]?>%</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $this->data["Product"]["bunga"]?>%</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $this->data["Product"]["bunga"]?>%</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $this->data["Product"]["bunga"]?>%</td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Angsuran Pokok (Rp) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ap"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ap"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ap"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ap"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Bunga flat per Bulan (Rp) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["bf"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["bf"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["bf"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["bf"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#FFFA75" style="padding-right:10px;">Angsuran per Bulan (Rp):</td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["ab"][0]?></td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["ab"][1]?></td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["ab"][2]?></td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["ab"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px; font-weight:bold;">Pembayaran Pertama - Estimasi</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7">&nbsp;</td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Uang Muka (<?php echo $this->data["Product"]["dppersen"]?>%) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["dp"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Angsuran pertama (Rp) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ab"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ab"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ab"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ab"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Administrasi (Rp) :</td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ad"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ad"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ad"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["ad"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#bbbbbb" style="padding-right:10px;">Asuransi (3,6%) :</td>
         <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["as"][0]?></td>
        <td style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["as"][1]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["as"][2]?></td>
        <td style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["as"][3]?></td>
    </tr>
    <tr style="color:#333;text-align:right;">
        <td height="27" bgcolor="#FFFA75" style="padding-right:10px;">Pembayaran Awal (Rp):</td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["pa"][0]?></td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7; padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["pa"][1]?></td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["pa"][2]?></td>
        <td bgcolor="#FFFA75" style="border-right:1px dotted #c7c7c7;padding-right:10px;border-bottom:1px dotted #c7c7c7; font-weight:bold;"><?php echo $data["pa"][3]?></td>
    </tr>
</table>