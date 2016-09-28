<script>
function GotoPage(value)
{
	var val	=	parseInt(value);
	if(val > <?php echo $paginator->counter(array('format' => '%pages%'));?>)
	{
		val	=	<?php echo $paginator->counter(array('format' => '%pages%'));?>;
	}
	
	if(val<1)
	{
		val = 1
	}
	location.href	=	'<?php echo $settings['site_url']."News/Index/"?>/page:'+val;
}

</script>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="kiri size50 left5" style="border:0px solid black;">
                JM NEWS
            </div>
		</div>
		<?php if(!empty($data)):?>
		
		<?php echo $paginator->options(array(
				'url'	=> implode("/",$this->params['pass'])
				));
?>
		<div class="line top20" style="border:0px solid black;">
		<!-- PAGING -->
		<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
		<div style="float:none; margin:auto; width:45%; border:0px solid black; margin-bottom:10px;">
			<?php echo $form->create("Goto",array("onsubmit"=>"GotoPage($('#goto').val());return false;"))?>
			<div class="style1 text11 grey2 bold"><?php echo $paginator->counter(array('format' => 'Page <span class="red2">%page%</span> of %pages%'));?>
				<?php echo $paginator->prev('<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;" alt="icon prev"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/prev.jpg" border="0" style=" vertical-align:middle;" alt="icon prev"/>',array('tag'=>"a")); ?>
				<?php echo $paginator->next('<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;" alt="icon next"/>',array("escape"=>false),'<img src="'.$this->webroot.'img/next.jpg" border="0" style=" vertical-align:middle;" alt="icon next"/>',array('tag'=>"a")); ?>
				Go to page <input type="text" class="style1 text11 grey2" style="border:1px solid #C1C1C1; width:30px; height:20px;" maxlength="3" onblur="return GotoPage($('#goto').val())" id="goto"/>
			</div>
			<?php echo $form->end();?>
		</div>
		<?php endif;?>
		<!-- END PAGING -->
		<?php $count=0;?>
		<?php foreach($data as $data):?>
		<div class="kiri size98" style="border-bottom:1px solid #D5D5D5; padding:10px; padding-left:0px;">
			<div class="kiri size50 right10" style="width:296px;">
				<img src="<?php echo $settings['showimages_url']."/".$data['News']['id'].".jpg?code=".$data['News']['id']."&prefix=_296_174&content=News&w=296&h=174"?>"/>
			</div>
			<div class="kiri size45 right10">
				<div class="line kiri size30 style1 text12 grey2">
					<?php echo $general->DateConvert($data['News']['created']);?>
				</div>
				<div class="line kiri size30 style1 text13 red top5 bold">
					<a href="<?php echo $settings['site_url']?>News/Detail/<?php echo $data['News']['id']?>/<?php echo $general->seoUrl($data['News']['title'])?>.html" class="style1 text13 red top10 bold"><?php echo $data['News']['title'];?></a>
				</div>
				<div class="line kiri size30 style1 text12 grey2 top5">
					<?php echo $text->truncate($data['News']['description'],300, array("html"=>true));?>
				</div>
			</div>
		</div>
		<?php endforeach;?>
		<?php else:?>
		<div class="box_alert">
			<div class="alert">
				<img src="<?php echo $this->webroot?>img/warning.gif"/>
				Data Not Found<br /><br />
			</div>
		</div>
		<?php endif;?>
	</div>
</div>