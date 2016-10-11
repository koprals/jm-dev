<?php echo $this->start('header');?>
<!-- HEADER -->
<div class="topNav">
    <div class="wrapper">
        <div class="userNav">
            <ul>
                <li>
					<a href="#">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</a>
				</li>
            </ul>
        </div>
    </div>
</div>
<!-- HEADER -->
<?php echo $this->end();?>

<div class="loginWrapper" >
	<div class="loginLogo" style="margin-left:-75px; margin-top:-80px;">
		<img src="<?php echo $this->webroot?>img/client_logo.png" alt="" width="150"/>
	</div>
    <div class="widget" style="height:auto;">
        <div class="title">
			<img src="<?php echo $this->webroot?>img/icons/dark/files.png" alt="files" class="titleIcon" />
			<h6>SIGN IN</h6>
		</div>
		<?php echo $this->Form->create("Admin",array("url"=>array("controller"=>"Account","action"=>"Login","?"=>"debug=0"),"class"=>"form"));?>
            <fieldset>
				 <?php echo $this->Form->input("username",
					array(
						"div"			=>	array("class"=>"formRow"),
						"label"			=>	"Username",
						"between"		=>	'<div class="loginInput">',
						"after"			=>	"</div>",
						"autocomplete"	=>	"off",
						"type"			=>	"text"
					)
				)?>
                <?php echo $this->Form->input("password",
					array(
						"div"			=>	array("class"=>"formRow"),
						"label"			=>	"Password",
						"between"		=>	'<div class="loginInput">',
						"after"			=>	"</div>",
						"autocomplete"	=>	"off",
						"type"			=>	"password"
					)
				)?>
				<div class="body textC">
					<input class="wButton redwB ml15 m10" value="Log me in" type="submit" />
				</div>
            </fieldset>
		<?php echo $this->Form->end()?>
    </div>
</div>
<div id="footer">
    <div class="wrapper">
      <a href="<?php echo $settings['web_url']?>" title="<?php echo $settings['web_url']?>" target="_blank">
        <?php echo $settings['site_name']?>
      </a>
    </div>
</div>
