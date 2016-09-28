<?php 
class IklanController extends AppController
{
	var $components 	=	array('Cookie');
	var $name			=	"Iklan";
	var $uses			=	null;
	var $helpers		=	array("Text","Number","General","Cache");
	
	function beforeFilter()
	{
		parent::beforeFilter();
		
	}
	
	function GetOther($parent_id=NULL,$group_id=NULL,$product_id=NULL)
	{
		$this->layout		=	"json";
		$status				=	false;
		$data				=	NULL;
		
		//GET PROVINCE
		$this->loadModel("ProvinceGroup");
		$this->loadModel("Province");
		$this->ProvinceGroup->findById($group_id);
		$prov_lists		=	$this->Province->find("list",array(
								"conditions"	=>	array(
									"Province.group_id"	=>	$group_id
								),
								"fields"	=>	array("Province.id")
							));
		
		//GET CATEGORY
		$this->loadModel("Category");
		$category_id	=	$this->Category->find("list",array(
								"conditions"	=>	array(
									"Category.parent_id"	=>	$parent_id
								),
								"fields"	=>	array("Category.id")
							));

		//GET PRODUCT
		$this->loadModel("Product");
		$this->Product->unbindModel(array(
			"belongsTo"	=>	array(
				'Productstatus',
				'User'
			)	
		));
		
		$joins	=	array(
							array(
								'table'			=>	'product_images',
								'type'			=>	'LEFT',
								'alias'			=>	'ProductImages',
								'conditions'	=>	array("Product.id	=	ProductImages.product_id AND ProductImages.is_primary='1' AND ProductImages.status='1'")
							)
						);
		
		$product		=	$this->Product->find("all",array(
								"conditions"	=>	array(
									"Product.city_id"				=>	$prov_lists,
									"Product.lat IS NOT NULL",
									"Product.lat !=	"				=>	0,
									"Product.lng IS NOT NULL",
									"Product.lng != "				=>	0,
									"Product.productstatus_id"		=>	1,
									"Product.productstatus_user"	=>	1
								),
								"joins"		=>	$joins,
								"fields"	=>	array("Product.id","Category.name","Parent.name","Product.lat","Product.lng","Product.price","Product.address","ProductImages.id"),
								"order"		=>	array("IF( Product.id = $product_id ,1,0) ASC,IF( Category.parent_id = $parent_id ,1,0) DESC")
							));
		
		$data	=	empty($product) ? NULL : $product;
		$status	=	empty($product) ? false : true;
		
		$msg	=	array("status"=>$status,"data"=>$data);
		$this->set("data",$msg);
		$this->render(false);
	}
	
	
	function Detail($product_id)
	{
		$this->Session->write('back_url',$this->settings['site_url']."Iklan/Detail/".$product_id);
		
		//LOAD MODEL PRODUCT
		
		if (($data = Cache::read('detail_product_'.$product_id)) === false)
		{
			$this->loadModel("Product");
			$this->Product->bindModel(
					array(
						'hasOne' => array(
							'ProductImage' => array(
								'className'		=> 'ProductImage',
								'foreignKey'	=> 'product_id',
								'conditions'	=> "ProductImage.is_primary = '1'"
							)
						),
						'belongsTo'	=>	array(
							'Province' => array(
								'className' 	=>	'Province',
								'foreignKey' 	=>	false,
								'conditions'	=>	'Product.city_id = Province.id'
							)
						)
					), false
			);
			$data				=	$this->Product->find('first',array(
										"conditions"	=>	array(
											"Product.id"				=>	$product_id,
											"Product.productstatus_id"		=>	"1",
											"Product.productstatus_user"	=>	"1",
										)
									));
			Cache::write('detail_product_'.$product_id, $data);
		}
		
		$condition			=	($data['Product']['condition_id']=="1") ? "Baru" : "Bekas";
		$kategori			=	$data['Parent']['name']." ".$data['Category']['name'];
		$tahun				=	$data['Product']['thn_pembuatan'];
		$province			=	$data['Province']['name'];
		$title_for_layout	=	"Jual {$kategori} Thn {$tahun} - {$province}";
		$site_description	=	"Jual {$kategori} Thn {$tahun} - {$province}";
		$site_keywords		=	implode(", ",explode(" ",$this->settings['site_name'].": Jual {$kategori} tahun {$tahun} kota {$province} hubungi: ".$data['Product']["contact_name"]." ".$data['Product']["phone"]." ".$data['Product']["address"]));
		$product_img_id		=	$data['ProductImage']['id'];
		
	

		if($data==false)
		{
			$this->render("not_found");
			return;
		}
		$this->set(compact("product_id","title_for_layout","site_description","site_keywords","product_img_id"));
	}
	
