<div class="main" style="height:78px;">
    <div style="float:right; margin-right:10px;">
    	<a href="<?php echo $settings['cms_url']?>AccessAdmin/Logout" class="table_text1" style="text-decoration:none;">Logout</a>
    </div>
</div>
<div class="menu-area">
	<div class="menu_header">
        <ul>
        	<?php foreach($data as $data):?>
            <?php if($data['CmsMenu']['code'] == $parent_code):?>
            	<li><a href="<?php echo $settings['cms_url'].$data['CmsMenu']['url']?>" class="curent_head"><?php echo $data['CmsMenu']['name']?></a></li>
            <?php else:?>
            	<li><a href="<?php echo $settings['cms_url'].$data['CmsMenu']['url']?>" class="menulink"><?php echo $data['CmsMenu']['name']?></a></li>
            <?php endif;?>
           <?php endforeach;?>
        </ul>
    </div>
    <div style="float:left; display:block; width:100%; background-color: #7D704F; height:10px; margin-top:-20px;"></div>
</div>