<?php if(!empty($data)):?>
<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $html->css("jquery.sceditor.min")?>
<?php echo $javascript->link("jquery.sceditor")?>
<?php echo $javascript->link("jquery.sceditor.bbcode")?>

<script>
$(document).ready(function(){
	$("#NewsDescription").sceditorBBCodePlugin({
		toolbar:	"bold,italic,underline,strike,subscript,superscript|left,center,right,justify|" +
				"bulletlist,orderedlist|" + "undo,redo|link,unlink|" + "source"
	});
});

function SubmitAdd()
{
	$.sceditorBBCodePlugin.documentClickHandler;
	$("#NewsForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Admin/PrcessEditNews",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		contentType	: "multipart/form-data",
		beforeSend	: function()
		{
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19.gif"/>Please wait ..');
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
				$(document).scrollTo("#psng_ikln", 800);
				$("#success").fadeIn('slow');
				$("#success").animate({opacity: 1.0}, 3000);
				$("#success").fadeOut('slow',function(){
					location.href	=	data.error;
				});
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
}
</script>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Edit News</div>
        </div>
		<div class="line back3 size100 kiri position1 rounded2" style="padding-bottom:10px;" id="psng_ikln">
        	<div class="size80 tengah" id="success"  style="display:none">
                <div class="kiri size100 style1 white top10 reounded_error">
                    <div class="kiri size100 left10 top10 bold text14 blink">Success Save</div>
                    <div class="kiri size100 left10 top10 bold text12 bottom10">Please wait, we will refresh your page ...</div>
                </div>
            </div>
            <div class="tengah size65">
				<?php echo $form->create("News",array("onsubmit"=>"return SubmitAdd()","type"=>"file","id"=>"NewsForm"))?>
				<?php echo $form->input("id",array("type"=>"hidden","value"=>$datap['News']['id']))?>
				<div class="line top20">
                    <div class="style1 text14 bold white" id="span_title"><span class="text15">*</span>Title :</div>
                    <?php echo $form->input("title",array("class"=>"input3 style1 black text12 size50 kiri","div"=>false,"label"=>false,"type"=>"text"))?>
                    <span class="kiri left10" id="img_title"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_title"></span>
                </div>
				 <div class="line top20">
                    <div class="style1 text14 bold white" id="span_description">Keterangan :</div>
                    <?php echo $form->textarea("description",array("label"=>false,"div"=>false,"error"=>false,"class"=>"input3 style1 black text12 size70 kiri textarea","style"=>"height:400px;width:550px;"))?>
                    <span class="kiri left10" id="img_description"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_description"></span>
                    
                </div>
				<div class="line top10">
                    <div class="style1 text14 bold white" id="span_photo"><span class="text15">*</span> Image :</div>
                    <img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data['News']['id']?>&prefix=_150x150&content=News&w=150&h=150&nopict=noimages&time=<?php echo time()?>" style="border:1px solid #cccccc; padding:1px; margin-right:10px; float:left"/>
					<?php echo $form->file("photo",array("style"=>"float:left;"))?>
                    <span class="kiri left10" id="img_photo"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_photo"></span>
                </div>
				<div class="line top10">
                    <div class="style1 text14 bold white" id="span_province"><span class="text15">*</span> Publish :</div>
                    <?php echo $form->input("status",array('options'=>array('0'=>"&nbsp;No",'1'=>"&nbsp;Yes"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>"0") )?>
                    <span class="kiri left10" id="img_status"></span>
                    <span class="line kiri style1 white text12 bold" style="text-decoration:blink;" id="err_status"></span>
                </div>
				<div class="line top20">
                    <input type="button" name="button" value="SUBMIT" class="tombol1" onclick="SubmitAdd()" style="float:left"/>
                    <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                </div>
				<?php echo $form->end();?>
			</div>
		</div>
	</div>
</div>
<?php else:?>
<div class="box_alert">
	<div class="alert">
		<img src="<?php echo $this->webroot?>img/warning.gif"/>
		Data Not Found<br /><br />
	</div>
</div>
<?php endif;?>