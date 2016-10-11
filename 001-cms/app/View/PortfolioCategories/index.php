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
	$( "#SearchStartDate" ).datepicker({
		dateFormat:"yy-mm-dd",
		changeMonth: false,
		changeYear: false,
		maxDate: "0",
		onSelect: function(){
			$("#SearchEndDate").removeAttr('disabled');
			$("#SearchEndDate").attr('readonly','readonly');
			$( "#SearchEndDate" ).val('');
		}
	}).focus(function() {
	  //$(".ui-datepicker-prev, .ui-datepicker-next").remove();
	});

	$( "#SearchEndDate" ).datepicker({
		dateFormat:"yy-mm-dd",
		changeMonth: false,
		changeYear: false,
		maxDate: "0",
		onSelect: function(){
			var start_date	=	$( "#SearchStartDate" ).val();
			var end_date	=	$( "#SearchEndDate" ).val();
			var diff 		= 	Math.floor(( Date.parse(end_date) - Date.parse(start_date) ) / 86400000)+1;
			if(diff < 0)
			{
				alert("\"Join Date End\" must be greater than \"Join Date Start\"");
				$( "#SearchEndDate" ).val('');
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
function ClearSearchAdvance()
{
	$("#SearchId, #SearchName, #SearchStartDate, #SearchEndDate").val("");
	$('#reset').val('1');
	$("#SearchEndDate").removeAttr('readonly');
	$("#SearchEndDate").attr('disabled','disabled');
	$("#SearchEndDate" ).val('');
	$.uniform.update();
	SearchAdvance();
}
</script>
<!-- HEADER -->
<div class="titleArea">
    <div class="wrapper">
        <div class="pageTitle">
            <h5><?php echo Inflector::humanize(Inflector::underscore($ModelName))?></h5>
            <span>List</span>
        </div>
    </div>
</div>
<div class="line"></div>
<div class="statsRow">
	<div class="wrapper">
		<div class="controlB">
			<ul>
				<li>
					<a href="<?php echo $settings["cms_url"].$ControllerName?>/Add" title="Add New <?php echo Inflector::humanize(Inflector::underscore($ModelName))?>">
						<img src="<?php echo $this->webroot?>img/icons/control/32/plus.png" alt="" />
					<span>Add new row</span></a>
				</li>
			</ul>
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
	                  <a href="javascript:void(0)"><?php echo Inflector::humanize(Inflector::underscore($ModelName))?></a>
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
													'label'			=>	'ID',
	                    		'div'				=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
													"style"			=>	"width:50px"
	                    	));
						?>
						<?php
	                    	echo $this->Form->input('Search.name', array(
													'label'			=>	'Name',
	                    		'div'				=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
													"style"			=>	"width:100px"
	                    	));
						?>
					</fieldset>
					<fieldset>
						<?php
	                    	echo $this->Form->input('Search.start_date', array(
													'label'			=>	'Created From',
	                    		'div'				=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
													"style"			=>	"width:100px",
													'type'			=>	'text',
													'readonly'	=>	'readonly'
	                    	));
						?>
						<?php
	                    	echo $this->Form->input('Search.end_date', array(
													'label'			=>	'Created End',
	                    		'div'				=>	array("class"=>"dataTables_filter"),
	                    		'between'		=>	'<div class="formRight"><span class="span3">',
	                    		'after'			=>	'</span></div>',
													"style"			=>	"width:100px",
													'disabled'	=>	'disabled'
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
