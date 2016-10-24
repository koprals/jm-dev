<script>
var count	=	0;
function Delete(anc_files_id)
{
	count--;
	$("#div_"+anc_files_id).remove();
	$("#AncSubcategoryEditSubCategoryForm").append('<input type="hidden" name="data[AncSubcategory][delete][]" value="'+anc_files_id+'" />');
}

function AddFile()
{
	if(count >=5)
	{
		alert('Maksimal file upload 5 files!');
		return;
	}
	count++;
	var html='\
		<div style="width:100%; float:left; display:block; margin-bottom:10px;" id="div_'+count+'">\
			<div style="width:600px; float:left; display:block; background-color:#999999; border-bottom:1px solid #333333; height:50px; vertical-align:middle;">\
				<div style="width:300px; float:left; display:block; margin-top:10px;margin-left:10px;">\
					<input type="file" name="data[AncSubcategory][addfiles][]">\
					<a href="javascript:void(0)" onclick="DeleteAddFiles(\''+count+'\')">DELETE</a>\
				</div>\
			</div>\
		</div>\
	';
	$("#add").append(html);
}

function DeleteAddFiles(id_add_file)
{
	$("#div_"+id_add_file).remove();
	count--;
}
</script>

<?php if(!empty($fSubcategory)):?>
<input type="button" value="&lt;&lt;Back" onclick="location.href='<?php echo $this->webroot.$this->params["controller"]?>/ListSubcategory/<?php echo $this->data["AncSubcategory"]["anc_category_id"]?>'"/>
<h1>Edit Subkategori &quot;<?php echo $this->data["AncSubcategory"]["name"]?>&quot;</h1>
<?php echo $form->create("AncSubcategory",array("url"=>array("controller"=>$this->params["controller"],"action"=>"EditSubCategory","?"=>"debug=1"),"type"=>"file"))?>

<?php echo $form->input("id",array("type"=>"hidden"))?>
<?php echo $form->input("name",array("type"=>"text"))?>
Description :<br />
<?php echo $form->textarea("description",array("style"=>"width:400px;height:200px","label"=>"Description"))?><br/>
<?php echo "Kategori ID: ".$form->select("news_category_id",$list_category,$this->data["AncSubcategory"]["anc_category_id"],array("empty"=>false,"label"=>"Kategory ID","default"=>$this->data["AncSubcategory"]["anc_category_id"]))?><br />
<br/>


<a href="javascript:void(0)" onclick="AddFile()">ADD</a>&nbsp;&nbsp;<a href="<?php echo $this->webroot.$this->params["controller"]?>/Preview/<?php echo $this->data["AncSubcategory"]["id"]?>" target="_blank">View</a><br />
<?php if(!empty($this->data['AncFiles'])):?>
ATTACH FILES:<br />
<?php foreach($this->data['AncFiles'] as $AncFiles):?>
<script>count++;</script>
<div style="width:100%; float:left; display:block; margin-bottom:10px;" id="div_<?php echo $AncFiles['id']?>">
	<div style="width:600px; float:left; display:block; background-color:#999999; border-bottom:1px solid #333333; height:50px; vertical-align:middle;">
		<div style="width:120px; float:left; display:block; margin-top:10px; margin-left:10px;">
			<a href="<?php echo $this->webroot.$this->params["controller"]?>/DownloadFile/<?php echo $AncFiles['id']?>"><?php echo $AncFiles['filename']?></a>
		</div>
		<div style="width:300px; float:left; display:block; margin-top:10px;margin-left:10px;">
			<?php echo $form->file("files",array("name"=>"data[AncSubcategory][files][".$AncFiles['id']."]"))?>
			<a href="javascript:void(0)" onclick="Delete('<?php echo $AncFiles['id']?>')">DELETE</a>
		</div>
	</div>
</div>
<?php endforeach;?>
<?php endif;?>
<div id="add">

</div>
<?php echo $form->checkbox("send")."Send notification to user."?>
<?php echo $form->end("Edit");?>
<?php else:?>
Subcategory tidak ditemukan!!
<?php endif;?>