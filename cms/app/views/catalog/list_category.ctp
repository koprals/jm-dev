<script>
$(document).ready(function(){
	$("#ajax").load("<?php echo $settings['cms_url']?>Catalog/ListCategoryAjax");
});
</script>
<div id="ajax">
</div>