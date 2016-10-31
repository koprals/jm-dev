<?php
class Product extends AppModel
{
	var $name		= 'Product';
	var $settings;
	var $profile;
	var $virtualFields	=	array(
								'Surl'				=>	'CONCAT("http://www.jualanmotor.com/Iklan/Detail/",Product.id,"/motor-dijual-",Product.seo_name,".html")',
								'Sprice'			=>	'CAST(CAST(Product.price AS UNSIGNED) AS SIGNED)',
								'SFirstCredit'		=>	'CAST(CAST(Product.first_credit AS UNSIGNED) AS SIGNED)',
								'SCreditInterval'	=>	'CAST(CAST(Product.credit_interval AS UNSIGNED) AS SIGNED)',
								'SCreditPerMonth'	=>	'CAST(CAST(Product.credit_per_month AS UNSIGNED) AS SIGNED)'
							);
	var $belongsTo 	= array(
		'Productstatus' => array(
			'className' 	=> 'Productstatus',
			'foreignKey' 	=> 'productstatus_id'
		),
		'Category' => array(
			'className' 	=> 'Category',
			'foreignKey' 	=> 'category_id'
		),
		'Parent' => array(
			'className' 	=>	'Category',
			'foreignKey' 	=>	false,
			'conditions'	=>	'Category.parent_id = Parent.id'
		),
		'User' => array(
			'className' 	=>	'User',
			'foreignKey' 	=>	'user_id'
		)
	);
	
	function DisplayCondition()
	{
		$condition	=	array("1"=>"Baru","2"=>"Bekas");
		return $condition;
	}
	
	function DisplayColor()
	{
		$color	=	array(
							"Merah"		=>"Merah",
							"Hitam"		=>"Hitam",
							"Silver"	=>"Silver",
							"Biru"		=>"Biru",
							"Kuning"	=>"Kuning",
							"Hijau"		=>"Hijau"
						);
		return $color;
	}

