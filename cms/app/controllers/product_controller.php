<?php
class ProductController extends AppController
{
	var $name		=	"Product";
	var $uses		=	array('Product');
	var $helpers	=	array('Time');
	var $components	=	array('Action','General');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('parent_code','product_catalog');
		$this->set('child_code','list_product');
		$this->layout	=	"new";
	}
	
	function __clearCache()
	{
		@unlink($this->settings['path_web'].'app/tmp/cache/views/element__home_latest_new');
		@unlink($this->settings['path_web'].'app/tmp/cache/views/element_yamaha_home_carousel');
		@unlink($this->settings['path_web'].'app/tmp/cache/views/element_honda_home_carousel');
		@unlink($this->settings['path_web'].'app/tmp/cache/views/element_suzuki_home_carousel');
		@unlink($this->settings['path_web'].'app/tmp/cache/views/element_kawasaki_home_carousel');
		$this->General->RmDir($this->settings['path_web'].'app/tmp/cache/pagination/product/');
		$this->General->RmDir($this->settings['path_web'].'app/tmp/cache/pagination/daftarharga/');
	}
	
	function GetSumData()
	{
		$this->layout	=	"json";
		$type			=	urldecode($_GET['type']);
		
		switch(strtolower($type))
		{
			case "daftar iklan";
				$conditions	=	array('Product.productstatus_id > ' => -10);
				break;
			case "approve";
				$conditions	=	array('Product.productstatus_id' => 1);
				break;
			case "editing required";
				$conditions	=	array('Product.productstatus_id' => -1);
				break;
			case "waiting approval";
				$conditions	=	array('Product.productstatus_id' => 0);
				break;
			case "waiting approval after editing";
				$conditions	=	array('Product.productstatus_id' => -2);
				break;
			case "sold";
				$conditions	=	array('Product.productstatus_id' => 1,'Product.sold' => 1);
				break;
			case "not sold";
				$conditions	=	array('Product.productstatus_id' => 1,'Product.sold' => 0);
				break;
			default : $conditions	=	array('Product.productstatus_id > ' => -10);
		}
		
		$sum	=	$this->Product->find('count',array(
						'conditions'	=>	$conditions
					));
		$this->set("data",(empty($sum)) ? "0" : $sum);
		$this->render(false);
	}
	
	function GetFullText($ID)
	{
		$this->layout	=	"json";
		$data			=	$this->Product->findById($ID);
		$this->set("data",$data['Product']['notice']);
		$this->render(false);
	}
	
	function GetTruncateText($ID)
	{
		//IMPORT HELPERS
		App::Import('Helper','Text');
		$text	=	new TextHelper();
		
		$this->layout	=	"json";
		$data			=	$this->Product->findById($ID);
		
		if(strlen($data['Product']['notice']) > 20 )
		{
			$text		=	$text->truncate($data['Product']['notice'],20,array('ending'=>"..<br>"));
		}
		else
		{
			$text		= nl2br($data['Product']['notice']);
		}
		$this->set("data",$text);
		$this->render(false);
	}
	
	function Index()
	{
		$this->Session->delete('SearchProduct');
		$this->Session->delete('Product.cond_search');
		
		//DEFINE CATEGORY
		$this->loadModel('Category');
        $category = $this->Category->DisplayCategory();
        $this->set("category", $category);
		
		//DEFINE CONDITIONS
        $condition = $this->Product->DisplayCondition();
        $this->set("condition", $condition);
		
		//DEFINE PRODUCT STATUS
		$this->loadModel("Productstatus");
		$product_statuses		=	$this->Productstatus->find("list",array("conditions"=>array('Productstatus.id >'=>-10),'order' =>	array('Productstatus.name ASC')));
		$this->set(compact("product_statuses"));
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
	}
	
	function Detail($ID)
	{
		$this->loadModel('Province');
		$this->loadModel('Stnk');
		$this->loadModel('Bpkb');
		$this->loadModel('Productstatus');
		
		App::import('Helper', 'Number');
		$Number 			= 	new NumberHelper();
		
		App::import('Helper', 'Time');
		$Time 				= 	new TimeHelper();
				
		$data				=	$this->Product->findById($ID);
		
		//DEFINE PHOTO
		$this->loadModel('ProductImage');
		$img = $this->ProductImage->GetImages($ID);
        $this->set("img", $img);
		
		$id					=	$data['Product']['id'];
		$user_id			=	$data['Product']['user_id'];
		$data_type			=	($data['Product']['data_type']==1) ? "Profile" : "Company";
		$contact_name		=	$data['Product']['contact_name'];
		$parent_name		=	$data['Parent']['name'];
		$category_name		=	$data['Category']['name'];
		$telp				=	$data['Product']['phone'];
		$ym					=	(!empty($data['Product']['ym'])) ? $data['Product']['ym'] : "-";
		$address			=	$data['Product']['address'];
		$province_name		=	$this->Province->GetNameProvince($data['Product']['province_id']);
		$city_name			=	$this->Province->GetNameCity($data['Product']['city_id']);
		$conditions			=	($data['Product']['condition_id']==2) ? "Baru" : "Bekas";
		$nopol				=	($data['Product']['nopol']==-1) ? "-" : $data['Product']['nopol'];
		$thn_pembuatan		=	$data['Product']['thn_pembuatan'];
		$color				=	$data['Product']['color'];
		$kilometer			=	$data['Product']['kilometer'];
		$description		=	(empty($data['Product']['description'])) ? "-" : $data['Product']['description'];
		$stnk				=	$this->Stnk->GetStatusStnk($data['Product']['stnk_id']);
		$bpkb				=	$this->Bpkb->GetStatusBpkb($data['Product']['bpkb_id']);
		$price				=	$Number->format($data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		
		$is_credit			=	($data['Product']['is_credit']==1) ? "Ya" : "Tidak";
		$first_credit		=	($data['Product']['is_credit']==1) ? $Number->format($data['Product']['first_credit'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) : "-";
		$credit_interval	=	($data['Product']['is_credit']==1) ? $Number->format($data['Product']['credit_interval'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) : "-";
		$credit_per_month	=	($data['Product']['is_credit']==1) ? $Number->format($data['Product']['credit_per_month'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) : "-";
		$status				=	$this->Productstatus->GetNameStatus($data['Product']['productstatus_id']);
		
		$created			=	date("d-M-Y",strtotime($data['Product']['created']))." (".$Time->timeAgoInWords($data['Product']['created']).")";
		$modified			=	date("d-M-Y",strtotime($data['Product']['modified']))." (".$Time->timeAgoInWords($data['Product']['modified']).")";
		$modified_by		=	(!empty($data['Product']['modified_by'])) ? $data['Product']['modified_by'] : "-";
		
		$approved			=	(!empty($data['Product']['approved'])) ? date("d-M-Y",strtotime($data['Product']['approved']))." ".$Time->timeAgoInWords($data['Product']['approved']) : "-";
		
		$approved_by		=	(!empty($data['Product']['approved_by'])) ? $data['Product']['approved_by'] : "-";
		$notice				=	(!empty($data['Product']['notice'])) ? $data['Product']['notice'] : "-";
		
		
		$this->set(compact("data","id","user_id","data_type","contact_name","parent_name","category_name","telp","ym","address","province_name","city_name","conditions","nopol","thn_pembuatan","color","kilometer","description","stnk","bpkb","price","is_credit","first_credit","credit_interval","credit_per_month","status","created","modified","modified_by","approved","approved_by","notice"));
		
		$this->layout = "ajax";
	}
	
	function MessageEditing()
	{
		$this->layout	=	"ajax";	
	}
	
	function MessageDeleted($product_id)
	{
		$this->layout	=	"ajax";
		$this->set(compact('product_id'));
	}
	
	function SelectType()
	{
		$this->layout	=	"ajax";
		$parent_id		=	$_POST['parent_id'];
		$category_id	=	$_POST['category_id'];
		
		//DISPLAY Category
		$this->loadModel('Category');
        $category = $this->Category->DisplaySubCategory($parent_id,$category_id);
        $this->set("category", $category);
		$this->set("selected", $category_id);
	}

	function Edit($product_id,$tab="list_product")
	{
		$data	=	$this->Product->findById($product_id);
		$error	=	"";
		$this->set('child_code',$tab);
		
		
		if($data==false)
		{
			$error	=	"Data tidak ditemukan.";
		}
		elseif($data['Product']['productstatus_user']==0)
		{
			$error	=	"Maaf data ini telah dihapus oleh pemasang iklan.";
		}
		
		//DEFINE BACK URL
		switch($tab)
		{
			case "list_product" :
				$url			=	"Product/Index";
				$breadcrumb		=	'Daftar Iklan';
				break;
			case "approve_product" :
				$url	=	"ApproveProduct/Index";
				$breadcrumb		=	'Approve';
				break;
			case "editing_required" :
				$url			=	"EditingRequiredProduct/Index";
				$breadcrumb		=	'Editing Required';
				break;
			case "waiting_approval" :
				$url			=	"WaitingApprovalProduct/Index";
				$breadcrumb		=	'Waiting Approval';
				break;
			case "after_editing" :
				$url			=	"AfterEditingProduct/Index";
				$breadcrumb		=	'Waiting Approval After Editing';
				break;
			case "sold_product" :
				$url			=	"SoldProduct/Index";
				$breadcrumb		=	'Sold';
				break;
			case "notsold_product" :
				$url			=	"NotSoldProduct/Index";
				$breadcrumb		=	'Not Sold';
				break;
			default:
				$url			=	"Product/Index";
				$breadcrumb		=	'DaftarIklan';
		}
		
		//DEFINE CONDITIONS
        $condition = $this->Product->DisplayCondition();
        $this->set("condition_id", $condition);
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province_id = $this->Province->DisplayProvince();
        $this->set("province_id", $province_id);
		
		//DISPLAY Category
		$this->loadModel('Category');
        $category_id = $this->Category->DisplayCategory($data['Parent']['id']);
        $this->set("category_id", $category_id);
		
		//DEFINE STNK
		$this->loadModel('Stnk');
		$stnk = $this->Stnk->DisplayStnk();
        $this->set("stnk", $stnk);
		
		//DEFINE BPKB
		$this->loadModel('Bpkb');
		$bpkb = $this->Bpkb->DisplayBpkb();
        $this->set("bpkb", $bpkb);
		
		//DEFINE PHOTO
		$this->loadModel('ProductImage');
		$img = $this->ProductImage->GetImages($product_id);
        $this->set("img", $img);
		
		//DEFINE STATUS
		$this->loadModel('Productstatus');
		$productstatus_id = $this->Productstatus->DisplayStatus();
        $this->set("productstatus_id", $productstatus_id);
		$this->set(compact("data","error","url","breadcrumb"));
	}
	
	function ProcessEdit()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"error"=>"");
		App::import('Sanitize');
		
		if(!empty($this->data))
		{
			$detail					=	$this->Product->findById($this->data['Product']['id']);
			$this->Product->set($this->data);
			$this->Product->InitiateValidate();
			$error					=	$this->Product->InvalidFields();
			$trans 					=	array(' ' => '', '.' => '', ',' => '');
			$subcategory_name		=	$this->data['Product']["subcategory_name"];
			$subcategory			=	$this->data['Product']["newsubcategory"];
			$parent_id				=	$this->data['Product']['category_id'];
			$category_id			=	$this->data['Product']['subcategory_id'];
			
			if(empty($error))
			{
				/* ==============================SAVE TO EDIT LOG================================== */
				$this->loadModel('ProductEditLog');
				App::Import('Sanitize');
				
				$notice		=	(!empty($this->data['Product']['notice'])) ? Sanitize::html($this->data['Product']['notice'], array('remove' => true)) : NULL;
				$save_log	=	$this->ProductEditLog->SaveLogEdit($this->data['Product']['id'],$notice,$this->profile['Profile']['fullname']);
				
				/* ==============================SAVE TO EDIT LOG================================== */
			
				//LOAD MODEL CATEGORY
				$this->loadModel('Category');
				$out					=	array("status"=>true,"error"=>"Data telah tersimpan.");
				if($subcategory_name==1)
				{
					$category_id		=	$this->Category->GetCatId($subcategory,$parent_id,1);
				}
				
				if($this->data['Product']['productstatus_id']==1)
				{
					//UPDATE CATEGORY
					$updt_category								=	$this->Category->updateAll(
																		array(
																			'Category.status'	=>	1
																		),
																		array(
																			'Category.id'		=>	array($parent_id,$category_id)
																		)
																	);
					
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_category_list');
					
				}
				
				//SAVE PRODUCT
				$this->data['Product']['approved'] 			=	($this->data['Product']['productstatus_id']==1) ? date("Y-m-d H:i:s") : NULL;
				$this->data['Product']['approved_by'] 		= 	($this->data['Product']['productstatus_id']==1) ? $this->profile['Profile']['fullname'] : NULL;
				
				$this->data['Product']['city_id']			=	$this->data['Product']['city'];
				$this->data['Product']['nopol']				=	($this->data['Product']['condition_id']==1) ? -1 : strtoupper($this->data['Product']['nopol']);
				$this->data['Product']['kilometer']			=	($this->data['Product']['condition_id']==1) ? 0 : $this->data['Product']['kilometer'];
				$this->data['Product']['stnk_id']			=	($this->data['Product']['condition_id']==1) ? -1 : $this->data['Product']['stnk_id'];
				$this->data['Product']['bpkb_id']			=	($this->data['Product']['condition_id']==1) ? -1 : $this->data['Product']['bpkb_id'];
				$this->data['Product']['price']				=	strtr($this->data['Product']['price'], $trans);
				$this->data['Product']['first_credit']		=	strtr($this->data['Product']['first_credit'], $trans);
				$this->data['Product']['credit_per_month']	=	strtr($this->data['Product']['credit_per_month'], $trans);
				$this->data['Product']['category_id']		=	$category_id;
				$this->data['Product']['description']		=	Sanitize::html($this->data['Product']['description']);
				
				$this->data['Product']['seo_name']			=	$this->Category->GetSeoName($category_id);
				$this->data['Product']['modified_by']		=	"Admin(".$this->profile['Profile']['fullname'].")";
				
				if($this->data['Product']['productstatus_id']==1)
				{
					$this->data['Product']['approved']			=	date("Y-m-d H:i:s");
					$this->data['Product']['approved_by']		=	$this->profile['Profile']['fullname'];
				}

				$save										=	$this->Product->save($this->data);
				
				//SAVE PRODUCT IMAGES
				if(!empty($this->data['Product']['imgapproved']))
				{
					$this->loadModel('ProductImage');
					//FIND IS PRIMARY FIRST
					$primary	=	$this->ProductImage->find('first',array(
										'conditions'	=>	array(
											"ProductImage.is_primary"	=>	1,
											"ProductImage.product_id"	=>	$this->data['Product']['id']
										)
									));
					
					$primary_id	=	$primary['ProductImage']['id'];
					
					$updt_img	=	$this->ProductImage->updateAll(
										array(
											'ProductImage.status'	=>	"'0'"
										),
										array(
											'ProductImage.product_id'		=>	$this->data['Product']['id']
										)
									);
					$updt_img	=	$this->ProductImage->updateAll(
										array(
											'ProductImage.status'	=>	"'1'"
										),
										array(
											'ProductImage.id'		=>	$this->data['Product']['imgapproved']
										)
									);
					
					if(!in_array($primary_id,$this->data['Product']['imgapproved']))
					{
						$updt_img	=	$this->ProductImage->updateAll(
											array(
												'ProductImage.is_primary'	=>	"'1'"
											),
											array(
												'ProductImage.id'		=>	reset($this->data['Product']['imgapproved'])
											)
										);
						
						$updt_img	=	$this->ProductImage->updateAll(
											array(
												'ProductImage.is_primary'	=>	"'0'"
											),
											array(
												'ProductImage.id'		=>	$primary_id
											)
										);
					}
				}
				
				//SEND MESSAGE TO USER
				$product_status		=	$this->data['Product']['productstatus_id'];
				if(!empty($this->data['Product']['pesan']) && $this->data['Product']['kirim_pesan']==1)
				{
					$arr_status	=	array(1=>"admin_product_approval",-1=>"admin_editing_required",-10=>"default",0=>"default");
					$html		=	str_replace("&quot;","",$this->data['Product']['pesan']);
					$send		=	$this->Action->EmailSend($arr_status[$product_status],$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'Product',$this->data['Product']['id'],$html);
				}
				
				//SAVE ADMIN ACTIONS
				$arr_email		=	array('-10'=>'admin_delete_product',0=>"admin_waiting_approval_product",1=>"admin_approve_product",-1=>"admin_editing_required_product");
				$user_editing	=	$this->data['Product']['id'];
				$text = $this->Action->generateHTML($arr_email[$this->data['Product']['productstatus_id']], array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_editing), array("Anda",$user_editing) );
				$this->Action->saveAdminLog($this->profile['User']['id']);
				
				//USER ADD POINT
				if(is_null($detail['Product']['approved']) && $this->data['Product']['productstatus_id'] == 1)
				{
					$text = $this->Action->generateHTML("user_add_product", array('[username]','[product_name]'), array($detail['Product']['contact_name'],$this->Category->GetCategoryName($category_id)), array("Anda",$this->Category->GetCategoryName($category_id)));
					$this->Action->save($detail['Product']['user_id']);
				}
				
				//SHARE TO VENDOR
				if($this->data['Product']['productstatus_id']==1)
				{
					if($detail['Product']['facebook_share']==1 or $detail['Product']['twitter_share']==1)
					{
						$detail				=	$this->Product->findById($this->data['Product']['id']);
						App::import('Helper', 'Number');
						$Number 			= 	new NumberHelper();
						$harga				=	$Number->format($detail['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
						$userID				=	$detail['Product']['user_id'];
						$linktext			=	"Lihat detilnya disini";
						$linkurl			=	$this->settings['site_url']."Iklan/Detail/".$detail['Product']['id']."/".$detail['Product']['seo_name'];
						$text_facebook		=	"Jual motor di ".$this->settings['site_name'].":\n ".$detail['Parent']['name']." ".$detail['Category']['name']."\n".$harga;
						$text_twitter		=	"Jual motor ".$detail['Parent']['name']." ".$detail['Category']['name']." ".$harga." ".$linkurl;
						$text_twitter		=	(strlen($text_twitter)>140) ? $linkurl : $text_twitter;
						
						if($detail['Product']['facebook_share']==1)
						{
							
							$this->loadModel('ProductImage');
							$img	=	$this->ProductImage->find('first',array(
											'conditions'	=>	array(
												'ProductImage.product_id'	=>	$detail['Product']['id'],
												'ProductImage.is_primary'	=>	1,
												'ProductImage.status'		=>	1
												
											)
										));
							$linkimg	=	"";
							if($img)
							{
								App::import('Vendor','img' ,array('file'=>'img.class.php'));
								$img 		= 	new img($img['ProductImage']['id'],"_facebook","ProductImage",300,300);
								$filename	=	$img->code;
								$CEK_PATH 	= 	$this->settings['path_content'].$img->content."/".$img->code."/".$filename;
								$img->create($CEK_PATH);
								$dest_folder	=	$this->settings['path_webroot']."img/facebook/".$img->code."/";
								$dest_file		=	$dest_folder.$img->code.".".$img->ext;
								$this->General->RmDir($dest_folder);
								mkdir($dest_folder,0777);
								if(!file_exists($dest_file)) copy($img->source,$dest_file);
								$linkimg		=	$this->settings['site_url']."img/facebook/".$img->code."/".$img->code.".".$img->ext;
							}
							//$this->Action->ShareFacebook($text_facebook,$userID,$linktext,$linkurl,$linkimg);
						}
					
						if($detail['Product']['twitter_share']==1)
						{
							//$this->Action->ShareTwitter($text_twitter,$userID,$linktext,$linkurl);
						}
					}
				}
				
				if($this->data['Product']['productstatus_id']==1)
				{
					//GET USER POINT
					$this->loadModel("PointsHistory");
					$POINT		=	$this->PointsHistory->find("first",array(
										"conditions"	=>	array(
											"PointsHistory.user_id"	=>	$detail['Product']['user_id']
										),
										"order"	=>	array(
											"PointsHistory.id DESC"
										)
									));
					$user_point	=	(!empty($POINT)) ? intval($POINT["PointsHistory"]["points_after"]) : 0;
					
					//FIND ADS REQUEST
					$this->loadModel("AdsRequest");
					$fAdsRequest	=	$this->AdsRequest->find("all",array(
											"conditions"	=>	array(
												"AdsRequest.product_id"	=>	$detail['Product']['id'],
												"AdsRequest.status"		=>	"0"
											),
											"order"	=>	array(
												"AdsRequest.id DESC"
											)
										));
					
					if(!empty($fAdsRequest))
					{
						foreach($fAdsRequest as $fAdsRequest)
						{
							if($user_point >= $fAdsRequest["AdsType"]["point"])
							{
								
								//UPDATE STATUS ADS REQUEST
								$start_date	=	date("Y-m-d")." 00:00:00";
								$end_date	=	date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")+$fAdsRequest["AdsType"]["days"],date("Y")));
								//UPDATE STATUS ADS REQUEST
								$update	=	$this->AdsRequest->updateAll(
												array(
													"status"		=>	"'1'",
													"start_date"	=>	"'".$start_date."'",
													"end_date"		=>	"'".$end_date."'"
												),
												array(
													"AdsRequest.id"	=>	$fAdsRequest["AdsRequest"]["id"]
												)
											);
											
								//UPDATE USER POINT
								$this->loadModel("User");
								$update_user_point	=	$this->User->updateAll(
															array(
																"points"	=>	"points - ".intval($fAdsRequest["AdsType"]["point"])
															),
															array(
																"User.id"	=>	$detail['Product']['user_id']
															)
														);
								
								$text = $this->Action->generateHTML("promo_jm_point", array('[username]','[ads_type]','[point]'), array($detail['Product']['contact_name'],$fAdsRequest["AdsRequest"]["ads_type_id"],$fAdsRequest["AdsType"]["point"]), array("Anda",$fAdsRequest["AdsRequest"]["ads_type_id"],$fAdsRequest["AdsType"]["point"]));
								
								$this->Action->save($detail['Product']['user_id'],array("userValue"=> -intval($fAdsRequest["AdsType"]["point"])));	
								$user_point -= $fAdsRequest["AdsType"]["point"];
							}
						}
					}
				}
				
				//CLEAR CACHE
				$this->__clearCache();
				@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$detail['Product']['id'].'_detail_product');
				@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$detail['Product']['id']);
			}
			else
			{
				foreach($this->data['Product'] as $k=>$v)
				{
					if(array_key_exists($k,$error))
					{
						$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);		
					}
					elseif(empty($v) OR (is_array($v) AND empty($v["name"])))
					{
						$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
					}
					else
					{
						$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
					}
				}
				$out	=	array("status"=>false,"error"=>$err);
			}
		}
		$this->set("data",$out);
		$this->render(false);
	}
	
	function CleanStyleAttribute($html)
	{
		$dom = new DOMDocument;                 // init new DOMDocument
		$dom->loadHTML($html);                  // load HTML into it
		$xpath = new DOMXPath($dom);            // create a new XPath
		$nodes = $xpath->query('//*[@style]');  // Find elements with a style attribute
		foreach($nodes as $node) {              // Iterate over found elements
			$node->removeAttribute('style');    // Remove style attribute
		}
		$html_fragment = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
		
		return $html_fragment;                  // output cleaned HTML
	}
	
	function ListItem()
	{
		$this->layout		=	"ajax";
		$viewpage			=	empty($this->params['named']['limit']) ? 20 : $this->params['named']['limit'];
		$order				=	array('Product.modified DESC');
		
		//DEFINE FIELDS
		$fields				=	array(
									'Product.*',
									'Category.*',
									'Parent.*',
									'Productstatus.id',
									'Productstatus.name',
									'Stnk.name',
									'Bpkb.name'
								);
		
		
		//DEFINE QUERY FOR KEYWORDS
		$keywords		=	$_POST['keywords'];
		
		if(!empty($keywords) && !empty($_POST['btn_keywords']))
		{
			$this->Session->delete('Product.cond_search');
			
			//SPLIT EACH WORDS/GENERATE SQL
			$split_stemmed	= split(" ",$keywords);
			while(list($key,$val)=each($split_stemmed)){
				if($val<>" "){
					$OR['OR'][]	= array('OR'	=> array(
											'Parent.name LIKE'		=> "%$val%",
											'Category.name LIKE'	=> "%$val%",
											'Product.nopol LIKE'	=> "%$val%"
										)
									);
				
				}
			}
			$OR['OR'][]			=	"MATCH (Parent.name, Category.name, Product.nopol) AGAINST ('*".$keywords."*' IN BOOLEAN MODE)";
			$cond_search		=	$OR;
			array_push($fields,"MATCH (Parent.name, Category.name, Product.nopol) AGAINST ('*".$keywords."*' IN BOOLEAN MODE) AS score");
			$this->Session->write("Product.cond_search",$cond_search);
			$order			=	array('score DESC','Product.created DESC');
		}
		
		//DEFINE QUERY FOR ADVANCE SEARCH
		//$cond_search	=	array();
		if(!empty($this->data))
		{
			$this->Session->delete('Product.cond_search');
			$trans 					=	array(' ' => '', '.' => '', ',' => '');
			$this->data['Search']['price_from']	=	strtr($this->data['Search']['price_from'], $trans);
			$this->data['Search']['price_to']	=	strtr($this->data['Search']['price_to'], $trans);
			
			if(!empty($this->data['Search']['id']))
			{
				$cond_search["Product.id"]					=	$this->data['Search']['id'];
			}
			if(!empty($this->data['Search']['parent_id']))
			{
				$cond_search['Parent.id']					=	$this->data['Search']['parent_id'];
			}
			if(!empty($this->data['Search']['category_id']))
			{
				$cond_search['Product.category_id']			=	$this->data['Search']['category_id'];
			}
			if(!empty($this->data['Search']['contact_name']))
			{
				$cond_search["Product.contact_name LIKE "]	=	"%".$this->data['Search']['contact_name']."%";
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
					
					$cond_search["Product.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",$date1)." 00:00:00",date("Y-m-d",$date2)." 23:59:59");
				}
				else
					$cond_search["Product.created BETWEEN ? AND ?"]		=	array(date("Y-m-d",strtotime($string[0]))." 00:00:00",date("Y-m-d",strtotime($string[0]))." 23:59:59");
				
			}
			
			if(!empty($this->data['Search']['data_type']))
			{
				$cond_search["Product.data_type"]			=	$this->data['Search']['data_type'];
			}
			
			if(!empty($this->data['Search']['status']))
			{
				$cond_search["Product.productstatus_id"]	=	$this->data['Search']['status'];
			}
			if(!empty($this->data['Search']['condition_id']))
			{
				$cond_search['Product.condition_id']		=	$this->data['Search']['condition_id'];
			}
			if(!empty($this->data['Search']['province_id']))
			{
				$cond_search['Product.province_id']			=	$this->data['Search']['province_id'];
			}
			if(!empty($this->data['Search']['city_id']))
			{
				$cond_search['Product.city_id']				=	$this->data['Search']['city_id'];
			}
			
			if(!empty($this->data['Search']['nopol']))
			{
				$cond_search["Product.nopol LIKE "]		=	"%".$this->data['Search']['nopol']."%";
			}
			
			if(!empty($this->data['Search']['thn_from']) && empty($this->data['Search']['thn_to']))
			{
				$cond_search["Product.thn_pembuatan >= "]		=	$this->data['Search']['thn_from'];
			}
			
			if(empty($this->data['Search']['thn_from']) && !empty($this->data['Search']['thn_to']))
			{
				$cond_search["Product.thn_pembuatan <= "]		=	$this->data['Search']['thn_to'];
			}
			
			if(!empty($this->data['Search']['thn_from']) && !empty($this->data['Search']['thn_to']))
			{
				$thn_from	=	$this->data['Search']['thn_from'];
				$thn_to		=	$this->data['Search']['thn_to'];
				if($this->data['Search']['thn_to'] < $this->data['Search']['thn_from'])
				{
					$thn_to		=	$this->data['Search']['thn_from'];
					$thn_from	=	$this->data['Search']['thn_to'];
				}
				$cond_search["Product.thn_pembuatan BETWEEN ? AND ?"]		=	array($thn_from,$thn_to);
			}
			if(!empty($this->data['Search']['price_from']) && empty($this->data['Search']['price_to']))
			{
				$cond_search["Product.price >= "]		=	$this->data['Search']['price_from'];
			}
			
			if(empty($this->data['Search']['price_from']) && !empty($this->data['Search']['price_to']))
			{
				$cond_search["Product.price <= "]		=	$this->data['Search']['price_to'];
			}
			
			if(!empty($this->data['Search']['price_from']) && !empty($this->data['Search']['price_to']))
			{
				$price_from		=	$this->data['Search']['price_from'];
				$price_to		=	$this->data['Search']['price_to'];
				if($this->data['Search']['price_to'] < $this->data['Search']['price_from'])
				{
					$price_to		=	$this->data['Search']['price_from'];
					$price_from		=	$this->data['Search']['price_to'];
				}
				$cond_search["Product.price BETWEEN ? AND ?"]	=	array($price_from,$price_to);
			}
			if(!empty($this->data['Search']['color']))
			{
				$cond_search["Product.color LIKE "]				=	"%".$this->data['Search']['color']."%";
			}
			$this->Session->write("Product.cond_search",$cond_search);
		}
		
		//DELETE SESSION
		if($_POST['reset']=="1")
		{
			$this->Session->delete('Product.cond_search');
			unset($this->data);
		}
		
		//DEFINE FILTERING
		$this->Product->bindModel(
                array('belongsTo' => array(
						'Stnk' => array(
							'className' 	=> 'Stnk',
							'foreignKey' 	=> 'stnk_id'
						),
						'Bpkb' => array(
							'className' 	=> 'Bpkb',
							'foreignKey' 	=> 'bpkb_id'
						)
			)), false
        );
		
		$cond_search		=	array();
		$filter_paginate	=	array('Product.productstatus_id > ' => -10);
		$this->paginate		=	array(
			'Product'	=>	array(
				'limit'		=>	$viewpage,
				'order'		=>	$order,
				'group'		=>	array('Product.id'),
				'fields'	=>	$fields
			)
		);
		
		$ses_cond			=	$this->Session->read("Product.cond_search");
		$cond_search		=	isset($ses_cond) ? $ses_cond : array();
		
		$data				=	$this->paginate('Product',array_merge($filter_paginate,$cond_search));
		
		if($this->params['named']['page'] > $this->params['paging']['Product']['pageCount'])
		{
			$this->params['named']['page']	=	$this->params['paging']['Product']['pageCount'];
		}
		$page				=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$this->set('data',$data);
		$this->set('page',$page);
		$this->set('viewpage',$viewpage);
	}
	
	function SelectAll($productstatus_id = -10,$sold=null,$ses_name="Product.cond_search")
	{
		$this->layout		=	"json";
		
		$filter_paginate	=	($productstatus_id == -10) ? array('Product.productstatus_id > ' => -10, 'Product.productstatus_user'=>1) : array('Product.productstatus_id' => $productstatus_id, 'Product.productstatus_user'=>1);
		
		if($sold==1)
		{
			$filter_paginate	=	array_merge($filter_paginate,array('Product.sold'=>"1"));
		}
		elseif($sold==0)
		{
			$filter_paginate	=	array_merge($filter_paginate,array('Product.sold'=>"0"));	
		}
		
		$ses_cond			= 	$this->Session->read($ses_name);
		$cond_search		= 	isset($ses_cond) ? $ses_cond : array();
		$data				= 	$this->Product->find('all',array(
									'conditions'	=>	array_merge($filter_paginate,$cond_search),
									'fields'		=>	array('Product.id'),
									'group'			=>	array('Product.id'),
								));
		
		$this->set("data",$data);
		$this->render(false);
	}
	
	
	function Delete()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terdelete :</b><br><br>";
		$data_delete		=	"<b>Data telah didelete :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $product_id)
		{
			$detail				=	$this->Product->findById($product_id);
			if($detail)
			{
				$delete				=	$this->Product->updateAll(
											array(
												'Product.productstatus_id'	=>	-10,
												'Product.modified'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.modified_by'		=>	"'".$this->profile['Profile']['fullname']."'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
					}
					
					
					if(!empty($_POST['msg_editing_required']))
					{
						//SEND EMAIL
						$html		=	$_POST['msg_editing_required'];
						$send		=	$this->Action->EmailSend("admin_product_deleted",$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'Product',$detail['Product']['id'],$html);
					}
					
					//CLEAR CACHE
					@unlink($this->settings['path_web'].'app/tmp/cache/detail_product_'.$product_id);
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_delete_product", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		
		//CLEAR CACHE
		$this->__clearCache();
		$this->render(false);
		
	}
	
	function DeleteMulti()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terdelete :</b><br><br>";
		$data_delete		=	"<b>Data telah didelete :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		$message			=	$_POST['msg_editing_required'];
		
		foreach($id as $product_id)
		{
			$detail				=	$this->Product->findById($product_id);
			if($detail)
			{
				$delete				=	$this->Product->updateAll(
											array(
												'Product.productstatus_id'	=>	-10,
												'Product.modified'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.modified_by'		=>	"'".$this->profile['Profile']['fullname']."'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
					}
					
					if(!empty($_POST['msg_editing_required']))
					{
						//SEND EMAIL
						$html		=	$this->GetHtmlDeleted($detail,$message);
						$send		=	$this->Action->EmailSend("admin_product_deleted",$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'Product',$detail['Product']['id'],$html);
					}
					
					//CLEAR CACHE
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_delete_product", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		
		//CLEAR CACHE
		$this->__clearCache();
		
		$this->render(false);
	}
	
	function ChangeToNotSold()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terupdate :</b><br><br>";
		$data_delete		=	"<b>Data telah diupdate :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $product_id)
		{
			$detail				=	$this->Product->findById($product_id);
			if($detail)
			{
				$delete				=	$this->Product->updateAll(
											array(
												'Product.sold'				=>	"'0'",
												'Product.modified'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.modified_by'		=>	"'".$this->profile['Profile']['fullname']."'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
					}
					
					//CLEAR CACHE
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		
		//CLEAR CACHE
		$this->__clearCache();
		
		$this->set("data",$msg);
		$this->render(false);	
	}
	
	function ChangeToSold()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terupdate :</b><br><br>";
		$data_delete		=	"<b>Data telah diupdate :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $product_id)
		{
			$detail				=	$this->Product->findById($product_id);
			if($detail)
			{
				$delete				=	$this->Product->updateAll(
											array(
												'Product.sold'				=>	"'1'",
												'Product.modified'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.modified_by'		=>	"'".$this->profile['Profile']['fullname']."'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
					}
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
				}
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		$this->set("data",$msg);
		
		//CLEAR CACHE
		$this->__clearCache();
		$this->render(false);	
	}
	
	function Approve()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"<b>Maaf terdapat beberapa data yang tidak terupdate :</b><br><br>";
		$data_delete		=	"<b>Data telah diupdate :</b><br><br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		$this->loadModel('Category');
		$this->loadModel('ProductImage');
		
		foreach($id as $product_id)
		{
			
			$detail				=	$this->Product->findById($product_id);
			$notice				=	"xxx";
			$message			=	"xxx";
			
			if($detail && !empty($notice) && !empty($message) && isset($this->profile))
			{
				$delete				=	$this->Product->updateAll(
											array(
												'Product.productstatus_id'	=>	1,
												'Product.approved'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.approved_by'		=>	"'".$this->profile['Profile']['fullname']."'",
												'Product.modified'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.modified_by'		=>	"'".$this->profile['Profile']['fullname']."'",
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				
				//UPDATE CATEGORY
				$updt_category		=	$this->Category->updateAll(
											array(
												'Category.status'	=>	1
											),
											array(
												'Category.id'		=>	array($detail['Parent']['id'],$detail['Category']['id'])
											)
										);
				
				//SAVE PRODUCT IMAGES
				$this->loadModel('ProductImage');
				$updt_img	=	$this->ProductImage->updateAll(
									array(
										'ProductImage.status'			=>	"'1'"
									),
									array(
										'ProductImage.product_id'		=>	$product_id
									)
								);
				
				//USER ADD POINT
				if(is_null($detail['Product']['approved']))
				{
					$text = $this->Action->generateHTML("user_add_product", array('[username]','[product_name]'), array($detail['Product']['contact_name'],$this->Category->GetCategoryName($detail['Category']['id'])), array("Anda",$this->Category->GetCategoryName($detail['Category']['id'])));
					$this->Action->save($detail['Product']['user_id']);
				}
				
				//SHARE TO VENDOR
				if($detail['Product']['facebook_share']==1 or $detail['Product']['twitter_share']==1)
				{
					$detail				=	$this->Product->findById($detail['Product']['id']);
					App::import('Helper', 'Number');
					$Number 			= 	new NumberHelper();
					$harga				=	$Number->format($detail['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
					$userID				=	$detail['Product']['user_id'];
					$linktext			=	"Lihat detilnya disini";
					$linkurl			=	$this->settings['site_url']."Iklan/Detail/".$detail['Product']['id']."/".$detail['Product']['seo_name'];
					$text_facebook		=	"Jual motor di ".$this->settings['site_name'].":\n ".$detail['Parent']['name']." ".$detail['Category']['name']."\n".$harga;
					$text_twitter		=	"Jual motor ".$detail['Parent']['name']." ".$detail['Category']['name']." ".$harga." ".$linkurl;
					$text_twitter		=	(strlen($text_twitter)>140) ? $linkurl : $text_twitter;
					
					if($detail['Product']['facebook_share']==1)
					{
						$img	=	$this->ProductImage->find('first',array(
										'conditions'	=>	array(
											'ProductImage.product_id'	=>	$detail['Product']['id'],
											'ProductImage.is_primary'	=>	1,
											'ProductImage.status'		=>	1
										)
									));
						
						$linkimg	=	"";
						if($img)
						{
							App::import('Vendor','img' ,array('file'=>'img.class.php'));
							$img 		= 	new img($img['ProductImage']['id'],"_facebook","ProductImage",300,300);
							$filename	=	$img->code;
							$CEK_PATH 	= 	$this->settings['path_content'].$img->content."/".$img->code."/".$filename;
							$img->create($CEK_PATH);
							$dest_folder	=	$this->settings['path_webroot']."img/facebook/".$img->code."/";
							$dest_file		=	$dest_folder.$img->code.".".$img->ext;
							$this->General->RmDir($dest_folder);
							mkdir($dest_folder,0777);
							if(!file_exists($dest_file)) copy($img->source,$dest_file);
							$linkimg		=	$this->settings['site_url']."img/facebook/".$img->code."/".$img->code.".".$img->ext;
						}
						$this->Action->ShareFacebook($text_facebook,$userID,$linktext,$linkurl,$linkimg);
					}
				
					if($detail['Product']['twitter_share']==1)
					{
						$this->Action->ShareTwitter($text_twitter,$userID,$linktext,$linkurl);
					}
				}
				
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
				}
				else
				{
					//SEND EMAIL TO USER
					$html		=	$this->GetHtmlApprove($detail);
					$send		=	$this->Action->EmailSend("admin_product_approval",$detail['User']['email'],$search=array('[link]','[link_edit]','[link_add]'),$replace=array($link,$link_edit,$link_add),$searchSub=array(),$replaceSub=array(),'Product',$detail['Product']['id'],$html);
					
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
					}
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
				}
			}
			else
			{
				$count++;
				$err++;
				$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>.......<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_approve_product", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}			
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		
		
		//CLEAR CACHE
		$this->__clearCache();
		
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function WaitingApproval()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"Maaf terdapat beberapa data yang tidak ter update :<br>";
		$data_delete		=	"Data telah di update :<br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		foreach($id as $product_id)
		{
			
			$detail				=	$this->Product->findById($product_id);
			$notice				=	"xxx";
			$message			=	"xxx";
			
			if($detail && !empty($notice) && !empty($message) && isset($this->profile))
			{
				$delete				=	$this->Product->updateAll(
											array(
												'Product.productstatus_id'	=>	0,
												'Product.modified'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.modified_by'		=>	"'".$this->profile['Profile']['fullname']."'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
				}
				else
				{
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
					}
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
				}
			}
			else
			{
				$count++;
				$err++;
				$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_waiting_approval_product", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted) );
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		
		//CLEAR CACHE
		$this->__clearCache();
		
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function EditingRequired()
	{
		$this->layout		=	"json";
		$id					=	explode(",",$_POST['selected_items']);
		$err				=	0;
		$count				=	0;
		$data_not_delete	=	"Maaf terdapat beberapa data yang tidak ter update :<br>";
		$data_delete		=	"Data telah di update :<br>";
		$item_delete		=	"";
		$item_notdelete		=	"";
		$tr_id				=	array();
		
		App::import('Sanitize');
		
		foreach($id as $product_id)
		{
			$notice				=	Sanitize::html($_POST['notice'], array('remove' => true));
			$detail				=	$this->Product->findById($product_id);
			$message			=	$_POST['msg_editing_required'];
			
			if($detail && !empty($notice) && !empty($message) && isset($this->profile))
			{
				/* ==============================SAVE TO EDIT LOG================================== */
				$this->loadModel('ProductEditLog');
				$save_log	=	$this->ProductEditLog->SaveLogEdit($product_id,$notice,"'Admin(".$this->profile['Profile']['fullname'].")'");
				
				/* ==============================SAVE TO EDIT LOG================================== */
					
				$delete				=	$this->Product->updateAll(
											array(
												'Product.productstatus_id'	=>	-1,
												'Product.modified'			=>	"'".date("Y-m-d H:i:s")."'",
												'Product.modified_by'		=>	"'Admin(".$this->profile['Profile']['fullname'].")'"
											),
											array(
												'Product.id'				=>	$product_id
											)
										);
				
				$count++;
				if($delete==false)
				{
					$err++;
					$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
				}
				else
				{
					//SEND EMAIL
					$html		=	$this->GetHtmlEditing($detail,$message);
					$send		=	$this->Action->EmailSend("admin_editing_required2",$detail['User']['email'],$search=array(),$replace=array(),$searchSub=array(),$replaceSub=array(),'Product',$detail['Product']['id'],$html);
					
					$tr_id[]		=	$product_id;
					if(count($tr_id)<7)
					{
						$item_delete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
					}
					@unlink($this->settings['path_web'].'app/tmp/cache/views/element_'.$product_id.'_detail_product');
					@unlink($this->settings['path_web'].'app/tmp/cache/cake_detail_product_'.$product_id);
				}
			}
			else
			{
				$count++;
				$err++;
				$item_notdelete	.= $count.". Product : ID-".$detail['Product']['id']."<br>";
			}
		}
		
		if(count($tr_id)>7)
		{
			$item_delete	.= "........<br>........<br>";
			$item_delete	.= $count.". Product : ID-".end($id);
		}
		
		if(!empty($tr_id))
		{
			//SAVE ADMIN ACTIONS
			$user_deleted	=	implode(",",$tr_id);
			$text = $this->Action->generateHTML("admin_editing_required_product", array('[adminname]','[data_deleted]'), array($this->profile['Profile']['fullname'],$user_deleted), array("Anda",$user_deleted));
			$this->Action->saveAdminLog($this->profile['User']['id']);
		}
		
		$message			=	($err > 0 ) ? $data_not_delete.$item_notdelete : $data_delete.$item_delete;
		$msg				=	array("messages"=>$message,"tr_id"	=>	$tr_id);
		
		//CLEAR CACHE
		$this->__clearCache();
		
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function GetHtmlApprove($product)
	{
		App::import('Helper', 'Number');
		$Number 		= 	new NumberHelper();
		$emailID		=	"admin_product_approval";
		$logo_url		=	$this->settings['logo_url'];
		$contact_name	=	$product['Product']['contact_name'];
		$category		=	$product['Parent']['name'];
		$sub_category	=	$product['Category']['name'];
		$contact		=	$product['Product']['contact_name'];
		$address		=	$product['Product']['address'];
		$price			=	$Number->format($product['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		$date			=	date("d-M-Y H:i:s",strtotime($product['Product']['created']));
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		$cms_url		=	$this->settings['cms_url'];
		$link			=	$site_url."Iklan/Detail/".$product['Product']['id']."/".$product['Product']['seo_name'];
		$link_edit		=	$site_url."EditProduct/Index/".$product['Product']['id'];
		$link_add		=	$site_url."Cpanel/AddProduct";
				
		$search			=	array('[logo_url]','[contact_name]','[category]','[sub_category]','[contact]','[address]','[price]','[date]','[site_name]','[site_url]','[cms_url]','[link]','[link_edit]','[link_add]');
		$replace		=	array($logo_url,$contact_name,$category,$sub_category,$contact,$address,$price,$date,$site_name,$site_url,$cms_url,$link,$link_edit,$link_add);
		
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	function GetHtmlEditing($product,$message)
	{
		App::import('Helper', 'Number');
		$Number 		= 	new NumberHelper();
		$emailID		=	"admin_editing_required2";
		$logo_url		=	$this->settings['logo_url'];
		$contact_name	=	$product['Product']['contact_name'];
		$category		=	$product['Parent']['name'];
		$sub_category	=	$product['Category']['name'];
		$contact		=	$product['Product']['contact_name'];
		$address		=	$product['Product']['address'];
		$price			=	$Number->format($product['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		$date			=	date("d-M-Y H:i:s",strtotime($product['Product']['created']));
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		$search			=	array('[logo_url]','[contact_name]','[category]','[sub_category]','[contact]','[address]','[price]','[date]','[site_name]','[site_url]','[message]');
		$replace		=	array($logo_url,$contact_name,$category,$sub_category,$contact,$address,$price,$date,$site_name,$site_url,$message);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}
	
	function GetHtmlDeleted($product,$message)
	{
		App::import('Helper', 'Number');
		$Number 		= 	new NumberHelper();
		$emailID		=	"admin_product_deleted2";
		$logo_url		=	$this->settings['logo_url'];
		$contact_name	=	$product['Product']['contact_name'];
		$category		=	$product['Parent']['name'];
		$sub_category	=	$product['Category']['name'];
		$contact		=	$product['Product']['contact_name'];
		$address		=	$product['Product']['address'];
		$price			=	$Number->format($product['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
		$date			=	date("d-M-Y H:i:s",strtotime($product['Product']['created']));
		$site_name		=	$this->settings['site_name'];
		$site_url		=	$this->settings['site_url'];
		$link			=	$this->settings['site_url'].'Product/Deactivated/'.$product['Product']['id'];
		$search			=	array('[logo_url]','[contact_name]','[category]','[sub_category]','[contact]','[address]','[price]','[date]','[site_name]','[site_url]','[message]','[link]');
		$replace		=	array($logo_url,$contact_name,$category,$sub_category,$contact,$address,$price,$date,$site_name,$site_url,$message,$link);
		$html			=	$this->Action->generateHTMLEMail($emailID,$search, $replace);
		return $html;
	}

	function GetSubcategoryJson()
	{
		$this->layout					=	"json";
		$parent_id						=	$_GET['parent_id'];
		$this->loadModel('Category');
		$find							=	$this->Category->find("all",array(
												'conditions'	=>	array(
													'Category.parent_id'	=>	$parent_id,
													'Category.status'		=>	1,
												)
											));
		$this->set("data",$find);
		$this->render(false);
	}
}
?>