<?php if(!empty($data)):?>
<?php echo $javascript->link("jquery.bt")?>
<script>
var fade_in 	= 	500;
var fade_out	= 	500;
if($.browser.msie)
{
	fade_in 	= 100;
	fade_out	= 3500;
}
$(document).ready(function(){
	$('a[rel^=help]').each(function(){
		$(this).bt({
		  width: 230,
		  trigger: ['hover'],
		  positions: ['right'],
		  cornerRadius: 7,
		  strokeStyle: '#FFFFFF',
		  fill: 'rgba(136, 136, 136, 1)',
		  showTip: function(box){
			$(box).fadeIn(fade_in);
		  },
		  hideTip: function(box, callback){
			$(box).animate({opacity: 0}, fade_out, callback);
		  },
		  cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px'},
		  shrinkToFit: true,
		  hoverIntentOpts: {
			interval: 0,
			timeout: 0
		  }
		});
	});
});

function SeeNotice(transaction_log_id)
{
	$.getJSON("<?php echo $settings['site_url']?>Point/SeeNotice/"+transaction_log_id,function(data){
		if(data.status == true)
		{
			$("#alert").show(200);
			$("#notice").html(data.message);
		}
		else
		{
			$("#alert").show(200);
			$("#notice").html("Maaf data notice tidak ditemukan");
		}
	});
}
</script>
<?php $paginator->options(array(
	'url'	=> array(
		'controller'	=> 'Point',
		'action'		=> 'TransactionHistoryList'
	),
	'onclick'=>"return onClickPage(this,'#transaction_data');")
);?>
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
<div class="kiri bottom5 size100 top10">
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
		<tr style="background-color:#5E5E5E; text-align:center; font-weight:bold;" height="29">
			<td width="15%"><?php echo $paginator->sort('Tanggal', 'TransactionLog.created',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
			<td width="15%"><?php echo $paginator->sort('Invoice ID', 'TransactionLog.invoice_id',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
			<td width="15%"><?php echo $paginator->sort('Poin', 'TransactionLog.voucher_value',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
			<td width="15%"><?php echo $paginator->sort('Harga', 'TransactionLog.total',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
			<td width="25%"><?php echo $paginator->sort('Status', 'TransactionLog.SStatus',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
			<td>Konfirmasi</td>
		</tr>
		
		
		<?php foreach($data as $data):?>
		
		<tr style="color:#fff; background-color:#898989; font-weight:bold;">
			<td height="35" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
				<?php echo date("d-M-Y",$data["TransactionLog"]["created"])?>
			</td>
			<td height="35" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
				<?php echo $data["TransactionLog"]["invoice_id"]?>
			</td>
			<td height="35" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
				<?php echo $number->format($data['TransactionLog']['voucher_value'],array("thousands"=>".","before"=>null,"places"=>null,"after"=>null))?>
			</td>
			<td height="35" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
				<?php echo $number->format($data['TransactionLog']['total'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>",-"))?>
			</td>
			<td height="35" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
				<?php echo $data["TransactionLog"]["SStatus"]?>
				<?php if($data["TransactionLog"]["status"]=="0"):?>
				<a href="javascript:void(0)" style="text-decoration:none; color:#0000FF;" rel="help" title="Klik disini untuk mengetahui alasan mengapa transaksi anda terpending." onclick="SeeNotice('<?php echo $data['TransactionLog']['id']?>')">&nbsp;<img src="<?php echo $settings['site_url']?>img/help.png" style="border:0px solid black;" alt="pending" border="0"/></a>
				<?php endif;?>
			</td>
			<td height="35" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
				<?php if($data["TransactionLog"]["status"]=="-2" or $data["TransactionLog"]["status"]=="0"):?>
				<input type="button" name="button" value="konfirmasi" class="tombol1 right10" onClick="location.href='<?php echo $settings['site_url']?>Point/KonfirmasiPembayaran/<?php echo $data["TransactionLog"]["id"]?>'"/>
				<?php else:?>
				-
				<?php endif;?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
</div>
<div class="kiri size100 top20" style="display:none;" id="alert">
	<div class="box_alert">
		<div class="alert">
			<div class="kiri" style="border:0px solid black; width:750px;">
				<div class="kiri text12 style1" id="notice"></div>
			</div>
		</div>
	</div>
</div>
<?php else:?>
<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1">Daftar Transaksi Tidak Ditemukan</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Maaf daftar transaksi anda masih kosong.<br /><br />
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
<?php endif;?>