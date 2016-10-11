<?php
class PortfolioCategoriesController extends AppController
{
	 var $ControllerName	= "PortfolioCategories";
	 var $ModelName 			= "PortfolioCategory";
	 var $helpers 				=	array("Text","General");
	 var $uses 						=	"PortfolioCategory";

	 function beforeFilter()
	 {
			 parent::beforeFilter();
			 $this->set("ControllerName", $this->ControllerName);
			 $this->set("ModelName", $this->ModelName);
			 $this->set('lft_menu_category_id', "6");

			 //CHECK PRIVILEGES
			 $this->loadModel("MyAco");
			 $find         = $this->MyAco->find("first", array(
					 "conditions" => array(
							 "LOWER(MyAco.alias)" => strtolower("PortfolioCategories")
					 )
			 ));
			 $this->aco_id = $find["MyAco"]["id"];
			 $this->set("aco_id", $this->aco_id);
	 }

	 function Index($page = 1, $viewpage = 50)
	 {
			 if ($this->access[$this->aco_id]["_read"] != "1") {
					 $this->layout = "no_access";
					 return;
			 }

			 $this->Session->delete("Search." . $this->ControllerName);
			 $this->Session->delete('Search.' . $this->ControllerName . 'Operand');
			 $this->Session->delete('Search.' . $this->ControllerName . 'ViewPage');
			 $this->Session->delete('Search.' . $this->ControllerName . 'Sort');
			 $this->Session->delete('Search.' . $this->ControllerName . 'Page');
			 $this->Session->delete('Search.' . $this->ControllerName . 'Conditions');
			 $this->Session->delete('Search.' . $this->ControllerName . 'parent_id');
			 $this->set(compact("page", "viewpage"));
	 }

	 function ListItem()
	 {
			 $this->layout = "ajax";

			 if ($this->access[$this->aco_id]["_read"] != "1") {
					 $data = array();
					 $this->set(compact("data"));
					 return;
			 }

			 $this->loadModel($this->ModelName);
			 $this->{$this->ModelName}->BindDefault(false);
			 $this->{$this->ModelName}->VirtualFieldActivated();


			 //DEFINE LAYOUT, LIMIT AND OPERAND
			 $viewpage = empty($this->params['named']['limit']) ? 50 : $this->params['named']['limit'];
			 $order    = array(
					 "{$this->ModelName}.id" => "DESC"
			 );
			 $operand  = "AND";

			 //DEFINE SEARCH DATA
			 if (!empty($this->request->data)) {
					 $cond_search = array();
					 $operand     = $this->request->data[$this->ModelName]['operator'];
					 $this->Session->delete('Search.' . $this->ControllerName);

					 if (!empty($this->request->data['Search']['id'])) {
							 $cond_search["{$this->ModelName}.id"] = $this->data['Search']['id'];
					 }

					 if (!empty($this->request->data['Search']['name'])) {
							 $cond_search["{$this->ModelName}.name LIKE "] = "%" . $this->data['Search']['name'] . "%";
					 }

					 if (!empty($this->request->data['Search']['description'])) {
							 $cond_search["{$this->ModelName}.name LIKE "] = "%" . $this->data['Search']['description'] . "%";
					 }

					 if (!empty($this->data['Search']['start_date']) && empty($this->data['Search']['end_date'])) {
							 $cond_search["{$this->ModelName}.created >= "] = $this->data['Search']['start_date'] . " 00:00:00";
					 }

					 if (empty($this->data['Search']['start_date']) && !empty($this->data['Search']['end_date'])) {
							 $cond_search["{$this->ModelName}.created <= "] = $this->data['Search']['end_date'] . " 23:59:59";
					 }

					 if (!empty($this->data['Search']['start_date']) && !empty($this->data['Search']['end_date'])) {
							 $tmp                                                            = $this->data['Search']['start_date'];
							 $START                                                          = (strtotime($this->data['Search']['end_date']) < strtotime($this->data['Search']['start_date'])) ? $this->data['Search']['end_date'] : $this->data['Search']['start_date'];
							 $END                                                            = ($this->data['Search']['end_date'] < $tmp) ? $tmp : $this->data['Search']['end_date'];
							 $cond_search["{$this->ModelName}.created BETWEEN ? AND ? "] = array(
									 $START . " 00:00:00",
									 $END . " 23:59:59"
							 );
					 }

					 if ($this->request->data["Search"]['reset'] == "0") {
							 $this->Session->write("Search." . $this->ControllerName, $cond_search);
							 $this->Session->write('Search.' . $this->ControllerName . 'Operand', $operand);
					 }
			 }

			 $this->Session->write('Search.' . $this->ControllerName . 'Viewpage', $viewpage);
			 $this->Session->write('Search.' . $this->ControllerName . 'Sort', (empty($this->params['named']['sort']) or !isset($this->params['named']['sort'])) ? $order : $this->params['named']['sort'] . " " . $this->params['named']['direction']);

			 $cond_search     = array();
			 $filter_paginate = array();
			 $this->paginate  = array(
					 "{$this->ModelName}" => array(
							 "order" => $order,
							 'limit' => $viewpage
					 )
			 );

			 $ses_cond    = $this->Session->read("Search." . $this->ControllerName);
			 $cond_search = isset($ses_cond) ? $ses_cond : array();
			 $ses_operand = $this->Session->read("Search." . $this->ControllerName . "Operand");
			 $operand     = isset($ses_operand) ? $ses_operand : "AND";
			 $merge_cond  = empty($cond_search) ? $filter_paginate : array_merge($filter_paginate, array(
					 $operand => $cond_search
			 ));
			 $data        = $this->paginate("{$this->ModelName}", $merge_cond);

			 $this->Session->write('Search.' . $this->ControllerName . 'Conditions', $merge_cond);

			 if (isset($this->params['named']['page']) && $this->params['named']['page'] > $this->params['paging'][$this->ModelName]['pageCount']) {
					 $this->params['named']['page'] = $this->params['paging'][$this->ModelName]['pageCount'];
			 }
			 $page = empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
			 $this->Session->write('Search.' . $this->ControllerName . 'Page', $page);
			 $this->set(compact('data', 'page', 'viewpage'));
	 }


