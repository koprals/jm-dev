<?php if(!empty($data)):?>
<?php echo $paginator->options(array(
				'url'	=> array(
					'controller'	=> 'Iklan',
					'action'		=> 'ListComment/'.$product_id,
				),
				'onclick'=>"return onClickPage(this,'#list_comment');")
			);
?>
<?php foreach($data as $data):?>
<!-- LOOP -->
<div class="kiri size100" style="border-bottom:1px solid #D5D5D5; padding-bottom:10px;">
    <div class="kiri style1 bold red2 text13 size100 top10"><?php echo $data['Comment']['name']?> <span class="left5 text11 grey3 unbold"><?php echo date("d-M-Y H:i:s",strtotime($data['Comment']['created']))?></span></div>
    <div class="kiri style1 unbold grey4 text12 left10 top5" style="word-wrap: break-word;"><?php echo  chunk_split($data['Comment']['comment'],150,"<br />")?></div>
</div>
<!-- LOOP -->
<?php endforeach;?>
<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
<div class="line top10">
    <div class="paging-box" style=" width:98%; float:left">
        <ul id="pagination-digg">
            <?php  $paginator->counter(array('format' => 'Page %page% of %pages%'));?>
            <?php echo $paginator->prev("Prev",array('class'=>'next',"escape"=>false)); ?>
            <?php echo $paginator->numbers(array('modulus'=>4,'separator'=>null,'class'=>'navigasi1','span'=>false,'current'=>'current')); ?>
            <?php echo $paginator->next("Next",array('class'=>'next',"escape"=>false)); ?>   
        </ul>  
    </div>
</div>
<?php endif;?>
<?php endif;?>