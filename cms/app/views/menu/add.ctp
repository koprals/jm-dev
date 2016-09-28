<script>
function SubmitAdd()
{
	$("span[id^=err_]").html('');
	$("#CmsMenuAddForm").ajaxSubmit({
		url:'<?php echo $settings['cms_url']?>Menu/ProcessAddMenus',
		type:'POST',
		dataType: "json",
		clearForm:false,
		
		beforeSend:function()
		{
			$("#loading").show();
		},
		complete:function(data,html)
		{
			$("#loading").hide();
		},
		error:function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert(textStatus);
		},
		success:function(data)
		{
			$("#output").html(data);
			var err	=	data.error;
			
			if(data.status==true)
			{
				alert(data.error);
				window.location.reload();
			}
			else
			{
				for(var i=0;i<err.length;i++)
				{
					$("#err_"+err[i].key).html(err[i].value);
				}
			}
		}
	});
	return false
}
</script>
<div id="output"></div>
<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code))?>
<div class="test-right">
	<div class="content">
    	<?php echo $form->create('CmsMenu')?>
        <?php echo $form->input("id",array("type"=>"hidden","value"=>$id,"readonly"=>"readonly"))?>
        <div class="line1">
            <div class="line3">
                <div class="left" style="width:100%;">
                    <div class="line3">
                        <div class="left" style="width:15%;">
                            <div class="text4">Parent Menu</div>
                        </div>
                        <div class="right" style="width:84%;">
                            <select name="data[CmsMenu][parent_id]" size="1" id="CategoryId" class="sel1">
                                <?php foreach($data as $key=>$value):?>
                                    <?php if($key==$cat_id):?>
                                        <option value="<?php echo $key?>" selected="selected" label="<?php echo str_replace("&nbsp;","",$value)?>">&nbsp;&nbsp;<?php echo $value?></option>
                                    <?php else:?>
                                        <option value="<?php echo $key?>" label="<?php echo str_replace("&nbsp;","",$value)?>">&nbsp;&nbsp;<?php echo $value?></option>
                                    <?php endif;?>
                                <?php endforeach;?>                        
                            </select>
                        </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Sub Menu Name</div>
                        </div>
                        <div class="right" style="width:84%;">
                            <input type="text" name="data[CmsMenu][name]" class="all_input3" style="width:48.8%; float:left; height:16px;" maxlength="100" value="<?php echo $cat_name?>"/>
                            <span style="float:left;color:#F00000; display:block;width:100%; text-decoration:blink; height:10px" id="err_name"></span>
                            <?php if(!empty($id)):?>
                            <a href="<?php echo $settings['cms_url']?>Menu/Add/<?php echo $id?>/up" class="nav_6" style="text-decoration:none; margin-left:10px">Move Up <img src="<?php echo $this->webroot?>img/up.png" style="border:0px"/></a>
                            <a href="<?php echo $settings['cms_url']?>Menu/Add/<?php echo $id?>/down" class="nav_6" style="text-decoration:none">Move Down  <img src="<?php echo $this->webroot?>img/down.png" style="border:0px"/></a>
                        	<?php endif;?>
                         </div>
                    </div>
                    <div class="line3" style="border:0px solid black;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Url</div>
                        </div>
                        <div class="right" style="width:84%;">
                            <div class="left" style="width:49%; margin-right:20px;">
                                <input type="text" name="data[CmsMenu][url]" class="all_input3" style="width:100%; float:left; height:16px;" maxlength="100" value="<?php echo $url?>"/>
                                <span style="float:left;color:#F00000; display:block;width:100%; text-decoration:blink; height:10px" id="err_url"></span>
                            </div>
                            <div class="left" style="border:0px solid black;width:35%;">
                                <span class="text5">notes: (Cukup masukkan nama Controler slash nama Action) co:Menu/Add</span>
                            </div>
                       </div>
                    </div>
                    <div class="line3" style="margin-top:10px;">
                        <div class="left" style="width:15%;">
                            <div class="text4">Code</div>
                        </div>
                        <div class="right" style="width:84%;">
                            <input type="text" name="data[CmsMenu][code]" class="all_input3" style="width:48.8%; float:left; height:16px;" maxlength="100" value="<?php echo $code?>"/>
                            <span style="float:left;color:#F00000; display:block;width:100%; text-decoration:blink; height:10px" id="err_code"></span>
                         </div>
                    </div>
                    <div class="line1" style="margin-top:15px;">
                        <input type="button" name="button" id="button" value="" class="save" onClick="return SubmitAdd()"/>
                        <img src="<?php echo $this->webroot?>img/loading19.gif" style="float:left; margin:5px 0 0 5px;display:none" id="loading">
                    </div>
                </div>
            </div>
        </div>
        <?php echo $form->end();?>
    </div>
</div>