<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("phpjs.js")?>
<?php
$order		=	array_keys($this->params['paging']['TransactionLog']['options']['order']);
$direction	=	$this->params['paging']['TransactionLog']['options']['order'];
$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction[$order[0]] : "";
?>
<script>
var total_record = <?php echo $paginator->counter(array('format' => __('%current%', true)))?>;
var total_all_record = <?php echo $paginator->counter(array('format' => __('%count%', true)))?>;
var total_spread = total_all_record - total_record;
var cookie	=	new Array();

var fade_in 	= 	500;
var fade_out	= 	500;
if($.browser.msie)
{
	fade_in 	= 100;
	fade_out	= 3500;
}
$(document).ready(function(){
	$('a[rel^=help]').each(function(){
		$(this).bt({
			width: 230,
			trigger: ['hover'],
			positions: ['top'],
			cornerRadius: 7,
			strokeStyle: '#000000',
			fill: 'rgba(255, 255, 255, 1)',
			shadow: true,
			shadowOffsetX: 3,
			shadowOffsetY: 3,
			shadowBlur: 8,
			shadowColor: 'rgba(0,0,0,.9)',
			shadowOverlap: false,
			noShadowOpts: {strokeStyle: '#999', strokeWidth: 2},
			shrinkToFit: true,
			hoverIntentOpts: {
			  interval: 0,
			  timeout: 0
		  }
		});
	});
	
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
	$.getJSON('<?php echo $settings['cms_url']?>Product/SelectAll',function(data){
		$.each(data, function(i, item){
			cookie[i]	=	item.Product.id;
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

function DeleteMenu(type,msg)
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	Boxy.confirm("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Anda yakin akan "+msg+" item ini ? </span></div>", function()																																																																			
																																																																					   {																																																																																																																																					   		$("#selected_items").val($.cookie('checklist'));
			DeleteItem(ID);
	},{title:"Konfirmasi hapus data"});
	
	var confirm_delete 	=	confirm('Do you realy want to '+msg+' selected items ?');
	if(confirm_delete==true)
	{
		$("#SearchListItemForm").ajaxSubmit({
			url:'<?php echo $settings['cms_url']?>Product/'+type,
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
				alert("Maaf terjadi kesalahan dalam proses manipulasi data.");
			},
			success:function(data)
			{
				$("#output").html(data);
				var tr	=	data.tr_id;
				
				for(var i=0;i<tr.length;i++)
				{
					$("#tr_"+tr[i]).remove();
				}
				$("#list_item").load("<?php echo $settings['cms_url']?>Product/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
				alert(data.messages);
			}
		});
	}
	return false;
}

function Action(val)
{
	var len	=	$(':checkbox:checked').length;
	
	if(len==0)
	{
		Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Silahkan pilih item anda</span></div>",function(){},{title:'Pilih item.'});
	}
	else
	{
		$("select#select_action option:selected").each(function(){
			if($(this).val()=="Deleted")
			{
				$("#MsgEditing").val('');
				$.prettyPhoto.open('<?php echo $settings['cms_url']?>Product/MessageDeleted/?iframe=true&width=490&height=420');
			}
			else if($(this).val()=="Approve")
			{
				ChangeDataConfirmation("all",'disetujui','menyetujui','Approve');
			}
			else if($(this).val()=="Editing Required")
			{
				$("#MsgEditing").val('');
				$("#ProductNotice").val('');
				$.prettyPhoto.open('<?php echo $settings['cms_url']?>Product/MessageEditing?iframe=true&width=490&height=520');
			}
			else if($(this).val()=="Waiting Approval")
			{
				ChangeDataConfirmation("all",'dirubah statusnya','merubah status menjadi \'Waiting Approval\'','WaitingApproval');
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

function GetMsgEditing(value,notice)
{
	$("#MsgEditing").val(value);
	$("#ProductNotice").val(notice);
	$.prettyPhoto.close();
	ChangeDataConfirmation("all",'dirubah statusnya','merubah status menjadi \'Editing Required\'','EditingRequired');
}
function HapusDanKirim(value)
{
	$("#MsgEditing").val(value);
	ChangeDataConfirmation("all",'didelete','menghapus','Delete');
	$.prettyPhoto.close();
}

function HapusTanpaKirim()
{
	$("#MsgEditing").val('');
	ChangeDataConfirmation("all",'didelete','menghapus','Delete');
	$.prettyPhoto.close();
}

function MultiHapusDanKirim(value)
{
	$("#MsgEditing").val(value);
	ChangeDataConfirmation("all",'didelete','menghapus','DeleteMulti');
	$.prettyPhoto.close();
}

function MultiHapusTanpaKirim()
{
	$("#MsgEditing").val('');
	ChangeDataConfirmation("all",'didelete','menghapus','DeleteMulti');
	$.prettyPhoto.close();
}

function ChangeItem(type,action)
{
	var pos						=	$("#list_item").offset();
	var leftpos					=	pos.left;
	var toppos					=	pos.left;
	var selected_items			=	type;
	var msg_editing_required	=	$("#MsgEditing").val();
	var notice				 	=	$("#ProductNotice").val();
	
	$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
	
	if(type=="all")
	{
		selected_items			=	$("#selected_items").val();
	}

	$.ajax({
		type		:	"POST",
		url			:	"<?php echo $settings['cms_url']?>Product/"+action,
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
		$("#list_item").load("<?php echo $settings['cms_url']?>Product/ListItem/1/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		},
		{title:'Update data'});
		}
	});
}

function Detail(ID)
{
	var pos						=	$("#list_item").offset();
	var leftpos					=	pos.left;
	var toppos					=	pos.left;	
	$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
	
	$("#detail").load("<?php echo $settings['cms_url']?>Product/Detail/"+ID,function(){
		$("#list_item").css("opacity","1");
		$("#detail").show();
		$(document).scrollTo('#table_detail', 500);
		$("#loading_gede").hide();
		
		//HIDE EXPAND TABLE///
		$('td[rel^=col_hide]').hide();
		$("#table").removeClass();
		$("#table").addClass('table_off');
		$("#expand").removeClass();
		$("#expand").addClass('expand');
		$.cookie('expand',0);
		//HIDE EXPAND TABLE///
		
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
		width:2600px;
	}
</style>
<?php $paginator->options(array(
	'url'	=> array(
		'controller'	=> 'Transaction',
		'action'		=> 'ListTransaction'
	),
	'onclick'=>"return onClickPage(this,'#list_item');")
);?>

<div id="output"></div>
<input type="hidden" id="selected_items" value="" name="data[Transaction][selected_items]">
<div class="line1">
    <div class="left" style="width:60%;margin-top:8px;">
        <div class="text7" style="width:100%; float:left">
            <span style="float:left;">page</span>
            <?php if($paginator->hasPrev()):?>
                <a href="<?php echo $settings['cms_url']?>Transaction/ListTransaction/page:<?php echo ($page-1)?>/limit:<?php echo $viewpage.$ordered?>" class="nav_table_left" onclick="return onClickPage(this,'#list_item')"></a>
                
            <?php endif;?>
            <input type="text" name="textfield" id="page" class="all_input2" style="width:30px; float:left; height:16px; margin-top:-5px;" value="<?php echo $page;?>" />
            <?php if($paginator->hasNext()):?>
                <a href="<?php echo $settings['cms_url']?>Transaction/ListTransaction/page:<?php echo ($page+1)?>/limit:<?php echo $viewpage.$ordered?>" class="nav_table_right" onclick="return onClickPage(this,'#list_item')"></a>
            <?php endif;?>
            <span style="float:left;"><?php echo $paginator->counter(array('format' => 'of %pages% pages'));?> | View </span>
            
            <?PHP echo $form->select("view",array(1=>1,5=>5,10=>10,20=>20,100=>100,200=>200,1000=>1000),$viewpage,array("class"=>"text7","style"=>"width:auto; height:22px; float:left; margin:-5px 3px 0 3px; border:1px solid #c8c8c8; cursor:pointer;","onchange"=>"onClickPage('".$settings['cms_url']."Transaction/ListTransaction/limit:'+this.value+'".$ordered."','#list_item')","empty"=>false))?>
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
            <div class="left" style="border:0px solid black; width:58%; border:0px solid black;">
                <a href="javascript:void(0)" class="nav_5" id="select_all">Select All</a><span style="float:left;">|</span>
                <a href="javascript:void(0)" class="nav_5" id="unselect_all">Unselect All</a><span style="float:left;">|</span>
                <a href="javascript:void(0)" class="nav_5" id="select_visible">Select Visible</a><span style="float:left;">|</span>
                <a href="javascript:void(0)" class="nav_5" id="unselect_visible">Unselect Visible</a><span style="float:left;">|</span>
                <span style="float:left; margin:2px 0 0 3px;"><strong id="total_selected"></strong> items selected </span>
            </div>
            <div class="right" style="margin:-2px 0 0 0;width:40%;border:0px solid black;">                                    	
                <input type="button" name="button" id="button" value="" class="submit" onclick="Action()"/>
                <select name="select" size="1" id="select_action" class="sel1" style="width:50%; height:20px; float:right;">
                  <option selected="selected" value="">-</option>
                  <option value="Approve">Approve</option>
                  <option value="Editing Required">Editing Required</option>
                  <option value="Deleted">Deleted</option>
                </select>      
                <span style="float:right; margin:2px 3px 0 0;">Actions</span>                                  
            </div>
        </div>
    </div>
    
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;" id="table" class="table_off">
        <tr class="table2" height="30">
            <td class="table_text1" style="border:1px solid #D3C8AA;width:50px;">
			<?php echo $paginator->sort('ID', 'TransactionLog.id',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
            
            </td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:80px;">
				<?php echo $paginator->sort('Invoice.id', 'TransactionLog.invoice_id',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
			</td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;">
				<?php echo $paginator->sort('Fullname', 'Profile.fullname',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
			</td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;">
				<?php echo $paginator->sort('Email', 'User.email',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
			</td>
           
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;display:none;" rel="col_hide">
            	<?php echo $paginator->sort('Payment Method', 'PaymentMethod.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
            </td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;display:none;" rel="col_hide">
            	<?php echo $paginator->sort('Jml Point', 'TransactionLog.voucher_value',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
            </td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;display:none;" rel="col_hide">
            	<?php echo $paginator->sort('Basic Price', 'TransactionLog.basic_price',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
            </td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;display:none;" rel="col_hide">
            	<?php echo $paginator->sort('Extra', 'TransactionLog.extra',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
            </td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;display:none;" rel="col_hide">
            	<?php echo $paginator->sort('Tax', 'TransactionLog.tax',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
            </td>
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;display:none;" rel="col_hide">
            	<?php echo $paginator->sort('Total', 'TransactionLog.total',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?>
            </td>
			
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Created', 'TransactionLog.created',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
			
			<td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Expired', 'TransactionLog.expired',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
			
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;"><?php echo $paginator->sort('Status', 'TransactionLog.SStatus',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
			
            <td class="table_text1" style="border:1px solid #D3C8AA;width:120px;">Action</td> 
			<!---->
        </tr>
        
        <tr class="table3" style="border:1px solid #D3C8AA;">
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
               
            </td>
			
            <td style="border:1px solid #D3C8AA;" rel="col_hide">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;" rel="col_hide">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;" rel="col_hide">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;" rel="col_hide">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;" rel="col_hide">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;" rel="col_hide">&nbsp;</td>
            
            <td style="border:1px solid #D3C8AA;">&nbsp;
            	
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
            	  
            </td>
			<td style="border:1px solid #D3C8AA;">&nbsp;
            	  
            </td>
			<!---->
        </tr>
        
        <?php if(count($data)>0):?>
        <?php $count=0;?>
        <?php foreach($data as $data):?>
        <?php $count++?>
        <?php $back	=	($data['TransactionLog']['status']=="-1") ? "#FFD5D8" : (($count%2==0) ? "#f6f6f6" : "#FFFFFF");?>
		
        <tr bgcolor="<?php echo $back?>">
			<td class="table_text3" style="border:1px solid #D3C8AA;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')">
				<?php echo $data['TransactionLog']['id']?>
			</td>
			
            <td class="table_text2" style="border:1px solid #D3C8AA;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')">
				<a href="javascript:void(0)" class="nav_4" onclick="Detail('<?php echo $data['TransactionLog']['id']?>')"><?php echo $data['TransactionLog']['invoice_id']?></a>
            </td>
			
            <td class="table_text3" style="border:1px solid #D3C8AA;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')">
				<?php echo $data['Profile']['fullname']?>
			</td>
			
            <td class="table_text3" style="border:1px solid #D3C8AA;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')">
				<?php echo $data['User']['email']?>
			</td>
			
			
            <td class="table_text3" style="border:1px solid #D3C8AA;display:none;;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')" rel="col_hide">
				<?php echo $data['PaymentMethod']['name']?>
			</td>
			
            <td class="table_text3" style="border:1px solid #D3C8AA;display:none;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')" rel="col_hide">
			
				<?php echo $number->format($data['TransactionLog']['voucher_value'],array("thousands"=>".","before"=>null,"places"=>null,"after"=>null))?>
				
			</td>
            <td class="table_text3" style="border:1px solid #D3C8AA;display:none;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')" rel="col_hide">
				<?php echo $number->format($data['TransactionLog']['basic_price'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null))?>
			</td>
			<td class="table_text3" style="border:1px solid #D3C8AA;display:none;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')" rel="col_hide">
				<?php echo $number->format($data['TransactionLog']['extra'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null))?>
			</td>
			<td class="table_text3" style="border:1px solid #D3C8AA;display:none;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')" rel="col_hide">
				<?php echo $number->format($data['TransactionLog']['tax'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null))?>
			</td>
			<td class="table_text3" style="border:1px solid #D3C8AA;display:none;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')" rel="col_hide">
				<?php echo $number->format($data['TransactionLog']['total'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null))?>
			</td>
			
            <td class="table_text2" style="border:1px solid #D3C8AA;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')">
				<?php echo date("d-M-Y",$data['TransactionLog']['created'])?>
			</td>
			
			<td class="table_text2" style="border:1px solid #D3C8AA;" onClick="OnClickTr('<?php echo $data['TransactionLog']['expired']?>')">
				<?php echo date("d-M-Y",$data['TransactionLog']['expired'])?>
			</td>
			
            <td class="table_text2" style="border:1px solid #D3C8AA;" onClick="OnClickTr('<?php echo $data['TransactionLog']['id']?>')">
				<?php echo $data['TransactionLog']['SStatus']?>
			</td>
			
            <td style="border:1px solid #D3C8AA; text-align:center;">
                <div class="line2">
                    <a href="<?php echo $settings['cms_url']?>Transaction/DetailTransaction/<?php echo $data['TransactionLog']['id']?>" class="nav_6">detail</a>
					<?php if($data['Product']['have_log']=='1'):?>
                    	<a href="<?php echo $settings['cms_url']?>ProductLogDetail/Index/<?PHP echo $data['Product']['id']?>" class="nav_6" target="_blank">log</a>
                    <?php endif;?>
                </div>
            </td>
			<!---->
        </tr>
        <?php endforeach;?>
        <?php else:?>
        <tr height="130">
            <td width="100%" colspan="25">
                <div class="alert">
                    <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
                    Data tidak di temukan
                </div>
            </td>
        </tr>
        <?php endif;?>
    </table>	
</div>
<div id="detail" style="display:none; float:left; width:100%"></div>            
<!-- End Table -->