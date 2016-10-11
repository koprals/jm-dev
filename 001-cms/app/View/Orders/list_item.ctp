<?php if(!empty($data)): ?>
<?php
	$order		=	array_keys($this->params['paging'][$ModelName]['order']);
	$direction	=	$this->params['paging'][$ModelName]["order"][$order[0]];
	$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction: "";
?>
<?php $this->Paginator->options(array(
				'url'	=> array(
					'controller'	=> $ControllerName,
					'action'		=> 'ListItem/limit:'.$viewpage,
				),
				'onclick'=>"return onClickPage(this,'#contents_area');")
			);
?>

<script>
function ChangeStatus(msg,id,status)
{
	var a	=	confirm(msg);
	if(a)
	{
		$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/ChangeStatus/"+id+"/"+status,function(result){
			alert(result.data.message);
			if(result.data.status == "1")
			{
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
				});
			}
		});
	}
	return false;
}

function Delete(msg,id)
{
	var a	=	confirm(msg);
	if(a)
	{
		$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/Delete/"+id,function(result){
			alert(result.data.message);
			if(result.data.status == "1")
			{
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
				});
			}
		});
	}
	return false;
}

function UpLevel(msg,id)
{
	var a	=	confirm(msg);
	if(a)
	{
		$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/UpLevel/"+id,function(result){
			alert(result.data.message);
			if(result.data.status == "1")
			{
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
				});
			}
		});
	}
	return false;
}

function DownLevel(msg,id)
{
	var a	=	confirm(msg);
	if(a)
	{
		$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/DownLevel/"+id,function(result){
			alert(result.data.message);
			if(result.data.status == "1")
			{
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
				});
			}
		});
	}
	return false;
}

function ActionChecked()
{
	var checked	=	"";
	$("input[id^=chck_]").each(function(index){
		if($(this).prop("checked"))
		{
			checked			+=		$(this).val()+",";
		}
	});
	checked		=	checked.substring(0,checked.length-1);
	
	if(checked.length == 0)
	{
		alert("Please check item!");
	}
	else
	{
		if($("#action").val() == "")
		{
			alert("Please select action!");
		}
		else
		{
			if( $("#action").val() == "delete" )
			{
				var a	=	confirm("All child from selected item will be deleted, Do you realy want to delete all selected item ?");
				if(a)
				{
					$("#loadingAction").show();
					
					$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/DeleteMultiple/",{
						"id":checked
					},function(result){
						$("#loadingAction").hide();
						alert(result.data.message);
						if(result.data.status == "1")
						{
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
							});
						}
					});
				}
			}
			else if( $("#action").val() == "hide" )
			{
				var a	=	confirm("Do you realy want hide all selected item ?");
				if(a)
				{
					$("#loadingAction").show();
					$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/ChangeStatusMultiple/",{
						"id":checked,
						"status":"0",
						"child":"0",
					},function(result){
						$("#loadingAction").hide();
						alert(result.data.message);
						if(result.data.status == "1")
						{
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
							});
						}
					});
				}
			}
			else if( $("#action").val() == "hideall" )
			{
				var a	=	confirm("Do you realy want hide all selected item include all childs ?");
				if(a)
				{
					$("#loadingAction").show();
					$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/ChangeStatusMultiple/",{
						"id":checked,
						"status":"0",
						"child":"1",
					},function(result){
						$("#loadingAction").hide();
						alert(result.data.message);
						if(result.data.status == "1")
						{
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
							});
						}
					});
				}
			}
			else if( $("#action").val() == "publish" )
			{
				var a	=	confirm("Do you realy want publish all selected item ?");
				if(a)
				{
					$("#loadingAction").show();
					$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/ChangeStatusMultiple/",{
						"id":checked,
						"status":"1",
						"child":"0",
					},function(result){
						$("#loadingAction").hide();
						alert(result.data.message);
						if(result.data.status == "1")
						{
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
							});
						}
					});
				}
			}
			else if( $("#action").val() == "publishall" )
			{
				var a	=	confirm("Do you realy want publish all selected item include all childs ?");
				if(a)
				{
					$("#loadingAction").show();
					$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/ChangeStatusMultiple/",{
						"id":checked,
						"status":"1",
						"child":"1",
					},function(result){
						$("#loadingAction").hide();
						alert(result.data.message);
						if(result.data.status == "1")
						{
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
							});
						}
					});
				}
			}
		}
	}
}
function CheckAll(el)
{
	if($(el).prop("checked"))
	{
		$("input[id^=chck_]").prop('checked', true);
		$("input[id^=chck_]").parent("span").addClass("checked");
	}
	else
	{
		$("input[id^=chck_]").prop('checked', false);
		$("input[id^=chck_]").parent("span").removeClass("checked");
	}
}
</script>

