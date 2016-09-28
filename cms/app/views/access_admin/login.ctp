<div class="login_center">
	<div class="login_box">
    	<div class="line1" style="margin-top:10px;">
            <div class="text11">
                ADMIN LOGIN
            </div>
        </div>
        <?php echo $form->create("User",array("url"=>array("controller"=>"AccessAdmin","action"=>"Login")))?>
        <div class="line1" style=" margin-left:60px; margin-top:20px;">
            <div class="line3">
                <div class="left" style="border:0px solid black; width:20%;">
                    <div class="text12">Email : </div>
                </div>
                <div class="right" style="border:0px solid black; width:79%">
                	<?php echo $form->input("email_login",array("div"=>false,"label"=>false,"error"=>false,"class"=>"all_input3","style"=>"width:60%; float:left; height:16px;","maxlength"=>25))?>
                    <span class="error" style="width:80%"><?php echo $form->error("User.email")?></span>
                </div>
            </div>
        </div>
        <div class="line1" style=" margin-left:60px; margin-top:10px;">
            <div class="line3">
                <div class="left" style="border:0px solid black; width:20%;">
                    <div class="text12">Password : </div>
                </div>
                <div class="right" style="border:0px solid black; width:79%">
                	<?php echo $form->input("password_login",array("div"=>false,"label"=>false,"error"=>false,"class"=>"all_input3","style"=>"width:60%; float:left; height:16px;","type"=>"password","maxlength"=>25))?>
                    <span class="error" style="width:80%"><?php echo $form->error("User.password")?></span>
                </div>
            </div>
        </div>
        <div class="line1" style=" margin-left:60px; margin-top:10px; margin-bottom:20px;">
            <div class="line3">
                <div class="left" style="border:0px solid black; width:20%;">
                    <div class="text12"><?php echo $form->submit("Login",array("class"=>"button_admin_login","div"=>false))?></div>
                </div>
            </div>
        </div>
        <?php echo $form->end()?>
    </div>
</div>