	function CacheDetail($product_id)
	{
		//LOAD MODEL PRODUCT
		$this->loadModel("Product");
		$this->Product->BindUnbind();
		$rand_id			=	$this->Action->GetRandomUser();

		//GENERATE BRADCRUMB
		$bread_crumb		=	$this->Product->GenerateBreadCrumb($product_id);
		
		//GET DATA
		$joins	=	array(
							array(
								'table'			=>	'companies',
								'type'			=>	'LEFT',
								'alias'			=>	'Company',
								'conditions'	=>	array("Product.user_id	=	Company.user_id")
							)
						);
		
		$data	=	$this->Product->find("first",array(
						"fields" => array(
							"Product.*",
							"Province.group_id",
							"Province.name",
							"Province.province",
							"Category.id",
							"Parent.id",
							"Parent.name",
							"Category.name",
							"User.email",
							"ProvinceGroup.name",
							"Company.id",
							"Product.user_id"
						),
						"conditions"	=>	array(
							"Product.id"				=>	$product_id,
							"Product.productstatus_id"		=>	"1",
							"Product.productstatus_user"	=>	"1",
						),
						"joins"			=>	$joins,
						"group"			=>	array("Product.id")
					));
		
		if($data==false)
		{
			$this->render("not_found");
			return;
		}
		
		///SAVE VIEW LOG
		$this->loadModel("ViewLog");
		$check	=	$this->ViewLog->find("first",array(
						"conditions"	=>	array(
							"ViewLog.rand_id"		=>	$rand_id,
							"ViewLog.product_id"	=>	$product_id
						)
					));
		
		
		if($check==false)
		{
			$this->loadModel("RandomUser");
			$this->RandomUser->unbindModel( array('belongsTo' => array('User','Profile')) );
			$random_detail	=	$this->RandomUser->findByRandId($rand_id);
			$save			=	$this->ViewLog->save(
									array(
										"rand_id"		=>	$rand_id,
										"product_id"	=>	$product_id,
										"user_id"		=>	$random_detail['RandomUser']['user_id']
									),false
								);
			
			$update_view	=	$this->Product->updateAll(
									array(
										"Product.view"	=> "Product.view + 1"
									),
									array(
										"Product.id"	=> $product_id
									)
								);
		}
		
		//GET IMAGES
		$this->loadModel("ProductImage");
		$images	=	$this->ProductImage->find("all",array(
						"fields" => array(
							"ProductImage.id"
						),
						"conditions"	=>	array(
							"ProductImage.product_id"	=>	$product_id
						),
						"order"	=>	array("ProductImage.is_primary DESC, ProductImage.number ASC")
					));
		$img_id	=	$images[0]["ProductImage"]["id"];
		if(!is_null($data["Product"]["stnk_id"]))
		{
			//DEFINE STNK
			$this->loadModel('Stnk');
			$stnk = $this->Stnk->findById($data["Product"]["stnk_id"]);
		}
		
		if(!is_null($data["Product"]["bpkb_id"]))
		{
			//DEFINE STNK
			$this->loadModel('Bpkb');
			$bpkb = $this->Bpkb->findById($data["Product"]["bpkb_id"]);
		}
		
		
		//BBCODES
		App::import('Vendor','Decoda' ,array('file'=>'decoda/Decoda.php'));
		$code 				= 	new Decoda();
		$code->addFilter(new DefaultFilter());
		$code->addFilter(new TextFilter());
		$code->addFilter(new UrlFilter());
		$code->addFilter(new ListFilter());
		$code->addFilter(new ImageFilter());
		$code->addHook(new EmoticonHook());
		
		$code->reset($data["Product"]["description"]);
		
		$data["Product"]["description"]		=	$code->parse();
		
		$this->set(compact("bread_crumb","data","images","stnk","bpkb","img_id"));
	}
	