<div class="widget">
	<div class="title">
		<img src="<?php echo $this->webroot ?>img/icons/dark/frames.png" alt="" class="titleIcon">
		<h6>
			<?php echo $ModelName?> - Page <?php echo $this->Paginator->counter(); ?>
		</h6>
		
	</div>
	<div class="title">
		<div class="itemsPerPage">
			<?php
			if(
				$access[$aco_id]["_update"] == 1 or
				$access[$aco_id]["_delete"] == 1
			):
			?>
			<div class="dataTables_left">
				<label>
					<span>Action Selected:</span>
					<?php
						/*$updateAction	=	array(
							"hide"			=>	"Hide",
							"publish"		=>	"Publish"
						);*/
						$deleteAction	=	array(
							"delete"		=>	"Delete"
						);
						$action			=	array();
						
						if($access[$aco_id]["_delete"] == 1)
							$action		=	array_merge($action,$deleteAction);
						/*if($access[$aco_id]["_update"] == 1)
							$action		=	array_merge($action,$updateAction);*/
					?>
					
					<?PHP echo $this->Form->select("action",$action,
					array(
						"empty"	=>	"Select Action"
					));?>
					<a href="javascript:void(0);" class="smallButton blueB" title="See Child" style=" cursor:default; padding:3px 15px 1px 15px; margin-left:10px; float:left;" onclick="ActionChecked()" >Go</a>
					<span style="float:left; margin-left:10px;display:none;;" id="loadingAction">
						<img src="<?php echo $this->webroot?>img/loaders/loader2.gif" /> Loading..
					</span>
					<a href="<?php echo $settings['cms_url'].$ControllerName?>/Excel" class="smallButton redB" title="See Child" style="padding:3px 15px 1px 15px; margin-left:10px; float:left;">Export</a>
				</label>
			</div>
			<?php endif;?>
			<div class="dataTables_length">
				<label>
					<span>Show entries:</span>
					<?PHP echo $this->Form->select("view",array(1=>1,5=>5,10=>10,20=>20,50=>50,100=>100,200=>200,1000=>1000),array("onchange"=>"onClickPage('".$settings["cms_url"].$ControllerName."/ListItem/limit:'+this.value+'".$ordered."','#contents_area')","empty"=>false,"default"=>$viewpage))?>
				</label>
			</div>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable">
		<thead>
			<tr>
				<?php
				if(
					$access[$aco_id]["_update"] == 1 or
					$access[$aco_id]["_delete"] == 1
				):
				?>
				<td style="width:5%;text-align:center;" >
					<input type="checkbox" onclick="CheckAll(this)"/>
				</td>
				<?php endif;?>
				<td style="width:5%;">
					No
				</td>
				<td style="width:7%;">
					<?php echo $this->Paginator->sort("$ModelName.order_id_display",'ID');?>
				</td>
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.fullname",'Name');?>
				</td>
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("Order.type",'Type');?>
				</td>
				<td style="width:8%;">
					<?php echo $this->Paginator->sort("Service.name",'Service');?>
				</td>
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.date_order", 'Service Date');?>
				</td>
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.time_order", 'Service Time');?>
				</td>
				
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.total",'Total');?>
				</td>
				<td style="width:15%;">
					<?php echo $this->Paginator->sort("OrderStatuses.name",'Status');?>
				</td>
				<?php
				if(
					$access[$aco_id]["_update"] == 1 or
					$access[$aco_id]["_delete"] == 1
				):
				?>
				<td style="width:15%;">
					Action
				</td>
				<?php endif;?>
			</tr>
		</thead>
		<tbody>
			<?php $count = 0;?>
			<?php foreach($data as $data): ?>
				<?php $count++;?>
				<?php $no		=	(($page-1)*$viewpage) + $count;?>
				<?php $class	=	($data[$ModelName]['status'] == "1") ? "style='background-color:#FFDDDE'" : "";?>
				<tr <?php echo $class?>>
					<?php
					if(
						$access[$aco_id]["_update"] == 1 or
						$access[$aco_id]["_delete"] == 1
					):
					?>
					<td style=" width:5%;text-align:center;">
						<input type="checkbox" id="chck_<?php echo $data[$ModelName]['id']?>" value="<?php echo $data[$ModelName]['id']?>"/>
					</td>
					<?php endif;?>
					<td> <?php echo $no ?></td>
					<td><?php echo $data[$ModelName]['order_id_display'] ?></td>
					<td><?php echo $data[$ModelName]['fullname'] ?></td>
					<td><?php echo $data["OrderType"]['name'] ?></td>
					<td><?php echo $data["Service"]['name'] ?></td>
					<td><?php echo date("d M Y",strtotime($data[$ModelName]['date_order'])) ?></td>
					<td><?php echo $data[$ModelName]['time_order'] ?></td>
					<td><?php echo "Rp ".number_format($data[$ModelName]['total'],0,null,",")?></td>
					<td>
						<a href="<?php echo $settings['cms_url'].$ControllerName?>/Detail/<?php echo $data[$ModelName]['id']?>"><?php echo $data['OrderStatus']['name'] ?></a>
					</td>
					
					<?php
					if(
						$access[$aco_id]["_update"] == 1 or
						$access[$aco_id]["_delete"] == 1
					):
					?>
					<td style="text-align:center;">
						<?php if($access[$aco_id]["_update"] == 1):?>
							<a href="<?php echo $settings['cms_url'].$ControllerName?>/Detail/<?php echo $data[$ModelName]["id"]?>/<?php echo $page?>/<?php echo $viewpage?>" class="tipS smallButton blueB" title="Detail">
								<img src="<?php echo $this->webroot?>img/icons/topnav/pencil.png" alt="Detail" />
							</a>
						<?php endif;?>
						
						<?php if($access[$aco_id]["_delete"] == 1):?>
							<a href="javascript:void(0);" onclick="Delete('Anda yakin akan menghapus order ini','<?php echo $data[$ModelName]['id']?>')" class="tipS smallButton redB" title="Delete">
								<img src="<?php echo $this->webroot?>img/icons/topnav/subTrash.png" alt="Delete"/>
							</a>
						<?php endif;?>
					</td>
					<?php endif;?>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<?php
				if(
					$access[$aco_id]["_update"] == 1 or
					$access[$aco_id]["_delete"] == 1
				):
				?>
				<td colspan="13">
				<?php else:?>
				<td colspan="12">
				<?php endif;?>
					<div class="itemActions">
						<label><?php echo $this->Paginator->counter(array('format' => 'Showing %start% to %end% of %count% entries'));?></label>
					</div>
					<?php if($this->Paginator->hasPrev() or $this->Paginator->hasNext()):?>
					<div class="tPagination">
						<ul class="pages">
							<?php echo $this->Paginator->prev("",
									array(
										"escape"	=>	false,
										'tag'		=>	"li",
										"class"		=>	"prev"
									),
									"<a href='javascript:void(0)'></a>",
									array(
										'tag'		=>	"li",
										"escape"	=>	false,
										"class"		=>	"prev"
									)
								);
							?>
							
							<?php
								echo $this->Paginator->numbers(array(
									'separator'		=>	null,
									'tag'			=>	"li",
									'currentclass'	=>	'active',
									'modulus'		=>	4
								));
							?>
							<?php echo $this->Paginator->next("",
									array(
										"escape"	=>	false,
										'tag'		=>	"li",
										"class"		=>	"next"
									),
									"<a href='javascript:void(0)'></a>",
									array(
										'tag'		=>"li",
										"escape"	=>	false,
										"class"		=>	"next"
									)
								);
							?>
						</ul>
					</div>
					<?php endif;?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<?php else:?>
<div class="nNote nFailure">
	<p><strong>DATA IS EMPTY!</strong></p>
</div>
<?php endif;?>