	function ValidatePhoto()
	{
		$Number 		=	new NumberHelper();
		
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}
		
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
			'photo' => array(
				'imageheight' => array(
					'rule' => array('imageheight',$settings['min_height_product']),
					'message' => 'Maaf ukuran panjang gambar anda terlalu kecil, minimal '.$settings['min_height_product'].' px.'	
				),
				'imagewidth' => array(
					'rule' => array('imagewidth',$settings['min_width_product']),
					'message' => 'Maaf ukuran lebar gambar anda terlalu kecil, minimal '.$settings['min_width_product'].' px.'	
				),
				'size' => array(
					'rule' => array('size',$settings['max_photo_upload']),
					'message' => 'File anda terlalu besar, maksimum ukuran photo adalah '.$Number->toReadableSize($settings['max_photo_upload']).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Hanya extension (*.gif,*.jpeg,*.jpg,*.png) yang di perbolehkan.'	
				)
			)
		);
	}
	
	
	function ValidateSimulasi()
	{
		$this->validate	=	array(
			'harga' => array(
				'postal' => array(
					'rule' => array("postal","/^([0-9]+)$/"),
					'message' => 'Berikan nilai angka tanpa spasi atau karakter apapun.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan harga motor.'	
				)
			),
			"dppersen" => array(
				'money' => array(
					'rule' => "numeric",
					'message' => 'Masukan dengan format yang benar, co: 20.4 atau 20.43 atau 20 .'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Berikan persentase DP anda.'	
				)
			),
			"bunga" => array(
				'numeric' => array(
					'rule' =>"numeric",
					'message' => 'Masukan dengan format yang benar, co: 20.4 atau 20.43 atau 20 .'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Berikan persentase bunga pertahun.'	
				)
			),
			"administrasi" => array(
				'postal' => array(
					'rule' 			=>	array("postal","/^([0-9]+)$/"),
					'message' 		=>	'Berikan nilai angka tanpa spasi atau karakter apapun.',
					"allowEmpty"	=>	true
				)
			)
		);
	}
	
	function ValidateSendPm()
	{
		$this->validate	=	array(
			'to' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Tujuan email tidak terdefinisi.'	
				),
			),
			'from' => array(
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Nama anda terlalu pendek, minimal jumlah karakter nama adalah 3 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Tuliskan nama anda.'	
				),
			),
			'email' => array(
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda tidak benar, co: abyfajar@jualanmotor.com.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
				),
			),
			'subject' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Tuliskan subjek pesan anda.'	
				),
			),
			'message' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Tuliskan pesan anda.'	
				),
			)
		);
	}
	
	function InitiateValidate($profile)
	{
		$Number 		=	new NumberHelper();
		
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}
		$settings		=	$settings['Setting'];
		$this->settings	=	$settings;
		$this->profile	=	$profile;
		
		
		$this->validate	=	array(
			'akses' => array(
				'akses' => array(
					'rule' => "akses",
					'message' => 'Maaf anda tidak memiliki akses untuk mengedit iklan ini'	
				)
			),
			'contact_name' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan masukkan/pilih nama penjual'	
				),
				'ContactName' => array(
					'rule' 		=> "ContactName",
					'message'	=> 'Maaf nama penjual anda tidak tersedia.'	
				)
			),
			'phone' => array(
				'notEmptyPhone' => array(
					'rule' => "notEmptyPhone",
					'message' => 'Silahkan pilih no telp penjual.'	
				),
				'Phone' => array(
					'rule' => array("Phone",$profile),
					'message' => 'No telp anda tidak tersedia.'	
				)
			),
			'ym' => array(
				'YahooMail' => array(
					'rule' => "YahooMail",
					'message' => 'Format email yahoo anda tidak benar.'	
				)
			),
			'address' => array(
				'maxLength' => array(
					'rule' => array('maxLength',$settings['max_address_char']),
					'message' => 'Maksimum jumlah karakter alamat adalah '.$settings['max_address_char'].' karakter.'
				),
				'minLength' => array(
					'rule' => array('minLength',10),
					'message' => 'Minimum jumlah karakter alamat adalah 10 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan alamat lengkap anda.'	
				)
			),
			'province_id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan pilih propinsi anda.'	
				)
			),
			'cat_id' => array(
				'NotEmptyCat'	=> array(
					'rule' => 'NotEmptyCat',
					'message' => 'Silahkan pilih merk motor anda.'
				)
			),
			'newcategory'	=> array(
				'UnixNewCat'	=> array(
					'rule' => 'UnixNewCat',
					'message' => 'Maaf merk motor anda telah tersedia pada pilihan default, <br>masukkan nama merk motor yang lain <br>atau klik tombol cancel di sebelah kanan kolom merk motor.'
				),
				'maxLengthNewCat' => array(
					'rule' => array('maxLengthNewCat',"category_request",15),
					'message' => 'Maksimum jumlah karakter merk motor adalah 15 karakter.'
				),
				'minLengthNewCat' => array(
					'rule' => array('minLengthNewCat',"category_request",3),
					'message' => 'Minimum jumlah karakter merk motor adalah 3 karakter.'
				),
				'NotEmptyNewCat'	=> array(
					'rule' => 'NotEmptyNewCat',
					'message' => 'Masukkan nama merk motor anda.'
				)
			),
			'subcategory' => array(
				'NotEmptySub'	=> array(
					'rule' => 'NotEmptySub',
					'message' => 'Silahkan pilih tipe motor anda.'
				)
			),
			'newsubcategory'	=> array(			 
				'UnixNewSubCat' => array(
					'rule' => "UnixNewSubCat",
					'message' => 'Maaf tipe motor anda telah tersedia pada pilihan default, <br>masukkan nama tipe motor yang lain <br>atau klik tombol cancel di sebelah kanan kolom tipe motor.'
				),
				'maxLengthNewCat' => array(
					'rule' => array('maxLengthNewCat',"subcategory_request",25),
					'message' => 'Maksimum jumlah karakter tipe motor adalah 25 karakter.'
				),
				'minLengthNewCat' => array(
					'rule' => array('minLengthNewCat',"subcategory_request",3),
					'message' => 'Minimum jumlah karakter tipe motor adalah 3 karakter.'
				),
				'NotEmptyNewSub'	=> array(
					'rule' => 'NotEmptyNewSub',
					'message' => 'Masukkan nama tipe motor anda.'
				)
			),
			'condition_id' => array(
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Silahkan pilih kondisi motor anda, baru/bekas ?.'
				)
			),
			'nopol' => array(
				'regexnopol'	=> array(

					'rule' => "regexnopol",
					'message' => 'Format nopol anda salah.',
					"allowEmpty"	=>	true
				),
				'maxLengthNopol' => array(
					'rule' => array('maxLengthNopol',9),
					'message' => 'Maksimum jumlah karakter nopol motor adalah 9 karakter.',
					"allowEmpty"	=>	true
				),
				'minLengthNopol' => array(
					'rule' => array('minLengthNopol',3),
					'message' => 'Minimum jumlah karakter nopol motor adalah 3 karakter.',
					"allowEmpty"	=>	true
				)
			),
			'thn_pembuatan' => array(
				'Feature'	=> array(
					'rule' => "Feature",
					'message' => 'Tahun tidak boleh melebihi tahun saat ini.'
				),
				'postal'	=> array(
					'rule' => array('postal',"/^(19|20)([0-9]{2})$/"),
					'message' => 'Format tahun anda salah.'
				),
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Masukkan tahun pembuatan motor anda.'
				)
			),
			'color' => array(
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Minimum jumlah karakter warna motor adalah 4 karakter.'
				),
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Pilih/masukkan warna motor anda.'
				)
			),
			'kilometer' => array(
				'postal'	=> array(
					'rule' => array('postal',"/^([0-9]+)$/"),
					'message' => 'Masukkan angka untuk kilometer, dan bernilai diatas 0.',
					'allowEmpty' => true
				)
			),
			'description' => array(
				'minLength' => array(
					'rule' => array('minLength',10),
					'message' => 'Minimum jumlah karakter keterangan adalah 10 karakter.',
					'allowEmpty' => true
				)
			),
			'price'	=>array(
				'money'	=> array(
					'rule' 		=> 'money',
					'message'	=> 'Masukkan format harga dengan benar co: 12.000.000.'
				),
				'notEmpty'	=> array(
					'rule' 		=> 'notEmpty',
					'message' 	=> 'Masukkan harga motor anda.'
				)
			),
			'first_credit'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 12.000.000.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan anngsuran pertama motor anda.'
				)
			),
			'credit_interval'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 10.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan jumlah anngsuran motor anda.'
				)
			),
			'credit_per_month'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 12.000.000.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan anngsuran perbulan motor anda.'
				)
			),
			'primary'	=>array(
				'notEmptyFile'	=> array(
					'rule' 		=> 'notEmptyFile',
					'message' 	=> 'Silahkan upload foto motor anda.'
				),
				'notEmptyPrimary'	=> array(
					'rule' 		=> 'notEmptyPrimary',
					'message' 	=> 'Pilih foto utama anda.'
				)
			),
			'request_point'	=>array(
				'CheckRequestPoint'	=> array(
					'rule' 		=> 'CheckRequestPoint',
					'message' 	=> 'Maaf jumlah point anda tidak mencukupi untuk melakukan Promo JmPoint, tingkatkan lagi JmPoint Anda.'
				)
			),
			'agree'	=>array(
				'notEmptyAgree'	=> array(
					'rule' 		=> 'notEmptyAgree',
					'message' 	=> 'Harap anda menyetujui perjanjian dan ketentuan yang berlaku.'
				)
			)
		);
		//$this->validate	=array();
	}
	
	function CheckRequestPoint($fields=array())
	{
		$user_point	=	$this->data[$this->name]["user_point"];
		foreach($fields as $k => $v)
		{
			if($v > $user_point)
			{
				return false;
			}
		}
		return true;
	}
	
	function InitiateValidateOdp()
	{
		$Number 		=	new NumberHelper();
		
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}
		$settings		=	$settings['Setting'];
		$this->settings	=	$settings;
		$this->profile	=	$profile;
		
		
		$this->validate	=	array(
			"user_id"		=>	array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Maaf session login anda telah habis, silahkan login kembali'	
				)
			),
			'contact_name' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan masukkan/pilih nama penjual'	
				)
			),
			'phone' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan masukkan no telp anda.'	
				)
			),
			'address' => array(
				'maxLength' => array(
					'rule' => array('maxLength',$settings['max_address_char']),
					'message' => 'Maksimum jumlah karakter alamat adalah '.$settings['max_address_char'].' karakter.'
				),
				'minLength' => array(
					'rule' => array('minLength',10),
					'message' => 'Minimum jumlah karakter alamat adalah 10 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan alamat lengkap anda.'	
				)
			),
			'province_id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan pilih propinsi anda.'	
				)
			),
			'category_id' => array(
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Silahkan pilih merk motor anda.'
				)
			),
			'condition_id' => array(
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Silahkan pilih kondisi motor anda, baru/bekas ?.'
				)
			),
			'nopol' => array(
				'regexnopol'	=> array(
					'rule' => "regexnopol",
					'message' => 'Format nopol anda salah.',
					"allowEmpty"	=>	true
				),
				'maxLengthNopol' => array(
					'rule' => array('maxLengthNopol',9),
					'message' => 'Maksimum jumlah karakter nopol motor adalah 9 karakter.',
					"allowEmpty"	=>	true
				),
				'minLengthNopol' => array(
					'rule' => array('minLengthNopol',3),
					'message' => 'Minimum jumlah karakter nopol motor adalah 3 karakter.',
					"allowEmpty"	=>	true
				)
			),
			'thn_pembuatan' => array(
				'Feature'	=> array(
					'rule' => "Feature",
					'message' => 'Tahun tidak boleh melebihi tahun saat ini.'
				),
				'postal'	=> array(
					'rule' => array('postal',"/^(19|20)([0-9]{2})$/"),
					'message' => 'Format tahun anda salah.'
				),
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Masukkan tahun pembuatan motor anda.'
				)
			),
			'color' => array(
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Minimum jumlah karakter warna motor adalah 4 karakter.'
				),
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Pilih/masukkan warna motor anda.'
				)
			),
			'kilometer' => array(
				'postal'	=> array(
					'rule' => array('postal',"/^([0-9]+)$/"),
					'message' => 'Masukkan angka untuk kilometer, dan bernilai diatas 0.',
					'allowEmpty' => true
				)
			),
			'description' => array(
				'minLength' => array(
					'rule' => array('minLength',10),
					'message' => 'Minimum jumlah karakter keterangan adalah 10 karakter.',
					'allowEmpty' => true
				)
			),
			'price'	=>array(
				'money'	=> array(
					'rule' 		=> 'money',
					'message'	=> 'Masukkan format harga dengan benar co: 12000000.'
				),
				'notEmpty'	=> array(
					'rule' 		=> 'notEmpty',
					'message' 	=> 'Masukkan harga motor anda.'
				)
			),
			'first_credit'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 12000000.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan anngsuran pertama motor anda.'
				)
			),
			'credit_interval'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 10.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan jumlah anngsuran motor anda.'
				)
			),
			'credit_per_month'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 12000000.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan anngsuran perbulan motor anda.'
				)
			),
			'image_str'	=>array(
				'notEmpty'	=> array(
					'rule' 		=> 'notEmpty',
					'message' 	=> 'Pilih foto utama anda.'
				)
			),
			'agree'	=>array(
				'notEmptyAgree'	=> array(
					'rule' 		=> 'notEmptyAgree',
					'message' 	=> 'Harap anda menyetujui perjanjian dan ketentuan yang berlaku.'
				)
			)
		);
	}

	
	
	function akses()
	{
		$product_id		=	$this->data['Product']['id'];
		if(!empty($product_id))
		{
			$PRODUCT		=	ClassRegistry::Init('Product');
			$data			=	$PRODUCT->find('first',array(
									'conditions'	=>	array(
										'Product.id'					=>	$product_id,
										'Product.user_id'				=>	$this->profile['User']['id'],
										'Product.productstatus_id != '	=>	-10,
										'Product.productstatus_user'	=>	1
									)
								));
			
			if($data==false)
			{
				return false;
			}
		}
		return true;
	}
	
	function notEmptyIsCredit($field=array())
	{
		$is_credit	=	$this->data[$this->name]["is_credit"];
		if($is_credit==1)
		{
			foreach( $field as $key => $value ){
				if(empty($value))
				{
					return false;
				}
			}
		}
        return TRUE;
	}
	
	function notEmptyAgree($field=array())
	{
		
		foreach( $field as $key => $value ){
			if(empty($value))
			{
				return false;
			}
		}
		
        return TRUE;
	}
	
	function notEmptyPrimary($field=array())
	{
		$filename	=	$this->data[$this->name]["filename"];
		$isset_file	=	0;
		$ROOT		=	$this->settings['path_content'];
		$IMG		=	ClassRegistry::Init('ProductImage');
		
		foreach($filename as $filename)
		{
			
			$tmp		=	$ROOT."TmpProduct/".$this->profile['User']['id']."/".$filename;
			if(!empty($filename))
			{
				if(!is_numeric($filename) and is_file($tmp))
				{
					$isset_file	=	1;
					break;
				}
				elseif(is_numeric($filename))
				{
					$data	=	$IMG->find('first',array(
									'conditions'	=>	array(
										'ProductImage.id'	=>	$filename
									)
								));
					if($data)
					{
						$isset_file	=	1;
						break;
					}
				}
			}
		}
		
		foreach( $field as $key => $value )
		{
			if(empty($value) && $isset_file==1)
			{
				return false;
			}
		}
		
        return TRUE;
	}
	
	function notEmptyFile($field=array())
	{
		$filename	=	$this->data[$this->name]["filename"];
		$isset_file	=	0;
		$ROOT		=	$this->settings['path_content'];
		$IMG		=	ClassRegistry::Init('ProductImage');
		
		foreach($filename as $filename)
		{
			$tmp		=	$ROOT."TmpProduct/".$this->profile['User']['id']."/".$filename;
			
			if(!empty($filename))
			{
				if(!is_numeric($filename) and is_file($tmp))
				{
					$isset_file	=	1;
					break;
				}
				elseif(is_numeric($filename))
				{
					$data	=	$IMG->find('first',array(
									'conditions'	=>	array(
										'ProductImage.id'	=>	$filename
									)
								));
					if($data)
					{
						$isset_file	=	1;
						break;
					}
				}
			}
		}
		
		if($isset_file	== 0)
		{
			return false;
		}
        return TRUE;
	}
	
	
	function Feature($field=array())
	{
		foreach( $field as $key => $value ){
			if($value>date("Y"))
			{
				return false;
			}
		}
        return TRUE;
	}
	
	function moneyIsCredit($field=array())
	{
		$is_credit	=	$this->data[$this->name]["is_credit"];
		if($is_credit==1)
		{
			foreach( $field as $key => $value ){
				if(!Validation::money($value))
				{
					return false;
				}
			}
		}
        return TRUE;
	}
	
	function notEmptyPhone($field=array())
	{
		foreach( $field as $key => $value )
		{
			if(empty($value) or (is_array($value) && empty($value[0])))
			{
				return false;
			}
        }
        return TRUE;
	}
	
	function NotEmptyCat($field=array())
	{
		$category_request	=	$this->data[$this->name]["category_request"];
		
		foreach($field as $key=>$value)
		{
			if($category_request==1)
			{
				if(empty($value) or $value=="new")
				{
					return false;
				}
			}
			return true;
		}
	}
	
	function UnixNewCat($field=array())
	{
		$CATEGORY			=	ClassRegistry::Init('Category');
		$category_name		=	$this->data[$this->name]["category_name"];
		$product_id			=	$this->data['Product']['id'];
		
		if(!empty($product_id))
		{
			$PRODUCT		=	ClassRegistry::Init('Product');
			$data			=	$PRODUCT->find('first',array(
									'conditions'	=>	array(
										'Product.id'					=>	$product_id,
										'Product.user_id'				=>	$this->profile['User']['id'],
										'Product.productstatus_id != '	=>	-10,
										'Product.productstatus_user'	=>	1
									)
								));
		}
		
		foreach($field as $key=>$value)
		{
			$value	=	trim($value);
			$cond	=	array(
							'LOWER(Category.name)'	=>	strtolower($value),
							'Category.status'		=>	1,
							'Category.parent_id'	=>	$CATEGORY->FindTop()
						);
			
			if(!empty($product_id))
			{
				$cond					=	array(
												'OR'	=>	array(
													array(
														'LOWER(Category.name)'	=>	strtolower($value),
														'Category.status'		=>	1,
														'Category.parent_id'	=>	$CATEGORY->FindTop()
													),
													array(
														'LOWER(Category.name)'	=>	strtolower($value),
														'Category.id'			=>	$data['Parent']['id'],
														'Category.status'		=>	0,
													),
												)
											);
			}
			
			if($category_name==1)
			{
				$find	=	$CATEGORY->find('first',array(
					'conditions'	=>	$cond
				));
				
				if($find!=false)
				{
					return false;
				}
			}
			return true;
		}
	}
	
	function UnixNewSubCat($field=array())
	{
		$CATEGORY				=	ClassRegistry::Init('Category');
		$subcategory_name		=	$this->data[$this->name]["subcategory_name"];
		$category_id			=	$this->data[$this->name]["cat_id"];
		$product_id				=	$this->data['Product']['id'];
		
		
		if(!empty($product_id))
		{
			$PRODUCT		=	ClassRegistry::Init('Product');
			$data			=	$PRODUCT->find('first',array(
									'conditions'	=>	array(
										'Product.id'					=>	$product_id,
										'Product.user_id'				=>	$this->profile['User']['id'],
										'Product.productstatus_id != '	=>	-10,
										'Product.productstatus_user'	=>	1
									)
								));
		}

		foreach($field as $key=>$value)
		{
			$value	=	trim($value);
			
			if($subcategory_name==1)
			{
				$cond					=	array(
												'LOWER(Category.name)'	=>	strtolower($value),
												'Category.status'		=>	1,
												'Category.parent_id'	=>	$category_id,
											);
				
				if(!empty($product_id))
				{
					$cond					=	array(
													'OR'	=>	array(
														array(
															'LOWER(Category.name)'	=>	strtolower($value),
															'Category.status'		=>	1,
															'Category.parent_id'	=>	$category_id,
														),
														array(
															'LOWER(Category.name)'	=>	strtolower($value),
															'Category.id'			=>	$data['Category']['id'],
															'Category.parent_id'	=>	$category_id,
															'Category.status'		=>	0,
														),
													)
												);
				}
				
				$find	=	$CATEGORY->find('first',array(
					'conditions'	=>	$cond
				));
				
				if($find!=false)
				{
					return false;
				}
			}
			return true;
		}
	}
	
	function NotEmptySub($field=array())
	{
		$subcategory_request	=	$this->data[$this->name]["subcategory_request"];
		
		foreach($field as $key=>$value)
		{
			if($subcategory_request==1)
			{
				if(empty($value) or $value=="new")
				{
					return false;
				}
			}
			return true;
		}
	}
	
	function minLengthNewCat($field=array(),$name,$length)
	{
		$category_request	=	$this->data[$this->name][$name];
		
		foreach($field as $key=>$value)
		{
			if($category_request==0)
			{
				if(!empty($value))
				{
					if(!Validation::minLength($value,$length))
					{
						return false;
					}
				}
			}
			return true;
		}
	}
	
	function maxLengthNewCat($field=array(),$name,$length)
	{
		$category_request	=	$this->data[$this->name][$name];
		
		foreach($field as $key=>$value)
		{
			if($category_request==0)
			{
				if(!empty($value))
				{
					if(!Validation::maxLength($value,$length))
					{
						return false;
					}
				}
			}
			return true;
		}	
	}
	
	function NotEmptyNewCat($field=array())
	{
		$category_name	=	$this->data[$this->name]["category_name"];
		
		foreach($field as $key=>$value)
		{
			
			if($category_name==1)
			{
				if(empty($value))
				{
					return false;
				}
			}
			
			return true;
		}
	}
	function NotEmptyNewSub($field=array())
	{
		$subcategory_name	=	$this->data[$this->name]["subcategory_name"];
		
		foreach($field as $key=>$value)
		{
			
			if($subcategory_name==1)
			{
				if(empty($value))
				{
					return false;
				}
			}
			
			return true;
		}
	}
	
	function YahooMail($field=array())
	{
		foreach( $field as $key => $value )
		{
		   	if(!empty($value))
			{
				$domain	=	array_pop(explode("@",$value));
				$domain	=	explode(".",$domain);
				if(!(Validation::email($value) && strtolower($domain[0])=="yahoo"))
				{
					return false;
				}
			}
        }
        return true;
	}
	
	function Phone($field=array(),$profile)
	{
		$ExtdPhone		=	ClassRegistry::Init('ExtendedPhone');
		$arr			=	$ExtdPhone->GetAllPhone($profile);
		$product_id		=	$this->data['Product']['id'];
		$PRODUCT		=	ClassRegistry::Init('Product');
		
		if(!empty($product_id))
		{
			$data		=	$PRODUCT->findById($product_id);
			foreach(explode(",",$data['Product']['phone']) as $key=>$val)
			{
				$productphone[$val]	=	$val;
			}
			$arr		=	array_merge($arr,$productphone);
		}
		
		
		if(!empty($arr) && is_array($arr))
		{
			foreach($arr as $k=>$v)
			{
				$arr_tmp[trim($v)]	=	trim($v);
			}
			
			foreach( $field as $key => $value )
			{
				if(!empty($value))
				{
					foreach($value as $phone)
					{
						if(!in_array(trim($phone),$arr_tmp) and !empty($phone))
						{
							return false;
						}
					}
				}
			}
		}
		
        return TRUE;
	}
	
	function ContactName($field=array())
	{
		$arr	=	array("1","2");
		foreach( $field as $key => $value ){
            if(!in_array($value,$arr))
			{
				return false;
			}
        }
        return TRUE;
	}
	
	function size( $field=array(), $aloowedsize) 
    {
		foreach( $field as $key => $value ){
            $size = $value['size'];
            if($size > $aloowedsize) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }
	
	function validateName($file=array(),$ext=array())
	{
		$err	=	array();
		$i=0;
		
		foreach($file as $file)
		{
			$i++;
			
			if(!empty($file['name']))
			{
				if(!Validation::extension($file['name'], $ext))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	function notEmptyNopol($field=array())
	{
		$condition_id	=	$this->data[$this->name]["condition_id"];
		
		if($condition_id==2 or $condition_id=="" )
		{
			foreach( $field as $key => $value )
			{
				if(empty($value))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	function regexnopol($field=array())
	{
		$condition_id	=	$this->data[$this->name]["condition_id"];
		
		if($condition_id==2)
		{
			foreach( $field as $key => $value )
			{
				if(!Validation::postal($value,"/^([A-Z,a-z]{1,2})([0-9]{1,4})([A-Z,a-z]{0,3})$/"))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	function minLengthNopol($field=array(),$length)
	{
		$condition_id	=	$this->data[$this->name]["condition_id"];
		if($condition_id==2 or $condition_id=="" )
		{
			foreach( $field as $key => $value )
			{
				$value	=	str_replace(" ","",trim($value));
				if(!Validation::minLength($value,$length))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	function maxLengthNopol($field=array(),$length)
	{
		$condition_id	=	$this->data[$this->name]["condition_id"];
		if($condition_id==2 or $condition_id=="" )
		{
			foreach( $field as $key => $value )
			{
				$value	=	str_replace(" ","",trim($value));
				if(!Validation::maxLength($value,$length))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	function imagewidth($field=array(), $allowwidth=0)
	{
		
		foreach( $field as $key => $value ){
			if(!empty($value['name']))
			{
				$imgInfo	= getimagesize($value['tmp_name']);
				$width		= $imgInfo[0];
				
				if($width < $allowwidth)
				{
					return false;
				}
			}
        }
        return TRUE;
	}
	
	function imageheight($field=array(), $allowheight=0)
	{
		
		foreach( $field as $key => $value ){
			if(!empty($value['name']))
			{
				$imgInfo	= getimagesize($value['tmp_name']);
				$height		= $imgInfo[1];
				
				if($height < $allowheight)
				{
					return false;
				}
			}
        }
        return TRUE;
	}
	
	function NewProductId()
	{
		$id	=	array(0);
		$new	=	$this->find("all",array(
						"conditions"	=>	array(
							"Product.productstatus_id"		=>	1,
							"Product.productstatus_user"	=>	1
						),
						"fields"	=>	array(
											"Product.id"
										),
						"order"		=>	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC"),
						"limit"		=>	6
					));
		
		foreach($new as $new)
		{
			$id[]	=	$new['Product']['id'];
		}
		return $id;
	}
	
	
	function Carousel($category_name=0)
	{
		$this->unbindModel(array(
			"belongsTo"	=>	array(
				'Category',
				'Parent'
			)	
		));
		
		$this->bindModel(
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
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						)
					),
					"hasMany"	=>	array(
						'AdsRequest' => array(
							'className'		=> 'AdsRequest',
							'foreignKey'	=> 'product_id',
							'conditions'	=> array(
								"AdsRequest.status"			=>	"1",
								"AdsRequest.start_date <= "	=>	date("Y-m-d H:i:s"),
								"AdsRequest.end_date >= "	=>	date("Y-m-d H:i:s")
							)
						)
					)
				), false
        );
		
		$CATEGORY	=	ClassRegistry::Init("Category");
		$find_id	=	$CATEGORY->find("first",array(
							"conditions"	=>	array(
								"LOWER(Category.name)"	=>	strtolower($category_name),
								"Category.status"		=>	1
							)
						));
		
		$category_id	=	array(0);
		if($find_id)
		{
			$category_id[]	=	$find_id["Category"]["id"];
			$LIST			=	$CATEGORY->children($find_id["Category"]["id"],false,"Category.id","Category.name ASC");
			if(!empty($LIST))
			{
				foreach($LIST as $LIST)
				{
					$category_id[]	=	$LIST["Category"]["id"];
				}
			}
		}
		
		$not_in		=	$this->NewProductId();
		$data		=	$this->find("all",array(
							"conditions"	=>	array(
								"Product.category_id"			=>	$category_id,
								"Product.productstatus_id"		=>	1,
								"Product.productstatus_user"	=>	1
							),
							"fields"	=>	array(
												"Product.id",
												"Product.category_id",
												"Product.price",
												"Product.thn_pembuatan",
												"Product.condition_id",
												"Product.kilometer",
												"Product.sold",
												"Product.data_type",
												"Product.ym",
												"Product.contact_name",
												"Category.name",
												"Category.parent_id",
												"Parent.name",
												"Province.name",
												"ProductImage.id",
												"ProvinceGroup.name"
											),
							"order"		=>	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC"),
							"limit"		=>	16
						));
		
		return $data;
	}
	
	
	function NotFoundMsg($category_id="all_categories",$current_city="all_cities")
	{
		$msg	=	"";
		if($category_id != "all_categories" && ((int) $category_id)!==0)
		{
			$CATEGORY			=	ClassRegistry::Init("Category");
			$tree	=	$CATEGORY->getpath($category_id);
			unset($tree[0]);
			$display_category	=	"";
			foreach($tree as $tree)
			{
				$display_category	.=	$tree['Category']['name']." ";
			}
			$display_category	=	substr($display_category,0,-1);
			$msg				.=	"dengan kategori <u>".$display_category."</u>";
		}
		
		if($current_city!=="all_cities")
		{
			$PROVINCE		=	ClassRegistry::Init("ProvinceGroup");
			$display_city	=	$PROVINCE->findById($current_city);
			$msg			.=	" untuk daerah <u>".$display_city['ProvinceGroup']['name']."</u>";
		}
		else
		{
			$msg			.=	" di <u> semua kota </u>";	
		}
		return $msg;
	}
	
	
	function BindUnbind()
	{
		$this->unbindModel(array(
			"belongsTo"	=>	array(
				'Productstatus',
				'Category',
				'Parent',
				'User'
			)	
		));
		$this->bindModel(
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
						),
						'Category' => array(
							'className' 	=> 'Category',
							'foreignKey' 	=> 'category_id'
						),
						'Parent' => array(
							'className' 	=>	'Category',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Category.parent_id = Parent.id'
						),
						'ProvinceGroup' => array(
							'className' 	=>	'ProvinceGroup',
							'foreignKey' 	=>	false,
							'conditions'	=>	'Province.group_id = ProvinceGroup.id'
						),
						'User' => array(
							'className' 	=>	'User',
							'foreignKey' 	=>	'user_id'
						)
					),
					"hasMany"	=>	array(
						'AdsRequest' => array(
							'className'		=> 'AdsRequest',
							'foreignKey'	=> 'product_id',
							'conditions'	=> array(
								"AdsRequest.status"			=>	"1",
								"AdsRequest.start_date <= "	=>	date("Y-m-d H:i:s"),
								"AdsRequest.end_date >= "	=>	date("Y-m-d H:i:s")
							)
						)
					)
				), false
        );
	
	}

	function GetCategoryOthers($category_id="all_categories",$current_city="all_cities",$controller)
	{
		$data	=	array();
		
		//GET CATEGORY SELECTED
		if($category_id != "all_categories" && ((int) $category_id)!==0)
		{
			$CATEGORY		=	ClassRegistry::Init("Category");
			
			//CHECK PARENT CATEGORY
			$cat_detail		=	$CATEGORY->findById($category_id);
			
			if($cat_detail['Category']['parent_id'] !== $CATEGORY->FindTop() )
			{
				$selected_id		=	array($cat_detail['Category']['parent_id']);
				$children			=	$CATEGORY->children($cat_detail['Category']['parent_id'],false,"Category.id");
				if(!empty($children))
				{
					foreach($children as $children)
					{
						if($children["Category"]["id"] != $category_id) $selected_id[]	=	$children["Category"]["id"];
					}
				}
				
				$conditions["Product.productstatus_id"]		=	1;
				$conditions["Product.productstatus_user"]	=	1;
				$conditions["Category.id"]					=	$selected_id;
				
				
				if($current_city!="all_cities")
				{
					//FIND CITY
					$PROVINCE		=	ClassRegistry::Init("Province");
					$prov_lists		=	$PROVINCE->find("list",array(
											"conditions"	=>	array(
												"Province.group_id"	=>	$current_city
											),
											"fields"	=>	array("Province.id")
										));
					$conditions["Product.city_id"]	=	$prov_lists;
				}
				
				$this->BindUnbind();
				$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
				$fields			=	array(
										"Product.id",
										"Product.price",
										"Product.thn_pembuatan",
										"Product.condition_id",
										"Product.kilometer",
										"Product.sold",
										"Product.data_type",
										"Product.ym",
										"Product.contact_name",
										"Product.view",
										"Product.comment",
										"Category.name",
										"Parent.name",
										"Province.name",
										"Province.province",
										"ProductImage.id",
										"ProvinceGroup.name"
									);
				
				$data			=	$this->find("all",array(
										"conditions"	=>	$conditions,
										 "fields"		=>	$fields,
										 "order"		=>	$order,
										 "limit"		=>	4
									));
			}
		}
		
		return $data;
	}
	
	
	function GetProvinceOthers($category_id="all_categories",$current_city="all_cities",$type)
	{
		$data	=	array();
		
		//GET CATEGORY SELECTED
		if($category_id != "all_categories" && ((int) $category_id)!==0)
		{
			$CATEGORY			=	ClassRegistry::Init("Category");
			$selected_id		=	array($category_id);
			$children			=	$CATEGORY->children($category_id,false,"Category.id");
			if(!empty($children))
			{
				foreach($children as $children)
				{
					$selected_id[]	=	$children["Category"]["id"];
				}
			}
			$order										=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
			$conditions["Product.productstatus_id"]		=	1;
			$conditions["Product.productstatus_user"]	=	1;
			$conditions["Category.id"]					=	$selected_id;
			
			switch($type)
			{
				case "DaftarMotor" :
					$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
					break;
				case "MotorMurah" :
					$order			=	array("IF( Product.sold = '0', 1, 0) DESC, Product.price ASC");
					$conditions		=	array_merge($conditions,array("Product.price <= " => 7000000));
					break;
				case "MotorKredit" :
					$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
					$conditions		=	array_merge($conditions,array("Product.is_credit" => "1"));
					break;
				case "MotorGede" :
					$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
					$conditions		=	array_merge($conditions,array(
											"OR"	=>	array(
												"Category.is_moge"			=>	"1",
												"Parent.is_moge"			=>	"1"
											)
										));
					break;
			}
			
			$this->BindUnbind();
			$fields			=	array(
									"Product.id",
									"Product.price",

									"Product.thn_pembuatan",
									"Product.condition_id",
									"Product.kilometer",
									"Product.sold",
									"Product.data_type",
									"Product.ym",
									"Product.contact_name",
									"Product.view",
									"Product.comment",
									"Category.name",
									"Parent.name",
									"Province.name",
									"Province.province",
									"ProductImage.id",
									"ProvinceGroup.name"
								);
			
			$data			=	$this->find("all",array(
									"conditions"	=>	$conditions,
									 "fields"		=>	$fields,
									 "order"		=>	$order,
									 "limit"		=>	4
								));
		}
		
		return $data;	
	}
	
	function GetData($category_id="all_categories",$current_city="all_cities",$type="DaftarMotor")
	{
		$this->BindUnbind();
		$replace_title		=	"";
		$replace_region		=	"";
		
		$conditions			=	array(
									"Product.productstatus_id"		=>	1,
									"Product.productstatus_user"	=>	1,
								);
		switch($type)
		{
			case "DaftarMotor" :
				$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
				$title			=	"Cari Motor Bekas {category} {region} - Jual Motor Bekas {category} {region} - Beli Motor Bekas {category} {region}";
				$keywords		=	"Harga Motor Bekas, Motor Bekas, Cari motor Bekas, Jual Motor Bekas, Beli Motor Bekas,";
				break;
			case "MotorMurah" :
				$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.price ASC");
				$conditions		=	array_merge($conditions,array("Product.price <= " => 9000000));
				$title			=	"Cari motor murah {category} {region} - Jual motor murah {category} {region} - Beli Motor Murah {category} {region}";
				$keywords		=	"Harga Motor Murah, Motor Bekas Murah, Cari Motor Murah, Jual Motor Murah, Beli Motor Murah, Kredit Motor Murah";
				break;
			case "MotorKredit" :
				$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
				$conditions		=	array_merge($conditions,array("Product.is_credit" => "1"));
				$title			=	"Cari motor kredit {category} {region} | Jual Motor Kredit {category} {region} | Beli Motor Kredit {category} {region}";
				$keywords		=	"Harga Motor Kredit, Motor Kredit, Daftar Motor Kredit, Cari Motor Kredit, Jual Motor Kredit, Beli Motor Kredit,";
				break;
			case "MotorGede" :
				$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
				$conditions		=	array_merge($conditions,array(
										"OR"	=>	array(
											"Category.is_moge"			=>	"1",
											"Parent.is_moge"			=>	"1"
										)
									));
				$title			=	"Cari motor gede - Jual motor gede - Beli motor gede";
				$keywords		=	"Harga Motor Gede, Moge, Cari Motor Gede, Jual Motor Gede, Beli Motor Gede,";
				break;
			case "MotorKlasik" :
				$order			=	array("IF( Product.sold = '0', 1, 0) DESC,Product.id DESC");
				$conditions		=	array_merge($conditions,array('Product.thn_pembuatan <= '		=> (date("Y")-20)));
				$title			=	"Cari motor klasik - Jual motor klasik - Beli motor klasik";
				$keywords		=	"Jual Motor Klasik, Harga Motor Klasik, Motor Klasik, Cari Motor Klasik,";
				break;
		}

		$fields			=	array(
								"Product.id",
								"Product.price",
								"Product.Sprice",
								"Product.is_credit",
								"Product.thn_pembuatan",
								"Product.condition_id",
								"Product.kilometer",
								"Product.sold",
								"Product.data_type",
								"Product.ym",
								"Product.contact_name",
								"Product.SFirstCredit",
								"Product.SCreditInterval",
								"Product.SCreditPerMonth",
								"Category.name",
								"Parent.name",
								"Province.name",
								"Province.province",
								"ProductImage.id",
								"Product.view",
								"Product.comment",
								"ProvinceGroup.name"
							);
		
		
		//GET CATEGORY SELECTED
		if($category_id != "all_categories" && ((int) $category_id)!==0)
		{
			$CATEGORY			=	ClassRegistry::Init("Category");
			$detail_category	=	$CATEGORY->findById($category_id);
			
			$replace_title		=	($detail_category['Category']['parent_id'] != $CATEGORY->GetTop()) ? $detail_category['Parent']['name']." ".$detail_category['Category']['name']." " : $detail_category['Category']['name']." ";
			
			$keywords			.=	($detail_category['Category']['parent_id'] != $CATEGORY->GetTop()) ? $detail_category['Parent']['name'].", ".$detail_category['Category']['name'].", " : $detail_category['Category']['name'].", ";
			
			$selected_id		=	array($category_id);
			$children			=	$CATEGORY->children($category_id,false,"Category.id");
			if(!empty($children))
			{
				foreach($children as $children)
				{
					$selected_id[]	=	$children["Category"]["id"];
				}
			}
			$conditions["Category.id"]	=	$selected_id;
		}
		
		$title		=	str_replace("{category}",$replace_title,$title);
		
		//GET PROVINCE ID SELECTED
		if($current_city!=="all_cities" && ((int) $current_city)!==0)
		{
			$PROVINCE		=	ClassRegistry::Init("Province");
			$ProvinceGroup	=	ClassRegistry::Init("ProvinceGroup");
			$detail_pgroup	=	$ProvinceGroup->findById($current_city);
			$prov_lists		=	$PROVINCE->find("list",array(
									"conditions"	=>	array(
										"Province.group_id"	=>	$current_city
									),
									"fields"	=>	array("Province.id")
								));
			$conditions["Product.city_id"]	=	$prov_lists;
			
			$replace_region	 =	"daerah ".$detail_pgroup['ProvinceGroup']['name'];
			$keywords		.=	$detail_pgroup['ProvinceGroup']['name'];
		}
		
		$title		=	str_replace("{region}",$replace_region,$title);
		
		return	array("conditions"	=>	$conditions,"order" => $order, "fields"	=> $fields,"title" => $title,"keywords" => $keywords);
	}
	
	
	
	function GenerateBreadCrumb($product_id)
	{
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}
		
		$site_url		=	$settings['Setting']['site_url'];
		$bread			=	array($site_url=>"Beranda");
		$string_bread	=	"";
		
		//FIND DATA
		$this->BindUnbind();
		$data	=	$this->find("first",array(
						"fields" => array(
							"Product.id",
							"Province.group_id",
							"Category.id",
							"Parent.id",
							"Parent.name",
							"Category.name",
						),
						"conditions"	=>	array(
							"Product.id"	=>	$product_id
						)
					));
		
		if($data==false)
		{
			return "";
		}
		
		$ProvinceGroup	=	ClassRegistry::Init("ProvinceGroup");
		$group_name		=	$ProvinceGroup->findById($data['Province']['group_id']);
		$group_name		=	$group_name["ProvinceGroup"]["name"];
		
		$bread[$site_url."DaftarMotor/all_categories/".$data['Province']['group_id']."/motor_semua-merk_".strtolower($group_name).".html"]		=	$group_name;
		
		$bread[$site_url."DaftarMotor/".$data['Parent']['id']."/all_cities/motor_".$this->seoUrl($data['Parent']['name'])."_semua-kota.html"]		=	$data['Parent']['name'];
		$bread[]		=	$data['Category']['name'];
		
		foreach($bread as $url => $name)
		{
			
			if($name==end($bread)) $string_bread	.=	"<span class='bold black1'>".$name."</span>";
			else $string_bread	.=	"<a href='".$url."' class='style1 text12 grey2 normal'>".$name."</a> &raquo; ";
		}
		
		return array("bread"=>$string_bread,"city"=>$group_name);
	}
	
	
	function seoUrl($string)
	{
		//Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
		$string = strtolower($string);
		//Strip any unwanted characters
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
	
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$args			=	func_get_args();
		$uniqueCacheId	=	'';
		$extra['cache']	=	(isset($extra['cache'])) ? $extra['cache'] : true;
		
		foreach ($args as $arg)
		{
			$uniqueCacheId .= serialize($arg);
		}
		
		if (!empty($extra['contain']))
		{
			$contain = $extra['contain'];
		}
		
		$uniqueCacheId = md5($uniqueCacheId);
		//var_dump('pagination-'.$this->alias.'-'.$uniqueCacheId);
		
		if(empty($order))
		{
	        $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
	    }
	    $group = $extra['group'];
		
		
		if($extra['cache'])
		{
			if (($pagination  = Cache::read('pagination-'.$this->alias.'-'.$uniqueCacheId,'paginate_cache')) === false)
			{
				$pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
				Cache::write('pagination-'.$this->alias.'-'.$uniqueCacheId, $pagination,'paginate_cache');
			}
		}
		else
		{
			$pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
		}
	    return $pagination;
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array())
	{
		$args 					=	func_get_args();
		$uniqueCacheId 			=	'';
		$extra['cache']			=	(isset($extra['cache'])) ? $extra['cache'] : true;
		
		foreach ($args as $arg)
		{
			$uniqueCacheId 		.= serialize($arg);
		}
		$uniqueCacheId 			= md5($uniqueCacheId);
		
		if (!empty($extra['contain']))
		{
			$contain = $extra['contain'];
		}

		$parameters 			=	compact('conditions');
		$parameters["fields"]	=	array('Product.id');
	    $this->recursive 		=	$recursive;
	    
	    if($extra['cache'])
		{
			if (($paginationcount  = Cache::read('paginationcount-'.$this->alias.'-'.$uniqueCacheId,'paginate_cache')) === false)
			{
				if (isset($extra['group']))
				{	
					$paginationcount = $this->find('all', array_merge($parameters, $extra));
					$paginationcount = count($paginationcount);
				}
				else
				{
					$paginationcount = $this->find('count', array_merge($parameters, $extra));
				}
				Cache::write('paginationcount-'.$this->alias.'-'.$uniqueCacheId, $paginationcount,'paginate_cache');
			}
		}
		else
		{
			if (isset($extra['group']) or isset($extra['group2']) )
			{	
				$paginationcount = $this->find('all', array_merge($parameters, $extra));
				$paginationcount = count($paginationcount);
			}
			else
			{
				$paginationcount = $this->find('count', array_merge($parameters, $extra));
			}
		}
	    return $paginationcount;
	}
}
?>