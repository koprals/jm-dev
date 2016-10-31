<input type="button" value="&lt;&lt;Category" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/ListCategory/<?php echo $type_id?>'"/>
<input name="" type="button" value="add subcategory" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/AddSubcategory/<?php echo $category_id?>'"/>

<h1> Daftar Subkategori dari Kategori "<?php echo $category_name?>"</h1>

<table width="593" border="1" style="margin-top:10px">
  <tr>
    <td width="5%">No</td>
	<td width="19%">Images</td>
    <td width="19%">Name</td>
    <td width="19%">Description</td>
    <td width="19%">Url</td>
    <td colspan="2">Action</td>
  </tr>
  <?php $count	=	0;?>
  <?php foreach($menu as $data):?>
  <?php $count++;?>
  <tr>
    <td><?php echo $count;?></td>
    <td><img src="<?php echo $data["NewsSubcategory"]["images1"]?>"></td>
    <td><?php echo $data["NewsSubcategory"]["name"]?></td>
    <td><?php echo $data["NewsSubcategory"]["description"]?></td>
    <td><?php echo $data["NewsSubcategory"]["url"]?></td>
    <td width="10%"><a href="<?php echo $this->webroot.$this->params["controller"]?>/EditSubCategory/<?php echo $data["NewsSubcategory"]["id"]?>">Edit</a></td>
    <td width="9%"><a href="<?php echo $this->webroot.$this->params["controller"]?>/DeleteSubCategory/<?php echo $data["NewsSubcategory"]["id"]?>">Delete</a></td>
  </tr>
  <?php endforeach;?>
</table>
