<?php echo $this->start("script");?>
<script src="<?php echo $this->webroot?>wysiwyg/minified/jquery.sceditor.bbcode.min.js"></script>
<script>
	$(document).ready(function() {
		/**DATE PICKER**/
		$( "#MaidBirthDate" ).datepicker({
			dateFormat	:	"yy-mm-dd",
			changeMonth	:	true,
			changeYear	:	true,
			yearRange	:	"<?php echo date("Y") - 40?>:<?php echo date("Y") - 15?>",
			onSelect	:	function(){
			
			}
		}).focus(function() {
		});
		
		/**DATE PICKER**/
		$( "#MaidActiveDate" ).datepicker({
			dateFormat	:	"yy-mm-dd",
			changeMonth	:	true,
			changeYear	:	true,
			maxDate		:	new Date("<?php echo date("Y")?>","<?php echo date("m")-1?>","<?php echo date("d")?>"),
			onSelect	:	function(){
			
			}
		}).focus(function() {
		});
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
            <h5>Add <?php echo $ModelName?></h5>
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
			<?php echo $this->Form->create($ModelName, array('url' => array("controller"=>$ControllerName,"action"=>"Add"),'class' => 'form',"type"=>"file","novalidate")); ?>
				<fieldset>
					<div class="widget">
						<div class="title">
							<img src="<?php echo $this->webroot ?>img/icons/dark/list.png" alt="" class="titleIcon" />
							<h6>Add new <?php echo strtolower($ModelName)?></h6>
						</div>
						<?php
							echo $this->Form->input('name', array(
								'label'			=>	'Nama Lengkap (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span4">',
								'after' 		=> '</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	30,
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('birth_date', array(
								'label'			=>	'Tgl Lahir (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span4">',
								'after' 		=> '</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	30,
								"type"			=>	"text",
								"readonly"		=>	"readonly",
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('gender', array(
								'label'			=> 'Jenis Kelamin (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span4">',
								'after' 		=> '</span></div>',
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"			=> "Jenis Kelamin",
								'options' 		=> array("male"=>"Pria","female"=>"Wanita")
							));
						?>
						<?php
							echo $this->Form->input('origin', array(
								'label'			=>	'Daerah Asal (*)',
								'div' 			=>	'formRow',
								'between'		=>	'<div class="formRight"><span class="span4">',
								'after' 		=>	'</span>&nbsp;contoh: Klaten, Ciamis, Semarang dll</div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	120,
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('address', array(
								'label'			=>	'Alamat Saat Ini (*)',
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
							echo $this->Form->input('mobile_phone', array(
								'label'			=>	'No HP (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight"><span class="span4">',
								'after' 		=> '</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	30,
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('marital_status_id', array(
								'label'			=> 'Status Perkawinan (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight">',
								'after' 		=> '</div>',
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
								"empty"			=> "Pilih status perkawinan",
								'options' 		=> $marital_status_id_list
							));
						?>
						<?php
							echo $this->Form->input('active_date', array(
								'label'			=>	'Tgl aktif Kerja (*)',
								'div' 			=>	'formRow',
								'between'		=>	'<div class="formRight"><span class="span4">',
								'after' 		=>	'</span></div>',
								"required"		=>	"",
								"autocomplete"	=>	"off",
								"maxlength"		=>	30,
								"type"			=>	"text",
								"readonly"		=>	"readonly",
								'error' 		=> array('attributes' => array('wrap' => 'label', 'class' => 'formRight error')),
							));
						?>
						<?php
							echo $this->Form->input('images', array(
								'label'			=> 'Images (*)',
								'div' 			=> 'formRow',
								'between'		=> '<div class="formRight">',
								'after' 		=> '&nbsp;(Width: 300px, Height: 300px)</div>',
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