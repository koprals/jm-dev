<style>
.font1{
	font-family:Arial, Helvetica, sans-serif;
}
.text12
{
	font-size:12px;
}
.text13
{
	font-size:13px;
}
.text14
{
	font-size:14px;
}
.kiri{
	float:left; display:block
}
.size100
{
	width:100%
}
.grey{
	color:#333333;
}
.bold{
	font-weight:bold;
}
.normal{
	font-weight:normal;
}
.top5{
	margin-top:5px;
}
.top10{
	margin-top:10px;
}
.top20{
	margin-top:20px;
}
.left10{
	margin-left:10px;
}
.bottom20{
	margin-bottom:20px;
}
</style>
<?php if(!empty($fAncSubcategory)): ?>
	<div class="kiri size100 font1 text12 grey">
		<?php echo date("d M Y",$fAncSubcategory['AncSubcategory']['created'])." (".$time->timeAgoInWords($fAncSubcategory['AncSubcategory']['created']).")"?>
	</div>
	<div class="kiri size100 text14 font1 bold top20">
		<?php echo $fAncSubcategory['AncSubcategory']['name']?>
	</div>
	<?php if(!empty($fAncSubcategory['AncFiles'])):?>
	<div class="kiri size100 top10">
		<?php foreach($fAncSubcategory['AncFiles'] as $AncFilesFirst):?>
			<?php $arr_images	=	array("jpg","png","gif","jpeg");?>
			<?php $ext			=	pathinfo($AncFilesFirst['filename'],PATHINFO_EXTENSION)?>
			<?php $filename		=	pathinfo($AncFilesFirst['path_location'],PATHINFO_FILENAME)?>
			<?php if(in_array(strtolower($ext),$arr_images)):?>
			<div class="kiri size100 bottom20">
				<?php $url_images	=	$this->webroot."anc_app/".$filename;?>
				<img src="<?php echo $url_images?>" style="max-width:500px;">
			</div>
			<?php endif;?>
		<?php endforeach;?>
	</div>
	<?php endif;?>
	<div class="kiri size100 text13 font1 normal top10">
		<?php echo nl2br(html_entity_decode($fAncSubcategory['AncSubcategory']['description']))?>
	</div>
	
	<?php if(!empty($fAncSubcategory['AncFiles'])):?>
	
	<div class="kiri size100 top20" style="border-top-color:#999999; border-top:1px solid #999999; padding-top:20px;">
		<?php foreach($fAncSubcategory['AncFiles'] as $AncFiles):?>
		<?php $arr_images	=	array("jpg","png","gif","jpeg");?>
		<?php $ext			=	pathinfo($AncFiles['filename'],PATHINFO_EXTENSION)?>
		<?php $filename		=	pathinfo($AncFiles['path_location'],PATHINFO_FILENAME)?>
		
		
		<div class="kiri size100 bottom20">
			<?php if(!in_array(strtolower($ext),$arr_images)):?>
			<div class="kiri bold text12">
				<div class="kiri bold text12 font1 size100"><?php echo $AncFiles['filename']?></div>
				<div class="kiri text12 font1 size100"><span class="kiri normal"><?php echo $number->toReadableSize(filesize($AncFiles['path_location']))?></span>&nbsp;&nbsp;<a href="<?php echo $this->webroot.$this->params["controller"]?>/DownloadFile/<?php echo $AncFiles['id']?>" class="kiri normal text12 font1 left10">Donwload</a></div>
			</div>
			<?php endif;?>
		</div>
	</div>
	<?php endforeach;?>
	<?php endif;?>
<?php endif;?>