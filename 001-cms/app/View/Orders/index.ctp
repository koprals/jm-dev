<script src="<?php echo $this->webroot?>js/autoNumeric-1.9.18.js"></script>
<script>
$(document).ready(function(){
	$("#contents_area").css("opacity","0.5");
	$("#contents_area").load("<?php echo $settings['cms_url'] . $ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage?>/?time=<?php echo time()?>",function(){
		$("#contents_area").css("opacity","1");
		$("a[rel^='lightbox']").prettyPhoto({
			social_tools :''									
		});
		
		$("#view, input:checkbox, #action").uniform();
		$('.tipS').tipsy({gravity: 's',fade: true});
	});
	
	/**DATE PICKER**/
	$( "#SearchDateOrderStart" ).datepicker({
		dateFormat:"yy-mm-dd",
		changeMonth: false,
		changeYear: false,
		onSelect: function(){
			$("#SearchDateOrderEnd").removeAttr('disabled');
			$("#SearchDateOrderEnd").attr('readonly','readonly');
			$("#SearchDateOrderEnd" ).val('');
			$("#SearchDateOrderEnd").datepicker( "option", "minDate", new Date($( "#SearchDateOrderStart" ).val()) );
		}
	}).focus(function() {
	  //$(".ui-datepicker-prev, .ui-datepicker-next").remove();
	});
	
	$( "#SearchDateOrderEnd" ).datepicker({
		dateFormat:"yy-mm-dd",
		changeMonth: false,
		changeYear: false,
		onSelect: function(){
			var start_date	=	$( "#SearchDateOrderStart" ).val();
			var end_date	=	$( "#SearchDateOrderEnd" ).val();
			var diff 		= 	Math.floor(( Date.parse(end_date) - Date.parse(start_date) ) / 86400000)+1;
			if(diff < 0)
			{
				alert("\"Order created to\" must be less than \"Order created from\"");
				$( "#SearchDateOrderEnd" ).val('');
			}
		}
	}).focus(function() {
	 // $(".ui-datepicker-prev, .ui-datepicker-next").remove();
	});
});

function onClickPage(el,divName)
{
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("a[rel^='lightbox']").prettyPhoto({
			social_tools :''									
		});
		$("#view, input:checkbox, #action").uniform();
		$('.tipS').tipsy({gravity: 's',fade: true});
	});
	return false;
}
function SearchAdvance()
{
	$("#SearchAdvance").ajaxSubmit({
		url:"<?php echo $settings['cms_url'].$ControllerName ?>/ListItem",
		type:'POST',
		dataType: "html",
		clearForm:false,
		beforeSend:function()
		{
			$("#reset").val("0");
			$("#contents_area").css("opacity","0.5");
		},
		complete:function(data,html)
		{
			$("#contents_area").css("opacity","1");
		},
		error:function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert(textStatus);
		},
		success:function(data)
		{
			$("#contents_area").html(data);
			$("#view, input:checkbox, #action").uniform();
		}
	});
	
	return false;
}
function GetDistrict(city_id)
{
	$("#SearchDistrictId").html('<option value="">Loading..</option>');
	$.uniform.update("#SearchDistrictId");
	
	$("#SearchVillageId").html('<option value="">Pilh Kelurahan</option>');
	$.uniform.update("#SearchVillageId");
		
	$.getJSON('<?php echo $settings['cms_url'].$ControllerName?>/GetDistrict',{'city_id':city_id},function(result){
			
		var optionData	=	'<option value="">Pilih Kecamatan</option>';
		
		if(result.data.length > 0)
		{
			$.each(result.data,function(key,value){
				var id		=	value.District.id;
				var name	=	value.District.name;
				optionData	+=	'<option value="'+id+'">'+name+'</option>';
			});
		}
		$("#SearchDistrictId").html(optionData);
		$.uniform.update("#SearchDistrictId");
	});
}

