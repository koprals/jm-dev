<style>
tr{
	height:30px;
	vertical-align:middle;
	padding-left:10px;
}
tr.header{
	background-color:#999999;
	font-weight:bold;
	font-size:16px;
}

tr.ganjil{
	background-color:#ffffff;
	font-weight:normal;
	font-size:14px;
}
tr.genap{
	background-color:#F0F0F0;
	font-weight:normal;
	font-size:14px;
}


</style>

<h2 style="text-align:center;"><?php echo $ControllerName?></h2>
<table width="850" border="1" cellspacing="0" cellpadding="0">
	<tr class="header">
		<td width="50">No</td>
		<td width="50">ID</td>
		<td width="150">Name</td>
		<td width="250">Description</td>
		<td width="150">Total Admin</td>
		<td width="100">Status</td>
	</tr>
	
	<?php if(!empty($data)):?>
	<?php $count = 0;?>
	<?php foreach($data as $data): ?>
	<?php $count++;?>
	<?php $no		=	(($page-1)*$viewpage) + $count;?>
	<?php $class = ($count % 2 == 0) ? "ganjil" : "genap";?>
  	<tr class="<?php echo $class?>">
		<td style="text-align:center;"><?php echo $no; ?></td>
		<td style="text-align:center;"><?php echo $data[$ModelName]['id'] ?></td>
		<td style="padding-left:5px;"><?php echo $data[$ModelName]['alias'] ?></td>
		<td style="padding-left:5px;"><?php echo $this->Text->truncate($this->Aimfox->IsEmptyText($data[$ModelName]['description']),100,array("html"=>true))?></td>
		<td style="text-align:right; padding-right:5px;"><?php echo $data[$ModelName]['total_admin'] ?></td>
		<td style="padding-left:5px;"><?php echo $data[$ModelName]['SStatus'] ?></td>
	</tr>
	<?php endforeach;?>
	<?php else:?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<?php endif;?>
</table>