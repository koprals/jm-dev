<?php if(!empty($error)):?>
<span class="error"><?php echo $error;?></span>
<?php else:?>
<script>
function SelectAll()
{
	var checked	=	$("#select_all");
	
	if(checked.is(':checked')==true)
	{
		
		$(":checkbox").attr({ checked: true});
	}
	else
	{
		$(":checkbox").attr({ checked: false});
	}
}
function SendInviterInviterForm()
{
	$("#SendInviterInviterForm").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Users/SendInviter",
		type		: "POST",
		dataType	: "html",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#LoadingInviter").show(300);
			
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert('Maaf kami tidak dapat terhubung dengan provider anda, silahkan coba beberapa saat lagi.');
			
			$("#LoadingInviter").hide(300);
		},
		success		: function(data)
		{
			$("#LoadingInviter").hide(300);
			$("#output").html(data);
		}
	});
	return false;
}
</script>
<?php $count=0;?>
<?php echo $form->create('SendInviter')?>
<div id="output"></div>
<div class="line1" style="border-top:2px dotted #775800; width:96%">&nbsp;</div>
<div class="line1" style="margin-bottom:12px;">
    <div class="left" style="border:0px solid black; width:25%; margin-right:15px; text-align:right; padding-top:5px;">
        <span><strong>*</strong> Messages</span>
    </div>
    <div class="left" style="border:0px solid black; width:50%">
         <?php echo $form->textarea("messages",array("name"=>"messages","label"=>false,"div"=>false,"error"=>false,"class"=>"address"))?>
         <span style="margin-left:5px;" id="img_address"></span>
         <span class="error" id="err_address" ></span>
    </div>
</div>
<div class="line1">
	<div id="scrollbar1" style="border:0px solid black;">
		<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
		<div class="viewport" style="border:0px solid black;">
			 <div class="overview">
             <?php echo $form->input("step",array("name"=>"step","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","style"=>"width:110px",'value'=>'send_invites'))?>
             	<input type="text" name="oi_session_id" value="<?php echo $oi_session_id?>">
                <input type="text" name="provider_box" value="<?php echo $provider_box?>">
                
                <input type="hdden" value="" name="check"/>
                
				<table width="550" border="1" cellspacing="0" cellpadding="0" bordercolor="#cccccc" style="border-collapse:collapse;">
                    
                    <tr style="background-color:#cde99f; height:30px;">
                        <td width="5%" >
                        	<input type="checkbox" value="" onclick="SelectAll()" id="select_all"/>
                        </td>
                        <td width="35%" class="text9">Invite ?</td>
                        <?php if($plugType=="email"):?>
                        <td width="45%" class="text9">Contact</td>
                        <?php endif;?>
                    </tr>
                    <?php foreach($contacts as $email=>$name):?>
                    <?php 
						$count++;
						$back	=	($count%2==0) ? "#FEF7E4" : "#ffffff";
					?>
                    <tr style="background-color:<?php echo $back?>; height:30px;">
                        <td>
                        	<input type="checkbox" value="<?php echo $name?>" name="check[<?php echo $email?>]"/>
                        </td>
                        <td class="font5"><?php echo $name?></td>
                        <?php if($plugType=="email"):?>
                        <td class="font5"><?php echo $email?></td>
                        <?php endif;?>
                    </tr>
                    
                    <?php endforeach;?>
                </table>               
			</div>
		</div>
	</div>
</div>
<?php echo $form->end();?>
<div class="line1" style="margin:20px 0 50px 0;">
    <div class="left" style="text-align:right; width:50%; border:0px solid black;">
        <a href="javascript:void(0)" onclick="SendInviterInviterForm()"><img src="<?php echo $this->webroot?>img/kirim.jpg" border="0"/></a>
    </div>
    <img src="<?php echo $this->webroot?>img/loading19.gif" id="LoadingInviter" style="float:left; display:none"/>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#scrollbar1').tinyscrollbar();	
	});
</script>
<?php endif;?>