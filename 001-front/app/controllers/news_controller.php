<?php
class NewsController extends AppController
{
	var $components = 	array();
	var $name		=	"News";
	var $uses		=	array("News");
	var $helpers	=	array("Text","Number","General");
	
	function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	function Index()
	{
		$this->paginate	=	array(
			"News"	=>	array(
				"limit"			=>	10,
				"conditions"	=>	array(
					"News.status"	=>	1
				),
				"order"	=>	array("News.id DESC")
			)
		);
		
		$data	=	$this->paginate("News");
		
		foreach($data as $k =>$v)
		{
			//BBCODES
			App::import('Vendor','Decoda' ,array('file'=>'decoda/Decoda.php'));
			$code 				= 	new Decoda();
			$code->addFilter(new DefaultFilter());
			$code->addFilter(new TextFilter());
			$code->addFilter(new UrlFilter());
			$code->addFilter(new ListFilter());
			$code->addFilter(new ImageFilter());
			$code->addHook(new EmoticonHook());
			$code->reset($data[$k]["News"]["description"]);
			$data[$k]["News"]["description"]		=	$code->parse();
		}
		$this->set(compact("data"));
	}
	
	function Detail($news_id)
	{
		$this->News->VirtualFieldActivated();
		
		$data	=	$this->News->find("first",array(
						"conditions"	=>	array(
							"News.status"	=>	"1",
							"News.id"		=>	$news_id
						)
					));
					
		if(!empty($data))
		{
			//BBCODES
			App::import('Vendor','Decoda' ,array('file'=>'decoda/Decoda.php'));
			$code 				= 	new Decoda();
			$code->addFilter(new DefaultFilter());
			$code->addFilter(new TextFilter());
			$code->addFilter(new UrlFilter());
			$code->addFilter(new ListFilter());
			$code->addFilter(new ImageFilter());
			$code->addHook(new EmoticonHook());
			$code->reset($data["News"]["description"]);
			$data["News"]["description"]		=	$code->parse();
			
			$title_for_layout	=	"jualanmotor.com - ".$data["News"]["title"];
			$site_description	=	"jualanmotor.com - ".$data["News"]["ShortDesc"];
			$site_keywords		=	implode(", ",explode(" ",$data["News"]["title"]));
			$news_img_id		=	$data['News']['id'];
		}
		$this->set(compact("data","title_for_layout","site_description","site_keywords","news_img_id"));
	}
}
?>