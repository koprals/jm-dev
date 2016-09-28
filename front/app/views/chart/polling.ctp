<style>
table{
	font-family:Arial,Verdana, Geneva, sans-serif;
	font-size:12px;
	background-color:#FFC;
	border:1px solid red;
	border-collapse:collapse;
}
</style>

<?php echo $form->create("PollingAnswer",array("url"=>array("controller"=>"Chart","action"=>"Polling")))?>
<table width="503" border="1" cellspacing="2" cellpadding="1">
  <tr>
    <td height="27" colspan="2"><?php echo $data['Polling']['title']?></td>
  </tr>
  <?php foreach($data['PollingOption'] as $option):?>
  <tr>
    <td width="24" height="30"><input type="radio" id="Option_<?php echo $option["id"]?>" name="data[PollingAnswer][polling_option_id]" value="<?php echo $option["id"]?>"></td>
    <td width="463"><label for="Option_<?php echo $option["id"]?>"><?php echo $option['title']?></label></td>
  </tr>
  <?php endforeach;?>
  <tr>
    <td height="41" colspan="2"><?php echo $form->submit("Kirim")?></td>
  </tr>
</table>
<?php echo $form->end();?>

<div id="Result">
</div>