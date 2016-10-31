<?php if(!empty($fSubcategory)):?>
<input type="button" value="&lt;&lt;Back" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/ListSubcategory/<?php echo $this->data["NewsSubcategory"]["news_category_id"]?>'"/>
<h1>Edit Subkategori &quot;<?php echo $this->data["NewsSubcategory"]["name"]?>&quot;</h1>
<?php echo $form->create("NewsSubcategory",array("url"=>array("controller"=>$this->params["controller"],"action"=>"EditSubCategory"),"type"=>"file"))?>

<?php echo $form->input("id",array("type"=>"hidden"))?>
<?php echo $form->input("name",array("type"=>"text"))?>
<?php echo $form->input("description",array("type"=>"text"))?>
<?php echo $form->input("url",array("type"=>"text"))?>
<?php echo "Kategori ID: ".$form->select("news_category_id",$list_category,$this->data["NewsSubcategory"]["news_category_id"],array("empty"=>false,"label"=>"Kategory ID","default"=>$this->data["NewsSubcategory"]["news_category_id"]))?><br />
<?php echo $form->file("photo")?>
<?php echo $form->end("Edit");?>
<?php else:?>
Subcategory tidak ditemukan!!
<?php endif;?>