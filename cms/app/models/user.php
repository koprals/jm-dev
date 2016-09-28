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
					'rule' 		=> "CheckEmailExists",
					'message'	=> 'Maaf email user belum terdaftar sebagai member kami.'	
				),
				'email' => array(
					'rule' => "email",
					'message' => 'Format email user salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email user.'	
				)
			),
		);
		
	}
	
	
	
	
	function ValidateProcessForgot()
	{
		$this->validate 	= array(
			'password' => array(
				'maxLength' => array(
					'rule' => array('maxLength',10),
					'message' => 'Maksimum jumlah karakter password adalah 10 karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',4),
					'message' => 'Minimum jumlah karakter password adalah 4 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password user.'	
				)
			),
			'retype_password' => array(
				'Retype' => array(
					'rule' 		=> "Retype",
					'message'	=> 'Password tidak sama.'	
				),
				'notEmptyRetype' => array(
					'rule' 		=> "notEmptyRetype",
					'message'	=> 'Ulangi password user.'	
				)
			),
		);
		
	}
	
	
	function validateAdmin()
	{
		$this->validate 	= array(
			'email_login' => array(
				'CheckAdminExists' => array(
					'rule' => "CheckAdminExists",
					'message' => 'Maaf email anda belum terdaftar di tempat kami.'	
				),
				'email' => array(
					'rule' => "email",
					'message' => 'Format email user salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email anda.'	
				)
			),
			'password_login' => array(
				'CheckLogin' => array(
					'rule' => "CheckLogin",
					'message' => 'Password user salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password anda.'	
				)
			)
		);
	}
	
	function CheckAdminExists($field=array())
	{
		foreach( $field as $key => $value )
		{
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$value,
								'User.userstatus_id'	=>	1,
								'User.admintype_id'		=>	array(2,3)
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
	
	
	function validateLogin()
	{
		$this->validate 	= array(
			'email_login' => array(
				'email' => array(
					'rule' => "email",
					'message' => 'Format email user salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email user.'	
				)
			),
			'password_login' => array(
				'CheckLogin' => array(
					'rule' => "CheckLogin",
					'message' => 'Password user salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan password user.'	
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
								'email'			=>	$email,
								'password'		=>	$password,
								'userstatus_id'	=>	1,
							),
							'order'	=>	array('User.id DESC')
						));
		return $find;
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
					'rule' => array('maxLength',30),
					'message' => 'Maksimum jumlah karakter nama adalah 30 karakter.'	
				),
				'minLength' => array(
					'rule' => array('minLength',3),
					'message' => 'Minimum jumlah karakter nama adalah 3 karakter.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan nama lengkap user.'	
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
					'message' => 'Masukkan alamat lengkap user.'	
				)
			),
			'province' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan pilih propinsi user.'	
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
					'rule' => "notEmpty",
					'message' => 'Masukkan no telpon user.'	
				)
			),
			'fax' => array(
				'minLength' => array(
					'rule' => array('minLength',6),
					'message' => 'Minimum jumlah karakter telpon adalah 6 karakter.',
					'allowEmpty' => true	
				),
				'maxLength' => array(
					'rule' => array('maxLength',15),
					'message' => 'Maximum jumlah karakter telpon adalah 15 karakter.',
					'allowEmpty' => true	
				),
				'postal'	=> array(
					'rule' => array('postal','/^([0-9]+)$/'),
					'message' => 'No fax harus berupa angka, tidak boleh ada karakter ataupun spasi.',
					'allowEmpty' => true
				)
			),
			'email' => array(
				'ChangeStatus' => array(
					'rule' 			=> "ChangeStatus",
					'message'		=> 'Email member harus berbeda dengan sebelumnya, silahkan ganti email member.'	
				),
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
					'message' => 'Format email user salah.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Masukkan email user.'	
				)
			),
			'admintype_id' => array(
				'notEmpty' => array(
					'rule' 			=> "notEmpty",
					'message'		=> 'Pilih tipe user.'	
				)
			),
			'cname' => array(
				'maxLengthCname' => array(
					'rule' => array('maxLengthCname',20),
					'message' => 'Maksimum jumlah karakter Dealer adalah 20 karakter.'	
				),
				'minLengthCname' => array(
					'rule' => array('minLengthCname',3),
					'message' => 'Minimum jumlah karakter Dealer adalah 3 karakter.'	
				),
				'notEmptyCname' => array(
					'rule' 			=> "notEmptyCname",
					'message'		=> 'Masukkan Dealer/Perusahaan/Distributor user.'	
				)
			),
			'photo' => array(
				'imageheight' => array(
					'rule' => array('imageheight',$settings['max_height_userphoto']),
					'message' => 'Maaf ukuran panjang gambar user terlalu kecil, minimal '.$settings['max_height_userphoto'].' px.'	
				),
				'imagewidth' => array(
					'rule' => array('imagewidth',$settings['max_width_userphoto']),
					'message' => 'Maaf ukuran lebar gambar user terlalu kecil, minimal '.$settings['max_width_userphoto'].' px.'	
				),
				'size' => array(
					'rule' => array('size',$settings['max_photo_upload']),
					'message' => 'File user terlalu besar, maksimum ukuran photo adalah '.$Number->toReadableSize($settings['max_photo_upload']).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Hanya extension (*.gif,*.jpeg,*.jpg,*.png) yang di perbolehkan.'	
				)
			)
		);
		
		if(empty($this->data[$this->name]['id']))
		{
			$validate	=	array(
				'password' => array(
					'maxLength' => array(
						'rule' => array('maxLength',10),
						'message' => 'Maksimum jumlah karakter password adalah 10 karakter.'	
					),
					'minLength' => array(
						'rule' => array('minLength',4),
						'message' => 'Minimum jumlah karakter password adalah 4 karakter.'	
					),
					'notEmpty' => array(
						'rule' => "notEmpty",
						'message' => 'Masukkan password user.'	
					)
				),
				'retype_password' => array(
					'Retype' => array(
						'rule' 		=> "Retype",
						'message'	=> 'Password tidak sama.'	
					),
					'notEmptyRetype' => array(
						'rule' 		=> "notEmptyRetype",
						'message'	=> 'Ulangi password user.'	
					)
				)
			);
			$this->validate	=	array_merge($this->validate,$validate);
		}
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
					'rule' => array('imageheight',$settings['max_height_userphoto']),
					'message' => 'Maaf ukuran panjang gambar user terlalu kecil, minimal '.$settings['max_height_userphoto'].' px.'	
				),
				'imagewidth' => array(
					'rule' => array('imagewidth',$settings['max_width_userphoto']),
					'message' => 'Maaf ukuran lebar gambar user terlalu kecil, minimal '.$settings['max_width_userphoto'].' px.'	
				),
				'size' => array(
					'rule' => array('size',$settings['max_photo_upload']),
					'message' => 'File user terlalu besar, maksimum ukuran photo adalah '.$Number->toReadableSize($settings['max_photo_upload']).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Hanya extension (*.gif,*.jpeg,*.jpg,*.png) yang di perbolehkan.'	
				)
			)
		);
	}
	
	function ChangeStatus($field=array())
	{
		$user_id	=	$this->data[$this->name]['id'];
		
		if(!empty($user_id))
		{
			$user_before	=	$this->findById($user_id);
			$userstatus_id	=	$this->data[$this->name]['userstatus_id'];
			foreach( $field as $key => $email )
			{
				if($user_before['User']['userstatus_id']==1 && $userstatus_id==0 && $user_before['User']['email']==$email)
				{
					return false;
				}
			}
		}
		return true;
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
	
	function notEmptyCname($field=array())
	{
		$usertype_id	=	$this->data[$this->name]['usertype_id'];
		foreach( $field as $key => $value )
		{
			if($usertype_id=="2" and empty( $value)) return false;
		}
		return true;
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
	
	function CheckEmailExists($field=array())
	{
		foreach( $field as $key => $value )
		{
			$find	=	$this->find('first',array(
							'conditions'	=>	array(
								'email'			=>	$value,
								'userstatus_id'	=>	array(1,-2)
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