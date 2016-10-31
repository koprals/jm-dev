<table border="0">
  <tr>
  	<?php foreach($menu as $data):?>
    <td><a href="<?php echo $this->webroot.$this->params["controller"]?>/ListCategory/<?php echo $data["PhonelistType"]["id"]?>"><?php echo $data["PhonelistType"]["name"]?></a></td>
	<?php endforeach;?>
  </tr>
</table>