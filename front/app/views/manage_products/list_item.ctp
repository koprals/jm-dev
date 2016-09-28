<?php echo $paginator->options(array(
				'url'	=> array(
					'controller'	=> 'ManageProducts',
					'action'		=> 'ListItem/'.$product_status.'/limit:'.$viewpage.$ordered,
				),
				'onclick'=>"return onClickPage(this,'#list_item');")
			);
?>

<?php
$order		=	array_keys($this->params['paging']['Product']['options']['order']);
$direction	=	$this->params['paging']['Product']['options']['order'];
$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction[$order[0]] : "";
?>

<?php echo $javascript->link("phpjs.js")?>

<script>
var total_record = <?php echo $paginator->counter(array('format' => __('%current%', true)))?>;
var total_all_record = <?php echo $paginator->counter(array('format' => __('%count%', true)))?>;
var total_spread = total_all_record - total_record;
var cookie	=	new Array();

$(document).ready(function(){
	
	if($.cookie('action'))
	{
		$("#action").val($.cookie('action'));
	}
	if($.cookie('checklist'))
	{
		var chkcookie	=	$.cookie('checklist').split(",");
		$("input[type='checkbox']").each(function(key){
			if(in_array($(this).val(),chkcookie))
			{
				$(this).attr("checked","checked");
				$("#tr_"+$(this).val()).removeClass();
				$("#tr_"+$(this).val()).addClass('header_table_checked');
			}
		});
	}
	
	$('a[rel^=sudah_terjual]').each(function(){
		$(this).bt({
			width: 237,
			positions: ['left'],
			cornerRadius: 2,
			strokeStyle: '#FFFFFF',
		 	fill: 'rgba(0, 0, 0, 1)',
			cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px'},
			shrinkToFit: true
		});
	});
});

function select_all(){
	$.cookie('checklist',null);	
	$(":checkbox").attr({ checked: true});
	$("tr[id^='tr_']").each(function(){
		$(this).removeClass();
		$(this).addClass('header_table_checked');
	});
	
	var myCars	=	new Array();
	$("input[name='data[Product][id][]']").each(function(i){
		myCars[i]	=	$(this).val();
	});
	$("#test_select_all").html('');
	
	$.getJSON('<?php echo $settings['site_url']?>ManageProducts/SelectAll/'+$("#tab_active").val(),function(data){
		$.each(data, function(i, item){
			cookie[i]	=	item.Product.id;
		});
		$.cookie('checklist', cookie, { expires: 1});
	});
}

function unselect_all()
{
	$(":checkbox").attr({ checked: false});
	$("#test_select_all").html('');
	$("tr[id^='tr_']").each(function(){
		$(this).removeClass();
		var ID = $(this).attr('id').split('_');

		if($("#ul_"+ID[1]).is(":visible"))
		{
			$("#li_"+ID[1]).removeClass();
			$("#li_"+ID[1]).addClass('drop');
			$("#ul_"+ID[1]).hide();	
		}
	});
	cookie	=	new Array();
	$.cookie('checklist',null);
}
function select_visible()
{
	$.cookie('checklist',null);
	cookie	=	new Array();
	
	$("#test_select_all").html('');
	$(":checkbox").attr({ checked: true});
	$("tr[id^='tr_']").each(function(i){
		$(this).removeClass();
		$(this).addClass('header_table_checked');
		var ID		=	$(this).attr('id').split('_');
		cookie[i]	=	ID[1];
		
	});
	$.cookie('checklist', cookie, { expires: 1});
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
					chkcookie.splice (key,1);
				}
			});
		}
	});
	$.cookie('checklist',chkcookie);
}

function DeleteItem()
{
	$.getJSON("<?php echo $settings['site_url']?>Template/CheckLogin",function(data){
		if(data.status==false)
		{
			Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Maaf session login anda telah habis, silahkan login kembali atau refresh halaman ini.</span></div>",function(){window.location.reload()},{title:'Login expired.'});
		}
		else
		{
			if(!$.cookie('checklist'))
			{
				Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Silahkan pilih item yang akan didelete</span></div>",function(){},{title:'Pilih iklan.'});
			}
			else
			{
				Boxy.confirm("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Anda yakin akan menghapus item ini ? </span></div>", function() {
					$("#selected_items").val($.cookie('checklist'));
					DeleteAll();
				},{title:"Konfirmasi hapus data"});
			}
		}
	});	
}


