<?php if(!empty($data)): ?>
<?php
	$order		=	array_keys($this->params['paging'][$ModelName]['order']);
	$direction	=	$this->params['paging'][$ModelName]["order"][$order[0]];
	$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction: "";
?>

<?php $this->Paginator->options(array(
				'url'	=> array(
					'controller'	=> $ControllerName,
					'action'		=> 'ListItem/limit:'.$viewpage.'/parent_id:'.$parent_id,
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
			Module Object - Page <?php echo $this->Paginator->counter(); ?>
		</h6>
	</div>
	<div class="title">
		<?php if(!empty($check[$ModelName]['parent_id'])):?>
			<div class="horControlB" style="width:auto; height:100%; display:block; float:left; padding:0px; margin-top:15px; margin-left:10px;">
				<a href="javascript:void(0);" class="button redB" onclick="onClickPage('<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>/parent_id:<?php echo $check[$ModelName]['parent_id']?>','#contents_area');">
					<span ><img src="<?php echo $this->webroot?>/img/icons/control/16/back.png" style="float:left; margin-right:5px; margin-top:-2px;"/>BACK</span>
				</a>
			</div>
		<?php endif;?>
		<div class="itemsPerPage">
			<div id="DataTables_Table_0_length" class="dataTables_length">
				<label>
					<span>Show entries:</span>
					<?PHP echo $this->Form->select("view",array(1=>1,5=>5,10=>10,20=>20,50=>50,100=>100,200=>200,1000=>1000),array("onchange"=>"onClickPage('".$settings["cms_url"].$ControllerName."/ListItem/limit:'+this.value+'".$ordered."/parent_id:$parent_id','#contents_area')","empty"=>false,"default"=>$viewpage))?>
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
				<td style="width:5%;">
					<?php echo $this->Paginator->sort("$ModelName.id",'ID');?>
				</td>
				<td style="width:20%;">
					<?php echo $this->Paginator->sort("$ModelName.alias",'Name');?>
				</td>
				<td style="width:22%;">
					<?php echo $this->Paginator->sort("$ModelName.description", 'Description');?>
				</td>
				<td style="width:13%;">
					<?php echo $this->Paginator->sort("Parent.name", 'Parent');?>
				</td>
				
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.SStatus",'Status');?>
				</td>
				
				<td style="width:10%;">
					<?php echo $this->Paginator->sort("$ModelName.modified",'Modified');?>
				</td>
				<td style="width:15%;">
					Action
				</td>
			</tr>
		</thead>
		<tbody>
			<?php $count = 0;?>
			<?php foreach($data as $data): ?>
				<?php $count++;?>
				<?php $no		=	(($page-1)*$viewpage) + $count;?>
				<?php $class	=	($data[$ModelName]['status'] == "0") ? "style='background-color:#FFDDDE'" : "";?>
				<tr <?php echo $class?>>
					<td><?php echo $no ?></td>
					<td><?php echo $data[$ModelName]['id'] ?></td>
					<td><?php echo $data[$ModelName]['alias'] ?></td>
					<td><?php echo $this->Text->truncate($this->Aimfox->IsEmptyText($data[$ModelName]['description']),100,array("html"=>true))?></td>
					<td><?php echo $this->Aimfox->IsEmptyText($data["Parent"]['alias']) ?></td>
					<td><?php echo $data[$ModelName]['SStatus'] ?></td>
					<td style="text-align:center;"><?php echo date("d M Y",strtotime($data[$ModelName]['modified'])) ?></td>
					<td style="text-align:center;">
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
						<a href="javascript:void(0);" onclick="Delete('Do you realy want delete this Category ?','<?php echo $data[$ModelName]['id']?>')" class="tipS smallButton redB" title="Delete">
							<img src="<?php echo $this->webroot?>img/icons/topnav/subTrash.png" alt="Delete"/>
						</a>
						
						<?php if($data[$ModelName]['rght']-$data[$ModelName]['lft'] > 1):?>
						<a href="javascript:void(0);" class="tipS smallButton blackB" title="See Child" onclick="$(this).tipsy('hide');onClickPage('<?php echo $settings["cms_url"].$ControllerName?>/ListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>/parent_id:<?php echo $data[$ModelName]['id']?>','#contents_area');">
							<img src="<?php echo $this->webroot?>img/icons/topnav/folder.png" alt="Child"/>
						</a>
						<?php else:?>
						<a href="javascript:void(0);" class="smallButton greyB" title="See Child" style=" cursor:default;">
							<img src="<?php echo $this->webroot?>img/icons/topnav/folder.png" alt="Child"/>
						</a>
						<?php endif;?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8">
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