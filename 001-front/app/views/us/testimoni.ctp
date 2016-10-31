<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<script>
	var fade_in = 500;
	var fade_out = 500;
	$(document).ready(function(){
		if($.browser.msie)
		{
			fade_in 	= 100;
			fade_out	= 3500;
		}
		$('input,textarea').each(function(){
			$(this).bt({
				width: 230,
				trigger: ['focus', 'blur'],
				positions: ['right'],
				cornerRadius: 7,
				strokeStyle: '#FFFFFF',
				fill: 'rgba(136, 136, 136, 1)',
				showTip: function(box){
				  $(box).fadeIn(fade_in);
				},
				hideTip: function(box, callback){
				  $(box).animate({opacity: 0}, fade_out, callback);
				},
				cssStyles:{'color':'white','fontFamily':'Arial','font-size':'12px'},
				shrinkToFit: true,
				hoverIntentOpts: {
				  interval: 0,
				  timeout: 0
				}
			});
		});
	});

function SubmitFormContact()
{
	$("#FormContact").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Us/ProccessAddTestimoni",
		type		: "POST",
		dataType	: "json",
		clearForm	: false,
		beforeSend	: function()
		{
			$("#loading").html('<img src="<?php echo $this->webroot?>img/loading19_bak.gif" style="float:left; display:block;vertical-align:middle; margin-right:5px;"/>Mohon tunggu ..');
		},
		complete	: function(data,html)
		{
		},
		error		: function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert("Maaf terjadi kesalahan pada server kami, mohon coba beberapa saat lagi.");
		},
		success		: function(data)
		{
			$("#out").html(data);
			$("#loading").html('');
			$("span[id^=err]").html('');
			$("span[id^=img]").html('');
			
			if(data.status==true)
			{
				$("#success").fadeIn('slow');
				$("#success").animate({opacity: 1.0}, 3000);
				$("#success").fadeOut('slow',function(){
					window.location.reload();
				});
			}
			else
			{
				var err		=	data.error;
				var scrool	=	"";
				var count	=	0;
				$.each(err, function(i, item){
					if(item.status=="false")
					{
						$("#err_"+item.key).html(item.value);
						$("#img_"+item.key).html('<img src="<?php echo $this->webroot?>img/icn_error.png">');	
						count++;
					}
					else if(item.status=="true")
					{
						$("#err_"+item.key).html("");
						$("#img_"+item.key).html('<img src="<?php echo $this->webroot?>img/check.png">');
						
					}
					else if(item.status=="blank")
					{
						$("#err_"+item.key).html("");
						
					}
					if(count==1 && item.status=="false")
					{
						scrool	=	"#span_"+item.key;
					}
					
				});
				$(document).scrollTo(scrool, 800);
			}
		}
	});
	return false;
}
</script>	
<?PHP if(!empty($data)):?>
<?php echo $paginator->options(array(
				'url'	=> array(
					'controller'	=> 'Us',
					'action'		=> 'Testimoni/'.$id,
				)
			));
