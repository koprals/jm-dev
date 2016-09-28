<script>
$(document).ready(function(){
	$("#product_request").load("<?php echo $settings['cms_url']?>Home/ProductRequest");
	$("#member_approval").load("<?php echo $settings['cms_url']?>Home/MemberApproval");
	$("#contact_us").load("<?php echo $settings['cms_url']?>Home/ContactUs");
});
</script>
<div class="line2" style="border:0px solid black;">
	<div class="body_up">
    	<div class="nav_2">Selamat datang, <?php echo $profile['fullname']?></div>
    </div>
    <!-- CONTENT -->
    <div class="line1">
    	<div class="left" style="width:55%; margin-right:10px; border:0px solid black;">
            <div class="line1" id="product_request" style="border:0px solid black; margin-bottom:30px;">
                <img src="<?php echo $this->webroot?>img/loading19.gif" style="margin:40px 250px;"/>
         	</div>
            <div class="line1" id="member_approval" style="border:0px solid black;">
                <img src="<?php echo $this->webroot?>img/loading19.gif" style="margin:40px 250px;"/>
         	</div>
         </div>
         <div class="left" style="width:43%; border:0px solid black;">
         	<div class="line1" id="contact_us" style="border:0px solid black; margin-bottom:30px;">
                <img src="<?php echo $this->webroot?>img/loading19.gif" style="margin:40px 250px;"/>
         	</div>
         </div>
     </div>
    <!-- CONTENT -->
</div>