function SoldItem()
{
	$.getJSON("<?php echo $settings['site_url']?>Template/CheckLogin",function(data){
		if(data.status==false)
		{
			Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Maaf session login anda telah habis, silahkan login kembali atau refresh halaman ini.</span></div>",function(){window.location.reload()},{title:'Login expired.'});
		}
		else
		{
			if(!$.cookie('checklist'))
			{
				Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Silahkan pilih item yang akan diupdate</span></div>",function(){},{title:'Pilih iklan.'});
			}
			else
			{
				Boxy.confirm("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Ubah status menjadi terjual ?</span></div>", function() {
					$("#selected_items").val($.cookie('checklist'));
					SoldAll();
				},{title:"Konfirmasi update data"});
			}
		}
	});	
}

function SoldAll()
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
		
	$.ajax({
		type		:	"POST",
		url			:	"<?php echo $settings['site_url']?>ManageProducts/Sold",
		dataType	:	"json",
		data		:	{
			"selected_items":$("#selected_items").val()
		},
		success		:	function(data){
		//$("#output").html(data);
		$("#list_item").css("opacity","1");
		$("#loading_gede").hide();
			
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
						chkcookie.splice (key,1);
					}
				});
			}
		}
		$.cookie('checklist',chkcookie);
		$("#list_item").load("<?php echo $settings['site_url']?>ManageProducts/ListItem/"+$("#tab_active").val()+"/1/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		SumData();
		},
		{title:'Updating data'});
		}
	});
}

function DeleteAll()
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
		
	$.ajax({
		type		:	"POST",
		url			:	"<?php echo $settings['site_url']?>ManageProducts/Delete",
		dataType	:	"json",
		data		:	{
			"selected_items":$("#selected_items").val()
		},
		success		:	function(data){
		//$("#output").html(data);
		$("#list_item").css("opacity","1");
		$("#loading_gede").hide();
			
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
						chkcookie.splice (key,1);
					}
				});
			}
		}
		$.cookie('checklist',chkcookie);
		$("#list_item").load("<?php echo $settings['site_url']?>ManageProducts/ListItem/"+$("#tab_active").val()+"/1/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		SumData();
		},
		{title:'Deleting data'});
		}
	});
}


function DeleteItemByOne(ID)
{
	$.getJSON("<?php echo $settings['site_url']?>Template/CheckLogin",function(data){
		if(data.status==false)
		{
			Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Maaf session login anda telah habis, silahkan login kembali atau refresh halaman ini.</span></div>",function(){window.location.reload()},{title:'Login expired.'});
		}
		else
		{
			if(!$.cookie('checklist'))
			{
				Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Silahkan pilih item yang akan didelete</span></div>",function(){},{title:'Pilih item.'});
			}
			else
			{
				Boxy.confirm("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Anda yakin akan menghapus item ini ? </span></div>", function() {
					DeleteByOne(ID)
				},{title:"Konfirmasi hapus data"});
			}
		}
	});	
}

function SoldItemByOne(ID)
{
	$.getJSON("<?php echo $settings['site_url']?>Template/CheckLogin",function(data){
		if(data.status==false)
		{
			Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Maaf session login anda telah habis, silahkan login kembali atau refresh halaman ini.</span></div>",function(){window.location.reload()},{title:'Login expired.'});
		}
		else
		{
			if(!$.cookie('checklist'))
			{
				Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Silahkan pilih item yang akan didelete</span></div>",function(){},{title:'Pilih item.'});
			}
			else
			{
				Boxy.confirm("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Ubah status menjadi terjual ?</span></div>", function() {
					SoldByOne(ID)
				},{title:"Konfirmasi update data"});
			}
		}
	});	
}

function SoldByOne(ID)
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
	
	$.ajax({
		type		:	"POST",
		url			:	"<?php echo $settings['site_url']?>ManageProducts/Sold",
		dataType	:	"json",
		data		:	{
			"selected_items":ID
		},
		success		:	function(data){
		$("#list_item").css("opacity","1");
		$("#loading_gede").hide();
		
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
		$("#list_item").load("<?php echo $settings['site_url']?>ManageProducts/ListItem/"+$("#tab_active").val()+"/1/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		SumData();
		},
		{title:'Updating data'});
		}
	});
}

function DeleteByOne(ID)
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
	
	$.ajax({
		type		:	"POST",
		url			:	"<?php echo $settings['site_url']?>ManageProducts/Delete",
		dataType	:	"json",
		data		:	{
			"selected_items":ID
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
		
		$("#list_item").load("<?php echo $settings['site_url']?>ManageProducts/ListItem/"+$("#tab_active").val()+"/1/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		SumData();
		},
		{title:'Deleting data'});
		}
	});
}


