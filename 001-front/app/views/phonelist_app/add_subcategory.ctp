<input type="button" value="&lt;&lt;Home" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/Index'"/>
<h1>Tambah Subkategori Untuk kategori &quot;<?php echo $category_name?>&quot;</h1>
<?php echo $form->create("PhonelistSubcategory",array("url"=>array("controller"=>$this->params["controller"],"action"=>"AddSubcategory",$category_id,"?"=>"debug=1"),"type"=>"file"))?>
<?php echo $form->input("name",array("type"=>"text"))?>
<?php echo $form->input("phone",array("type"=>"text"))?>
<?php echo $form->input("phonelist_location_id",array("options"=>$locations,"label"=>"Location"))?>
<?php echo $form->file("photo")?>
<?php echo $form->end("Add");?>