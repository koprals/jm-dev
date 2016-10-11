<?php if(!empty($data)): ?>
<?php
  $interval = 50;
  if($this->params['paging']['Customer']['order'] != null) {
    $order		=	array_keys($this->params['paging']['Customer']['order']);
  	$direction	=	$this->params['paging']['Customer']["order"][$order[0]];
  	$ordered	=	($order[0]!==0) ? "/sort:".$order[0]."/direction:".$direction: "";
  }

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

</script>
<div class="widget">
	<div class="title">
		<img src="<?php echo $this->webroot ?>img/icons/dark/frames.png" alt="" class="titleIcon">
		<h6>
			Customer Report
		</h6>
	</div>
	<div class="title">
		<div class="itemsPerPage">
			<div id="DataTables_Table_0_length" class="dataTables_left">
				<label>
					<span>Show entries:</span>
					<?PHP echo $this->Form->select("view",array(50=>50,100=>100,200=>200, 500 => 500, 1000 => 1000, 5000 => 5000, 10000 => 10000),array("onchange"=>"onClickPage('".$settings["cms_url"].$ControllerName."/ListItem/limit:'+this.value+'?time=".time()."','#contents_area')","empty"=>false,"default"=>$viewpage))?>
				</label>
			</div>
		</div>
	</div>
</div>
<div class="widget widgetHeader" style="overflow:auto;height:500px;">
	<table cellpadding="0" cellspacing="0" class="demo sTable mTable" style="width:<?php echo $interval*50 + 850?>px">
		<thead>
			<tr>
				<td rowspan="2" style="width:30px; font-size:12px; font-weight:bold; vertical-align:middle;">
					No
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					BA Code
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					BA Name
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Device ID
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Device Model
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Customer Name
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Email
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Mobile Phone
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Gender
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Instagram
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Cigarette Brand
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Cigarette Brand Product
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Status
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Device Date
				</td>
        <!-- <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Distance
				</td> -->
				<!--td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Customer Name
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Email
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Email Status
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Mobile Phone
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Gender
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Consumer Age Range
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Consumer Province
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Consumer City
				</td>
        <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Consumer District
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Product
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Customers Package
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Total Sales
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Total Trial
				</td-->
        <!-- <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Total No Trial
				</td> -->
        <!--td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Total NOC
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Current Cigaretes
				</td>
				<td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					SKU SOB
				</td-->
				<!-- <td rowspan="2" style="font-size:12px; font-weight:bold; vertical-align:middle;">
					Total Value
				</td> -->
				<!-- <td colspan="4" style="text-align:center; font-weight:bold;font-size:12px;">
					Interest
				</td>
				<td colspan="5" style="text-align:center; font-weight:bold;font-size:12px;">
					Social Media Interest
				</td>
				<td colspan="6" style="text-align:center; font-weight:bold;font-size:12px;">
					Favorite Hangout Place
				</td>
				<td colspan="6" style="text-align:center; font-weight:bold;font-size:12px;">
					Favorite Hangout Day
				</td>
				<td colspan="6" style="text-align:center; font-weight:bold;font-size:12px;">
					Favorite Music Genre
				</td> -->
				<td rowspan="2" style="text-align:center; font-weight:bold;font-size:12px;">
					Created
				</td>
			</tr>
		</thead>
		<tbody>
			<?php $count = 0;?>
			<?php foreach($data as $data):?>
			<?php $count++;?>
			<?php $no	=	(($page-1)*$viewpage)+$count;?>

			<tr>
				<td><?php echo $no?></td>
        <td><?php echo $data['User']['code']; ?></td>
				<td><?php echo $data['User']['name']; ?></td>
        <td><?php echo $data[$ModelName]['device_id']; ?></td>
        <td><?php echo $data[$ModelName]['device_model']; ?></td>
        <td><?php echo $data[$ModelName]['name']; ?></td>
				<td><?php echo $data[$ModelName]['email']; ?></td>
        <td><?php echo $data[$ModelName]['mobile_phone']; ?></td>
        <td><?php echo $data[$ModelName]['gender']; ?></td>
				<td><?php echo $data[$ModelName]['instagram']; ?></td>
				<td><?php echo $data['CigaretteBrand']['name']; ?></td>
        <td><?php echo $data['CigaretteBrandProduct']['name']; ?></td>
				<td><?php echo $data[$ModelName]['SValid']; ?></td>
				<td><?php echo $this->Time->nice($data[$ModelName]['device_date']); ?></td>
				<td><?php echo $this->Time->nice($data[$ModelName]['created']); ?></td>
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
<script type="text/javascript">
var $table = $('table.demo');
$table.floatThead({
    scrollContainer: function($table){
        return $table.closest('.widgetHeader');
    },
	zIndex : 100
});
</script>
<?php else:?>
<div class="nNote nFailure">
	<p><strong>DATA IS EMPTY!</strong></p>
</div>
<?php endif;?>
