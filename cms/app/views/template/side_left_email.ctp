<?php $arr = array(0=>array('Inbox','Users/Add/'),1=>array('Sent Box','Sent Box'),2=>array('Trash','Sent Box'))?>

<div class="side_left">
    <div class="box_panel">
        <div class="line4" style="border:0px solid black; height:auto; margin-bottom:15px; float:left;">
            <span class="text6">Email</span>
        </div>
        <div style="width:98%; margin-left:auto; margin-right:auto; clear: both;">
            <ul class="menu collapsible">
               <?php foreach($arr as $k=>$v):?>
               <?php $class	=	($k==$active) ? 'class="active"' : ""?>
               <li>
                    <a href="<?php echo $settings['cms_url'].$v[1]."/".$user_id?>" target="_self" <?php echo $class?>>
                        <?php echo $v[0]?>
                    </a>
               </li>
               <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>