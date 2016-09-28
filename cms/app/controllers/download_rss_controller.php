<?php
class DownloadRssController extends AppController
{
	var $name 			=	'DownloadRss';
	var $uses 			=	null;
	
	function beforeFilter()
	{
	}
	
	function Index()
	{
		//DEFINE SETTINGS
		$Setting				=	ClassRegistry::Init('Setting');
		$settings				=	$Setting->find('first');
		
		//FLAG LOCATION
		$flag					=	"/home/jualanmotor/cms/app/tmp/flag_cron/cron_download_rss.txt";
		$flag_status			=	file_get_contents($flag);
		
		//DEFINE SOURCE AND DESTINATION
		$source			=	"http://www.otomotifnet.com/otonet/index.php/feed/8/ATPM";
		$destination	=	$settings['Setting']['path_content']."rss/news.xml";
		
		if($flag_status=="1") exit;
	

		if($flag_status=="0")
		{
			//WRITE FLAG 1 TO FILE
			if (is_writable($flag)) {
				if (!$handle = fopen($flag, 'wb')) {
					 exit;
				}
				
				if (fwrite($handle, "1") === FALSE) {
					exit;
				}
				fclose($handle);
			}
			
			$download	=	$this->getContent($destination, $source);
				var_dump($download);
			if (is_writable($flag)) {
				if (!$handle = fopen($flag, 'wb')) {
					 exit;
				}
				
				if (fwrite($handle, "0") === FALSE) {
					exit;
				}
				fclose($handle);
			}
		}
		$this->autoRender = false;
	}
	
	function getContent($destination, $source){
		$filename 	= $destination;
		$handle 	= fopen("$source", "rb");
		if($handle)
		{
	  		$somecontent = stream_get_contents($handle);
	  		fclose($handle);
	  		$handle = fopen($filename, 'wb');
	 
	  		if($handle)
			{
				if (fwrite($handle, $somecontent) === FALSE) 
				{
		   			$confirm = false;
		   			exit;
				}
				$confirm = true;
				fclose($handle);
	  		}
			else
			{
		 		$confirm = false;
		 		exit;
	  		}
		}
		return $confirm;
	}
}
?>