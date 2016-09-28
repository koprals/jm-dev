<script>
var count	=	1;
function AddFile()
{
	count++;
	if(count<=5)
	{
		$("#copied").append($("#original").html()+"<br/>");
	}
	else
	{
		alert("Maksimal file upload 5 files!");
	}
}
</script>
<input type="button" value="&lt;&lt;Home" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/Index'"/>
<h1>Tambah Subkategori Untuk kategori &quot;<?php echo $category_name?>&quot;</h1>
<?php echo $form->create("AncSubcategory",array("url"=>array("controller"=>$this->params["controller"],"action"=>"AddSubcategory",$category_id,"?"=>"debug=1"),"type"=>"file"))?>
<?php echo $form->input("name",array("type"=>"text"))?>
Description :<br />
<?php echo $form->textarea("description",array("style"=>"width:400px;height:200px","label"=>"Description"))?>
<div>
<br />
Hanya diperbolehkan extension (*jpg,*gif,*png,*doc,*docx,*xls,*xlsx,*ppt,*pdf);<br />
<span id="original"><?php echo $form->file("files",array("name"=>"data[AncSubcategory][files][]"))?></span><a href="javascript:void(0)" onclick="return AddFile()">add file</a>
</div>
<div id="copied"></div>
<br />
<?php echo $form->checkbox("send")."Send notification to user."?>
<?php echo $form->end("Add");?>