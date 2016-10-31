<?php
App::import('Helper', 'Number');
class User extends AppModel
{
	var $name		=	"User";
	var $useTable	=	"users";
	
	var $belongsTo 	= array(
		'Userstatus' => array(
			'className' 	=> 'Userstatus',
			'foreignKey' 	=> 'userstatus_id'
		),
		'Usertype' => array(
			'className' 	=> 'Usertype',
			'foreignKey' 	=> 'usertype_id'
		)
	);
	
	var $hasOne = array(
        'Profile' => array(
            'className'		=> 'Profile',
            'foreignKey'	=> 'user_id',
            'dependent'		=> true
        ),
		'Company' => array(
            'className'		=> 'Company',
            'foreignKey'	=> 'user_id',
            'dependent'		=> true
        )
	);
	
	
	function InitiateResend()
	{
		$this->validate 	= array(
			'email_resend' => array(
				'IsNotValidateEmail' => array(
					'rule' => "IsNotValidateEmail",
					'message' => 'Maaf email anda belum terdaftar sebagai member kami.'	
				),
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
				)
			),
		);
	}
	
	function ValidateForgot()
	{
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate 	= array(
			'email' => array(
				'CheckSuspendUser' => array(
					'rule' 		=> 'CheckSuspendUser',
					'message'	=> 'Maaf email ini telah diblokir, silahkan hubungi customer service kami di '.$settings['admin_mail'].' untuk dapat mengaktifkan kembali akun anda.'
				),
				'CheckEmailExists' => array(
					'rule' => "CheckEmailExists",
					'message' => 'Maaf email anda belum terdaftar sebagai member kami.'	
				),
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
				)
			),
		);
		
	}
	
	function ValidateProcessForgot()
	{
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate 	= array(
			'password' => array(
				'CheckToken' => array(
					'rule' => "CheckToken",
					'message' => 'Maaf token anda telah expired silahkan <a href="'.$settings['site_url'].'"User/ForgotPassword>ulangi proses</a> ubah password.'	
				),
				'maxLength' => array(
					'rule' => array('maxLength',16),
					'message' => 'Maksimum jumlah karakter password adalah 16 karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',4),
					'message' => 'Minimum jumlah karakter password adalah 4 karakter.'	
				),
				'alphaNumeric' => array(
					'rule' => "alphaNumeric",
					'message' => 'Berikan huruf dan angka, tanpa spasi dan karakter lain.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password anda.'	
				)
			),
			'retype_password' => array(
				'Retype' => array(
					'rule' 		=> "Retype",
					'message'	=> 'Password tidak sama.'	
				),
				'notEmptyRetype' => array(
					'rule' 		=> "notEmptyRetype",
					'message'	=> 'Ulangi password anda.'	
				)
			),
		);
	}
	
	function ValidateProcessForgotApps()
	{
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate 	= array(
			'email' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Maaf email tidak ditemukan, silahkan ulangi kembali dari menu forgot password.'	
				)
			),
			'password' => array(
				'maxLength' => array(
					'rule' => array('maxLength',16),
					'message' => 'Maksimum jumlah karakter password adalah 16 karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',4),
					'message' => 'Minimum jumlah karakter password adalah 4 karakter.'	
				),
				'alphaNumeric' => array(
					'rule' => "alphaNumeric",
					'message' => 'Berikan huruf dan angka, tanpa spasi dan karakter lain.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password anda.'	
				)
			),
			'retype_password' => array(
				'Retype' => array(
					'rule' 		=> "Retype",
					'message'	=> 'Password tidak sama.'	
				),
				'notEmptyRetype' => array(
					'rule' 		=> "notEmptyRetype",
					'message'	=> 'Ulangi password anda.'	
				)
			),
			'token' => array(
				'CheckToken' => array(
					'rule' => "CheckToken",
					'message' => 'Maaf token anda telah expired silahkan masuk kembali ke menu forgot password.'	
				),
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message'	=> 'Masukkan kode verifikasi anda.'	
				)
			)
		);
	}
	
	function CheckToken()
	{
		$token		=	$this->data[$this->name]['token'];
		$email		=	$this->data[$this->name]['email'];
		$FPToken	=	ClassRegistry::Init('FPToken');
		$validtoken	=	$FPToken->FindToken($email,$token);
		return $validtoken['status'];
	}
	
	function validateLogin()
	{
		$this->validate 	= array(
			'email_login' => array(
				'CheckEmailExists' => array(
					'rule' => "CheckEmailExists",
					'message' => 'Maaf email anda belum terdaftar sebagai member kami.'	
				),
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
				)
			),
			'password_login' => array(				  
				'CheckLogin' => array(
					'rule' => "CheckLogin",
					'message' => 'Password anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password anda.'	
				)
			)
		);
	}

	function CheckLogin()
	{
		$email		=	$this->data[$this->name]['email_login'];
		$password	=	md5($this->data[$this->name]['password_login']);
		$find		=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'				=>	$email,
								'User.password'			=>	$password,
								'User.userstatus_id'		=>	1
							),
							'order'	=>	array('User.id DESC')
						));
		return $find;
	}
	
	function ValidateChangePassword()
	{
		$this->validate 	= array(
			'newpassword' => array(
				'maxLength' => array(
					'rule' => array('maxLength',16),
					'message' => 'Maksimum jumlah karakter password adalah 16 karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',4),
					'message' => 'Minimum jumlah karakter password adalah 4 karakter.'	
				),
				'alphaNumeric' => array(
					'rule' => "alphaNumeric",
					'message' => 'Berikan huruf dan angka, tanpa spasi dan karakter lain.'	
				),
				'IsSameWithOld' => array(
					'rule' => "IsSameWithOld",
					'message' => 'Password baru anda sama dengan yang lama, berikan nilai yang baru.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password baru anda.'	
				)
			),
			'cnewpassword' => array(
				'confirm_password' => array(
					'rule' 		=> "confirm_password",
					'message'	=> 'Berikan nilai yang sama dengan password baru anda.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Ulangi password baru anda.'	
				)
			),
		);
	}
	
	
	
	function IsSameWithOld($fields	=	array())
	{
		$password	=	$this->data[$this->name]["password"];
		foreach($fields as $k=>$v)
		{
			if(md5($v) == $password)
			{
				return false;
			}
		}
		return true;
	}
	
	function confirm_password($fields	=	array())
	{
		$newpassword	=	$this->data[$this->name]["newpassword"];
		foreach($fields as $k=>$v)
		{
			if($v == $newpassword)
			{
				return true;
			}
		}
		return false;
	}
	
	function InitiateValidate()
	{
		$Number 		= 	new NumberHelper();
		
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate 	= array(
			'fullname' => array(
				'maxLength' => array(
					'rule' => array('maxLength',$settings['max_name_char']),
					'message' => 'Maksimum jumlah karakter nama adalah '.$settings['max_name_char'].' karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Minimum jumlah karakter nama adalah 3 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan nama lengkap anda.'	
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
					'message'	=> 'Masukkan no telpon anda.'	
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
			'email' => array(
				'CheckSuspendUser' => array(
					'rule' 		=> empty($this->data[$this->name]['id']) ? 'CheckSuspendUser' : array('CheckSuspendUser2','id'),
					'message'	=> 'Maaf email ini telah diblokir, silahkan gunakan email lainnya.'
				),
				'CheckUsersExists' => array(
					'rule' 		=> empty($this->data[$this->name]['id']) ? 'CheckUsersExists' : array('CheckUsersExists2','id'),
					'message'	=> 'Maaf email ini telah digunakan member lain, silahkan gunakan email lainnya.'
				),
				'email' => array(
					'rule' => "email",
					'message' => 'Format email anda salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
				)
			),
			'ym' => array(
				'YahooMail' => array(
					'rule' => "YahooMail",
					'message' => 'Format email yahoo anda tidak benar.'	
				)
			),
			'password' => array(
				'maxLength' => array(
					'rule' => array('maxLength',16),
					'message' => 'Maksimum jumlah karakter password adalah 16 karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',4),
					'message' => 'Minimum jumlah karakter password adalah 4 karakter.'	
				),
				'alphaNumeric' => array(
					'rule' => "alphaNumeric",
					'message' => 'Berikan huruf dan angka, tanpa spasi dan karakter lain.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password anda.'	
				)
			),
			'retype_password' => array(
				'Retype' => array(
					'rule' 		=> "Retype",
					'message'	=> 'Password tidak sama.'	
				),
				'notEmptyRetype' => array(
					'rule' 		=> "notEmptyRetype",
					'message'	=> 'Ulangi password anda.'	
				)
			),
			'cname' => array(
				'maxLengthCname' => array(
					'rule' => array('maxLengthCname',40),
					'message' => 'Maksimum jumlah karakter Dealer adalah 40 karakter.'	
				),
				'minLengthCname' => array(
					'rule' => array('minLengthCname',3),
					'message' => 'Minimum jumlah karakter Dealer adalah 3 karakter.'	
				),
				'notEmptyCname' => array(
					'rule' 			=> "notEmptyCname",
					'message'		=> 'Masukkan Dealer/Perusahaan/Distributor anda.'	
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
			),
			'agree' => array(
				'notEmpty' => array(
					'rule' 			=> "notEmptyAgree",
					'message'		=> 'Harap memverifikasi Anda setuju dengan sarat dan ketentuan yang berlaku.'	
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
	
	function ValidateUpload()
	{
		$Number 		=	new NumberHelper();
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
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
	
	
	function minLengthCname($field=array(),$min)
	{
		$usertype_id	=	$this->data[$this->name]['usertype_id'];
		foreach( $field as $key => $value )
		{
			if($usertype_id=="2" and strlen( $value)<$min) return false;
		}
		return true;
	}
	
	function maxLengthCname($field=array(),$max)
	{
		$usertype_id	=	$this->data[$this->name]['usertype_id'];
		foreach( $field as $key => $value )
		{
			if($usertype_id=="2" and strlen( $value)>$max) return false;
		}
		return true;
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
	
	function notEmptyCname($field=array())
	{
		$usertype_id	=	$this->data[$this->name]['usertype_id'];
		foreach( $field as $key => $value )
		{
			if($usertype_id=="2" and empty( $value)) return false;
		}
		return true;
	}
	
	function notEmptyAgree($field=array(),$settings)
	{
		
		foreach( $field as $key => $value )
		{
			if(empty( $value ))
			{
				return false;
			}
			return true;
		}
	}
	
	function Retype($field=array())
	{
		$password	=	$this->data[$this->name]['password'];
		foreach( $field as $key => $value )
		{
			if($password === $value) return true;
		}
		return false;
	}
	
	function notEmptyRetype($field=array())
	{
		$password	=	$this->data[$this->name]['password'];
		
		if(!empty($password))
		{
			foreach( $field as $key => $value )
			{
				if(empty($value)) return false;
			}
		}
		return true;
	}
	
	
	function CheckSuspendUser($field=array())
	{
		foreach( $field as $key => $value )
		{
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$value,
								'User.userstatus_id'	=>	array(-1,-2)
							),
							'order'	=>	array('User.id DESC')
						));
			
			if($find)
			{
				return false;
			}
		}
		return true;
	}
	
	function CheckSuspendUser2($field=array(),$field_compare)
	{
		$id	=	$this->data[$this->name][$field_compare];
		foreach( $field as $key => $value )
		{
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$value,
								'User.userstatus_id'	=>	array(-1,-2),
								'User.id !=	'			=>	$id
							),
							'order'	=>	array('User.id DESC')
						));
			if($find)
			{
				return false;
			}
		}
		return true;
	}
	
	
	function CheckUsersExists($field=array())
	{
		foreach( $field as $key => $value )
		{
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$value,
								'User.userstatus_id'	=>	1
							),
							'order'	=>	array('User.id DESC')
						));
			if($find == false)
			{
				return true;
			}
		}
		return false;
	}
	
	function CheckUsersExists2($field=array(),$field_compare)
	{
		$id	=	$this->data[$this->name][$field_compare];
		foreach( $field as $key => $value )
		{
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$value,
								'User.userstatus_id'	=>	1,
								'User.id !=	'			=>	$id
							),
							'order'	=>	array('User.id DESC')
						));
			if($find == false)
			{
				return true;
			}
		}
		return false;
	}
	
	function CheckUsersExistsEmail($email)
	{
		$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'email'			=>	$value,
								'userstatus_id'	=>	1
							),
							'order'	=>	array('User.id DESC')
						));
		
		if($find == false)
		{
			return true;
		}
		return false;
	}
	
	function IsNotValidateEmail($field=array())
	{
		foreach( $field as $key => $value )
		{
			
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$value,
								'User.userstatus_id'	=>	0
							),
							'order'	=>	array('User.id DESC')
						));
		}
		return $find;
	}
	
	function CheckEmailExists($field=array())
	{
		foreach( $field as $key => $value )
		{
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$value,
								'userstatus_id'			=>	array(1,-2)
							),
							'order'	=>	array('User.id DESC')
						));
			if($find == false)
			{
				return false;
			}
		}
		return true;
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
	
	function getValidation($ID) {
		
        $VALIDATIONS = ClassRegistry::Init("ValidationCode");
        
        //FIND FIRST
        $find = $VALIDATIONS->find('first', array(
                    'conditions' => array(
                        'ValidationCode.id' => $ID
                    )
                ));
		
        $validation = $this->Secret();
        $expired = date("Y-m-d h:i:s", mktime(date("h"), date("i"), date("s"), date("m"), date("d") + 30, date("Y")));

        if ($find == false) {
            $save = $VALIDATIONS->saveAll(
                            array(
                                'code' => $validation,
                                'id' => $ID,
                                'expired' => $expired
                            ),
                            false
            );
        } else {
            if ($find["ValidationCode"]['expired'] < date("Y-m-d H:i:s")) {
                $update = $VALIDATIONS->updateAll(
                                array(
                                    'code' 		=> "'" . $validation . "'",
                                    'expired'	=> "'" . $expired . "'"
                                ),
                                array(
                                    "ValidationCode.id" => $find["ValidationCode"]["id"]
                                )
                );
            } else {
                return $find["ValidationCode"]["code"];
            }
        }
        return $validation;
    }

	function Secret()
	{
        $VALIDATIONS = ClassRegistry::Init("ValidationCode");

        
        $tmp_code = rand(1, 99999);

        // Check if is already been used
        $ver = $VALIDATIONS->find('first', array(
                    'fields' => 'code',
                    'conditions' => array('ValidationCode.code' => $tmp_code)
                ));
        if (is_array($ver)) {
            $tmp_code = $this->Secret();
        }
        return $tmp_code;
    }
	
	function CheckUserByEmail($email)
	{
		$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'	=>	$email
							),
							'order'				=>	array("User.userstatus_id DESC")
						));
		return $find;
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
}

?>