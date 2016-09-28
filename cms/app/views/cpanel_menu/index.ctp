<script>
$(document).ready(function(){
	var pos			=	$(".side_center").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+50)});
	$("#loading_gede").show();
	$(".side_center").css("opacity","0.5");
	
	$(".side_center").load("<?php echo $settings['cms_url']?>CpanelMenu/ListMenu",function(){
		$(this).css("opacity","1");
		$("#loading_gede").hide();
	});
});

function onClickPage(el,divName) {
	
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+200)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}
</script>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">
<div class="line2">
	<div class="body_up" >
    	<a href="<?php echo $settings['cms_url']."Product/Index"?>" class="nav_2">CMS Menu</a><span class="text2">&raquo;</span><div class="text3">Home</span></div>
    </div>
    <?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code))?>
    
    <!-- CONTENT -->
    <div class="side_center">
    </div>
    <!-- CONTENT -->
</div>