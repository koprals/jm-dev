<?php echo $javascript->link("jquery.filestyle")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.scrollTo")?>
<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.boxy")?>
<?php echo $html->css("boxy")?>

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
var count	= 	0;
var arr				=	 new Array();
var email			=	"";
var userstatus_id	=	"";

$(document).ready(function(){
	$("input.browse1").filestyle({ 
		  image: "<?php echo $this->webroot?>img/browse.png",
		  imageheight : 30,
		  imagewidth : 80,
		  width : 1,
		  height : 30		  
	});
	Boxy.DEFAULTS.title = 'Title';
	userstatus_id	=	$("#UserUserstatusId").html();
	email	= $("#UserEmail").val();
	
	<?php if(!empty($data)):?>
		SelectCity('<?php echo $data['Profile']['province_id']?>','<?php echo $data['Profile']['city_id']?>');
		ChooseTypeMember('<?php echo $data['User']['usertype_id']?>');
		$("#UserPhoto").attr("src","<?php echo $settings['showimages_url']?>?code=<?php echo $data['User']['id']?>&prefix=_prevthumb&content=User&w=120&h=120&time"+(new Date()).getTime());
		<?php foreach($ext_phone as $k=>$v):?>
			arr[<?php echo $k?>]	=	"<?php echo $v['ExtendedPhone']['phone']?>";
		<?php endforeach;?>
		ChangeStatus('<?php echo $data['User']['userstatus_id']?>');
	<?php endif;?>
	$("#UserFullname").watermark({watermarkText:'ex: Her Robby Fajar',watermarkCssClass:'all_input3_watermark'});
	$("#UserAddress").watermark({watermarkText:'ex: Jl Kedoya Timur No5',watermarkCssClass:'address_watermark'});
	$("#UserPhone").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'all_input3_watermark'});
	$("#UserFax").watermark({watermarkText:'ex: 0214562552',watermarkCssClass:'all_input3_watermark'});
	$("#UserEmail").watermark({watermarkText:'ex: abyfajar@gmail.com',watermarkCssClass:'all_input3_watermark'});
	$("#UserCname").watermark({watermarkText:'ex: Panprisa Motor',watermarkCssClass:'all_input3_watermark'});
		
	$("#UserAddress").jqEasyCounter({ 
		   'maxChars': <?php echo $settings['max_address_char']?>,
	       'maxCharsWarning': <?php echo $settings['max_address_char']-50?>,
	       'msgFontSize': '12px',
	       'msgFontColor': '#000',
	       'msgFontFamily': 'Arial',
	       'msgTextAlign': 'left',
	       'msgWarningColor': '#F00',
		   'msgAppendMethod': 'insertBefore',
		   'msgAppendSelector':'#charleft'     	  
	});
	

});

function ChangeStatus(val)
{
	if(val==-1 || val==-2 || val==-10)
	{
	$.get("<?php echo $settings['cms_url']?>Template/GetStatusMessageUser",{"type":val,'user_id':'<?php echo $data['User']['id']?>'},function(data){
		$("#pesan").html('<?php echo $form->textarea("User.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px"))?>');
		$("#UserPesan").val(data);
		generate_wysiwyg('UserPesan');
	});
		$("#KirimPesan").show();
	}
	else
	{
		$("#KirimPesan").hide();
	}
}


function Alert(a_title,a_message)
{
	Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:right; width:84%'>"+a_message+"</span></div>",function(){},{title:a_title});
}

function ChooseTypeMember(value)
{
	$("#img_cname").html("");
	$("#err_cname").html("");
	if(value=="2")
	{
		$("#dealer").fadeIn(900);
	}
	else if(value=="1")
	{
		$("#dealer").fadeOut(900);
		
	}
}

function ResendEmailVerification(user_id)
{
	$.getJSON("<?PHP $settings['site_url']?>Users/ResendVerification",{'user_id':user_id},function(url){
		$.prettyPhoto.open(url+'?iframe=true&width=510&height=100');
	});	
}

