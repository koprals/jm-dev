<input type="button" value="&lt;&lt;Home" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/Index'"/>
<h1>Tambah kategori untuk menu &quot; <?php echo $type_name?>&quot;</h1>
<?php echo $form->create("PhonelistCategory",array("url"=>array("controller"=>$this->params["controller"],"action"=>"AddCategory",$phonelist_type_id)))?>
<?php echo $form->input("name",array("type"=>"text"))?>
<?php echo $form->end("Add");?>