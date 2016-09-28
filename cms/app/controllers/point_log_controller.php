<?php
class PointLogController extends AppController
{
	var $name		=	"PointLog";
	var $uses		=	array("PointsHistory");
	var $components	=	array('Action','General');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','admin_user_management');
		$this->layout	=	"new";
	}
	
	function Index($user_id=0)
	{
		$this->set('child_code','user_list');
		$this->Session->delete('CondSearch.PointsHistory');
		
		//SET ACTION TYPE
		$this->loadModel('ActionTypes');
		$actionID	=	$this->ActionTypes->find("list",array(
							"fields"		=>	array("id","name"),
							"order"			=>	array("name ASC")
						));
		$this->set("actionID",$actionID);
		
		$this->set("user_id",$user_id);
	}
	
	function ListItem($user_id)
	{
		$this->layout	=	"ajax";
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order				=	array('PointsHistory.id DESC');
		
		
		//DEFINE FIELDS
		$fields				=	array(
									'UserLogs.*',
									'PointsHistory.*',
									'ActionTypes.name'
								);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('CondSearch.PointsHistory');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'UserLogs.actionText LIKE'	=> "%$val%",
											'ActionTypes.name LIKE'	=> "%$val%",
											
										)
									);
				
				}
			}
			$OR['OR'][]			=	"MATCH (UserLogs.actionText,ActionTypes.name) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			
			array_push($fields,"MATCH (UserLogs.actionText,ActionTypes.name) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("CondSearch.PointsHistory",$cond_search);
			$order			=	array('score DESC','PointsHistory.created DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		if(!empty($this->data))
		{
			$this->Session->delete('CondSearch.PointsHistory');
			$trans 								=	array(' ' => '', '.' => '', ',' => '');
			$this->data['Search']['point_from']	=	strtr($this->data['Search']['point_from'], $trans);
			$this->data['Search']['point_to']	=	strtr($this->data['Search']['point_to'], $trans);
			
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["PointsHistory.id"]					=	$this->data['Search']['id'];
			}
			
			if(!empty($this->data['Search']['tgl_input']))
			{
				$string		=	explode("s.d",str_replace(" ","",$this->data['Search']['tgl_input']));
				if(count($string) > 1)
				{
					$date1		=	strtotime($string[0]);
					$date2		=	strtotime($string[1]);
					if($date1 > $date2)
					{
						$date1		=	$date2;
						$date2		=	strtotime($string[0]);
					}
					
					$cond_search["PointsHistory.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
					$cond_search["PointsHistory.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				
			}
			
			if(!empty($this->data['Search']['actionID']))
			{
				$cond_search["UserLogs.actionID"]					=	$this->data['Search']['actionID'];
			}
			
			//VALUE
			if(!empty($this->data['Search']['value_from']) && empty($this->data['Search']['value_to']))
			{
				$cond_search["PointsHistory.value >= "]		=	$this->data['Search']['value_to'];
			}
			
			if(empty($this->data['Search']['value_from']) && !empty($this->data['Search']['value_to']))
			{
				$cond_search["PointsHistory.value <= "]		=	$this->data['Search']['value_to'];
			}
			
			if(!empty($this->data['Search']['value_from']) && !empty($this->data['Search']['value_to']))
			{
				$point_from	=	$this->data['Search']['value_from'];
				$point_to		=	$this->data['Search']['value_to'];
				if($this->data['Search']['value_to'] < $this->data['Search']['value_from'])
				{
					$point_to		=	$this->data['Search']['value_from'];
					$point_from		=	$this->data['Search']['value_to'];
				}
				$cond_search["PointsHistory.value BETWEEN ? AND ?"]		=	array($point_from,$point_to);
			}
			
			//POINT BEFORE
			if(!empty($this->data['Search']['before_from']) && empty($this->data['Search']['before_to']))
			{
				$cond_search["PointsHistory.points_before >= "]		=	$this->data['Search']['before_from'];
			}
			
			if(empty($this->data['Search']['before_from']) && !empty($this->data['Search']['before_to']))
			{
				$cond_search["PointsHistory.points_before <= "]		=	$this->data['Search']['before_to'];
			}
			
			if(!empty($this->data['Search']['before_from']) && !empty($this->data['Search']['before_to']))
			{
				$point_from	=	$this->data['Search']['before_from'];
				$point_to		=	$this->data['Search']['before_to'];
				if($this->data['Search']['before_to'] < $this->data['Search']['before_from'])
				{
					$point_to		=	$this->data['Search']['before_from'];
					$point_from		=	$this->data['Search']['before_to'];
				}
				$cond_search["PointsHistory.points_before BETWEEN ? AND ?"]		=	array($point_from,$point_to);
			}
			
			//POINT AFTER
			if(!empty($this->data['Search']['after_from']) && empty($this->data['Search']['after_to']))
			{
				$cond_search["PointsHistory.points_after >= "]		=	$this->data['Search']['after_to'];
			}
			
			if(empty($this->data['Search']['after_from']) && !empty($this->data['Search']['after_to']))
			{
				$cond_search["PointsHistory.points_after <= "]		=	$this->data['Search']['after_to'];
			}
			
			if(!empty($this->data['Search']['after_from']) && !empty($this->data['Search']['after_to']))
			{
				$point_from	=	$this->data['Search']['after_from'];
				$point_to		=	$this->data['Search']['after_to'];
				if($this->data['Search']['after_to'] < $this->data['Search']['after_from'])
				{
					$point_to		=	$this->data['Search']['after_from'];
					$point_from		=	$this->data['Search']['after_to'];
				}
				$cond_search["PointsHistory.points_after BETWEEN ? AND ?"]		=	array($point_from,$point_to);
			}
			
			
			if(!empty($this->data['Search']['actionText']))
			{
				$cond_search["UserLogs.actionText LIKE "]				=	"%".$this->data['Search']['actionText']."%";
			}
			
			$this->Session->write("CondSearch.PointsHistory",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.PointsHistory');
			unset($this->data);
		}
		
		$cond_search		=	array();
		$filter_paginate	=	(preg_match('/^([0-9]+)$/',$user_id)) ? array('PointsHistory.user_id' => $user_id) : array();
		$this->paginate		=	array(
			'PointsHistory'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'fields'	=>	$fields,
				'group'		=>	array("PointsHistory.id")
			)
		);
		
		$ses_cond			=	$this->Session->read("CondSearch.PointsHistory");
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$data				=	$this->paginate('PointsHistory',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['PointsHistory']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['PointsHistory']['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
		$this->set('user_id',$user_id);
	}
}
?>