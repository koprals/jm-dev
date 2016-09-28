<?php
class EditProductController extends AppController
{
	var $name	=	"EditProduct";
	var $uses	=	array('Product');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}

	function Index($ID)
	{
		$this->Session->write('back_url',$this->settings['site_url'].'EditProduct/Index/'.$ID);
		$this->set("active_code","manage_products");
		
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
		$this->loadModel('ProductEditLog');
		$error	=	"";
		
		//CHECK AKSES
		$data	=	$this->Product->find('first',array(
						'conditions'	=>	array(
							'Product.id'					=>	$ID,
							'Product.user_id'				=>	$this->user_id,
							'Product.productstatus_id != '	=>	-10,
							'Product.productstatus_user'	=>	1
						)
					));
		
		if($data==false)
		{
			$error	=	"Maaf anda tidak memiliki akses untuk membuka halaman ini.";
		}
		elseif($data['Product']['sold']=="1")
		{
			$error	=	"Maaf produk yang telah terjual, tidak dapat lagi di edit.";	
		}
		
		$this->set('error',$error);
		$this->set('data',$data);
		$this->set('product_id',$ID);
		
		//DELETE FOLDER TMP
		$ROOT			=	$this->settings['path_content'];
		$tmp			=	$ROOT."TmpProduct/";
		$tmp_id			=	$tmp.$this->user_id."/";
		if(is_dir($tmp_id)) $this->General->RmDir($tmp_id);
		
		//DEFINE PHOTO
		$this->loadModel('ProductImage');
		$img = $this->ProductImage->GetImages($ID);
        $this->set("img", $img);
		
		
		//DEFINE PHONE
		$this->loadModel('ExtendedPhone');
		$profilephone	=	$this->ExtendedPhone->GetAllPhone($this->profile);
		foreach(explode(",",$data['Product']['phone']) as $key=>$val)
		{
			$productphone["$val "]	=	$val;
			$test[]					=	trim($val);
		}
		$phone_			=	array_merge($profilephone,$productphone);
		foreach($phone_ as $k=>$v)
		{
			$phone[trim($k)]	=	$v;
		}
		$this->set('phone',$phone);
		$this->set('productphone',$test);
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DEFINE CATEGORY
		$this->loadModel('Category');
        $category 	=	$this->Category->DisplayCategoryEdit($data['Parent']['id']);
        $this->set("category", $category);
		
		//DEFINE CONDITIONS
		$this->loadModel('Product');
        $condition = $this->Product->DisplayCondition();
        $this->set("condition", $condition);
		
		//DEFINE COLOR
		$color = $this->Product->DisplayColor();
		if(!in_array($data['Product']['color'],$color))
		{
			$color	=	array_merge(array($data['Product']['color']=>$data['Product']['color']),$color);
		}
        $this->set("color", $color);
		
		//DEFINE STNK
		$this->loadModel('Stnk');
		$stnk = $this->Stnk->DisplayStnk();
        $this->set("stnk", $stnk);
		
		//DEFINE BPKB
		$this->loadModel('Bpkb');
		$bpkb = $this->Bpkb->DisplayBpkb();
        $this->set("bpkb", $bpkb);
		
		//GET USER POINT
		$this->loadModel("PointsHistory");
		$POINT	=	$this->PointsHistory->find("first",array(
						"conditions"	=>	array(
							"PointsHistory.user_id"	=>	$this->user_id
						),
						"order"	=>	array(
							"PointsHistory.id DESC"
						)
					));
		$user_point	=	(!empty($POINT)) ? $POINT["PointsHistory"]["points_after"] : 0;
		
		
		//DEFINE ADS TYPES
		$this->loadModel("AdsType");
		$Ads	=	$this->AdsType->find("all",array(
						"conditions"	=>	array(
							"AdsType.status"	=>	"1"
						),
						"order"	=>	array(
							"AdsType.id ASC"
						)
						
					));
					
		$this->loadModel("AdsRequest");
		foreach($Ads as $k => $v)
		{
			$AdsRequest	=	$this->AdsRequest->find("first",array(
				"conditions"	=>	array(
					"AdsRequest.product_id"		=>	$ID,
					"AdsRequest.ads_type_id"	=>	$v["AdsType"]["id"]
				),
				"order"	=>	array("AdsRequest.id DESC")
			));
			$Ads[$k]["AdsType"]["disabled"]	=	"false";
			if(!empty($AdsRequest))
			{
				if($AdsRequest["AdsRequest"]["status"] == "0" or ($AdsRequest["AdsRequest"]["status"] == "1" && strtotime($AdsRequest["AdsRequest"]["end_date"]) > time()))
				{
					$Ads[$k]["AdsType"]["disabled"]	=	"true";
				}
			}
		}
		$this->set(compact("user_point","Ads"));
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
	
	
	function PrcessEdit()
	{
		$this->loadModel('Product');
		$this->layout					=	"json";
		$out							=	array("status"=>false,"error"=>array());
		App::import('Sanitize');
		
		if(!empty($this->data))
		{
			$this->data['Product']['description']	=	trim($this->data['Product']['description']);
			
			//GET USER POINT
			$this->loadModel("PointsHistory");
			$POINT	=	$this->PointsHistory->find("first",array(
							"conditions"	=>	array(
								"PointsHistory.user_id"	=>	$this->user_id
							),
							"order"	=>	array(
								"PointsHistory.id DESC"
							)
						));
			$user_point	=	(!empty($POINT)) ? intval($POINT["PointsHistory"]["points_after"]) : 0;
			
			//GET TOTAL REQUEST POINT
			$request_point	=	0;
			if(!empty($this->data["AdsType"]["id"]))
			{
				$AdsRequest	=	$this->data["AdsType"]["id"];
				$this->loadModel("AdsType");
				foreach($AdsRequest as $k => $AdsId)
				{
					$Ads	=	$this->AdsType->find("first",array(
									"conditions"	=>	array(
										"AdsType.id"	=>	$AdsId
									),
									"fields"	=>	array(
										"AdsType.point"
									)
									
								));
					$request_point	+=	$Ads["AdsType"]["point"];
				}
			}
			
			$this->data["Product"]["user_point"]	=	$user_point;
			$this->data["Product"]["request_point"]	=	$request_point;
			
			$this->Product->set($this->data);
			$this->Product->InitiateValidate($this->profile);
			
			
			//DEFINE VARIABLE
			$is_request									=	false;
			$trans 										=	array(' ' => '', '.' => '', ',' => '');

			$cat_id										=	$this->data['Product']['cat_id'];
			$category_name								=	$this->data['Product']["category_name"];
			$newcategory								=	$this->data['Product']["newcategory"];
			
			$subcategory_name							=	$this->data['Product']["subcategory_name"];
			$subcategory								=	$this->data['Product']["newsubcategory"];
			$this->data['Product']['data_type']			=	$data_type	=	$this->data['Product']['contact_name'];
			
			
			if($data_type==1)
			{
				if(!empty($this->profile['Profile']['lat']))
				{
					$this->data['Product']['lat']		=	$this->profile['Profile']['lat'];
					$this->data['Product']['lng']		=	$this->profile['Profile']['lng'];
				}
				$this->data['Product']['address']		=	$this->profile['Profile']['address'];
				$this->data['Product']['province_id']	=	$this->profile['Profile']['province_id'];
				$this->data['Product']['city_id']		=	$this->profile['Profile']['city_id'];
			}
			else
			{
				if(!empty($this->profile['Company']['lat']))
				{
					$this->data['Product']['lat']		=	$this->profile['Company']['lat'];
					$this->data['Product']['lng']		=	$this->profile['Company']['lng'];
				}
				$this->data['Product']['address']		=	$this->profile['Company']['address'];
				$this->data['Product']['province_id']	=	$this->profile['Company']['province_id'];
				$this->data['Product']['city_id']		=	$this->profile['Company']['city_id'];
			}
			
			if(!empty($this->profile['Profile']['ym']))
			{
				$this->data['Product']['ym']		=	$this->profile['Profile']['ym'];
			}
			
			if($this->Product->validates())
			{
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'EditProduct/Thanks');
				
				/*=======START SAVE===========*/
				
				//LOAD MODEL CATEGORY
				$this->loadModel('Category');
				
				//DEFINE CATEGORY
				$parent_cat	=	"";
				if($category_name==1)
				{
					$cat_id			=	$this->Category->GetCatId($newcategory,$this->Category->FindTop(),0);
					$parent_cat		=	$cat_id;
					$is_request		= 	true;
					$act_name		= 	"request_product";
				}
				
				//DEFINE SUBCATEGORY
				$sub_cat	=	"";
				if($subcategory_name==1)
				{
					$cat_id			=	$this->Category->GetCatId($subcategory,$cat_id,0);
					$sub_cat		=	$cat_id;
					$is_request		=  true;
					$act_name		=  "request_product";
				}
				
				$product_id									=	$this->data['Product']['id'];
				
				$this->data['Product']['contact_name']		=	($this->data['Product']['contact_name']==1) ? $this->profile['Profile']['fullname'] : $this->profile['Company']['name'];
				$this->data['Product']['phone']				=	implode(",",$this->data['Product']['phone']);
				$this->data['Product']['description']		=	Sanitize::html($this->data['Product']['description']);
			
				$this->data['Product']['category_id'] 		=	($is_request ==  false) ?  $this->data['Product']['subcategory'] : $cat_id;
				$this->data['Product']['seo_name']			=	$this->Category->GetSeoName($this->data['Product']['category_id']);
				$this->data['Product']['user_id']	  		=	$this->user_id;
				
				$this->data['Product']['price']				=	strtr($this->data['Product']['price'], $trans);
				$this->data['Product']['first_credit']		=	($this->data['Product']['is_credit']==1) ? strtr($this->data['Product']['first_credit'], $trans) : NULL;
				
				$this->data['Product']['credit_per_month']	=	($this->data['Product']['is_credit']==1) ? strtr($this->data['Product']['credit_per_month'], $trans) : NULL;
				
				$this->data['Product']['credit_interval']	=	($this->data['Product']['is_credit']==1) ? strtr($this->data['Product']['credit_interval'], $trans) : NULL;
				$this->data['Product']['productstatus_id']	=	-2;
				$this->data['Product']['nopol']				=	($this->data['Product']['condition_id']==1) ? -1 : strtoupper($this->data['Product']['nopol']);
				$this->data['Product']['kilometer']			=	($this->data['Product']['condition_id']==1) ? 0 : $this->data['Product']['kilometer'];
				$this->data['Product']['stnk_id']			=	($this->data['Product']['condition_id']==1) ? -1 : $this->data['Product']['stnk_id'];
				$this->data['Product']['bpkb_id']			=	($this->data['Product']['condition_id']==1) ? -1 : $this->data['Product']['bpkb_id'];
				$this->data['Product']['created']			=	date("Y-m-d H:i:s");
				$this->data['Product']['ym']				=	strtolower($this->data['Product']['ym']);
				$this->data['Product']['modified_by']		=	"Owner(".$this->data['Product']['contact_name'].")";
				
				
				/* ==============================SAVE TO EDIT LOG================================== */
				$this->loadModel('ProductEditLog');
				$save_log	=	$this->ProductEditLog->SaveLogEdit($this->data['Product']['id'],$this->data['Product']);
				
				/* ==============================SAVE TO EDIT LOG================================== */
				
				$this->Product->save($this->data,false);
				
				/*=======END SAVE===========*/
				
				//START SAVE FOTO
				$this->loadModel('ProductImage');
				$ROOT			=	$this->settings['path_content'];
				$path			=	$ROOT."ProductImage/";
				if(!is_dir($path)) mkdir($path,0777);
				
				
				//UPDATE PRIMARY
				$updt		=	$this->ProductImage->updateAll(
									array(
										'is_primary'	=>	"'0'"
									),
									array(
										'ProductImage.product_id'	=>	$product_id,
									)
								);
				
				
				foreach($this->data['Product']['filename'] as $k => $filename)
				{
					$is_primary		=	($k==intval($this->data['Product']['primary'])) ? "1" : "0";
					
					
					//IMG DETAIL
					$img		=	$this->ProductImage->find('first',array(
											'conditions'	=>	array(
												'ProductImage.product_id'	=>	$product_id,
												'ProductImage.number'		=>	$k
											)
										));
					$delPath	=	$path.$img['ProductImage']['id']."/";
					
					
					
					//DELETE IMG
					$delete		=	$this->data['Product']['delete'][$k];
					if($delete==1 && !empty($img['ProductImage']['id']))
					{
						$delPath	=	$path.$img['ProductImage']['id']."/";
						$this->General->RmDir($delPath);
						$this->ProductImage->delete($img['ProductImage']['id']);
					}
					
					//SAVE IMG
					$image_id	=	$img['ProductImage']['id'];
					
					if(!empty($filename) && !is_numeric($filename))
					{
						$info			=	pathinfo($filename);
						$this->ProductImage->create();
						$save			=	$this->ProductImage->saveAll(array(
												'id'			=>	$img['ProductImage']['id'],
												'product_id'	=>	$product_id,
												'status'		=>	0,
												'number'		=>	$k
											));
						
						$image_id	=	(is_null($img['ProductImage']['id'])) ? $this->ProductImage->getLastInsertId() : $img['ProductImage']['id'];
						$delPath	=	$path.$image_id."/";
						$this->General->RmDir($delPath);
						mkdir($delPath,0777);
						$targetFile		=	$delPath.$image_id.".".$info['extension'];
						$source			=	$ROOT."/TmpProduct/".$this->user_id."/".$filename;
						copy($source,$targetFile);
					}

					//UPDATE PRIMARY
					if(!is_null($image_id))
					{
						$updt		=	$this->ProductImage->updateAll(
											array(
												'is_primary'	=>	"'".$is_primary."'"
											),
											array(
												"ProductImage.id"	=>	$image_id
											)
										);
					}
				}
				//END SAVE FOTO
				
				//CLEAR CACHE HOME LATEST NEW
				$this->__clearCache();
				
				//SAVE ADS REQUEST
				$this->loadModel("AdsRequest");
				if(!empty($this->data["AdsType"]["id"]))
				{
					foreach($AdsRequest as $k => $AdsId)
					{
						$AdsRequest	=	$this->AdsRequest->find("first",array(
							"conditions"	=>	array(
								"AdsRequest.product_id"		=>	$ID,
								"AdsRequest.ads_type_id"	=>	$AdsId
							),
							"order"	=>	array("AdsRequest.id DESC")
						));
						
						if(empty($AdsRequest) or ($AdsRequest["AdsRequest"]["status"] == "1" && strtotime($AdsRequest["AdsRequest"]["end_date"]) < time()))
						{
							$AdsData["AdsRequest"]["product_id"]	=	$product_id;
							$AdsData["AdsRequest"]["ads_type_id"]	=	$AdsId;
							$this->AdsRequest->create();
							$this->AdsRequest->save($AdsData,array("validate"=>false));
						}
					}
				}
				
				/* ======================START SEND EMAIL TO SUPER ADMIN ========================== */
				App::import('Helper', 'Number');
				$Number 		= 	new NumberHelper();
				$detail_cat		=	$this->Category->GetCatAndSubcat($this->data['Product']['category_id']);
				$category		=	$detail_cat[0];
				$sub_category	=	$detail_cat[1];
				$contact		=	$this->data['Product']['contact_name'];
				$address		=	$this->data['Product']['address'];
				$price			=	$Number->format($this->data['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$date			=	date("d-m-Y H:i:s",strtotime($this->data['Product']['created']));
				$link			=	$this->settings['cms_url']."Product/Edit/".$product_id;
				
				$search 		=	array('[logo_url]','[category]', '[sub_category]', '[contact]','[address]','[price]','[date]','[link]','[site_name]','[site_url]');
				
				$replace 		=	array($this->settings['logo_url'],$category,$sub_category,$contact,$address,$price,$date,$link,$this->settings['site_name'],$this->settings['site_url']);
				
				$search_s 		=	array('[contact]','[category]','[sub_category]');
				$replace_s 		=	array($contact,$category,$sub_category);
				$this->Action->EmailSend('admin_alert_user_editproduct', $this->settings['admin_mail'], $search, $replace,$search_s,$replace_s,'Product',$product_id);
				/* ====================== END SEND EMAIL TO SUPER ADMIN ========================== */
				
			}//END IF VALIDATE
			else
			{
				$error	=	$this->Product->InvalidFields();
				foreach($this->data['Product'] as $k=>$v)
				{
					if(array_key_exists($k,$error))
					{
						$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);
					}
					elseif( empty($v) OR (is_array($v) AND isset($v["name"]) AND empty($v["name"])) )
					{
						$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
					}
					else
					{
						$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
					}
				}
				$out	=	array("status"=>false,"error"=>$err);
				
			}//END IF NOT VALIDATE
		}//END IFNOT EMPTY DATA
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	
	function Thanks()
	{
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
	
	function GetSubcategoryJson()
	{
		$this->layout					=	"json";
		$parent_id						=	$_GET['parent_id'];
		$category_id					=	$_GET['category_id'];
		
		$this->loadModel('Category');
		$find							=	$this->Category->DisplaySubCategoryEdit($parent_id,$category_id);
		$this->set("data",$find);
		$this->render(false);
	}
}
?>