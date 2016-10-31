<?php echo $javascript->link("jquery.filestyle")?>
<script>
	$(document).ready(function(){
		$("input.browse1").filestyle({ 
			  image: "<?php echo $this->webroot?>img/browse1.png",
			  imageheight : 30,
			  imagewidth : 80,
			  width : 0,
			  height : 30
		});
	});


function SubmitUpdate()
{
	$("#CompanyIndexForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>CpanelLogo/ProcessUpload",
		type		: "POST",
		dataType	: "json",
		contentType	: "multipart/form-data",
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
			if(data.status==true)
			{
				$("#error").find("div[id=error_text]").html("Success Upload Photo");
				$("#error").find("div[id=error_list]").html("Please wait, we will refresh your page ...");
				$("#error").find("img").attr("src","<?php echo $this->webroot?>img/check.png");
				$("#error").fadeIn('slow');
				$("#error").animate({opacity: 1.0}, 3000);
				$("#error").fadeOut('slow',function(){
					location.href	=	data.error;
				});
			}
			else
			{
				$("#error").find("div[id=error_list]").html(data.error);
				$("#error").show(300);
			}
		}
	});
	return false;
}

function UploadPhoto()
{
	$("#img_photo").html('');
	$("#err_photo").html('');
	$("#err_photo").html('');
	$("#CompanyIndexForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>CpanelLogo/UploadTmp",
		type		: "POST",
		dataType	: "json",
		contentType	: "multipart/form-data",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#CompanyPhoto").hide();
			$("#LoadingPhoto").show();
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
			
			if(data.status==true)
			{
				$("#CompanyPhoto").attr("src","<?php echo $settings['showimages_url']?>?code="+data.error+"&prefix=_prevthumb&content=RandomUser&w=120&h=120&time"+(new Date()).getTime());
				$("#CompanyPhoto").load(function(){
					$(this).show();
					$("#LoadingPhoto").hide();
				});
				$("#cancelButton").fadeIn(300);
				$("#img_photo").html('<img src="<?php echo $this->webroot?>img/check.png" />');
				$("#name_file").html(data.name_file);
				$("#error").hide(300);
			}
			else
			{
				$("#img_photo").html('<img src="<?php echo $this->webroot?>img/icn_error.png" />');
				$("#CompanyPhoto").attr("src","<?php echo $settings['showimages_url']?>?code=<?php echo $profile['Company']['id']?>&prefix=_prevthumb&content=Company&w=120&h=120&time"+(new Date()).getTime());
				$("#cancelButton").fadeIn(300);
				$("#LoadingPhoto").hide();
				$("#CompanyPhoto").show(300);
				$("#error").find("div[id=error_list]").html(data.error);
				$("#error").show(300);
				$("#name_file").html(data.name_file);
			}
		}
	});
	return false;
}

function cancelUpload()
{
	$("#cancelButton").fadeOut(300);
	$("#img_photo").html('');
	$("#error").hide(300);
	
	$("#file_browse").html('<?php echo $form->file("Company.photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?>');
	$("#name_file").html('');
	$("input.browse1").filestyle({ 
		  image: "<?php echo $this->webroot?>img/browse1.png",
		  imageheight : 30,
		  imagewidth : 80,
		  width : 0,
		  height : 30	  
	});
	$("#CompanyPhoto").attr("src","<?php echo $settings['showimages_url']?>?code=<?php echo $profile['Company']['id']?>&prefix=_prevthumb&content=Company&w=120&h=120&time"+(new Date()).getTime());
}
</script>
<div id="output"></div>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Dealer Logo</div>
        </div>
        <div class="line back3 size100 kiri position1 rounded2" style="padding-bottom:10px;">
        	<div class="size70 tengah" id="error" style="display:none">
                <div class="kiri size100 style1 white top10 reounded_error">
                    <div class="kiri size100 left10 top10 bold text14 blink" id="error_text">Error</div>
                   	<div class="kiri size100 left10 top10 bold text12 bottom10" id="error_list"></div>
                </div>
            </div>
        	<?php echo $form->create("Company",array("type"=>"file","onsubmit"=>"return SubmitUpdate()"))?>
            <div class="tengah size60" style="border:0px solid black;">
            	<div class="kiri top50" style="width:120px; height:120px;border:0px solid white;">
                    <div class="kiri" style="width:120px;height:auto;">
                        <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPhoto" style="margin:50px auto auto 50px; display:none"/>
                        <img src="<?php echo $settings['showimages_url']?>?code=<?php echo $profile['Company']['id']?>&prefix=_prevthumb&content=Company&w=120&h=120" id="CompanyPhoto" style="border:1px solid #ffffff; padding:2px;"/>
                        
                    </div>
                    <div class="line style1 white top5 text12" id="name_file" style="word-wrap: break-word;"></div>
                </div>
                <div class="kiri left20 top50" style="width:300px;border:0px solid white;">
                	<div class="kiri size30" id="file_browse" style="margin-left:-5px;border:0px solid white;">
                        <?php echo $form->file("photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?>
                        
                    </div>
                    <span class="kiri" id="img_photo"></span>
                    
                    <div class="line kiri top20 left5" style="display:none;" id="cancelButton">
                    	<input type="button" name="button" value="Cancel" class="tombol1" onclick="cancelUpload()" style="float:left"/>
                    </div>
                    <div class="line kiri top20 left5 style1 white text11" style="border:0px solid black;">
                        Accepted image format: .jpg .bmp .png. Size: <?php echo $number->toReadableSize($settings['max_photo_upload'])?>
                    </div>
                    <div class="line kiri top5 left5 style1 white text11">
                        <div class="kiri" style="width:10px;border:0px solid black;">
                            <input name="data[Company][agree]" id="agree_" value="" type="hidden">
                            <input name="data[Company][agree]" value="1" id="agree" type="checkbox" style="margin-left:-2px;">
                        </div>
                        <div class="kiri" style="width:90%;border:0px solid black;margin-left:10px;">
                            <label class="style1 white text11" for="agree">
                            Saya menyatakan bahwa saya memiliki hak dan wewenang untuk mendistribusikan gambar ini dan bahwa hal itu tidak melanggar <a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/TermConditions?iframe=true&amp;width=400&amp;height=440');" class="style1 red text11 normal">ketentuan dan sarat</a> yang berlaku.
                            </label>
                        </div>
                    </div>
                    <div class="line top20 left5 bottom50">
                        <input type="button" name="button" value="SUBMIT" class="tombol1" onclick="return SubmitUpdate()" style="float:left"/>
                        <div class="kiri style1 white text12 left5 top5" style="display:block" id="loading"></div>
                    </div>
                    
                </div>
            </div>
            <?php echo $form->end()?>
    	</div>
    </div>
</div>