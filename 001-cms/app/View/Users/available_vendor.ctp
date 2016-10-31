<script>
function OnMouseOverTr(ID)
{
	$("div[rel^='div_vendor_']").each(function(){
		if($(this).attr('aby')!=='active')
		{
			$(this).attr("style","border-bottom:1px solid #cde99f; margin-left:10px;width:96%; padding:5px 0 5px 5px ;cursor:pointer;background-color:#ffffff");
		}
	});
	
	if($("#"+ID).attr('aby')!=='active')
	{
		$("#"+ID).attr("style","border-bottom:1px solid #cde99f; margin-left:10px;width:96%;padding:5px 0 5px 5px ;cursor:pointer;background-color:#cde99f");	
	}
}
function OnMouseOutTable()
{
	$("div[rel^='div_vendor_']").each(function(){
		if($(this).attr('aby')!=='active')
		{
			$(this).attr("style","border-bottom:1px solid #cde99f; margin-left:10px;width:96%; padding:5px 0 5px 5px ;cursor:pointer;background-color:#ffffff");
		}
	});
}

function OnClickTr(ID)
{
	$("div[rel^='div_vendor_']").each(function(){
		$(this).attr('aby','')
		$(this).attr("style","border-bottom:1px solid #cde99f; margin-left:10px;width:96%; padding:5px 0 5px 5px ;cursor:pointer;background-color:#ffffff");
	});
	
		$("#"+ID).attr('aby',"active")
		$("#"+ID).attr("style","border-bottom:1px solid #cde99f; margin-left:10px;width:96%;padding:5px 0 5px 5px ;cursor:pointer;background-color:#cde99f");	
	
}
</script>
<div id="invite" onmouseout="OnMouseOutTable()">
	<?php foreach($data as $data):?>
    <div class="line1" style="border-bottom:1px solid #cde99f; margin-left:10px;width:96%; padding:5px 0 5px 5px;cursor:pointer;" rel="div_vendor_<?php echo $data['AvailableVendor']['name']?>" id="<?php echo $data['AvailableVendor']['name']?>" onmouseover="OnMouseOverTr('<?php echo $data['AvailableVendor']['name']?>')" onclick="OnClickTr('<?php echo $data['AvailableVendor']['name']?>')">
        <div class="line1">
            <div class="left" style="border:0px solid black;width:60%">
                <div class="left" style="width:32px;border:0px solid black;">
                    <img src="<?php echo $this->webroot?><?php echo $data['AvailableVendor']['icon']?>" />
                </div>
                <div class="left" style="width:100px; height:32px;border:0px solid black; margin-left:5px;">
                     <div style="margin-top:5px;" class="text9"><?php echo ucfirst($data['AvailableVendor']['name'])?></div>
                </div>
            </div>
            <div class="right" style="border:0px solid black;width:15%;">
                <span style="margin-top:5px;"><a href="" style=" color:#2971ba;" class="text9">Find friends</a></span>
            </div>
        </div>
        <div id="form_<?php echo $data['AvailableVendor']['name']?>" style="display:none">
            <div class="line1" style="margin-bottom:5px;">
                <div class="left" style="border:0px solid black; width:30%; margin-right:15px; text-align:right; padding-top:5px;">
                    <span>Email: </span>
                </div>
                <div class="left" style="border:0px solid black; width:50%">
                    <?php echo $form->input("fullname",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text"))?>
                    <span style="margin-left:5px" id="img_fullname"></span>
                    <span class="error" id="err_fullname"></span>
                </div>
            </div>
            <div class="line1" style="margin-bottom:5px;">
                <div class="left" style="border:0px solid black; width:30%; margin-right:15px; text-align:right; padding-top:5px;">
                    <span>Password: </span>
                </div>
                <div class="left" style="border:0px solid black; width:50%">
                    <?php echo $form->input("fullname",array("class"=>"user","div"=>false,"label"=>false,"type"=>"text"))?>
                    <span style="margin-left:5px" id="img_fullname"></span>
                    <span class="error" id="err_fullname"></span>
                </div>
            </div>
            <div class="line1" style="margin-bottom:12px;">
                <div class="left" style="border:0px solid black; width:30%; margin-right:15px; text-align:right; padding-top:5px;">
                    &nbsp;
                </div>
                <div class="left" style="border:0px solid black; width:50%">
                    <input type="submit" name="button" id="button" value="invite" class="btn_sign" onClick="return UserForgotPasswordForm()"/>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>
</div>