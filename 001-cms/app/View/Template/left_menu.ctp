
<ul id="menu" class="nav">
    <?php foreach($menu as $menu):
		$current	=	($menu["CmsMenu"]["id"] == $lft_menu_category_id ) ? "active" : "";
		$expand		=	(!empty($menu["CmsSubmenu"])) ? " exp " : "";
    $current  = ($menu["CmsMenu"]["id"] == $lft_menu_category_id ) ? "current" : "";
	?>
		<li class="<?php echo $menu["CmsMenu"]["class"]?>">

			<a href="<?php echo $this->webroot . $menu["CmsMenu"]["url"]?>" title="<?php echo $menu["CmsMenu"]["name"]?>" class="<?php echo $current.$expand?>" id="<?php echo $current?>"><span><?php echo $menu["CmsMenu"]["name"]?></span>
				<?php
					if(!empty($menu["CmsSubmenu"])) {
						echo "<strong>".count($menu['CmsSubmenu'])."</strong>";
					}
				?>
			</a>

			<?php if(!empty($menu["CmsSubmenu"])):?>
				<ul class="sub">
					<?php foreach($menu["CmsSubmenu"] as $Submenu):?>
						<li class="last">
							<a href="<?php echo $this->webroot . $Submenu["url"]?>" title="<?php echo $Submenu["name"]?>"><?php echo $Submenu["name"]?></a>
						</li>
					<?php endforeach;?>
				</ul>
			<?php endif;?>
		</li>
	<?php endforeach;?>
</ul>
