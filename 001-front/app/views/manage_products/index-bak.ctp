<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery-ui-1.7.1.custom.min")?>
<?php echo $javascript->link("daterangepicker.jQuery")?>
<?php echo $html->css("ui.daterangepicker")?>
<?php echo $html->css("redmond/jquery-ui-1.7.1.custom.css")?>

<?php echo $html->css("tab")?>
<?php echo $html->css("menu_baba")?>

<script type="text/javascript">
var cookie	=	0;
$(document).ready(function() {
	$("ul.tabs li:first").addClass("active").show(); 
	$("ul.tabs li").click(function() {
								   
		var pos			=	$("#list_item").offset();
		var leftpos		=	pos.left;
		var toppos		=	pos.left;
		var product_id	=	$(this).attr('rel');
		
		$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
		$("#loading_gede").show();
	
		$("#list_item").css("opacity","0.5");
		$("#list_item").load("<?php echo $settigs['site_url']?>ManageProducts/ListItem/"+product_id,function(){
			$("#list_item").css("opacity","1");
			$("#loading_gede").hide();
		});
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		var activeTab = $(this).find("a").attr("href");
		$("#psng_ikln").trigger('click');
		return false;
	});
	
	var sumdata;
	$.getJSON("<?php echo $settigs['site_url']?>ManageProducts/LoadStatusJson",function(data){
		$.each(data,function(i,status){
			$.getJSON("<?php echo $settigs['site_url']?>ManageProducts/GetSumStatus/"+status.Productstatus.id,function(data){
				sumdata	=	(data!==null) ? data : "0";
				$("#sum_"+status.Productstatus.id).html("("+sumdata+")");
			});
		});
	});
	
	$("#list_item").load("<?php echo $settigs['site_url']?>ManageProducts/ListItem/1",function(){
		$("span[id^='sum_']").show();
	});
	
	$("#SearchNopol").watermark({watermarkText:'Masukkan No.polisi motor',watermarkCssClass:'all_input4'});
	$("#SearchThnFrom").watermark({watermarkText:'2001',watermarkCssClass:'all_input4'});
	$("#SearchThnTo").watermark({watermarkText:'2005',watermarkCssClass:'all_input4'});
	$("#SearchColor").watermark({watermarkText:'Merah',watermarkCssClass:'all_input4'});
	
	$("#SearchPriceFrom").watermark({watermarkText:'5.000.000',watermarkCssClass:'all_input4'});
	$("#SearchPriceTo").watermark({watermarkText:'13.000.000',watermarkCssClass:'all_input4'});
});

