<?php echo $javascript->link("jquery.bt")?>
<?php echo $javascript->link("jquery.hoverIntent.minified")?>
<!--[if IE]><script src="<?php echo $this->webroot?>js/excanvas.js" type="text/javascript" charset="utf-8"></script><![endif]-->
<script>
function SubCat(parent_id)
{
	if(parent_id.length>0)
	{
		var option	=	'<option value="">Mohon tunggu sebentar..</option>';
		$("#category_id").html(option);
		
		$.getJSON("<?php echo $settings['site_url']?>AddProduct/GetSubcategoryJson",
		{
			"parent_id":parent_id
		},function(data)
		{
			var option	=	'<option value="">Semua tipe '+$("#parent_id option:selected").text()+'</option>';
			if(data.length>0)
			{
				$.each(data,function(i,item){
					option	+=	'<option value="'+item.Category.id+'">'+item.Category.name+'</option>';
				});
			}
			$("#category_id").html(option);
		});
	}
	else
	{
		$("#category_id").html('<option value="">Semua tipe</option>');	
	}
}
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



function KeyupKm()
{
	CheckIsNumerik("#km_from");
	CheckIsNumerik("#km_to");
	
	var valFrom		=	$("#km_from").val();
	var lengthFrom	=	valFrom.length;
	
	var valTo		=	$("#km_to").val();
	var lengthTo	=	valTo.length;
	
	if(lengthFrom>0 && lengthTo==0)
	{
		$("#convert_km").html("&gt;= "+formatCurrency(valFrom));
	}
	else if(lengthFrom>0 && lengthTo>0)
	{
		$("#convert_km").html(formatCurrency(valFrom) +"&nbsp;&nbsp;s/d&nbsp;&nbsp; "+formatCurrency(valTo));
	}
	else if(lengthFrom==0 && lengthTo>0)
	{
		$("#convert_km").html("&lt;= "+formatCurrency(valTo));
	}
}

function KeyupTahun()
{
	CheckIsNumerik("#thn_from");
	CheckIsNumerik("#thn_to");
	
	var valFrom		=	$("#thn_from").val();
	var lengthFrom	=	valFrom.length;
	
	var valTo		=	$("#thn_to").val();
	var lengthTo	=	valTo.length;
	
	if(lengthFrom>0 && lengthTo==0)
	{
		$("#convert_thn").html("&gt;= "+valFrom);
	}
	else if(lengthFrom>0 && lengthTo>0)
	{
		$("#convert_thn").html(valFrom +"&nbsp;&nbsp;s/d&nbsp;&nbsp; "+valTo);
	}
	else if(lengthFrom==0 && lengthTo>0)
	{
		$("#convert_thn").html("&lt;= "+valTo);
	}
}

function KeyupHarga()
{
	CheckIsNumerik("#price_from");
	CheckIsNumerik("#price_to");
	
	var valFrom		=	$("#price_from").val();
	var lengthFrom	=	valFrom.length;
	
	
	var valTo		=	$("#price_to").val();
	var lengthTo	=	valTo.length;
	
	if(lengthFrom>0 && lengthTo==0)
	{
		$("#convert_harga").html("&gt;= Rp "+formatCurrency(valFrom));
	}
	else if(lengthFrom>0 && lengthTo>0)
	{
		$("#convert_harga").html("Rp "+formatCurrency(valFrom)+"&nbsp;&nbsp;s/d&nbsp;&nbsp;Rp "+formatCurrency(valTo));
	}
	else if(lengthFrom==0 && lengthTo>0)
	{
		$("#convert_harga").html("&lt;= Rp "+formatCurrency(valTo));
	}
}


function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+'.'+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num);
}


function CalcKeyCode(aChar)
{
  var character	= aChar.substring(0,1);
  var code		= aChar.charCodeAt(0);
  return code;
}

function CheckIsNumerik(selector)
{
	var strPass 	= $(selector).val();
	var strLength	= strPass.length;
	var lchar 		= strPass.charAt((strLength) - 1);
	var cCode 		= CalcKeyCode(lchar);
	
	if (cCode < 48 || cCode > 57 )
	{
		var myNumber = strPass.substring(0, (strLength) - 1);
		$(selector).val(myNumber);
	}
	
	return false;
}


