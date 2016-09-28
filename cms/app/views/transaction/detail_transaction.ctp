<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.scrollTo")?>

<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<script>
var ROOT		=	"<?php echo $settings['web_url']?>";
imagesDir		=	"<?php echo $this->webroot?>img/wysiwyg/";
popupsDir		=	"<?php echo $this->webroot?>wysiwyg_popup/";
wysiwygWidth	=	500;
wysiwygHeight	=	200;
</script>

<?php echo $html->css('wysiwyg/styles.css')?>
<?php echo $javascript->link('wysiwyg.js')?>
<script>
var fade_in 	= 	500;
var fade_out	= 	500;
if($.browser.msie)
{
	fade_in 	= 	100;
	fade_out	= 	3500;
}
$(document).ready(function(){
});


function ChangeStatus(status,transaction_log_id,confirmation_id)
{
	if(status != "")
	{
		$("#text_message").show(300);
		$.get("<?php echo $settings['cms_url']?>Transaction/GetStatusMessage",{"status":status,'transaction_log_id':transaction_log_id,"confirmation_id":confirmation_id},function(data){
			$("#pesan").html('<?php echo $form->textarea("TransactionLog.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px","id"=>"AdminMessage"))?>');
			$("#AdminMessage").val(data);
			generate_wysiwyg('AdminMessage');
		});
		
		if(status=="0")
		{
			$("#notice").show(300);
		}
		else
		{
			$("#notice").hide(300);
		}
	}
	else
	{
		$("#text_message").hide(300);
		$("#notice").hide(300);
	}
}

