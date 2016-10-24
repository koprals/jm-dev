<?php $controller	=	($controller=="Home") ? "DaftarMotor" : $controller ;?>
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

<div class="text_title1" style="margin-top:10px;">
    <div class="line1"><h1 style="font:bold 15px Arial, Helvetica, sans-serif;color:#ffffff;text-shadow:0px 1px 3px #272727,0px 1px 3px #272727; margin-top:-5px;"><?php echo $h1.strtoupper($display_title)?></h1></div>
</div>
<?php $count = 1;?>
<div class="line back1 rounded2" style="padding-bottom:10px;">
    <div class="line1">
    	<div class="line top10">
			<?php foreach($daftar_kota as $id => $nama):?>
            <?php $link	=	implode("_",array($prefix,$general->seoUrl($display_category),$general->seoUrl($nama).".html"))?>
             
            <?php if($count%5==0):?>
                    <a href="<?php echo $settings['site_url']?><?php echo $controller?>/<?php echo $category_id?>/<?php echo $id?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18"><?php echo $nama?></a>
                </div>
                <?php if($count==20):?>
                <div class="line top10" id="more_open" onclick="$('#more').show(300);$('#more_open').hide();" style=" cursor:pointer;">
                    <div  style="float:none; display:block; margin:auto; width:120px; text-align:center; border:0px solid black;">
                        <span class="style1 text12 white bold normal">Lihat kota lainnya</span>
                    </div>
                </div>
                <div class="line" style="display:none" id="more">
                <?php endif;?>
                <div class="line top10">
            <?php else:?>
                <a href="<?php echo $settings['site_url']?><?php echo $controller?>/<?php echo $category_id?>/<?php echo $id?>/<?php echo $link?>" class="white text11 style1 bold normal kiri size18 right10"><?php echo $nama?></a>
            <?php endif;?>
            <?php $count++;?>
            <?php endforeach;?>
            </div>
            <div  style="float:none; display:block; margin:auto; width:100px; text-align:center; border:0px solid black;">
            	<a href="javascript:void(0)" onclick="$('#more').hide(300);$('#more_open').show(300);"><img src="<?php echo $this->webroot?>img/up_red.gif" style=" margin-top:-1px; border:none;" alt="up_red.gif"/></a>
            </div>
        </div>
    </div>
</div>