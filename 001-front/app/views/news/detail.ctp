<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="kiri size50 left5" style="border:0px solid black;">
                JM NEWS
            </div>
		</div>
		
		<?php if(!empty($data)):?>
		<div class="line kiri size30 style1 text12 grey2 top20">
			<?php echo $general->DateConvert($data['News']['created']);?>
		</div>
		<div class="line kiri size100 style1 text35 red top10">
			<?php echo $data['News']['title'];?>
		</div>
		<div class="line kiri size100 style1 text15 red top10">
			<img src="<?php echo $settings['showimages_url']."/".$data['News']['id'].".jpg?code=".$data['News']['id']."&prefix=_460_250&content=News&w=460&h=250"?>"/>
		</div>
		<div class="line kiri size100 style1 text12 black top10">
			<?php echo nl2br($data['News']['description']);?>
		</div>
		<div class="kiri size10 style1 text12 grey2 top20 right10">
			<a name="fb_share" type="box_count" display="block" share_url="<?php echo $settings['site_url']?>News/Detail/<?php echo $data['News']['id']?>/<?php echo $general->seoUrl($data['News']['title'])?>.html"></a>
			<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
		</div>
		<div class="kiri size10 style1 text12 grey2 top20 right10">
			<a href="https://twitter.com/share" data-count="vertical" class="twitter-share-button" data-url="<?php echo $settings['site_url']?>News/Detail/<?php echo $data['News']['id']?>/<?php echo $general->seoUrl($data['News']['title'])?>.html" data-text="<?php echo $data['News']['title'];?>" data-via="JualanMotor.Com"></a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

		</div>
		<div class="kiri size10 style1 text12 grey2 top20 right10">
			<g:plusone size="tall" href="<?php echo $settings['site_url']?>News/Detail/<?php echo $data['News']['id']?>/<?php echo $general->seoUrl($data['News']['title'])?>.html"></g:plusone>&nbsp;
			<script type="text/javascript">
			  (function() {
			 var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			 po.src = 'https://apis.google.com/js/plusone.js';
			 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</div>
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