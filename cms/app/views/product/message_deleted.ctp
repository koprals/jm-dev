<!-- START BLOCK CSS -->
<?PHP echo $html->css('main_css')?>
<!-- END BLOCK CSS -->
<!-- START BLOCK JAVASCRIPT -->
<?PHP echo $javascript->link('jquery.latest')?>
<?php echo $javascript->link("jquery.bt")?>

<!-- END BLOCK JAVASCRIPT -->

<script>
var fade_in 	= 	500;
var fade_out	= 	500;
if($.browser.msie)
{
	fade_in 	= 100;
	fade_out	= 3500;
}

var ROOT		=	"<?php echo $settings['web_url']?>";
imagesDir		=	"<?php echo $this->webroot?>img/wysiwyg/";
popupsDir		=	"<?php echo $this->webroot?>wysiwyg_popup/";
wysiwygWidth	=	447;
wysiwygHeight	=	200;

function HapusDanKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("ProductPesan");
	}
	updateTextArea("ProductPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.HapusDanKirim($("#ProductPesan").val());
}
function HapusTanpaKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("ProductPesan");
	}
	updateTextArea("ProductPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.HapusTanpaKirim();
}
function MultiHapusDanKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("ProductPesan");
	}
	updateTextArea("ProductPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.MultiHapusDanKirim($("#ProductPesan").val());
}
function MultiHapusTanpaKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("ProductPesan");
	}
	updateTextArea("ProductPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.MultiHapusTanpaKirim();
}
</script>
<script>
$(document).ready(function(){
	$('a[rel^=help]').each(function(){
		$(this).bt({
		  width: 320,
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
	
	<?php if(!empty($product_id)):?>
		$.get("<?php echo $settings['cms_url']?>Template/GetStatusMessage",{"type":"-10",'product_id':'<?php echo $product_id?>'},function(data){
			$("#pesan").html('<?php echo $form->textarea("Product.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px"))?>');
			$("#ProductPesan").val(data);
			generate_wysiwyg('ProductPesan');
			$("#HapusDanKirim").bind('click',function(){HapusDanKirim();});
			$("#HapusTanpaKirim").bind('click',function(){HapusTanpaKirim();});
		});
	<?php else:?>
		var html	=	'Kami menginformasikan bahwa iklan mobil Anda kami hapus dan batal untuk ditayangkan.<br><br>Dikarenakan Anda belum memberikan respon pada email konfirmasi yang kami kirimkan pada tanggal ....';
		$("#ProductPesan").val(html);
		generate_wysiwyg('ProductPesan');
		$("#HapusDanKirim").bind('click',function(){MultiHapusDanKirim();});
		$("#HapusTanpaKirim").bind('click',function(){MultiHapusTanpaKirim();});
	<?php endif;?>
});
</script>
<?php echo $html->css('wysiwyg/styles.css')?>
<?php echo $javascript->link('wysiwyg.js')?>
<div class="line1" style="width:470px;">
    <div class="box_panel">
        <div class="line4" style="border:0px solid black; height:30px;">
            <span class="text6" style="width:95%">Masukkan isi email<hr /></span>
        </div>
        <div class="line1">
        	<div class="line3" style="height:250px">
                <div class="left" style="width:84%;border:0px solid black; height:200px; margin-left:10px;">
                    <div id="pesan" class="line1">
                    <?php echo $form->textarea("Product.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px","value"=>''))?>
                    <script language="javascript1.2">
						
                    </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="line1" style="border:0px solid black; margin-top:10px; margin-bottom:10px;">
            <div class="left" style="border:0px solid black; width:36%; margin-right:15px; text-align:right;">
                &nbsp;
            </div>
            <div style="border:0px solid black; width:300px; float:none; margin:auto;">
                <div class="left" style="width:100%">
                    <input type="button" name="button" value="Hapus dan Kirim" id="HapusDanKirim" class="btn_sign"/>
                    <input type="button" name="button" value="Hapus tanpa Kirim" id="HapusTanpaKirim"  class="btn_sign" style="margin-left:5px;"/>
                </div>
                <span class="font4" style="color:#000000;" id="loading"></span>
            </div>
        </div>
    </div>
</div>