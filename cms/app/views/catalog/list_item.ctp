<?php
$order		=	array_keys($this->params['paging']['Category']['options']['order']);
$direction	=	$this->params['paging']['Category']['options']['order'];
$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction[$order[0]] : "";
?>
<script>


var total_record		= <?php echo $paginator->counter(array('format' => __('%current%', true)))?>;
var total_all_record	= <?php echo $paginator->counter(array('format' => __('%count%', true)))?>;
var total_spread 		= total_all_record - total_record;

$("#select_all").click(function(){
	$(":checkbox").attr({ checked: true});
	$("tr[id^='tr_']").each(function(){
		$(this).removeClass();
		$(this).addClass('trCpanelChecked');
	});
	
	var myCars	=	new Array();
	$("input[name='data[Category][id][]']").each(function(){
		myCars[$(this).val()]	=	$(this).val();
	});
	$("#test_select_all").html('');
	$.getJSON('<?php echo $settings['cms_url']?>Catalog/SelectAll',function(data){
		$.each(data, function(i, item){
			if(!in_array(item.Category.id,myCars))
			{
				$("#test_select_all").append('<input name="data[Category][id][]" type="hidden" value="'+item.Category.id+'" checked="checked">');
			}
		});	
	});
	counter(total_spread);
});

$("#unselect_all").click(function(){
	$(":checkbox").attr({ checked: false});
	counter();
	$("#test_select_all").html('');
	$("tr[id^='tr_']").each(function(){
		$(this).removeClass();
	});
});
$("#select_visible").click(function(){
	$("#test_select_all").html('');
	$(":checkbox").attr({ checked: true});
	counter();
	$("tr[id^='tr_']").each(function(){
		$(this).removeClass();
		$(this).addClass('trCpanelChecked');
	});
});

$("#unselect_visible").click(function(){
	$("#test_select_all").html('');
	$(":checkbox").attr({ checked: false});
	counter();
	$("tr[id^='tr_']").each(function(){
		$(this).removeClass();
	});
});
function counter(hit){
	var counter = 0;
	if(hit>0){
		counter = counter  + hit;
	}
	$(":checkbox").each(function(i){
		if(this.checked){
			counter++;
		}
	});
	$("#total_selected").html(counter);
}
function Search()
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;

	$("#SearchListItemForm").ajaxSubmit({
		url:'<?php echo $settings['cms_url']?>Catalog/ListItem',
		type:'POST',
		dataType: "html",
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
			alert(textStatus);
		},
		success:function(data)
		{
			$("#list_item").html(data);
		}
	});
	return false;
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

	if($("#tr_"+ID).attr('class')!=='trCpanelChecked')
	{
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('trCpanelHover');
		$("#tr_"+ID).find("td").css({"color":"#000000"});
	}
}

function OnClickTr(ID)
{
	var checked	=	$("#tr_"+ID).find("input[type='checkbox']");
	var item_selected	=	$(":checkbox").filter(':checked').length;
	
	if(checked.is(':checked')==true)
	{
		checked.attr("checked","");
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).find("td").css({"color":"#000000"});
		
	}
	else
	{
		checked.attr("checked","checked");
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('trCpanelChecked');
		$("#tr_"+ID).find("td").css({"color":"#000000"});
	}
	counter();
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
	var a 	=	confirm('Do you realy want to DELETE category WHERE id =  '+ID);
	if(a==true)
	{
		DeleteMenu();
	}
}

