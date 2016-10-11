<div class="cLine"></div>

<div class="smalldd">
	<span class="goTo"><img src="<?php echo $this->webroot ?>img/icons/light/home.png" alt="" />Dashboard</span>
	<ul class="smallDropdown">
		
		<?php foreach($menu as $menu):
			$current	=	($menu["CmsMenu"]["id"] == $lft_menu_category_id ) ? "class='active'" : "";
			$expand		=	(!empty($menu["CmsSubmenu"])) ? 'class="exp"' : "";
		?>
		<li>
			<a href="<?php echo $this->webroot . $menu["CmsMenu"]["url"]?>" title="<?php echo $menu["CmsMenu"]["name"]?>" <?php echo $expand?>>
				<img src="<?php echo $this->webroot ?>img/icons/light/list.png" alt="" />
				<?php echo $menu["CmsMenu"]["name"]?>
				<?php
					if(!empty($menu["CmsSubmenu"])) {
						echo "<strong>".count($menu['CmsSubmenu'])."</strong>";
					}
				?>
			</a>
			
			<?php if(!empty($menu["CmsSubmenu"])):?>
				<ul>
					
					<?php foreach($menu["CmsSubmenu"] as $k	=> $Submenu):?>
					<?php  $last	=	($k == count($menu["CmsSeubmenu"])-1) ? 'class="last"' : '';?>
					<li <?php echo $last?>>
						<a href="<?php echo $this->webroot . $Submenu["url"]?>" title="<?php echo $Submenu["name"]?>">
							<?php echo $Submenu["name"]?>
						</a>
					</li>
					<?php endforeach;?>
				</ul>
			<?php endif;?>
		</li>
		<?php endforeach;?>
	</ul>
</div>
<div class="cLine"></div>