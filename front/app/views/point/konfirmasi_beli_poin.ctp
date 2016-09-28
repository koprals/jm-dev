

<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Konfirmasi Pembelian Voucher JmPoin</div>
        </div>
		<div class="line back1 size100 kiri position1 rounded2" style="padding-bottom:10px;">
			<div class="line top20">
				<div class="size93 kiri left15">
					<div class="rounded4 kiri size100" style="background-color:#595959; padding:10px;">
						<div class="kiri size100 style1 white bold text13" style="border-bottom:1.5px solid white; padding-bottom:5px;">
							Pemesanan Voucher JmPoin Sukses, berikut detail transaksi anda:
						</div>
						<div class="kiri size100 top20">
							<div class="kiri size30 style1 white normal text13" id="span_newpassword">No. Invoice</div>
							<div class="kiri size60 left10 style1 white normal text13">: <?php echo $data['TransactionLog']['invoice_id']?></div>
						</div>
						<div class="kiri size100 top10">
							<div class="kiri size30 style1 white normal text13" id="span_newpassword">Jumlah Poin</div>
							<div class="kiri size60 left10 style1 white normal text13">: <?php echo $number->format($data['TransactionLog']['voucher_value'],array("thousands"=>".","places"=>0,"after"=>" poin","before"=>""))?></div>
						</div>
						<div class="kiri size100 top10">
							<div class="kiri size30 style1 white normal text13" id="span_newpassword">Harga</div>
							<div class="kiri size60 left10 style1 white normal text13">: <?php echo $number->format($data['TransactionLog']['total'],array("thousands"=>".","places"=>0,"before"=>"Rp "))?></div>
						</div>
						<div class="kiri size100 top10">
							<div class="kiri size30 style1 white normal text13" id="span_newpassword">Tanggal Pembelian</div>
							<div class="kiri size60 left10 style1 white normal text13">: <?php echo date("d-M-Y",$data['TransactionLog']['created'])?></div>
						</div>
						<div class="kiri size100 top10">
							<div class="kiri size30 style1 white normal text13" id="span_newpassword">Batas Waktu Pembayaran</div>
							<div class="kiri size60 left10 style1 white normal text13">: <?php echo date("d-M-Y",$data['TransactionLog']['expired'])?></div>
						</div>
						<div class="kiri size100 top10">
							<div class="kiri size30 style1 white normal text13" id="span_newpassword">Metode Pembayaran</div>
							<div class="kiri size60 left10 style1 white normal text13">: <?php echo $data['PaymentMethod']['name']?></div>
						</div>
						<div class="kiri size100 top20 style1 white normal text13">
							Harap melakukan pembayaran sebelum batas waktu pembayaran.
						</div>
						<div class="kiri size100 top50 style1 white normal text13">
							Catatan:<br>
							<ul>
								<li>Cantumkan nomor invoice pada kolom berita saat melakukan transfer melalui ATM, m-banking atau i-banking.</li>
								<li>Invoice juga telah dikirimkan ke email Anda Silahkan cek untuk info lebih detail.</li>
								<li>Harap mengisi form konfirmasi jika anda telah melakukan pembayaran.</li>
								<li>Jumlah JmPoin anda akan bertambah setelah ada konfirmasi dari kami.</li>
							</ul>
						</div>
						<div class="tengah size30">
							<div class="line kiri top20 bottom20">
								<input type="button" name="button" value="KEMBALI" class="tombol1 right10" onClick="location.href='<?php echo $settings['site_url']?>Point/BeliPoint'"/>
								<input type="button" name="button" value="KONFIRMASI" class="tombol1" onclick="location.href='<?php echo $settings['site_url']?>Point/KonfirmasiPembayaran'"/>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="kiri size95 left10" style="border:0px solid black;">
                
			</div>
		</div>
	</div>
</div>