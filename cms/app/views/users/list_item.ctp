<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("phpjs.js")?>

<?php
$order		=	array_keys($this->params['paging']['User']['options']['order']);
$direction	=	$this->params['paging']['User']['options']['order'];
$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction[$order[0]] : "";
?>
<script>
var total_record = <?php echo $paginator->counter(array('format' => __('%current%', true)))?>;
var total_all_record = <?php echo $paginator->counter(array('format' => __('%count%', true)))?>;
var total_spread = total_all_record - total_record;
var cookie	=	new Array();

$(document).ready(function(){
	if($.cookie('action'))
	{
		$("#select").val($.cookie('action'));
		
	}
	if($.cookie('checklist'))
	{
		var chkcookie	=	$.cookie('checklist').split(",");
		$("input[type='checkbox']").each(function(key){
			if(in_array($(this).val(),chkcookie))
			{
				$(this).attr("checked","checked");
				$("#tr_"+$(this).val()).removeClass();
				$("#tr_"+$(this).val()).addClass('trCpanelChecked');
			}
		});
	}
	
	$("#select_all").click(function(){
		select_all();
	});
	$("#unselect_all").click(function(){
		unselect_all();
	});
	$("#select_visible").click(function(){
		select_visible();
	});
	$("#unselect_visible").click(function(){
		unselect_visible();
	});
	
	counter();
	
	$("#updown").bind("click",function(){
		$("#advance_search").toggle(400);
		$(this).toggleClass('updown_up');
	});
	
	if($.cookie('expand')==0)
	{
		$('td[rel^=col_hide]').hide();
		$("#table").removeClass();
		$("#table").addClass('table_off');
		$("#expand").removeClass();
		$("#expand").addClass('expand');
	}
	else
	{
		$('td[rel^=col_hide]').show();
		$("#table").removeClass();
		$("#table").addClass('table_on');
		$("#expand").removeClass();
		$("#expand").addClass('contract');
	}
	
	$("a[rel^=sideleft]").each(function(){
		var arr	=	new Array("Active","Block(Soft Block)","Suspend(Hard Block)","Waiting Email Confirm");								
		var a	=	$(this);
		if(in_array(a.html(),arr))
		{
			$.getJSON('<?php echo $settings['cms_url']?>Users/GetSumData',{'type':a.html()},function(data){
				a.html(a.html() + ' (' +data+')');
			});
		}
	});
});
function select_all(){
	$.cookie('checklist',null);	
	$(":checkbox").attr({ checked: true});
	$("tr[id^='tr_']").each(function(){
		var checked			=	$(this).find("input[type='checkbox']");
		
		if(checked.length==1)
		{
			$(this).removeClass();
			$(this).addClass('trCpanelChecked');
		}
	});
	$.getJSON('<?php echo $settings['cms_url']?>Users/SelectAll/<?php echo $liststatus?>',function(data){
		$.each(data, function(i, item){
			cookie[i]	=	item.User.id;
		});
		$.cookie('checklist', cookie, { expires: 1});
		counter();
	});	
}
function unselect_all()
{
	$(":checkbox").attr({ checked: false});
	$("tr[id^='tr_']").each(function(){
		$(this).removeClass();
	});
	cookie	=	new Array();
	$.cookie('checklist',null);
	counter();
}
function select_visible()
{
	$.cookie('checklist',null);
	cookie	=	new Array();
	$(":checkbox").attr({ checked: true});
	$("tr[id^='tr_']").each(function(i){
		$(this).removeClass();
		$(this).addClass('trCpanelChecked');
		var ID		=	$(this).attr('id').split('_');
		cookie[i]	=	ID[1];
	});
	$.cookie('checklist', cookie, { expires: 1});
	counter();
}
function unselect_visible()
{
	$(":checkbox").attr({ checked: false});
	if($.cookie('checklist'))
	{
		var chkcookie	=	$.cookie('checklist').split(",");
	}
	
	$("tr[id^='tr_']").each(function(i){
		$(this).removeClass();
		var ID		=	$(this).attr('id').split('_');
		if($.cookie('checklist'))
		{
			$.each(chkcookie,function(key,val){
				if(val==ID[1])
				{
					chkcookie.splice(key,1);
				}
			});
		}
	});
	$.cookie('checklist',chkcookie);
	counter();
}
function counter(){
	if($.cookie('checklist'))
	{
		var chkcookie	=	$.cookie('checklist').split(",");
		$("#total_selected").html(chkcookie.length);
	}
	else
	{
		$("#total_selected").html("0");
	}
}
function OnMouseOverTr(ID)
{
	$("tr[id^='tr_']").each(function(){
		if($(this).attr('class')!=='trCpanelChecked')
		{
			$(this).removeClass();
			$(this).find("td").css({"color":"#000000"});
		}
	});
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
	if($("#tr_"+ID).attr('class')!=='trCpanelChecked')
	{
		if(checked.length==1)
		{
			$("#tr_"+ID).removeClass();
			$("#tr_"+ID).addClass('trCpanelHover');
			$("#tr_"+ID).find("td").css({"color":"#000000"});
		}
	}
}
function OnClickTr(ID)
{
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
	var item_selected	=	$(":checkbox").filter(':checked').length;
	if(checked.length==1)
	{
		if(checked.is(':checked')==true)
		{
			checked.attr("checked","");
			$("#tr_"+ID).removeClass();
			
			if($.cookie('checklist'))
			{
				var chkcookie	=	$.cookie('checklist').split(",");
				$.each(chkcookie,function(key,val){
					if(val==ID)
					{
						chkcookie.splice (key,1);
					}
				});
				$.cookie('checklist',chkcookie);
			}
		}
		else
		{
			checked.attr("checked","checked");
			$("#tr_"+ID).removeClass();
			$("#tr_"+ID).addClass('trCpanelChecked');
			if($.cookie('checklist'))
			{
				var chkcookie	=	$.cookie('checklist').split(",");
				chkcookie[chkcookie.length]	=	ID;
				$.cookie('checklist',chkcookie);
			}
			else
			{
				$.cookie('checklist',ID);
			}
		}
		counter();
	}
	return true;
}