function ChangeEmail()
{

	if($("#change_email").html()=='[change email]')
	{
		$("#change_email").html('[cancel]');
		$("#UserEmail").attr("readonly","");
		$("#UserEmail").css("background-color","#ffffff");
		
		<?php if($data['User']['userstatus_id']==1):?>
			$("#UserUserstatusId").html('<option selected="selected" value="1">Active</option>');
		<?php else:?>
			$("#UserUserstatusId").html('<option selected="selected" value="0">Waiting Email Confirm</option>');
		<?php endif;?>
	}
	else
	{
		$("#change_email").html('[change email]');
		$("#UserEmail").attr("readonly","readonly");
		$("#UserEmail").css("background-color","#cccccc");
		$("#UserEmail").val(email);
		$("#UserUserstatusId").html(userstatus_id);
	}
}
function AddPoint()
{
	$("#add_point").show(300);
	$("#is_add_point").val(1);
	$("#add_point_link").hide(300);
	CancelDecreasePoint();
}

function CancelAddPoint()
{
	$("#add_point").hide(300);
	$("#is_add_point").val(0);
	$("#add_point_link").show(300);
}

function DecreasePoint()
{
	$("#decrease_point").show(300);
	$("#is_decrease_point").val(1);
	$("#decrease_point_link").hide(300);
	CancelAddPoint();
}

function CancelDecreasePoint()
{
	$("#decrease_point").hide(300);
	$("#is_decrease_point").val(0);
	$("#decrease_point_link").show(300);
}

function SelectCity(province_id,city_id)
{
	if(province_id.length>0)
	{
		$("#pilih_kota").load("<?php echo $settings['cms_url']?>Template/SelectCity/",{'province_id':province_id,'city_id':city_id});
	}
	else
	{
		$("#pilih_kota").html('<select name="data[User][city]" class="sel1" label="false" id="city"><option value="" selected="selected">Pilih Kota</option></select>');	
	}
}


function DeletePhone(id)
{
	$("#"+id).remove();
	count--;
	$("#add_phone").show(300);
	$("#UserCountPhone").val(parseInt($("#UserCountPhone").val())-1);
	$("span[rel^=aby_]").each(function(i){
		$(this).html("No Telp-"+parseInt(i+1));
	});
}

function AddPhone()
{
	count	=	$("#phone .line3:last-child").attr("rel");
	if(typeof(count) == 'undefined' && count == null)
	{
		count	=	0;
	}

	count++;
	var add		=	"";
	add			+=	'<div class="line3" style="margin-top:10px;" id="phone_'+count+'" rel="'+count+'">';
    add			+=		'<div class="left" style="border:0px solid black; width:15%;">';
    add			+=			'<span rel="aby_'+(count+1)+'">No Telp-'+(count+1)+':</span>';
    add			+=		'</div>';
    add			+=		'<div class="right" style="border:0px solid black; width:84%">';
	add			+=			'<input name="data[ExtendedPhone]['+count+'][phone]" class="all_input3" type="text" style="width:235px;" rel="inputaby_'+count+'"><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none" onclick="DeletePhone(\'phone_'+count+'\')">[ delete phone ]</a>';
    add			+=			'<span style="margin-left:5px;" id="img_phone'+count+'"></span>';
	add			+=			'<span class="error" id="err_phone'+count+'"></span>';
    add			+=		'</div>';
    add			+=	'</div>';
	
	$("#UserCountPhone").val(parseInt($("#UserCountPhone").val())+1);
	var lastCount	=	$("#UserCountPhone").val();
	
	if(lastCount<3)
	{
		$("#phone").append(add);
		if(lastCount==2)
		{
			$("#add_phone").hide(300);
		}
	}
	else
	{
		$("#add_phone").hide(300);
	}
	$("span[rel^=aby_]").each(function(i){
		$(this).html("No Telp-"+parseInt(i+1));
	});
	$("input[rel^=inputaby_]").each(function(i){
		if(typeof(arr[i]) != 'undefined' && arr[i] != null)
		{
			$(this).val(arr[i]);
		}
	});
}

