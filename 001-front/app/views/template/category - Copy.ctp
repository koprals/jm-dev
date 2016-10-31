<?php echo $html->css('accordion')?>
<?php echo $javascript->link('menu')?>
<div class="box_panel">
	<div class="line4">
    	<span class="text3">Categories</span>
    </div>
	<div style="width:98%; margin-left:auto; margin-right:auto;">
        <?php
			echo $tree->generate($stuff,array('model' => 'Category','link'=>$settings['site_url']."Product/ListContent/","current_id"=>$current_id,'ROOT'=>$settings['site_url'],'class'=>'menu collapsible'));
		?>
	</div>
</div>