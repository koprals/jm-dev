<?php echo $javascript->link("jquery.uitablefilter")?>

<script>
function SelectAll()
{
	var checked	=	$("#select_all");
	if(checked.is(':checked')==true)
	{
		$(":checkbox").attr({checked: true});
	}
	else
	{
		$(":checkbox").attr({checked: false});
	}
}
function SendInviterInviterForm()
{
	$("#SendInviterFriendListsForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Users/SendInviter",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#LoadingPict").show(300);
			$("#error").hide(300);
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert('Maaf kami tidak dapat terhubung dengan provider anda, silahkan coba beberapa saat lagi.');
			
		},
		success		: function(data)
		{
			$("#output").html(data);
			$("#LoadingPict").hide(300);
			if(data.status==false)
			{
				$("#error").find('div[id=error_list]').html('.&nbsp; '+data.error+'<br />');
				$("#error").show(300);
			}
			else
			{
				window.location.href	=	data.error;
			}
		}
	});
	return false;
}

function Check(el)
{
	if($(el).html()=="pilih semua")
	{
		$("tr").each(function(){
			if($(this).is(':visible'))
			{
				$(this).find('input[type=checkbox]').attr('checked','checked');
			}
		});
		$(el).html('tidak pilih semua');
	}
	else
	{
		$("tr").each(function(){
			if($(this).is(':visible'))
			{
				$(this).find('input[type=checkbox]').removeAttr('checked');
			}
		});
		$(el).html('pilih semua');
	}
}
</script>
<div id="output"></div>

<div class="size100 tengah" style="border:0px solid black;">
    <?php if(!empty($error)):?>
    <div class="text_title3">
        <div class="line1">Error.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto; border:0px solid black;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	<?php echo $error;?>
            <div class="line top10">
                <input type="button" name="button" value="&laquo; Back" class="tombol1" onClick="window.history.back();" style="float:left"/>
                <input type="button" name="button" value="Try Again &raquo;" class="tombol1 left10" onClick="window.location.reload();" style="float:left"/>
            </div>
        </div> 
    </div>
    <?php else:?>
    <?php echo $form->create('SendInviter')?>
    <?php echo $form->input("step",array("name"=>"step","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:110px",'value'=>'send_invites'))?>
    <input type="hidden" name="username" value="<?php echo $username?>">
    <input type="hidden" name="oi_session_id" value="<?php echo $oi_session_id?>">
    <input type="hidden" name="provider_box" value="<?php echo $provider_box?>">
    <div class="text_title3">
        <div class="line1">Undang Teman.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="tengah size60">
        	<div class="kiri size100 style1 white text12 top10 bold reounded_error" style="display:none" id="error">
                <div class="kiri size100 left10 top10">Error</div>
                <div class="kiri size100 left10 top5 bottom10" id="error_list">
                </div>
            </div>
        	<div class="top10 kiri size100">
            	<div class="kiri size25 white style1 text13 bold" style="padding-top:5px;">Cari Teman:</div>
                <div class="kiri size40 left5">
                	<input type="text" class="kiri input2 style1 white text12 size90" style="height:15px;" onkeyup="$.uiTableFilter($('#table'), this.value )"/>
                </div>
                <div class="kiri size35 white style1 text13 bold" style="padding-top:5px;"> / <a href="javascript:void(0);" class="white style1 text13 bold normal" onclick="Check(this)">pilih semua</a></div>
            </div>
            
        	<div class="reounded_friend top10 kiri size100">
                <table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" id="table">
                    <?php foreach($contacts as $email=>$name):?>
                    <tr style="width:100%;border-bottom:1px solid #ffffff;padding:4px 0;display:block;" rel="1">
                        <td width="25" height="30">
                            <input type="checkbox" value="<?php echo $name?>" style="float:left;" name="check[<?php echo $email?>]"/>
                         </td>
                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;">
                            <?php echo $name?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
            <div class="top20 kiri size100 style1 text13 bold white">
            	Pesan
            </div>
            <div class="kiri size100 style1 text13 bold white">
            	<?php echo $form->textarea("messages",array("name"=>"messages","label"=>false,"div"=>false,"error"=>false,"class"=>"input2 size100 style1 white","rows"=>4))?>
            </div>
            <div class="line top20">
                <input type="button" name="button" value="&laquo; Back" class="tombol1" onClick="window.history.back();" style="float:left"/>
                <input type="button" name="button" value="Invite &raquo;" class="tombol1 left10" onClick="return SendInviterInviterForm()" style="float:left"/>
                <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingPict" style="float:left; display:none; margin:5px 0 0 5px;"/>
            </div>
        </div>
    </div>
    <?php echo $form->end();?>
    <?php endif;?>
    <div class="line">&nbsp;</div>
</div>