	 function Excel()
	 {
			 if ($this->access[$this->aco_id]["_read"] != "1") {
					 $this->layout = "no_access";
					 return;
			 }

			 $this->layout = "ajax";
			 $this->{$this->ModelName}->BindDefault(false);

			 $order      = $this->Session->read("Search." . $this->ControllerName . "Sort");
			 $viewpage   = $this->Session->read("Search." . $this->ControllerName . "Viewpage");
			 $page       = $this->Session->read("Search." . $this->ControllerName . "Page");
			 $conditions = $this->Session->read("Search." . $this->ControllerName . "Conditions");

			 $this->paginate = array(
					 "{$this->ModelName}" => array(
							 "order" => $order,
							 "limit" => $viewpage,
							 "conditions" => $conditions,
							 "page" => $page
					 )
			 );

			 $data     = $this->paginate("{$this->ModelName}", $conditions);
			 $title    = $this->ModelName;
			 $filename = "Order_" . date("dMY") . ".xls";
			 $this->set(compact("data", "title", "page", "viewpage", "filename"));
	 }

	 function Add()
	 {
			 if ($this->access[$this->aco_id]["_create"] != "1") {
					 $this->layout = "no_access";
					 return;
			 }

			 if (!empty($this->request->data)) {

					 $this->{$this->ModelName}->set($this->request->data);
					 $this->{$this->ModelName}->ValidateAdd();
					 if ($this->{$this->ModelName}->validates()) {
							 $save = $this->{$this->ModelName}->save($this->request->data);
							 $ID   = $this->{$this->ModelName}->getLastInsertId();
							 $this->redirect(array(
									 "action" => "SuccessAdd",
									 $ID
							 ));
					 } //END IF VALIDATE
			 } //END IF NOT EMPTY

	 }

	 function Edit($ID = NULL, $page = 1, $viewpage = 50)
	 {
			 if (($ID == $this->super_admin_id && $this->profile["Admin"]["id"] != $this->super_admin_id) or $this->access[$this->aco_id]["_update"] != "1") {
					 $this->layout = "no_access";
					 return;
			 }

			 $this->{$this->ModelName}->BindDefault(false);
			 $detail = $this->{$this->ModelName}->find('first', array(
					 'conditions' => array(
							 "{$this->ModelName}.id" => $ID
					 )
			 ));

			 if (empty($detail)) {
					 $this->layout = "ajax";
					 $this->render("/errors/error404");
					 return;
			 }

			 if (empty($this->data)) {
					 $this->data = $detail;
			 } else {
					 $this->{$this->ModelName}->set($this->data);
					 $this->{$this->ModelName}->ValidateEdit();

					 if ($this->{$this->ModelName}->validates()) {
							 $save = $this->{$this->ModelName}->save($this->data, false);
							 $this->redirect(array(
									 'action' => 'SuccessEdit',
									 $ID,
									 $page,
									 $viewpage
							 ));
					 }
			 }
			 $this->set(compact("ID", "detail", "page", "viewpage"));
	 }

