<?php echo $javascript->link("jquery-ui-1.7.1.custom.min")?>
<?php echo $javascript->link("daterangepicker.jQuery")?>

<?php echo $html->css("ui.daterangepicker")?>
<?php echo $html->css("redmond/jquery-ui-1.7.1.custom.css")?>
<?php echo $html->css("tab")?>
<?php echo $html->css("menu_baba")?>
<?php echo $html->css("boxy")?>
<script>
$(document).ready(function(){
	//$("#transaction_data").load("<?php echo $settings['site_url']?>Point/TransactionHistoryList");
	
	$(".tabs").find("li").each(function(i){
		if(i==<?php echo $tab_active?>)
		{
			$(this).addClass("active").show();
			$("#transaction_data").load("<?php echo $settings['site_url']?>Point/TransactionHistoryList/"+$(this).attr("rel")+"/1",function(){
				$("span[id^='sum_']").show();
			});
			$("#tab_active").val($(this).attr("rel"));
		}
	});
	
	$("ul.tabs li").click(function() {					   
		var pos			=	$("#transaction_data").offset();
		var leftpos		=	pos.left;
		var toppos		=	pos.left;
		var status		=	$(this).attr('rel');
		
		$("#keywords").val("");
		$("#loading_gede").css({left:(leftpos+300),top:(toppos)});
		$("#loading_gede").show();
		$("#transaction_data").css("opacity","0.5");
		
		$("#transaction_data").load("<?php echo $settings['site_url']?>Point/TransactionHistoryList/"+status+"/1",function(){
			$("#transaction_data").css("opacity","1");
			$("#loading_gede").hide();
		});
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$("#tab_active").val(status);
		return false;
	});
	
	var sumdata;
	$.getJSON("<?php echo $settings['site_url']?>Point/GetSumStatus/100",function(data){
		sumdata	=	(data!==null) ? data : "0";
		$("#sum_100").html("("+sumdata+")");
	});
	<?php foreach($status as $id=>$name):?>
	$.getJSON("<?php echo $settings['site_url']?>Point/GetSumStatus/<?php echo $id?>",function(data){
		sumdata	=	(data!==null) ? data : "0";
		$("#sum_<?php echo $id?>").html("("+sumdata+")");
	});
	<?php endforeach;?>
	
	//DATE PICKER
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
});

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

function SearchAdvance()
{
	var pos			=	$("#transaction_data").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#SearchAdvance").ajaxSubmit({
		url:'<?php echo $settings['site_url']?>Point/TransactionHistoryList/'+$("#tab_active").val()+'/0',
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
			$("#transaction_data").html(data);
			$("#reset").val(0);
		}
	});
	return false;
}
function ClearSearchAdvance()
{
	var pos			=	$("#transaction_data").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#SearchTglInput").val("");
	$("#invoice_id").val("");
	$("#voucher_id").val("");
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+100)});
			$("#loading_gede").show();
			$(".side_center").css("opacity","0.5");
	$("#transaction_data").load('<?php echo $settings['site_url']?>Point/TransactionHistoryList/'+$("#tab_active").val()+'/1',function(){
	$("#loading_gede").hide();
			$(".side_center").css("opacity","1");
	});
}

</script>
<div id="output"></div>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<div class="line" style="width:805px;">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">Transaction History</div>
        </div>
	</div>
</div>
<div class="line top20" style="border:0px solid black;">
	<div class="divcontainer" style="border:0px solid black;">
        <ul class="tabs">
            <li rel="100"><a href="javascript:void(0)">All<span id="sum_100" style="display:none">(0)</span></a></li>
            <?php foreach($status as $id=>$name):?>
            <li rel="<?php echo $id?>"><a href="javascript:void(0)"><?php echo $name?><span id="sum_<?php echo $id?>" style="display:none">(0)</span></a></li>
            <?php endforeach;?>
        </ul>
        <div class="tab_container">
       		<div id="tab1" class="tab_content">
				<?php echo $form->create("Search",array("id"=>"SearchAdvance","onsubmit"=>"return SearchAdvance()"))?>
				<input name="tab_active" type="hidden" class="all_input3" value="100" id="tab_active">
				<div class="line top10 size100" style="border:0px solid black;" id="advanceddiv_1">
                    <div class="kiri size315 right15" style="border:0px solid black;">
                        <div class="line size100">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                              Tgl Pembelian
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <?php echo $form->input("tgl_input",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text","readonly"=>true))?>
                            </div>
                        </div>
					</div>
					<div class="kiri size315 right15" style="border:0px solid black;">
						<div class="line size100">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                               Invoice ID
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <?php echo $form->input("invoice_id",array("class"=>"input6","div"=>false,"label"=>false,"type"=>"text","id"=>"invoice_id"))?>
                            </div>
                        </div>
					</div>
					<div class="kiri size315" style="border:0px solid black;">
                        <div class="line size100">
                            <div class="kiri size30 right5 white style1 text11 bold" style="border:0px solid black;">
                              Voucher
                            </div>
                            <div class="kiri size66" style="border:0px solid black;">
                                <select name="data[Search][voucher_id]" class="input5 style1 black text11 size100 kiri" id="voucher_id">
                                    <option value="">Voucher</option>
                                	<?php foreach($voucher as $k=>$v):?>
                                    <option value="<?php echo $k?>"><?php echo $v?></option>
                                	<?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
					<div class="line top20">
                        <input type="submit" name="button" value="Search" class="tombol1" style="margin-left:0px"/>
                        <input type="button" name="reset" value="Clear" class="tombol1" onclick="return ClearSearchAdvance()"/>
                    </div>
				</div>
				<?php echo $form->end();?>
			</div>
		</div>
		<div class="line top10" id="transaction_data" style="width:805px;"></div>
	</div>
</div>
