<?php echo $javascript->link("jquery.cookie")?>
<?php echo $javascript->link("jquery.boxy")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery-ui-1.7.1.custom.min")?>
<?php echo $javascript->link("daterangepicker.jQuery")?>
<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->


<?php echo $html->css("ui.daterangepicker")?>
<?php echo $html->css("redmond/jquery-ui-1.7.1.custom.css")?>
<?php echo $html->css("tab")?>
<?php echo $html->css("menu_baba")?>
<?php echo $html->css("boxy")?>

<script type="text/javascript">
var cookie_advance	=	0;
$(document).ready(function() {
	$.cookie('checklist',null);
	$.cookie('action',null);
	Boxy.DEFAULTS.title = 'Title';
	
	$(".tabs").find("li").each(function(i){
		if(i==<?php echo $tab_active?>)
		{
			$(this).addClass("active").show();
			$("#list_item").load("<?php echo $settings['site_url']?>ManageProducts/ListItem/"+$(this).attr("rel")+"/1",function(){
				$("span[id^='sum_']").show();
			});
			$("#tab_active").val($(this).attr("rel"));
		}
	});
	//$("ul.tabs li:first").addClass("active").show();
	
	$("ul.tabs li").click(function() {					   
		var pos			=	$("#list_item").offset();
		var leftpos		=	pos.left;
		var toppos		=	pos.left;
		var product_id	=	$(this).attr('rel');
		$("#keywords").val("");
		$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
		$("#loading_gede").show();
		$("#list_item").css("opacity","0.5");
		
		
		$("#list_item").load("<?php echo $settings['site_url']?>ManageProducts/ListItem/"+product_id+"/1",function(){
			$("#list_item").css("opacity","1");
			$("#loading_gede").hide();
		});
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$("#tab_active").val(product_id);
		var activeTab = $(this).find("a").attr("href");
		$("#psng_ikln").trigger('click');
		return false;
	});
	
	var sumdata;
	$.getJSON("<?php echo $settings['site_url']?>ManageProducts/LoadStatusJson",function(data){
		$.each(data,function(i,status){
			$.getJSON("<?php echo $settings['site_url']?>ManageProducts/GetSumStatus/"+status.Productstatus.id,function(data){
				sumdata	=	(data!==null) ? data : "0";
				$("#sum_"+status.Productstatus.id).html("("+sumdata+")");
			});
		});
	});
	
	$.getJSON("<?php echo $settings['site_url']?>ManageProducts/GetSumStatus/100",function(data){
		sumdata	=	(data!==null) ? data : "0";
		$("#sum_100").html("("+sumdata+")");
	});
	
	$.getJSON("<?php echo $settings['site_url']?>ManageProducts/GetSumStatus/sold",function(data){
		sumdata	=	(data!==null) ? data : "0";
		$("#sum_sold").html("("+sumdata+")");
	});
	

	$("#SearchNopol").watermark({watermarkText:'Masukkan No.polisi motor',watermarkCssClass:'input6 italic'});
	$("#SearchThnFrom").watermark({watermarkText:'2001',watermarkCssClass:'input6 italic'});
	$("#SearchThnTo").watermark({watermarkText:'2005',watermarkCssClass:'input6 italic'});
	$("#SearchColor").watermark({watermarkText:'Merah',watermarkCssClass:'input6 italic'});
	$("#SearchPriceFrom").watermark({watermarkText:'5.000.000',watermarkCssClass:'input6 italic'});
	$("#SearchPriceTo").watermark({watermarkText:'13.000.000',watermarkCssClass:'input6 italic'});
});

