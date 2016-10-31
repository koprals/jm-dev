<?php 
$arr		=	array("MotorMurah","MotorKredit");
$controller	=	in_array($controller,$arr) ? $controller : "DaftarMotor";
?>
<?php 
switch($controller)
{
	case "DaftarMotor" :	
		$prefix	=	"motor";
		break;
	case "MotorMurah" :
		$prefix	=	"motor_harga_di_bawah_9_juta";
		break;
	case "MotorKredit" :
		$prefix	=	"motor_kredit";
		break;
	default:
		$prefix	=	"motor";
		break;
}
?>
<?php echo $javascript->link("menuaa")?>
<div class="menuaa">
    <div class="menu-head"></div>
    <?php
		echo $tree->generate($stuff,array('model' => 'Category','link'=>$settings['site_url']."{$controller}/","current_id"=>$current_id,'current_parent_id'=>$current_parent_id,'current_city'=>$current_city,"city_name"=>$city_name,'ROOT'=>$settings['site_url'],'class'=>'menu-left',"id"=>"bangke","prefix_seo"=>$prefix));
	?>
</div>