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

function SendMsgToParent()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("ProductPesan");
	}
	updateTextArea("ProductPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	parent.GetMsgEditing($("#ProductPesan").val(),$("#ProductNotice").val());
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
                    <?php echo $form->textarea("Product.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px","value"=>'<strong style="font-size: 15px;">Namun setelah kami periksa terdapat beberapa data yang perlu di perbaiki, diataranya:</strong> <br> <br> 1. .................................................... <br> 2. .................................................... <br> 3. .................................................... <br> 4. .................................................... <br> <br>'))?>
                    <script language="javascript1.2">
                        generate_wysiwyg('ProductPesan');
                    </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="line1" style=" margin-top:10px;">
        	<div class="line1">
                <div class="line4" style="border:0px solid black; height:20px; width:450px;">
                    <span class="text6" style="width:10%;float:left;border:0px solid black;">Notice</span>
                    <a class="text8" href="javascript:void(0)" style="margin-left:10px; margin-top:3px;border:0px solid black; float:left; text-decoration:none" rel="help" title="Notice ini akan ditampikan pada halaman cpanel user,agar user dapat mengetahui alasan mengapa iklan mereka perlu diedit, dan apa saja yang perlu diedit tanpa harus membuka email mereka kembali. <br><br>Sebaiknya alasan yang dikemukakan sama dengan email yang anda kirim."><img src='<?php echo $this->webroot?>img/help.png' border="0"></a>
                 </div>
            	 <hr width="447"/>
            </div>
            <div class="line1">
                <div class="line3">
                    <div class="left" style="width:84%;border:0px solid black;margin-left:10px;">
                        <div class="line1">
                            <?php echo $form->textarea("Product.notice",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","style"=>"width:450px"))?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="line1" style="border:0px solid black; margin-top:10px; margin-bottom:10px;">
            <div class="left" style="border:0px solid black; width:36%; margin-right:15px; text-align:right;">
                &nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:40%">
                <input type="submit" name="button" id="button" value="Kirim" class="btn_sign" onClick="return SendMsgToParent()"/>
                <span class="font4" style="color:#000000;" id="loading"></span>
            </div>
        </div>
    </div>
</div>