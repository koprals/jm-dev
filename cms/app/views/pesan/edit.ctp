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
	ChangeResponse('<?php echo $data["Contact"]["id"]?>');
});
function ChangeResponse(val)
{
	$.get("<?php echo $settings['cms_url']?>Pesan/GetMessage",{"contact_id":val},function(data){
		$("#response").html('<?php echo $form->textarea("Contact.response_message",array("label"=>false,"div"=>false,"error"=>false,"style"=>"height:250px"))?>');
		$("#ContactResponseMessage").val(data);
		generate_wysiwyg('ContactResponseMessage');
	});
}


function SubmitEdit()
{
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	if(viewTextMode==1)
	{
		viewText("ContactResponseMessage");
	}
	updateTextArea("ContactResponseMessage");
	/****nilai viewTextMode di ambil dari wysiwyg.js ***/
	

	$("#FormEditContact").ajaxSubmit({
		url			: "<?php echo $settings['cms_url']?>Pesan/ProcessEdit",
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
    	<?php if(!empty($data)):?>
    	<?php echo $form->create('Contact',array("id"=>"FormEditContact"))?>
        <?php echo $form->input("id",array("type"=>"hidden","value"=>$data['Contact']['id']))?>
        <div class="line1">
            <div class="line3">
                <div class="left" style="width:100%;">
                    <span class="nav_2">Informasi Pesan</span>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Pengirim</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("from",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Contact']['from'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_from"></span> 
                            <span class="error" id="err_from" ></span>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Email</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("email",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Contact']['email'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_email"></span> 
                            <span class="error" id="err_email"></span>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>&nbsp;</strong> Telp</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("phone",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","value"=>$data['Contact']['phone'],"style"=>"width:48.8%; float:left; height:16px;"))?>
                            <span style="margin-left:5px;" id="img_phone"></span> 
                            <span class="error" id="err_phone"></span>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Kategori</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->select("contact_category_id",$contact_category_id,$data['Contact']['contact_category_id'],array("class"=>"sel1","label"=>"false","escape"=>false,"empty"=>false));?>
                            <span style="margin-left:5px;" id="img_contact_category_id"></span> 
                            <span class="error" id="err_contact_category_id"></span>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Pesan</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->textarea("message",array("label"=>false,"div"=>false,"error"=>false,"class"=>"address","value"=>$data['Contact']['message'],"style"=>"height:200px;"))?>
                            <span style="margin-left:5px;" id="img_message"></span> 
                            <span class="error" id="err_message"></span>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4"><strong>*</strong> Publish</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<?php echo $form->input("publish",array('options'=>array("0"=>"Tidak","1"=>"Ya"),'type'=>"radio","legend"=>false,"div"=>false,"separator"=>"&nbsp;&nbsp;&nbsp;","escape"=>false,"default"=>$data['Contact']['publish']) )?>
                            <span style="margin-left:5px;" id="img_publish"></span> 
                            <span class="error" id="err_publish"></span>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                        	<?php if($data["Contact"]["response"]=="0"):?>
                            <div class="text4"><strong>&nbsp;</strong> Response</div>
                            <?php else:?>
                            <div class="text4"><strong>&nbsp;</strong> Last Response</div>
                            <?php endif?>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;" id="response">
							
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            &nbsp;
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
                             <?php echo $form->checkbox("kirim_pesan",array("value"=>1))?>
                             <label for="ContactKirimPesan">Simpan dan Kirim Pesan</label>
                        </div>
                    </div>
                    <?php if($data["Contact"]["response"]=="1"):?>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                        	<div class="text4"><strong>&nbsp;</strong> Response By</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<div class="text4"><?php echo $data["Contact"]["response_by"]?></div>
                        </div>
                	</div>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                        	<div class="text4"><strong>&nbsp;</strong> Response Date</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<div class="text4"><?php echo $time->timeAgoInWords($data["Contact"]["response_date"])?></div>
                        </div>
                	</div>
                    <?php endif?>
                    <div class="line3">
                        <div class="left" style="width:15%;">
                        	<div class="text4"><strong>&nbsp;</strong> Tgl Input</div>
                        </div>
                        <div class="right" style="width:84%;border:0px solid black;">
							<div class="text4"><?php echo $time->timeAgoInWords($data["Contact"]["created"])?></div>
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
        <?php else:?>
        <div class="line1">
            <div class="alert">
                <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
                Maaf detail pesan yang anda cari tidak ditemukan
            </div>
        </div>
        <?php endif;?>
	</div>
</div>