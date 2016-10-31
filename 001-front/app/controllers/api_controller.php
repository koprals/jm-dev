<?php
class ApiController extends AppController
{
	var $settingvar;
	var $uses			=	null;
	function beforeFilter()
	{
		$this->autoRender = false;
		$username	=	$_REQUEST['username'];
		$password	=	$_REQUEST['password'];
		
		if($username!=="olx.co.id" && $password!=="olx***124")
		{
			header('Content-type: text/xml');
			$xml	='<?xml version="1.0" encoding="utf-8"?>
			<ADS>
				<STATUS>2</STATUS>
				<MESSAGE>Wrong username or password.</MESSAGE>
			</ADS>
			';
			echo	$xml;
			exit;
		}
		else
		{
			//SET GENERAL SETTINGS
			if (($settings = Cache::read('settings')) === false)
			{
				$this->loadModel('Setting');
				$settings			=	$this->Setting->find('first');
				Cache::write('settings', $settings);
			}
			$this->settingvar	=	$settings['Setting'];
		}
	}
	
	
	function AllContent()
	{
		$this->autoRender = false;
		$source	=	$this->settingvar['path_content']."rss/api_olx.xml";
		header('Content-type: text/xml');
		ob_clean();
    	flush();
		readfile($source);
	}
}
?>