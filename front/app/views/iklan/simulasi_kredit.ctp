<?php
$err_harga	=	(!is_null($this->data["Product"]["harga"]) && !is_null($form->error("Product.harga"))) ? "<img src='{$settings['site_url']}img/icn_error.png'>" : (!empty($this->data["Product"]["submit"]) ? "<img src='{$settings['site_url']}img/check.png'>" :"" );

$err_dppersen	=	(!is_null($this->data["Product"]["dppersen"]) && !is_null($form->error("Product.dppersen"))) ? "<img src='{$settings['site_url']}img/icn_error.png'>" : (!empty($this->data["Product"]["submit"]) ? "<img src='{$settings['site_url']}img/check.png'>" :"" );

$err_bunga	=	(!is_null($this->data["Product"]["bunga"]) && !is_null($form->error("Product.bunga"))) ? "<img src='{$settings['site_url']}img/icn_error.png'>" : (!empty($this->data["Product"]["bunga"]) ? "<img src='{$settings['site_url']}img/check.png'>" :"" );

$err_administrasi	=	(!is_null($this->data["Product"]["administrasi"]) && !is_null($form->error("Product.administrasi"))) ? "<img src='{$settings['site_url']}img/icn_error.png'>" : (!empty($this->data["Product"]["administrasi"]) ? "<img src='{$settings['site_url']}img/check.png'>" : "" );
?>
<?php echo $javascript->link("jquery.scrollTo")?>
<script>
function CalcKeyCode(aChar) {
  var character	= aChar.substring(0,1);
  var code		= aChar.charCodeAt(0);
  return code;
}
function KeyupAdministrasi()
{
	var valAdministrasi			=	$("#ProductAdministrasi").val();
	var lengthAdministrasi		=	valAdministrasi.length;
	
	if(lengthAdministrasi>0)
	{
		if (!CheckIsNumerik(valAdministrasi))
		{
			$("#err_administrasi").html("Masukkan nilai angka saja!");
			$("#img_administrasi").html('<img src="<?php echo $this->webroot?>img/icn_error.png">');
		}
		else
		{
			$("#err_administrasi").html("");
			$("#img_administrasi").html('<img src="<?php echo $this->webroot?>img/check.png">');
		}
	}
}

function KeyupHarga()
{
	var valHarga		=	$("#ProductHarga").val();
	var lengthHarga		=	valHarga.length;
	
	var valPersen		=	$("#ProductDppersen").val();
	var lengthPersen	=	valPersen.length;
	
	$("#err_harga").html("");
	$("#img_harga").html('');
	$("#img_dppersen").html('');
	$("#err_dppersen").html('');
	$("#hasil_persen").html('');
	
	if(lengthHarga>0)
	{
		if (!CheckIsNumerik(valHarga))
		{
			$("#err_harga").html("Masukkan nilai angka saja!");
			$("#img_harga").html('<img src="<?php echo $this->webroot?>img/icn_error.png">');
		}
		else
		{
			$("#img_harga").html('<img src="<?php echo $this->webroot?>img/check.png">');
			if(valPersen>80) $("#SimulasiDppersen").val(80);
			
			if(validateNumeric(valPersen) && lengthPersen>0)
			{
				var hasilPersen		=	roundNumber((valPersen/100) * valHarga);
				if(hasilPersen<1) $("#hasil_persen").html(1);
				else $("#hasil_persen").html(formatCurrency(hasilPersen));
			}
			
			if(lengthPersen>0)
			{
				if(valPersen<1) $("#hasil_persen").html(0);
				if(!validateNumeric(valPersen))
				{
					$("#img_dppersen").html('<img src="<?php echo $this->webroot?>img/icn_error.png">');
					$("#err_dppersen").html('Berikan nilai numerik. co: 2.3, 20, 13.5');
					$("#hasil_persen").html('');
				}
				else
				{
					$("#img_dppersen").html('<img src="<?php echo $this->webroot?>img/check.png">');
					$("#err_dppersen").html('');
				}
			}
		}
	}
	else
	{
		$("#SimulasiDppersen").val(0);
	}
	return false;
}
function roundNumber(number)
{ 
	var newnumber = new Number(number+'').toFixed(parseInt(0));
	return parseFloat(newnumber);
}

function  validateNumeric( strValue )
{
  //var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/;
  var objRegExp  =  /^[0-9]/;
  
  return objRegExp.test(strValue);
}

function CheckIsNumerik(val)
{
	var strLength	=	val.length;
	for (var i = 0; i < strLength ; i++)
	{
		var lchar 		=	val.charAt(i);
		var cCode 		=	CalcKeyCode(lchar);
		if (cCode < 48 || cCode > 57 )
		{
			return false;
		}
	}
	return true;
}

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+'.'+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num);
}

