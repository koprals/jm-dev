<div class="test-left">
    <div class="test-sidebar">
        <ul>
            <li>
                <div style=" position:relative;width:1000px; top:-20px;">
                    <?php echo $breadcrumb?>
                </div>
               <div style="clear: both;">&nbsp;</div>
            </li>
            <li>
                <h2><?php echo ucwords($parent_name)?></h2>
                <?php
					echo $tree->generate($data,array('model' => 'CmsMenu','link'=>$settings['cms_url']."Product/ListContent/","current_id"=>$current_id,'ROOT'=>$settings['cms_url']));
				?>
            </li>
        </ul>
    </div>
    <div id="tree-div" style="height:400px;">&nbsp;</div>
</div>
<!-- end #sidebar -->