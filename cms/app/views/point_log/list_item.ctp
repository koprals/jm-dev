<script>
var total_record = <?php echo $paginator->counter(array('format' => __('%current%', true)))?>;
var total_all_record = <?php echo $paginator->counter(array('format' => __('%count%', true)))?>;
var total_spread = total_all_record - total_record;


$(document).ready(function(){
	$( "#SearchFrom" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"yy-mm-dd",
		navigationAsDateFormat:true,
		gotoCurrent:true,
		yearRange:'<?php echo date("Y")-3?>:<?php echo date("Y")?>',
		onSelect: function(dateText, inst)
		{
			var date_explode	=	dateText.split("-");
			if(date_explode[0]=='<?php echo date("Y")?>')
			{
				$("#SearchFrom").val('<?php echo (date("Y")-3)?>-'+date_explode[1]+'-'+date_explode[2]);	
			}
			$("#SearchFrom").show();
		}
	});
	
	$( "#SearchTo" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:"yy-mm-dd",
		navigationAsDateFormat:true,
		gotoCurrent:true,
		yearRange:'<?php echo date("Y")-3?>:<?php echo date("Y")?>',
		onSelect: function(dateText, inst)
		{
			var date_explode	=	dateText.split("-");
			if(date_explode[0]=='<?php echo date("Y")?>')
			{
				$("#SearchTo").val('<?php echo (date("Y")-3)?>-'+date_explode[1]+'-'+date_explode[2]);	
			}
			$("#SearchTo").show();
		}
	});
});

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
		'controller'	=> 'PointLog',
		'action'		=> 'ListItem/'.$user_id
	),
	'onclick'=>"return onClickPage(this,'#list_item');")
);?>
<div id="output"></div>
<?php echo $form->create("Search",array("onsubmit"=>"return Search()"))?>   

<div class="line1">
    <div class="left" style="width:60%;margin-top:8px;">
        <div class="text7" style="width:100%; float:left">
            <span style="float:left;">page</span>
            <?php if($paginator->hasPrev()):?>
                <a href="<?php echo $settings['cms_url']?>PointLog/ListItem/<?php echo $user_id?>/page:<?php echo ($page-1)?>/limit:<?php echo $viewpage?>" class="nav_table_left" onclick="return onClickPage(this,'#list_item')"></a>
                
            <?php endif;?>
            <input type="text" name="textfield" id="page" class="all_input2" style="width:30px; float:left; height:16px; margin-top:-5px;" value="<?php echo $page;?>" />
            <?php if($paginator->hasNext()):?>
                <a href="<?php echo $settings['cms_url']?>PointLog/ListItem/<?php echo $user_id?>/page:<?php echo ($page+1)?>/limit:<?php echo $viewpage?>" class="nav_table_right" onclick="return onClickPage(this,'#list_item')"></a>
            <?php endif;?>
            <span style="float:left;"><?php echo $paginator->counter(array('format' => 'of %pages% pages'));?> | View </span>
            
            <?PHP echo $form->select("view",array(1=>1,5=>5,10=>10,20=>20,100=>100,200=>200,1000=>1000),$viewpage,array("class"=>"text7","style"=>"width:auto; height:22px; float:left; margin:-5px 3px 0 3px; border:1px solid #c8c8c8; cursor:pointer;","onchange"=>"onClickPage('".$settings['cms_url']."PointLog/ListItem/".$user_id."/limit:'+this.value,'#list_item')","empty"=>false))?>
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
            <td width="5%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('ID', 'PointsHistory.id',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="14%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Name', 'ActionTypes.name',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="29%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Action', 'UserLogs.actionText',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="14%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Value', 'PointsHistory.value',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="12%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Point Before', 'PointsHistory.points_before',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="13%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Point After', 'PointsHistory.points_after',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
            <td width="13%" class="table_text1" style="border:1px solid #D3C8AA;"><?php echo $paginator->sort('Created', 'PointsHistory.created',array('class'=>'table_text1','escape'=>false,'current'=>'current_sort'));?></td>
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
        
        <tr bgcolor="<?php echo $back?>" height="30">
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo $data['PointsHistory']['id']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['ActionTypes']['name']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['UserLogs']['actionText']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['PointsHistory']['value']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['PointsHistory']['points_before']?></td>
            <td class="table_text3" style="border:1px solid #D3C8AA;"><?php echo $data['PointsHistory']['points_after']?></td>
            <td class="table_text2" style="border:1px solid #D3C8AA;"><?php echo date("d-M-Y H:i:s",strtotime($data['PointsHistory']['created']))?></td>
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
<?php echo $form->end();?> 