function UploadPhoto()
{
	$("#img_photo").html('');
	$("#err_photo").html('');
	$("#err_photo").html('');
	$("#UserAddForm").ajaxSubmit({
		url			: "<?php echo $settings['cms_url']?>Users/UploadTmp",
		type		: "POST",
		dataType	: "json",
		contentType	: "multipart/form-data",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#UserPhoto").hide();
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
				$("#UserPhoto").attr("src","<?php echo $settings['showimages_url']?>?code="+data.error+"&prefix=_prevthumb&content=RandomUser&w=120&h=120&time"+(new Date()).getTime());
				$("#UserPhoto").load(function(){
					$(this).show();
					$("#LoadingPhoto").hide();
				});
				$("#cancelButton").fadeIn(300);
				$("#img_photo").html('<img src="<?php echo $this->webroot?>img/check.png" />');
				$("#name_file").html(data.name_file);
			}
			else
			{
				$("#cancelButton").fadeIn(300);
				$("#img_photo").html('<img src="<?php echo $this->webroot?>img/icn_error.png" />');
				$("#UserPhoto").attr("src","<?php echo $this->webroot?>img/user.png");
				$("#LoadingPhoto").hide();
				$("#UserPhoto").show(300);
				$("#err_photo").html(data.error);
				$("#name_file").html(data.name_file);
			}
		}
	});
	return false;
}

