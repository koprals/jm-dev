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
	
	$("#list_item").load("<?php echo $settings['cms_url']?>Pesan/ListItem/2",function(){
		$(this).css("opacity","1");
		$("#loading_gede").hide();
	});
	
	$.cookie('checklist',null);
	$.cookie('action',null);
	Boxy.DEFAULTS.title = 'Title';
	
	//WATERMARK
	$("#SearchId").watermark({watermarkText:'Masukkan id user',watermarkCssClass:'all_search_watermark'});
	$("#SearchEmail").watermark({watermarkText:'Masukkan email user',watermarkCssClass:'all_search_watermark'});
	$("#SearchFullname").watermark({watermarkText:'Masukkan nama user',watermarkCssClass:'all_search_watermark'});
	$("#SearchAddress").watermark({watermarkText:'Masukkan alamat user',watermarkCssClass:'all_search_watermark'});
	$("#SearchPhone").watermark({watermarkText:'Masukkan telepon user',watermarkCssClass:'all_search_watermark'});
	$("#SearchFax").watermark({watermarkText:'Masukkan fax user',watermarkCssClass:'all_search_watermark'});
	$("#SearchPointFrom").watermark({watermarkText:'10',watermarkCssClass:'all_search_watermark'});
	$("#SearchPointTo").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
});

function OpenText(ID)
{
	$.getJSON('<?PHP echo $settings['cms_url']?>Pesan/GetFullText/'+ID,function(data){
		$('#span_'+ID).html(data+'\n<input type="button" value="close" onclick="CloseText(\''+ID+'\')">');	
	});
}
function CloseText(ID)
{
	$.getJSON('<?PHP echo $settings['cms_url']?>Pesan/GetTruncateText/'+ID,function(data){
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
		url:'<?php echo $settings['cms_url']?>Pesan/ListItem/2',
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
		url:'<?php echo $settings['cms_url']?>Pesan/ListItem/2',
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
	$('#reset').val('1');
	SearchItem();
	$("#SearchId").val("");
	$("#SearchEmail").val("");
	$("#SearchFullname").val("");
	$("#SearchTglInput").val("");
	$("input:radio").attr("checked", false);
	$("#SearchStatus").val("");
	$("#SearchAddress").val("");
	$("#SearchPhone").val("");
	$("#SearchFax").val("");
	$("#SearchProvinceId").val("");
	$("#SearchFrom").val("");
	
	
	$("#SearchPointFrom").val("");
	$("#SearchPointTo").val("");
	
	//WATERMARK
	$("#SearchId").watermark({watermarkText:'Masukkan id user',watermarkCssClass:'all_search_watermark'});
	$("#SearchEmail").watermark({watermarkText:'Masukkan email user',watermarkCssClass:'all_search_watermark'});
	$("#SearchFullname").watermark({watermarkText:'Masukkan nama user',watermarkCssClass:'all_search_watermark'});
	$("#SearchAddress").watermark({watermarkText:'Masukkan alamat user',watermarkCssClass:'all_search_watermark'});
	$("#SearchPhone").watermark({watermarkText:'Masukkan telepon user',watermarkCssClass:'all_search_watermark'});
	$("#SearchFax").watermark({watermarkText:'Masukkan fax user',watermarkCssClass:'all_search_watermark'});
	$("#SearchPointFrom").watermark({watermarkText:'10',watermarkCssClass:'all_search_watermark'});
	$("#SearchPointTo").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
}
</script>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code)) ?>

<div class="test-right">
    <div class="content">
        <!-- advance search -->
        <div class="line1" style="margin-bottom:20px;">
            <div class="line1">
                <div class="table1">
                    <div class="line2" style="float:left; width:105px; padding-left:10px;">
                        <b>SEARCH</b>
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
                <?php echo $form->input("msg_editing_required",array("type"=>"hidden","id"=>"MsgEditing"))?>
                <?php echo $form->textarea("notice",array("id"=>"Notice","style"=>"display:none;"))?>
                
                <div class="line1" style="margin:10px; display:none;" id="advanceddiv_1">
                    <div class="left" style="width:31%;border:0px solid black; margin-right:10px">
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Nama</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("from",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text"))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Email</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("email",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text"))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Tgl Input</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("tgl_input",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text"))?>
                            </div>
                        </div>
        			</div>
                    <div class="left" style="width:31%;border:0px solid black; margin-right:10px">
                    	<div class="line1" style="margin-top:15px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Tanggapi</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <div class="text8" style="margin-top:-2px;">
                                    <?php echo $form->radio("response",array("0"=>"Belum","1"=>"Sudah"),array("div"=>false,"label"=>true,"legend"=>false))?>
                                </div>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:15px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Publish</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <div class="text8" style="margin-top:-2px;">
                                    <?php echo $form->radio("publish",array("0"=>"Belum","1"=>"Sudah"),array("div"=>false,"label"=>true,"legend"=>false))?>
                                </div>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Telp</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("phone",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text"))?>
                            </div>
                        </div>
                    </div>
                    <div class="left" style="width:31%;border:0px solid black; margin-right:10px">
                    	<div class="line1" style="margin-top:15px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Isi Pesan</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <div class="text8" style="margin-top:-2px;">
                                    <?php echo $form->textarea("message",array("class"=>"all_search","div"=>false,"label"=>false))?>
                                </div>
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
        <div class="line1" id="list_item"></div>
    </div>
</div>
