<?php if(!empty($data)):?>
<div class="line1">
    <div class="title3">Daftar Iklan</div>                        
</div>
<!-- Table kiri 1 -->
<div class="line1">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;">
        <tr class="table2" height="25">
            <td width="3%" class="table_text1" style="border:1px solid #D3C8AA;">ID</td>
            <td width="16%" class="table_text1" style="border:1px solid #D3C8AA;">Kategori</td>
            <td width="20%" class="table_text1" style="border:1px solid #D3C8AA;">Sub Kategori</td>
            <td width="18%" class="table_text1" style="border:1px solid #D3C8AA;">Harga</td>
            <td width="22%" class="table_text1" style="border:1px solid #D3C8AA;">Penjual</td>
            <td width="21%" class="table_text1" style="border:1px solid #D3C8AA;">Status</td>
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
            <tr bgcolor="#f6f6f6" height="30">
                <td class="table_text2" style="border:1px solid #D3C8AA;"><a href="<?php echo $settings['cms_url']?>Product/Edit/<?php echo $data['Product']['id']?>" target="_blank" class="table_text1"><?php echo $data['Product']['id']?></a></td>
                <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['Product']['category']?></td>
                <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['Product']['subcategory']?></td>
                <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?></td>
                <td class="table_text2" style="border:1px solid #D3C8AA;"><a href="<?php echo $settings['cms_url']?>Users/Add/<?php echo $data['Product']['user_id']?>" target="_blank" class="table_text1"><?php echo $data['Product']['contact_name']?></a></td>
                <td class="table_text2" style="border:1px solid #D3C8AA;">
					<?php echo $product->LinkStatus($data['Productstatus']['name'],'_blank')?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
	<?php if(!empty($c_w_a)):?>
        <div class="line1">
            <a href="<?php echo $settings['cms_url']?>WaitingApprovalProduct/Index" class="nav_6"><?php echo $c_w_a?> Waiting Approval</a>
        </div>
    <?php endif;?>
    <?php if(!empty($c_w_aae)):?>
        <div class="line1">
            <a href="<?php echo $settings['cms_url']?>AfterEditingProduct/Index" class="nav_6"><?php echo $c_w_aae?> Waiting Approval After Editing</a>
        </div>
    <?php endif;?>
    <?php if(!empty($c_e_r)):?>
        <div class="line1">
            <a href="<?php echo $settings['cms_url']?>EditingRequiredProduct/Index" class="nav_6"><?php echo $c_e_r?> Editing Required</a>
        </div>
    <?php endif;?>
    <?php if(!empty($c_a)):?>
        <div class="line1">
            <a href="<?php echo $settings['cms_url']?>ApproveProduct/Index" class="nav_6"><?php echo $c_a?> Approval</a>
        </div>
    <?php endif;?>
<?php endif;?>