function DeleteMenu()
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;

	$("#SearchListItemForm").ajaxSubmit({
		url:'<?php echo $settings['cms_url']?>Catalog/Delete',
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
			alert(textStatus);
		},
		success:function(data)
		{
			$("#output").html(data);
			var tr	=	data.tr_id;
			for(var i=0;i<tr.length;i++)
			{
				$("#tr_"+tr[i]).remove();
			}
			$("#list_item").load("<?php echo $settings['cms_url']?>Catalog/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
			alert(data.messages);
		}
	});
	return false;
}
function Action(val)
{
	var len	=	$(':checkbox:checked').length;
	if(len==0)
	{
		alert("Please select menu !");
	}
	else
	{
		$("select#select_action option:selected").each(function(){
			if($(this).val()=="delete")
			{
				DeleteMenu();
			}
		});
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

</style>

<?php echo $paginator->options(array(
	'url'	=> array(
		'controller'	=> 'Catalog',
		'action'		=> 'ListItem'
	),
	'onclick'=>"return onClickPage(this,'#list_item');")
);?>
<div id="output"></div>
<?php echo $form->create("Search",array("onsubmit"=>"return Search()"))?>  
<div id="test_select_all">
</div> 
<div class="line3">
    <a href="<?php echo $settings['cms_url'] ?>Catalog/Add/" class="nav_6" style="float:right;"><img src="<?php echo $this->webroot?>img/icn_add.gif" border="0" style="float:left; margin-right:3px;" />Add Category</a>
</div>
<div class="line1">
    <div class="left" style="width:60%;margin-top:8px;">
        <div class="text7" style="width:100%; float:left">
            <span style="float:left;">page</span>
            <?php if($paginator->hasPrev()):?>
                <a href="<?php echo $settings['cms_url']?>Catalog/ListItem/page:<?php echo ($page-1)?>/limit:<?php echo $viewpage.$ordered?>" class="nav_table_left" onclick="return onClickPage(this,'#list_item')"></a>
                
            <?php endif;?>
            <input type="text" name="textfield" id="page" class="all_input2" style="width:30px; float:left; height:16px; margin-top:-5px;" value="<?php echo $page;?>" />
            <?php if($paginator->hasNext()):?>
                <a href="<?php echo $settings['cms_url']?>Catalog/ListItem/page:<?php echo ($page+1)?>/limit:<?php echo $viewpage.$ordered?>" class="nav_table_right" onclick="return onClickPage(this,'#list_item')"></a>
            <?php endif;?>
            <span style="float:left;"><?php echo $paginator->counter(array('format' => 'of %pages% pages'));?> | View </span>
            
            <?PHP echo $form->select("view",array(1=>1,5=>5,10=>10,20=>20,100=>100,200=>200,1000=>1000),$viewpage,array("class"=>"text7","style"=>"width:auto; height:22px; float:left; margin:-5px 3px 0 3px; border:1px solid #c8c8c8; cursor:pointer;","onchange"=>"onClickPage('".$settings['cms_url']."Catalog/ListItem/limit:'+this.value+'".$ordered."','#list_item')","empty"=>false))?>
            
            
            <span style="float:left;">per page | <?php echo $paginator->counter(array('format' => 'Total %count% records found'));?></span>
        </div>
    </div>
    <input type="submit" name="button" id="button" value="" class="search" />	
    <input type="submit" name="button" id="button" value="" class="reset_filter" onclick="$('#reset').val(1)"/>
    <?php echo $form->hidden("reset",array("id"=>"reset","value"=>0))?>
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
            <div class="right" style="margin:-2px 0 0 0;">                                    	
                <input type="button" name="button" id="button" value="" class="submit" onclick="Action(this.value)"/>
                <select name="select" size="1" id="select_action" class="sel1" style="width:50%; height:20px; float:right;">
                  <option value="delete">Delete</option>
                  <option selected="selected">-</option>                              
                </select>      
                <span style="float:right; margin:2px 3px 0 0;">Actions</span>                                  
            </div>
        </div>
    </div>
    
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;" onmouseout="OnMouseOutTable()">
        <tr class="table2">
            <td width="11%" style="border:1px solid #d1cfcf;">&nbsp;</td>
            <td width="8%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('ID', 'Category.id',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="19%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Name', 'Category.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="14%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Parent', 'Parent.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="12%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Link', 'Category.url',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="10%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Taget Link', 'Category.target',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="12%" class="table_text1" style="border:1px solid #D3C8AA;">Action</td>
        </tr>
        
        <tr class="table3" style="border:1px solid #D3C8AA;">
            <td style="border:1px solid #bdbdbd;">
                <select name="select" size="1" id="select" class="text5" style="height:22px; padding:1px 0; margin:0 3px; border:1px solid #c8c8c8; cursor:pointer;">
                  <option selected="selected">Any</option>
                  <option>-</option>                              
                </select>
            </td>
            <td style="border:1px solid #D3C8AA;">
                <?php echo $form->input("id",array("class"=>"all_input2","style"=>"width:60%;","label"=>false,"div"=>false,"value"=>$id))?>
            </td>
           
            <td style="border:1px solid #D3C8AA;">
                <?php echo $form->input("name",array("class"=>"all_input2","label"=>false,"div"=>false,"value"=>$name))?>
            </td>
            <td style="border:1px solid #D3C8AA;">
                <?php echo $form->input("parent",array("class"=>"all_input2","label"=>false,"div"=>false,"value"=>$parent))?>
            </td>
            <td style="border:1px solid #D3C8AA;">
                <?php echo $form->input("link",array("class"=>"all_input2","label"=>false,"div"=>false,"value"=>$link))?>
            </td>
            <td style="border:1px solid #D3C8AA;">
                <?php echo $form->input("target",array("class"=>"all_input2","label"=>false,"div"=>false,"value"=>$target))?>
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
              
            </td>
        </tr>
        
        <?php if(count($data)>0):?>
        <?php $count=0;?>
        <?php foreach($data as $data):?>
        <?php $count++?>
        <?php $back	=	($count%2==0) ? "#f6f6f6" : "#FFFFFF";?>
        
        <tr bgcolor="<?php echo $back?>"  onClick="OnClickTr('<?php echo $data['Category']['id']?>')" onMouseOver="OnMouseOverTr('<?php echo $data['Category']['id']?>')" id="tr_<?php echo $data['Category']['id']?>">
            <td style="border:1px solid #D3C8AA;">
                <input name="data[Category][id][]" type="checkbox" value="<?php echo $data['Category']['id']?>" style="margin:5px auto; display:block;" onClick="OnClickTr('<?php echo $data['Category']['id']?>')" />
            </td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['Category']['id']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['Category']['name']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['Parent']['name']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $settings['site_url'].$data['Category']['url']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['Category']['target']?></td>
            <td style="border:1px solid #D3C8AA;">
                <div class="line2">
                    <a href="<?php echo $settings['cms_url']?>Catalog/Add/<?php echo $data['Category']['id']?>" class="nav_6">edit</a>
                    <a href="javascript:void(0)" class="nav_6" onclick="DeleteItem('<?php echo $data['Category']['id']?>')">delete</a>
                </div>
            </td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
    </table>	
</div>                        
<!-- End Table -->
<?php echo $form->end();?> 