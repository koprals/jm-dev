<?php
class UserLogsController extends AppController
{
<<<<<<< HEAD
	 var $ControllerName	= "UserLogs";
	 var $ModelName 			= "UserLog";
	 var $helpers 				=	array("Text","General");
	 var $uses 						=	"UserLog";

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
							 "LOWER(MyAco.alias)" => strtolower("UserLogs")
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
			 $this->set(compact("page",	"viewpage"));
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

					 if (!empty($this->request->data['Search']['portfolio_category_id'])) {
							 $cond_search["{$this->ModelName}.portfolio_category_id"] = $this->data['Search']['portfolio_category_id'];
					 }

					 if (!empty($this->request->data['Search']['portfolio_subcategory_id'])) {
							 $cond_search["{$this->ModelName}.portfolio_subcategory_id"] = $this->data['Search']['portfolio_subcategory_id'];
					 }

					 if (!empty($this->request->data['Search']['client_id'])) {
							 $cond_search["{$this->ModelName}.client_id"] = $this->data['Search']['client_id'];
					 }

					 if (!empty($this->request->data['Search']['name'])) {
							 $cond_search["{$this->ModelName}.title LIKE "] = "%" . $this->data['Search']['name'] . "%";
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
							 "order" 			=>	$order,
							 'limit' 			=>	$viewpage,
							 "recursive"	=>	2
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

			 //DEFINE CATEGORY
			 $this->loadModel("PortfolioCategory");
			 $category_id_list		=		$this->PortfolioCategory->find("list",array(
				 													"conditions"	=>	array(
																		"PortfolioCategory.status"	=>	"1"
																	)
			 													));

			 //DEFINE CLIENT
 			 $this->loadModel("Client");
 			 $client_id_list			=		$this->Client->find("list",array(
 				 													"conditions"	=>	array(
 																		"Client.status"	=>	"1"
 																	)
 			 													));

			 //DEFINE USER TARGET
			 $this->loadModel("UserTarget");
			 $user_target_id_list			=		$this->UserTarget->find("list",array(
						 													"conditions"	=>	array(
																				"UserTarget.status"	=>	"1"
																			)
					 													));


			$errorImg							=		array();
			$isExistsUpload				=		false;

			$errorFeature					=		array();

			 if (!empty($this->request->data))
			 {
				  $addImage					=		0;
					if(isset($this->request->data[$this->ModelName]['images']))
						$addImage				=		count($this->request->data[$this->ModelName]['images'])-1;

					$this->{$this->ModelName}->set($this->request->data);
					$this->{$this->ModelName}->ValidateAdd();
					$error						=		$this->{$this->ModelName}->invalidFields();

					//PHOTO
					$uploadPhoto			=		isset($this->request->data[$this->ModelName]["photo"]) ? $this->request->data[$this->ModelName]["photo"] : array();

					if(!empty($uploadPhoto))
					{
						for($i=0;$i<count($uploadPhoto);$i++)
						{
							if(!empty($uploadPhoto[$i]["name"]))
							{
								$size				=	$uploadPhoto[$i]["size"];
								$name				=	$uploadPhoto[$i]["name"];
								$imgInfo		=	getimagesize($uploadPhoto[$i]['tmp_name']);
								$width			=	$imgInfo[0];
								$height			=	$imgInfo[1];
								$isExistsUpload	=	true;


								if($width < 500)
								{
									$errorImg[$i]					=		'Please upload image with minimum width is 500px for image '.($i+1).'.';
									$error["image-$i"]		=		array($errorImg[$i]);
								}

								if($height < 500)
								{
									$errorImg[$i]					=		'Please upload image with minimum height is 500px for image '.($i+1).'.';
									$error["image-$i"]		=		array($errorImg[$i]);
								}

								if(!Validation::extension($name, array('gif','jpeg','jpg','png')))
								{
									$errorImg[$i]					=		'Only (*.gif,*.jpeg,*.jpg,*.png) are allowed for image '.($i+1).'.';
									$error["image-$i"]		=		array($errorImg[$i]);
								}
							}
						}
					}
					else
					{
						$error["image"]		=	array("Please upload at least 1 photos");
					}

					if(!$isExistsUpload )
					{
						$errorImg[0]			=		'Please upload portfolio image default';
						$error["image"]		=		array("Please upload at least 1 photos");
					}

					//FEATURE
					$featurePortfolio		=		isset($this->request->data["PortfolioFeature"]) ? $this->request->data["PortfolioFeature"] : array();
					if(!empty($featurePortfolio))
					{
						for($i=0;$i<count($featurePortfolio);$i++)
						{
							if(strlen($featurePortfolio[$i]['name']) < 3)
							{
								$errorFeature[$i]					=		'Feature is too sort';
								$error["feature-$i"]			=		array($errorFeature[$i]);
							}

							if(empty($featurePortfolio[$i]['name']))
							{
								$errorFeature[$i]					=		'Please insert portfolio feature';
								$error["feature-$i"]			=		array($errorFeature[$i]);
							}
						}
					}

					if (empty($error) && empty($errorImg) && empty($errorFeature)) {

						//FEATURE
						if(isset($this->request->data['PortfolioFeature']))
						{
							foreach($this->request->data['PortfolioFeature'] as $k => $PortfolioFeature)
							{
								if(empty($PortfolioFeature['name']))
								{
									unset($this->request->data['PortfolioFeature'][$k]);
								}
								else {
									$this->request->data['PortfolioFeature'][$k]['name']	=	Sanitize::html($this->request->data['PortfolioFeature'][$k]['name']);
								}
							}
						}

						$this->{$this->ModelName}->BindFeature(false);
					  $save = $this->{$this->ModelName}->saveAll($this->request->data,array("validate"=>false));
						$ID   = $this->{$this->ModelName}->getLastInsertId();


						//////////////////////////////////////START SAVE FOTO/////////////////////////////////////////////
						$this->loadModel("PortfolioImage");

						for($i=0;$i<count($this->request->data[$this->ModelName]['photo']);$i++)
						{
							if(!empty($this->request->data[$this->ModelName]['photo'][$i]["name"]))
							{
								$this->PortfolioImage->create();
								$this->PortfolioImage->saveAll(
									array(
										"portfolio_id"		=>	$ID,
										"pos"							=>	$i
									),
									array(
										"validate"				=>	false
									)
								);

								$PortfolioImageId			=		$this->PortfolioImage->getLastInsertId();
								$tmp_name							=		$this->request->data[$this->ModelName]["photo"][$i]["name"];
								$tmp									=		$this->request->data[$this->ModelName]["photo"][$i]["tmp_name"];
								$mime_type						=		$this->request->data[$this->ModelName]["photo"][$i]["type"];

								$path_tmp							=		ROOT.DS.'app'.DS.'tmp'.DS.'upload'.DS;
									if(!is_dir($path_tmp)) mkdir($path_tmp,0777);

								$ext									=		pathinfo($tmp_name,PATHINFO_EXTENSION);
								$tmp_file_name				=		md5(time());
								$tmp_images1_img			=		$path_tmp.$tmp_file_name.".".strtolower($ext);
								$upload 							=		move_uploaded_file($tmp,$tmp_images1_img);

								if($upload)
								{
									$resize							=		$this->General->ResizeImageContent(
																						$tmp_images1_img,
																						$this->settings["cms_url"],
																						$PortfolioImageId,
																						"PortfolioImage",
																						"big",
																						$mime_type,
																						500,
																						500,
																						"cropFill"
																					);
								}
								@unlink($tmp_images1_img);
							}
						}
						//////////////////////////////////////END SAVE FOTO/////////////////////////////////////////////
						 $this->redirect(array(
								 "action" => "SuccessAdd",
								 $ID
						 ));
					} //END IF VALIDATE
					else {
						$errMessage	=	"";
						foreach($error as $k => $message)
						{
							$errMessage	.=	reset($message)."<br/>";
						}

						$this->Session->setFlash(
							'<p>'.$errMessage.'</p>',
							'default',
							array(
								'class' => 'nNote nFailure hideit',
							)
						);
					}
			 } //END IF NOT EMPTY

			 $this->set(compact(
			 	"category_id_list",
				"client_id_list",
				"user_target_id_list",
				"errMessage",
				"errorFeature"
		 	 ));
	 }

