<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.hoverIntent.minified")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<?php echo $javascript->link("jquery.scrollTo")?>
<script>
var fade_in = 500;
var fade_out = 500;
$(document).ready(function(){
	if($.browser.msie)
	{
		fade_in 	= 100;
		fade_out	= 3500;
	}
	jQuery.bt.options.ajaxLoading = '<div style="width:235px;height:250px;display:block;float:left;"><div style="color:#000000;font-size:12px;float:none;margin:50% auto;display:block; text-align:center;"><img src="<?php echo $this->webroot?>img/loading19_bak.gif" />&nbsp;Loading..</div></div>';
	jQuery.bt.options.closeWhenOthersOpen = true;
	$('a[rel^=img_thumb]').each(function(){
		$(this).bt({
			ajaxPath: ["$(this).attr('link')"],
			width: 237,
			positions: ['right'],
			cornerRadius: 0,
			strokeStyle: '#8a8a8a',
			fill: 'rgba(255, 255, 255, 1)',
			cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px','padding-top':'2px','padding-right':'1px','padding-left':'2px','padding-bottom':'1px'},
			shrinkToFit: true,
			hoverIntentOpts: {
				interval: 200,
				timeout: 2000
			  }
		});
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
});

function SubmitPm()
{
	$("#SendPmForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Us/ProcessContact",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
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
				location.href	=	data.error;
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
</script>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Hubungi Kami</div>
        </div>
        <div class="line back1 size100 kiri position1 rounded2" style="padding-bottom:10px;" id="updt_prfl">
        	<div class="size70 tengah" id="success"  style="display:none">
                <div class="kiri size100 style1 white top10 reounded_error">
                    <div class="kiri size100 left10 top10 bold text14 blink">Pesan Anda telah terkirim</div>
                    <div class="kiri size100 left10 top10 bold text12 bottom10">Mohon tunggu, kami kembalikan anda ke halaman detail iklan ...</div>
                </div>
            </div>
            <?php echo $form->create("Contact",array("onsubmit"=>"return SubmitPm()","id"=>"SendPmForm"))?>
            <div class="kiri size95 left10" style="border:0px solid black;">
                <div class="kiri size100  top15">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_from"><span class="text15">*</span> Nama</div>
                    <div class="kiri size70 left10">
                    	
                        <?php echo $form->input("from",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama anda disini. Maksimal ".$settings['max_name_char']." karakter","class"=>"input2 style1 white text12 size80 kiri","value"=>$display_name))?>
                        
                        <span class="kiri left10" id="img_from"></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_from"></span>
                    </div>
                </div>
                <div class="kiri size100  top15">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_email"><span class="text15">*</span> Email</div>
                    <div class="kiri size70 left10">
                        <?php echo $form->input("email",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Berikan email anda kami dapat menghubungi anda.","class"=>"input2 style1 white text12 size80 kiri","value"=>$profile["User"]["email"]))?>
                        <span class="kiri left10" id="img_email"></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_email"></span>
                    </div>
                </div>
                <div class="kiri size100  top15">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_telp"><span class="text15">&nbsp;</span> NoTelp</div>
                    <div class="kiri size70 left10">
                        <?php echo $form->input("telp",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan no telp anda agar kami dapat menghubungi anda","class"=>"input2 style1 white text12 size80 kiri","maxlength"=>30,"value"=>$profile["Profile"]["phone"]))?>
                        <span class="kiri left10" id="img_telp"></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_telp"></span>
                    </div>
                </div>
                <div class="kiri size100  top15">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_contact_category_id"><span class="text15">*</span> Kategori</div>
                    <div class="kiri size70 left10">
                        <?php echo $form->select("contact_category_id",$contact_category_id,false,array("div"=>false,"label"=>false,"error"=>false,"class"=>"rounded1 size82 kiri style1 white text12 input2","empty"=>false,"style"=>"cursor:pointer;"))?>
                        <span class="kiri left10" id="img_subject"></span>
                        <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_subject"></span>
                    </div>
                </div>
                <div class="kiri size100 top15" style="border:0px solid black;">
                    <div class="kiri size20 style1 white bold text13 top5" id="span_message" style="border:0px solid black;"><span class="text15">*</span> Isi Pesan</div>
                    <div class="kiri size75 left10" style="border:0px solid black;">
                    	<div class="style1 white text12 size98 kiri">
                            <?php echo $form->textarea("message",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan isi pesan anda.","class"=>"input2 style1 white text12 size100 kiri textarea","style"=>"height:200px;"))?>
                        </div>
                        <div class="kiri size5" id="img_message" style="border:0px solid black;"></div>
                    	<div class="kiri style1 white text12 bold left10 size70" style="text-decoration:blink;" id="err_message"></div>
                    </div>
                </div>
                
                <div class="line top20 bottom10">
                    <input type="button" name="button" value="KIRIM PESAN" class="tombol1" onclick="SubmitPm()" style="float:left"/>
                    <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                </div>
            </div>
            <?php echo $form->end()?>
		</div>
	</div>
</div>