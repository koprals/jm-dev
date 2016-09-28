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


function SuspendDanKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("UserPesan");
	}
	updateTextArea("UserPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.SuspendDanKirim($("#UserPesan").val());
}

function SuspendTanpaKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("UserPesan");
	}
	updateTextArea("UserPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.SuspendTanpaKirim();
}
function MultiSuspendDanKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("UserPesan");
	}
	updateTextArea("UserPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.MultiSuspendDanKirim($("#UserPesan").val());
}
function MultiSuspendTanpaKirim()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("UserPesan");
	}
	updateTextArea("UserPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	parent.MultiSuspendTanpaKirim();
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
	
	<?php if(!empty($user_id)):?>
		$.get("<?php echo $settings['cms_url']?>Template/GetStatusMessageUser",{"type":"-2",'user_id':'<?php echo $user_id?>'},function(data){
			$("#pesan").html('<?php echo $form->textarea("User.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px"))?>');
			$("#UserPesan").val(data);
			generate_wysiwyg('UserPesan');
			$("#BlockDanKirim").bind('click',function(){SuspendDanKirim();});
			$("#BlockTanpaKirim").bind('click',function(){SuspendTanpaKirim();});
		});
	<?php else:?>
		var html	=	'Kami menginformasikan bahwa akun anda telah kami blokir, karena anda tidak mengindahkan peringatan kami untuk tidak memasang iklan-iklan pornographi.';
		$("#UserPesan").val(html);
		generate_wysiwyg('UserPesan');
		$("#BlockDanKirim").bind('click',function(){MultiSuspendDanKirim();});
		$("#BlockTanpaKirim").bind('click',function(){MultiSuspendTanpaKirim();});
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
                    <?php echo $form->textarea("User.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px","value"=>''))?>
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
                    <input type="button" name="button" value="Blokir dan Kirim" id="BlockDanKirim" class="btn_sign"/>
                    <input type="button" name="button" value="Blokir tanpa Kirim" id="BlockTanpaKirim"  class="btn_sign" style="margin-left:5px;"/>
                </div>
                <span class="font4" style="color:#000000;" id="loading"></span>
            </div>
        </div>
    </div>
</div>