function GetVillage(district_id)
{
	$("#SearchVillageId").html('<option value="">Loading..</option>');
	$.uniform.update("#SearchVillageId");
	
	$.getJSON('<?php echo $settings['cms_url'].$ControllerName?>/GetVillage',{'district_id':district_id},function(result){
			
		var optionData	=	'<option value="">Pilih Kelurahan</option>';
		
		if(result.data.length > 0)
		{
			$.each(result.data,function(key,value){
				var id		=	value.Village.id;
				var name	=	value.Village.name;
				optionData	+=	'<option value="'+id+'">'+name+'</option>';
			});
		}
		$("#SearchVillageId").html(optionData);
		$.uniform.update("#SearchVillageId");
	});
}

function ClearSearchAdvance()
{
	$("#SearchId, #SearchFullname, #SearchEmail, #SearchStatus, #SearchDateOrderStart, #SearchDateOrderEnd").val("");
	$('#reset').val('1');
	$.uniform.update();
	$("#SearchDateOrderEnd").removeAttr('readonly');
	$("#SearchDateOrderEnd").attr('disabled','disabled');
	$("#SearchDateOrderEnd" ).val('');
	SearchAdvance();
	
}
</script>
<!-- HEADER -->
<div class="titleArea">
    <div class="wrapper">
        <div class="pageTitle">
            <h5><?php echo $ModelName?></h5>
            <span>List</span>
        </div>
    </div>
</div>
<div class="line"></div>
<!-- HEADER -->

<!-- CONTENT -->
<div class="wrapper">
	<!-- START SEARCH  -->
	<div class="span6">
		<div class="bc">
	        <ul id="breadcrumbs" class="breadcrumbs">
	             <li>
	                  <a href="javascript:void(0)"><?php echo $ModelName?></a>
	             </li>
	             <li class="current">
	                  <a href="javascript:void(0)">List</a>
	             </li>
	        </ul>
	    </div>
		<div class="toggle" style="border-color:#a0a0a0;">
			<div class="title closed" id="toggleOpened" style="border-color:#a0a0a0;">
				<img src="<?php echo $this->webroot?>img/icons/dark/magnify.png" alt="" class="titleIcon"/>
				<h6 class="red">Search</h6>
			</div>
			<div class="body" style="border-color:#a0a0a0;">
				<?php echo $this->Form->create("Search",array("onsubmit"=>"return SearchAdvance()","url"=>"","id"=>"SearchAdvance"))?>
					<input name="data[Search][reset]" type="hidden" value="0" id="reset">
					<fieldset>
						<?php
	                    	echo $this->Form->input('Search.id', array(
								'label'			=>	'Order ID',
	                    		'div'			=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
								"style"			=>	"width:50px"
	                    	));
						?>
						<?php
	                    	echo $this->Form->input('Search.fullname', array(
								'label'			=>	'Customer Name',
	                    		'div'			=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
								"style"			=>	"width:100px"
	                    	));
						?>
						<?php
	                    	echo $this->Form->input('Search.email', array(
								'label'			=>	'Customer Email',
	                    		'div'			=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
								"style"			=>	"width:100px",
								"type"			=>	"text"
	                    	));
						?>
						<?php
	                    	echo $this->Form->input('Search.status', array(
								'label'			=>	'Order Status',
	                    		'div'			=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
								"empty"			=>	"Select Order Status",
								"options"		=>	$order_status_id_list
	                    	));
						?>
					</fieldset>
					
					<fieldset>
						<?php
	                    	echo $this->Form->input('Search.date_order_start', array(
								'label'			=>	'Order Created From',
	                    		'div'			=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
								"style"			=>	"width:100px",
								'type'			=>	'text',
								'readonly'		=>	'readonly'
	                    	));
						?>
						<?php
	                    	echo $this->Form->input('Search.date_order_end', array(
								'label'			=>	'Order Created To',
	                    		'div'			=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
								"style"			=>	"width:100px",
								'disabled'		=>	'disabled'
	                    	));
						?>
					</fieldset>
				<?php echo $this->Form->end()?>
				<a href="javascript:void(0);" title="" class="wButton bluewB ml15 m10" onclick="return SearchAdvance();"><span>Search</span></a>
				<a href="javascript:void(0);" title="" class="wButton redwB ml15 m10" onclick="ClearSearchAdvance();"><span>Reset</span></a>
			</div>
		</div>
	</div>
	<!-- END SEARCH  -->
	<div id="contents_area">
	</div>
</div>
<!-- CONTENT -->