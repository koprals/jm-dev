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
            <h5>Edit <?php echo $ControllerName?></h5>
            <span><?php echo $detail[$ModelName]["fullname"]?></span>
        </div>
        <div class="middleNav">
	        <ul>
				<li class="mUser"><a href="<?php echo $settings["cms_url"].$ControllerName ?>" title="View List"><span class="list"></span></a></li>
	        </ul>
	    </div>
    </div>
</div>
<div class="line"></div>

<div class="wrapper">
	<div class="fluid">
		<div class="users form span8">
			<?php echo $this->Form->create($ModelName, array("type"=>"file",'url' => array("controller"=>$ControllerName,"action"=>"Edit", $ID,$page,$viewpage),'class' => 'form')); ?>
				<?php
					echo $this->Form->input('id', array(
						'type'			=>	'hidden',
						'readonly'		=>	'readonly'
					));
				?>
				<fieldset>
					<div class="widget">
						<div class="title">
							<img src="<?php echo $this->webroot ?>img/icons/dark/list.png" alt="" class="titleIcon" />
							<h6>Edit <?php echo $detail[$ModelName]["fullname"]?></h6>
						</div>
						
						<?php
							echo $this->Form->input('username', array(
								'label'			=>	'Username (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span4">',
								'after' 		=> '</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	20,
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('password', array(
								'label'			=> 'Password (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span4">',
								'after' 		=> '</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	16,
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('aro_id', array(
								'label'			=> 'Admin Group(*)',
								'div'			=> 'formRow',
								'between'		=> '<div class="formRight">',
								'after'			=> '</div>',
								'error'			=> array('attributes' => array('wrap' => 'label', 'class' =>  'formRight error')),
								'options'		=> $aro_id_list,
								"required"		=>	"",
								"empty"			=>	"Select Admin Group"
								));
						?>
						
						<?php
							echo $this->Form->input('fullname', array(
								'label'			=> 'Fullname (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span6">',
								'after' 		=> '</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	20,
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						
						<?php
							echo $this->Form->input('images', array(
								'label'			=> 'Images (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight">',
								'after' 		=> '&nbsp;(Width: 300px, Height: 300px)<br>
								<a rel="lightbox" href="'.$detail["Image"]["host"].$detail["Image"]["url"].'?time='.time().'" title="'.$detail[$ModelName]["fullname"].'">
								<img src="'.$detail["Image"]["host"].$detail["Image"]["url"].'?time='.time().'" width="50" style="margin-top:10px;"></a>
								</div>',
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"type"			=>	"file",
								"required"		=>	""
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
							<input type="submit" value="Edit" class="redB" />
							<input type="reset" value="Reset" class="blueB"/>
							<input type="button" value="Cancel" class="blackB" onclick="location.href = '<?php echo $settings["cms_url"].$ControllerName?>/Index/<?php echo $page?>/<?php echo $viewpage?>'"/>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>