	function AddComment($product_id=null)
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		App::import('Sanitize');
		$err				=	array();

		if(!empty($this->data))
		{
			//SAVE COMMENT
			$this->loadModel('Comment');
			$this->loadModel('Product');
			$this->data['Comment']['name']			=	(!empty($this->user_id)) ? $this->profile["Profile"]["fullname"] : Sanitize::html($this->data['Comment']['name']);
			$this->data['Comment']['email']			=	(!empty($this->user_id)) ? $this->profile["User"]["email"] : Sanitize::html($this->data['Comment']['email']);
			$this->data['Comment']['comment']		=	Sanitize::html($this->data['Comment']['comment']);
			$this->data['Comment']['product_id']	=	Sanitize::html($product_id);
			$rand_id								=	$this->Action->GetRandomUser();
			$this->data['Comment']['rand_id']		=	$rand_id;
			
			
			$this->Comment->set($this->data);
			
			if($this->Comment->validates())
			{
				$this->Comment->create();
				$save			=	$this->Comment->save($this->data);
				$comment_id		=	$this->Comment->getLastInsertId();
				
				//UPDATE COMMENT
				$update_comment	=	$this->Product->updateAll(
										array(
											"Product.comment"	=> "Product.comment + 1"
										),
										array(
											"Product.id"	=> $product_id
										)
									);
				
				//SEND EMAIL
				//PRODUCT DETAIL
				$this->loadModel('Product');
				$this->Product->unbindModel(array("belongsTo"	=>	array("Productstatus")));
				$detail	=	$this->Product->find("first",array(
								"conditions"	=>	array(
									"Product.id"	=>	$product_id
								),
								"fields"			=>	array("Product.contact_name","Category.name","Parent.name","Product.address","Product.price","Product.created","User.email")
							));
				
				
				if($this->user_id !== $detail["Product"]["user_id"])
				{
					App::import('Helper', 'Number');
					$logo_url		=	$this->settings['logo_url'];
					$site_url		=	$this->settings['site_url'];
					$site_name		=	$this->settings['site_name'];
					$contact_name	=	$detail['Product']['contact_name'];
					$contact_name	=	$detail['Product']['contact_name'];
					$sender_name	=	$this->data['Comment']['name'];
					$comment		=	$this->data['Comment']['comment'];
					$category		=	$detail['Parent']['name'];
					$sub_category	=	$detail['Category']['name'];
					$address		=	$detail['Product']['address'];
					$Number 		= 	new NumberHelper();
					$price			=	$Number->format($detail['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
					$date			=	date("d-m-Y",strtotime($detail['Product']['created']));
					
					$link 			=	$this->settings['site_url'] . "Iklan/Detail/" . $product_id;
					
					$s_search		=	array("[sender_name]");
					$s_replace		=	array($sender_name);
					$search 		=	array('[logo_url]','[site_url]','[site_name]','[contact_name]','[sender_name]','[comment]','[category]','[sub_category]','[address]','[price]','[date]','[link]');
					$replace 		=	array($logo_url,$site_url,$site_name,$contact_name,$sender_name,$comment,$category,$sub_category,$address,$price,$date,$link);
					
					$this->Action->EmailSend('comment', $detail['User']['email'], $search, $replace,$s_search,$s_replace,"Comment",$comment_id);
				}
				
				$out		=	array("status"=>true,"error"=>"");
			}
			else
			{
				$error	=	$this->Comment->InvalidFields();
				foreach($this->data['Comment'] as $k=>$v)
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
	
	function SumComment($product_id)
	{
		$this->layout		=	"json";
		$this->loadModel("Comment");
		$total				=	$this->Comment->find("count",array(
									"conditions"	=>	array(
										"Comment.product_id"	=>	$product_id
									)
								));
		$this->set("data",$total);
		$this->render(false);
	}
	
	function ListComment($product_id)
	{
		$this->layout	=	"ajax";
		$this->loadModel("Comment");
		$this->paginate	=	array(
			'Comment'	=>	array(
				'limit'			=>	5,
				'order'			=>	array("Comment.id DESC"),
				"conditions"	=>	array("Comment.product_id"	=>	$product_id)
			)
		);
		$page	=	empty($this->params['named']['page']) ? 1 : $this->params['named']['page'];
		$data	=	$this->paginate('Comment');
		$this->set(compact('data','page','product_id'));
	}
	
	function SendMessage($product_id)
	{
		//LOAD MODEL PRODUCT
		$this->loadModel("Product");
		$this->Product->unbindModel(
			array(
				"belongsTo"	=>	array(
					"Productstatus"
				)
			)
		);
		$joins	=	array(
							array(
								'table'			=>	'companies',
								'type'			=>	'LEFT',
								'alias'			=>	'Company',
								'conditions'	=>	array("Product.user_id	=	Company.user_id")
							)
						);
		
		$data	=	$this->Product->find('first',array(
						"conditions"	=>	array(
							"Product.productstatus_id"		=>	1,
							"Product.productstatus_user"	=>	1,
							"Product.id"					=>	$product_id,
						),
						"fields"	=>	array(
							"Product.data_type",
							"Product.user_id",
							"Company.id as company_id",
							"Product.contact_name",
							"Parent.name",
							"Category.name",
							"Product.thn_pembuatan",
							"User.email",
							"Product.id",
							"Product.sold",
						),
						"joins"	=>	$joins
					));
		
		if($data)
		{
			$model		=	($data["Product"]["data_type"]==1) ? "User" : "Company";
			$model_id	=	($data["Product"]["data_type"]==1) ? $data["Product"]["user_id"] : $data[0]["company_id"];
			$this->set(compact("model","model_id","data"));
		}
	}
	
	function ProcessSendPm()
	{
		
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		$err				=	array();
		
		if(!empty($this->data))
		{
			$product_id		=	$this->data["Product"]["product_id"];
			
			$this->loadModel("Product");
			$this->Product->unbindModel(
				array(
					"belongsTo"	=>	array(
						"Productstatus"
					)
				)
			);
			
			$detail	=	$this->Product->find("first",array(
								"conditions"	=>	array(
									"Product.id"	=>	$product_id
								),
								"fields"			=>	array("Product.contact_name","Category.name","Parent.name","Product.address","Product.price","Product.created","User.email")
							));
				
				
			$this->data["Product"]["to"]	=	$detail["User"]["email"];
			$this->Product->ValidateSendPm();
			$this->Product->set($this->data);
			
			if($this->Product->validates())
			{
				App::import('Helper', 'Number');
				$logo_url		=	$this->settings['logo_url'];
				$site_url		=	$this->settings['site_url'];
				$site_name		=	$this->settings['site_name'];
				$contact_name	=	$detail['Product']['contact_name'];
				$sender_name	=	$this->data['Product']['from'];
				$comment		=	$this->data['Product']['message'];
				$category		=	$detail['Parent']['name'];
				$sub_category	=	$detail['Category']['name'];
				$address		=	$detail['Product']['address'];
				$sender_mail	=	$this->data['Product']['email'];
				$no_telp		=	(!empty($this->data['Product']['telp'])) ? " atau melalui no telp: ".$this->data['Product']['telp'] : "";
				
				$Number 		= 	new NumberHelper();
				$price			=	$Number->format($detail['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$date			=	date("d-m-Y",strtotime($detail['Product']['created']));
				
				$link 			=	$this->settings['site_url'] . "Iklan/Detail/" . $product_id;
				
				$s_search		=	array("[subject]");
				$s_replace		=	array($this->data['Product']['subject']);
				$search 		=	array('[logo_url]','[site_url]','[site_name]','[contact_name]','[sender_name]','[comment]','[category]','[sub_category]','[address]','[price]','[date]','[link]','[sender_mail]','[no_telp]');
                $replace 		=	array($logo_url,$site_url,$site_name,$contact_name,$sender_name,$comment,$category,$sub_category,$address,$price,$date,$link,$sender_mail,$no_telp);
				
				$this->Action->EmailSend('pm', $detail['User']['email'], $search, $replace,$s_search,$s_replace,"Product",$product_id);
				
				$out		=	array("status"=>true,"error"=>$this->settings['site_url'].'Iklan/Detail/'. $product_id);
				
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
	
	function SimulasiKredit($product_id=NULL)
	{
		$tenor	=	array(
						1	=>	"1 Tahun",
						2	=>	"2 Tahun",
						3	=>	"3 Tahun",
						4	=>	"4 Tahun"
					);
		
		$this->set(compact("tenor"));
		
		//DEFINE PRODUCT MODEL
		$this->loadModel("Product");
		$this->Product->unbindModel(
			array(
				"belongsTo"	=>	array(
					"Productstatus"
				)
			)
		);
			
		if(!is_null($product_id))
		{
			$joins	=	array(
							array(
								'table'			=>	'product_images',
								'type'			=>	'LEFT',
								'alias'			=>	'ProductImages',
								'conditions'	=>	array("Product.id	=	ProductImages.product_id AND ProductImages.is_primary='1' AND ProductImages.status='1'")
							)
						);
			
			$find	=	$this->Product->find("first",array(
							"conditions"	=>	array(
								"Product.id"	=>	$product_id
							),
							"fields"		=>	array(
								"Product.id",
								"ProductImages.id",
								"Product.contact_name",
								"Parent.name",
								"Category.name",
								"Product.thn_pembuatan",
								"Product.price"
							),
							"joins"			=>	$joins
						));
			
			if($find)
			{
				if(empty($this->data))
				{
					$this->data["Product"]["harga"]			= number_format($find["Product"]["price"],0,'','');
				}
				$this->set("data",$find);
			}
		}//END IF !IS_NULL($product_id)
		
		if(!empty($_GET['tenor'])) $this->data["Product"]["tenor"]=$_GET['tenor'];
		$this->data["Product"]["administrasi"]	=	empty($this->data["Product"]["administrasi"]) ? 100000 : $this->data["Product"]["administrasi"];
		$this->data["Product"]["dppersen"]		=	empty($this->data["Product"]["dppersen"]) ? 20 : $this->data["Product"]["dppersen"];
		$this->data["Product"]["bunga"]			=	empty($this->data["Product"]["bunga"]) ? 6 : $this->data["Product"]["bunga"];
					
	
		$this->Product->set($this->data);
		$this->Product->ValidateSimulasi();
		$this->Product->validates();
		
		$title_for_layout	=	$this->settings['site_name'].": Simulasi kredit motor baru dan bekas";
		$site_description	=	$title_for_layout;
		$site_keywords		=	implode(", ",explode(" ",$title_for_layout));
		$this->set(compact("title_for_layout","site_description","site_keywords"));
	}
	
	
	function TestBunga()
	{
		App::import('Helper', 'Number');
		$Number 				= 	new NumberHelper();
				
				
		$jmlhbulan		=	36;
		$bln_sblm[0]	=	isset($_GET["pokok"]) ? $_GET["pokok"] : 5000000;
		$hasil			=	"";
		$bunga			=	0.1;
		
		for($i=1;$i<=$jmlhbulan;$i++)
		{
			$bln_sblm[$i]	=	($bunga * $bln_sblm[$i-1]) + $bln_sblm[$i-1];
			echo "Bulan - {$i} = (".$Number->format($bln_sblm[$i-1],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))." * ".$bunga.") + ".$Number->format($bln_sblm[$i-1],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))." = ".$Number->format($bln_sblm[$i],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))."<br>";
			
		}
		$this->autoRender	=	false;
		
	}
	
	
	function ProcessSimulasi($product_id=NULL)
	{
		$this->layout		=	"json";
		$out				=	array("status"=>false,"error"=>"");
		$err				=	array();
		
		//DEFINE PRODUCT MODEL
		$this->loadModel("Product");
		$this->Product->unbindModel(
			array(
				"belongsTo"	=>	array(
					"Productstatus"
				)
			)
		);
		
		if(!empty($this->data))
		{
			$this->Product->set($this->data);
			$this->Product->ValidateSimulasi();
			if($this->Product->validates())
			{
				App::import('Helper', 'Number');
				$Number 				= 	new NumberHelper();
				$harga					=	$this->data["Product"]["harga"];
				$hasil_harga			=	$Number->format($harga,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$hasil_dppersen			=	$this->data["Product"]["dppersen"];
				$dppersen				=	$this->data["Product"]["harga"]*($this->data["Product"]["dppersen"]/100);
				$hasil_dp				=	$Number->format($dppersen,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				
				$pokok_hutang			=	$this->data["Product"]["harga"]-($this->data["Product"]["harga"]*($this->data["Product"]["dppersen"]/100));
				$hasil_pokok_hutang		=	$Number->format($pokok_hutang,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				
				$hasil_bunga			=	$this->data["Product"]["bunga"];
				$tenor					=	$this->data["Product"]["tenor"];
				$hasil_jangka_waktu		=	$tenor * 12;
				$bunga_kredit			=	($pokok_hutang * ($this->data["Product"]["bunga"]/100) * $tenor)/$hasil_jangka_waktu;
				$hasil_bunga_flat		=	$Number->format($bunga_kredit,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$angsuran_pokok			=	$pokok_hutang/$hasil_jangka_waktu;
				$hasil_angsuran_pokok	=	$Number->format($angsuran_pokok,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$angsuran_per_bulan		=	$angsuran_pokok	+ $bunga_kredit;
				$hasil_angsuran			=	$Number->format($angsuran_per_bulan,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				
				$administrasi			=	$this->data["Product"]["administrasi"];
				$hasil_administrasi		=	$Number->format($administrasi,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$asuransi				=	0.036*$harga;
				$hasil_asuransi			=	$Number->format($asuransi,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				$total					=	$dppersen + $hasil_angsuran + $administrasi + $asuransi;
				$hasil_total			=	$Number->format($total,array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null));
				
				$data		=	array(
									"hasil_harga"			=>	$hasil_harga,
									"hasil_dppersen"		=>	$hasil_dppersen,
									"hasil_dp"				=>	$hasil_dp,
									"hasil_pokok_hutang"	=>	$hasil_pokok_hutang,
									"hasil_bunga"			=>	$hasil_bunga,
									"hasil_jangka_waktu"	=>	$hasil_jangka_waktu,
									"hasil_bunga_flat"		=>	$hasil_bunga_flat,
									"hasil_angsuran"		=>	$hasil_angsuran,
									"hasil_angsuran_pokok"	=>	$hasil_angsuran_pokok,
									"hasil_administrasi"	=>	$hasil_administrasi,
									"hasil_asuransi"		=>	$hasil_asuransi,
									"hasil_total"			=>	$hasil_total
									
								);
				$out		=	array("status"=>true,"error"=>"","data"=>$data);
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
	
	function HasilTable($product_id=NULL)
	{
		$this->layout	=	"ajax";
		$harga			=	array();
		App::import('Helper', 'Number');
		$Number 				= 	new NumberHelper();
		
		
		for($i=1;$i<=4;$i++)
		{
			$v_harga	=	$this->data["Product"]["harga"];
			$v_dp		=	$this->data["Product"]["dppersen"]*0.01*$v_harga;
			$v_ph		=	$v_harga-$v_dp;
			$v_bunga	=	$this->data["Product"]["bunga"]*0.01;
			$v_ap		=	$v_ph/($i*12);
			$v_bf		=	($v_ph*$v_bunga)/12;
			$v_ab		=	($v_ph/($i*12)) + $v_bf;
			$v_ad		=	$this->data["Product"]["administrasi"];
			$v_as		=	3.6*0.01*$this->data["Product"]["harga"];
			$v_pa		=	$v_dp + $v_ab + $v_ad + $v_as;
			
			$harga[]	=	$Number->format($v_harga,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$dp[]		=	$Number->format($v_dp,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$ph[]		=	$Number->format($v_ph,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$ph[]		=	$Number->format($v_ph,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$ap[]		=	$Number->format($v_ap,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$bf[]		=	$Number->format($v_bf,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$ab[]		=	$Number->format($v_ab,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$ad[]		=	$Number->format($v_ad,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$as[]		=	$Number->format($v_as,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
			$pa[]		=	$Number->format($v_pa,array("thousands"=>".","before"=>null,"places"=>null,"after"=>null));
		}
		$data			=	array(
								"harga"	=>	$harga,
								"dp"	=>	$dp,
								"ph"	=>	$ph,
								"ap"	=>	$ap,
								"bf"	=>	$bf,
								"ab"	=>	$ab,
								"ad"	=>	$ad,
								"as"	=>	$as,
								"pa"	=>	$pa
							);
		$this->set(compact("data"));
	}
}
?>