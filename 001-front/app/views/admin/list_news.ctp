<?php echo $javascript->link("jquery.boxy")?>
<?php echo $html->css("boxy")?>
<script>
$(document).ready(function(){
	$("#list_item").load("<?php echo $settings['site_url']?>Admin/ListItemNews",function(){
		$("#list_item").css("opacity","1");
		$("#loading_gede").hide();
	});
});
</script>

<div id="output"></div>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="line1">List News</div>
        </div>
		<div class="line" id="list_item">
		</div>
	</div>
</div>