function SeeNotice(ID)
{
	$("tr[id^=notice_]").hide();
	$("#notice_"+ID).show();
	$("#alert_"+ID).show(500);
	if($("#ul_"+ID).is(":visible"))
	{
		$("#li_"+ID).removeClass();
		$("#li_"+ID).addClass('drop');
		$("#ul_"+ID).hide();	
	}
	
}

function CloseNotice(ID)
{
	$("#alert_"+ID).fadeOut(500);	
	$("#notice_"+ID).fadeOut(500);
}

function CheckCookie()
{
	$("#cookie").html($.cookie('checklist'));
}

function Action(value)
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
</script>
<div id="cookie"></div>
<input type="hidden" id="selected_items" value="" name="data[Product][selected_items]">
<table width="805" border="0" cellspacing="0" cellpadding="0" style="border-color:#888888; border-collapse:collapse;">
    <tr class="header_table" style="border:1px solid #888888; height:40px;background-color:#A5A5A5;">
        <td style="width:61%">
            <div class="kiri size100 left8" style="border:0px solid black;">
                <div class="style1 white text12 top5 kiri right15 bold">
                    <span style="float:left;">View&nbsp;</span>
                    <?PHP echo $form->select("view",array(1=>1,5=>5,10=>10,20=>20,100=>100,200=>200,1000=>1000),$viewpage,array("class"=>"style1 black1 text11","style"=>"width:auto; height:22px; float:left; margin:-5px 3px 0 3px; border:1px solid #c8c8c8; cursor:pointer;","onchange"=>"onClickPage('".$settings['site_url']."ManageProducts/ListItem/$product_status/limit:'+this.value+'".$ordered."','#list_item')","empty"=>false))?>
                    <span style="float:left;">per page | <?php echo $paginator->counter(array('format' => 'Total %count% records found'));?></span>
                </div>
                
                <input type="button" name="add_new" value="Add New" class="tombol1 right5" onclick="location.href='<?php echo $settings['site_url']?>Cpanel/AddProduct'"/>
                <!-- input type="submit" name="submit" value="Delete" class="tombol1 right5" onclick="DeleteItem()"/-->
                <?php if(in_array($product_status,array("100","1"))):?>
                <input type="submit" name="submit" value="Terjual" class="tombol1" onclick="SoldItem()"/>
                <?php endif;?>
            </div>
        </td>
        <td style="width:38%" class="table_text1">
            &nbsp;
            <?php if($paginator->hasNext() or $paginator->hasPrev()):?>
            <div class="paging-box" style=" width:98%; float:left">
                <ul id="pagination-digg">
                    <?php  $paginator->counter(array('format' => 'Page %page% of %pages%'));?>
                    <?php echo $paginator->prev("Prev",array('class'=>'next',"escape"=>false)); ?>
                    <?php echo $paginator->numbers(array('modulus'=>4,'separator'=>null,'class'=>'navigasi1','span'=>false,'current'=>'current')); ?>
                    <?php echo $paginator->next("Next",array('class'=>'next',"escape"=>false)); ?>   
                </ul>  
            </div>
            <?php endif;?>
        </td>
    </tr>
