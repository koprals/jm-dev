<?php echo $javascript->link("jquery.counter")?>
<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.scrollTo")?>

<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->

<script>
var ROOT		=	"<?php echo $settings['web_url']?>";
imagesDir		=	"<?php echo $this->webroot?>img/wysiwyg/";
popupsDir		=	"<?php echo $this->webroot?>wysiwyg_popup/";
wysiwygWidth	=	700;
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
	ChangeResponse();
});

function ChangeResponse()
{
	
	var name		=	$("#InvitationName").val();
	var username	=	$("#InvitationUsername").val();
	var password	=	$("#InvitationPassword").val();
	var email		=	$("#InvitationEmail").val();
	
	$.get("<?php echo $settings['cms_url']?>Invite/GetMessage",{"name":name,"username":username,"password":password,"email":email,"id":""},function(data){
		$("#response").html('<?php echo $form->textarea("Invitation.message",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px","value"=>""))?>');
		$("#InvitationMessage").val(data);
		generate_wysiwyg('InvitationMessage');
	});
}

function SubmitEdit()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("InvitationMessage");
	}
	updateTextArea("InvitationMessage");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	

	$("#FormAddInvite").ajaxSubmit({
		url			: "<?php echo $settings['cms_url']?>Invite/ProcessAdd",
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

</script>
<div id="output"></div>
<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code)) ?>
<div class="test-right">
    <div class="content">
    
    	<?php echo $form->create('Invitation',array("id"=>"FormAddInvite"))?>
        <?php echo $form->input("id",array("type"=>"hidden","readonly"=>true))?>
        <div class="line1">
            <div class="line3">
                <div class="left" style="width:100%;">
                    <span class="nav_2">Informasi User</span>
                    
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Nama</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("name",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_name"></span> 
                            <span class="error" id="err_name" ></span>
                        </div>
                	</div>
                    
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Username</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("username",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;","onblur"=>"ChangeResponse()"))?>
                            <span style="margin-left:5px;" id="img_username"></span> 
                            <span class="error" id="err_username" ></span>
                        </div>
                	</div>
                    
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Password</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("password",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;","onblur"=>"ChangeResponse()"))?>
                            <span style="margin-left:5px;" id="img_password"></span> 
                            <span class="error" id="err_password" ></span>
                        </div>
                	</div>
                    
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Email</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("email",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;","onblur"=>"ChangeResponse()"))?>
                            <span style="margin-left:5px;" id="img_email"></span> 
                            <span class="error" id="err_email" ></span>
                        </div>
                	</div>
                    
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Message</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<div id="response"></div>
                            <span style="margin-left:5px;" id="img_message"></span> 
                            <span class="error" id="err_message"></span>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            &nbsp;
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->checkbox("kirim_pesan",array("value"=>1))?>
                             <label for="InvitationKirimPesan">Simpan dan Kirim Pesan</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="line1" style="border:0px solid black; margin-top:30px;">
            <div class="left" style="border:0px solid black; width:20%; margin-right:15px; text-align:right;">
                &nbsp;
            </div>
            <div class="left" style="border:0px solid black; width:50%">
                <input type="submit" name="button" id="button" value="Kirim" class="btn_sign" onClick="return SubmitEdit()"/>
                <span class="font4" style="color:#000000;" id="loading"></span>
            </div>
        </div>
        <?php echo $form->end();?>
    </div>
</div>