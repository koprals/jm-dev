<!--[if lt IE 8]><style>
.wraptocenter_gambar span {
    display: inline-block;
    height: 100%;
}
</style><![endif]-->
<div class="line kiri size50 style1 text12 grey2">
	<?php echo $bread_crumb['bread']?>
</div>
<div class="line kiri size50 style1 text17 black bold top10">
	<?php echo $data['Parent']['name']." - ".$data['Category']['name']." (".$data['Product']['thn_pembuatan'].") "?>
</div>
<div class="line kiri size50 style1 text12 grey2 top10">
	<?php echo $data['Category']['name']?>, <span class="bold black1"><?php echo $bread_crumb['city']?></span>, dilihat <span class="bold black1"><?php echo $data['Product']['view']?></span> kali
</div>

<div class="line size100 top10" style="border:0px solid black; position:relative;">
	<div class="price">&nbsp;</div>
	<div class="shadowgambar">
    	<div class="tengahgambar">
            <div class="wraptocenter_gambar">
                <span></span><img src="<?php echo $this->webroot?>img/testbig.gif" style="width:100%; height:380px;"/>
            </div>
        </div>
    </div>
</div>