</table>
<table width="805" border="0" cellspacing="0" cellpadding="0" style="border-color:#888888; border-collapse:collapse; font-family:Arial, Helvetica, sans-serif; font-size:12px;" onmouseout="OnMouseOutTable()">
    <tr class="header_table" style="border:1px solid #888888;">
        <td style="border:1px solid #888888;border-right:none; width:90px;">
            <select name="select" size="1" id="action" class="style1 black1 text11" style="height:22px; width:70px;padding:1px 0; margin:0 3px; border:1px solid #c8c8c8; cursor:pointer;" onchange="Action(this.value)">
                <option selected="">Any</option>
                <option value="1">Select All</option>
                <option value="2">Unselect All</option>
                <option value="3">Select Visible</option>
                <option value="4">Unselect Visible</option>
            </select>
        </td>
        <td style="width:80px;color:#000" class="table_text1">Foto</td>
        <td style="width:80px;"><?php echo $paginator->sort('Merk', 'Parent.name',array('class'=>'style1 white text12 normal bold','escape'=>false,'current'=>'current_sort'));?></td>
        <td style="width:130px;"><?php echo $paginator->sort('Tipe', 'Category.name',array('class'=>'style1 white text12 normal bold','escape'=>false,'current'=>'current_sort'));?></td>
        <td style="width:120px;"><?php echo $paginator->sort('No.Pol', 'Product.nopol',array('class'=>'style1 white text12 normal bold','escape'=>false,'current'=>'current_sort'));?></td>
        <td style="width:120px;"><?php echo $paginator->sort('Harga', 'Product.price',array('class'=>'style1 white text12 normal bold','escape'=>false,'current'=>'current_sort'));?></td>
        <td style="width:120px;"><?php echo $paginator->sort('Tgl Input', 'Product.created',array('class'=>'style1 white text12 normal bold','escape'=>false,'current'=>'current_sort'));?></td>
        <td style="color:#000" class="table_text1">Action</td>
        
    </tr>
    <?php if(!empty($data)):?>
    
    <?php $count=0;?>
    <?php foreach($data as $data):?>
    <?php $count++?>
    <tr class="header_table_off"  onMouseOver="OnMouseOverTr('<?php echo $data['Product']['id']?>')" id="tr_<?php echo $data['Product']['id']?>">
        <td style="padding-left:20px">
            <input name="data[Product][id][]" type="checkbox" value="<?php echo $data['Product']['id']?>" onclick="OnClickTr2('<?php echo $data['Product']['id']?>')"/>
        </td>
        <td onclick="OnClickTr('<?php echo $data['Product']['id']?>')">
        <?php if(!empty($data['ProductImage']['id'])):?>
        <?php $title	=	($data['Product']['sold']=="1") ? "title='Sudah terjual' rel='sudah_terjual'" : ""?>
        <a href="javascript:void(0)" onclick="$.prettyPhoto.open('<?php echo $settings['showimages_url']?>?code=<?php echo $data['ProductImage']['id']?>&prefix=_zoom&content=ProductImage&w=500&h=500&nopict=noimages');" <?php echo $title?>>
        	<img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data['ProductImage']['id']?>&prefix=_tiny&content=ProductImage&w=50&h=50&nopict=noimages" style="border:1px solid #cccccc; padding:1px;"/>
        </a>
        <?php else:?>
        	<img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data['ProductImage']['id']?>&prefix=_tiny&content=ProductImage&w=50&h=50&nopict=noimages" style="border:1px solid #cccccc;padding:1px;"/>
        <?php endif;?>
        </td>
        <td onclick="OnClickTr('<?php echo $data['Product']['id']?>')"><?php echo $data['Parent']['name']?></td>
        <td onclick="OnClickTr('<?php echo $data['Product']['id']?>')"><?php echo $data['Category']['name']?></td>
        <td onclick="OnClickTr('<?php echo $data['Product']['id']?>')"><?php echo strtoupper( $data['Product']['nopol'])?></td>
        <td onclick="OnClickTr('<?php echo $data['Product']['id']?>')" style="width:120px;word-wrap: break-word;" title="<?php echo $number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))?>"><?php echo $text->truncate($number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)),15,array('ending'=>".."))?></td>
        <td onclick="OnClickTr('<?php echo $data['Product']['id']?>')"><?php echo date("d-M-Y",strtotime($data['Product']['created']))?></td>
        <td >
        	<?php if($data['Product']['sold']=="0"):?>
        	<a href="<?php echo $settings['site_url']?>EditProduct/Index/<?php echo $data['Product']['id']?>" class="style1 red text12 normal bold" style="float:left; margin-top:5px;">Edit</a>
			
			<?php if($data['Product']['productstatus_id']==1):?>
            <div id="beacon">
                <ul id="mainNav2" rel='mainNav2'>
                    <li class="drop" id="li_<?php echo $data['Product']['id']?>">
                    	
                        <a href="javascript:void(0)" rel="nofollow" title="More" onclick="ShowEdit('<?php echo $data['Product']['id']?>')"><img src="<?php echo $this->webroot?>img/accr_on.png" border="0"/></a>
                        <div class="submenu">
                            <ul style="display: none;left: -199px;" id="ul_<?php echo $data['Product']['id']?>">
                                <!-- li><a href="javascript:void(0)" onclick="CheckDelete('<?php echo $data['Product']['id']?>');">Delete</a></li -->
                                <?php if(!empty($data['Product']['notice']) && $data['Product']['productstatus_id']==-1):?>
                                    <li><a href="javascript:void(0)" rel="nofollow" onclick="SeeNotice('<?php echo $data['Product']['id']?>')">Lihat catatan dari admin</a></li>
                                <?php endif;?>
                                <?php if($data['Product']['sold']=="0" && $data['Product']['productstatus_id']==1):?>
                                	<li><a href="javascript:void(0)" rel="nofollow" onclick="CheckSold('<?php echo $data['Product']['id']?>')">Sudah Terjual</a></li>
                                <?php endif;?>
                                <?php if($data['Product']['productstatus_id']==1):?>
                                	<li><a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data['Product']['id']?>" rel="nofollow">Lihat</a></li>
                                <?php endif;?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
			<?php endif;?>
            <?php else:?>
            	<!-- a href="javascript:void(0)" onclick="CheckDelete('<?php echo $data['Product']['id']?>');" class="style1 red text12 normal bold" style="float:left; margin-top:5px;">Delete</a -->
                <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data["Product"]["id"]?>" class="style1 red text12 normal bold" style="float:left; margin-top:5px;">Lihat</a>
            <?php endif;?>
        </td>
    </tr>
    <?php if(!empty($data['Product']['notice']) && $data['Product']['productstatus_id']==-1):?>
    <tr height="100" style="display:none;width:98%;" id="notice_<?php echo $data['Product']['id']?>">
    	<td  colspan="8" style="border:0px solid black;" valign="top">
        	<div class="box_alert" id="alert_<?php echo $data['Product']['id']?>" style="display:none">
                <div class="alert">
                    <div class="kiri" style="border:0px solid black; width:750px;">
                        <div class="kiri text12 size100" style="text-align:left;">Catatan:</div>
                        <div class="kiri text12"><?php echo nl2br($data['Product']['notice'])?></div>
                    </div>
                    <div class="kanan" style="border:0px solid black; width:17px; margin-top:-3px;">
                    	<a href="javascript:void(0)" onclick="CloseNotice('<?php echo $data['Product']['id']?>')"><img src="<?php echo $this->webroot?>img/x_blue.gif" border="0"/></a>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <?php endif;?>
    <?php endforeach;?>
    <?php else:?>
    <tr height="130">
    	<td width="100%" colspan="8">
        	<div class="box_alert">
                <div class="alert">
                    <img src="<?php echo $this->webroot?>img/warning.gif"/>
                    <?php if($is_login==0):?>
                        Maaf session login anda telah habis, silahkan login kembali. Atau refresh halaman ini.
                    <?php else:?>
                        Data tidak di temukan
                    <?php endif;?>
                </div>
            </div>
        </td>
    </tr>
    <?php endif;?>
