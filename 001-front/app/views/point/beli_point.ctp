<?php echo $javascript->link("jquery.boxy")?>
<?php echo $html->css("boxy")?>
<script>
function BeliVoucher(id, val,price)
{
	Boxy.confirm("<div style='display:block;float:left;border:0px solid black;'><div style='margin-top:10px;border:0px solid black;float:left'>Anda yakin akan melakukan pembelian Vaoucher JmPoin Sebesar "+val+" dengan harga Rp "+price+",- ?</span></div>",
	function() {
		Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Mohon tunggu..</span></div>",
		function(){
		
		},
		{
			title:'Mohon Tunggu'
		});
				
		$.getJSON("<?php echo $settings['site_url']?>Point/BeliPointProcess",{"data[Voucher][id]":id},function(data){
			if(data.status  == false)
			{
				Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>"+data.message+"</span></div>",
				function(){
				
				},
				{
					title:'Pembelian voucher gagal.'
				});
			}
			else
			{
				window.location.href="<?php echo $settings['site_url']?>Point/KonfirmasiBeliPoin";
			}
		});
	},
	{
		title:"Konfirmasi pembelian voucher"
	});
	return false;
}
</script>
<div id="output"></div>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Beli JmPoin</div>
        </div>
        <div class="line back1 size100 kiri position1 rounded2" style="padding-bottom:10px;">
			<div class="line top20">
				<div class="size90 rounded4 tengah top20" style="background-color:#595959; padding:10px;">
					<div class="bold style1 white size100 text12 kiri bottom10">Apakah JmPoin itu..?</div>
					<div class="normal style1 white size100 text12">Dengan menggunakan JmPoin anda bisa mempromosikan iklan anda dengan lebih intensif, sehingga peluang kesempatan iklan anda dilihat oleh calon pembeli lebih besar.<br><br>
					Selain itu JmPoint juga dapat digunakan untuk pembelian template fremium yang dikhususkan untuk para dealer agar dealer memiliki custom template sendiri untuk mempromosikan motor-motor yang akan dijual.
					JmPoin dapat dibeli dengan menggunakan sistem pembayaran yang disediakan oleh <?php echo $settings['site_name']?>
					</div>
				</div>
			</div>
			<div class="line top50">
				<div class="size93 tengah">
					<div class="style1 white size100 text14 kiri bottom10 bold">JmPoin Anda saat ini:</div>
				</div>
			</div>
			<div class="line">
				<div class="size93 tengah">
					<div class="kiri rounded4 text20 white style1 bold right10" style=" min-width:60px;background-color:#595959; padding:10px; text-align:center">
						<?php echo $user_point?>
					</div>
					<div class="kiri size80 text12 white style1">
						Semakin banyak JmPoin anda, semakin cepat motor anda terjual. Promosi dapat dilakukan setiap saat selama iklan anda aktif. Ayo buruan promosikan iklan anda!
					</div>
				</div>
			</div>
			<div class="line top50">
				<div class="size93 tengah">
					<div class="style1 white size100 text14 kiri bottom10 bold">Silahkan pilih voucher JmPoin yang anda inginkan:</div>
				</div>
			</div>
			
			<div class="line top20 bottom10">
				<div class="tengah size93">
					<?php if(!empty($big)):?>
					<center><a href="javascript:void(0)" style="border:none;" onclick="return BeliVoucher(<?php echo $big["Voucher"]["id"]?>,<?php echo $big["Voucher"]["value"]?>,'<?php echo number_format($big["Voucher"]["price"],0,"",".")?>')"><img src="<?php echo $this->webroot?><?php echo $big["Voucher"]["icon"]?>" alt="voucher<?php echo $big["Voucher"]["value"]?>" border="0"/></a></center>
					<?php endif;?>
					<?php $count=0;?>
					<?php $total	=	count($vouchers);?>
					<?php foreach($vouchers as $vouchers):?>
					<?php $right30	=	($count < ($total-1)) ? "right30" : ""?>
					<div class="kiri <?php echo $right30?> top40">
						<a href="javascript:void(0)" style="border:none;" onclick="return BeliVoucher(<?php echo $vouchers["Voucher"]["id"]?>,<?php echo $vouchers["Voucher"]["value"]?>,'<?php echo number_format($vouchers["Voucher"]["price"],0,"",".")?>')"><img src="<?php echo $this->webroot?><?php echo $vouchers["Voucher"]["icon"]?>" alt="voucher<?php echo $vouchers["Voucher"]["value"]?>" border="0"/></a>
					</div>
					<?php $count++;?>
					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>
</div>