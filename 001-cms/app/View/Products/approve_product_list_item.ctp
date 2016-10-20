<?php if(!empty($data)): ?>
<?php
  $interval = 30;
  if($this->params['paging'][$ModelName]['order'] != null) {
    $order		=	array_keys($this->params['paging'][$ModelName]['order']);
  	$direction	=	$this->params['paging'][$ModelName]["order"][$order[0]];
  	$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction: "";
  }

?>
<?php $this->Paginator->options(array(
				'url'	=> array(
					'controller'	=> $ControllerName,
					'action'		=> 'ApproveProductListItem/limit:'.$viewpage,
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
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
					$("a[rel^='lightbox']").prettyPhoto({
						social_tools :''
					});
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
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
					$("a[rel^='lightbox']").prettyPhoto({
						social_tools :''
					});
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
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
					$("a[rel^='lightbox']").prettyPhoto({
						social_tools :''
					});
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
				$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:<?php echo $page?>/limit:<?php echo $viewpage.$ordered?>",function(){
					$("#view, input:checkbox, #action").uniform();
					$('.tipS').tipsy({gravity: 's',fade: true});
					$("a[rel^='lightbox']").prettyPhoto({
						social_tools :''
					});
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
				var a	=	confirm("Do you realy want delete selected items?");
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
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
								$("a[rel^='lightbox']").prettyPhoto({
									social_tools :''
								});
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
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
								$("a[rel^='lightbox']").prettyPhoto({
									social_tools :''
								});
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
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
								$("a[rel^='lightbox']").prettyPhoto({
									social_tools :''
								});
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
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
								$("a[rel^='lightbox']").prettyPhoto({
									social_tools :''
								});
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
							$("#contents_area").load("<?php echo $settings["cms_url"].$ControllerName?>/ApproveProductListItem/page:1/limit:<?php echo $viewpage.$ordered?>",function(result){
								$("#view, input:checkbox, #action").uniform();
								$('.tipS').tipsy({gravity: 's',fade: true});
								$("a[rel^='lightbox']").prettyPhoto({
									social_tools :''
								});
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
			Approve Advertisement List
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
            $updateAction	=	array(
              "hide"			=>	"Activate",
              "publish"		=>	"Deactivate"
            );
            $deleteAction	=	array(
              "delete"		=>	"Delete"
            );
            $action			=	array();

            if($access[$aco_id]["_delete"] == 1)
              $action		=	array_merge($action,$deleteAction);
            if($access[$aco_id]["_update"] == 1)
              $action		=	array_merge($action,$updateAction);
          ?>

          <?PHP echo $this->Form->select("action",$action,
          array(
            "empty"	=>	"Select Action"
          ));?>
          <a href="javascript:void(0);" class="smallButton blueB" title="See Child" style=" cursor:default; padding:3px 15px 1px 15px; margin-left:10px; float:left;" onclick="ActionChecked()" >Go</a>
          <span style="float:left; margin-left:10px;display:none;;" id="loadingAction">
            <img src="<?php echo $this->webroot?>img/loaders/loader2.gif" /> Loading..
          </span>
        </label>
      </div>
      <?php endif;?>
      <div class="dataTables_length">
				<label>
					<span>Show entries:</span>
					<?PHP echo $this->Form->select("view",array(1=>1,5=>5,10=>10,20=>20,50=>50,100=>100,200=>200,1000=>1000),array("onchange"=>"onClickPage('".$settings["cms_url"].$ControllerName."/ApproveProductListItem/limit:'+this.value+'".$ordered."/','#contents_area')","empty"=>false,"default"=>$viewpage))?>
				</label>
			</div>
		</div>
	</div>
<div class="widget widgetHeader" style="overflow:auto;height:500px;">
	<table cellpadding="0" cellspacing="0" class="demo sTable mTable" style="width:<?php echo $interval*30 + 850?>px">
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
        <td rowspan="2" style="width:30px; font-size:12px; font-weight:bold; vertical-align:middle;">
					No
				</td>
				<td rowspan="2" style="width:30px; font-size:12px; font-weight:bold; vertical-align:middle;">
					ID
				</td>
				<!--td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Merk
				</td-->
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Tipe
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Penjual
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Telp
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Kondisi
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					No.Pol
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Thn. Pembuatan
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Warna
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Kilometer
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					STNK
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					BPKB
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Harga
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Status Jual
				</td>
				<td rowspan="2" style="text-align:center; font-weight:bold;font-size:12px;">
					Created
				</td>
        <td rowspan="2" style="text-align:center; font-weight:bold;font-size:12px;">
					Action
				</td>
			</tr>
		</thead>
		<tbody>
			<?php $count = 0;?>
			<?php foreach($data as $data):?>
			<?php $count++;?>
			<?php $no	=	(($page-1)*$viewpage)+$count;?>
			<tr>
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
				<td><?php echo $no?></td>
        <td><?php echo $data[$ModelName]['id'] ?></td>
        <td><?php echo $data['Category']['name'] ?></td>
        <td><?php echo $data[$ModelName]['contact_name'] ?></td>
        <td><?php echo $data[$ModelName]['phone'] ?></td>
        <td><?php echo $data[$ModelName]['CConditions'] ?></td>
        <td><?php echo $data[$ModelName]['nopol'] ?></td>
        <td><?php echo $data[$ModelName]['thn_pembuatan'] ?></td>
        <td><?php echo $data[$ModelName]['color'] ?></td>
        <td><?php echo $data[$ModelName]['kilometer'] ?></td>
        <td><?php echo $data['Stnk']['name'] ?></td>
        <td><?php echo $data['Bpkb']['name'] ?></td>
        <td><?php echo $data[$ModelName]['price'] ?></td>
        <td><?php echo $data[$ModelName]['SSold'] ?></td>
        <td><?php echo $this->Time->niceShort($data[$ModelName]['created']) ?></td>
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
            <?php else:?>
              <a href="javascript:void(0);" onclick="ChangeStatus('Do you realy want activate this item ?','<?php echo $data[$ModelName]['id']?>','1')" class="tipS smallButton blackB" title="Activate">
                <img src="<?php echo $this->webroot?>img/icons/topnav/arrowUp.png" alt="Publish"/>
              </a>
            <?php endif;?>
          <?php endif;?>
          <?php if($access[$aco_id]["_delete"] == 1):?>
            <a href="javascript:void(0);" onclick="Delete('Do you realy want to delete this item?','<?php echo $data[$ModelName]['id']?>')" class="tipS smallButton redB" title="Delete">
              <img src="<?php echo $this->webroot?>img/icons/topnav/subTrash.png" alt="Delete"/>
            </a>
          <?php endif;?>
        </td>
      </tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<?php echo $interval+4?>">
					<div class="dataTables_left" style="width:49%;">
						<label><?php echo $this->Paginator->counter(array('format' => 'Showing %start% to %end% of %count% entries'));?></label>
					</div>
					<div style="display:block; float:right; width:40%; text-align:right;padding-top:10px;padding-right:10px;">
						<a href="<?php echo $settings['cms_url'].$ControllerName?>/Excel" class="smallButton redB" title="See Child" style="padding:3px 15px 3px 15px; margin-left:10px; float:right; display:block;">Export Excel</a>
						<?php if($this->Paginator->hasPrev() or $this->Paginator->hasNext()):?>
						<div class="tPagination" style="margin-top:0px; float:right;">
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
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

<?php else:?>
<div class="nNote nFailure">
	<p><strong>DATA IS EMPTY!</strong></p>
</div>
<?php endif; ?>
