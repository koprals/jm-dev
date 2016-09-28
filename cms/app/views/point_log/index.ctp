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
	
	$("#list_item").load("<?php echo $settings['cms_url']?>PointLog/ListItem/<?php echo $user_id?>",function(){
		$(this).css("opacity","1");
		$("#loading_gede").hide();
	});
	
	$.cookie('checklist',null);
	$.cookie('action',null);
	Boxy.DEFAULTS.title = 'Title';
	
	//WATERMARK
	$("#SearchId").watermark({watermarkText:'Masukkan id point log',watermarkCssClass:'all_search_watermark'});
	$("#SearchActionText").watermark({watermarkText:'Telah login',watermarkCssClass:'all_search_watermark'});
	$("#SearchValueFrom").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
	$("#SearchValueTo").watermark({watermarkText:'1000',watermarkCssClass:'all_search_watermark'});
	$("#SearchBeforeFrom").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
	$("#SearchBeforeTo").watermark({watermarkText:'1000',watermarkCssClass:'all_search_watermark'});
	$("#SearchAfterFrom").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
	$("#SearchAfterTo").watermark({watermarkText:'1000',watermarkCssClass:'all_search_watermark'});
});

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
		url:'<?php echo $settings['cms_url']?>PointLog/ListItem/<?php echo $user_id?>',
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
		url:'<?php echo $settings['cms_url']?>PointLog/ListItem/<?php echo $user_id?>',
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
	SearchItem()
	
	$("#SearchId").val("");
	$("#SearchActionText").val("");
	$("#SearchTglInput").val("");
	$("#SearchActionID").val("");
	$("#SearchValueFrom").val("");
	$("#SearchValueTo").val("");
	$("#SearchBeforeFrom").val("");
	$("#SearchBeforeTo").val("");
	$("#SearchAfterFrom").val("");
	$("#SearchAfterTo").val("");
	
	//WATERMARK
	$("#SearchId").watermark({watermarkText:'Masukkan id point log',watermarkCssClass:'all_search_watermark'});
	$("#SearchActionText").watermark({watermarkText:'Telah login',watermarkCssClass:'all_search_watermark'});
	$("#SearchValueFrom").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
	$("#SearchValueTo").watermark({watermarkText:'1000',watermarkCssClass:'all_search_watermark'});
	$("#SearchBeforeFrom").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
	$("#SearchBeforeTo").watermark({watermarkText:'1000',watermarkCssClass:'all_search_watermark'});
	$("#SearchAfterFrom").watermark({watermarkText:'100',watermarkCssClass:'all_search_watermark'});
	$("#SearchAfterTo").watermark({watermarkText:'1000',watermarkCssClass:'all_search_watermark'});
}
</script>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<?php echo $this->requestAction('/Template/UserLeftMenu/'.$user_id."/point",array('return'))?>
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
                               <span class="text9">Action</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                 <?php echo $form->select("actionID",$actionID,false,array("style"=>"width:87%;float:left","class"=>"text8","label"=>"false","escape"=>false,"empty"=>'Action'));?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Text</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("actionText",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text"))?>
                            </div>
                        </div>
                    </div>
                    <div class="left" style="width:31%;border:0px solid black; margin-right:10px">
                   		<div class="line1">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Value</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("value_from",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                                <span style="float:left; margin:0 4px 0 3px" class="text8"> s/d </span>
                                <?php echo $form->input("value_to",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                            </div>

                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Point Before</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("before_from",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                                <span style="float:left; margin:0 4px 0 3px" class="text8"> s/d </span>
                                <?php echo $form->input("before_to",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                            </div>
                        </div>
                        <div class="line1" style="margin-top:10px;">
                            <div class="left" style="width:30%;border:0px solid black; margin-right:3px">
                               <span class="text9">Point After</span> 
                            </div>
                            <div class="left" style="width:68%;border:0px solid black;">
                                <?php echo $form->input("after_from",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
                                <span style="float:left; margin:0 4px 0 3px" class="text8"> s/d </span>
                                <?php echo $form->input("after_to",array("class"=>"all_search","div"=>false,"label"=>false,"type"=>"text","maxlength"=>4,"style"=>"width:63px;padding:0 0 0 5px;margin:0;float:left"))?>
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
        		</div>
                <?php echo $form->end();?>
        	</div>
        </div>
    	<div class="line1" id="list_item"></div>
    </div>
</div>