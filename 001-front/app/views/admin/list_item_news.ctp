
<?php if(!empty($data)):?>

<script>
function onClickPage(el,divName)
{
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.top;
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+100)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}

function DeleteItem(ID)
{
	$.getJSON("<?php echo $settings['site_url']?>Template/CheckLogin",function(data){
		if(data.status==false)
		{
			Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Maaf session login anda telah habis, silahkan login kembali atau refresh halaman ini.</span></div>",function(){window.location.reload()},{title:'Login expired.'});
		}
		else
		{
			Boxy.confirm("<div style='display:block;float:left;border:0px solid black;'><img src='<?php echo $this->webroot?>img/warning.png' style='float:left'> <div style='margin-top:10px;border:0px solid black;float:left'>Anda yakin akan menghapus item ini ? </span></div>", function() {
				$.get("<?php echo $settings['site_url']?>Admin/DeleteNews/"+ID,function(data){
					Boxy.alert("<div style='display:block;float:left;border:0px solid black;width:100%'><div style='width:auto;float:left;display:block;border:0px solid black;margin-right:5px;'><img src='<?php echo $this->webroot?>img/warning.png'></div><div style='margin-top:10px;border:0px solid black;float:left;width:80%;display:block;'>Data telah dihapus</span></div>",function(){
						$("#list_item").load("<?php echo $settings['site_url']?>Admin/ListItemNews",function(){});
					},{title:'Data has deleted.'});
					
				});
			},{title:"Konfirmasi hapus data"});
		}
	});	
}
</script>
<?php echo $paginator->options(array(
				'url'	=> implode("/",$this->params['pass']),
				'onclick'=>"return onClickPage(this,'#list_item');"
				));
?>
<style>
#pagination-digg a{
	border:1px solid grey;
}
#pagination-digg a:hover
{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
	border:1px solid #D3D1D1;
}
#pagination-digg .current{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
}
</style>
<div class="kiri bottom5 size100 top20">
	<div class="kiri size20">
		<input type="button" name="button" value="Add New Data" class="tombol1" onclick="location.href='<?php echo $settings['site_url']?>Admin/AddNews'"/>
	</div>
	<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
    <div class="paging-box" style="width:auto;float:right; border:0px solid black;">
        <ul id="pagination-digg" style="border:0px solid black; margin-left:-40px;">
            <?php  $paginator->counter(array('format' => 'Page %page% of %pages%'));?>
            <?php echo $paginator->prev("Prev",array('class'=>'next',"escape"=>false),'Prev',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
            <?php echo $paginator->numbers(array('modulus'=>4,'separator'=>null,'class'=>'navigasi1','span'=>false,'current'=>'current')); ?>
            <?php echo $paginator->next("Next",array('class'=>'next',"escape"=>false),'Next',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
        </ul>  
    </div>
	<?php endif;?>
</div>
<div class="kiri size100">
	<table width="100%" cellspacing="1" bgcolor="#ffffff" style="border:1px solid grey; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF;">
	  <tr style="background-color:#5E5E5E; text-align:center; font-weight:bold;">
	  	<td width="4%" height="28"><?php echo $paginator->sort('ID', 'News.id',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
		<td width="13%">Image</td>
		<td width="17%" height="28"><?php echo $paginator->sort('Title', 'News.title',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
		<td width="29%"><?php echo $paginator->sort('Desciption', 'News.description',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
		<td width="14%"><?php echo $paginator->sort('Status', 'News.status',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
		<td width="10%"><?php echo $paginator->sort('Created', 'News.created',array('class'=>'style1 white text12 normal bold','escape'=>false));?></td>
	    <td colspan="2">Action</td>
	  </tr>
	  <?php foreach($data as $data):?>
	  <tr style="color:#fff; background-color:#898989; font-weight:bold; height:60px;">
		<td height="29" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
			<a href="<?php echo $settings['site_url']?>News/Detail/<?php echo $data["News"]["id"]?>/<?php echo $general->seoUrl($data["News"]["title"])?>.html" class="style1 white text12 normal"><?php echo $data["News"]["id"]?></a>		</td>
		<td style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7">
			<img src="<?php echo $settings['showimages_url']?>?code=<?php echo $data['News']['id']?>&prefix=_tiny&content=News&w=50&h=50&nopict=noimages&time=<?php echo time()?>" style="border:1px solid #cccccc; padding:1px;"/>
		</td>
		<td style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><a href="<?php echo $settings['site_url']?>News/Detail/<?php echo $data["News"]["id"]?>/<?php echo $general->seoUrl($data["News"]["title"])?>.html" class="style1 white text12 normal"><?php echo chunk_split($data["News"]["title"],20,"<br>")?></a></td>
		<td align="center" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><?php echo chunk_split($data["News"]["ShortDesc"],20,"<br>")?></td>
		<td align="center" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><?php echo $data["News"]["SStatus"]?></td>
		<td align="center" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><?php echo date("d-M-Y h:i:s",strtotime($data["News"]["created"]))?></td>
	    <td width="6%" align="center" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><a href="<?php echo $settings['site_url']?>Admin/EditNews/<?php echo $data['News']['id']?>" class="style1 white text12 normal bold underline">Edit</a></td>
	    <td width="7%" align="center" style="border-right:1px dotted #c7c7c7;padding-left:10px;border-bottom:1px dotted #c7c7c7"><a href="javascript:void(0);" class="style1 white text12 normal bold underline" onclick="DeleteItem('<?php echo $data['News']['id']?>')">Delete</a></td>
	  </tr>
	  <?php endforeach;?>
	</table>
</div>
<?php else:?>
<div class="box_alert">
	<div class="alert">
		<img src="<?php echo $this->webroot?>img/warning.gif"/>
		Data tidak di temukan<br /><br />
		
		<input type="button" name="button" value="Add New Data" class="tombol1" onclick="location.href='<?php echo $settings['site_url']?>Admin/AddNews'"/>
	</div>
</div>
<?php endif;?>