function SubmitSimulasi()
{
	
	$("#FormSimulasi").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Iklan/ProcessSimulasi/<?php echo $data["Product"]["id"]?>",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#hasil_simulasi").hide(300);
			$("#hasil_table").hide(300);
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19.gif" style="float:left; display:block;vertical-align:middle; margin-right:5px;"/>Please wait...');
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert("Maaf terjadi kesalahan pada server kami, mohon coba beberapa saat lagi.");
		},
		success		: function(data)
		{
			$("#output").html(data);
			$("#loading").html('');
			$("span[id^=err]").html('');
			$("span[id^=img]").html('');
			
			if(data.status==true)
			{
				if($("#table").is(':checked')==false)
				{
					$("#hasil_harga").html(data.data.hasil_harga);
					$("#hasil_dppersen").html(data.data.hasil_dppersen+" %");
					$("#hasil_dppersen2").html(data.data.hasil_dppersen+" %");
					$("#hasil_dp").html(data.data.hasil_dp);
					$("#hasil_dp2").html(data.data.hasil_dp);
					$("#hasil_pokok_hutang").html(data.data.hasil_pokok_hutang);
					$("#hasil_bunga").html(data.data.hasil_bunga+" %");
					$("#hasil_jangka_waktu").html(data.data.hasil_jangka_waktu+" bulan");
					$("#hasil_angsuran").html(data.data.hasil_angsuran);
					$("#hasil_angsuran2").html(data.data.hasil_angsuran);
					$("#hasil_bunga_flat").html(data.data.hasil_bunga_flat);
					$("#hasil_angsuran_pokok").html(data.data.hasil_angsuran_pokok);
					$("#hasil_administrasi").html(data.data.hasil_administrasi);
					$("#hasil_asuransi").html(data.data.hasil_asuransi);
					$("#hasil_total").html(data.data.hasil_total);
					$("#hasil_simulasi").show(300);
					$(document).scrollTo("#span_administrasi", 800);
				}
				else
				{
					SubmitTable();
				}
			}
			else
			{
				var err		=	data.error;
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
function SubmitTable()
{
	$("#FormSimulasi").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Iklan/HasilTable/<?php echo $data["Product"]["id"]?>",
		type		: "POST",
		dataType	: "html",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#hasil_simulasi").hide(300);
			$("#hasil_table").hide(300);
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert("Maaf terjadi kesalahan pada server kami, mohon coba beberapa saat lagi.");
		},
		success		: function(data)
		{
			$("#hasil_table").html(data).show(300);
		}
	});
}
</script>
<div id="output"></div>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">
            	<img src="<?php echo $this->webroot?>img/icn_simulasi.png" style="float:left;margin:-3px 3px 0 0;"/>
                SIMULASI KREDIT MOTOR
            </div>
        </div>
        
        <div class="line back1 size100 kiri position1 rounded2" style="padding-bottom:10px;">
            <div class="kiri left10 size95 top10">
            	<?php if(!empty($data)):?>
                <div class="kiri size100  bottom30">
                    <div class="kiri style1 text14 bold white" style="border:0px solid black">
                        <img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data["ProductImages"]["id"]?>&prefix=_thumb50x50&content=ProductImage&w=50&h=50" id="UserPhoto" style="border:1px solid #ffffff; padding:2px;"/>
                    </div>
                     <div class="kiri style1 text14 bold white left10" style="border:0px solid black">
                     	<div class="kiri size100">
							<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]?>" class="style1 white normal"><?php echo $data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].")"?></a>
                       </div>
                      <div class="kiri size100 style1 white text13 top5"><?php echo $number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?></div>
                      <div class="kiri size100 style1 white text12 top5"><?php echo $data["Product"]["contact_name"]?></div>
                    </div>
                </div>
                <?php endif;?>
                <?php echo $form->create("Product",array("id"=>"FormSimulasi"))?>
            	<div class="kiri size100">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_harga">Harga Motor</div>
                    <div class="kiri size70 left10">
                    	<div class="input2 style1 white text12 size82 kiri">Rp
                        	<?php echo $form->input("harga",array("div"=>false,"label"=>false,"error"=>false,"style"=>"border:none;background-color:transparent","maxlength"=>15,"onkeyup"=>"KeyupHarga()","class"=>"style1 white text12"))?>
                        </div>
                        <span class="kiri left10" id="img_harga">
                        	<?php echo $err_harga?>
                        </span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_harga"><?php echo $form->error("Product.harga")?></span>
                    </div>
                </div>
                <div class="kiri size100 top20">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_dppersen">Uang Muka</div>
                    <div class="kiri size70 left10">
                    	<?php echo $form->input("dppersen",array("div"=>false,"label"=>false,"error"=>false,"class"=>"input2 style1 white text12 size10 kiri","maxlength"=>5,"onkeyup"=>"KeyupHarga()"))?>
                        <div class="kiri size5 left10 style1 white bold text13 top5">%</div>
                        <div class="input2 style1 white text12 size63 kiri">Rp<span id="hasil_persen" class="left5">&nbsp;</span></div>
                        <span class="kiri left10" id="img_dppersen"><?php echo $err_dppersen?></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_dppersen"><?php echo $form->error("Product.dppersen")?></span>
                    </div>
                </div>
                <div class="kiri size100 top20">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_bunga">Bunga / Tahun</div>
                    <div class="kiri size70 left10">
                    	<?php echo $form->input("bunga",array("div"=>false,"label"=>false,"error"=>false,"class"=>"input2 style1 white text12 size10 kiri","maxlength"=>5))?><div class="kiri size5 left10 style1 white bold text13 top5">%</div>
                        <span class="kiri left10" id="img_bunga"><?php echo $err_bunga?></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_bunga"><?php echo $form->error("Product.bunga")?></span>
                    </div>
                </div>
                <div class="kiri size100 top20">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_tenor">Jangka Waktu</div>
                    <div class="kiri size70 left10">
                    	<?php echo $form->select("tenor",$tenor,$this->data["Product"]["tenor"],array("div"=>false,"label"=>false,"error"=>false,"class"=>"rounded1 size83 kiri style1 white text12 input2","empty"=>false,"style"=>"cursor:pointer;"))?>
                        <div class="kiri style1 white text12 size100 top5"><label><input type="checkbox" name="table" id="table"/>Tampilkan semua tahun dalam bentuk table.</label></div>
                    </div>
                </div>
                <div class="kiri size100 top20">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_administrasi">Administrasi</div>
                    <div class="kiri size70 left10">
                    	<div class="input2 style1 white text12 size82 kiri">Rp
                        	<?php echo $form->input("administrasi",array("div"=>false,"label"=>false,"error"=>false,"style"=>"border:none;background-color:transparent","maxlength"=>15,"class"=>"style1 white text12 size80","onkeyup"=>"KeyupAdministrasi()"))?>
                        </div>
                        <span class="kiri left10" id="img_administrasi"><?php echo $err_administrasi?></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_administrasi">
                        	<?php echo $form->error("Product.administrasi")?>
                        </span>
                    </div>
                </div>
                <div class="line top20 bottom10">
                    <input type="submit" name="data[Product][submit]" value="HITUNG" class="tombol1" style="float:left" onclick="return SubmitSimulasi()"/>
                    <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                </div>
              <?php echo $form->end()?>
                <div class="kiri size100" id="hasil_simulasi" style="display:none">
                	<div class="line top20 style1 bold white text17" style="border-bottom:1px solid white;">
                        Rincian Kredit - Estimasi
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Harga Motor
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_harga"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            DP Motor (<span id="hasil_dppersen"></span>)
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_dp"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Pokok Hutang /Plafon Kredit
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_pokok_hutang"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Suku Bunga Kredit
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_bunga"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Jangka Waktu(bln)
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_jangka_waktu"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Angsuran Pokok
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_angsuran_pokok"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Bunga flat per Bulan
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_bunga_flat"></span>
                        </div>
                    </div>
                    <div class="kiri style1 red2 bold text12 size100 top15">
                        <div class="kiri size30">
                            Angsuran per Bulan
                        </div>
                        <div class="kiri size50 bold blink">
                            :&nbsp;&nbsp;<span id="hasil_angsuran" class="text17"></span>
                        </div>
                    </div>
                    <div class="line top20 style1 bold white text17" style="border-bottom:1px solid white;">
                        Pembayaran Pertama - Estimasi
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                             DP Motor (<span id="hasil_dppersen2"></span>)
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_dp2"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Angsuran pertama
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_angsuran2"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Administrasi
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_administrasi"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Asuransi (3,6%)
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_asuransi"></span>
                        </div>
                    </div>
                    <div class="kiri style1 white bold text12 size100 top15">
                        <div class="kiri size30">
                            Tipe Asuransi
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;Total Loss Only
                        </div>
                    </div>
                    <div class="kiri style1 red2 bold text12 size100 top15">
                        <div class="kiri size30">
                            Pembayaran awal
                        </div>
                        <div class="kiri size50 bold text13">
                            :&nbsp;&nbsp;<span id="hasil_total" class="text15"></span>
                        </div>
                    </div>
                </div>
                <div class="kiri size100" id="hasil_table" style="display:none">
                </div>
            </div>
        </div>
	</div>
</div>
<?php if(!empty($data)):?>
 <script>KeyupHarga()</script>
<div class="kiri top15"><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data['Product']['id']?>" class="style1 red2 bold text12 normal"><img src="<?php echo $this->webroot?>img/admin_arrowleft.gif" style="border:none; vertical-align:middle;">&nbsp;&nbsp;Lihat Detail Iklan</a></div>
<?php endif;?>