function OnClickTr2(ID)
{
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
	var item_selected	=	$(":checkbox").filter(':checked').length;
	
	if(checked.length==1)
	{
		if(checked.is(':checked')==false)
		{
			$("#tr_"+ID).removeClass();
			if($.cookie('checklist'))
			{
				var chkcookie	=	$.cookie('checklist').split(",");
				$.each(chkcookie,function(key,val){
					if(val==ID)
					{
						chkcookie.splice (key,1);
					}
				});
				$.cookie('checklist',chkcookie);
			}
		}
		else
		{
			$("#tr_"+ID).removeClass();
			$("#tr_"+ID).addClass('trCpanelChecked');
			if($.cookie('checklist'))
			{
				var chkcookie	=	$.cookie('checklist').split(",");
				chkcookie[chkcookie.length]	=	ID;
				$.cookie('checklist',chkcookie);
			}
			else
			{
				$.cookie('checklist',ID);
			}
		}
		counter();
	}
	return true;
}
function OnMouseOutTable()
{
	$("tr[id^='tr_']").each(function(){
		if($(this).attr('class')!=='trCpanelChecked')
		{
			$(this).removeClass();
			$(this).find("td").css({"color":"#000000"});
		}
	});
}
function DeleteItem(ID)
{
	OnClickTr(ID);
	DeleteMenu('Delete','delete');
}

function DeleteMenu(type,msg)
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	var confirm_delete 	=	confirm('Do you realy want to '+msg+' selected items ?');
	if(confirm_delete==true)
	{
		$("#SearchListItemForm").ajaxSubmit({
			url:'<?php echo $settings['cms_url']?>Users/'+type,
			type:'POST',
			dataType: "json",
			clearForm:false,
			beforeSend:function()
			{
				$("#loading_gede").css({left:(leftpos+350),top:(toppos+100)});
				$("#loading_gede").show();
				$("#list_item").css("opacity","0.5");
			},
			complete:function(data,html)
			{
				$("#loading_gede").hide();
				$("#list_item").css("opacity","1");
			},
			error:function(XMLHttpRequest, textStatus,errorThrown)
			{
				alert("Maaf terjadi kesalahan dalam proses delete data.");
			},
			success:function(data)
			{
				$("#output").html(data);
				var tr	=	data.tr_id;
				
				for(var i=0;i<tr.length;i++)
				{
					$("#tr_"+tr[i]).remove();
				}
				$("#list_item").load("<?php echo $settings['cms_url']?>Users/ListItem/<?php echo $liststatus?>/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
				alert(data.messages);
			}
		});
	}
	return false;
}

