<?php echo $html->css("ui.daterangepicker")?>
<?php echo $html->css("redmond/jquery-ui-1.7.1.custom.css")?>
<?php echo $javascript->link("jquery-ui-1.7.1.custom.min")?>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.scrollTo")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<script>
var fade_in 	= 	500;
var fade_out	= 	500;
if($.browser.msie)
{
	fade_in 	= 100;
	fade_out	= 3500;
}

$(document).ready(function(){
	$('#transfer_date').datepicker({
		dateFormat:'yy-mm-dd',
		maxDate: 'today'
	});
	$('input,textarea').each(function(){
		$(this).bt({
		  width: 230,
		  trigger: ['focus', 'blur'],
		  positions: ['right'],
		  cornerRadius: 7,
		  strokeStyle: '#FFFFFF',
		  fill: 'rgba(136, 136, 136, 1)',
		  cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px'},
		  showTip: function(box){
			$(box).fadeIn(fade_in);
		  },
		  hideTip: function(box, callback){
			$(box).animate({opacity: 0}, fade_out, callback);
		  },
		  
		  shrinkToFit: true,
		  hoverIntentOpts: {
			interval: 0,
			timeout: 0
		  }
		});
	});
	GetTotal($("#transaction_log_id").val());
	GetPaymentMethod($("#transaction_log_id").val());
	
});

function GetTotal(transaction_log_id)
{
	$.getJSON("<?php echo $settings['site_url']?>Point/GetTotalRequestedPayment",{'trx_id':transaction_log_id},function(result){
		if(result.status == true)
		{
			$("#jml_pembayaran").html(result.data);
		}
		else
		{
			$("#jml_pembayaran").html("");
		}
	});
}

function GetPaymentMethod(transaction_log_id)
{
	$.getJSON("<?php echo $settings['site_url']?>Point/GetPaymentMethod",{'trx_id':transaction_log_id},function(result){
		if(result.status == true)
		{
			$("#metode_pembayaran").html(result.data);
		}
		else
		{
			$("#metode_pembayaran").html("");
		}
	});
}

function SubmitConfirmation()
{
	$("#SubmitConfirmationForm").ajaxSubmit({
			url			: "<?php echo $settings['site_url']?>Point/KonfirmasiPembayaranProcess",
			type		: "POST",
			dataType	: "json",
			clearForm	: false,
			beforeSend	: function()
			{
				$("#LoadingPict").show(300);
			},
			complete	: function(data,html)
			{
			},
			error		: function(XMLHttpRequest, textStatus,errorThrown)
			{
				alert(textStatus);
			},
			success		: function(data)
			{
				$("#output").html(data);
				$("#LoadingPict").hide(300);
				
				$("span[id^=err]").html('');
				$("span[id^=img]").html('');
				
				if(data.status==true)
				{
					location.href	=	data.message.url;
				}
				else
				{
					var err		=	data.message;
					var scrool	=	"";
					var count	=	0;
					$.each(err, function(i, item){
						
						if(item.status=="false")
						{
							$("#err_"+item.key).html(item.value);
							$("#img_"+item.key).html('<img src="<?php echo $this->webroot?>img/icn_error.png">');	
							count++;
						}
						else if(item.status=="true")
						{
							$("#err_"+item.key).html("");
							$("#img_"+item.key).html('<img src="<?php echo $this->webroot?>img/check.png">');
							
						}
						else if(item.status=="blank")
						{
							$("#err_"+item.key).html("");	
						}
						
						if(count==1 && item.status=="false")
						{
							scrool	=	"#span_"+item.key;
						}
						
					});
					$(document).scrollTo(scrool, 800);
				}
			}
		});
		return false;
}

</script>
<div id="output"></div>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Konfirmasi Pembayaran</div>
        </div>
		<div class="line back1 size100 kiri position1 rounded2" style="padding-bottom:10px;">
			<div class="line top10">
				<div class="size93 kiri left15">
					<div class="rounded4 kiri size100" style="background-color:#595959; padding:10px;">
						<?php echo $form->create("Confirmation",array("onsubmit"=>"return SubmitConfirmation()","id"=>"SubmitConfirmationForm"))?>
						<div class="kiri size95 left10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_user_id">
									Nama Lengkap
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">: <?php echo $profile['Profile']['fullname']?></span>
									<span class="kiri left10" id="img_user_id"></span>
									<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_user_id"></span>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_user_email">
									Email
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">: <?php echo $profile['User']['email']?></span>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_transaction_log_id">
									Invoice. No
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">:</span><?php echo $form->input("transaction_log_id",array("options"=>$transactions,"default"=>$selected_trx_id,"div"=>false,"label"=>false,"class"=>"input3 style1 black text12 size45 kiri","onchange"=>" GetTotal(this.value);GetPaymentMethod(this.value)","id"=>"transaction_log_id"))?>
									<span class="kiri left10" id="img_transaction_log_id"></span>
									<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_transaction_log_id"></span>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_transfer_date">
									Tgl Pembayaran
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">:</span><?php echo $form->input("transfer_date",array("div"=>false,"label"=>false,"class"=>"input3 style1 black text12 size45 kiri","id"=>"transfer_date","readonly"=>"readonly"))?>
									<span class="kiri left10" id="img_transfer_date"></span>
									<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_transfer_date"></span>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_transfer_required_value">
									Jumlah Pembayaran
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">:</span> <span id="jml_pembayaran" class="kiri right5"></span>
									<span class="kiri left10" id="img_transfer_required_value"></span>
									<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_transfer_required_value"></span>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_payment_method_id">
									Metode Pembayaran
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<div class="kiri size3 right5">:</div>
									<div class="kiri size70 right5" id="metode_pembayaran" style="height:50px;"></div>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_bank_name">
									Dari Bank
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">:</span><?php echo $form->input("bank_name",array("div"=>false,"label"=>false,"class"=>"input3 style1 black text12 size45 kiri","title"=>"Masukkan nama bank tempat anda mentransfer"))?>
									<span class="kiri left10" id="img_bank_name"></span>
									<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_bank_name"></span>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_bank_account_name">
									Nama Rekening
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">:</span><?php echo $form->input("bank_account_name",array("div"=>false,"label"=>false,"class"=>"input3 style1 black text12 size45 kiri","title"=>"Masukkan nama akun/nama rekening bank tempat anda mentransfer"))?>
									<span class="kiri left10" id="img_bank_account_name"></span>
									<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_bank_account_name"></span>
								</div>
							</div>
						</div>
						<div class="kiri size95 left10 top10" style="border:0px solid black;">
							<div class="kiri size100 ">
								<div class="kiri size20 style1 white bold text13 top5" id="span_message">
									Pesan
								</div>
								<div class="kiri size70 left10 style1 text12 white top5">
									<span class="kiri right5">:</span><?php echo $form->input("message",array("div"=>false,"label"=>false,"class"=>"input3 style1 black text12 size65 kiri","type"=>"textarea","title"=>"Jika anda memiliki pesan untuk tim support kami, silahkan anda masukkan di sini."))?>
									<span class="kiri left10" id="img_message"></span>
									<span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_message"></span>
								</div>
							</div>
						</div>
						<div class="tengah size30">
							<div class="kiri top30">
								<input type="button" name="button" value="Konfirmasi" class="tombol1" onclick="SubmitConfirmation()" style="float:left"/>
								<img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPict" style="float:left; display:none; margin:5px 0 0 5px;"/>
							</div>
						</div>
						<?php echo $form->end();?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>