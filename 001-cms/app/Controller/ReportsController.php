<?php
class ReportsController extends AppController
{
	var $ControllerName		=	"Reports";
	var $ModelName			=	"Customer";
	var $helpers			=	array("Text","Aimfox");
	var $uses				=	"Customer";


	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set("ControllerName",$this->ControllerName);
		$this->set("ModelName",$this->ModelName);
		$this->set('lft_menu_category_id',"9");

		//CHECK PRIVILEGES
		$this->loadModel("MyAco");
		$find					=	$this->MyAco->find("first",array(
										"conditions"	=>	array(
											"LOWER(MyAco.alias)"	=>	strtolower("Report")
										)
									));
		$this->aco_id			=	$find["MyAco"]["id"];
		$this->set("aco_id",$this->aco_id);
	}

	function DataValid($page=1,$viewpage=50)
	{
		if($this->access[$this->aco_id]["_read"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}

		$this->Session->delete("Search.".$this->ControllerName);
		$this->Session->delete('Search.'.$this->ControllerName.'Operand');
		$this->Session->delete('Search.'.$this->ControllerName.'ViewPage');
		$this->Session->delete('Search.'.$this->ControllerName.'Sort');
		$this->Session->delete('Search.'.$this->ControllerName.'Page');
		$this->Session->delete('Search.'.$this->ControllerName.'Conditions');
		$this->Session->delete('Search.'.$this->ControllerName.'parent_id');
		$this->set(compact("page","viewpage"));
	}

	function DataValidListItem()
	{
		$this->layout	=	"ajax";
		if($this->access[$this->aco_id]["_read"] != "1")
		{
			$data			=	array();
			$this->set(compact("data"));
			return;
		}

		$this->loadModel('User');

		//DEFINE LAYOUT, LIMIT AND OPERAND
		$viewpage			=	empty($this->params['named']['limit']) ? 100 : $this->params['named']['limit'];
		$order				=	array("User.code" => "ASC");
		$operand			=	"AND";

		$sesStartDate = $this->Session->read("Search.".$this->ControllerName."startDate");
		$sesEndDate = $this->Session->read("Search.".$this->ControllerName."endDate");

		$startDate = isset($sesStartDate) ? $sesStartDate : false;
		$endDate = isset($sesEndDate) ? $sesEndDate : false;

		//DEFINE SEARCH DATA
		if(!empty($this->request->data))
		{
			$cond_search	=	array();
			$cond_search_ba = array();
			$cond_search_customer = array();
			$operand		=	$this->request->data[$this->ModelName]['operator'];
			$this->Session->delete('Search.'.$this->ControllerName);

			if(isset($this->request->data['Search']['start_date']) && !empty($this->request->data['Search']['start_date'])) {
				$startDate = $this->request->data['Search']['start_date'];

				if(isset($this->request->data['Search']['end_date']) && !empty($this->request->data['Search']['end_date'])) {
					$endDate = $this->request->data['Search']['end_date'];

				} else {

					$endDate = $this->request->data['Search']['start_date'];
				}

			}

			if(!empty($this->request->data['Search']['status'])) {
				$cond_search["Ba.status"] = $this->request->data['Search']['status'];
			}

			if($this->request->data["Search"]['reset']=="0")
			{
				$this->Session->write("Search.".$this->ControllerName."startDate",$startDate);
				$this->Session->write("Search.".$this->ControllerName."endDate",$endDate);

				$this->Session->write("Search.".$this->ControllerName,$cond_search);
				$this->Session->write("Search.".$this->ControllerName."CondCustomer",$cond_search_customer);
				$this->Session->write('Search.'.$this->ControllerName.'Operand',$operand);
			}
		}

		// $startDate = "2016-01-01";
		// $endDate = "2016-05-20";

		// harus di pilih dulu date nya soalnya. untuk cari date schedule nya
		if($startDate != false) {
			$this->Session->write('Search.'.$this->ControllerName.'Viewpage',$viewpage);
			$this->Session->write('Search.'.$this->ControllerName.'Sort',(empty($this->params['named']['sort']) or !isset($this->params['named']['sort'])) ? $order : $this->params['named']['sort']." ".$this->params['named']['direction']);

			$cond_search		=	array();
			$filter_paginate	=	array();
		}

			$this->paginate		=	array(
										"User"	=>	array(
											"order"				=>	$order,
											'limit'				=>	$viewpage,
											'recursive'	=> 0
										)
									);

			$ses_cond				=	$this->Session->read("Search.".$this->ControllerName);
			$cond_search		=	isset($ses_cond) ? $ses_cond : array();
			$ses_operand		=	$this->Session->read("Search.".$this->ControllerName."Operand");
			$operand				=	isset($ses_operand) ? $ses_operand : "AND";
			$merge_cond			=	empty($cond_search) ? $filter_paginate : array_merge($filter_paginate,array($operand => $cond_search) );
			$data						=	$this->paginate("User",$merge_cond);

			//debug($data);

			$ses_cond_customer = $this->Session->read("Search.".$this->ControllerName."CondCustomer");
			$cond_search_customer		=	isset($ses_cond_customer) ? $ses_cond_customer : array();
			$operand_customer			=	isset($ses_operand_customer) ? $ses_operand_customer : "AND";
			$merge_cond_customer = empty($cond_search_customer) ? $filter_paginate_customer : array_merge($filter_paginate_customer,array($operand_customer => $cond_search_customer));

			$merge_cond_customer = array_merge(
				$merge_cond_customer,
				array(
					'Customer.created between ? and ?' => array($startDate, $endDate)
				)
			);

			//debug($merge_cond_customer);
			$this->Session->write("Search.".$this->ControllerName."MergeCondCustomer", $merge_cond_customer);

			$list_customers = $this->Customer->find('all', array(
				'conditions' => $merge_cond_customer,
			));

			$list_customers_ids = array();
			$list_customers_ids_string = "";

			if(count($list_customers) > 0) {
				foreach($list_customers as $ctr => $ls) {
					if($ctr == 0) {
						$list_customers_ids_string = $ls['Customer']['id'];
					} else {
						$list_customers_ids_string = $list_customers_ids_string.", ".$ls['Customer']['id'];
					}
					$list_customers_ids[$ctr] = $ls['Customer']['id'];
				}
			}

			$this->loadModel('Customer');

			$allCustomer = $this->Customer->find('all', array(
				'fields' => array(
					'COUNT(Customer.id) as total_data',
					'Customer.user_id',
				),
				'conditions' => array(
					'Customer.status' => 1 // di ganti sejak kedatangan
				),
				'group' => array(
					'Customer.user_id'
				)
			));

			$allValid = $this->Customer->find('all', array(
				'fields' => array(
					'COUNT(Customer.id) as total_data_valid',
					'Customer.user_id'
				),
				'conditions' => array(
					'Customer.status' => 1,
					'Customer.is_valid' => 1
				),
				'group' => array(
					'Customer.user_id'
				)
			));

			$allNotValid = $this->Customer->find('all', array(
				'fields' => array(
					'COUNT(Customer.id) as total_data_not_valid',
					'Customer.user_id'
				),
				'conditions' => array(
					'Customer.status' => 1,
					'Customer.is_valid' => 0
				),
				'group' => array(
					'Customer.user_id'
				)
			));
			debug($allCustomer);


			// NOTE: cari working daysnya nih.
			/*
			query update untuk cari yang WD nya ada punchedLog nya sama udah login
			select ba_id, count(id) as total from (
				select distinct `PunchedLog`.`ba_id` as ba_id, `PunchedLog`.`id` as id FROM
			sales as Sale
			left join
			punched_logs as PunchedLog
			on
			Sale.schedule_id = PunchedLog.schedule_id and Sale.ba_id = PunchedLog.ba_id and Sale.id is not null
			WHERE `PunchedLog`.`schedule_id` IN (296, 297, 298, 324, 325, 326, 334, 335, 336, 344, 345, 346, 379, 380, 381, 389, 390, 391, 399, 400, 401, 409, 410, 411, 451, 452, 453, 462, 463, 464, 471, 472, 473, 481, 482, 483, 523, 524, 525, 533, 534, 535, 583, 584, 599, 600, 601, 609, 610, 611, 619, 620, 621, 629, 630, 631, 653, 654, 655, 673, 674, 675, 683, 684, 685, 693, 694, 695)
			order by PunchedLog.ba_id asc, PunchedLog.id asc
			) d
			group by ba_id
			*/

			/*
			$this->loadModel('PunchedLog');

			$punchedLogs = $this->PunchedLog->query("select ba_id, count(id) as total_punched from (
				select distinct `PunchedLog`.`ba_id` as ba_id, `PunchedLog`.`id` as id FROM
			sales as Sale
			left join
			punched_logs as PunchedLog
			on
			Sale.schedule_id = PunchedLog.schedule_id and Sale.ba_id = PunchedLog.ba_id and Sale.id is not null
			WHERE `PunchedLog`.`schedule_id` IN (".$list_schedules_ids_string.")
			order by PunchedLog.ba_id asc, PunchedLog.id asc
			) PunchedLog
			group by ba_id");

			//debug($thePunchedLogs);
			*/

			$matix = array();
			$matixAllValid = array();
			$matixAllNotValid = array();
			$matixAllCustomer = array();

			foreach($allValid as $an) {
				$matixAllValid[$an['Customer']['user_id']] = $an[0]['total_data_valid'];
			}

			foreach($allNotValid as $at) {
				$matixAllNotValid[$at['Customer']['user_id']] = $at[0]['total_data_not_valid'];
			}

			foreach($allCustomer as $as) {
				$matixAllCustomer[$as['Customer']['user_id']] = $as[0]['total_data'];
			}

			$this->Session->write('Search.'.$this->ControllerName.'Conditions',$merge_cond);

			if(isset($this->params['named']['page']) && $this->params['named']['page'] > $this->params['paging'][$this->ModelName]['pageCount'])
			{
				$this->params['named']['page']	=	$this->params['paging'][$this->ModelName]['pageCount'];
			}
			$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
			$this->Session->write('Search.'.$this->ControllerName.'Page',$page);

		$this->set(compact('data','page','viewpage', 'startDate', 'matixAllValid', 'matixAllNotValid', 'matixAllCustomer'));
	}

	function ExcelDataValid()
	{
		if($this->access[$this->aco_id]["_read"] != "1")
		{
			$this->layout	=	"no_access";
			return;
		}

		$this->layout = "ajax";

		$this->loadModel('User');

		$startDate = $this->Session->read("Search.".$this->ControllerName.".startDate");
		$endDate = $this->Session->read("Search.".$this->ControllerName.".endDate");
		$order				=	$this->Session->read("Search.".$this->ControllerName."Sort");
		$viewpage			=	$this->Session->read("Search.".$this->ControllerName."Viewpage");
		$page				=	$this->Session->read("Search.".$this->ControllerName."Page");

		$cond_search		=	array();
		$this->paginate		=	array(
									"User"	=>	array(
										"order"				=>	$order,
										'limit'				=>	$viewpage,
										'page' => $page,
										'recursive'	=> 0
									)
								);

		$ses_cond			=	$this->Session->read("Search.".$this->ControllerName);
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$ses_operand		=	$this->Session->read("Search.".$this->ControllerName."Operand");
		$operand			=	isset($ses_operand) ? $ses_operand : "AND";
		$merge_cond			=	empty($cond_search) ? $filter_paginate : array_merge($filter_paginate,array($operand => $cond_search) );
		$data				=	$this->paginate("User",$merge_cond);

		//debug($data);

		$this->Session->write("Search.".$this->ControllerName."MergeCondCustomer", $merge_cond_customer);

			$list_customers = $this->Customer->find('all', array(
				'conditions' => $merge_cond_customer,
			));

			$list_customers_ids = array();
			$list_customers_ids_string = "";

			if(count($list_customers) > 0) {
				foreach($list_customers as $ctr => $ls) {
					if($ctr == 0) {
						$list_customers_ids_string = $ls['Customer']['id'];
					} else {
						$list_customers_ids_string = $list_customers_ids_string.", ".$ls['Customer']['id'];
					}
					$list_customers_ids[$ctr] = $ls['Customer']['id'];
				}
			}

			$this->loadModel('Customer');

			$allCustomer = $this->Customer->find('all', array(
				'fields' => array(
					'COUNT(Customer.id) as total_data',
					'Customer.user_id',
				),
				'conditions' => array(
					'Customer.status' => 1 // di ganti sejak kedatangan
				),
				'group' => array(
					'Customer.user_id'
				)
			));

			$allValid = $this->Customer->find('all', array(
				'fields' => array(
					'COUNT(Customer.id) as total_data_valid',
					'Customer.user_id'
				),
				'conditions' => array(
					'Customer.status' => 1,
					'Customer.is_valid' => 1
				),
				'group' => array(
					'Customer.user_id'
				)
			));

			$allNotValid = $this->Customer->find('all', array(
				'fields' => array(
					'COUNT(Customer.id) as total_data_not_valid',
					'Customer.user_id'
				),
				'conditions' => array(
					'Customer.status' => 1,
					'Customer.is_valid' => 0
				),
				'group' => array(
					'Customer.user_id'
				)
			));
			debug($allCustomer);


			// NOTE: cari working daysnya nih.
			/*
			query update untuk cari yang WD nya ada punchedLog nya sama udah login
			select ba_id, count(id) as total from (
				select distinct `PunchedLog`.`ba_id` as ba_id, `PunchedLog`.`id` as id FROM
			sales as Sale
			left join
			punched_logs as PunchedLog
			on
			Sale.schedule_id = PunchedLog.schedule_id and Sale.ba_id = PunchedLog.ba_id and Sale.id is not null
			WHERE `PunchedLog`.`schedule_id` IN (296, 297, 298, 324, 325, 326, 334, 335, 336, 344, 345, 346, 379, 380, 381, 389, 390, 391, 399, 400, 401, 409, 410, 411, 451, 452, 453, 462, 463, 464, 471, 472, 473, 481, 482, 483, 523, 524, 525, 533, 534, 535, 583, 584, 599, 600, 601, 609, 610, 611, 619, 620, 621, 629, 630, 631, 653, 654, 655, 673, 674, 675, 683, 684, 685, 693, 694, 695)
			order by PunchedLog.ba_id asc, PunchedLog.id asc
			) d
			group by ba_id
			*/

			/*
			$this->loadModel('PunchedLog');

			$punchedLogs = $this->PunchedLog->query("select ba_id, count(id) as total_punched from (
				select distinct `PunchedLog`.`ba_id` as ba_id, `PunchedLog`.`id` as id FROM
			sales as Sale
			left join
			punched_logs as PunchedLog
			on
			Sale.schedule_id = PunchedLog.schedule_id and Sale.ba_id = PunchedLog.ba_id and Sale.id is not null
			WHERE `PunchedLog`.`schedule_id` IN (".$list_schedules_ids_string.")
			order by PunchedLog.ba_id asc, PunchedLog.id asc
			) PunchedLog
			group by ba_id");

			//debug($thePunchedLogs);
			*/

			$matix = array();
			$matixAllValid = array();
			$matixAllNotValid = array();
			$matixAllCustomer = array();

			foreach($allValid as $an) {
				$matixAllValid[$an['Customer']['user_id']] = $an[0]['total_data_valid'];
			}

			foreach($allNotValid as $at) {
				$matixAllNotValid[$at['Customer']['user_id']] = $at[0]['total_data_not_valid'];
			}

			foreach($allCustomer as $as) {
				$matixAllCustomer[$as['Customer']['user_id']] = $as[0]['total_data'];
			}

			$this->Session->write('Search.'.$this->ControllerName.'Conditions',$merge_cond);

			if(isset($this->params['named']['page']) && $this->params['named']['page'] > $this->params['paging'][$this->ModelName]['pageCount'])
			{
				$this->params['named']['page']	=	$this->params['paging'][$this->ModelName]['pageCount'];
			}
			$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
			$this->Session->write('Search.'.$this->ControllerName.'Page',$page);

		$this->set(compact('data','page','viewpage', 'startDate', 'matixAllValid', 'matixAllNotValid', 'matixAllCustomer'));

		$title				=	"total_data_valid_";
		$filename			=	$title."_".date("dMY").".xlsx";
		$this->set(compact("data","title","page","viewpage","filename"));
	} 
}
