<table width="200" border="0">
  <tr>
  	<?php foreach($menu as $data):?>
    <td width="30%"><a href="<?php echo $this->webroot.$this->params["controller"]?>/ListCategory/<?php echo $data["NewsType"]["id"]?>"><?php echo $data["NewsType"]["name"]?></a></td>
	<?php endforeach;?>
  </tr>
</table>