function SumData()
{
	var sumdata;
	$.getJSON("<?php echo $settings['site_url']?>ManageProducts/LoadStatusJson",function(data){
		$.each(data,function(i,status){
			$.getJSON("<?php echo $settings['site_url']?>ManageProducts/GetSumStatus/"+status.Productstatus.id,function(data){
				sumdata	=	(data!==null) ? data : "0";
				$("#sum_"+status.Productstatus.id).html("("+sumdata+")");
			});
		});
	});
	
	$.getJSON("<?php echo $settings['site_url']?>ManageProducts/GetSumStatus/100",function(data){
		sumdata	=	(data!==null) ? data : "0";
		$("#sum_100").html("("+sumdata+")");
	});
}

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
	$("#keywords").val("");
	if(cookie_advance==0)
	{
		$("#SearchTglInput").daterangepicker({
			arrows:false,
			doneButtonText:'Pilih',
			presetRanges: [
				{text: '<b>Kapanpun</b>', dateStart: function(){$("#SearchTglInput").val('');$("#psng_ikln").trigger('click');}, dateEnd: '' },
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
		cookie_advance=1;
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
		$("#tr_"+ID).addClass('header_table_checked');
		
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
	//counter();
	return true;
}

function CheckDelete(ID)
{
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
	checked.attr("checked","checked");
	$("#tr_"+ID).removeClass();
	$("#tr_"+ID).addClass('header_table_checked');
	if(!$.cookie('checklist'))
	{
		$.cookie('checklist',ID);
	}
	DeleteItemByOne(ID);
}

function CheckSold(ID)
{
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
	checked.attr("checked","checked");
	$("#tr_"+ID).removeClass();
	$("#tr_"+ID).addClass('header_table_checked');
	if(!$.cookie('checklist'))
	{
		$.cookie('checklist',ID);
	}
	SoldItemByOne(ID);
}

function OnClickTr2(ID)
{
	var checked			=	$("#tr_"+ID).find("input[type='checkbox']");
	var item_selected	=	$(":checkbox").filter(':checked').length;
	
	if(checked.is(':checked')==false)
	{
		$("#tr_"+ID).removeClass();
		$("#tr_"+ID).addClass('header_table_off');
		if($("#ul_"+ID).is(":visible"))
		{
			$("#li_"+ID).removeClass();
			$("#li_"+ID).addClass('drop');
			$("#ul_"+ID).hide();	
		}
		
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
		$("#tr_"+ID).addClass('header_table_checked');
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

function SearchItem()
{
	var keywords	=	$("#keywords").val();
	
	if(keywords.length < 1 && $("#reset").val()==0)
	{
		alert("Masukkan pencarian anda");
		return false;
	}
		var pos			=	$("#list_item").offset();
		var leftpos		=	pos.left;
		var toppos		=	pos.left;
		
		$("#SearchIndexForm").ajaxSubmit({
			url:'<?php echo $settings['site_url']?>ManageProducts/ListItem/'+$("#tab_active").val(),
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
				$("#list_item").html(data);
				$("#reset").val(0);
			}
		});
	
	return false;
}

function SearchAdvance()
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#SearchAdvance").ajaxSubmit({
		url:'<?php echo $settings['site_url']?>ManageProducts/ListItem/'+$("#tab_active").val(),
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
			$("#list_item").html(data);
			$("#reset").val(0);
		}
	});
	
	return false;
}

function ClearSearchAdvance()
{
	$('.watermarkPluginCustomClass').remove();
	
	$("#parent_id").val("");
	SubCat('');
	$("#SearchConditionId").val("");
	$("#SearchTglInput").val("");
	
	$("#SearchNopol").val("");
	$("#SearchThnFrom").val("");
	$("#SearchThnTo").val("");
	$("#SearchColor").val("");
	$("#SearchPriceFrom").val("");
	$("#SearchPriceTo").val("");
	
	$("#SearchNopol").watermark({watermarkText:'Masukkan No.polisi motor',watermarkCssClass:'input6 italic'});
	$("#SearchThnFrom").watermark({watermarkText:'2001',watermarkCssClass:'input6 italic'});
	$("#SearchThnTo").watermark({watermarkText:'2005',watermarkCssClass:'input6 italic'});
	$("#SearchColor").watermark({watermarkText:'Merah',watermarkCssClass:'input6 italic'});
	$("#SearchPriceFrom").watermark({watermarkText:'5.000.000',watermarkCssClass:'input6 italic'});
	$("#SearchPriceTo").watermark({watermarkText:'13.000.000',watermarkCssClass:'input6 italic'});
	
	$("#list_item").load('<?php echo $settings['site_url']?>ManageProducts/ListItem/'+$("#tab_active").val()+'/1');
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
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#FFF;
	background-color:#A5A5A5;
}
.header_table_on
{
	min-height:30px;
	max-height:40px;	
	margin:0;
	height:70px;
	background-color:#DAD9D9;
	border:1px solid #888888;
	cursor:pointer;
}
.header_table_checked
{
	min-height:30px;
	max-height:40px;	
	margin:0;
	height:70px;
	background-color:#6C6C6C;
	border:1px solid #888888;
	border-top:1px solid #888888;
	cursor:pointer;
	color:#FFF;
}
.header_table_checked a
{
	color:#FFF;
}

.header_table_off
{
	min-height:30px;
	max-height:40px;	
	margin:0;
	height:70px;
	border:1px solid #ffffff;
	border-bottom:1px solid #888888;
	cursor:pointer;
	background-color:#ffffff;
}

</style>
<div id="output"></div>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<div class="line" style="width:805px;">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Daftar Iklan</div>
        </div>
	</div>