function SearchAdvance()
{
	var pos			=	$("#list_item").offset();
	var leftpos		=	pos.left;
	var toppos		=	pos.left;
	
	$("#SearchAdvance").ajaxSubmit({
		url:'<?php echo $settings['site_url']?>Search/ListItem/',
		type:'POST',
		dataType: "html",
		clearForm:false,
		
		beforeSend:function()
		{
			$("#loading_gede").css({left:(leftpos+250),top:(toppos+100)});
			$("#loading_gede").show();
			$("#list_item").css("opacity","0.5");
		},
		complete:function(data,html)
		{
			$("#loading_gede").hide();
			$("#list_item").css("opacity","1");
			
		},
		error:function(XMLHttpRequest, textStatus,errorThrown)
		{
			alert(textStatus);
		},
		success:function(data)
		{
			$("#list_item").html(data);
			//$("#reset").val(0);
		}
	});
	
	return false;
}
function ClearSearchAdvance()
{
	$('#reset').val('1');
	$("#parent_id").val("");
	SubCat('');
	SearchAdvance();
	$("#SearchGroupId").val("");
	$("input:radio").attr("checked", false);
	$("input:text").val("");
	$("#convert_harga").html("");
	$("#convert_thn").html("");
	$("#convert_km").html("");
	return false;
}
</script>
<img src="<?php echo $this->webroot?>img/loading51.gif" id="loading_gede" style="position:absolute;display:none">

