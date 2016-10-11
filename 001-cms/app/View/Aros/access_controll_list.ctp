<!-- HEADER -->
<div class="titleArea">
    <div class="wrapper">
        <div class="pageTitle">
            <h5>Module Object</h5>
            <span>Access Controll List</span>
        </div>
    </div>
</div>
<div class="line"></div>

<!-- CONTENT -->
<div class="wrapper">
	<?php
	echo $this->Session->flash();
	?>
	<div class="span6">
		<div class="bc">
	        <ul id="breadcrumbs" class="breadcrumbs">
	             <li>
	                  <a href="javascript:void(0)" style="cursor:default">Module Object</a>
	             </li>
	             <li>
	                  <a href="javascript:void(0)" style="cursor:default">Access Controll List</a>
	             </li>
				 <li class="current">
	                  <a href="javascript:void(0)" style="cursor:default"><?php echo str_replace("_"," ",$detailAro[$ModelName]["alias"])?></a>
	             </li>
	        </ul>
	    </div>
		<?php echo $this->Form->create("AroAcos", array('url' => array("controller"=>$ControllerName,"action"=>"AccessControllList",$ID,"?"=>"debug=1"),'class' => 'form')); ?>
		<div class="widget">
			<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable">
				<thead>
					<tr>
						<td style="width:20%;">&nbsp;
							
						</td>
						<td style="width:16%;">
							<label style="font-weight:bold; cursor:pointer"><input type="checkbox" id="parent_col_1"/>View</label>
						</td>
						<td style="width:16%;">
							<label style="font-weight:bold; cursor:pointer"><input type="checkbox" id="parent_col_2"/>Add</label>
						</td>
						<td style="width:16%;">
							<label style="font-weight:bold; cursor:pointer"><input type="checkbox" id="parent_col_3"/>Edit</label>
						</td>
						<td style="width:16%;">
							<label style="font-weight:bold; cursor:pointer"><input type="checkbox" id="parent_col_4"/>Delete</label>
						</td>
						<td style="width:16;">
							<label style="font-weight:bold; cursor:pointer"><input type="checkbox" id="parent_col_5"/>All</label>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php $count=0;?>
					<?php foreach($data as $data):?>
					<?php $count++;?>
					<?php $style = ($data["MyAco"]["parent_id"] == $top["MyAco"]["id"]) ? "style='background-color:#e3e3e3'" : "";?>
					<?php $bold = ($data["MyAco"]["parent_id"] == $top["MyAco"]["id"]) ? "style='font-weight:bold;'" : "";?>
					<tr <?php echo $style?>>
						<td <?php echo $bold?>><?php echo $data["tree_prefix"].$data["MyAco"]["alias"]?></td>
						<td>
							<?php echo $this->Form->input("AroAco.".$data["MyAco"]["id"]."._read",array(
								"name"		=>	"data[AroAco][".$data["MyAco"]["id"]."][_read]",
								"type"		=>	"checkbox",
								"col"		=>	"col_1",
								"row"		=>	"row_".$count,
								"id"		=>	"chk_".$count."_1",
								"label"		=>	false,
								"div"		=>	false,
								"required"	=>	""
							));?>
							
						</td>
						<td>
							<?php echo $this->Form->input("AroAco.".$data["MyAco"]["id"]."._create",array(
								"name"		=>	"data[AroAco][".$data["MyAco"]["id"]."][_create]",
								"type"		=>	"checkbox",
								"col"		=>	"col_2",
								"row"		=>	"row_".$count,
								"id"		=>	"chk_".$count."_2",
								"label"		=>	false,
								"div"		=>	false,
								"required"	=>	""
							));?>
						</td>
						<td>
							<?php echo $this->Form->input("AroAco.".$data["MyAco"]["id"]."._update",array(
								"name"		=>	"data[AroAco][".$data["MyAco"]["id"]."][_update]",
								"type"		=>	"checkbox",
								"col"		=>	"col_3",
								"row"		=>	"row_".$count,
								"id"		=>	"chk_".$count."_3",
								"label"		=>	false,
								"div"		=>	false,
								"required"	=>	""
							));?>						
						</td>
						<td>
							<?php echo $this->Form->input("AroAco.".$data["MyAco"]["id"]."._delete",array(
								"name"		=>	"data[AroAco][".$data["MyAco"]["id"]."][_delete]",
								"type"		=>	"checkbox",
								"col"		=>	"col_4",
								"row"		=>	"row_".$count,
								"id"		=>	"chk_".$count."_4",
								"label"		=>	false,
								"div"		=>	false,
								"required"	=>	""
							));?>							
						</td>
						<td>
							<input type="checkbox" id="<?php echo "chk_".$count."_5"?>" col="col_5" row="row_<?php echo $count?>"/>
						</td>
					</tr>
					<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6">
							<div class="formSubmit">
								<input type="submit" value="Save" class="redB" style="width:100px;"/>
								<input type="button" value="Cancel" class="blackB" onclick="location.href = '<?php echo $settings["cms_url"].$ControllerName?>/Index/<?php echo $page?>/<?php echo $viewpage?>'" style="width:100px;"/>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php echo $this->Form->end();?>
	</div>
