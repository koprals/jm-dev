<?php 
switch($controller)
{
	case "DaftarMotor" :	
		$prefix	=	"motor";
		$h1		=	"MOTOR DIJUAL - ";
		break;
	case "MotorMurah" :
		$prefix	=	"motor_harga_di_bawah_9_juta";
		$h1		=	"MOTOR MURAH - ";
		break;
	case "MotorKredit" :
		$prefix	=	"motor_kredit";
		$h1		=	"MOTOR KREDIT - ";
		break;
	case "MotorGede" :
		$prefix	=	"motor_gede";
		$h1		=	"MOTOR GEDE - ";
		break;
	case "MotorKlasik" :
		$prefix	=	"motor_klasik";
		$h1		=	"MOTOR KLASIK - ";
		break;
}
?>
<?php if(!empty($children)):?>
<?php $count = 1;?>
<div class="text_title1">
    <div class="line1"><?php echo $h1.strtoupper($display_title)?></div>
</div>
<div class="line back1 rounded2 bottom10" style="padding-bottom:10px;">
    <div class="line1">
    	<div class="line top10">
			<?php foreach($children as $children):?>
            <?php $link	=	implode("_",array($prefix,$general->seoUrl($children['Parent']['name']." ".$children['Category']['name']),$general->seoUrl($display_city).".html"))?>
            <?php if($count%5==0):?>
                <a href="<?php echo $settings['site_url']?><?php echo $controller?>/<?php echo $children['Category']['id']?>/<?php echo $current_city?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18" title="<?PHP echo $children['Category']['name']?>"><?php echo $text->truncate($children['Category']['name'],15,array('ending'=>""))?></a>
            </div>
            <div class="line top10">
            <?php else:?>
            <a href="<?php echo $settings['site_url']?><?php echo $controller?>/<?php echo $children['Category']['id']?>/<?php echo $current_city?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18 right10" title="<?PHP echo $children['Category']['name']?>"><?php echo $text->truncate($children['Category']['name'],15,array('ending'=>""))?></a>
            <?php endif;?>
            
             <?php $count++;?>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php endif;?>