<div class="line">
    <div class="text_title3">
        <div class="line1">
            <img src="<?php echo $this->webroot?>img/search_ico.png" style="float:left;margin:-6px 3px 0 0;"/>
            PENCARIAN DETAIL
        </div>
    </div>
    <div class="kiri back1 size100 kiri position1 rounded2" style="padding-bottom:10px;border:0px solid black;">
        
        <div class="kiri left10 size95 top10" style="border:0px solid black;">
            
            <?php echo $form->create("Search",array("id"=>"SearchAdvance"))?>
            <?php echo $form->input("reset",array("type"=>"hidden","id"=>"reset"))?>
            <div class="kiri size50" style="border:0px solid black;">
                <div class="kiri size100">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Merk motor 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <select name="data[Search][parent_id]" class="rounded1 size100 kiri style1 white text12 input8" onchange="SubCat(this.value)" id="parent_id" style="cursor:pointer;">
                            <option value="">Semua merk</option>
                        <?php foreach($category as $k=>$v):?>
                            <option value="<?php echo $k?>"><?php echo $v?></option>
                        <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="kiri size100 top15">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Tipe motor 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <select name="data[Search][category_id]" class="rounded1 size100 kiri style1 white text12 input8"  id="category_id" style="cursor:pointer;">
                            <option value="">Semua tipe</option>
                        </select>
                    </div>
                </div>
                <div class="kiri size100 top15">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Kota 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <?php echo $form->select("group_id",$ProvinceGroup,FALSE,array("div"=>false,"label"=>false,"error"=>false,"class"=>"rounded1 size100 kiri style1 white text12 input8","empty"=>"Semua kota","style"=>"cursor:pointer;"))?>
                    </div>
                </div>
                <div class="kiri size100 top15">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Warna 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <?php echo $form->input("color",array("div"=>false,"label"=>false,"error"=>false,"class"=>"size95 kiri style1 white text12 input8"))?> 
                    </div>
                </div>
                <div class="kiri size100 top15">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Harga 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <?php echo $form->input("price_from",array("class"=>"kiri style1 white text12 input8","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:73px',"maxlength"=>10,"onkeyup"=>"KeyupHarga()","id"=>"price_from"))?>
                        <span style="float:left; margin:0 4px 0 3px" class="style1 white text11"> s/d </span>
                        <?php echo $form->input("price_to",array("class"=>"kiri style1 white text12 input8","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:73px',"maxlength"=>10,"onkeyup"=>"KeyupHarga()","id"=>"price_to"))?>
                    </div>
                </div>
                <div class="kiri size100 top3">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       &nbsp; 
                    </div>
                    <div class="kanan size65 style1 white text12" style="border:0px solid black;" id="convert_harga">
                    </div>
                </div>
            </div>
            
            <div class="kanan size45" style="border:0px solid black;">
                <div class="kiri size100">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Kondisi 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <div style="width:60px; border:0px solid black;" class="kiri">
                        	<input type="hidden" value="" name="data[Search][condition_id]">
                            <input id="SearchConditionId1" type="radio" value="1" name="data[Search][condition_id]">
                            <label for="SearchConditionId1">Baru</label>
                        </div>
                        <div class="kiri">
                            <input id="SearchConditionId2" type="radio" value="2" name="data[Search][condition_id]">
                            <label for="SearchConditionId2">Bekas</label>
                        </div>
                    </div>
                </div>
                <div class="kiri size100 top5">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       STNK 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <div style="width:60px; border:0px solid black;" class="kiri">
                        	<input type="hidden" value="" name="data[Search][stnk_id]">
                            <input id="SearchStnkId1" type="radio" value="1" name="data[Search][stnk_id]">
                            <label for="SearchStnkId1">Ada</label>
                        </div>
                        <div class="kiri">
                            <input id="SearchStnkId2" type="radio" value="2" name="data[Search][stnk_id]">
                            <label for="SearchStnkId2">Tidak Ada</label>
                        </div>
                    </div>
                </div>
                <div class="kiri size100 top5">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       BPKB 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <div style="width:60px; border:0px solid black;" class="kiri">
                        	<input type="hidden" value="" name="data[Search][bpkb_id]">
                            <input id="SearchBpkbId1" type="radio" value="1" name="data[Search][bpkb_id]">
                            <label for="SearchBpkbId1">Ada</label>
                        </div>
                        <div class="kiri">
                            <input id="SearchBpkbId2" type="radio" value="2" name="data[Search][bpkb_id]">
                            <label for="SearchBpkbId2">Tidak Ada</label>
                        </div>
                    </div>
                </div>
                <div class="kiri size100 top5">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Kredit 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <div style="width:60px; border:0px solid black;" class="kiri">
                        	<input type="hidden" value="" name="data[Search][is_credit]">
                            <input id="SearchIsKredit1" type="radio" value="1" name="data[Search][is_credit]">
                            <label for="SearchIsKredit1">Ya</label>
                        </div>
                        <div class="kiri">
                            <input id="SearchIsKredit2" type="radio" value="0" name="data[Search][is_credit]">
                            <label for="SearchIsKredit2">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="kiri size100 top15">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Km 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <?php echo $form->input("km_from",array("class"=>"kiri style1 white text12 input8","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:53px',"maxlength"=>7,"id"=>"km_from","onkeyup"=>"KeyupKm()"))?>
                        <span style="float:left; margin:0 4px 0 3px" class="style1 white text11"> s/d </span>
                        <?php echo $form->input("km_to",array("class"=>"kiri style1 white text12 input8","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:53px',"maxlength"=>7,"id"=>"km_to","onkeyup"=>"KeyupKm()"))?>
                    </div>
                </div>
                <div class="kiri size100 top3">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       &nbsp; 
                    </div>
                    <div class="kanan size65 style1 white text12" style="border:0px solid black;" id="convert_km">
                    </div>
                </div>
                
                <div class="kiri size100">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       Tahun 
                    </div>
                    <div class="kanan size65" style="border:0px solid black;">
                        <?php echo $form->input("thn_from",array("class"=>"kiri style1 white text12 input8","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:53px',"maxlength"=>4,"id"=>"thn_from","onkeyup"=>"KeyupTahun()"))?>
                        <span style="float:left; margin:0 4px 0 3px" class="style1 white text11"> s/d </span>
                        <?php echo $form->input("thn_to",array("class"=>"kiri style1 white text12 input8","div"=>false,"label"=>false,"type"=>"text",'style'=>'width:53px',"maxlength"=>4,"id"=>"thn_to","onkeyup"=>"KeyupTahun()"))?>
                    </div>
                </div>
                <div class="kiri size100 top3">
                    <div class="kiri size30 white style1 text12 bold" style="border:0px solid black;">
                       &nbsp; 
                    </div>
                    <div class="kanan size65 style1 white text12" style="border:0px solid black;" id="convert_thn">
                    </div>
                </div>
            </div>
            
            <div class="kiri size100 top10">
                <input type="submit" name="button" value="Search" class="tombol1" style="margin-left:0px" onclick=" $('#reset').val('0');return SearchAdvance()"/>
                <input type="submit" name="button2" value="Clear" class="tombol1" onclick="return ClearSearchAdvance()"/>
            </div>
            <?php echo $form->end()?>
        </div>
    </div>
</div>
<div class="kiri size100" id="list_item">
</div>