function CheckApprove(ID)
{
	unselect_all();
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
	checked.attr("checked","checked");
	$("#tr_"+ID).removeClass();
	$("#tr_"+ID).addClass('trCpanelChecked');
	
	if(!$.cookie('checklist'))
	{
		$.cookie('checklist',ID);
	}
	else
	{
		var chkcookie	=	$.cookie('checklist').split(",");
		if(!in_array(ID,chkcookie))
		{
			chkcookie[chkcookie.length]	=	ID;
			$.cookie('checklist',chkcookie);
		}
	}
	counter();
	ChangeDataConfirmation("all",'disetujui','menyetujui','Approve');
}

function Action(val)
{
	var len	=	$(':checkbox:checked').length;
	
	if(len==0)
	{
		Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Silahkan pilih user.</span></div>",function(){},{title:'Pilih item.'});
	}
	else
	{
		$("select#select_action option:selected").each(function(){
			if($(this).val()=="-1")
			{
				$("#MsgEditing").val('');
				$.prettyPhoto.open('<?php echo $settings['cms_url']?>Users/MessageBlocked/?iframe=true&width=490&height=420');
			}
			else if($(this).val()=="1")
			{
				ChangeDataConfirmation("all",'disetujui','menyetujui','Approve');
			}
			else if($(this).val()=="0")
			{
				ChangeDataConfirmation("all",'dikirim kembali email aktivasinya','mengirimkan kembali email aktivasi','Waiting');	
			}
			else if($(this).val()=="-2")
			{
				$("#MsgEditing").val('');
				$.prettyPhoto.open('<?php echo $settings['cms_url']?>Users/MessageSuspend/?iframe=true&width=490&height=420');
			}
			else if($(this).val()=="-10")
			{
				Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Tahan...</span></div>",function(){},{title:'Pilih action anda.'});
			}
			else
			{
				Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Pilih action yang anda akan lakukan!</span></div>",function(){},{title:'Pilih action anda.'});
			}
		});
	}
}

function Select(value)
{
	$.cookie('action',value);
	switch (value)
	{
		case "1" :
			select_all();
			break;
		case "2" :
			unselect_all();
			break;
		case "3" :
			select_visible();
			break;
		case "4" :
			unselect_visible();
			break;
		default :
			unselect_all();
			break;
	}
}

function BlockDanKirim(value)
{
	$("#MsgEditing").val(value);
	ChangeDataConfirmation("all",'didelete','memblokir','Block');
	$.prettyPhoto.close();
}

function BlockTanpaKirim()
{
	$("#MsgEditing").val('');
	ChangeDataConfirmation("all",'didelete','memblokir','Block');
	$.prettyPhoto.close();
}

function MultiBlockDanKirim(value)
{
	$("#MsgEditing").val(value);
	ChangeDataConfirmation("all",'didelete','memblokir','BlockMulti');
	$.prettyPhoto.close();
}

function MultiBlockTanpaKirim()
{
	$("#MsgEditing").val('');
	ChangeDataConfirmation("all",'didelete','memblokir','BlockMulti');
	$.prettyPhoto.close();
}



function SuspendDanKirim(value)
{
	$("#MsgEditing").val(value);
	ChangeDataConfirmation("all",'didelete','memblokir','Suspend');
	$.prettyPhoto.close();
}

function SuspendTanpaKirim()
{
	$("#MsgEditing").val('');
	ChangeDataConfirmation("all",'didelete','memblokir','Suspend');
	$.prettyPhoto.close();
}

function MultiSuspendDanKirim(value)
{
	$("#MsgEditing").val(value);
	ChangeDataConfirmation("all",'didelete','memblokir','SuspendMulti');
	$.prettyPhoto.close();
}

function MultiSuspendTanpaKirim()
{
	$("#MsgEditing").val('');
	ChangeDataConfirmation("all",'didelete','memblokir','SuspendMulti');
	$.prettyPhoto.close();
}

