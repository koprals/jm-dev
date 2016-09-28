<?php $arr	=	array(0=>"Users/WaitingEmailConfirm",-2=>"Users/StatusSuspend",-1=>"Users/StatusBlock",1=>"Users/ActiveUser");?>
<div class="test-left">
    <div class="test-sidebar">
        <ul>
        	<?php if(!empty($data)):?>
            <li>
                <div style=" position:relative;width:1000px; top:-20px;">
                    <a href="<?php echo $settings['cms_url']?>Users/Index" class="nav_2">Member</a><span class="text2">&raquo;</span>
                    <a href="<?php echo $settings['cms_url']?>Users/Index" class="nav_2">MemberList</a><span class="text2">&raquo;</span>
                    <a href="<?php echo $settings['cms_url'].$arr[$data['User']['userstatus_id']]?>" class="nav_2"><?php echo $data['Userstatus']['name']?></a><span class="text2">&raquo;</span>
                    <div class="text3">General (<?php echo $data['Profile']['fullname']?>)</div> 
                </div>
               <div style="clear: both;">&nbsp;</div>
            </li>
            <li>
                <h2><?php echo $data['Profile']['fullname']?>'s Information</h2>
                <ul>
                	<?php $general_class	=	($active_code=="general") ? "class='active'" : "";?>
                    <li><a href="<?php echo $settings['cms_url']?>Users/Add/<?php echo $data['User']['id']?>" <?php echo $general_class?>>General</a></li>
                </ul>
            </li>
            <li>
                <h2>Logs</h2>
                <ul>
                	<?php $activity_class	=	($active_code=="activity") ? "class='active'" : "";?>
                    <?php $point_class		=	($active_code=="point") ? "class='active'" : "";?>
                    <?php $email_class		=	($active_code=="email") ? "class='active'" : "";?>
                    
                	<li><a href="<?php echo $settings['cms_url']?>UserLogs/Index/<?php echo $data['User']['id']?>" <?php echo $activity_class?>>User Log</a></li>
                    <li><a href="<?php echo $settings['cms_url']?>PointLog/Index/<?php echo $data['User']['id']?>" <?php echo $point_class?>>Point Log</a></li>
                    <li><a href="<?php echo $settings['cms_url']?>EmailLog/Index/<?php echo $data['User']['id']?>" <?php echo $email_class?>>Email Log</a></li>
                </ul>
            </li>
            <?php else:?>
            <li>
                <div style=" position:relative;width:1000px; top:-20px;">
                    <a href="<?php echo $settings['cms_url']?>Users/Index" class="nav_2">Member</a><span class="text2">&raquo;</span>
                    <div class="text3">Add New Member</div> 
                </div>
               <div style="clear: both;">&nbsp;</div>
            </li>
            <li>
                <h2>Add New Member</h2>
            </li>
            <?php endif;?>
        </ul>
    </div>
</div>
<!-- end #sidebar -->
