<?php echo $this->start("script");?>
<script src="<?php echo $this->webroot?>wysiwyg/minified/jquery.sceditor.bbcode.min.js"></script>
<script>
	var loadCSS = function(url, callback){
		var link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = url;
		link.id = 'theme-style';

		document.getElementsByTagName('head')[0].appendChild(link);

		var img = document.createElement('img');
		img.onerror = function(){
			if(callback) callback(link);
		}
		img.src = url;
	}
	
	$(document).ready(function() {
		var initEditor = function() {
			$("textarea").sceditor({
				plugins: 'bbcode',
				style: "<?php echo $this->webroot?>wysiwyg/minified/jquery.sceditor.default.min.css"
			});
		};
	
		$("#theme").change(function() {
			var theme = "<?php echo $this->webroot?>wysiwyg/minified/themes/" + $(this).val() + ".min.css";
	
			$("textarea").sceditor("instance").destroy();
			$("link:first").remove();
			$("#theme-style").remove();
	
			loadCSS(theme, initEditor);
		});
		initEditor();
	});
	
</script>
<?php echo $this->end();?>

<?php echo $this->start("css");?>
<link rel="stylesheet" href="<?php echo $this->webroot?>wysiwyg/minified/themes/default.min.css" type="text/css" media="all" />
<?php echo $this->end();?>



<!-- Title area -->
<div class="titleArea">
    <div class="wrapper">
        <div class="pageTitle">
            <h5>Add Module Object</h5>
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
		<div class="users form span8">
			<?php echo $this->Form->create($ModelName, array('url' => array("controller"=>$ControllerName,"action"=>"Add"),'class' => 'form',"type"=>"file")); ?>
				<fieldset>
					<div class="widget">
						<div class="title">
							<img src="<?php echo $this->webroot ?>img/icons/dark/list.png" alt="" class="titleIcon" />
							<h6>Add new <?php echo strtolower($ModelName)?></h6>
						</div>
						<?php $ModelName?>
						<?php
							echo $this->Form->input('alias', array(
								'label'			=>	'Modul Name (*)',
								'div' 			=>	'formRow',
								'between'		=>	'<div class="formRight"><span class="span4">',
								'after' 		=>	'</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	30,
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('parent_id', array(
								'label'			=> 'Parent (*)',
								'div'			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span5">',
								'after'			=> '</span></div>',
								'error'			=> array('attributes' => array('wrap' => 'label', 'class' =>  'formRight error')),
								'options'		=> $parent_id_list,
								"required"		=>	"",
								"empty"			=>	"Select Parent"
								));
						?>
						<?php
							echo $this->Form->input('description', array(
								'label'			=>	'Description',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight">',
								'after' 		=> '</div>',
								"rows"			=>	"20",
								"cols"			=>	"12",
								"required"		=>	"",
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
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
						
						<div class="formSubmit">
							<input type="submit" value="Add" class="redB" />
							<input type="reset" value="Reset" class="blueB"/>
							<input type="button" value="Cancel" class="blackB" onclick="location.href = '<?php echo $settings["cms_url"].$ControllerName?>/Index'"/>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>