function ChangeItem(type,action)
{
	var pos						=	$("#list_item").offset();
	var leftpos					=	pos.left;
	var toppos					=	pos.left;
	var selected_items			=	type;
	var msg_editing_required	=	$("#MsgEditing").val();
	var notice				 	=	$("#Notice").val();
	
	$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
	
	if(type=="all")
	{
		selected_items			=	$("#selected_items").val();
	}

	$.ajax({
		type		:	"POST",
		url			:	"<?php echo $settings['cms_url']?>Users/"+action,
		dataType	:	"json",
		data		:	{
			"selected_items":selected_items,
			"msg_editing_required":msg_editing_required,
			"notice":notice
		},
		success		:	function(data){
		$("#list_item").css("opacity","1");
		$("#loading_gede").hide();
		
		//$("#output").html(data);
		Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/check.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>"+data.messages+"</span></div>",function(){
		var tr	=	data.tr_id;
		if($.cookie('checklist'))
		{
			var chkcookie	=	$.cookie('checklist').split(",");
		}
		
		for(var i=0;i<tr.length;i++)
		{
			$("#tr_"+tr[i]).remove();
			if($.cookie('checklist'))
			{
				$.each(chkcookie,function(key,val){
					if(val==tr[i])
					{
						chkcookie.splice(key,1);
					}
				});
			}
		}
		
		$.cookie('checklist',chkcookie);
		$("#list_item").load("<?php echo $settings['cms_url']?>Users/ListItem/<?php echo $liststatus?>/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		},
		{title:'Update data'});
		}
	});
}

function TogleTable()
{
	$('td[rel^=col_hide]').toggle();
	if($.cookie('expand')==0)
	{
		$.cookie('expand',1);
		$("#table").removeClass();
		$("#table").addClass('table_on');
		$("#expand").removeClass();
		$("#expand").addClass('contract');
		$("#detail").hide();
	}
	else
	{
		$("#table").removeClass();
		$("#table").addClass('table_off');
		$("#expand").removeClass();
		$("#expand").addClass('expand');
		$.cookie('expand',0);
		
	}
}

</script>
<style>
	.trCpanelHover
	{
		background-color:#cde99f;
		color: #000000;
	}
	.trCpanelChecked
	{
		background-color:#cde99f;
		color: #000000;
	}
	.table_off
	{
		width:100%;
	}
	.table_on
	{
		width:1600px;
	}
</style>
<?php echo $paginator->options(array(
	'url'	=> array(
		'controller'	=> 'Users',
		'action'		=> 'ListItem',
		$liststatus
	),
	'onclick'=>"return onClickPage(this,'#list_item');")
);?>
<div id="output"></div>
<input type="hidden" id="selected_items" value="" name="data[User][selected_items]">
<div id="test_select_all">
</div>
<div class="line3">
    <a href="<?php echo $settings['cms_url'] ?>Users/Add/" class="nav_6" style="float:right;"><img src="<?php echo $this->webroot?>img/icn_add.gif" border="0" style="float:left; margin-right:3px;" />Add User</a>
</div>
<div class="line1">
    <div class="left" style="width:60%;margin-top:8px;">
        <div class="text7" style="width:100%; float:left">
            <span style="float:left;">page</span>
            <?php if($paginator->hasPrev()):?>
                <a href="<?php echo $settings['cms_url']?>Users/ListItem/<?php echo $liststatus?>/page:<?php echo ($page-1)?>/limit:<?php echo $viewpage.$ordered?>" class="nav_table_left" onclick="return onClickPage(this,'#list_item')"></a>
                
            <?php endif;?>
            <input type="text" name="textfield" id="page" class="all_input2" style="width:30px; float:left; height:16px; margin-top:-5px;" value="<?php echo $page;?>" />
            <?php if($paginator->hasNext()):?>
                <a href="<?php echo $settings['cms_url']?>Users/ListItem/<?php echo $liststatus?>/page:<?php echo ($page+1)?>/limit:<?php echo $viewpage.$ordered?>" class="nav_table_right" onclick="return onClickPage(this,'#list_item')"></a>
            <?php endif;?>
            <span style="float:left;"><?php echo $paginator->counter(array('format' => 'of %pages% pages'));?> | View </span>
            
            <?PHP echo $form->select("view",array(1=>1,5=>5,10=>10,20=>20,100=>100,200=>200,1000=>1000),$viewpage,array("class"=>"text7","style"=>"width:auto; height:22px; float:left; margin:-5px 3px 0 3px; border:1px solid #c8c8c8; cursor:pointer;","onchange"=>"onClickPage('".$settings['cms_url']."Users/ListItem/{$liststatus}/limit:'+this.value+'".$ordered."','#list_item')","empty"=>false))?>
            
            
            <span style="float:left;">per page | <?php echo $paginator->counter(array('format' => 'Total %count% records found'));?></span>
        </div>
    </div>
    <div class="right" style="border:0px solid black; width:100px; padding-top:5px;">
   		<a href="javascript:void(0)" onclick="TogleTable()" class="expand" id="expand">expand table</a>
    </div>
