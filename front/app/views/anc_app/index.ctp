<table  border="0">
  <tr>
  	<?php foreach($menu as $data):?>
    <td width="150"><a href="<?php echo $this->webroot.$this->params["controller"]?>/ListSubcategory/<?php echo $data["AncCategory"]["id"]?>"><?php echo $data["AncCategory"]["name"]?></a></td>
	<?php endforeach;?>
  </tr>
</table>