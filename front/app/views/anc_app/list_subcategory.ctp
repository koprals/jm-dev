<input type="button" value="&lt;&lt;Back" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>'"/>
<input name="" type="button" value="add subcategory" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/AddSubcategory/<?php echo $category_id?>'"/>

<h1> Daftar Subkategori dari Kategori "<?php echo $category_name?>"</h1>

<table border="1" style="margin-top:10px">
  <tr>
    <td width="100">No</td>
    <td width="200">Name</td>
    <td width="200">Description</td>
    <td colspan="2">Action</td>
  </tr>
  <?php $count	=	0;?>
  <?php foreach($menu as $data):?>
  <?php $count++;?>
  <tr>
    <td><?php echo $count;?></td>
    <td><?php echo $data["AncSubcategory"]["name"]?></td>
    <td><?php echo substr($data["AncSubcategory"]["description"],0,100)?></td>
    <td width="10%"><a href="<?php echo $this->webroot.$this->params["controller"]?>/EditSubCategory/<?php echo $data["AncSubcategory"]["id"]?>">Edit</a></td>
    <td width="9%"><a href="<?php echo $this->webroot.$this->params["controller"]?>/DeleteSubCategory/<?php echo $data["AncSubcategory"]["id"]?>">Delete</a></td>
  </tr>
  <?php endforeach;?>
</table>
