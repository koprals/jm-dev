<?php echo $javascript->link("jquery-1.7.1.min.js")?>
<?php echo $html->css("style_JM")?>
<?php echo $javascript->link("jquery.tools.min")?>
<style>
	/* override the root element to enable scrolling */
	.flowpanes {
		position:relative;
		overflow:hidden;
		clear:both;
		height:173px;
		border:1px solid black;
		float:left;
		width:100%;
		margin-top:5px;
	}
	/* our additional wrapper element for the items */
	.flowpanes .items {
		width:20000em;
		position:absolute;
		clear:both;
		margin:0;
		padding:0;
		
	}
	
</style>
<div class="kiri size30">
    <div class="line" style="border:0px solid black; margin-top:16px;">
        <!-- YAMAHA MURAH -->
        <div id="carousel_yamaha">
            <?php echo $this->element('home_carousel',array("category_name"=>"yamaha"))?>
        </div>
        <!-- YAMAHA MURAH -->
        
    </div>
</div>