function SubmitEdit()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("AdminMessage");
	}
	updateTextArea("AdminMessage");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	

	$("#EditForm").ajaxSubmit({
		url			: "<?php echo $settings['cms_url']?>Transaction/ProcessEdit",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19.gif"/>Please wait ..');
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert("Maaf koneksi anda terputus, cobalah beberapa saat lagi.");
		},
		success		: function(data)
		{
			$("#output").html(data);
			$("#loading").html('');
			
			$("span[id^=err]").html('')
			$("span[id^=img]").html('')
			
			if(data.status==true)
			{
				alert(data.message);
				location.href='<?php echo $settings['cms_url']?>Transaction/Index';
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
						scrool	=	"#err_"+item.key;
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

<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code))?>
<div class="test-right">
    <div class="content">
		<?php if(!empty($data)):?>
		<?php echo $form->create('TransactionLog',array("onsubmit"=>"return SubmitEdit()","id"=>"EditForm"))?>
        <?php echo $form->input("id",array("type"=>"hidden","readonly"=>true,"value"=>$data['TransactionLog']['id']))?>
		<div class="line1">
            <div class="line3">
                <div class="left" style="width:100%;">
                    <span class="nav_2">Detail Transaction</span>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Invoice.id</b></div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<div style="float:left; display:block; width:5%;">:</div>
							<div style="float:left; display:block; width:80%;">
								<?php echo $data["TransactionLog"]["invoice_id"]?>
							</div>
                        </div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Fullname</b></div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<div style="float:left; display:block; width:5%;">:</div>
							<div style="float:left; display:block; width:80%;">
								<?php echo $data["Profile"]["fullname"]?>
							</div>
                        </div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Email</b></div>
                        </div>
                        <div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $data["User"]["email"]?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Payment Method</b></div>
                        </div>
                        <div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $data["PaymentMethod"]["name"]?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Jml Point</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $number->format($data['TransactionLog']['voucher_value'],array("thousands"=>".","before"=>null,"places"=>null,"after"=>" poin"))?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Basic Price</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $number->format($data['TransactionLog']['basic_price'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>",-"))?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Extra</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $number->format($data['TransactionLog']['extra'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null))?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Tax</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $number->format($data['TransactionLog']['tax'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null))?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Total</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $number->format($data['TransactionLog']['total'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>",-"))?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Status</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $data['TransactionLog']['SStatus']?>
						</div>
                    </div>
					<?php if(!empty($data['TransactionPendingLog'])) :?>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Message From Admin</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $data['TransactionPendingLog']['message']?>
						</div>
                    </div>
					<?php endif;?>
					<?php if(($data['TransactionLog']['status'] == "-1") or ($data['TransactionLog']['status']=="0")):?>
					<?php echo $form->input("confirm_id",array("type"=>"hidden","value"=>$confirmation["Confirmation"]["id"],"readonly"=>"readonly"))?>
					<span class="nav_2" style="margin-top:30px;">User Confirmation</span>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Transfer Date</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo date("l, d-M-Y",strtotime($confirmation["Confirmation"]["transfer_date"]))?> (<?php echo $time->timeAgoInWords($confirmation["Confirmation"]["transfer_date"])?>)
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Bank Name</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $confirmation["Confirmation"]["bank_name"]?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Bank Account Name</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $confirmation["Confirmation"]["bank_account_name"]?>
						</div>
                    </div>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Message</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
							<?php echo $confirmation["Confirmation"]["message"]?>
						</div>
                    </div>
					
					<span class="nav_2" style="margin-top:30px;">Update status</span>
					<div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><b>Change Status To</b></div>
                        </div>
						<div style="float:left; display:block; width:5%;">:</div>
						<div style="float:left; display:block; width:80%;">
                            <?php echo $form->input("status",array("options"=>array("0"=>"Pending","1"=>"Admin Confirm"),"style"=>"width:160px;float:left","class"=>"text7","label"=>false,"div"=>false,"escape"=>false,"empty"=>"Select Status","onchange"=>"ChangeStatus(this.value,'".$data["TransactionLog"]["id"]."','".$confirmation["Confirmation"]["id"]."')"));?>
							<span style="margin-left:5px;" id="img_status"></span> 
                            <span class="error" id="err_status" ></span>
                        </div>
                    </div>
					<div id="text_message" class="line3" style="display:none;">
						<span class="nav_2" style="margin-top:30px;">Kirim pesan</span>
						<div class="line3" style="margin-top:10px;height:250px">
							<div class="left" style="width:15%;">
								<div class="text4">Pesan</div>
							</div>
							<div class="right" style="width:84%;border:0px solid black; height:200px">
								<div id="pesan" class="line1">
								<?php echo $form->textarea("pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px","id"=>"AdminMessage"))?>
								<script language="javascript1.2">
									generate_wysiwyg('AdminMessage');
								</script>
								</div>
							</div>
						</div>
					</div>
					<div class="line3" style="margin-top:10px; display:none" id="notice">
                        <div class="left" style="width:15%;">
                            <div class="text4">Notice</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->textarea("notice",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address"))?>
                            <div class="line1">
                                <div class="text8" style="width:340px;">Jika anda akan merubah status menjadi "Pending". Harap masukkan alasan anda, agar dapat diketahui user melalui cpanel user.</div>
                            </div>
                            <span style="margin-left:5px;" id="img_notice"></span> 
                            <span class="error" id="err_notice" ></span>
                        </div>
                    </div>
					<div class="line1" style="border:0px solid black; margin-top:30px;">
						<div class="left" style="border:0px solid black; width:20%; margin-right:15px; text-align:right;">
							&nbsp;
						</div>
						<div class="left" style="border:0px solid black; width:50%">
							<input type="submit" name="button" id="button" value="Simpan" class="btn_sign" onClick="return SubmitEdit()"/>
							<span class="font4" style="color:#000000;" id="loading"></span>
						</div>
					</div>
					<?php endif;?>
				</div>
			</div>
			<?php echo $form->end()?>
		</div>
		<?php else:?>
		<div class="line1">
            <div class="alert">
                <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
                Data Not Found
            </div>
        </div>
		<?php endif;?>
	</div>
</div>