function ShowEdit(ID)
{
	$("li[id^='li_']").each(function(){
		if($(this).attr('id')!=="li_"+ID)
		{
			$(this).removeClass();
			$(this).addClass('drop');
			$(this).find("ul").hide();
		}
		else
		{

			$("#li_"+ID).toggleClass('show');
			$("#ul_"+ID).toggle();
			
		}
	});
}
function Advance(searchdiv,advanceddiv)
{
	$("#"+searchdiv).hide();
	$("#"+advanceddiv).show();
	if(cookie==0)
	{
		
		$("#SearchTglInput").daterangepicker({
			arrows:false,
			doneButtonText:'Pilih',
			presetRanges: [
				{text: 'Kapanpun', dateStart: function(){$("#SearchTglInput").val('');$("#psng_ikln").trigger('click');}, dateEnd: '' },
				{text: 'Hari ini', dateStart: 'today', dateEnd: 'today' },
				{text: '7 hari terakhir', dateStart: 'today-7days', dateEnd: 'today' },
				{text: 'Bulan ini', dateStart: function(){ return Date.parse('today').moveToFirstDayOfMonth();  }, dateEnd: 'today' },
				{text: 'Tahun ini', dateStart: function(){ var x= Date.parse('today'); x.setMonth(0); x.setDate(1); return x; }, dateEnd: 'today' },
				{text: 'Bulan lalu', dateStart: function(){ return Date.parse('1 month ago').moveToFirstDayOfMonth();  }, dateEnd: function(){ return Date.parse('1 month ago').moveToLastDayOfMonth();  } }
			],
			presets: {
				specificDate: 'Tanggal tertentu',
				dateRange: 'Rentang Tanggal'
			},
			dateFormat: 'd-M-yy',
			rangeSplitter: 's.d',
			rangeStartTitle: 'Dari tanggal:',
			rangeEndTitle: 'Sampai dengan:',
			datepickerOptions : {
				maxDate: "Today",
				dayNamesMin: ['Mi', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
				monthNames: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']
			}
		});
		cookie=1;
	}
}
function BackTo(searchdiv,advanceddiv)
{
	$("#"+searchdiv).show();
	$("#"+advanceddiv).hide();
}

function OnMouseOverTr(ID)
{
	$("tr[id^='tr_']").each(function(){
		if($(this).attr('class')!=='header_table_checked')
		{
			$(this).removeClass();
			$(this).addClass('header_table_off');
		}
	});

	if($("#tr_"+ID).attr('class')!=='header_table_checked')
	{
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('header_table_on');
		
	}
}

function OnClickTr(ID)
{
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
		
	var item_selected	=	$(":checkbox").filter(':checked').length;
	
	if(checked.is(':checked')==true)
	{
		checked.attr("checked","");
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('header_table_off');
		if($("#ul_"+ID).is(":visible"))
		{
			$("#li_"+ID).removeClass();
			$("#li_"+ID).addClass('drop');
			$("#ul_"+ID).hide();	
		}
	}
	else
	{
		checked.attr("checked","checked");
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('header_table_checked');
		
	}
	//counter();
	return true;
}

function OnClickTr2(ID)
{
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
		
	var item_selected	=	$(":checkbox").filter(':checked').length;
	
	if(checked.is(':checked')==false)
	{
		//checked.attr("checked","");
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('header_table_off');
		if($("#ul_"+ID).is(":visible"))
		{
			$("#li_"+ID).removeClass();
			$("#li_"+ID).addClass('drop');
			$("#ul_"+ID).hide();	
		}
	}
	else
	{
		//checked.attr("checked","checked");
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('header_table_checked');
		
	}
	//counter();
	return true;
}
function OnMouseOutTable()
{
	$("tr[id^='tr_']").each(function(){
		if($(this).attr('class')!=='header_table_checked')
		{
			$(this).removeClass();
			$(this).addClass('header_table_off');
		}
	});
}

function onClickPage(el,divName)
{
	
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+100)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}
function Search()
{
	var pos			=	$(".list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;

	$("#SearchListItemForm").ajaxSubmit({
		url:'<?php echo $settings['cms_url']?>UserLogs/ListItem/<?php echo $user_id?>',
		type:'POST',
		dataType: "html",
		clearForm:false,
		
		beforeSend:function()
		{
			$("#loading_gede").css({left:(leftpos+350),top:(toppos+100)});
			$("#loading_gede").show();
			$(".side_center").css("opacity","0.5");
		},
		complete:function(data,html)
		{
			$("#loading_gede").hide();
			$(".side_center").css("opacity","1");
		},
		error:function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert(textStatus);
		},
		success:function(data)
		{
			$(".side_center").html(data);
		}
	});
	return false;
}

function SubCat(parent_id)
{
	if(parent_id.length>0)
	{
		var option	=	'<option value="">Mohon tunggu sebentar..</option>';
		$("#category_id").html(option);
		
		$.getJSON("<?php echo $settings['site_url']?>AddProduct/GetSubcategoryJson",
		{
			"parent_id":parent_id
		},function(data)
		{
			var option	=	'<option value="">Semua tipe '+$("#parent_id option:selected").text()+'</option>';
			if(data.length>0)
			{
				$.each(data,function(i,item){
					option	+=	'<option value="'+item.Category.id+'">'+item.Category.name+'</option>';
				});
			}
			$("#category_id").html(option);
		});
	}
	else
	{
		$("#category_id").html('<option value="">Semua tipe</option>');	
	}
}
</script>
<style>
.header_table{
	min-height:30px;
	max-height:40px;	
	margin:0;
	height:30px;
	background:url(<?php echo $this->webroot?>img/bg_panel_rotate2.jpg) repeat-x;
	background-position:bottom;
}
.header_table_on
{
	min-height:30px;
	max-height:40px;	
	margin:0;
	height:70px;
	background-color:#FDF9E8;
	border:1px solid #D3C8AA;
	cursor:pointer;
}
.header_table_checked
{
	min-height:30px;
	max-height:40px;	
	margin:0;
	height:70px;
	background-color:#F9F2D5;
	border:1px solid #ADA590;
	border-top:1px solid #ADA590;
	cursor:pointer;
}

.header_table_off
{
	min-height:30px;
	max-height:40px;	
	margin:0;
	height:70px;
	border:1px solid #ffffff;
	border-bottom:1px solid #D3C8AA;
	cursor:pointer;
}

