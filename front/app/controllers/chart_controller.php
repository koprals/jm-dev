<?php
class ChartController extends AppController
{
	var $name	=	"Chart" ;
	var $uses	=	null;
	
	function Polling()
	{
		$this->layout		=	"ajax";
		$this->loadModel('Polling');
		$this->Polling->bindModel(array(
			"hasMany"	=>	array(
				"PollingOption"	=>	array(
					'className' 	=> 'PollingOption',
					'foreignKey' 	=> 'polling_id'
				)
			)
		));
		
		
		//GET POLLING QUESTION
		$data	=	$this->Polling->find('first');
		$this->set(compact("data"));
		
		if(!empty($this->data))
		{
			$this->loadModel("PollingAnswer");
			$this->data['PollingAnswer']['polling_id']	=	1;
			$this->PollingAnswer->create();
			$this->PollingAnswer->save($this->data);
			$this->redirect(array("controller"=>"Chart","action"=>"Result"));
		}
	}
	
	function Result()
	{
		$this->layout		=	"ajax";
		
		//DEFINE OPTION
		$caption			=	!empty($this->data['PollingAnswer']['caption']) ? $this->data['PollingAnswer']['caption'] : "Poling Foke";
		
		$xAxisName			=	!empty($this->data['PollingAnswer']['xAxisName']) ? $this->data['PollingAnswer']['xAxisName'] : "Pilihan";
		
		$yAxisName			=	!empty($this->data['PollingAnswer']['yAxisName']) ? $this->data['PollingAnswer']['yAxisName'] : "Jumlah";
		
		$bgColor			=	!empty($this->data['PollingAnswer']['bgColor']) ? $this->data['PollingAnswer']['bgColor'] : "F0F0F0,FFFFFF";
		
		$logoPosition			=	!empty($this->data['PollingAnswer']['logoPosition']) ? $this->data['PollingAnswer']['logoPosition'] : "TL";
		
		$logoAlpha			=	!empty($this->data['PollingAnswer']['logoAlpha']) ? $this->data['PollingAnswer']['logoAlpha'] : "15";
		
		
		$showBorder			=	!empty($this->data['PollingAnswer']['showBorder']) ? $this->data['PollingAnswer']['showBorder'] : "0";
		
		$this->set(compact("caption","xAxisName","yAxisName","bgColor","logoPosition","logoAlpha","showBorder"));
		
	}
	
	function GetDataJson()
	{
		$this->layout	=	"json";
		//GET POLLING OPTION
		$this->loadModel("PollingOption");
		$option				=	$this->PollingOption->find("all",array(
									"conditions"	=>	array(
										"PollingOption.polling_id"	=>	1,
									),
									"order"			=>	array("PollingOption.id ASC")
								));
		
		
		//GET SUM
		$this->loadModel("PollingAnswer");
		foreach($option as $option)
		{
			$count		=	$this->PollingAnswer->find("count",array(
								"conditions"	=>	array(
									"PollingAnswer.polling_id"			=>	1,
									"PollingAnswer.polling_option_id"	=>	$option["PollingOption"]["id"]
								)
							));
			
			$data_polling[]	=	array("label" =>$option["PollingOption"]["title"], "value" =>$count);
		}
		
		
	
		$data				=	array(
									"chart" => array(
										"caption"		=>	$_POST['caption'],
										"xAxisName"		=>	$_POST['xAxisName'],
										"yAxisName"		=>	$_POST['yAxisName'],
										"numberPrefix"	=>	"",
										"bgColor"		=>	$_POST['bgColor'],
										"logoURL"		=>	$this->settings['logo_url'],
										"logoPosition"	=>	$_POST['logoPosition'],
										"logoAlpha"		=>	$_POST['logoAlpha'],
										"showBorder"	=>	$_POST['showBorder'],
									),
									"data"				=>	$data_polling
								);
		
		$this->set("data",$data);
		$this->render(false);
	}
}
?>