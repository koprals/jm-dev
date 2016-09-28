<?php echo $this->element('detail_product',array("id"=>$product_id))?>

<!-- KOMENTAR -->
<div id="out"></div>
<div class="kiri size100 top10" style="border:1px solid #D5D5D5;min-height:150px;">
	<div class="kiri size100 backred" style="height:30px;">
    	<div class="style1 kiri left10 bold text13 white top5">KOMENTAR <span id="komentar"></span></div>
    </div>
     <div class="tengah size97">
    	<div class="kiri size100 top10">
        	<div id="list_comment"></div>
            <!-- FORM -->
            <?php echo $form->create("Comment",array("id"=>"FormComment","onsubmit"=>"return SubmitFormComment()"))?>
            <?php $top	=	"top20";?>
            <?php if($is_login=="0"):?>
            <div class="line top10">
            	<div class="style1 text13 bold black" id="span_name">Nama :</div>
                <?php echo $form->input("name",array("div"=>false,"label"=>false,"type"=>"text","maxlength"=>$settings['max_name_char'],"title"=>"Masukkan nama lengkap anda di sini. Maksimum jumlah karakter adalah ".$settings['max_name_char']." karakter.","class"=>"input7 style1 black text12 size35 kiri"))?>
                <span class="kiri left10" id="img_name"></span>
                <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_name"></span>
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
            	<div class="style1 text13 bold black" id="span_comment">Komentar :</div>
                 <?php echo $form->textarea("comment",array("div"=>false,"label"=>false,"title"=>"Masukkan komentar anda. Maksimum jumlah karakter adalah ".$settings['max_address_char']." karakter.","class"=>"input3 style1 black text12 size65 kiri textarea"))?>
                <span class="kiri left10" id="img_comment"></span>
                <span class="line kiri style1 red2 text12 bold" style="text-decoration:blink;" id="err_comment"></span>
            </div>
            <div class="line top10 bottom15">
                <input type="button" name="button" value="KIRIM" class="tombol1" onclick="SubmitFormComment()" style="float:left"/>
               
                <div class="kiri style1 black text12 left5 top5" style="display:block" id="loading"></div>
                
            </div>
            <?php echo $form->end();?>
            <!-- FORM -->
        </div>
    </div>
</div>
<!-- KOMENTAR -->
<?php echo $javascript->link("jquery.bt")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<?php echo $javascript->link("jquery.watermark")?>
<?php echo $javascript->link("jquery.scrollTo")?>
<script>
var fade_in = 500;
var fade_out = 500;
function onClickPage(el,divName)
{
	
	var pos			=	$(divName).offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	$("#loading_gede").css({left:(leftpos+350),top:(toppos+100)});
	$("#loading_gede").show();
	
	$(divName).css("opacity","0.5");
	$(divName).load(el.toString(),function(){
		$(divName).css("opacity","1");
		$("#loading_gede").hide();
	});
	return false;
}
function SumComment()
{
	$.getJSON("<?php echo $settings['site_url']?>Iklan/SumComment/<?php echo $product_id?>",function(data){
		if(data!==null)
		{
			$("#komentar").html("( "+data+" komentar )");
		}
	});
}

$(document).ready(function(){
	/*List Comment*/
	$("#list_comment").load("<?php echo $settings['site_url']?>Iklan/ListComment/<?php echo $product_id?>",function(){
		SumComment();																										   });
	
	if($.browser.msie)
	{
		fade_in 	= 100;
		fade_out	= 3500;
	}
		
	/*BUAT TOOLTIPS*/					   
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
$("#CommentName").watermark({watermarkText:'co: Her Robby Fajar',watermarkCssClass:'input7 style1 grey1 text12 size35 kiri italic'});
$("#CommentEmail").watermark({watermarkText:'co: abyfajar@gmail.com',watermarkCssClass:'input7 style1 grey1 text12 size35 kiri italic'});
$("#CommentComment").watermark({watermarkText:'co: Jl Kedoya Timur No5',watermarkCssClass:'input3 style1 grey1 text12 size65 kiri italic textarea'});


function SubmitFormComment()
{
	$("#FormComment").ajaxSubmit({
		url			: "<?php echo $settings['site_url']?>Iklan/AddComment/<?php echo $product_id?>",
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
				$("#list_comment").load("<?php echo $settings['site_url']?>Iklan/ListComment/<?php echo $product_id?>",function(){
					SumComment();																														   				});
				$("#FormComment").clearForm();
				
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