?>
<style>
#pagination-digg a{
	border:1px solid grey;
}
#pagination-digg a:hover
{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
	border:1px solid #D3D1D1;
}
#pagination-digg .current{
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5E5E5E', endColorstr='#D6D6D6'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#5E5E5E), to(#D6D6D6)); /* for webkit browsers */
	background: -moz-linear-gradient(top, #5E5E5E, #D6D6D6); /* for firefox 3.6+ */
}
</style>
<div class="line">
    <div class="size100 tengah">
		<div class="text_title3">
            <div class="kiri size50 left5" style="border:0px solid black;">
                TESTIMONI ( <?php  echo $paginator->counter(array('format' => '%count%'));?> testimoni ) 
            </div>
            <div class="kanan" style="border:0px solid black;">
            	<?php if($paginator->hasNext() or $paginator->hasPrev()):?>
                <div class="kiri bottom5 size100 top-2" style="text-shadow: none;">
                    <div class="paging-box" style="width:auto;float:right; border:0px solid black;">
                        <ul id="pagination-digg" style="border:0px solid black; margin-left:-40px;">
                            <?php  $paginator->counter(array('format' => 'Page %page% of %pages%'));?>
                            <?php echo $paginator->prev("Prev",array('class'=>'next',"escape"=>false),'Prev',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
                            <?php echo $paginator->numbers(array('modulus'=>4,'separator'=>null,'class'=>'navigasi1','span'=>false,'current'=>'current')); ?>
                            <?php echo $paginator->next("Next",array('class'=>'next',"escape"=>false),'Next',array('tag'=>"a","class"=>"next","href"=>"javascript:void(0)")); ?>
                        </ul>  
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
        
        <?php foreach($data as $data):?>
        <!-- DETAIL -->
        <?php $back		=	($data["Contact"]["id"]==$id) ? "#CCC" : "#ffffff"?>
        <?php $back2	=	($data["Contact"]["id"]==$id) ? "black1 bold" : "unbold grey4"?>
        
        <div class="kiri size100" style="border-bottom:1px solid #D5D5D5; padding-bottom:10px;background-color:<?php echo $back ?>;">
            <div class="kiri style1 bold red2 text13 size100 top10 left10"><?php echo $data["Contact"]["from"]?> <span class="left5 text11 grey3 unbold"><?php echo date("d-m-Y  H:i:s",strtotime($data["Contact"]["created"]))?></span></div>
            <div class="kiri style1 <?php echo $back2?> text12 left25 top5" style="word-wrap: break-word;"><?php echo chunk_split($data["Contact"]["message"],50,"<br>")?></div>
        </div>
        <!-- DETAIL -->
        
        <?php endforeach;?>
    </div>
</div>


<?php else:?>
<div class="size100 tengah" style="border:0px solid black;">
    <div class="text_title3">
        <div class="line1">Maaf kami belum memiliki daftar testimoni.</div>
    </div>
    <div class="line size100 kiri position1 rounded2" style="padding-bottom:10px; background-color:#888888; background-image:none;">
    	<div class="kiri left10" style="width:auto;">
        	<img src="<?php echo $settings['site_url']?>img/warning_big.png" />
        </div>
        <div class="kiri size65 left20 style1 white text12 top10 bold">
        	Jadilah orang pertama yang mengisi testimonial untuk kami.<br /><br />
        </div>
    </div>
    <div class="line">&nbsp;</div>
</div>
<?php endif;?>

<!-- FORM -->
<div class="kiri size100 style1 top10" style="display:none;" id="success">
    <div class="kiri size100 top10 text14 blink red2">Terimakasih, Testimoni anda telah kami simpan.</div>
</div>
<?php echo $form->create("Contact",array("id"=>"FormContact","onsubmit"=>"return SubmitFormContact()"))?>
<?php $top	=	"top20";?>
<?php if($is_login=="0"):?>
<div class="line top10">
	<div class="style1 text13 bold black" id="span_name">Nama :</div>
	<?php echo $form->input("from",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter.","class"=>"input7 style1 black text12 size35 kiri"))?>
	<span class="kiri left10" id="img_from"></span>
	<span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_from"></span>
</div>
<div class="line top5">
	<div class="style1 text13 bold black" id="span_email">Email :</div>
	<?php echo $form->input("email",array("div"=>false,"label"=>false,"type"=>"text","title"=>"Masukkan alamat email anda di sini. Harap masukkan email dengan format standar : name@domain.com,<br>name.subname@domain.com","class"=>"input7 style1 black text12 size35 kiri"))?>
	<span class="kiri left10" id="img_email"></span>
	<span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_email"></span>
</div>
<?php $top	=	"top5";?>
<?php endif;?>
<div class="line <?php echo $top?>">
	<div class="style1 text13 bold black" id="span_comment">Testimoni :</div>
	 <?php echo $form->textarea("message",array("div"=>false,"label"=>false,"title"=>"Masukkan testimoni anda disini.","class"=>"input3 style1 black text12 size65 kiri textarea"))?>
	<span class="kiri left10" id="img_message"></span>
	<span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_message"></span>
</div>
<div class="line top10 bottom15">
	<input type="button" name="button" value="KIRIM" class="tombol1" onclick="SubmitFormContact()" style="float:left"/>
   
	<div class="kiri style1 black text12 left5 top5" style="display:block" id="loading"></div>
	
</div>
<?php echo $form->end();?>
<!-- FORM -->