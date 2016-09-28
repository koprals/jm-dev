<?php if(!empty($category)):?>
<?php echo $form->select("Product.subcategory_id",$category,$selected,array("class"=>"sel1","label"=>"false","escape"=>false,"style"=>"width:100%","empty"=>false));?>
<?php echo $form->input("Product.subcategory_name",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;","value"=>0,"type"=>"hidden"))?>
<?php else:?>
<?php echo $form->input("Product.subcategory_name",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;","value"=>1,"type"=>"hidden"))?>

<?php echo $form->input("Product.newsubcategory",array("class"=>"all_input3","div"=>false,"label"=>false,"type"=>"text","style"=>"width:48.8%; float:left; height:16px;"))?>
<?php endif;?>