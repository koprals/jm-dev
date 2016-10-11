<script>
$(document).ready(function(){
	$("#contents_area").css("opacity","0.5");
	$("#contents_area").load("<?php echo $settings['cms_url'] . $ControllerName?>/DataValidListItem/page:<?php echo $page?>/limit:<?php echo $viewpage?>/?time=<?php echo time()?>",function(){
		$("#contents_area").css("opacity","1");
		$("a[rel^='lightbox']").prettyPhoto({
			social_tools :''
		});

		$("#view").uniform();
		$('.tipS').tipsy({gravity: 's',fade: true});
	});


  /**DATE PICKER**/
	$( "#SearchStartDate" ).datepicker({
		dateFormat:"yy-mm-dd",
		changeMonth: false,
		changeYear: false,
		//maxDate: "0",
		onSelect: function(){
			$("#SearchEndDate").removeAttr('disabled');
			$("#SearchEndDate").attr('readonly','readonly');
			$( "#SearchEndDate" ).val('');
			$("#SearchEndDate").datepicker( "option", "minDate", new Date($( "#SearchStartDate" ).val()) );
		}
	}).focus(function() {
	  //$(".ui-datepicker-prev, .ui-datepicker-next").remove();
	});

	$( "#SearchEndDate" ).datepicker({
		dateFormat:"yy-mm-dd",
		changeMonth: false,
		changeYear: false,
		//maxDate: "0",
		onSelect: function(){
			var start_date	=	$( "#SearchStartDate" ).val();
			var end_date	=	$( "#SearchEndDate" ).val();
			var diff 		= 	Math.floor(( Date.parse(end_date) - Date.parse(start_date) ) / 86400000)+1;
			if(diff < 0)
			{
				alert("End date must be greater than start date");
				$( "#SearchEndDate" ).val('');
			}
			else if(diff > 31)
			{
				alert("Date range is too big, Only can show 31 days");
				$( "#SearchEndDate" ).val('');
			}else if(diff < 1)
			{
				alert("Date range is too small, minimum date range is 1 days");
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
		$("#view").uniform();
		$('.tipS').tipsy({gravity: 's',fade: true});
	});
	return false;
}
function SearchAdvance()
{

	url = "<?php echo $settings['cms_url'].$ControllerName ?>/DataValidListItem";
  url += '?_=' + (new Date()).getTime();

	$("#SearchAdvance").ajaxSubmit({
		url:url,
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
		}
	});

	return false;
}
function ClearSearchAdvance()
{
	$("#SearchStartDate, #SearchEndDate").val("");
	$('#reset').val('1');
	SearchAdvance();
}
</script>
<!-- HEADER -->
<div class="titleArea">
    <div class="wrapper">
        <div class="pageTitle">
            <h5>Report Data Valid</h5>
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
	                  <a href="javascript:void(0)"><?php echo $ControllerName?></a>
	             </li>
	             
	             <li class="current">
	                  <a href="javascript:void(0)">Report Data Valid</a>
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
			              echo $this->Form->input('Search.start_date', array(
			                'label'			=>	'From',
			                'div'			=>	array("class"=>"dataTables_filter"),
			                'between'		=>	'<div class="formRight"><span class="span3">',
			                'after'			=>	'</span></div>',
			                "style"			=>	"width:100px",
			                'type'			=>	'text',
			                'readonly'		=>	'readonly'
			              ));
						?>
						<?php
			            	echo $this->Form->input('Search.end_date', array(
			                'label'			=>	'To',
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