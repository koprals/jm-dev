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
		$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/ChangeStatus/"+id+"/"+status,function(){
			$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		});
	}
	return false;
}

function Delete(msg,id)
{
	var a	=	confirm(msg);
	if(a)
	{
		$.getJSON("<?php echo $settings["cms_url"].$ControllerName?>/Delete/"+id,function(){
			$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>");
		});
	}
	return false;
}
</script>

<div class="widget">
	<div class="title">
		<img src="<?php echo $this->webroot ?>img/icons/dark/frames.png" alt="" class="titleIcon">
		<h6>
			<?php echo $ControllerName?> - Page <?php echo $this->Paginator->counter(); ?>
		</h6>
	</div>
	<div class="title">
		<div class="itemsPerPage">
			<div id="DataTables_Table_0_length" class="dataTables_length">
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
				<td style="width:5%;">
					No
				</td>
				<td style="width:13%;">
					Image
				</td>
				<td style="width:5%;">
					<?php echo $this->Paginator->sort("$ModelName.id",'ID');?>
				</td>
				<td style="width:20%;">
					<?php echo $this->Paginator->sort("$ModelName.fullname",'Name');?>
				</td>
				<td style="width:22%;">
					<?php echo $this->Paginator->sort("MyAro.alias", 'Admin Group');?>
				</td>
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.SStatus",'Status');?>
				</td>
				
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.modified",'Modified');?>
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
				<?php $class	=	($data[$ModelName]['status'] == "0") ? "style='background-color:#FFDDDE'" : "";?>
				<tr <?php echo $class?>>
					<td>
						<?php echo $no ?>
					</td>
					<td>
						<a rel="lightbox" title="<?php echo $data[$ModelName]['fullname'] ?>" href="<?php echo $data["Image"]["host"].$data["Image"]["url"]?>?time=<?php echo time()?>" style="border:0px;">
						<img src="<?php echo $data["Image"]["host"].$data["Image"]["url"]?>?time=<?php echo time()?>" width="60"/>
						</a>
					</td>
					
					<td><?php echo $data[$ModelName]['id'] ?></td>
					<td><?php echo $data[$ModelName]['fullname'] ?></td>
					<td><?php echo $data["MyAro"]['alias'] ?></td>
					<td><?php echo $data[$ModelName]['SStatus'] ?></td>
					<td style="text-align:center;"><?php echo date("d M Y",strtotime($data[$ModelName]['modified'])) ?></td>
					<?php
					if(
						$access[$aco_id]["_update"] == 1 or
						$access[$aco_id]["_delete"] == 1
					):
					?>
					<td style="text-align:center;">
						<?php if($access[$aco_id]["_update"] == 1):?>
							<a href="<?php echo $settings['cms_url'].$ControllerName?>/Edit/<?php echo $data[$ModelName]["id"]?>/<?php echo $page?>/<?php echo $viewpage?>" class="tipS smallButton blueB" title="Edit">
								<img src="<?php echo $this->webroot?>img/icons/topnav/pencil.png" alt="Edit" />
							</a>
							<?php if($data[$ModelName]['status']=="1"):?>
								<a href="javascript:void(0);" onclick="ChangeStatus('Do you realy want hide this item ?','<?php echo $data[$ModelName]['id']?>','0')" class="tipS smallButton greenB" title="Hide">
									<img src="<?php echo $this->webroot?>img/icons/topnav/arrowDown.png" alt="Hide"/>
								</a>
							<?php else:?>
								<a href="javascript:void(0);" onclick="ChangeStatus('Do you realy want publish this item ?','<?php echo $data[$ModelName]['id']?>','1')" class="tipS smallButton blackB" title="Publish">
									<img src="<?php echo $this->webroot?>img/icons/topnav/arrowUp.png" alt="Publish"/>
								</a>
							<?php endif;?>
						<?php endif;?>
						<?php if($access[$aco_id]["_delete"] == 1):?>
						<a href="javascript:void(0);" onclick="Delete('Do you realy want delete this Admin ?','<?php echo $data[$ModelName]['id']?>')" class="tipS smallButton redB" title="Delete">
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
				<td colspan="8">
				<?php else:?>
				<td colspan="7">
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