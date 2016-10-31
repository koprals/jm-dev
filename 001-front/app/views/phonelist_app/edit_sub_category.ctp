<?php if(!empty($fSubcategory)):?>
<input type="button" value="&lt;&lt;Back" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/ListSubcategory/<?php echo $this->data["PhonelistSubcategory"]["phonelist_category_id"]?>'"/>
<h1>Edit Subkategori &quot;<?php echo $this->data["PhonelistSubcategory"]["name"]?>&quot;</h1>
<?php echo $form->create("PhonelistSubcategory",array("url"=>array("controller"=>$this->params["controller"],"action"=>"EditSubCategory"),"type"=>"file"))?>

<?php echo $form->input("id",array("type"=>"hidden"))?>
<?php echo $form->input("name",array("type"=>"text"))?>
<?php echo $form->input("phone",array("type"=>"text"))?>
<?php echo "Kategori ID: ".$form->select("phonelist_category_id",$list_category,$this->data["PhonelistSubcategory"]["phonelist_category_id"],array("empty"=>false,"label"=>"Kategory ID","default"=>$this->data["PhonelistSubcategory"]["phonelist_category_id"]))?><br />
<?php echo $form->input("phonelist_location_id",array("options"=>$locations,"label"=>"Location"))?>
<?php echo $form->file("photo")?>
<?php echo $form->end("Edit");?>
<?php else:?>
Subcategory tidak ditemukan!!
<?php endif;?>