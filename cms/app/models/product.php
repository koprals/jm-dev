<?php
class Product extends AppModel
{
	var $name		= 'Product';
	var $settings;
	var $profile;
	
	
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
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
			'photo' => array(
				'imageheight' => array(
					'rule' => array('imageheight',$settings['min_height_product']),
					'message' => 'Maaf ukuran panjang gambar penjual terlalu kecil, minimal '.$settings['min_height_product'].' px.'	
				),
				'imagewidth' => array(
					'rule' => array('imagewidth',$settings['min_width_product']),
					'message' => 'Maaf ukuran lebar gambar penjual terlalu kecil, minimal '.$settings['min_width_product'].' px.'	
				),
				'size' => array(
					'rule' => array('size',$settings['max_photo_upload']),
					'message' => 'File penjual terlalu besar, maksimum ukuran photo adalah '.$Number->toReadableSize($settings['max_photo_upload']).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Hanya extension (*.gif,*.jpeg,*.jpg,*.png) yang di perbolehkan.'	
				)
			)
		);
	}
	
	function InitiateValidate()
	{
		$Number 		=	new NumberHelper();
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		$this->settings	=	$settings;
		
		$this->validate	=	array(
			'contact_name' => array(
				'maxLength' => array(
					'rule' => array('maxLength',$settings['max_name_char']),
					'message' => 'Maksimum jumlah karakter nama penjual adalah '.$settings['max_name_char'].' karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Minimum jumlah karakter nama penjual adalah 3 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan masukkan/pilih nama penjual'	
				)
			),
			'phone' => array(
				'Phone' => array(
					'rule' => "Phone",
					'message' => 'Masukkan nilai angka untuk nomor telp, tidak boleh mengandung karakter ataupun spasi.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan masukkan no telp penjual.'	
				),
			),
			'ym' => array(
				'YahooMail' => array(
					'rule' => "YahooMail",
					'message' => 'Format email yahoo penjual tidak benar.'	
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
					'message' => 'Masukkan alamat lengkap penjual.'	
				)
			),
			'province_id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan pilih propinsi penjual.'	
				)
			),
			'cat_id' => array(
				'NotEmptyCat'	=> array(
					'rule' => 'NotEmptyCat',
					'message' => 'Silahkan pilih merk motor penjual.'
				)
			),
			'subcategory' => array(
				'NotEmptySub'	=> array(
					'rule' => 'NotEmptySub',
					'message' => 'Silahkan pilih tipe motor penjual.'
				)
			),
			'newsubcategory'	=> array(
				'maxLengthNewCat' => array(
					'rule' => array('maxLengthNewCat',"subcategory_request",15),
					'message' => 'Maksimum jumlah karakter tipe motor adalah 15 karakter.'
				),
				'minLengthNewCat' => array(
					'rule' => array('minLengthNewCat',"subcategory_request",3),
					'message' => 'Minimum jumlah karakter tipe motor adalah 3 karakter.'
				),
				'NotEmptyNewSub'	=> array(
					'rule' => 'NotEmptyNewSub',
					'message' => 'Masukkan nama tipe motor penjual.'
				)
			),
			'condition_id' => array(
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Silahkan pilih kondisi motor penjual, baru/bekas ?.'
				)
			),
			'nopol' => array(
				'regexnopol'	=> array(
					'rule' => "regexnopol",
					'message' => 'Format nopol motor salah.',
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
				'postal'	=> array(
					'rule' => array('postal',"/^(19|20)([0-9]{2})$/"),
					'message' => 'Format tahun pembuatan salah.'
				),
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Masukkan tahun pembuatan motor penjual.'
				)
			),
			'color' => array(
				'maxLength' => array(
					'rule' => array('maxLength',25),
					'message' => 'Maksimum jumlah karakter warna motor adalah 25 karakter.'
				),
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Minimum jumlah karakter warna motor adalah 4 karakter.'
				),
				'notEmpty'	=> array(
					'rule' => 'notEmpty',
					'message' => 'Masukkan warna motor penjual.'
				)
			),
			'kilometer' => array(
				'postal'	=> array(
					'rule' => array('postal',"/^([0-9]+)$/"),
					'message' => 'Masukkan angka untuk kilometer.',
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
					'message' 	=> 'Masukkan harga motor penjual.'
				)
			),
			'first_credit'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 12.000.000.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan anngsuran pertama motor penjual.'
				)
			),
			'credit_interval'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 10.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan anngsuran pertama motor penjual.'
				)
			),
			'credit_per_month'	=>array(
				'moneyIsCredit'	=> array(
					'rule' 		=> 'moneyIsCredit',
					'message'	=> 'Masukkan format harga dengan benar co: 12.000.000.'
				),
				'notEmptyIsCredit'	=> array(
					'rule' 		=> 'notEmptyIsCredit',
					'message' 	=> 'Masukkan anngsuran perbulan motor penjual.'
				)
			),
			'notice'	=>array(
				'notice'	=> array(
					'rule' 		=> array('notice','productstatus_id'),
					'message'	=> 'Masukkan alasan anda, mengapa iklan ini perlu di edit.'
				)
			)
		);
		//$this->validate	=array();
	}

	function notice($field=array(),$productstatus_id)
	{
		
		foreach( $field as $key => $value ){
			if(empty($value) && $this->data[$this->name][$productstatus_id]==-1)
			{
				return false;
			}
		}
        return TRUE;
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
	
	function notEmptyPrimary($field=array())
	{
		$filename	=	$this->data[$this->name]["filename"];
		$isset_file	=	0;
		$ROOT		=	$this->settings['path_content'];
		
		
		foreach($filename as $filename)
		{
			
			$tmp		=	$ROOT."TmpProduct/".$this->profile['User']['id']."/".$filename;
			if(!empty($filename) and is_file($tmp))
			{
				$isset_file	=	1;
				break;
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
		
		
		foreach($filename as $filename)
		{
			
			$tmp		=	$ROOT."TmpProduct/".$this->profile['User']['id']."/".$filename;
			if(!empty($filename) and is_file($tmp))
			{
				$isset_file	=	1;
				break;
			}
		}
		
		if($isset_file	== 0)
		{
			return false;
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
		
		foreach( $field as $key => $value ){
            if(empty($value))
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
	
	function Phone($field=array())
	{
		foreach( $field as $key => $value )
		{
			$phone	=	explode(",",$value);
			foreach($phone as $phone)
			{
				if(!Validation::postal($phone,"/^([0-9]+)$/"))
				{
					return false;
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
		if($condition_id==2)
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
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
	    $parameters = compact('conditions');
	    $this->recursive = $recursive;
	    $count = $this->find('count', array_merge($parameters, $extra));
	    if (isset($extra['group'])) {
	    	$count = $this->find('all', array_merge($parameters, $extra));
	        $count = $this->getAffectedRows();
	    }
	   
	    return $count;
	}
	
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
	    
		
		if(empty($order)){
	        $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
	    }
		
	    $group = $extra['group'];
	    return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
	}
}
?>