</style>
<div id="output"></div>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<div class="line1">
	<div class="line1" style=" margin-bottom:10px;">
        <div class="line4" style="border:0px solid black; margin-left:0px;">
            <span class="text3" id="psng_ikln">Daftar Iklan</span>
        </div>
    </div>
    <div class="line1">
        <div class="divcontainer">
            <ul class="tabs">
            	<?php foreach($status as $status):?>
                <li rel="<?php echo $status['Productstatus']['id']?>"><a href="javascript:void(0)"><?php echo $status['Productstatus']['name']?> <span id="sum_<?php echo $status['Productstatus']['id']?>" style="display:none">(0)</span></a></li>
                <?php endforeach;?>
            </ul>
            <div class="tab_container">
        		<div id="tab1" class="tab_content">
                	<div class="line1" style="margin-top:10px;" id="searchdiv1_1">
                    	<input name="" type="text" class="all_input3">
                        <input type="submit" name="button" id="button" value="Search" class="btn_sign2"/>
                        <a href="javascript:void(0)" class="text7" style="float:none; margin-left:10px" onClick="Advance('searchdiv1_1','advanceddiv_1')">Advanced Search</a>
                    </div>
                    <?php echo $form->create("Search",array("onsubmit"=>"return Search()"))?>
                    <div class="line1" style="border:0px solid black; display:none;margin-top:10px;" id="advanceddiv_1">
                    	<div class="left" style="width:28%;border:0px solid black; margin-right:10px">
                        	<div class="line1">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Merk motor</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                    <select name="data[Search][parent_id]" style="width: 100%;" class="text8" onchange="SubCat(this.value)" id="parent_id">
										<option value="">Semua merk</option>
									<?php foreach($category as $k=>$v):?>
                                        <option value="<?php echo $k?>"><?php echo $v?></option>
                                    <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div class="line1" style="margin-top:10px;">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Tipe motor</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                    <select name="data[Search][category_id]" style="width: 100%;" class="text8" id="category_id">
										<option value="">Semua tipe</option>
                                    </select>
                                </div>
                            </div>
                            <div class="line1" style="margin-top:10px;">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Tgl Input</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                    <?php echo $form->input("tgl_input",array("class"=>"all_input4","div"=>false,"label"=>false,"type"=>"text","readonly"=>true))?>
                                </div>
                            </div>
                        </div>
                       	<div class="left" style="width:28%;border:0px solid black; margin-right:10px">
                        	<div class="line1">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Kondisi</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                     <?php echo $form->select("condition_id",$condition,false,array("style"=>"width:100%;float:left","class"=>"text8","label"=>"false","escape"=>false,"empty"=>'Semua kondisi'));?>
                                </div>
                            </div>
                            <div class="line1" style="margin-top:10px;">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Nopol</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                	<?php echo $form->input("nopol",array("class"=>"all_input4","div"=>false,"label"=>false,"type"=>"text","maxlength"=>9))?>
                                </div>
                            </div>
                            <div class="line1" style="margin-top:10px;">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Warna</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                    <?php echo $form->input("color",array("class"=>"all_input4","div"=>false,"label"=>false,"type"=>"text","maxlength"=>9))?>
                                </div>
                            </div>
                        </div>
                        <div class="left" style="width:28%;border:0px solid black; margin-right:10px">
                        	<div class="line1">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Thn Pembuatan</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                	<?php echo $form->input("thn_from",array("class"=>"all_input4","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,'style'=>'width:53px'))?>
                                    <span style="float:left; margin:0 4px 0 3px" class="text8"> s/d </span>
                                    <?php echo $form->input("thn_to",array("class"=>"all_input4","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,'style'=>'width:53px'))?>
                                </div>
                            </div>
                            <div class="line1" style="margin-top:10px;">
                                <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                                   <span class="text8">Harga</span> 
                                </div>
                                <div class="left" style="width:68%;border:0px solid black;">
                                   <?php echo $form->input("price_from",array("class"=>"all_input4","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,'style'=>'width:53px'))?>
                                   <span style="float:left; margin:0 4px 0 3px" class="text8"> s/d </span>
                                   <?php echo $form->input("price_to",array("class"=>"all_input4","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,'style'=>'width:53px'))?>
                                </div>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <input type="submit" name="button" id="button" value="Search" class="btn_sign2" style="margin-left:0px"/>
                            <input type="submit" name="button" id="button" value="Reset" class="btn_sign2"/>
                            <a href="javascript:void(0)" class="text7" style="float:none; margin-left:10px" onClick="BackTo('searchdiv1_1','advanceddiv_1')">Back to Standart Search</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $form->end();?> 
          	<div class="line1" style="border:0px solid black; margin-top:10px;" id="list_item">
                <table width="805" border="0" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;">
                    <tr class="header_table" style="border:1px solid #D3C8AA;">
                        <td style="width:50%">&nbsp;
                           
                        </td>
                        <td style="width:50%" class="table_text1">&nbsp;
                        	
                        </td>
                    </tr>
                </table>
                <div class="line1" style="border:0px solid black; height:160px">
                	<img src="<?php echo $this->webroot?>img/loading51.gif" style="margin:20px 320px"/>
                </div>
                <table width="805" border="0" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;">
                    <tr class="header_table" style="border:1px solid #D3C8AA; height:40px;">
                        <td style="width:50%">&nbsp;
                           
                        </td>
                        <td style="width:50%" class="table_text1">&nbsp;
                        	
                        </td>
                    </tr>
                </table>
			</div>
        </div>
    </div>
</div>