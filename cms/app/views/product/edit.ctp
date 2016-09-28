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
	SelectCity('<?php echo $data['Product']['province_id']?>','<?php echo $data['Product']['city_id']?>');
	SelectTipe('<?php echo $data['Parent']['id']?>','<?php echo $data['Category']['id']?>');
	ChangeStatus('<?php echo $data['Product']['productstatus_id']?>');
	
	$("#ProductAddress").jqEasyCounter({ 
		   'maxChars': <?php echo $settings['max_address_char']?>,
	       'maxCharsWarning': <?php echo $settings['max_address_char']-50?>,
	       'msgFontSize': '12px',
	       'msgFontColor': '#000',
	       'msgFontFamily': 'Arial',
	       'msgTextAlign': 'left',
	       'msgWarningColor': '#F00',
		   'msgAppendMethod': 'insertBefore',
		   'msgAppendSelector':'#product_address'     	  
	});
	
	$('a[rel^=help]').each(function(){
		$(this).bt({
		  width: 230,
		  trigger: ['hover'],
		  positions: ['right'],
		  cornerRadius: 7,
		  strokeStyle: '#A9F826',
		  fill: 'rgba(205, 233, 159, 0.8)',
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

	<?php if($data['Product']['condition_id']==1):?>
	$("input[name='data[Product][stnk_id]']").each(function(){
		  if($(this).val()==-1)
		  {
			  $(this).attr({ "checked": true});
		  }
		  else
		  {
			  $(this).attr({ "disabled": 'disabled'});
		  }
	  });
	  
	  $("input[name='data[Product][bpkb_id]']").each(function(){
		  if($(this).val()==-1)
		  {
			  $(this).attr({ "checked": true});
		  }
		  else
		  {
			  $(this).attr({ "disabled": 'disabled'});
		  }
	  });
		
	<?php else:?>
	$("input[name='data[Product][stnk_id]']").each(function(){
		  if($(this).val()==-1)
		  {
				$(this).attr({ "disabled": 'disabled'});
		  }
		  
	  });
	  
	  $("input[name='data[Product][bpkb_id]']").each(function(){
		  if($(this).val()==-1)
		  {
			  $(this).attr({ "disabled": 'disabled'});
		  }
	  });
	<?php endif;?>
});

function onClickPage(el,divName) {
	
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+200)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}

function SelectTipe(parent_id,category_id)
{
	if(parent_id.length>0)
	{
		$("#tipe_motor").load("<?php echo $settings['cms_url']?>Product/SelectType/",{'parent_id':parent_id,'category_id':category_id});
	}
	else
	{
		$("#tipe_motor").html('<select name="data[Product][subcategory_id]" class="sel1" label="false" id="subcategory_id"><option value="" selected="selected">Pilih Tipe Motor</option></select>');	
	}
}

function SelectCity(province_id,city_id)
{
	if(province_id.length>0)
	{
		$("#pilih_kota").load("<?php echo $settings['cms_url']?>Template/SelectCity/",{'province_id':province_id,'city_id':city_id,'model':'Product'});
	}
	else
	{
		$("#pilih_kota").html('<select name="data[Product][city]" class="sel1" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>');	
	}
}

function SelectCondition(value,defaultvalue,kilometer,stnk,bpkb)
{
	if(value=="2")
	{
		if(parseInt(defaultvalue)!==-1)
		{
			$("#ProductNopol").val(defaultvalue);
		}
		else
		{
			$("#ProductNopol").val('');
		}
		
		if(parseInt(kilometer)>0)
		{
			$("#ProductKilometer").val(kilometer);
		}
		else
		{
			$("#ProductKilometer").val('');
		}
		
		$("input[name='data[Product][stnk_id]']").each(function(){
			$(this).attr({ "disabled": ''});
			$(this).attr({ "checked": false});
			if(parseInt(stnk)>-1)
			{
				if($(this).val()==stnk)
				{
					$(this).attr({ "checked": true});
				}
			}
		});
		
		$("input[name='data[Product][bpkb_id]']").each(function(){
			$(this).attr({ "disabled": ''});
			$(this).attr({ "checked": false});
			if(parseInt(bpkb)>-1)
			{
				if($(this).val()==bpkb)
				{
					$(this).attr({ "checked": true});
				}
			}
		});
	}
	else if(value=="1")
	{
		$("#ProductNopol").val("-1");
		$("#ProductKilometer").val("0");
		$("#ProductNopol").attr('readonly',true);
		$("#ProductKilometer").attr('readonly',true);
		$("input[name='data[Product][stnk_id]']").each(function(){
			if($(this).val()==-1)
			{
				$(this).attr({ "checked": true});
			}
			else
			{
				$(this).attr({ "disabled": 'disabled'});
			}
		});
		
		$("input[name='data[Product][bpkb_id]']").each(function(){
			if($(this).val()==-1)
			{
				$(this).attr({ "checked": true});
			}
			else
			{
				$(this).attr({ "disabled": 'disabled'});
			}
		});
		
	}
}

function IsCredits()
{
	var checked	=	$("#ProductIsCredit");
	
	if(checked.is(':checked')==true)
	{
		$("#is_credit").fadeIn(600);
		$("#ProductFirstCredit").val('<?php echo $data['Product']['first_credit']?>');
		$("#ProductCreditInterval").val('<?php echo $data['Product']['credit_interval']?>');
		$("#ProductCreditPerMonth").val('<?php echo $data['Product']['credit_per_month']?>');
	}
	else
	{
		$("#is_credit").fadeOut(600);
		$("#ProductFirstCredit").val('');
		$("#ProductCreditInterval").val('');
		$("#ProductCreditPerMonth").val('');
	}
}

function SubmitEdit()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("ProductPesan");
	}
	updateTextArea("ProductPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	

	$("#ProductEditForm").ajaxSubmit({
		url			: "<?php echo $settings['cms_url']?>Product/ProcessEdit",
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
				alert(data.error);
				location.href='<?php echo $settings['cms_url'].$url?>';
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
						scrool	=	"#err_"+item.key;
					}
					
				});
				$(document).scrollTo(scrool, 800);
			}
		}
	});
	return false;
}

