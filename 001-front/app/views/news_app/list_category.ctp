<input type="button" value="&lt;&lt;Home" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/Index'"/>
<input name="" type="button" value="add category" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/AddCategory/<?php echo $news_type_id?>'"/>
<h1> Daftar Kategori Menu "<?php echo $type_name?>"</h1>
<table width="499" border="1" style="margin-top:10px">
  <tr>
    <td width="6%">No</td>
	<td width="32%">Name</td>
	<td colspan="2"><div align="center">Action</div></td>
  </tr>
  <?php $count	=	0;?>
  <?php foreach($menu as $data):?>
  <?php $count++;?>
  <tr>
    <td><?php echo $count;?></td>
    <td><?php echo $data["NewsCategory"]["name"]?></td>
    <td width="13%"><a href="<?php echo $this->webroot.$this->params["controller"]?>/AddSubcategory/<?php echo $data["NewsCategory"]["id"]?>">Add Child</a></td>
    <td width="16%"><a href="<?php echo $this->webroot.$this->params["controller"]?>/ListSubcategory/<?php echo $data["NewsCategory"]["id"]?>">View Child</a></td>
  </tr>
  <?php endforeach;?>
</table>
