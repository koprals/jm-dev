<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url>
        <loc><?php echo $settings['site_url']?></loc>
        <image:image>
			<image:loc><?php echo $settings['site_url']?>img/logo_big</image:loc>
        </image:image>
        <changefreq>daily</changefreq>
        <priority>1.0</priority> 
    </url>
    <url>
        <loc><?php echo $settings['site_url']?>DaftarMotor/all_categories/all_cities/motor_dijual.html</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority> 
    </url>
    <url>
        <loc><?php echo $settings['site_url']?>MotorMurah/all_categories/all_cities/motor_murah_dijual.html</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority> 
    </url>
    <url>
        <loc><?php echo $settings['site_url']?>MotorKredit/all_categories/all_cities/motor_kredit.html</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority> 
    </url>
    <url>
        <loc><?php echo $settings['site_url']?>DaftarHarga/all_categories/daftar_harga_motor.html</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority> 
    </url>
    <url>
        <loc><?php echo $settings['site_url']?>Profil/ListMember/all_cities/daftar_dealer_motor.html</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority> 
    </url>
    <url>
        <loc><?php echo $settings['site_url']?>Users/InviteFriends/undang_teman.html</loc>
    </url>
    <url>
        <loc><?php echo $settings['site_url']?>Users/InviteFriends/undang_teman.html</loc>
    </url>
    
    <?php foreach($daftar_motor as $daftar_motor):?>
    <url>
        <loc><?php echo $daftar_motor?></loc>
    </url>
    <?php endforeach;?>
    <?php foreach($motor_murah as $motor_murah):?>
    <url>
        <loc><?php echo $motor_murah?></loc>
    </url>
    <?php endforeach;?>
    <?php foreach($motor_kredit as $motor_kredit):?>
    <url>
        <loc><?php echo $motor_kredit?></loc>
    </url>
    <?php endforeach;?>
</urlset>