</div>
<div class="line top20" style="border:0px solid black;">
	<div class="divcontainer" style="border:0px solid black;">
        <ul class="tabs">
            <li rel="100"><a href="javascript:void(0)">All<span id="sum_100" style="display:none">(0)</span></a></li>
            <?php foreach($status as $status):?>
            <li rel="<?php echo $status['Productstatus']['id']?>"><a href="javascript:void(0)"><?php echo $status['Productstatus']['name']?><span id="sum_<?php echo $status['Productstatus']['id']?>" style="display:none">(0)</span></a></li>
            <?php endforeach;?>
            <li rel="sold"><a href="javascript:void(0)">Terjual<span id="sum_sold" style="display:none">(0)</span></a></li>
        </ul>
        <div class="tab_container">
       		<div id="tab1" class="tab_content">
            	<?php echo $form->create("Search",array("onsubmit"=>"return SearchItem()"))?>
                <input name="tab_active" type="hidden" class="all_input3" value="100" id="tab_active">
                <div class="line" style="margin-top:10px;display:block" id="searchdiv1_1">
                    <input name="keywords" type="text" class="input4 style1 black1" id="keywords">
                    <input name="btn_keywords" id="btn_keywords" type="hidden" class="all_input3" value="1">
                    <input name="reset" type="hidden" id="reset" class="all_input3" value="0">
                    <input type="submit" name="submit" value="Search" class="tombol1" onclick="return SearchItem()"/>
                    <input type="reset" name="reset" value="Clear" class="tombol1" onclick="$('#reset').val('1');$('#keywords').val('');return SearchItem()"/>
                    <a href="javascript:void(0)" class="style1 white bold normal text12" style="float:none; margin-left:10px" onClick="Advance('searchdiv1_1','advanceddiv_1')">Advanced Search</a>
                </div>
                <?php echo $form->end();?>
                <?php echo $form->create("Search",array("onsubmit"=>"return SearchAdvance()","id"=>"SearchAdvance"))?>
                <div class="line top10 size100" style="border:0px solid black; display:none;" id="advanceddiv_1">
                    <div class="kiri size315 right15" style="border:0px solid black;">
                        <div class="line size100">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                               Merk motor 
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <select name="data[Search][parent_id]" class="input5 style1 black text11 size100 kiri" onchange="SubCat(this.value)" id="parent_id">
                                    <option value="">Semua merk</option>
                                <?php foreach($category as $k=>$v):?>
                                    <option value="<?php echo $k?>"><?php echo $v?></option>
                                <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="line size100 top15">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                              Tipe motor
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <select name="data[Search][category_id]" class="input5"  id="category_id">
                                	<option value="">Semua tipe</option>
                                </select>
                            </div>
                        </div>
                        <div class="line size100 top15">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                              Tgl Input
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <?php echo $form->input("tgl_input",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text","readonly"=>true))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="kiri size315 right10" style="border:0px solid black;">
                    	<div class="line size100">
                            <div class="kiri size25 right5 white style1 text11 bold" style="border:0px solid black;">
                               Kondisi
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <?php echo $form->select("condition_id",$condition,false,array("class"=>"input5","label"=>"false","escape"=>false,"empty"=>'Semua kondisi'));?>
                            </div>
                        </div>
                        <div class="line size100 top15">
                            <div class="kiri size25 right5 white style1 text11 bold" style="border:0px solid black;">
                              Nopol
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <?php echo $form->input("nopol",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text","maxlength"=>9))?>
                            </div>
                        </div>
                        <div class="line size100 top15">
                            <div class="kiri size25 right5 white style1 text11 bold" style="border:0px solid black;">
                              Warna
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <?php echo $form->input("color",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text","maxlength"=>50))?>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="kiri size315 right10" style="border:0px solid black;">
                    	<div class="line size100">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                               Thn Pembuatan
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <?php echo $form->input("kilometer 	_from",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,'style'=>'width:53px'))?>
                                <span style="float:left; margin:0 4px 0 3px" class="style1 white text11"> s/d </span>
                                <?php echo $form->input("kilometer 	_to",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,'style'=>'width:53px'))?>
                            </div>
                        </div>
                        <div class="line size100 top15">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                              Harga
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                            	<?php echo $form->input("price_from",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:53px'))?>
                            	<span style="float:left; margin:0 4px 0 3px" class="style1 white text11"> s/d </span>
                           		<?php echo $form->input("price_to",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:53px'))?>
                            </div>
                        </div>   
                    </div>
                    <div class="line top20">
                        <input type="submit" name="button" value="Search" class="tombol1" style="margin-left:0px" onclick="return SearchAdvance()"/>
                        <input type="button" name="reset" value="Clear" class="tombol1" onclick="return ClearSearchAdvance()"/>
                        <a href="javascript:void(0)" class="style1 white bold normal text12" style="float:none; margin-left:10px" onClick="BackTo('searchdiv1_1','advanceddiv_1')">Back to Standart Search</a>
                    </div>
                </div>
                <?php echo $form->end();?>
            </div>
        </div>
        
        <div class="line top10" style="border:0px solid black; margin-top:10px;" id="list_item">
            <table width="805" border="0" cellspacing="0" cellpadding="0" style="border-color:#888888; border-collapse:collapse;">
                <tr class="header_table" style="border:1px solid #888888;background-color:#A5A5A5;">
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
                <tr class="header_table" style="border:1px solid #888888;background-color:#A5A5A5; height:40px;">
                    <td style="width:50%">&nbsp;
                       
                    </td>
                    <td style="width:50%" class="table_text1">&nbsp;
                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="kiri style1 red text12 top10 size100 bold">Catatan: </div>
<div class="kiri style1 red text12 top5 size100">1. Iklan yang dapat dirubah status menjadi <span class="bold">"Terjual"</span> adalah iklan yang telah <span class="bold">disetujui(Approve)</span></div>
<div class="kiri style1 red text12 top5 size100">2. Iklan dengan status <span class="bold">"Terjual"</span> tidak dapat lagi di edit. Hanya dapat dilihat detailnya.</div>