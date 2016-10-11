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
            <h5>Edit <?php echo $ModelName?></h5>
            <span><?php echo $detail[$ModelName]["name"]?></span>
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