</div>

<!-- Table -->
<div class="line1">
  <div class="table1">
        <div class="line2">
            <div class="left">
                <a href="javascript:void(0)" class="nav_5" id="select_all">Select All</a><span style="float:left;">|</span>
                <a href="javascript:void(0)" class="nav_5" id="unselect_all">Unselect All</a><span style="float:left;">|</span>
                <a href="javascript:void(0)" class="nav_5" id="select_visible">Select Visible</a><span style="float:left;">|</span>
                <a href="javascript:void(0)" class="nav_5" id="unselect_visible">Unselect Visible</a><span style="float:left;">|</span>
                <span style="float:left; margin:2px 0 0 3px;"><strong id="total_selected">0</strong> items selected </span>
            </div>
            <?php if($liststatus!="all"):?>
            <div class="right" style="margin:-2px 0 0 0;">                                    	
                <input type="button" name="button" id="button" value="" class="submit" onclick="Action()"/>
                <?php echo $form->select("userstatus_id",$userstatus_id,false,array("name"=>"select","class"=>"sel1","label"=>"false","escape"=>false,"empty"=>"-","id"=>"select_action","style"=>"width:50%; height:20px; float:right;"));?>  
                <span style="float:right; margin:2px 3px 0 0;">Actions</span>                                  
            </div>
            <?php endif;?>
        </div>
    </div>
    
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;" onmouseout="OnMouseOutTable()" id="table" class="table_off">
        <tr class="table2" height="30">
        	<?php if($liststatus!="all" and in_array($liststatus,array(1,-2,0))):?>
            <td style="border:1px solid #d1cfcf;width:110px;">
            	<select name="select" size="1" id="select" class="text5" style="height:22px; padding:1px 0; margin:0 3px; border:1px solid #c8c8c8; cursor:pointer; width:100px;" onchange="Select(this.value)">
                    <option selected="">Any</option>
                    <option value="1">Select All</option>
                    <option value="2">Unselect All</option>
                    <option value="3">Select Visible</option>
                    <option value="4">Unselect Visible</option>
                </select>
            </td>
            <?php endif;?>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:50px;"><?php echo $paginator->sort('ID', 'User.id',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Fullname', 'Profile.fullname',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Email', 'User.email',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            
            <!-- COLLS HIDE --->
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px; display:none" rel="col_hide"><?php echo $paginator->sort('Gender', 'Profile.Gender',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px; display:none" rel="col_hide"><?php echo $paginator->sort('Address', 'Profile.address',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px; display:none" rel="col_hide"><?php echo $paginator->sort('Propinsi', 'Province.province',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px; display:none" rel="col_hide"><?php echo $paginator->sort('Kota', 'Province.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px; display:none" rel="col_hide"><?php echo $paginator->sort('Telp', 'Profile.phone',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px; display:none" rel="col_hide"><?php echo $paginator->sort('Fax', 'Profile.fax',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px; display:none" rel="col_hide"><?php echo $paginator->sort('Point', 'User.points',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <!-- COLLS HIDE --->
            
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Type', 'Usertype.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Created', 'User.created',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Status', 'Userstatus.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;">Action</td>
        </tr>
        
        <tr class="table3" style="border:1px solid #D3C8AA;">
        	<?php if($liststatus!="all" and in_array($liststatus,array(1,-2,0))):?>
            <td style="border:1px solid #bdbdbd;">&nbsp;
                
            </td>
            <?php endif;?>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            
            <!-- COLLS HIDE --->
            <td style="border:1px solid #D3C8AA; display:none" rel="col_hide">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA; display:none" rel="col_hide">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA; display:none" rel="col_hide">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA; display:none" rel="col_hide">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA; display:none" rel="col_hide">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA; display:none" rel="col_hide">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA; display:none" rel="col_hide">&nbsp;
                
            </td>
            <!-- COLLS HIDE --->
            
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
            	
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
            	
            </td>
        </tr>
        
        <?php if(count($data)>0):?>
        <?php $count=0;?>
        <?php foreach($data as $data):?>
        <?php $count++?>
        <?php $back	=	($count%2==0) ? "#f6f6f6" : "#FFFFFF";?>
        
        <tr bgcolor="<?php echo $back?>" onMouseOver="OnMouseOverTr('<?php echo $data['User']['id']?>')" id="tr_<?php echo $data['User']['id']?>">
            <?php if($liststatus!="all" and in_array($liststatus,array(1,-2,0))):?>
            <td style="border:1px solid #D3C8AA;">
                <input name="data[User][id][]" type="checkbox" value="<?php echo $data['User']['id']?>" style="margin:5px auto; display:block;"  onClick="OnClickTr2('<?php echo $data['User']['id']?>')" />
            </td>
             <?php endif;?>
            <td class="table_text2" style="border:1px solid #D3C8AA;"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" ><?php echo $data['User']['id']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" ><?php echo $data['Profile']['fullname']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" ><?php echo chunk_split($data['User']['email'],15,"<br />")?></td>
            
            <!-- COLLS HIDE --->
            <td class="table_text3" style="border:1px solid #D3C8AA; display:none"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" rel="col_hide"><?php if(empty($data['Profile']['gender'])){echo "-";}else{echo $data['Profile']['gender'];}?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA; display:none"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" rel="col_hide"><?php if(empty($data['Profile']['address'])){echo "-";}else{echo $data['Profile']['address'];}?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA; display:none"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" rel="col_hide"><?php if(empty($data['Province']['province'])){echo "-";}else{echo $data['Province']['province'];}?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA; display:none"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" rel="col_hide"><?php if(empty($data['Province']['name'])){echo "-";}else{echo $data['Province']['name'];}?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA; display:none"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" rel="col_hide"><?php if(empty($data['Profile']['phone'])){echo "-";}else{echo $data['Profile']['phone'];}?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA; display:none"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" rel="col_hide"><?php if(empty($data['Profile']['fax'])){echo "-";}else{echo $data['Profile']['fax'];}?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA; display:none"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" rel="col_hide"><?php if(empty($data['User']['points'])){echo "0";}else{echo $number->format($data['User']['points'],array("thousands"=>".","before"=>null,"places"=>null,"after"=>" point"));}?></td>
            <!-- COLLS HIDE --->
            
            <td class="table_text3" style="border:1px solid #D3C8AA;"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" ><?php echo $data['Usertype']['name']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" ><?php echo date("d-M-Y H:i:s",strtotime($data['User']['created']))?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"  onClick="OnClickTr('<?php echo $data['User']['id']?>')" ><?php echo $data['Userstatus']['name']?></td>
            <td style="border:1px solid #D3C8AA;">
                <div class="line2">
                    <a href="<?php echo $settings['cms_url']?>Users/Add/<?php echo $data['User']['id']?>" class="nav_6">edit</a>
                    <?php if($data['User']['userstatus_id']==0 or $data['User']['userstatus_id']==-2):?>
                    	<a href="javascript:void(0)" class="nav_6" onclick="CheckApprove('<?php echo $data['User']['id']?>');">approve</a>
                    <?php elseif($data['User']['userstatus_id']==1):?>
                    	<a href="javascript:void(0)" class="nav_6" onclick="CheckSuspend('<?php echo $data['User']['id']?>');">suspend</a>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        <?php endforeach;?>
        <?php else:?>
        <tr height="130">
            <td width="100%" colspan="15">
                <div class="alert">
                    <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
                    Data tidak di temukan
                </div>
            </td>
        </tr>
        <?php endif;?>
    </table>	
</div>                        
<!-- End Table -->
