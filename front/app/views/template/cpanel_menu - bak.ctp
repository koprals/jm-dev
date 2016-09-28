<?php echo $html->css('accordion')?>
<?php $parent_id=0;?>
<?php $count	=	0;?>
<?php foreach($data as $cpanel):?>
<?php $count++;?>
<?php if( $cpanel['CpanelMenu']['parent_id']==1): ?>
<?php if($cpanel['CpanelMenu']['parent_id']==1 && $parent_id!=0):?>
		</ul>
    </div>
</div>
<?php endif;?>
<div class="box_panel" style="min-height:50px; margin-bottom:10px;">
	<div class="line4">
    	<span class="text3"><?php echo $cpanel['CpanelMenu']['name']?></span>
    </div>
	<div style="width:98%; margin-left:auto; margin-right:auto; padding-bottom:10px;">
    	<ul class="menu collapsible">
<?php else:?>
<?php $parent_id	=	$cpanel['CpanelMenu']['parent_id'];?>
<?php $class		=	($active_code == $cpanel['CpanelMenu']['code']) ? 'class="active"' : ""?>
        	<li>
            	<a href="<?php echo $settings['site_url'].$cpanel['CpanelMenu']['url']?>" target="_self" <?php echo $class?> >
                	<?php echo $cpanel['CpanelMenu']['name']?>
                </a>
            </li>
<?php endif;?>
<?php if($count==count($data)):?>
        </ul>
    </div>
</div>
<?php endif;?>
<?php endforeach;?>