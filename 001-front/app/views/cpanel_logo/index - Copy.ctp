<?php echo $javascript->link("jquery.filestyle")?>
<script>
	$(document).ready(function(){
		$("input.browse1").filestyle({ 
			  image: "<?php echo $this->webroot?>img/browse.png",
			  imageheight : 30,
			  imagewidth : 80,
			  width : 1,
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
			$("#LoadingPict").show(300);
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
				$("#error").find("li").html("Please wait, we will refresh your page ...");
				$("#error").find(".text4").html("Success Upload Photo");
				$("#error").find("img").attr("src","<?php echo $this->webroot?>img/check.png");
				$("#error").fadeIn('slow');
				$("#error").animate({opacity: 1.0}, 3000);
				$("#error").fadeOut('slow',function(){
					location.href	=	data.error;
				});
			}
			else
			{
				$("#error").find("li").html(data.error);
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
				$("#cancelButton").fadeIn(300);
				$("#img_photo").html('<img src="<?php echo $this->webroot?>img/icn_error.png" />');
				$("#CompanyPhoto").attr("src","<?php echo $settings['showimages_url']?>?code=<?php echo $profile['Company']['id']?>&prefix=_prevthumb&content=Company&w=120&h=120&time"+(new Date()).getTime());
				$("#LoadingPhoto").hide();
				$("#CompanyPhoto").show(300);
				$("#error").find("li").html(data.error);
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
		  image: "<?php echo $this->webroot?>img/browse.png",
		  imageheight : 30,
		  imagewidth : 80,
		  width : 1,
		  height : 30		  
	});
	$("#CompanyPhoto").attr("src","<?php echo $settings['showimages_url']?>?code=<?php echo $profile['Company']['id']?>&prefix=_prevthumb&content=Company&w=120&h=120&time"+(new Date()).getTime());
}
</script>
<div id="output"></div>
<div class="box_panel" style="min-height:50px; margin-bottom:10px;">
	<div class="line1" style=" margin-bottom:10px;">
        <div class="line4" style="border:0px solid black;">
            <span class="text3">Upload My Photo</span>
        </div>
    </div>
    
    <?php echo $form->create("Company",array("type"=>"file","onsubmit"=>"return SubmitUpdate()"))?>
    <div class="line1" style="margin-bottom:12px;">
        <div class="left" style="border:0px solid black; width:55%;float:none; margin:10px auto;">
        	<div class="line1" style="border:2px solid red; width:350px; margin-bottom:20px; padding-bottom:10px; display:none;" id="error">
                <div class="left" style="width:40px; padding-top:0px; padding-left:10px;border:0px solid black;">
                    <img src="<?php echo $this->webroot?>img/icn_error.png" style="margin-top:5px;">
                </div>
                <div class="left" style="width:270px;border:0px solid black">
                    <span class="text4" style="font-weight:bold">There is some errors</span>
                    <div class="line1" style="border:0px solid black">
                        <ul style="list-style:none; margin:10px 0 0 -40px; color:#F00; text-decoration:blink">
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="line1">
                <div class="left" style="width:120px; height:120px;border:0px solid black;">
                    <div class="left" style="width:120px;height:auto;">
                        <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPhoto" style="margin:50px auto auto 50px; display:none"/>
                        <img src="<?php echo $settings['showimages_url']?>?code=<?php echo $profile['Company']['id']?>&prefix=_prevthumb&content=Company&w=120&h=120" id="CompanyPhoto" style="border:1px solid #999999; padding:2px;"/>
                        
                    </div>
                    <div class="line1" style="margin-top:5px" id="name_file">&nbsp;</div>
                </div>
                <div class="left" style="width:300px; border:0px solid black; float:left; margin-left:10px;">
                    <span id="file_browse" style="margin-left:-5px;">
                        <?php echo $form->file("photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?>
                    </span>
                    <span style="margin-right:170px; float: right;" id="img_photo"></span>
                    
                    <div class="line1" style="margin-left:5px; margin-top:10px; display:none;" id="cancelButton">
                        <a href="javascript:void(0)" onclick="cancelUpload()" >
                            <img src="<?php echo $this->webroot?>img/cancel_big.png" border="0"/>
                        </a>
                    </div>
                    <div class="line1" style="margin-top:20px;margin-left:10px;border:0px solid black;">
                        <span class="text8">Accepted image format: .jpg .bmp .png. Size: <?php echo $number->toReadableSize($settings['max_photo_upload'])?></span>
                    </div>
                    <div class="line1" style="margin-top:20px;margin-left:10px;">
                        <div class="left" style="width:10px;border:0px solid black;">
                            <input name="data[Company][agree]" id="agree_" value="" type="hidden">
                            <input name="data[Company][agree]" value="1" id="agree" type="checkbox" style="margin-left:-2px;">
                        </div>
                        <div class="left" style="width:90%;border:0px solid black;margin-left:10px;">
                            <label class="text8" for="agree">
                            Saya menyatakan bahwa saya memiliki hak dan wewenang untuk mendistribusikan gambar ini dan bahwa hal itu tidak melanggar <a href="javascript:$.prettyPhoto.open('<?php echo $settings['site_url']?>Template/TermConditionsUpload?iframe=true&amp;width=450&amp;height=550')" class="text8">ketentuan dan sarat</a> yang berlaku.
                            </label>
                        </div>
                    </div>
                    <div class="line1" style="margin-top:20px;margin-left:10px;">
                        <div class="left" style="width:20px;border:0px solid black;">
                            <input type="submit" name="button" id="button" value="Upload" class="btn_sign" onClick="return SubmitUpdate()"/>
                            <span class="font4" style="color:#000000;" id="loading"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $form->end()?>
</div>