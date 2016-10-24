<html>
  <head> 
  	<style>
    .vote{
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
		text-decoration:none;
		color:#F00;
		font-weight:bold;
		width:100%;
		display:block;
		float:left;
	}
	.chart_aby
	{
		display:block;
		width:600px;
		float:left;
		margin-right:10px;
		border:1px solid #cccccc;
		background-color:#F0F0F0;
		margin-top:10px;
	}
	.form_option
	{
		display:block;
		width:600px;
		float:left;
		margin-right:10px;
		border:1px solid #cccccc;
		background-color:#F0F0F0;
		margin-top:10px;
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
		color:#000;
		margin-bottom:20px;
	}
	.center
	{
		display:block;
		width:100%;
		float:left;
		text-align:center;
		
	}
	.table
	{
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
	}
    </style>       
    <title>My First chart using FusionCharts - Using pure JavaScript</title> 
       <?php echo $javascript->link("jquery.latest");?>

    <script type="text/javascript" src="<?php echo $this->webroot?>FusionCharts/FusionCharts.js"></script>
  </head>   
  <body>
  <div class="form_option">
   	<div class="center"><strong>PILIHAN</strong></div>
      <?php echo $form->create("PollingAnswer",array("url"=>array("controller"=>"Chart","action"=>"Result")))?>
      <table width="521" border="0" align="center" cellpadding="1" cellspacing="1" class="table">
      <tr>
        <td width="109">Judul Chart</td>
        <td width="405"><?php echo $form->input("caption",array("div"=>false,"label"=>false,"value"=>$caption))?></td>
      </tr>
      <tr>
        <td width="109">x Axis Name</td>
        <td width="405"><?php echo $form->input("xAxisName",array("div"=>false,"label"=>false,"value"=>$xAxisName))?></td>
      </tr>
      <tr>
        <td width="109">y Axis Name</td>
        <td width="405"><?php echo $form->input("yAxisName",array("div"=>false,"label"=>false,"value"=>$yAxisName))?></td>
      </tr>
      <tr>
        <td width="109">bg Color</td>
        <td width="405"><?php echo $form->input("bgColor",array("div"=>false,"label"=>false,"value"=>$bgColor))?></td>
      </tr>
      <tr>
        <td width="109">Logo Position</td>
        <td width="405"><?php echo $form->select("logoPosition",array("TL"=>"Top Left","TR"=>"Top Right","BL"=>"Bottom Left","BR "=>"Bottom Right","CC"=>"Center"),$logoPosition,array("div"=>false,"label"=>false,"empty"=>false))?></td>
      </tr>
      <tr>
        <td width="109">logoAlpha</td>
        <td width="405"><?php echo $form->input("logoAlpha",array("div"=>false,"label"=>false,"value"=>$logoAlpha))?></td>
      </tr>
      <tr>
        <td width="109">showBorder</td>
        <td width="405"><?php echo $form->input("showBorder",array("type"=>"radio","options"=>array("0"=>"Tidak","1"=>"Ya"),"div"=>false,"label"=>false,"legend"=>false,"default"=>$showBorder))?></td>
      </tr>
      <tr>
        <td width="109"><?php echo $form->submit("Ganti")?></td>
        <td width="405">&nbsp;</td>
      </tr>
    </table>
    <?php echo $form->end()?>
  </div>
    
  	<a href="<?php echo $settings['site_url']?>Chart/Polling" class="vote">Back to vote</a>
  	<div class="chart_aby" id="chart_1">&nbsp;</div>
    <div class="chart_aby" id="chart_2">&nbsp;</div>
    <div class="chart_aby" id="chart_3">&nbsp;</div>
    <div  class="chart_aby" id="chart_4">&nbsp;</div>
    <div class="chart_aby" id="chart_5">&nbsp;</div>
    <div  class="chart_aby" id="chart_6">&nbsp;</div>       
    <script type="text/javascript">
		FusionCharts.setCurrentRenderer('javascript');
		$(document).ready(function(){
			$.post("<?php echo $settings['site_url']?>Chart/GetDataJson",{
				"caption"	:	"<?php echo $caption?>",
				"xAxisName"	:	"<?php echo $xAxisName?>",
				"yAxisName"	:	"<?php echo $yAxisName?>",
				"bgColor"	:	"<?php echo $bgColor?>",
				"logoPosition"	:	"<?php echo $logoPosition?>",
				"logoAlpha"	:	"<?php echo $logoAlpha?>",
				"showBorder"	:	"<?php echo $showBorder?>",
				"caption"	:	"<?php echo $caption?>",
				"caption"	:	"<?php echo $caption?>",
				"caption"	:	"<?php echo $caption?>",
				"caption"	:	"<?php echo $caption?>",
			},function(data){
				 //CHART 1
				  var myChart = new FusionCharts( "<?php echo $this->webroot?>FusionCharts/Column2D.swf","myChartId_1", "600", "300", "0", "1" );
				  myChart.setJSONData(data);
				  myChart.render("chart_1");
				  
				  //CHART 2
				  var myChart = new FusionCharts( "<?php echo $this->webroot?>FusionCharts/Column3D.swf","myChartId_2", "600", "300", "0", "1" );
				  myChart.setJSONData(data);
				  myChart.render("chart_2");
				  
				  
				  //CHART 3
				  var myChart = new FusionCharts( "<?php echo $this->webroot?>FusionCharts/Spline.swf","myChartId_3", "600", "300", "0", "1" );
				  myChart.setJSONData(data);
				  myChart.render("chart_3");
				  
				  //CHART 4
				  var myChart = new FusionCharts( "<?php echo $this->webroot?>FusionCharts/SplineArea.swf","myChartId_4", "600", "300", "0", "1" );
				  myChart.setJSONData(data);
				  myChart.render("chart_4");
				  
				   //CHART 5
				  var myChart = new FusionCharts( "<?php echo $this->webroot?>FusionCharts/Waterfall2D.swf","myChartId_5", "600", "300", "0", "1" );
				  myChart.setJSONData(data);
				  myChart.render("chart_5");
				  
				   //CHART 6
				  var myChart = new FusionCharts( "<?php echo $this->webroot?>FusionCharts/Waterfall2D.swf","myChartId_6", "600", "300", "0", "1" );
				  myChart.setJSONData(data);
				  myChart.render("chart_6");
			});
		});
    </script> 
    <a href="<?php echo $settings['site_url']?>Chart/Polling" class="vote">Back to vote</a>
  </body> 
</html>