	 function Edit($ID = NULL, $page = 1, $viewpage = 50)
	 {
			 if (($ID == $this->super_admin_id && $this->profile["Admin"]["id"] != $this->super_admin_id) or $this->access[$this->aco_id]["_update"] != "1") {
					 $this->layout = "no_access";
					 return;
			 }

			 $this->{$this->ModelName}->BindFeature(false);
			 $this->{$this->ModelName}->BindHasManyImage(false);
			 $this->{$this->ModelName}->PortfolioImage->BindImage(false);
			 $detail = $this->{$this->ModelName}->find('first', array(
					 'conditions' => array(
							 "{$this->ModelName}.id" => $ID
					 ),
					 "recursive"	=>	3
			 ));

			 if (empty($detail)) {
					 $this->layout = "ajax";
					 $this->render("/errors/error404");
					 return;
			 }

			 //DEFINE CATEGORY
			 $this->loadModel("PortfolioCategory");
			 $category_id_list		=		$this->PortfolioCategory->find("list",array(
				 													"conditions"	=>	array(
																		"PortfolioCategory.status"	=>	"1"
																	)
			 													));

			 //DEFINE CLIENT
 			 $this->loadModel("Client");
 			 $client_id_list			=		$this->Client->find("list",array(
 				 													"conditions"	=>	array(
 																		"Client.status"	=>	"1"
 																	)
 			 													));

			 //DEFINE USER TARGET
			 $this->loadModel("UserTarget");
			 $user_target_id_list			=		$this->UserTarget->find("list",array(
						 													"conditions"	=>	array(
																				"UserTarget.status"	=>	"1"
																			)
					 													));

			$error										=		array();
			$errorImg									=		array();

			if (empty($this->data)) {
				if(!empty($detail["PortfolioImage"]))
				{
					for($i=0;$i<count($detail["PortfolioImage"]);$i++)
					{
						$detail[$this->ModelName]["photo"][$i]["url"]			=	$detail["PortfolioImage"][$i]["Image"]["host"].$detail["PortfolioImage"][$i]["Image"]["url"];
						$detail[$this->ModelName]["photo"][$i]["path"]		=	$detail["PortfolioImage"][$i]["Image"]["path"];
						$detail[$this->ModelName]["photo"][$i]["id"]			=	$detail["PortfolioImage"][$i]["Image"]["model_id"];
					}
				}
				$this->data = $detail;
			} else {

					$this->{$this->ModelName}->set($this->data);
					$this->{$this->ModelName}->ValidateAdd();
					$error			=	$this->{$this->ModelName}->invalidFields();

					//PHOTO
					$uploadPhoto	=	isset($this->request->data[$this->ModelName]["photo"]) ? $this->request->data[$this->ModelName]["photo"] : array();

					if(!empty($uploadPhoto))
					{
						for($i=0;$i<count($uploadPhoto);$i++)
						{
							if(!empty($uploadPhoto[$i]["name"]))
							{
								$this->request->data[$this->ModelName]["photo"][$i]["url"]	=	$this->settings["cms_url"]."img/default_content_horizontal.png";

								$size			=	$uploadPhoto[$i]["size"];
								$name			=	$uploadPhoto[$i]["name"];
								$imgInfo		=	getimagesize($uploadPhoto[$i]['tmp_name']);
								$width			=	$imgInfo[0];
								$isExistsUpload	=	true;

								if($width < 500)
								{
									$errorImg[$i]			=	'Please upload image with minimum width is 500px for image '.($i+1).'.';
									$error["image-$i"]		=	array($errorImg[$i]);
								}

								if(!Validation::extension($name, array('gif','jpeg','jpg','png')))
								{
									$errorImg[$i]			=	'Only (*.gif,*.jpeg,*.jpg,*.png) are allowed for image '.($i+1).'.';
									$error["image-$i"]		=	array($errorImg[$i]);
								}
							}
							else
							{
								if(!empty($detail["PortfolioImage"][$i]["Image"]["host"]))
								{
									$this->request->data[$this->ModelName]["photo"][$i]["url"]		=	(empty($this->request->data[$this->ModelName]["url_image"][$i])) ? $detail["PortfolioImage"][$i]["Image"]["host"].$detail["PortfolioImage"][$i]["Image"]["url"] : $this->request->data[$this->ModelName]["url_image"][$i];
									$this->request->data[$this->ModelName]["photo"][$i]["path"]		=	(empty($this->request->data[$this->ModelName]["path_image"][$i])) ? $detail["PortfolioImage"][$i]["Image"]["path"] : $this->request->data[$this->ModelName]["path_image"][$i];
									$this->request->data[$this->ModelName]["photo"][$i]["id"]		=	(empty($this->request->data[$this->ModelName]["id"][$i])) ? $detail["PortfolioImage"][$i]["Image"]["model_id"] : $this->request->data[$this->ModelName]["id_image"][$i];
								}
								else
								{
									$this->request->data[$this->ModelName]["photo"][$i]["url"]	=	$this->settings["cms_url"]."img/default_content_horizontal.png";
								}
							}
						}
					}
					else
					{
						$error["image"]		=	array("Please upload at least 1 photos");
					}

					//FEATURE
					$featurePortfolio		=		isset($this->request->data["PortfolioFeature"]) ? $this->request->data["PortfolioFeature"] : array();
					if(!empty($featurePortfolio))
					{
						for($i=0;$i<count($featurePortfolio);$i++)
						{
							if(strlen($featurePortfolio[$i]['name']) < 3)
							{
								$errorFeature[$i]					=		'Feature is too sort';
								$error["feature-$i"]			=		array($errorFeature[$i]);
							}

							if(empty($featurePortfolio[$i]['name']))
							{
								$errorFeature[$i]					=		'Please insert portfolio feature';
								$error["feature-$i"]			=		array($errorFeature[$i]);
							}
						}
					}

					 if (empty($error)) {

								//FEATURE
								if(isset($this->request->data['PortfolioFeature']))
								{
									foreach($this->request->data['PortfolioFeature'] as $k => $PortfolioFeature)
									{
										if(empty($PortfolioFeature['name']))
										{
											unset($this->request->data['PortfolioFeature'][$k]);
										}
										else {
											$this->request->data['PortfolioFeature'][$k]['name']	=	Sanitize::html($this->request->data['PortfolioFeature'][$k]['name']);
										}
									}
								}

							 $this->{$this->ModelName}->BindFeature(false);
							 $this->{$this->ModelName}->PortfolioFeature->deleteAll(array("PortfolioFeature.portfolio_id" => $ID));
							 $save = $this->{$this->ModelName}->saveAll($this->request->data, array("validate"=>false));

							 //////////////////////////////////////START SAVE FOTO/////////////////////////////////////////////
							 $this->loadModel("PortfolioImage");
							 for($i=0;$i<10;$i++)
							 {
							   if(isset($this->request->data[$this->ModelName]['photo'][$i]))
							   {
							     if(!empty($this->request->data[$this->ModelName]['photo'][$i]["name"]))
							     {
							       //CHECK FIRST IF CONTENT IS AVAILABLE
							       $checkContent	=	$this->PortfolioImage->find("first",array(
							                   "conditions"	=>	array(
							                     "PortfolioImage.portfolio_id"	=>	$ID,
							                     "PortfolioImage.pos"					=>	$i,
							                   )
							                 ));

							       if(!empty($checkContent))
							       {
							         $PortfolioImageId	=	$checkContent["PortfolioImage"]["id"];
							       }
							       else
							       {
							         //echo "Tambah Image - ".$i;
							         $this->PortfolioImage->create();
							         $this->PortfolioImage->saveAll(
							           array(
							             "portfolio_id"		=>	$ID,
							             "pos"							=>	$i
							           ),
							           array(
							             "validate"				=>	false
							           )
							         );
							         $PortfolioImageId						=	$this->PortfolioImage->getLastInsertId();
							       }

							       $tmp_name							=	$this->request->data[$this->ModelName]["photo"][$i]["name"];
							       $tmp										=	$this->request->data[$this->ModelName]["photo"][$i]["tmp_name"];
							       $mime_type							=	$this->request->data[$this->ModelName]["photo"][$i]["type"];

							       $path_tmp								=	ROOT.DS.'app'.DS.'tmp'.DS.'upload'.DS;
							         if(!is_dir($path_tmp)) mkdir($path_tmp,0777);

							       $ext										=	pathinfo($tmp_name,PATHINFO_EXTENSION);
							       $tmp_file_name					=	md5(time());
							       $tmp_images1_img				=	$path_tmp.$tmp_file_name.".".strtolower($ext);
							       $upload 								=	move_uploaded_file($tmp,$tmp_images1_img);

							       if($upload)
							       {
							         $resize								=	$this->General->ResizeImageContent(
							                                   $tmp_images1_img,
							                                   $this->settings["cms_url"],
							                                   $PortfolioImageId,
							                                   "PortfolioImage",
							                                   "big",
							                                   $mime_type,
							                                   800,
							                                   800,
							                                   "cropFill"
							                                 );
							       }
							       @unlink($tmp_images1_img);
							     }
							     else
							     {
							       if(!empty($this->request->data[$this->ModelName]['path_image'][$i]))
							       {
							         //CHECK FIRST IF CONTENT IS AVAILABLE
							         $checkContent	=	$this->PortfolioImage->find("first",array(
							                     "conditions"	=>	array(
							                       "PortfolioImage.portfolio_id"		=>	$ID,
							                       "PortfolioImage.pos"						=>	$i,
							                     )
							                   ));

							         if(!empty($checkContent))
							         {
							           $PortfolioImageId	=	$checkContent["PortfolioImage"]["id"];
							         }
							         else
							         {
							           //echo "Tambah Image - ".$i;
							           $this->PortfolioImage->create();
							           $this->PortfolioImage->saveAll(
							             array(
							               "portfolio_id"		=>	$ID,
							               "pos"							=>	$i
							             ),
							             array(
							               "validate"		=>	false
							             )
							           );
							           $PortfolioImageId						=	$this->PortfolioImage->getLastInsertId();
							         }

							         if($PortfolioImageId != $this->request->data[$this->ModelName]['id_image'][$i])
							         {
							           $path_content	=	$this->settings['path_content'];
							             if(!is_dir($path_content))mkdir($path_content,0777);

							           $path_model		=	$path_content."PortfolioImage/";
							             if(!is_dir($path_model)) mkdir($path_model,0777);

							           $path_model_id	=	$path_model . $PortfolioImageId . "/";
							             if(!is_dir($path_model_id))
							               mkdir($path_model_id,0777);
							             else
							             {
							               $this->General->RmDir($path_model_id);
							               mkdir($path_model_id,0777);
							             }

							           $resize							=	$this->General->ResizeImageContent(
							                                   $this->request->data[$this->ModelName]['path_image'][$i],
							                                   $this->settings["cms_url"],
							                                   $PortfolioImageId,
							                                   "PortfolioImage",
							                                   "big",
							                                   $mime_type,
							                                   800,
							                                   800,
							                                   "cropFill"
							                                 );
							         }
							       }
							     }
							   }
							   else
							   {
							     //echo "Hapus Image - ".$i;
							     $this->PortfolioImage->deleteAll(array(
							       "portfolio_id"				=>	$ID,
							       "pos"								=>	$i,
							     ),false, true);
							   }
							 }//END FOR
							 //////////////////////////////////////END SAVE FOTO/////////////////////////////////////////////


							 $this->redirect(array(
									 'action' => 'SuccessEdit',
									 $ID,
									 $page,
									 $viewpage
							 ));
					 }
					else
					{
						$errMessage	=	"";
						foreach($error as $k => $message)
						{
							$errMessage	.=	reset($message)."<br/>";
						}
						//var_dump($errMessage);
						$this->Session->setFlash(
							'<p>'.$errMessage.'</p>',
							'default',
							array(
								'class' => 'nNote nFailure hideit',
							)
						);
					}
			 }

			$this->set(compact(
				"ID",
				"detail",
				"page",
				"viewpage",
				"category_id_list",
				"client_id_list",
				"user_target_id_list",
				"error",
				"errMessage",
				"errorImg",
				"errorFeature"
			));
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

	function GetSubcategory()
	{
		$portfolio_category_id	=	$_REQUEST['portfolio_category_id'];

		//DEFINE STATE
		$this->loadModel('PortfolioSubcategory');
		$conditions		=		array('PortfolioSubcategory.portfolio_category_id'	=>	$portfolio_category_id);
		$data					=		$this->PortfolioSubcategory->find('all',array(
												'order'	=>	array(
													'PortfolioSubcategory.name ASC'
												 ),
												'conditions'	=>	$conditions
											));

		$out					=		array("data"	=>	$data);
		echo json_encode($out);
		$this->autoRender	=	false;
	}
}
?>
=======
	var $name		=	"UserLogs";
	var $uses		=	array('UserLogs');
	var $components	=	array('Action','General');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','admin_user_management');
		$this->layout	=	"new";
	}
	
	function Index($user_id="all")
	{
		$this->set('child_code','user_list');
		$this->Session->delete('CondSearch.UserLogs');
		
		//SET ACTION TYPE
		$this->loadModel('ActionTypes');
		$actionID	=	$this->ActionTypes->find("list",array(
							"fields"		=>	array("id","name"),
							"order"			=>	array("name ASC")
						));
		$this->set("actionID",$actionID);
		$this->set("user_id",$user_id);
	}
	
	function ListItem($user_id="all")
	{
		$this->layout	=	"ajax";
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order				=	array('UserLogs.modified DESC');
		
		//DEFINE FIELDS
		$fields				=	array(
									'UserLogs.*',
									'PointsHistory.value',
									'ActionTypes.name'
								);
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('CondSearch.UserLogs');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'UserLogs.actionText LIKE'		=> "%$val%",
											'ActionTypes.name LIKE'			=> "%$val%"
										)
									);
				
				}
			}
			$OR['OR'][]			=	"MATCH (UserLogs.actionText, ActionTypes.name) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			array_push($fields,"MATCH (UserLogs.actionText, ActionTypes.name) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("CondSearch.UserLogs",$cond_search);
			$order			=	array('score DESC','UserLogs.created DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		if(!empty($this->data))
		{
			$this->Session->delete('CondSearch.UserLogs');
			$trans 								=	array(' ' => '', '.' => '', ',' => '');
			$this->data['Search']['point_from']	=	strtr($this->data['Search']['point_from'], $trans);
			$this->data['Search']['point_to']	=	strtr($this->data['Search']['point_to'], $trans);
			
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["UserLogs.id"]					=	$this->data['Search']['id'];
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
					
					$cond_search["UserLogs.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
				{
					$cond_search["UserLogs.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				}
			}
			
			if(!empty($this->data['Search']['actionID']))
			{
				$cond_search["UserLogs.actionID"]					=	$this->data['Search']['actionID'];
			}
			
			if(!empty($this->data['Search']['point_from']) && empty($this->data['Search']['point_to']))
			{
				$cond_search["PointsHistory.value >= "]		=	$this->data['Search']['point_from'];
			}
			
			if(empty($this->data['Search']['point_from']) && !empty($this->data['Search']['point_to']))
			{
				$cond_search["PointsHistory.value <= "]		=	$this->data['Search']['point_to'];
			}
			
			if(!empty($this->data['Search']['point_from']) && !empty($this->data['Search']['point_to']))
			{
				$point_from	=	$this->data['Search']['point_from'];
				$point_to		=	$this->data['Search']['point_to'];
				if($this->data['Search']['point_to'] < $this->data['Search']['point_from'])
				{
					$point_to		=	$this->data['Search']['point_from'];
					$point_from	=	$this->data['Search']['point_to'];
				}
				$cond_search["PointsHistory.value BETWEEN ? AND ?"]		=	array($point_from,$point_to);
			}
			
			if(!empty($this->data['Search']['actionText']))
			{
				$cond_search["UserLogs.actionText LIKE "]				=	"%".$this->data['Search']['actionText']."%";
			}
			$this->Session->write("CondSearch.UserLogs",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('CondSearch.UserLogs');
			unset($this->data);
		}
		
		$cond_search		=	array();
		$filter_paginate	=	(preg_match('/^([0-9]+)$/',$user_id)) ? array('UserLogs.user_id' => $user_id) : array();
		$this->paginate		=	array(
			'UserLogs'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'fields'	=>	$fields
			)
		);
		
		$ses_cond			=	$this->Session->read("CondSearch.UserLogs");
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		$data				=	$this->paginate('UserLogs',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['UserLogs']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['UserLogs']['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
		$this->set('user_id',$user_id);
	}
}
?>
>>>>>>> 64a6180cb8f481c5e8b296da6476d814c36f43ba
