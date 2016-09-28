<style>
.container{
	width:235px;
	height:auto;
	background-color:#8a8a8a;
	float:left;
	display:block;
}
.container-bottom{
	width:99%;
	height:40px;
	float:left;
	display:block;
	border:0px solid black;
	margin-bottom:5px;
}
.container-tengah{
	width:93%;
	margin:7px auto;
	float:none;
}
.wraptocenter {
    display: table-cell;
    text-align: center;
    vertical-align: middle;
   	width:235px;
	height:250px;
	background-color:#ffffff;
	border:1px solid #616060;
	position:relative;
}
.wraptocenter * {
    vertical-align: middle;
}
/*\*//*/
.wraptocenter {
    display: block;
}
.wraptocenter span {
    display: inline-block;
    height: 100%;
    width: 1px;
}
/**/

</style>
<!--[if lt IE 8]><style>
.wraptocenter span {
    display: inline-block;
    height: 100%;
}
</style><![endif]-->

<div class="container">
	<div class="container-tengah">
        <div class="wraptocenter">
        	<?php if($data['Product']['sold']==1):?>
        	<div style="background-image:url(<?php echo $this->webroot?>img/sold.png);width:217px;height:250px; position:absolute; z-index:1;top:10px">&nbsp;</div>
            <?php endif;?>
        	<span></span><img src="<?php echo $settings['showimages_url']."?code=".$data['ProductImage']['id']."&prefix=_195_250&content=ProductImage&w=195&h=250"?>" border="0"/>
        </div>
        <?php if($PM==0):?>
        <div class="container-bottom">
        	<?php if($data['Product']['user_id']!=$profile['User']['id']):?>
                <a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data['Product']['id']?>" class="style1 white normal text12 bold top10 kiri right5"><img src="<?php echo $this->webroot?>img/action_postcomment.gif" border="0" style="vertical-align:middle; margin-right:5px;"/ >Komentar</a>
                <a href="<?php echo $settings['site_url']?>Iklan/SendMessage/<?php echo $data['Product']['id']?>" class="style1 white normal text12 bold top10 kanan"><img src="<?php echo $this->webroot?>img/newmessage16.gif" border="0" style="vertical-align:middle; margin-right:5px;"/ >Hubungi Penjual</a>
            <?php else:?>
            	<a href="<?php echo $settings['site_url']?>Iklan/Detail/<?php echo $data['Product']['id']?>" class="style1 white normal text12 bold top10 kiri right5"><img src="<?php echo $this->webroot?>img/action_login.gif" border="0" style="vertical-align:middle; margin-right:5px;"/ >Lihat Detail</a>
            	<a href="<?php echo $settings['site_url']?>EditProduct/Index/<?php echo $data['Product']['id']?>" class="style1 white normal text12 bold top10 kanan"><img src="<?php echo $this->webroot?>img/b_edit.gif" border="0" style="vertical-align:middle; margin-right:5px;"/ >Edit Iklan</a>
            <?php endif;?>
        </div>
        <?php else:?>
        <div class="container-bottom">
        	<div class="style1 white text17 bold top10 kiri"><?php echo $number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp ","places"=>null,"after"=>null));?></div>
        </div>
        <?php endif ?>
    </div>
</div>