<?php echo $html->css('/js/resources/css/ext-custom.css'); ?>
<?php echo $javascript->link('/js/ext-custom.js'); ?>


<script>
$(document).ready(function(){
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+50)});
	$("#loading_gede").show();
	$("#list_item").css("opacity","0.5");
	
	$("#list_item").load("<?php echo $settings['cms_url']?>Menu/ListMenu",function(){
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
<?php echo $this->element('side_left',array('child_code'=>$child_code,'parent_code'=>$parent_code))?>
<div class="test-right">
	<div class="content">
    	<div class="line1" id="list_item"></div>
    </div>
</div>

<script type="text/javascript">

Ext.BLANK_IMAGE_URL = '<?php echo $settings['cms_url'].'js/resources/images/default/s.gif' ?>';

Ext.onReady(function(){

	var getnodesUrl = '<?php echo $settings['cms_url'].'Menu/getnodes' ?>';
	var reorderUrl = '<?php echo $settings['cms_url'].'Menu/reorder/' ?>';
	var reparentUrl = '<?php echo $settings['cms_url'].'Menu/reparent/' ?>';
	
	var Tree = Ext.tree;
	
	var tree = new Tree.TreePanel({
		el:'tree-div',
		autoScroll:true,
		animate:true,
		enableDD:true,
		containerScroll: true,
		rootVisible: true,
		loader: new Ext.tree.TreeLoader({
			dataUrl:getnodesUrl
		})
	});
	
	var root = new Tree.AsyncTreeNode({
		text:'Menu',
		draggable:false,
		id:'root'
	});
	tree.setRootNode(root);
	
	
	// track what nodes are moved and send to server to save
	
	var oldPosition = null;
	var oldNextSibling = null;
	
	tree.on('startdrag', function(tree, node, event){
		oldPosition = node.parentNode.indexOf(node);
		oldNextSibling = node.nextSibling;
		
	});
	
	tree.on('click', function(tree, node, event){
		var pos			=	$("#list_item").offset();
		var leftpos		=	pos.left;
		var toppos		=	pos.left;
		
		$("#loading_gede").css({left:(leftpos+350),top:(toppos+50)});
		$("#loading_gede").show();
		$("#list_item").css("opacity","0.5");
		
		$("#list_item").load("<?php echo $settings['cms_url']?>Menu/ListMenu",{'data[Search][parent_id]':tree.id},function(){
			$(this).css("opacity","1");
			$("#loading_gede").hide();
		});
	});
	
	tree.on('movenode', function(tree, node, oldParent, newParent, position){
	
		if (oldParent == newParent){
			var url = reorderUrl;
			var params = {'node':node.id, 'delta':(position-oldPosition)};
		} else {
			var url = reparentUrl;
			var params = {'node':node.id, 'parent':newParent.id, 'position':position};
		}
		
		// we disable tree interaction until we've heard a response from the server
		// this prevents concurrent requests which could yield unusual results
		
		tree.disable();
		
		Ext.Ajax.request({
			url:url,
			params:params,
			success:function(response, request) {
			
				// if the first char of our response is not 1, then we fail the operation,
				// otherwise we re-enable the tree
				var pos			=	$("#list_item").offset();
				var leftpos		=	pos.left;
				var toppos		=	pos.left;
				
				$("#loading_gede").css({left:(leftpos+350),top:(toppos+50)});
				$("#loading_gede").show();
				$("#list_item").css("opacity","0.5");
				
				$("#list_item").load("<?php echo $settings['cms_url']?>Menu/ListMenu",function(){
					$(this).css("opacity","1");
					$("#loading_gede").hide();
				});
				
				if (response.responseText.charAt(0) != 1){
					request.failure();
				} else {
					tree.enable();
				}
			},
			failure:function() {
			
				// we move the node back to where it was beforehand and
				// we suspendEvents() so that we don't get stuck in a possible infinite loop
				
				tree.suspendEvents();
				oldParent.appendChild(node);
				if (oldNextSibling){
					oldParent.insertBefore(node, oldNextSibling);
				}
				
				tree.resumeEvents();
				tree.enable();
				
				alert("Oh no! Your changes could not be saved!");
			}
		
		});
	
	});
	
	// render the tree
	tree.render();
	root.expand();

});

</script>
