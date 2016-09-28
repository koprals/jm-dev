<?php if(!empty($data)):?>
<div class="side_left">
    <div class="box_panel">
        <div class="line4" style="border:0px solid black; height:auto; margin-bottom:15px; float:left;">
            <span class="text6"><?php echo ucwords($parent_name)?></span>
        </div>
        <div style="width:98%; margin-left:auto; margin-right:auto; clear: both;">
            <ul class="menu collapsible">
            	<?php foreach($data as $data):?>
                <?php if($data['CmsMenu']['code']==$child_code):?>
                <li><a href="<?php echo $settings['cms_url'].$data['CmsMenu']['url']?>" target="_self" class="active" rel="sideleft"><?php echo $data['CmsMenu']['name']?></a></li>
                <?php else:?>
                 <li><a href="<?php echo $settings['cms_url'].$data['CmsMenu']['url']?>" target="_self" rel="sideleft"><?php echo $data['CmsMenu']['name']?></a></li>
                <?php endif;?>
				<?php endforeach;?>
            </ul>
        </div>
    </div>
</div>
<?php endif;?>