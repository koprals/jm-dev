<?php
App::import('Helper', 'Number');
class Company extends AppModel
{
	var $name		=	"Company";
	var $useTable	=	"companies";
	var $belongsTo 	= array(
		'User' => array(
			'className' 	=> 'User',
			'foreignKey' 	=> 'user_id'
		)
	);
	
	function InitiateValidate()
	{
		$Number 		= 	new NumberHelper();
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate 	= array(
			'name' => array(
				'maxLength' => array(
					'rule' => array('maxLength',$settings['max_name_char']),
					'message' => 'Maksimum jumlah karakter nama dealer/toko adalah '.$settings['max_name_char'].' karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Minimum jumlah karakter dealer/toko adalah 3 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan nama dealer/toko anda.'	
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
			'province' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan pilih propinsi anda.'	
				)
			),
			'phone' => array(
				'minLength' => array(
					'rule' => array('minLength',6),
					'message' => 'Minimum jumlah karakter telpon adalah 6 karakter.'	
				),
				'maxLength' => array(
					'rule' => array('maxLength',15),
					'message' => 'Maximum jumlah karakter telpon adalah 15 karakter.'	
				),
				'postal'	=> array(
					'rule' => array('postal','/^([0-9]+)$/'),
					'message' => 'No telpon harus berupa angka, tidak boleh ada karakter ataupun spasi.'
				),
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message'	=> 'Masukkan no telpon dealer/toko anda.'	
				)
			),
			'fax' => array(
				'minLength' => array(
					'rule' => array('minLength',6),
					'message' => 'Minimum jumlah karakter fax adalah 6 karakter.',
					'allowEmpty'	=>	true
				),
				'maxLength' => array(
					'rule' => array('maxLength',15),
					'message' => 'Maximum jumlah karakter fax adalah 15 karakter.',
					'allowEmpty'	=>	true	
				),
				'postal'	=> array(
					'rule' => array('postal','/^([0-9]+)$/'),
					'message' => 'No fax harus berupa angka, tidak boleh ada karakter ataupun spasi.',
					'allowEmpty'	=>	true
				)
			),
			'website' => array(
				'url'	=> array(
					'rule' => "url",
					'message'		=> 'Masukkan format website dengan benar.',
					'allowEmpty'	=>	true
				)
			),
			'description' => array(
				'minLength' => array(
					'rule' => array('minLength',10),
					'message' => 'Minimum jumlah karakter alamat adalah 10 karakter.',
					'allowEmpty'	=>	true
				)
			),
			'photo' => array(
				'imageheight' => array(
					'rule' => array('imageheight',$settings['max_width_userphoto']),
					'message' => 'Maaf ukuran panjang gambar anda terlalu kecil, minimal '.$settings['max_width_userphoto'].' px.'	
				),
				'imagewidth' => array(
					'rule' => array('imagewidth',$settings['max_height_userphoto']),
					'message' => 'Maaf ukuran lebar gambar anda terlalu kecil, minimal '.$settings['max_height_userphoto'].' px.'	
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
	
	
	function ValidatePhoto()
	{
		$Number 		=	new NumberHelper();
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
			'photo' => array(
				'imagewidth' => array(
					'rule' => array('imagewidth',$settings['max_height_userphoto']),
					'message' => 'Maaf ukuran lebar gambar anda terlalu kecil, minimal '.$settings['max_height_userphoto'].' px.'	
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
	
	function ValidateUpload()
	{
		$Number 		=	new NumberHelper();
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
			'photo' => array(
				'imagewidth' => array(
					'rule' => array('imagewidth',$settings['max_height_userphoto']),
					'message' => 'Maaf ukuran lebar gambar anda terlalu kecil, minimal '.$settings['max_height_userphoto'].' px.'	
				),
				'size' => array(
					'rule' => array('size',$settings['max_photo_upload']),
					'message' => 'File anda terlalu besar, maksimum ukuran photo adalah '.$Number->toReadableSize($settings['max_photo_upload']).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Hanya extension (*.gif,*.jpeg,*.jpg,*.png) yang di perbolehkan.'	
				),
				'notEmpty' => array(
					'rule' => "notEmptyFileName",
					'message' => 'Pilih gambar anda.'	
				)
			),
			'agree' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Harap memverifikasi Anda setuju dengan sarat dan ketentuan yang berlaku.'	
				)
			)
		);
	}
	
	
	function notEmptyFileName($field=array())
	{
		foreach( $field as $key => $value ){
           if(empty($value['name']))
		   {
				return false;
				break;
			}
			break;
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
}
?>