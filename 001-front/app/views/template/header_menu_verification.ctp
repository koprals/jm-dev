<script>
function SubmitSearch()
{
	$category_id	=	$("#SearchCategoryId").val();
	$city_id		=	$("#SearchCityId").val();
	$.get("<?php echo $settings['site_url']?>Template/GetUrlSearch",{'category_id':$category_id,'city_id':$city_id},function(url){
		if(url)
		{
			location.href=url;
		}
		else
		{
			alert('Maaf terjadi gangguna koneksi di server. Cobalah sekali lagi!');
		}
	});
	return false;
}
</script>
<!-- Header -->
<div class="header">
	<div class="body_all">
    	<div class="line">            
            <div class="kiri size65">
            	<div class="line" style="min-height:10px;">
                	<?php if($is_login=="0" && !empty($rand_detail['User']['id'])):?>
                	<span class="white text11 style1 bold normal">Welcome <?php echo $rand_name?>, <a href="<?php echo $settings['site_url']?>Users/Login" class="white text11 style1 bold normal" style="text-decoration:underline;">Sign In</a> (Not <a href="<?php echo $settings['site_url']?>Users/DeleteRandUser"class="white text11 style1 bold normal" style="text-decoration:underline;">you</a>?)</span>
                    <?php endif;?>
                </div>
                <div class="line">
                    <a href="<?php echo $settings['site_url']?>" class="logo" title="Kembali ke beranda."><img src="<?php echo $this->webroot?>img/logo_beta.png" border="0"/></a>
                    <a href="<?php echo $settings['site_url']?>" class="style1 white text13 normal shadow1 bold kiri top10 left60" title="Kembali ke beranda.">BERANDA</a>
                    <a href="<?php echo $settings['site_url']?>ManageProducts/Index" class="style1 white text13 normal shadow1 bold kiri top10 left30" title="Kelola iklan anda di sini.">KELOLA IKLAN</a>
                    <a href="<?php echo $settings['site_url']?>ManageProducts/Index/1" class="style1 white text13 normal shadow1 bold kiri top10 left30" title="Motor anda sudah laku ?, klik di sini.">TERJUAL</a>
                </div>
            </div>
            <div class="kanan size30" style="border:0px solid black; position:relative;">
            	<a href="<?php echo $settings['site_url']?>Cpanel/AddProduct" title="Ayo pasang iklan anda disini."><img src="<?php echo $this->webroot?>img/pasang_iklan.png" border="0" class="kiri" /></a>
                <div style="border:0px solid black; position:relative;width:450px;">
                    <a href="<?php echo $settings['site_url']?>Cpanel/UpdateProfile" class="style1 white text12 normal shadow1 bold kiri top20" title="Klik di sini untuk masuk ke control panel anda."><?php echo $fullname?></a>
                    <a href="<?php echo $settings['site_url']?>Users/LogOut" title="Anda yakin akan keluar dari <?php echo $settings['site_name']?> ?" class="style1 white text12 normal shadow1 bold kiri left10 top20">Log out</a>
                </div>
            </div>
        </div>
		<div class="line top-15">
        	
        	<div class="kiri size75">
   	      		<div class="kiri style1 white bold text16 shadow1 top3 right5 left10">Cari Motor</div>
                <div class="kiri right5">
                <?php echo $form->select("Search.category_id",$category,false,array("div"=>false,"label"=>false,"error"=>false,"id"=>"SearchCategoryId","empty"=>false,"style"=>"width:95px;"))?>
                    
                    <script type="text/javascript">
					//<![CDATA[
						$("#SearchCategoryId").selectbox().bind('change', function(){
							$('<div>Value of #default-usage-select changed to: '+$(this).val()+'</div>').appendTo('#demo-default-usage .demoTarget').fadeOut(5000, function(){
								$(this).remove();
							});
						});
					//]]>
					</script>  
                </div>                      		
                <div class="kiri right5">
                	<?php echo $form->select("Search.city_id",$ProvinceGroup,false,array("div"=>false,"label"=>false,"error"=>false,"id"=>"SearchCityId","empty"=>false,"style"=>"width:95px;"))?>
                    <script type="text/javascript">
					//<![CDATA[
						$("#SearchCityId").selectbox().bind('change', function(){
							$('<div>Value of #doel changed to: '+$(this).val()+'</div>').appendTo('#doel .demoTarget').fadeOut(5000, function(){
								$(this).remove();
							});
						});
					//]]>
					</script>
                </div>              	
                <input type="submit" name="button" id="button" value="" class="search kiri" onclick="return SubmitSearch();"/>
                <a href="<?php echo $settings['site_url']?>Search" class="style1 white text12 normal shadow1 bold kiri top5 left5">PENCARIAN DETAIL</a>
            </div> 
            <div class="kanan size10" style="border:0px solid black;">
                <a href="http://www.facebook.com/JualanMotorCom" target="_blank" class="kiri top10" style="border:0px solid black;" title="lihat facebook page kami di http://www.facebook.com/JualanMotorCom">
                <img src="<?php echo $this->webroot?>img/icn_fb.gif" border="0"/>
                </a>
                <a href="https://twitter.com/JualanMotor" target="_blank" class="kiri top10 left5" title="follow kami @JualanMotor"><img src="<?php echo $this->webroot?>img/icn_twitter.gif" border="0" /></a>
            </div>       	
        </div>
    </div>
</div>  
<!-- End Header -->
<!-- Menu -->
<?php
	$menu	=	array(
					array(
						"code"	=>	"iklan_terbaru",
						"url"	=>	"DaftarMotor/all_categories/all_cities/motor_dijual.html",
						"name"	=>	"IKLAN TERBARU"
					),
					array(
						"code"	=>	"motor_murah",
						"url"	=>	"MotorMurah/all_categories/all_cities/motor_murah_dijual.html",
						"name"	=>	"MOTOR MURAH"
					),
					array(
						"code"	=>	"motor_kredit",
						"url"	=>	"MotorKredit/all_categories/all_cities/motor_kredit.html",
						"name"	=>	"MOTOR KREDIT"
					),
					array(
						"code"	=>	"daftar_harga",
						"url"	=>	"DaftarHarga/all_categories/daftar_harga_motor.html",
						"name"	=>	"DAFTAR HARGA"
					),
					array(
						"code"	=>	"dealer",
						"url"	=>	"Profil/ListMember/all_cities/daftar_dealer_motor.html",
						"name"	=>	"DEALER"
					),
					array(
						"code"	=>	"undang_teman",
						"url"	=>	"Users/InviteFriends/undang_teman.html",
						"name"	=>	"UNDANG TEMAN"
					)
				);
?>
<div id="menu_header">
	<div class="body_all">    	    	
        <ul>
        	<?php foreach($menu as $menu):?>
            <?php $current	=	($menu['code']==$current_menu) ? "class='current'" : ""?>
            <li><a href="<?php echo $settings['site_url'].$menu["url"]?>" <?php echo $current?>><?php echo strtoupper($menu["name"])?></a></li>
            <?php endforeach;?>
        </ul>
    </div>	
</div>
<!-- End Menu -->