</table>
<table width="805" border="0" cellspacing="0" cellpadding="0" style="border-color:#888888; border-collapse:collapse;">
    <tr class="header_table" style="border:1px solid #888888; height:40px;background-color:#A5A5A5;">
        <td style="width:61%">
            <div class="kiri size100 left8" style="border:0px solid black;">
                <div class="style1 white text12 top5 kiri right15 bold">
                    <span style="float:left;">View&nbsp;</span>
                    <?PHP echo $form->select("view",array(1=>1,5=>5,10=>10,20=>20,100=>100,200=>200,1000=>1000),$viewpage,array("class"=>"style1 black1 text11","style"=>"width:auto; height:22px; float:left; margin:-5px 3px 0 3px; border:1px solid #c8c8c8; cursor:pointer;","onchange"=>"onClickPage('".$settings['site_url']."ManageProducts/ListItem/$product_status/limit:'+this.value+'".$ordered."','#list_item')","empty"=>false))?>
                    <span style="float:left;">per page | <?php echo $paginator->counter(array('format' => 'Total %count% records found'));?></span>
                </div>
                
                <input type="button" name="add_new" value="Add New" class="tombol1 right5" onclick="location.href='<?php echo $settings['site_url']?>Cpanel/AddProduct'"/>
                <!-- input type="submit" name="submit" value="Delete" class="tombol1 right5" onclick="DeleteItem()"/-->
                <input type="submit" name="submit" value="Terjual" class="tombol1" onclick="DeleteItem()"/>
            </div>
        </td>
        <td style="width:38%" class="table_text1">
            &nbsp;
            <?php if($paginator->hasNext() or $paginator->hasPrev()):?>
            <div class="paging-box" style=" width:98%; float:left">
                <ul id="pagination-digg">
                    <?php  $paginator->counter(array('format' => 'Page %page% of %pages%'));?>
                    <?php echo $paginator->prev("Prev",array('class'=>'next',"escape"=>false)); ?>
                    <?php echo $paginator->numbers(array('modulus'=>4,'separator'=>null,'class'=>'navigasi1','span'=>false,'current'=>'current')); ?>
                    <?php echo $paginator->next("Next",array('class'=>'next',"escape"=>false)); ?>   
                </ul>  
            </div>
            <?php endif;?>
        </td>
    </tr>
</table>