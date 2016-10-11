<?php echo $this->start("script");?>
<script>
	$(document).ready(function() {
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
            <h5>Edit <?php echo Inflector::humanize(Inflector::underscore($ModelName));?></h5>
            <span><?php echo $detail[$ModelName]["name"]?></span>
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
		<div class="users form span10">
			<?php echo $this->Form->create($ModelName, array("type"=>"file",'url' => array("controller"=>$ControllerName,"action"=>"Edit", $ID,$page,$viewpage),'class' => 'form','novalidate')); ?>
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
							<h6>Edit <?php echo $detail[$ModelName]["name"]?></h6>
						</div>

						<?php
							echo $this->Form->input('portfolio_category_id', array(
								'label'				=>	'Category (*)',
								'div' 				=>	'formRow',
								'between'			=>	'<div class="formRight"><div class="span3">',
								'after' 			=>	'</div><div class="span2"><a href="'.$settings['cms_url'].'PortfolioCategories/Add" style="margin-left:10px;" target="_blank">[create new]</a></div></div>',
								'error' 			=>	array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"				=>	"Select Category",
								'options' 		=>	$category_id_list
							));
						?>

						<?php
							echo $this->Form->input('name', array(
								'label'					=>	'Name (*)',
								'div' 					=> 'formRow',
								'between'				=> '<div class="formRight"><span class="span4">',
								'after' 				=> '</span></div>',
								"required"			=>	"",
								"autocomplete"	=>	"off",
								"maxlength"			=>	80,
								'error' 				=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>

						<?php
							echo $this->Form->input('description', array(
								'label'			=>	'Description',
								'div' 			=>	'formRow',
								'between'		=>	'<div class="formRight"><span class="span8">',
								'after' 		=>	'</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"type"			=>	"textarea",
								"rows"			=>	"7",
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
