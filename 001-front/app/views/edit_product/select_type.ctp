<?php if(!empty($category)):?>
	<?php echo $form->select("Product.subcategory",$category,$selected,array("class"=>"text7","label"=>"false","escape"=>false,"style"=>"width: 160px;","empty"=>false,"id"=>"subcat_id","onchange"=>"Item(this.value)"));?>
    
    <?php //echo $form->input("Product.subcategory_name",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;","value"=>0,"type"=>"hidden"))?>
<?php else:?>
	<?php //echo $form->input("Product.subcategory_name",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;","value"=>1,"type"=>"text"))?>
    
    <?php //echo $form->input("Product.newsubcategory",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;"))?>
<?php endif;?>