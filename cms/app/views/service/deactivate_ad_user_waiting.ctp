<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code))?>
<div class="test-right">
    <div class="content">
    	<?php if(!empty($error)):?>
            <div class="alert">
                <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
                <?php echo $error?>
            </div>
    	<?php else:?>
        	<div class="alert_success">
            	 <img src="<?php echo $this->webroot?>img/check.png" style=" vertical-align:middle;"/>
            	<?php echo $status_msg?>
            </div>
        <?php endif;?>
	</div>
</div>