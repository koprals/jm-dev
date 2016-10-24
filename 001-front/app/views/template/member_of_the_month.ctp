<div class="line" style="border:0px solid black;">
    <div class="line" style="border:0px solid black;">
        <img src="<?php echo $settings['site_url']?>img/member_month.png" class="left10 position1 size82 bottom10" alt="member_month.png">
    </div>
    <div class="line top-32">
        <div class="back3 size82 left10 rounded2 top-10" style="padding:20px 0px 10px 0px;">
            <div class="box1 left5 bottom10">
            	<a href="<?php echo $settings['site_url']?>Profil/DetailProfile/<?php echo $findProfile["User"]["id"]?>/profil_<?php echo $general->seoUrl($findProfile['Profile']['fullname'])?>.html" style="border:none;"><img src="<?php echo $settings['showimages_url']?>/user_125_112.jpg?code=<?php echo $findProfile["User"]["id"]?>&prefix=_125_112&content=User&w=125&h=112" style="border:none;" alt="<?php echo $findProfile['Profile']['fullname']?>"/></a>
            </div>
            <div class="style1 text12 white align_center bold"><a href="<?php echo $settings['site_url']?>Profil/DetailProfile/<?php echo $findProfile["User"]["id"]?>/profil_<?php echo $general->seoUrl($findProfile['Profile']['fullname'])?>.html" class="normal style1 text12 white bold"><?php echo $findProfile["Profile"]["fullname"]?></a></div>
            <div class="style1 text11 white align_center">Member paling aktif</div>
        </div>
    </div>
</div>