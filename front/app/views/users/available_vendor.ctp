<script>
function OnMouseOverTr(ID)
{
	$("div[rel^='div_vendor_']").each(function(){
		if($(this).attr('aby')!=='active')
		{
			$(this).attr("style","border-bottom:1px solid #ffffff; padding:5px 0 5px 5px;cursor:pointer; background-color:#888888");
		}
	});
	
	if($("#"+ID).attr('aby')!=='active')
	{
		$("#"+ID).attr("style","border-bottom:1px solid #ffffff; padding:5px 0 5px 5px;cursor:pointer;background-color:#595959");	
	}
}
function OnMouseOutTable()
{
	$("div[rel^='div_vendor_']").each(function(){
		if($(this).attr('aby')!=='active')
		{
			$(this).attr("style","border-bottom:1px solid #ffffff; padding:5px 0 5px 5px;cursor:pointer; background-color:#888888");
		}
	});
}

function OnClickTr(ID)
{
	$("div[rel^='div_vendor_']").each(function(){
		
		if($(this).attr('id')!==ID)
		{
			$(this).attr('aby','')
			$(this).attr("style","border-bottom:1px solid #ffffff; padding:5px 0 5px 5px;cursor:pointer; background-color:#888888");
			$(this).find('div[id^=form_]').hide(300);
			
		}
	});
	
	$("#"+ID).attr('aby',"active")
	$("#"+ID).attr("style","border-bottom:1px solid #ffffff; padding:5px 0 5px 5px;cursor:pointer;background-color:#595959");	
	$('#form_'+ID).show(300);
}
</script>

<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3 top-10">
        <div class="line1">Undang teman anda untuk ikut bergabung ?.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;" onmouseout="OnMouseOutTable()">
    	<?php foreach($data as $data):?>
        	<?php $email	=	($data['AvailableVendor']['type']=="email") ? "Email" : "Username";?>
        	<div class="kiri size95 left10" style="border-bottom:1px solid #ffffff; padding:5px 0 5px 5px;cursor:pointer;" rel="div_vendor_<?php echo $data['AvailableVendor']['name']?>" id="<?php echo $data['AvailableVendor']['name']?>" onmouseover="OnMouseOverTr('<?php echo $data['AvailableVendor']['name']?>')" onclick="OnClickTr('<?php echo $data['AvailableVendor']['name']?>')">
            	<div class="line">
                    <div class="kiri size60">
                        <div class="kiri" style="width:32px;border:0px solid black;">
                            <img src="<?php echo $this->webroot?><?php echo $data['AvailableVendor']['icon']?>" />
                        </div>
                        <div class="kiri style1 white text12 bold" style="width:100px; height:32px;border:0px solid black; margin-left:5px;">
                            <div class="top10"><?php echo ucfirst($data['AvailableVendor']['name'])?></div>
                        </div>
                    </div>
                    <div class="kanan size15">
                        <div class="kiri top10">
                            <a href="javascript:void(0);" class="style1 white text11 bold normal">Find friends</a>
                        </div>
                    </div>
                </div>
                <div class="tengah size50" id="form_<?php echo $data['AvailableVendor']['name']?>" style="display:none;">
                	<?php echo $form->create('Inviter',array("name"=>"form_".$data['AvailableVendor']['name'],"url"=>array("controller"=>"Users","action"=>"FriendLists"),"method"=>"POST"))?>
                    <div class="kiri size100">
                        <div class="kiri size25 style1 white text12 bold">
                            <?php echo $email?> :
                        </div>
                        <div class="kiri size75 style1 white text12 bold">
                        	<?php echo $form->input("provider_box",array("name"=>"provider_box","class"=>"user","div"=>false,"label"=>false,"type"=>"hidden","value"=>$data['AvailableVendor']['name'],"readonly"=>"readonly"))?>
                           <?php echo $form->input("email_box",array("name"=>"email_box","div"=>false,"label"=>false,"type"=>"text",'error'=>false,"class"=>"kiri input4 style1 black text12 size70","value"=>" "))?>
                           	
                        </div>
                    </div>
                    <div class="kiri size100 top5">
                        <div class="kiri size25 style1 white text12 bold">
                            Password :
                        </div>
                        <div class="kiri size75 style1 white text12 bold">
                           <?php echo $form->input("password_box",array("name"=>"password_box","div"=>false,"label"=>false,"type"=>"password",'error'=>false,"class"=>"kiri input4 style1 black text12 size70"))?>
                        </div>
                        <input type="submit" name="button" value="Send" class="tombol1"/>
                    </div>
                     <?php echo $form->end();?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="line">&nbsp;</div>
</div>