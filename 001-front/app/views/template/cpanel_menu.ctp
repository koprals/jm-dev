<?php $parent_id =  0;?>
<?php $count	 =	0;?>
<?php $margin	 =	0;?>
<?php $padding	 =	0;?>

<?php foreach($data as $k => $cpanel):?>
	<?php $count++;?>
    <?php if( $cpanel['CpanelMenu']['parent_id']==1): ?>
		<?php if($cpanel['CpanelMenu']['parent_id']==1 && $parent_id!=0):?>
                </ul>
            </div>
        <?php $margin=10;?>
        <?php endif;?>
        
        <div class="menu_cpanel" style="margin-top:<?php echo $margin?>px">
            <div style="float:left; width:100%;">
            	<img src="<?php echo $settings['site_url'].$cpanel['CpanelMenu']['icon']?>" style="float:left; vertical-align:middle; margin-left:5px; margin-top:-2px; margin-right:5px;"/><?php echo $cpanel['CpanelMenu']['name']?>
            </div>
            <ul class="menu-cpanel">
    <?php else:?>
    	<?php $padding		=	($data[$k+1]['CpanelMenu']['parent_id']!=$cpanel['CpanelMenu']['parent_id']) ? 10 : 0;?>
		<?php $parent_id	=	$cpanel['CpanelMenu']['parent_id'];?>
        <?php $class		=	($active_code == $cpanel['CpanelMenu']['code']) ? 'class="current"' : ""?>
                  <li style="padding-bottom:<?php echo $padding?>px;">
                  	<img src="<?php echo $settings['site_url'].$cpanel['CpanelMenu']['icon']?>" style="float:left; vertical-align:middle; margin-left:5px; margin-top:2px; margin-right:5px;"/>
                    <a href="<?php echo $settings['site_url'].$cpanel['CpanelMenu']['url']?>" <?php echo $class?>><?php echo $cpanel['CpanelMenu']['name']?></a>
                  </li>
    <?php endif;?>
    <?php if($count==count($data)):?>
            </ul>
    </div>
    <?php endif;?>
<?php endforeach;?>