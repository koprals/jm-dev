<!-- Title area -->
<div class="titleArea">
    <div class="wrapper">
        <div class="pageTitle">
            <h5>Success add new <?php echo $ControllerName?></h5>
            <span><?php echo $this->Html->link('Index', array('action' => 'index')); ?></span>
        </div>
        <div class="middleNav">
	        <ul>
				<li class="mUser"><a href="<?php echo $this->webroot.$ControllerName ?>" title="View Spg"><span class="list"></span></a></li>
	            
	        </ul>
	    </div>
    </div>
</div>

<div class="line"></div>
<div class="wrapper">
	<div class="nNote nSuccess">
		<p><strong>SUCCESS: </strong>Success add new <?php echo strtolower($ControllerName)?></p>
	</div>
	<div class="widget">
		<div class="body textC">
			<a href="<?php echo $settings["cms_url"].$ControllerName?>/Edit/<?php echo $ID?>" title="Back to Edit" class="button redB" style="margin: 5px;"><span>Edit this <?php echo strtolower($ControllerName)?></span></a>
			<a href="<?php echo $settings["cms_url"].$ControllerName?>/Add" title="Back to List" class="button greyishB" style="margin: 5px;"><span>Add More</span></a>
			<a href="<?php echo $settings["cms_url"].$ControllerName?>/Index" title="Back to List" class="button blueB" style="margin: 5px;"><span>Back to List</span></a>
		</div>
	</div>
</div>