	 function View($ID = NULL)
	 {
			 if ($this->access[$this->aco_id]["_read"] != "1") {
					 $this->layout = "no_access";
					 return;
			 }

			 $this->loadModel($this->ModelName);
			 $this->{$this->ModelName}->BindImageBig(false);
			 $this->{$this->ModelName}->VirtualFieldActivated();

			 $detail = $this->{$this->ControllerName}->find('first', array(
					 'conditions' => array(
							 "{$this->ControllerName}.id" => $ID
					 )
			 ));
			 if (empty($detail)) {
					 $this->layout = "ajax";
					 $this->set(compact("ID", "data"));
					 $this->render("/errors/error404");
					 return;
			 }
			 $this->set(compact("ID", "detail"));
	 }

	 function ChangeStatus($ID = NULL, $status)
	 {
			 if ($this->access[$this->aco_id]["_update"] != "1") {
					 echo json_encode(array(
							 "data" => array(
									 "status" => "0",
									 "message" => "No privileges"
							 )
					 ));
					 $this->autoRender = false;
					 return;
			 }
			 $detail = $this->{$this->ModelName}->find('first', array(
					 'conditions' => array(
							 "{$this->ModelName}.id" => $ID
					 )
			 ));

			 $resultStatus = "0";
			 if (empty($detail)) {
					 $message = "Item not found.";
			 } else {
					 $data[$this->ModelName]["id"]     = $ID;
					 $data[$this->ModelName]["status"] = $status;
					 $this->{$this->ModelName}->save($data);
					 $message      = "Data has updated.";
					 $resultStatus = "1";
			 }

			 echo json_encode(array(
					 "data" => array(
							 "status" => $resultStatus,
							 "message" => $message
					 )
			 ));
			 $this->autoRender = false;
	 }

	 function ChangeStatusMultiple()
	 {
			 if ($this->access[$this->aco_id]["_update"] != "1") {
					 echo json_encode(array(
							 "data" => array(
									 "status" => "0",
									 "message" => "No privileges"
							 )
					 ));
					 $this->autoRender = false;
					 return;
			 }

			 $ID     = explode(",", $_REQUEST["id"]);
			 $status = $_REQUEST["status"];


			 $this->{$this->ModelName}->updateAll(array(
					 "status" => "'" . $status . "'"
			 ), array(
					 "{$this->ModelName}.id" => $ID
			 ));
			 $message = "Data has updated.";
			 echo json_encode(array(
					 "data" => array(
							 "status" => "1",
							 "message" => $message
					 )
			 ));
			 $this->autoRender = false;
	 }

	 function Delete($ID = NULL)
	 {
			 if ($this->access[$this->aco_id]["_delete"] != "1") {
					 echo json_encode(array(
							 "data" => array(
									 "status" => "0",
									 "message" => "No privileges"
							 )
					 ));
					 $this->autoRender = false;
					 return;
			 }

			 $detail       = $this->{$this->ModelName}->find('first', array(
					 'conditions' => array(
							 "{$this->ModelName}.id" => $ID
					 )
			 ));
			 $resultStatus = "0";

			 if (empty($detail)) {
					 $message      = "Item not found.";
					 $resultStatus = "0";
			 } else {
					 $this->{$this->ModelName}->delete($ID, false);
					 $message      = "Data has deleted.";
					 $resultStatus = "1";
			 }

			 echo json_encode(array(
					 "data" => array(
							 "status" => $resultStatus,
							 "message" => $message
					 )
			 ));
			 $this->autoRender = false;
	 }

	 function DeleteMultiple()
	 {
			 if ($this->access[$this->aco_id]["_delete"] != "1") {
					 echo json_encode(array(
							 "data" => array(
									 "status" => "0",
									 "message" => "No privileges"
							 )
					 ));
					 $this->autoRender = false;
					 return;
			 }

			 $id = explode(",", $_REQUEST["id"]);

			 $this->{$this->ModelName}->deleteAll(array(
					 "id" => $id
			 ), false, true);
			 $message = "Data has deleted.";

			 echo json_encode(array(
					 "data" => array(
							 "status" => "1",
							 "message" => $message
					 )
			 ));
			 $this->autoRender = false;
	 }

	 function SuccessAdd($ID = NULL)
	 {
			 $data = $this->{$this->ModelName}->find('first', array(
					 'conditions' => array(
							 "{$this->ModelName}.id" => $ID
					 )
			 ));
			 if (empty($data)) {
					 $this->layout = "ajax";
					 $this->render("/errors/error404");
			 }
			 $this->set(compact("ID"));
	 }

	 function SuccessEdit($ID = NULL, $page = 1, $viewpage = 50)
	 {
			 $data = $this->{$this->ModelName}->find('first', array(
					 'conditions' => array(
							 "{$this->ModelName}.id" => $ID
					 )
			 ));

			 if (empty($data)) {
					 $this->layout = "ajax";
					 $this->render("/errors/error404");
			 }
			 $this->set(compact("ID", "page", "viewpage","data"));
	 }
}
?>