function SubmitRegister()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("UserPesan");
	}
	updateTextArea("UserPesan");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	
	$("#UserAddForm").ajaxSubmit({
		<?php if(!empty($data)):?>
		url			: "<?php echo $settings['cms_url']?>Users/EditUser",
		<?php else:?>
		url			: "<?php echo $settings['cms_url']?>Users/AddUser",
		<?php endif;?>
		type		: "POST",
		dataType	: "json",
		contentType	: "multipart/form-data",
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
				$("#output").html(data);
				alert(data.error);
				location.href='<?php echo $settings['cms_url']?>Users/Index';
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

function cancelUpload()
{
	$("#cancelButton").fadeOut(300);
	$("#img_photo").html('');
	$("#err_photo").html('');
	
	$("#file_browse").html('<?php echo $form->file("User.photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?>');
	$("#name_file").html('');
	$("input.browse1").filestyle({ 
		  image: "<?php echo $this->webroot?>img/browse.png",
		  imageheight : 30,
		  imagewidth : 80,
		  width : 1,
		  height : 30		  
	});
	<?php if(!empty($data)):?>
		$("#UserPhoto").attr("src","<?php echo $settings['showimages_url']?>?code=<?php echo $data['User']['id']?>&prefix=_prevthumb&content=User&w=120&h=120&time"+(new Date()).getTime());
	<?php else:?>
		$("#UserPhoto").attr("src","<?php echo $this->webroot?>img/user.png");
	<?php endif;?>
	
}
</script>
<div id="output"></div>
<?php echo $this->requestAction('/Template/UserLeftMenu/'.$data['User']['id']."/general",array('return'))?>
<div class="test-right">
    <div class="content">
    	<?php echo $form->create('User')?>
			<?php echo $form->input("count_phone",array("type"=>"hidden","value"=>count($ext_phone)))?>
            <?php echo $form->input("id",array("type"=>"hidden","value"=>$data['User']['id'],"readonly"=>"readonly"))?>
            <div class="line1">
                <div class="line3">
                    <div class="left" style="width:100%;">
                        <span class="nav_2">Informasi data pribadi</span>
                        <div class="line3">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Nama Lengkap</div>
                            </div>
                            <div class="right" style="width:84%;border:0px solid black;">
                                <?php echo $form->input("fullname",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Profile']['fullname'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                                <span style="margin-left:5px;" id="img_fullname"></span> 
                                <span class="error" id="err_fullname" ></span>
                            </div>
                        </div>
                        <div class="line3">
                            <div class="left" style="width:15%;">
                                <div class="text4">Gender</div>
                            </div>
                            <div class="right" style="width:84%;border:0px solid black;">
                                <?php echo $form->input("gender",array('options'=>array('Pria'=>"&nbsp;Pria",'Wanita'=>"&nbsp;Wanita"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Profile']['gender']) )?>
                                <span style="margin-left:5px;" id="img_gender"></span> 
                                <span class="error" id="err_gender"></span>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Alamat</div>
                            </div>
                            <div class="right" style="width:84%;">
                                 <?php echo $form->textarea("address",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$data['Profile']['address']))?>
                                 <span style="margin-left:5px;" id="img_address"></span>
                                 <span class="error" id="err_address" ></span>
                                 <span class="text8" id="charleft" style="float:left; width:100%"></span>
                             </div>
                        </div>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong>Propinsi</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php echo $form->select("province",$province,$data['Profile']['province_id'],array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>"Pilih Propinsi","onchange"=>"SelectCity(this.value,'".$data['Profile']['city_id']."')"));?>
                                <span style="margin-left:5px;" id="img_province"></span>
                                <span class="error" id="err_province" ></span>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong>City</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <div id="pilih_kota"><?php echo $form->select("city",array(""=>"Pilih Kota"),false,array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>false));?></div>
                                <span style="margin-left:5px;" id="img_city"></span>
                                <span class="error" id="err_city" ></span>
                            </div>
                        </div>
                        
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4">Latitude/Longitude</div>
                            </div>
                            <div class="right" style="width:84%;">
                            
                            	<?php if(empty($data)):?>
                                	<a href="javascript:$.prettyPhoto.open('<?php echo $settings['cms_url']?>Template/Map?iframe=true&amp;width=545&amp;height=390');" rel="help" title="Klik disni untuk mendapatkan posisi pasti dari alamat member." style="margin-left:5px; float:left;"><img src="<?php echo $settings['cms_url']?>img/maps_ico_tiny.png" style="border:0px solid black;" width="30"/></a>
                                <?php else:?>
                                	<a href="javascript:$.prettyPhoto.open('<?php echo $settings['cms_url']?>Template/MapProfile/<?php echo $data['User']['id']?>/?iframe=true&amp;width=545&amp;height=390');" rel="help" title="Klik disni untuk mendapatkan posisi pasti dari alamat member." style="margin-left:5px; float:left;"><img src="<?php echo $settings['cms_url']?>img/maps_ico_tiny.png" style="border:0px solid black;" width="30"/></a>
                                <?php endif;?>
                                <?php
                                	$lat	=	empty($data) ? 0 : $data['Profile']['lat'];
									$lng	=	empty($data) ? 0 : $data['Profile']['lng'];
								?>
								<?php echo $form->input("lat",array("type"=>"hidden","id"=>"UserLat","value"=>$lat))?>
                    			<?php echo $form->input("lng",array("type"=>"hidden","id"=>"UserLng","value"=>$lng))?>
                    
                            </div>
                            <div class="line1" id="span_lat_lng"></div>
                        </div>
                        
                        <div  class="line1" id="phone">
                            <div class="line3" style="margin-top:10px;">
                                <div class="left" style="width:15%;">
                                    <div class="text4"><strong>*</strong>No Telp</div>
                                </div>
                                <div class="right" style="width:84%;">
                                    <?php echo $form->input("phone",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Profile']['phone'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                                    <?php if(count($ext_phone)<2):?>
                                        <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none;display:block" onClick="AddPhone()" id="add_phone">[ add phone ]</a>
                                    <?php else:?>
                                        <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none;display:none" onClick="AddPhone()" id="add_phone">[ add phone ]</a>
                                    <?php endif;?>
                                    <span style="margin-left:5px;" id="img_phone"></span> 
                                    <span class="error" id="err_phone" ></span>
                                </div>
                            </div>
                            <?php $count=0;if(!empty($ext_phone)):?>
                            <?php foreach($ext_phone as $ext_phone):?>
                            <div class="line3" style="margin-top:10px;" id="phone_<?php echo  $count?>" rel="<?php echo  $count?>">
                                <div class="left" style="border:0px solid black; width:15%;">
                                    <span rel="aby_<?php echo  $count?>">No Telp-<?php echo  $count+1?></span>
                                </div>
                                <div class="right" style="border:0px solid black; width:84%">
                                    <input name="data[ExtendedPhone][<?php echo  $count?>][phone]" class="all_input3" type="text" value="<?php echo $ext_phone['ExtendedPhone']['phone']?>" style="width:235px;" rel="inputaby_[<?php echo  $count?>"><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px; text-decoration:none" onclick="DeletePhone('phone_<?php echo  $count?>')">[ delete phone ]
                                    </a>
                                    <span style="margin-left:5px;" id="img_phone<?php echo  $count?>"></span>
                                    <span class="error" id="err_phone<?php echo  $count?>"></span>
                                </div>
                            </div>
                            <?php $count++?>
                            <?php endforeach;?>
                            <?php endif;?>
                        </div>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4">Fax</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php echo $form->input("fax",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Profile']['fax'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                                <span style="margin-left:5px;" id="img_fax"></span> 
                                <span class="error" id="err_fax"></span>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:16%;">
                                <div class="text4">Photo</div>
                            </div>
                            <div class="left" style="width:84%;">
                                <div class="left" style="width:130px; height:120px;">
                                    <div class="left" style="border:1px solid #999999; width:120px;height:120px;">
                                        <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPhoto" style="margin:50px auto auto 50px; display:none"/>
                                        <img src="<?php echo $this->webroot?>img/user.png" id="UserPhoto"/>
                                    </div>
                                    <div class="line1" style="margin-top:5px" id="name_file"></div>
                                </div>
                                <div class="left" style="width:190px; border:0px solid black; float:left">
                                    <span id="file_browse"><?php echo $form->file("photo",array("class"=>"browse1","label"=>false,"div"=>false,"error"=>false,"onchange"=>"return UploadPhoto()"))?></span>
                                    <span style="margin-right:65px; float: right;" id="img_photo"></span>
                                    <div class="line1" style="margin-left:10px; margin-top:10px; display:none;" id="cancelButton">
                                        <a href="javascript:void(0)" onclick="cancelUpload()" ><img src="<?php echo $this->webroot?>img/cancel_big.png" border="0"/></a>
                                    </div>
                                    <div class="line1" style="margin-top:20px;margin-left:10px;">
                                        <span class="text8">Accepted image format: .jpg .bmp .png. Size: <?php echo $number->toReadableSize($settings['max_photo_upload'])?></span>
                                    </div>
                                </div>
                                <div class="line1" style="margin-top:25px;">
                                    <span class="error" id="err_photo"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="line3" style="margin-top:20px;">
                            <span class="nav_2">Informasi Akses Member</span>
                        </div>
                        <div class="line3">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Email Address</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php if(!empty($data)):?>
									<?php echo $form->input("email",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['User']['email'],"style"=>"width:48.8%; float:left; height:16px; background-color:#cccccc","readonly"=>"readonly"))?>
                                    <?php if(in_array($data['User']['userstatus_id'],array(0,1))):?>
                                    	<a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px;" onClick="ChangeEmail()" id="change_email">[change email]</a>
                                    <?php else:?>
                                    	<a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px;" onClick="Alert('Change Email','Tidak dapat merubah email, member ini dalam proses blokir.')" id="change_email">[change email]</a>
                                    <?php endif;?>
                                <?php else:?>
                                    <?php echo $form->input("email",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['User']['email'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                                <?php endif;?>
                                <span style="margin-left:5px;" id="img_email"></span> 
                                 <span class="error" id="err_email" ></span>
                            </div>
                        </div>
                        <?php if(empty($data)):?>
                        <div class="line3" style="margin-top:10px">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Password</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php echo $form->input("password",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"password","style"=>"width:48.8%; float:left; height:16px;","maxlength"=>10))?>
                                <span style="margin-left:5px;" id="img_password"></span> 
                                 <span class="error" id="err_password" ></span>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Ulangi Password</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php echo $form->input("retype_password",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"password","style"=>"width:48.8%; float:left; height:16px;","maxlength"=>10))?>
                                <span style="margin-left:5px;" id="img_retype_password"></span> 
                                 <span class="error" id="err_retype_password" ></span>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php if(!empty($data)):?>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4">Tgl Daftar</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <div class="text4"><?php echo date("d-M-Y H:i:s",strtotime($data['User']['created']))." (".$time->timeAgoInWords($data['User']['created']).")"?></div>
                            </div>
                        </div>
                        <?php if($data['User']['activated']!="0000-00-00 00:00:00"):?>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4">Tgl Aktivasi</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <div class="text4"><?php echo date("d-M-Y H:i:s",strtotime($data['User']['activated']))?></div>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4">Terakhir Login</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <div class="text4"><?php echo date("d-M-Y H:i:s",strtotime($data['User']['last_login']))." (".$time->timeAgoInWords($data['User']['last_login']).")"?></div>
                            </div>
                        </div>
                        <?php endif;?>
                        
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4">Point</div>
                            </div>
                            <div class="right" style="width:84%; border:0px solid black;">
                                <div class="text4"><?php echo $data['User']['points']?> JPoint</div>
                                <?php echo $form->input("is_add_point",array("type"=>"hidden","value"=>0,"id"=>"is_add_point"))?>
                                <?php echo $form->input("is_decrease_point",array("type"=>"hidden","value"=>0,"id"=>"is_decrease_point"))?>
                                
                                <?php if($data['User']['userstatus_id']>=1):?>
                                <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px;" onClick="AddPoint()" id="add_point_link">[add point]</a>
                                <a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px;" onClick="DecreasePoint()" id="decrease_point_link">[decrease point]</a>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px; display:none;" id="add_point">
                            <div class="left" style="width:15%;">
                                <div class="text4">Add Point</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php echo $form->input("add_point",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;"))?><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px;" onClick="CancelAddPoint()">[cancel]</a>
                                <span style="margin-left:5px;" id="img_add_point"></span> 
                                <span class="error" id="err_add_point" ></span>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px; display:none;" id="decrease_point">
                            <div class="left" style="width:15%;">
                                <div class="text4">Decrease Point</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php echo $form->input("decrease_point",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;"))?><a class="text8" href="javascript:void(0)" style="margin-left:10px; border:0px solid black; float:left; margin-top:5px;" onClick="CancelDecreasePoint()">[cancel]</a>
                                <span style="margin-left:5px;" id="img_decrease_point"></span> 
                                <span class="error" id="err_decrease_point" ></span>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="line3" style="margin-top:10px;">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Status</div>
                            </div>
                            <div class="right" style="width:84%;">
                                <?php echo $form->select("userstatus_id",$userstatus_id,$data['User']['userstatus_id'],array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>false,"onchange"=>"ChangeStatus(this.value)"));?>
                                <span style="margin-left:5px;" id="img_userstatus_id"></span> 
                                <span class="error" id="err_userstatus_id" ></span>
                            </div>
                        </div>
                        <div id="KirimPesan" style="display:none">
                            <span class="nav_2" style="margin-top:20px;">Kirim pesan</span>
                            <div class="line3" style="margin-top:10px;height:250px">
                                <div class="left" style="width:15%;">
                                    <div class="text4">Pesan</div>
                                </div>
                                <div class="right" style="width:84%;border:0px solid black; height:200px">
                                    <div id="pesan" class="line1">
                                    <?php echo $form->textarea("User.pesan",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px"))?>
                                    <script language="javascript1.2">
                                        generate_wysiwyg('UserPesan');
                                    </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:20px;">
                            <span class="nav_2">Tipe Member</span>
                        </div>
                        <div class="line3">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Tipe Member</div>
                            </div>
                            <div class="left" style="width:84%;">
                               <?php echo $form->input("usertype_id",array('options'=>array('1'=>"&nbsp;Perorangan",'2'=>"&nbsp;Dealer/Perusahaan/Distributor"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>!empty($data['User']['usertype_id'])?$data['User']['usertype_id']:1,"onclick"=>"ChooseTypeMember(this.value)") )?>
                                <span style="margin-left:5px;" id="img_email"></span> 
                                <span class="error" id="err_email" ></span>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:10px; display:none;" id="dealer">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Nama Dealer</div>
                            </div>
                            <div class="left" style="width:84%;">
                                <?php echo $form->input("cname",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Company']['name'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                                <span style="margin-left:5px;" id="img_cname"></span> 
                                <span class="error" id="err_cname" ></span>
                            </div>
                        </div>
                        <div class="line3" style="margin-top:20px;">
                            <span class="nav_2">Tipe User</span>
                        </div>
                        <div class="line3">
                            <div class="left" style="width:15%;">
                                <div class="text4"><strong>*</strong> Tipe User</div>
                            </div>
                            <div class="left" style="width:84%;">
                                <?php echo $form->select("admintype_id",$admintype_id,$data['User']['admintype_id'],array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>"Tipe User"));?>
                                <span style="margin-left:5px;" id="img_admintype_id"></span> 
                                <span class="error" id="err_admintype_id" ></span>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:15px;">
                            <div class="left" style="width:10%; margin-right:10px;">
                                <input type="submit" name="button" id="button" value="Submit" class="btn_sign" onClick="return SubmitRegister()"/>
                                <span class="font4" style="color:#000000;" id="loading"></span>
                            </div>
                            <?php if(!empty($data)):?>
                            <div class="left" style="width:20%;">
                                <input type="button" name="resend" value="Resend email verification" class="btn_sign" onClick="javascript:$.prettyPhoto.open('<?php echo $settings['cms_url']?>Users/ResendVerification/<?php echo $data['User']['id']?>?iframe=true&amp;width=510&amp;height=100')"/>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        <?php echo $form->end();?>
    </div>
</div>