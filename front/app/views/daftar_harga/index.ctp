<?php echo $this->requestAction("/Template/DaftarHarga/{$current_category_id}",array("return"))?>

<?php if(!empty($data)):?>
<?php echo $paginator->options(array(
				'url'	=> implode("/",$this->params['pass'])
				));
?>
<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
<style>
#pagination-digg a{
	border:1px solid grey;
}
#pagination-digg a:hover
{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
	border:1px solid #D3D1D1;
}
#pagination-digg .current{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
}
</style>
<div class="kiri bottom5 size100 top20">
    <div class="paging-box" style="width:auto;float:right; border:0px solid black;">
        <ul id="pagination-digg" style="border:0px solid black; margin-left:-40px;">
            <?php  $paginator->counter(array('format' => 'Page %page% of %pages%'));?>
            <?php echo $paginator->prev("Prev",array('class'=>'next',"escape"=>false),'Prev',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
            <?php echo $paginator->numbers(array('modulus'=>4,'separator'=>null,'class'=>'navigasi1','span'=>false,'current'=>'current')); ?>
            <?php echo $paginator->next("Next",array('class'=>'next',"escape"=>false),'Next',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
        </ul>  
    </div>
</div>

<?php endif;?>
<div class="kiri size100">
<table width="100%" cellspacing="1" bgcolor="#ffffff" style="border:1px solid grey; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;">
  <tr style="background-color:#5E5E5E; text-align:center; font-weight:bold;">
    <td width="22%" height="28"><?php echo $paginator->sort('Merk', 'Parent.name',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
    <td width="28%"><?php echo $paginator->sort('Tipe', 'Category.name',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
    <td width="16%"><?php echo $paginator->sort('Tahun', 'DaftarHarga.thn_pembuatan',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
    <td width="28%">Harga</td>
  </tr>
  <?php foreach($data as $data):?>
  <tr style="color:#fff; background-color:#898989; font-weight:bold;">
    <td height="29" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
		<a href="<?php echo $settings['site_url']?>DaftarMotor/<?php echo $data["Parent"]["id"]?>/all_cities/motor_<?php echo $general->seoUrl($data["Parent"]["name"])?>.html" class="style1 white text12 normal"><?php echo $data["Parent"]["name"]?></a>
    </td>
    <td style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><a href="<?php echo $settings['site_url']?>DaftarMotor/<?php echo $data["Category"]["id"]?>/all_cities/motor_<?php echo $general->seoUrl($data["Parent"]["name"]." ".$data["Category"]["name"])?>.html" class="style1 white text12 normal"><?php echo $data["Category"]["name"]?></a></td>
    <td align="center" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["DaftarHarga"]["thn_pembuatan"]?></td>
    <?PHP if($data["0"]["MIN"] != $data["0"]["MAX"]):?>
    <td align="left" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><?php echo $number->format($data["0"]["MIN"],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?> - <?php echo$number->format($data["0"]["MAX"],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?></td>
    <?php else:?>
    <td width="6%" align="left" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><?php echo $number->format($data["0"]["MIN"],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?></td>
    <?php endif;?>
  </tr>
  <?php endforeach;?>
</table>
</div>
<?php else:?>
<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1">Daftar harga tidak ditemukan.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Maaf daftar harga motor yang anda cari tidak kami temukan.<br /><br />
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
<?php endif;?>