</div>

<?php $this->start("script");?>
<script>
$(document).ready(function(){
	$("#parent_col_1").bind("click",function(){
		if($(this).is(":checked"))
		{
			$("input[col^=col_1]").attr('checked', 'checked');
			$("input[col^=col_1]").parent("span").addClass("checked");
		}
		else
		{
			$("input[col^=col_1]").removeAttr('checked');
			$("input[col^=col_1]").parent("span").removeClass("checked");
		}
	});
	
	$("#parent_col_2").bind("click",function(){
		if($(this).is(":checked"))
		{
			$("input[col^=col_2]").attr('checked', 'checked');
			$("input[col^=col_2]").parent("span").addClass("checked");
		}
		else
		{
			$("input[col^=col_2]").removeAttr('checked');
			$("input[col^=col_2]").parent("span").removeClass("checked");
		}
	});
	
	$("#parent_col_3").bind("click",function(){
		if($(this).is(":checked"))
		{
			$("input[col^=col_3]").attr('checked', 'checked');
			$("input[col^=col_3]").parent("span").addClass("checked");
		}
		else
		{
			$("input[col^=col_3]").removeAttr('checked');
			$("input[col^=col_3]").parent("span").removeClass("checked");
		}
	});
	
	$("#parent_col_4").bind("click",function(){
		if($(this).is(":checked"))
		{
			$("input[col^=col_4]").attr('checked', 'checked');
			$("input[col^=col_4]").parent("span").addClass("checked");
		}
		else
		{
			$("input[col^=col_4]").removeAttr('checked');
			$("input[col^=col_4]").parent("span").removeClass("checked");
		}
	});
	
	$("#parent_col_5").bind("click",function(){
		if($(this).is(":checked"))
		{
			$("input[type=checkbox]").attr('checked', 'checked');
			$("input[type=checkbox]").parent("span").addClass("checked");
		}
		else
		{
			$("input[type=checkbox]").removeAttr('checked');
			$("input[type=checkbox]").parent("span").removeClass("checked");
		}
	});
	$("input[col=col_5]").bind("click",function(){
		var row	=	$(this).attr("row").split("_");
		
		if($(this).is(":checked"))
		{
			$("input[row=row_"+row[1]+"]").attr('checked', 'checked');
			$("input[row=row_"+row[1]+"]").parent("span").addClass("checked");
			
		}
		else
		{
			$("input[row=row_"+row[1]+"]").removeAttr('checked');
			$("input[row=row_"+row[1]+"]").parent("span").removeClass("checked");
		}
	});
	
});
</script>
<?php $this->end();?>