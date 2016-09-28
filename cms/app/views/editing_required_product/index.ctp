<?php echo $javascript->link("jquery-ui-1.7.1.custom.min")?>
<?php echo $javascript->link("daterangepicker.jQuery")?>
<?php echo $html->css("ui.daterangepicker")?>
<?php echo $html->css("redmond/jquery-ui-1.7.1.custom.css")?>
<?php echo $javascript->link("jquery.cookie")?>
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.boxy")?>
<?php echo $javascript->link("jquery.scrollTo")?>

<?php echo $html->css("boxy")?>

<script>
var cookie_advance	=	0;
$.cookie('expand',0);
$(document).ready(function(){
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+50)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
	
	$("#list_item").load("<?php echo $settings['cms_url']?>EditingRequiredProduct/ListItem",function(){
		$(this).css("opacity","1");
		$("#loading_gede").hide();
	});
	
	$.cookie('checklist',null);
	$.cookie('action',null);
	Boxy.DEFAULTS.title = 'Title';
	
	//WATERMARK
	$("#SearchId").watermark({watermarkText:'Masukkan id produk/iklan',watermarkCssClass:'all_search_watermark'});
	$("#SearchContactName").watermark({watermarkText:'Masukkan nama penjual',watermarkCssClass:'all_search_watermark'});
	$("#SearchNopol").watermark({watermarkText:'Masukkan no.polisi motor',watermarkCssClass:'all_search_watermark'});
	
	$("#SearchThnFrom").watermark({watermarkText:'2001',watermarkCssClass:'all_search_watermark'});
	$("#SearchThnTo").watermark({watermarkText:'2005',watermarkCssClass:'all_search_watermark'});
	$("#SearchColor").watermark({watermarkText:'Merah',watermarkCssClass:'all_search_watermark'});
	$("#SearchPriceFrom").watermark({watermarkText:'5.000.000',watermarkCssClass:'all_search_watermark'});
	$("#SearchPriceTo").watermark({watermarkText:'13.000.000',watermarkCssClass:'all_search_watermark'});
	
	$("a[rel^=sideleft]").each(function(){
		var a	=	$(this);
		$.getJSON('<?php echo $settings['cms_url']?>Product/GetSumData',{'type':a.html()},function(data){
			a.html(a.html() + ' (' +data+')');
		});
	});
});
function OpenText(ID)
{
	$.getJSON('<?PHP echo $settings['cms_url']?>Product/GetFullText/'+ID,function(data){
		$('#span_'+ID).html(data+'\n<input type="button" value="close" onclick="CloseText(\''+ID+'\')">');	
	});
}
function CloseText(ID)
{
	$.getJSON('<?PHP echo $settings['cms_url']?>Product/GetTruncateText/'+ID,function(data){
		$('#span_'+ID).html(data+'\n<input type="button" value="open" onclick="OpenText(\''+ID+'\')">');	
	});	
}
function onClickPage(el,divName) {
	
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+200)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}
function SubCat(parent_id)
{
	if(parent_id.length>0)
	{
		var option	=	'<option value="">Mohon tunggu sebentar..</option>';
		$("#category_id").html(option);
		
		$.getJSON("<?php echo $settings['cms_url']?>Product/GetSubcategoryJson",
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

function SelectCity(province_id)
{
	if(province_id.length>0)
	{

		$("#pilih_kota").load("<?php echo $settings['cms_url']?>Template/SelectCity/",{'province_id':province_id,'model':'Search','class':'text8','empty':'Semua kota'});
	}
	else
	{
		$("#pilih_kota").html('<select name="data[Search][city_id]" style="width:100%;float:left" class="text8" label="false" id="SearchCityId"><option value="" selected="selected">Kota</option></select>');	
	}
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

function SearchAdvance()
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#SearchAdvance").ajaxSubmit({
		url:'<?php echo $settings['cms_url']?>EditingRequiredProduct/ListItem',
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
			$("#reset").val(0);
		}
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
		url:'<?php echo $settings['cms_url']?>EditingRequiredProduct/ListItem',
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
	$('#reset').val('1');
	SearchItem()
	
	$("#SearchId").val("");
	$("#SearchContactName").val("");
	$("#SearchTglInput").val("");
	$("input:radio").attr("checked", false);
	$("#SearchStatus").val("");
	$("#SearchConditionId").val("");
	$("#SearchProvinceId").val("");
	SelectCity("");
	
	$("#SearchNopol").val("");
	$("#SearchThnFrom").val("");
	$("#SearchThnTo").val("");
	$("#SearchColor").val("");
	$("#SearchPriceFrom").val("");
	$("#SearchPriceTo").val("");
	
	//WATERMARK
	$("#SearchId").watermark({watermarkText:'Masukkan id produk/iklan',watermarkCssClass:'all_search_watermark'});
	$("#SearchContactName").watermark({watermarkText:'Masukkan nama penjual',watermarkCssClass:'all_search_watermark'});
	$("#SearchNopol").watermark({watermarkText:'Masukkan no.polisi motor',watermarkCssClass:'all_search_watermark'});
	
	$("#SearchThnFrom").watermark({watermarkText:'2001',watermarkCssClass:'all_search_watermark'});
	$("#SearchThnTo").watermark({watermarkText:'2005',watermarkCssClass:'all_search_watermark'});
	$("#SearchColor").watermark({watermarkText:'Merah',watermarkCssClass:'all_search_watermark'});
	$("#SearchPriceFrom").watermark({watermarkText:'5.000.000',watermarkCssClass:'all_search_watermark'});
	$("#SearchPriceTo").watermark({watermarkText:'13.000.000',watermarkCssClass:'all_search_watermark'});
}


function CheckDelete(ID)
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
	$.prettyPhoto.open('<?php echo $settings['cms_url']?>Product/MessageDeleted/'+ID+'?iframe=true&width=490&height=420');
}

function ChangeDataConfirmation(type,msg1,msg2,action)
{
	if(!$.cookie('checklist'))
	{
		Boxy.alert("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Silahkan pilih item yang akan "+msg1+"</span></div>",function(){},{title:'Pilih item.'});
	}
	else
	{
		Boxy.confirm("<div style='display:block;float:left;border:0px solid black;width: 350px;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left; width:80%; margin-left:5px;'>Anda yakin akan "+msg2+" item ini ? </span></div>", function() {																																																																																																																																					   			$("#selected_items").val($.cookie('checklist'));
			ChangeItem(type,action);
		},{title:"Konfirmasi update data"});
	}
}


</script>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code))?>
<div class="test-right">
    <div class="content">
        <!-- advance search -->
        <div class="line1" style="margin-bottom:20px;">
            <div class="line1">
                <div class="table1">
                    <div class="line2" style="float:left; width:105px; padding-left:10px;">
                        <b>ADVANCE SEARCH</b>
                    </div>
                </div>
            </div>
            <div class="table3" style="width:99.8%; border:1px solid #D3C8AA; float:left; display:block; min-height:20px">
                <?php echo $form->create("Search",array("onsubmit"=>"return SearchItem()"))?>
                <div class="line1" style="margin-top:10px; margin-left:10px; margin-bottom:10px; border:0px solid black;" id="searchdiv1_1">
                    <input name="keywords" type="text" class="all_input2" id="keywords" style="width:163px;padding:0;margin:0 10px 0 0; float:left;">
                    <input name="reset" type="hidden" id="reset" class="all_input3" value="0">
                    <input name="btn_keywords" id="btn_keywords" type="hidden" class="all_input3" value="1">
                    <input type="submit" name="button" value="" class="search" style="float:left; margin:0" onclick="return SearchItem()"/>
                    <input type="reset" name="button" value="" class="reset_filter" onclick="$('#reset').val('1');$('#keywords').val('');return SearchItem()" style="float:left; margin-left:10px; margin-right:10px;"/>
                    <a href="javascript:void(0)" class="table_text1" onClick="Advance('searchdiv1_1','advanceddiv_1')">Advance Search</a>
                </div>
                <?php echo $form->end()?>
                <?php echo $form->create("Search",array("onsubmit"=>"return SearchAdvance()","id"=>"SearchAdvance"))?>
                <?php echo $form->input("msg_editing_required",array("type"=>"text","id"=>"MsgEditing"))?>
                <?php echo $form->textarea("notice",array("id"=>"ProductNotice","style"=>"display:none;"))?>
                <div class="line1" style="margin:10px; display:none;" id="advanceddiv_1">
                    <div class="left" style="width:31%;border:0px solid black; margin-right:10px">
                        <div class="line1" >
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">ID</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("id",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text"))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Merk motor</span> 
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
                               <span class="text9">Tipe motor</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <select name="data[Search][category_id]" style="width: 100%;" class="text8" id="category_id">
                                    <option value="">Semua tipe</option>
                                </select>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Penjual</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("contact_name",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text"))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Tgl Input</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("tgl_input",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","readonly"=>true))?>
                            </div>
                        </div>
                    </div>
                    <div class="left" style="width:31%;border:0px solid black; margin-right:10px">
                        <div class="line1">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Data Type</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <span class="text8">
                                    <?php echo $form->radio("data_type",array(1=>"Profile",2=>"Dealer"),array("div"=>false,"label"=>true,"legend"=>false))?>
                                </span>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Kondisi</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                 <?php echo $form->select("condition_id",$condition,false,array("style"=>"width:100%;float:left","class"=>"text8","label"=>"false","escape"=>false,"empty"=>'Semua kondisi'));?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Propinsi</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                 <?php echo $form->select("province_id",$province,false,array("style"=>"width:100%;float:left","class"=>"text8","label"=>"false","escape"=>false,"empty"=>'Propinsi',"onchange"=>"SelectCity(this.value)"));?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Kota</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;" id="pilih_kota">
                                 <?php echo $form->select("city_id",false,false,array("style"=>"width:100%;float:left","class"=>"text8","label"=>"false","escape"=>false,"empty"=>'Kota'));?>
                            </div>
                        </div>
                    </div>
                    <div class="left" style="width:31%;border:0px solid black; margin-right:10px">
                        <div class="line1">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Nopol</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("nopol",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>9))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Thn Pmbtn</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("thn_from",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                                <span style="float:left; margin:0 4px 0 3px" class="text8"> s/d </span>
                                <?php echo $form->input("thn_to",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Harga</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                               <?php echo $form->input("price_from",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                               <span style="float:left; margin:0 4px 0 3px" class="text8"> s/d </span>
                               <?php echo $form->input("price_to",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Warna</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("color",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>50))?>
                            </div>
                        </div>
                    </div>
                    <div class="line1" style="margin-top:10px;">
                        <div class="left" style="width:100%;border:0px solid black;">
                            <input type="submit" name="button" id="button" value="" class="search" style="float:left; margin:0" onclick="return SearchAdvance()"/>	
                            <input type="button" name="button" value="" class="reset_filter" onclick="return ClearSearchAdvance()"  style="float:left; margin-left:10px; margin-right:10px;"/>
                            <a href="javascript:void(0)" class="table_text1" onClick="BackTo('searchdiv1_1','advanceddiv_1')">Back to Standart Search</a>
                        </div>
                    </div>
                    <?php echo $form->end();?>
                </div>
            </div>
        </div>
        <!-- advance search -->
        <div class="line1" id="list_item"></div>
    </div>
</div>