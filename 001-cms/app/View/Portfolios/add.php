<?php echo $this->start("script");?>
<script>
	var totalImage			=	0;
	var counterImage		=	0;

	var totalFeature		=	0;
	var counterFeature	=	0;

	$(document).ready(function() {
			$( "#PortfolioStartDevelopment" ).datepicker({
				dateFormat:"yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				maxDate: "0",
				onSelect: function(){
				}
			}).focus(function() {
			});

			$( "#PortfolioEndDevelopment" ).datepicker({
				dateFormat:"yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				maxDate: "0",
				onSelect: function(){
				}
			}).focus(function() {
			});

			<?php if(!empty($this->data)):?>
			GetSubcategory("<?php echo $this->data[$ModelName]['portfolio_category_id']?>","<?php echo $this->data[$ModelName]['portfolio_subcategory_id']?>");
			<?php endif;?>

			/*********TAB MENU*************/
			$("#tabMenu" ).tabs();
			/*********TAB MENU*************/

			/***** IMAGE *****/
			<?php if(!empty($this->request->data)):?>
			<?php foreach($this->request->data[$ModelName]["photo"] as $k => $photo):?>
			AddImage('<?php echo $errorImg[$k]?>');
			<?php endforeach;?>
			<?php endif;?>
			/***** IMAGE *****/

			/***** FEATURE *****/
			<?php if(!empty($this->request->data)):?>
			<?php foreach($this->request->data["PortfolioFeature"] as $k => $PortfolioFeature):?>
			AddFeature('<?php echo $PortfolioFeature['name']?>','<?php echo $errorFeature[$k]?>');
			<?php endforeach;?>
			<?php endif;?>
			/***** FEATURE *****/
	});

	function GetSubcategory(portfolio_category_id,default_id)
	{
		var optionData	=	'<option value="">Loading..</option>';
		$("#PortfolioPortfolioSubcategoryId").html(optionData);
		$.uniform.update("#PortfolioPortfolioSubcategoryId");

		if(portfolio_category_id != "")
		{
			$.getJSON('<?php echo $settings['cms_url'].$ControllerName?>/GetSubcategory',{'portfolio_category_id':portfolio_category_id},function(result){
				var optionData	=	'<option value="">Select Subcategory</option>';
				if(result.data.length > 0)
				{
					$.each(result.data,function(key,value){
						var id		=	value.PortfolioSubcategory.id;
						var name	=	value.PortfolioSubcategory.name;

						if(default_id == id)
							optionData	+=	'<option value="'+id+'" selected="selected">'+name+'</option>';
						else
							optionData	+=	'<option value="'+id+'">'+name+'</option>';
					});
				}
				$("#PortfolioPortfolioSubcategoryId").html(optionData);
				$.uniform.update("#PortfolioPortfolioSubcategoryId");
			});

			//ANDROID
			if(portfolio_category_id == "1")
			{
				$("#crossBrowserDiv").hide('300');
				$("#mobileSupportDiv").hide('300');
				$("#applestoreDiv").hide('300');
				$("#playstoreDiv").show('300');
				$("#multipleScreenDiv").show('300');
				$("#screenOrientationDiv").show('300');

				//MULTIPLE SCREEN
				$("#multipleScreenDiv").find("input:radio").each(function(el){
					if($(this).attr("value") == "1")
					{
						$(this).prop("checked",true);
					}
				});

				//SCREEN ORIENTATION
				$("#screenOrientationDiv").find("input:radio").each(function(el){
					if($(this).attr("value") == "0")
						$(this).prop("checked",true);
				});

				//PLAYSTORE
				$("#playstoreDiv").find("input:radio").each(function(el){
					if($(this).attr("value") == "1")
						$(this).prop("checked",true);
				});

				//APPLESTORE
				$("#applestoreDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				//CROSS BROWSER
				$("#crossBrowserDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				//MOBILE SUPPORT
				$("#mobileSupportDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				$.uniform.update();
			}
			//IPHONE(IOS)
			else if(portfolio_category_id == "2")
			{
				$("#crossBrowserDiv").hide('300');
				$("#mobileSupportDiv").hide('300');
				$("#applestoreDiv").show('300');
				$("#playstoreDiv").hide('300');
				$("#multipleScreenDiv").show('300');
				$("#screenOrientationDiv").show('300');

				//MULTIPLE SCREEN
				$("#multipleScreenDiv").find("input:radio").each(function(el){
					if($(this).attr("value") == "1")
					{
						$(this).prop("checked",true);
					}
				});

				//SCREEN ORIENTATION
				$("#screenOrientationDiv").find("input:radio").each(function(el){
					if($(this).attr("value") == "0")
						$(this).prop("checked",true);
				});

				//PLAYSTORE
				$("#playstoreDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				//APPLESTORE
				$("#applestoreDiv").find("input:radio").each(function(el){
						if($(this).attr("value") == "1")
							$(this).prop("checked",true);
				});

				//CROSS BROWSER
				$("#crossBrowserDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				//MOBILE SUPPORT
				$("#mobileSupportDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				$.uniform.update();
			}
			//WEBSITE
			else if(portfolio_category_id == "3")
			{
				$("#crossBrowserDiv").show('300');
				$("#mobileSupportDiv").show('300');
				$("#applestoreDiv").hide('300');
				$("#playstoreDiv").hide('300');
				$("#multipleScreenDiv").hide('300');
				$("#screenOrientationDiv").hide('300');

				//MULTIPLE SCREEN
				$("#multipleScreenDiv").find("input:radio").each(function(el){
					$(this).prop("checked",false);
				});

				//SCREEN ORIENTATION
				$("#screenOrientationDiv").find("input:radio").each(function(el){
					$(this).prop("checked",false);
				});

				//PLAYSTORE
				$("#playstoreDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				//APPLESTORE
				$("#applestoreDiv").find("input:radio").each(function(el){
						$(this).prop("checked",false);
				});

				//CROSS BROWSER
				$("#crossBrowserDiv").find("input:radio").each(function(el){
					if($(this).attr("value") == "1")
						$(this).prop("checked",true);
				});

				//MOBILE SUPPORT
				$("#mobileSupportDiv").find("input:radio").each(function(el){
					if($(this).attr("value") == "1")
						$(this).prop("checked",true);
				});

				$.uniform.update();
			}
		}
		else
		{
			var optionData	=	'<option value="">Select Subcategory</option>';
			$("#PortfolioPortfolioSubcategoryId").html(optionData);
			$.uniform.update("#PortfolioPortfolioSubcategoryId");

			$("#crossBrowserDiv").show('300');
			$("#mobileSupportDiv").show('300');
			$("#applestoreDiv").show('300');
			$("#playstoreDiv").show('300');
			$("#multipleScreenDiv").show('300');
			$("#screenOrientationDiv").show('300');

			//MULTIPLE SCREEN
			$("#multipleScreenDiv").find("input:radio").each(function(el){
				$(this).prop("checked",false);
			});

			//SCREEN ORIENTATION
			$("#screenOrientationDiv").find("input:radio").each(function(el){
				$(this).prop("checked",false);
			});

			//PLAYSTORE
			$("#playstoreDiv").find("input:radio").each(function(el){
					$(this).prop("checked",false);
			});

			//APPLESTORE
			$("#applestoreDiv").find("input:radio").each(function(el){
					$(this).prop("checked",false);
			});

			//CROSS BROWSER
			$("#crossBrowserDiv").find("input:radio").each(function(el){
				$(this).prop("checked",false);
			});

			//MOBILE SUPPORT
			$("#mobileSupportDiv").find("input:radio").each(function(el){
					$(this).prop("checked",false);
			});

			$.uniform.update();
		}
	}



	function AddFeature(featureName,errorFeature)
	{
		if(counterFeature	<	6)
		{
			var b	=	'\
				<div class="formRow" id="uploadFormFeature'+totalFeature+'">\
					<label id="labelFeature'+totalFeature+'"></label>\
					<div class="formRight">\
						<span class="span4">\
							<input id="FeaturePos'+totalFeature+'" type="hidden"  name="data[PortfolioFeature]['+totalFeature+'][pos]" value="'+totalFeature+'">\
							<input id="inputFeature'+totalFeature+'" type="text" autocomplete="off" name="data[PortfolioFeature]['+totalFeature+'][name]" value="'+featureName+'">\
						</span>\
						<a href="javascript:void(0);" onclick="javascript:$(\'#uploadFormFeature'+totalFeature+'\').remove();counterFeature--;ReorderLableFeature();if(counterFeature==0){$(\'#submitFeature\').hide();$(\'#cancelFeature\').hide()}" class="tipS smallButton" title="Delete" style="margin-left:10px;padding: 5px 7px;">\
							<img src="<?php echo $this->webroot?>img/icons/color/cross.png" alt="Delete"/>\
						</a>\
					</div>\
					<label class="formRight error" id="errorFeature'+totalFeature+'">'+errorFeature+'</label>\
				</div>\
			';
			$("#submitFeature").show();
			$("#cancelFeature").show();
			$("#featureLyt").append(b);
			$("#inputFeature"+totalImage).uniform();
			totalFeature++;
			counterFeature++;
			ReorderLableFeature();
		}
		else
		{
			alert("Maximum feature is 6");
		}
	}

	function AddImage(errorImg)
	{
		if(counterImage<10)
		{
			var b	=	'\
			<div class="formRow" id="uploadForm'+totalImage+'">\
				<label id="labelImages'+totalImage+'"></label>\
				<div class="formRight">\
					<div style="float:left; display:block;">\
						<a rel="lightbox" href="<?php echo $this->webroot?>img/default_content_property.png" id="lighbox'+totalImage+'" title="Image - '+(totalImage+1)+'"><img width="70" height="70" src="<?php echo $this->webroot?>img/default_content.png" id="previewImg'+totalImage+'"></a>\
					</div>\
					<div style="float:left; display:block; margin-left:10px;width:260px;">\
						<input type="file" style="float: left; display: block; opacity: 0;" name="data[<?php echo $ModelName?>][photo][]" size="25" onchange="PreviewImage(\'file'+totalImage+'\',\'previewImg'+totalImage+'\',\'#lighbox'+totalImage+'\')" id="file'+totalImage+'">\
						<div style="float:left; display:block;">(Width: 500px, Height: 500px)</div>\
						<div style="float:left; display:block;color:#a73939; width:100%;">'+errorImg+'\
						</div>\
					</div>\
					<a href="javascript:void(0);" onclick="javascript:$(\'#uploadForm'+totalImage+'\').remove();counterImage--;ReorderLable();if(counterImage==0){$(\'#submitImage\').hide();$(\'#cancelImage\').hide()}" class="tipS smallButton" title="Delete" style="margin-left:10px;padding: 5px 7px;">\
						<img src="<?php echo $this->webroot?>img/icons/color/cross.png" alt="Delete"/>\
					</a>\
				</div>\
			</div>\
			';
			$("#submitImage").show();
			$("#cancelImage").show();
			$("#image-lyt").append(b);
			$("#file"+totalImage).uniform();
			totalImage++;
			counterImage++;
			ReorderLable();
			$("a[rel^='lightbox']").prettyPhoto({
				social_tools :''
			});
		}
		else
		{
			alert("Maximum photos is 10");
		}
	}

	function ReorderLableFeature()
	{
		$("#featureLyt").find("label[id^=labelFeature]").each(function(k,item){
			$(this).html("Feature - "+(k+1));
		});

		/*$("#featureLyt").find("label[id^=errorFeature]").each(function(k,item){
			var errorString	=	$(this).html();
			var errorSplit	=	errorString.split("-");

			if(errorSplit.length > 1)
				$(this).html(errorSplit[0]+" - "+(k+1));
		});*/

		$("#featureLyt").find("input[id^=inputFeature]").each(function(k,item){
			$(this).attr("name","data[PortfolioFeature]["+k+"][name]");
		});

		$("#featureLyt").find("input[id^=FeaturePos]").each(function(k,item){
			$(this).attr("name","data[PortfolioFeature]["+k+"][pos]");
			$(this).attr("value",k);
		});

	}

	function ReorderLable()
	{
		$("#image-lyt").find("label[id^=labelImages]").each(function(k,item){
			$(this).html("Image - "+(k+1));
		});
	}

	function PreviewImage(fileId,imageId,lighbox) {
		if ( window.FileReader && window.File && window.FileList && window.Blob )
		{
			var oFReader = new FileReader();
			oFReader.readAsDataURL(document.getElementById(fileId).files[0]);
			oFReader.onload = function (oFREvent) {
				document.getElementById(imageId).src = oFREvent.target.result;
				$(lighbox).attr("href",oFREvent.target.result);
			};
		}
	};
</script>
<?php echo $this->end();?>

<?php echo $this->start("css");?>
<link rel="stylesheet" href="<?php echo $this->webroot?>wysiwyg/minified/themes/default.min.css" type="text/css" media="all" />
<?php echo $this->end();?>


<!-- Title area -->
<div class="titleArea">
    <div class="wrapper">
	    <div class="pageTitle">
	        <h5>Add New <?php echo Inflector::humanize(Inflector::underscore($ModelName))?></h5>
	    </div>
	    <div class="middleNav">
				<ul>
					<li class="mUser">
						<a href="<?php echo $settings["cms_url"].$ControllerName ?>" title="View List"><span class="list"></span></a>
					</li>
				</ul>
	  	</div>
    </div>
</div>
<div class="line"></div>
<div class="wrapper">
	<div class="fluid">
		<div class="span12">
			<?php
			echo $this->Session->flash();
			?>
			<div class="widget">
				<div class="title">
					<img src="<?php echo $this->webroot ?>img/icons/dark/list.png" alt="" class="titleIcon" />
					<h6>Add <?php echo Inflector::humanize(Inflector::underscore($ModelName))?></h6>
				</div>
				<div id="tabMenu">
					<ul>
						<li><a href="#tabs-1">Information</a></li>
						<li><a href="#tabs-2">Photos</a></li>
						<li><a href="#tabs-3">Feature</a></li>
					</ul>

					<?php echo $this->Form->create($ModelName, array('url' => array("controller"=>$ControllerName,"action"=>"Add","?"=>"debug=0"),'class' => 'form',"type"=>"file","novalidate")); ?>
					<div id="tabs-1">
						<?php
							echo $this->Form->input('title', array(
								'label'					=>	'Title (*)',
								'div' 					=> 'formRow',
								'between'				=> '<div class="formRight"><span class="span4">',
								'after' 				=> '</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								'error' 				=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>

						<?php
							echo $this->Form->input('description', array(
								'label'					=>	'Description',
								'div' 					=>	'formRow',
								'between'				=>	'<div class="formRight"><span class="span8">',
								'after' 				=>	'</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								"type"					=>	"textarea",
								"rows"					=>	"7",
								'error' 				=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('portfolio_category_id', array(
								'label'				=>	'Category (*)',
								'div' 				=>	'formRow',
								'between'			=>	'<div class="formRight">',
								'after' 			=>	'</div>',
								'error' 			=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"				=>	"Select Category",
								'options' 		=>	$category_id_list,
								"onchange"		=>	"GetSubcategory(this.value,'')"
							));
						?>
						<?php
							echo $this->Form->input('portfolio_subcategory_id', array(
								'label'				=>	'Sub Category (*)',
								'div' 				=>	'formRow',
								'between'			=>	'<div class="formRight">',
								'after' 			=>	'</div>',
								'error' 			=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"				=>	"Select Sub Category"
							));
						?>
						<?php
							echo $this->Form->input('client_id', array(
								'label'				=>	'Client (*)',
								'div' 				=>	'formRow',
								'between'			=>	'<div class="formRight">',
								'after' 			=>	'</div>',
								'error' 			=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"				=>	"Select Client",
								'options' 		=>	$client_id_list
							));
						?>
						<?php
							echo $this->Form->input('user_target_id', array(
								'label'				=>	'Select User Target (*)',
								'div' 				=>	'formRow',
								'between'			=>	'<div class="formRight">',
								'after' 			=>	'</div>',
								'error' 			=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"				=>	"User Target",
								'options' 		=>	$user_target_id_list
							));
						?>
						<?php
							echo $this->Form->input('start_development', array(
								'label'					=>	'Start Development',
								'div' 					=>	'formRow',
								'between'				=>	'<div class="formRight"><span class="span2">',
								'after' 				=>	'</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								"maxlength"			=>	80,
								"type"					=>	"text",
								'error' 				=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('end_development', array(
								'label'					=>	'End Development',
								'div' 					=>	'formRow',
								'between'				=>	'<div class="formRight"><span class="span2">',
								'after' 				=>	'</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								"maxlength"			=>	80,
								"type"					=>	"text",
								'error' 				=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('multiple_screen', array(
								'div' 				=>	array('class'=>'formRow','id'=>'multipleScreenDiv'),
								'before'			=>	'<label>Multiple Screen</label><div class="formRight">',
								"type"				=>	"radio",
								'after'				=>	'</div>',
								'options' 		=>	array("0"=>"No","1"=>"Yes"),
								"legend"			=>	false
							));
						?>
						<?php
							echo $this->Form->input('screen_orientation', array(
								'div' 				=>	array('class'=>'formRow','id'=>'screenOrientationDiv'),
								'before'			=>	'<label>Screen Orientation</label><div class="formRight">',
								"type"				=>	"radio",
								'after'				=>	'</div>',
								'options' 		=>	array("0"=>"Portrait","1"=>"Landscape","2"=>"Portrait &amp; Landscape"),
								"legend"			=>	false
							));
						?>
						<?php
							echo $this->Form->input('last_version', array(
								'label'					=>	'Last Version',
								'div' 					=>	'formRow',
								'between'				=>	'<div class="formRight"><span class="span2">',
								'after' 				=>	'</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								"type"					=>	"text",
								'error' 				=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('online_data', array(
								'div' 				=>	'formRow',
								'before'			=>	'<label>Online Data</label><div class="formRight">',
								"type"				=>	"radio",
								'after'				=>	'</div>',
								'options' 		=>	array("0"=>"No","1"=>"Yes"),
								"default"			=>	"1",
								"legend"			=>	false
							));
						?>
						<?php
							echo $this->Form->input('playstore', array(
								'div' 				=>	array('class'=>'formRow','id'=>'playstoreDiv'),
								'before'			=>	'<label>Available on Play Store</label><div class="formRight">',
								"type"				=>	"radio",
								'after'				=>	'</div>',
								'options' 		=>	array("0"=>"No","1"=>"Yes"),
								"legend"			=>	false
							));
						?>
						<?php
							echo $this->Form->input('applestore', array(
								'div' 				=>	array('class'=>'formRow','id'=>'applestoreDiv'),
								'before'			=>	'<label>Available on Apple Store</label><div class="formRight">',
								"type"				=>	"radio",
								'after'				=>	'</div>',
								'options' 		=>	array("0"=>"No","1"=>"Yes"),
								"legend"			=>	false
							));
						?>
						<?php
							echo $this->Form->input('cross_browser', array(
								'div' 				=>	array('class'=>'formRow','id'=>'crossBrowserDiv'),
								'before'			=>	'<label>Cross Browser</label><div class="formRight">',
								"type"				=>	"radio",
								'after'				=>	'</div>',
								'options' 		=>	array("0"=>"No","1"=>"Yes"),
								"legend"			=>	false
							));
						?>
						<?php
							echo $this->Form->input('mobile_support', array(
								'div' 				=>	array('class'=>'formRow','id'=>'mobileSupportDiv'),
								'before'			=>	'<label>Mobile Support</label><div class="formRight">',
								"type"				=>	"radio",
								'after'				=>	'</div>',
								'options' 		=>	array("0"=>"No","1"=>"Yes"),
								"legend"			=>	false
							));
						?>
						<?php
							echo $this->Form->input('demo_url', array(
								'label'					=>	'Demo Url',
								'div' 					=> 'formRow',
								'between'				=> '<div class="formRight"><span class="span4">',
								'after' 				=> '</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								'error' 				=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('live_url', array(
								'label'					=>	'Live Url',
								'div' 					=> 'formRow',
								'between'				=> '<div class="formRight"><span class="span4">',
								'after' 				=> '</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								'error' 				=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>

						<?php
							echo $this->Form->input('status', array(
								'label'			=> 'Status (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight">',
								'after' 		=> '</div>',
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"			=> false,
								"default"		=> "1",
								'options' 		=> array("0"=>"Not Active","1"=>"Active")
							));
						?>
						<div class="formSubmit" style="float:left;">
							<input type="submit" value="Save" class="redB" />
							<input type="button" value="Cancel" class="blackB" onclick="location.href = '<?php echo $settings["cms_url"].$ControllerName?>/Index'"/>
							<input type="button" value="Back to top" class="greenB" onclick="$(document).scrollTo('.titleArea', 300);"/>
						</div>
					</div>

					<div id="tabs-2">
						<div id="image-lyt">
						</div>
						<a href="javascript:void(0);" title="Add Images" class="button blueB" style="color:white;" onclick="AddImage('')">
							<img src="<?php echo $this->webroot?>img/icons/light/add.png" alt="" class="icon" />
							<span>Add Images</span>
						</a>
						<input type="submit" value="Save" class="redB" id="submitImage" style="display:none;"/>
						<input type="button" value="Cancel" class="blackB" id="cancelImage" style="display:none;" onclick="location.href = '<?php echo $settings["cms_url"].$ControllerName?>/Index'"/>
					</div>

					<div id="tabs-3">
						<div id="featureLyt">
						</div>

						<a href="javascript:void(0);" title="Add Feature" class="button blueB" style="color:white;" onclick="AddFeature('','')">
							<img src="<?php echo $this->webroot?>img/icons/light/add.png" alt="" class="icon" />
							<span>Add Feature</span>
						</a>
						<input type="submit" value="Save" class="redB" id="submitFeature" style="display:none;"/>
						<input type="button" value="Cancel" class="blackB" id="cancelFeature" style="display:none;" onclick="location.href = '<?php echo $settings["cms_url"].$ControllerName?>/Index'"/>
					</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
