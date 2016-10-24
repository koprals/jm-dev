<?php
class AddProductController extends AppController
{
	var $name	=	"AddProduct";
	var $uses	=	null;
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout	=	"cpanel";
	}
	function ZoomImages($filename)
	{
		$this->layout	=	"ajax";
		$this->set('filename',$filename);
	}
	function UploadTmp()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"msg"=>"","filename"=>"");
		$this->loadModel('Product');
		
		if(!empty($this->data))
		{
			$this->Product->set($this->data);
			$this->Product->ValidatePhoto();
			if($this->Product->validates())
			{
				//DEFINE NEEDED VARIABLE
				$ROOT			=	$this->settings['path_content'];
				$arr			=	$this->data['Product']["arr"];
				$info 			= 	pathinfo($this->data['Product']["photo"]['name']);
				$name			= 	strtolower($info['filename']."-$arr.".$info['extension']);
				
				//GENERATE FOLDER
				$tmp			=	$ROOT."TmpProduct/";
				if(!is_dir($tmp)) mkdir($tmp,0777);
				$tmp_id			=	$tmp.$this->user_id."/";
				if(!is_dir($tmp_id)) mkdir($tmp_id,0777);
				$targetFile		=	$tmp_id.strtolower($name);
				
				//UPLOAD FILES
				$tempFile 				= 	$this->data['Product']["photo"]['tmp_name'];
				$upload					=	move_uploaded_file($tempFile,$targetFile);
				
				$out					=	array("status"=>true,"msg"=>"","filename"=>$name,"name"=>strtolower($info['filename']."-$arr"));
			}
			else
			{
				$error					=	$this->General->getArrayFirstIndex($this->Product->InvalidFields());
				$out					=	array("status"=>false,"msg"=>$error,"filename"=>"");
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	function DeletePhoto()
	{
		$this->layout	=	"json";
		$out			=	array("status"=>false,"msg"=>"Login anda telah expired, harap login kembali.");
		$filename		=	$_GET['filename'];
		
		if(!empty($filename))
		{
			//DEFINE NEEDED VARIABLE
			$cnt		=	$this->settings['path_content']."TmpProduct/".$this->user_id."/".$filename;
			$info		=	pathinfo($cnt);
			$cnt1		=	$this->settings['path_content']."TmpProduct/".$this->user_id."/".$info['filename']."_prevthumb.".$info['extension'];
			
			if(is_file($cnt))
			{
				$delete	=	unlink($cnt);
				$delete	=	unlink($cnt1);
			}
			$out			=	array("status"=>false,"msg"=>"File tidak ditemukan.");
			if($delete)
			{
				$out			=	array("status"=>true,"msg"=>"File telah di hapus.");
			}
			
		}
		else
		{
			$out			=	array("status"=>false,"msg"=>"File tidak ditemukan.");
		}
		$this->set("data",$out);
		$this->render(false);
	}
	
	function Index()
	{
		
		$this->Session->write('back_url',$this->settings['site_url'].'AddProduct/Index');
		$this->set("active_code","add_product");
		
		if(empty($this->user_id))
		{
			$this->redirect(array("controller"=>"Users","action"=>"Login"));	
		}
		
		//DELETE FOLDER TMP
		$ROOT			=	$this->settings['path_content'];
		$tmp			=	$ROOT."TmpProduct/";
		$tmp_id			=	$tmp.$this->user_id."/";
		if(is_dir($tmp_id)) $this->General->RmDir($tmp_id);
				
		//DEFINE PHONE
		$this->loadModel('ExtendedPhone');
		$phone	=	$this->ExtendedPhone->GetAllPhone($this->profile);
		$this->set('phone',$phone);
		
		//DISPLAY PROVINCE
		$this->loadModel('Province');
        $province = $this->Province->DisplayProvince();
        $this->set("province", $province);
		
		//DEFINE CATEGORY
		$this->loadModel('Category');
        $category = $this->Category->DisplayCategory();
        $this->set("category", $category);
		
		//DEFINE CONDITIONS
		$this->loadModel('Product');
        $condition = $this->Product->DisplayCondition();
        $this->set("condition", $condition);
		
		//DEFINE COLOR
		$color = $this->Product->DisplayColor();
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
					
		$this->set(compact("user_point","Ads"));
	}
	
	function PrcessAdd()
	{
		App::import('Sanitize');
		
		$this->loadModel('Product');
		$this->layout					=	"json";
		$out							=	array("status"=>false,"error"=>"");
		
		if(empty($this->user_id))
		{
			$out				=	array("status"=>true,"error"=>$this->settings['site_url'].'Users/Login');
			$this->set("data",$out);
			$this->render(false);
			return;
		}
		
		if(!empty($this->data))
		{
			$this->data['Product']['nopol']	=	str_replace(" ","",$this->data['Product']['nopol']);
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
			$is_request				=	false;
			$trans 					=	array(' ' => '', '.' => '', ',' => '');

			$cat_id					=	$this->data['Product']['cat_id'];
			$category_name			=	$this->data['Product']["category_name"];
			$newcategory			=	$this->data['Product']["newcategory"];
			
			$subcategory_name		=	$this->data['Product']["subcategory_name"];
			$subcategory			=	$this->data['Product']["newsubcategory"];
			
			
			if($this->Product->validates())
			{
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Cpanel/AddProduct/Thanks');
				
				/*=======START SAVE===========*/
				
				//LOAD MODEL CATEGORY
				$this->loadModel('Category');
				
				//DEFINE CATEGORY
				$parent_cat	=	"";
				if($category_name==1)
				{
					$cat_id			=	$this->Category->GetCatId($newcategory,$this->Category->FindTop(),0);
					$parent_cat		=	$cat_id;
					$is_request		=  true;
					$act_name		=  "request_product";
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
				
				
				$this->data['Product']['description']		=	(!empty($this->data['Product']['description'])) ? Sanitize::html($this->data['Product']['description']) : "";
				$this->data['Product']['category_id'] 		=	($is_request ==  false) ?  $this->data['Product']['subcategory'] : $cat_id;
				$this->data['Product']['seo_name']			=	$this->Category->GetSeoName($this->data['Product']['category_id']);
				
				$this->data['Product']['user_id']	  		=	$this->user_id;
				$this->data['Product']['data_type']			=	$data_type	=	$this->data['Product']['contact_name'];
				$this->data['Product']['contact_name']		=	($this->data['Product']['contact_name']==1) ? $this->profile['Profile']['fullname'] : $this->profile['Company']['name'];
				
				$this->data['Product']['phone']				=	implode(",",$this->data['Product']['phone']);
				$this->data['Product']['city_id']			=	$this->data['Product']['city'];
				$this->data['Product']['price']				=	strtr($this->data['Product']['price'], $trans);
				$this->data['Product']['first_credit']		=	strtr($this->data['Product']['first_credit'], $trans);
				$this->data['Product']['credit_per_month']	=	strtr($this->data['Product']['credit_per_month'], $trans);
				$this->data['Product']['productstatus_id']	=	0;
				$this->data['Product']['nopol']				=	($this->data['Product']['condition_id']==1) ? -1 : strtoupper(Sanitize::html($this->data['Product']['nopol']));
				$this->data['Product']['kilometer']			=	($this->data['Product']['condition_id']==1) ? 0 : (int)$this->data['Product']['kilometer'];
				$this->data['Product']['stnk_id']			=	($this->data['Product']['condition_id']==1) ? -1 : (int)$this->data['Product']['stnk_id'];
				$this->data['Product']['bpkb_id']			=	($this->data['Product']['condition_id']==1) ? -1 : (int)$this->data['Product']['bpkb_id'];
				$this->data['Product']['created']			=	date("Y-m-d H:i:s");
				$this->data['Product']['modified_by']		=	"Owner";
				
				$this->data['Product']['ym']				=	strtolower($this->data['Product']['ym']);
				$this->data['Product']['modified_by']		=	"Owner(".$this->data['Product']['contact_name'].")";
				
				if($data_type==1)
				{
					if(!empty($this->profile['Profile']['lat']))
					{
						$this->data['Product']['lat']		=	$this->profile['Profile']['lat'];
						$this->data['Product']['lng']		=	$this->profile['Profile']['lng'];
					}
				}
				else
				{
					if(!empty($this->profile['Company']['lat']))
					{
						$this->data['Product']['lat']		=	$this->profile['Company']['lat'];
						$this->data['Product']['lng']		=	$this->profile['Company']['lng'];
					}
				}
				
				$this->Product->create();
				$this->Product->save($this->data,false);
				$product_id	=	$this->Product->getLastInsertId();
				
				//UPDATE CATEGORY
				$this->Category->updateAll(
					array(
						'product_id'	=>	$product_id
					),
					array(
						'Category.id'	=>	array($parent_cat,$sub_cat)
					)
				);
				
				// START UPDATE PROFILE
				if($data_type==1)
				{
					$this->loadModel('Profile');
					if(empty($this->profile['Profile']['address']))
					{					
						$profile_update	=	$this->Profile->updateAll(
												array(
													'address'		=>	"'".$this->data['Product']['address']."'",
													'province_id'	=>	"'".$this->data['Product']['province_id']."'",
													'city_id'		=>	"'".$this->data['Product']['city']."'"
												),
												array(
													'Profile.id'	=>	$this->profile['Profile']['id']
												)
											);
					}
					
					if(!empty($this->data['Product']['ym']) && empty($this->profile['Profile']['ym']))
					{
						$profile_update	=	$this->Profile->updateAll(
												array(
													'ym'			=>	"'".$this->data['Product']['ym']."'"
												),
												array(
													'Profile.id'	=>	$this->profile['Profile']['id']
												)
											);
					}
					
					if(empty($this->profile['Profile']['phone']))
					{
						$phone			=	explode(",",$this->data['Product']['phone']);
						$profile_update	=	$this->Profile->updateAll(
												array(
													'phone'			=>	"'".$phone[0]."'"
												),
												array(
													'Profile.id'	=>	$this->profile['Profile']['id']
												)
											);
					}
				}
				// END UPDATE PROFILE
				
				// START UPDATE COMPANY PROFILE
				elseif($data_type==2)
				{
					$this->loadModel('Company');
					if(empty($this->profile['Company']['address']))
					{
						
						$profile_update	=	$this->Company->updateAll(
												array(
													'address'		=>	"'".$this->data['Product']['address']."'",
													'province_id'	=>	"'".$this->data['Product']['province_id']."'",
													'city_id'		=>	"'".$this->data['Product']['city']."'"
												),
												array(
													'Company.id'	=>	$this->profile['Company']['id']
												)
											);
					}
					if(empty($this->profile['Company']['phone']))
					{
						$phone			=	explode(",",$this->data['Product']['phone']);
						$profile_update	=	$this->Company->updateAll(
												array(
													'phone'			=>	"'".$phone[0]."'"
												),
												array(
													'Company.id'	=>	$this->profile['Company']['id']
												)
											);
					}
				}
				// END UPDATE COMPANY PROFILE
				
				//SAVE FOTO
				$this->loadModel('ProductImage');
				$ROOT			=	$this->settings['path_content'];
				$path			=	$ROOT."ProductImage/";
				if(!is_dir($path)) mkdir($path,0777);

				foreach($this->data['Product']['filename'] as $k => $filename)
				{
					if(!empty($filename))
					{
						$info			=	pathinfo($filename);
						$is_primary		=	($k==intval($this->data['Product']['primary'])) ? "1" : "0";
						
						$this->ProductImage->create();
						$this->ProductImage->saveAll(array(
							'product_id'	=>	$product_id,
							'status'		=>	0,
							'is_primary'	=>	$is_primary,
							'number'		=>	$k
						));
						
						$image_id	=	$this->ProductImage->getLastInsertId();
						$tmp_id			=	$path.$image_id."/";
						if(!is_dir($tmp_id)) mkdir($tmp_id,0777);
						$targetFile		=	$tmp_id.$image_id.".".$info['extension'];
						$source			=	$ROOT."/TmpProduct/".$this->user_id."/".$filename;
						copy($source,$targetFile);
					}
				}
				$this->General->RmDir($ROOT."/TmpProduct/".$this->user_id."/");
				
				
				//SAVE ADS REQUEST
				$this->loadModel("AdsRequest");
				if(!empty($this->data["AdsType"]["id"]))
				{
					foreach($AdsRequest as $k => $AdsId)
					{
						$AdsData["AdsRequest"]["product_id"]	=	$product_id;
						$AdsData["AdsRequest"]["ads_type_id"]	=	$AdsId;
						$this->AdsRequest->create();
						$this->AdsRequest->save($AdsData,array("validate"=>false));
					}
				}
				
				//SEND EMAIL TO SUPER ADMIN
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
				
				$search_s 		=	array('[category]');
				$replace_s 		=	array($category."-".$sub_category);
				$this->Action->EmailSend('admin_alert_user_addproduct', $this->settings['admin_mail'], $search, $replace,$search_s,$replace_s,'Product',$product_id);
				/*=======END SAVE===========*/
			}
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
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
	
	
	function Thanks()
	{
	}
	
	function CleanStyleAttribute($html)
	{
		$dom = new DOMDocument;                 // init new DOMDocument
		$dom->loadHTML($html);                 // load HTML into it
		libxml_use_internal_errors(false);

		$xpath = new DOMXPath($dom);            // create a new XPath
		$nodes = $xpath->query('//*[@style]');  // Find elements with a style attribute
		foreach($nodes as $node) {              // Iterate over found elements
			$node->removeAttribute('style');    // Remove style attribute
		}
		
		$html_fragment = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
		
		return $html_fragment;
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