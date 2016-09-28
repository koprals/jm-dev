<script>
var total_record = <?php echo $paginator->counter(array('format' => __('%current%', true)))?>;
var total_all_record = <?php echo $paginator->counter(array('format' => __('%count%', true)))?>;
var total_spread = total_all_record - total_record;

</script>
<style>
	.trCpanelHover
	{
		background-color:#cde99f;
		color: #000000;
	}
	.trCpanelChecked
	{
		background-color:#cde99f;
		color: #000000;
	}

</style>

<?php echo $paginator->options(array(
	'url'	=> array(
		'controller'	=> 'EmailLog',
		'action'		=> 'ListItem/'.$user_id
	),
	'onclick'=>"return onClickPage(this,'#list_item');")
);?>
<div id="output"></div>
<div class="line1">
    <div class="left" style="width:60%;margin-top:8px;">
        <div class="text7" style="width:100%; float:left">
            <span style="float:left;">page</span>
            <?php if($paginator->hasPrev()):?>
                <a href="<?php echo $settings['cms_url']?>EmailLog/ListItem/<?php echo $user_id?>/page:<?php echo ($page-1)?>/limit:<?php echo $viewpage?>" class="nav_table_left" onclick="return onClickPage(this,'#list_item')"></a>
                
            <?php endif;?>
            <input type="text" name="textfield" id="page" class="all_input2" style="width:30px; float:left; height:16px; margin-top:-5px;" value="<?php echo $page;?>" />
            <?php if($paginator->hasNext()):?>
                <a href="<?php echo $settings['cms_url']?>EmailLog/ListItem/<?php echo $user_id?>/page:<?php echo ($page+1)?>/limit:<?php echo $viewpage?>" class="nav_table_right" onclick="return onClickPage(this,'#list_item')"></a>
            <?php endif;?>
            <span style="float:left;"><?php echo $paginator->counter(array('format' => 'of %pages% pages'));?> | View </span>
            
            <?PHP echo $form->select("view",array(1=>1,5=>5,10=>10,20=>20,100=>100,200=>200,1000=>1000),$viewpage,array("class"=>"text7","style"=>"width:auto; height:22px; float:left; margin:-5px 3px 0 3px; border:1px solid #c8c8c8; cursor:pointer;","onchange"=>"onClickPage('".$settings['cms_url']."EmailLog/ListItem/".$user_id."/limit:'+this.value,'#list_item')","empty"=>false))?>
            <span style="float:left;">per page | <?php echo $paginator->counter(array('format' => 'Total %count% records found'));?></span>
        </div>
    </div>
</div>

<!-- Table -->
<div class="line1">
  <div class="table1">
        <div class="line2">
            &nbsp;
    </div>
    </div>
    
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-color:#dadfe0; border-collapse:collapse;">
        <tr class="table2" height="30">
            <td width="5%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('ID', 'EmailLog.id',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="14%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('To', 'EmailLog.to',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="19%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('From', 'EmailLog.from',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="25%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Subject', 'EmailLog.subject',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="15%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Type', 'EmailSettings.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="15%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Last Send', 'EmailLog.last_send',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="7%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Status', 'EmailLog.status',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
        </tr>
        
        <tr class="table3" style="border:1px solid #D3C8AA;">
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                 
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
                
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;</td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
               
            </td>
            <td style="border:1px solid #D3C8AA;">&nbsp;
            	
            </td>
        </tr>
        
        <?php if(count($data)>0):?>
        <?php $count=0;?>
        <?php foreach($data as $data):?>
        <?php $count++?>
        <?php $back	=	($count%2==0) ? "#f6f6f6" : "#FFFFFF";?>
        <?php $send	=	($data['EmailLog']['status']==1) ? "Send" : "Not Send";?>
        <tr bgcolor="<?php echo $back?>" height="30">
            <td class="table_text2" style="border:1px solid #D3C8AA;"><a href="<?php echo $settings['cms_url']?>EmailLog/Detail/<?php echo $data['EmailLog']['id']."/".$user_id."/".$page?>" class="table_text1" onClick="return onClickPage(this,'#list_item')"><?php echo $data['EmailLog']['id']?></a></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><a href="<?php echo $settings['cms_url']?>EmailLog/Detail/<?php echo $data['EmailLog']['id']."/".$user_id."/".$page?>" class="table_text1" onClick="return onClickPage(this,'#list_item')"><?php echo $data['EmailLog']['to']?></a></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['EmailLog']['from']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['EmailLog']['subject']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php if(empty($data['EmailSettings']['name'])){echo "-";}else{echo  $data['EmailSettings']['name'];}?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo date("d-M-Y H:i:s",$data['EmailLog']['last_send'])?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA; text-align:center"><?php echo $send ?></td>
        </tr>
        <?php endforeach;?>
        <?php else:?>
        <tr height="130">
            <td colspan="26">
                <div class="alert">
                    <img src="<?php echo $this->webroot?>img/icn_error.png" style=" vertical-align:middle;"/>
                    Data tidak di temukan
                </div>
            </td>
        </tr>
        <?php endif;?>
    </table>	
</div>                        
<!-- End Table -->