function ChangeStatus(val)
{
	$.get("<?php echo $settings['cms_url']?>Template/GetStatusMessage",{"type":val,'product_id':<?php echo $data['Product']['id']?>},function(data){
		$("#pesan").html('<?php echo $form->textarea("Product.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px"))?>');
		$("#ProductPesan").val(data);
		generate_wysiwyg('ProductPesan');
	});
}

</script>
<div id="output"></div>
<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code))?>
<div class="test-right">
    <div class="content">
        <?php if(empty($error)):?>
        <?php echo $form->create('Product',array("onsubmit"=>"return SubmitEdit()"))?>
        <?php echo $form->input("id",array("type"=>"hidden","readonly"=>true,"value"=>$data['Product']['id']))?>
        <div class="line1">
            <div class="line3">
                <div class="left" style="width:100%;">
                    <span class="nav_2">Informasi Penjual</span>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Nama Penjual</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("contact_name",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['contact_name'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_contact_name"></span> 
                            <span class="error" id="err_contact_name" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> No Telp</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("phone",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['phone'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <div class="line1">
                                <span class="text8">Untuk menambahkan no.telp, pisahkan dengan koma (,)</span>
                            </div>
                            <span style="margin-left:5px;" id="img_phone"></span> 
                            <span class="error" id="err_phone" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Yahoo! Messenger</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("ym",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['ym'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_ym"></span> 
                            <span class="error" id="err_ym" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Alamat</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                           <?php echo $form->textarea("address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$data['Product']['address']))?>
                            <span style="margin-left:5px;" id="img_address"></span> 
                            <span class="error" id="err_address" ></span>
                            <span class="text8" id="product_address" style="float:left; width:100%"></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Propinsi</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->select("province_id",$province_id,$data['Product']['province_id'],array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>"Pilih Propinsi","onchange"=>"SelectCity(this.value,'".$data['Product']['city_id']."')"));?>
                            <span style="margin-left:5px;" id="img_province_id"></span> 
                            <span class="error" id="err_province_id" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Kota</div>
                        </div>
                        <div class="right" style="width:84%;">
                            <div id="pilih_kota"><?php echo $form->select("city",array(""=>"Pilih Kota"),false,array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>false));?></div>
                            <span style="margin-left:5px;" id="img_city"></span>
                            <span class="error" id="err_city" ></span>
                        </div>
                    </div>
                    <span class="nav_2" style="margin-top:20px;">Informasi Motor</span>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Merk motor</div>
                        </div>
                        <div class="right" style="width:84%;">
                            <div style="width:40%; border:0px solid black; float:left; display:block;">
                            <?php echo $form->select("category_id",$category_id,$data['Parent']['id'],array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>false,"style"=>"width:100%",'onchange'=>"SelectTipe(this.value,'".$data['Category']['id']."')"));?>
                            </div>
                           
                            <?php if($data['Parent']['status']==0):?>
                                <div style="float:left; display:block; border:0px solid black; width:30px; color:#F00">&nbsp;(New)</div>
                                <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="User mengajukan pembuatan merk motor baru (<?php echo $data['Parent']['name']?>)."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                            <?php endif;?>
                            <span style="margin-left:5px;" id="img_category_id"></span>
                            <span class="error" id="err_category_id" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Tipe motor</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <div id="tipe_motor" style="width:40%; border:0px solid black; float:left; display:block;">
                                <?php echo $form->select("subcategory_id",array(""=>"Pilih Tipe Motor"),false,array("class"=>"sel1","label"=>"false","escape"=>false,"style"=>"width:100%","empty"=>false));?>
                            </div>
                            <?php if($data['Category']['status']==0):?>
                                <div style="float:left; display:block; border:0px solid black; width:30px;color:#F00">&nbsp;(New)</div>
                                <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; text-decoration:none" rel="help" title="User mengajukan pembuatan tipe motor baru (<?php echo $data['Category']['name']?>)."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                            <?php endif;?>
                            <span style="margin-left:5px;" id="img_subcategory_id"></span>
                            <span class="error" id="err_subcategory_id" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Kondisi</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->select("condition_id",$condition_id,$data['Product']['condition_id'],array("style"=>"width:160px;float:left","class"=>"text7","label"=>"false","escape"=>false,"empty"=>false,"onchange"=>"SelectCondition(this.value,'".$data['Product']['nopol']."','".$data['Product']['kilometer']."','".$data['Product']['stnk_id']."','".$data['Product']['bpkb_id']."')"));?>
                            <span style="margin-left:5px;" id="img_nopol"></span> 
                            <span class="error" id="err_nopol" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong>  No Pol</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->input("nopol",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['nopol'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_condition_id"></span> 
                            <span class="error" id="err_condition_id" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Tahun pembuatan</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->input("thn_pembuatan",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['thn_pembuatan'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_thn_pembuatan"></span> 
                            <span class="error" id="err_thn_pembuatan" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Warna</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->input("color",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['color'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_color"></span> 
                            <span class="error" id="err_color" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Kilometer</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->input("kilometer",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['kilometer'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_kilometer"></span> 
                            <span class="error" id="err_kilometer" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Keterangan</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->textarea("description",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>html_entity_decode($data['Product']['description'],ENT_QUOTES),"style"=>"height:200px;width:500px"))?>
                            <span style="margin-left:5px;" id="img_description"></span> 
                            <span class="error" id="err_description"></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Tgl Input</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                           <?php echo date("d-M-Y H:i:s",strtotime($data['Product']['created']))." (".$time->timeAgoInWords(strtotime($data['Product']['created'])).")"?>
                        </div>
                    </div>
                    <?php if($data['Product']['productstatus_id']==1):?>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Tgl Disetujui</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                           <?php echo date("d-M-Y H:i:s",strtotime($data['Product']['approved']))." (".$time->timeAgoInWords($data['Product']['approved']).")"?>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Admin</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                           <?php echo $data['Product']['approved_by']?>
                        </div>
                    </div>
                    <?php endif?>
                    <span class="nav_2" style="margin-top:20px;">Surat-surat motor</span>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">STNK :</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("stnk_id",array('options'=>$stnk,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['stnk_id']) )?>
                            <span style="margin-left:5px;" id="img_stnk_id"></span> 
                            <span class="error" id="err_stnk_id" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">BPKB :</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("bpkb_id",array('options'=>$bpkb,'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['bpkb_id']) )?>
                            <span style="margin-left:5px;" id="img_bpkb_id"></span> 
                            <span class="error" id="err_bpkb_id" ></span>
                        </div>
                    </div>
                    <span class="nav_2" style="margin-top:20px;">Harga motor</span>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Harga</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->input("price",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$number->format($data['Product']['price'],array("thousands"=>".","before"=>null,"places"=>null,"after"=>null)),"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_price"></span> 
                            <span class="error" id="err_price" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            &nbsp;
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php $checked = 	($data['Product']['is_credit']==1) ? "checked" : "" ?>
                             <?php $display = 	($data['Product']['is_credit']==1) ? "block" : "none" ?>
                             <?php echo $form->checkbox("is_credit",array("value"=>1,"onclick"=>"IsCredits()","checked"=>$checked))?>
                             <label for="ProductIsCredit">Saya jual dengan harga kredit</label>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;display:<?php echo $display?>" id="is_credit">
                        <div class="left" style="width:15%;">
                            &nbsp;
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <div class="line3" style="margin-top:10px;">
                                <div class="left" style="width:15%;">
                                    <div class="text4"><strong>*</strong> Angsuran Pertama</div>
                                </div>
                                <div class="right" style="width:84%;border:0px solid black;">
                                     <?php echo $form->input("first_credit",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['first_credit'],"style"=>"width:215px; float:left; height:16px;"))?>
                                    <span style="margin-left:5px;" id="img_first_credit"></span> 
                                    <span class="error" id="err_first_credit" ></span>
                                </div>
                            </div>
                            <div class="line3" style="margin-top:10px;">
                                <div class="left" style="width:15%;">
                                    <div class="text4"><strong>*</strong>Jumlah angsuran</div>
                                </div>
                                <div class="right" style="width:84%;border:0px solid black;">
                                     <?php echo $form->input("credit_interval",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['credit_interval'],"style"=>"width:215px; float:left; height:16px;"))?>
                                    <span style="margin-left:5px;" id="img_credit_interval"></span> 
                                    <span class="error" id="err_credit_interval" ></span>
                                </div>
                            </div>
                            <div class="line3" style="margin-top:10px;">
                                <div class="left" style="width:15%;">
                                    <div class="text4"><strong>*</strong>Nilai angsuran perbulan</div>
                                </div>
                                <div class="right" style="width:84%;border:0px solid black;">
                                     <?php echo $form->input("credit_per_month",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Product']['credit_per_month'],"style"=>"width:215px; float:left; height:16px;"))?>
                                    <span style="margin-left:5px;" id="img_credit_per_month"></span> 
                                    <span class="error" id="err_credit_per_month" ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="nav_2" style="margin-top:20px;">Share</span>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Facebook :</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("facebook_share",array('options'=>array("1"=>"Ya","0"=>"Tidak"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['facebook_share']) )?>
                            <span style="margin-left:5px;" id="img_facebook_share"></span> 
                            <span class="error" id="err_facebook_share" ></span>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Twitter :</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("twitter_share",array('options'=>array("1"=>"Ya","0"=>"Tidak"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['twitter_share']) )?>
                            <span style="margin-left:5px;" id="img_facebook_share"></span> 
                            <span class="error" id="err_facebook_share" ></span>
                        </div>
                    </div>
                    <span class="nav_2" style="margin-top:20px;">Foto</span>
                    <div class="line3" style="border:0px solid black;" id="photo">
                        <div class="line3" style="border:0px solid black; width:500px;" id="photo">
                            <?php for($i=1;$i<=6;$i++):?>
                            <div class="left" style="margin-right:10px; width:150px;border:0px solid black; height:200px;">
                                <div class="image_box7" style="margin-left:0px; float: left;width:128px; height:128px;">
                                    <?php if(!empty($img[$i]['id'])):?>
                                        <a href="javascript:void(0)" onclick="$.prettyPhoto.open('<?php echo $settings['showimages_url']."?code=".$img[$i]['id']."&prefix=_zoom&content=ProductImage&w=500&h=500"?>');" rel="zoom" path='img-zoom-<?php echo $img[$i]['id']?>'>
                                            <img src="<?php echo $settings['showimages_url']."?code=".$img[$i]['id']."&prefix=_prevthumb&content=ProductImage&w=128&h=128"?>" border="0"/>
                                        </a>
                                    <?php else:?>
                                        <img src="<?php echo $settings['site_url']."img/question.png"?>"/>
                                    <?php endif;?>
                                </div>
                                <div class="line1" style="border:0px solid black;">
                                    <?php if(!empty($img[$i])):?>
                                    <?php $chk	=	($img[$i]['status']==1) ? "checked='checked'" : ""?>
                                    <input type="checkbox" id="<?php echo "ProductApproved$i"?>" name="data[Product][imgapproved][]" value="<?php echo $img[$i]['id']?>" <?php echo $chk?>/>
                                    <label for="ProductApproved<?php echo $i?>">Disetujui
                                    <?php if($img[$i]['is_primary']==1) echo "<br><b>&nbsp;Primary</b>"?>
                                    </label>
                                    
                                    <?php else:?>
                                    Empty
                                    <?php endif;?>
                                </div>
                            </div>
                            <?php endfor;?>
                        </div>
                    </div>
                    <?php if(in_array($data['Product']['productstatus_id'],array(-1,-2))):?>
                    <span class="nav_2" style="margin-top:20px;">Catatan dari admin</span>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Catatan : </div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <div class="text4"><?php echo $data['Product']['notice']?></div>
                            <a class="text8" href="<?php echo $settings['cms_url']?>ProductLogDetail/Index/<?php echo $data['Product']['id']?>" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none;display:block">[ lihat log ]</a>
                        </div>
                    </div>
                    <?php endif;?>
                    <span class="nav_2" style="margin-top:20px;">Update status</span>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Status</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->select("productstatus_id",$productstatus_id,$data['Product']['productstatus_id'],array("style"=>"width:160px;float:left","class"=>"text7","label"=>"false","escape"=>false,"empty"=>false,"onchange"=>"ChangeStatus(this.value)"));?>
                            <span style="margin-left:5px;" id="img_status"></span> 
                            <span class="error" id="err_status" ></span>
                        </div>
                    </div>
                    <?php if($data['Product']['productstatus_id']==1 && $data['Product']['productstatus_user']==1):?>
                    <span class="nav_2" style="margin-top:20px;">Status Penjualan</span>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Status Penjualan</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->input("sold",array('options'=>array(0=>"Belum terjual",1=>"Sudah terjual"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Product']['sold']))?>
                            <span style="margin-left:5px;" id="img_status"></span> 
                            <span class="error" id="err_status" ></span>
                        </div>
                    </div>
                    <?php endif;?>
                    <span class="nav_2" style="margin-top:20px;">Kirim pesan</span>
                    <div class="line3" style="margin-top:10px;height:250px">
                        <div class="left" style="width:15%;">
                            <div class="text4">Pesan</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black; height:200px">
                            <div id="pesan" class="line1">
                            <?php echo $form->textarea("Product.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px"))?>
                            <script language="javascript1.2">
                                generate_wysiwyg('ProductPesan');
                            </script>
                            </div>
                        </div>
                    </div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            &nbsp;
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->checkbox("kirim_pesan",array("value"=>1))?>
                             <label for="ProductKirimPesan">Simpan dan Kirim Pesan</label>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Notice</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                            <?php echo $form->textarea("notice",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address"))?>
                            <div class="line1">
                                <div class="text8" style="width:340px;">Jika anda akan merubah status menjadi "Editing Required". Harap masukkan alasan anda, agar dapat diketahui user melalui cpanel user.</div>
                            </div>
                            <span style="margin-left:5px;" id="img_notice"></span> 
                            <span class="error" id="err_notice" ></span>
                        </div>
                    </div>
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
            <?php echo $form->end()?>
        </div>
        <?php else:?>
        <div class="line1">
            <div class="alert">
                <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
                <?php echo $error?>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>