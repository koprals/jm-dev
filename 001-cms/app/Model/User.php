<?php
App::uses('Sanitize','Utility');
class User extends AppModel
{
	var $dataOld;

	public function beforeSave($options = array())
	{
		if(!empty($this->data))
		{
			App::Import("Component","General");
			$GENERAL	=	new GeneralComponent();

			foreach($this->data[$this->name] as $key => $name)
			{

				if(!is_array($this->data[$this->name][$key]) && isset($this->data[$this->name][$key]))
					$this->data[$this->name][$key]			=	trim($this->data[$this->name][$key]);

				if($key == "name")
				{
					$this->data[$this->name][$key]			=	Sanitize::html($this->data[$this->name][$key]);
				}

				if($key == "email")
				{
					$this->data[$this->name][$key]			=	strtolower($this->data[$this->name][$key]);
				}

				if($key == "password")
				{
					$this->data[$this->name][$key]			=	$GENERAL->my_encrypt($this->data['User']['password']);
				}
			}
		}

		if($this->id)
		{
			$this->dataOld	=	$this->findById($this->id);
		}
		return true;
	}

	public function afterSave($created, $options = array())
	{
	}

	public function beforeValidate($options = array())
	{
		foreach($this->data[$this->name] as $key => $name)
		{

			if(!is_array($this->data[$this->name][$key]) && isset($this->data[$this->name][$key]))
				$this->data[$this->name][$key]			=	trim($this->data[$this->name][$key]);

			if($key == "email")
			{
				$this->data[$this->name][$key]			=	strtolower(trim($this->data[$this->name][$key]));
			}
		}

		return true;
	}

	public function beforeDelete($cascade = true)
	{
		$this->dataOld	=	$this->findById($this->id);
	}

	public function afterDelete()
	{
		//DELETE IMAGE CONTENT
		App::import('Component','General');
		$General		=	new GeneralComponent();
		$General->DeleteContent($this->id,$this->name);
	}

	public function afterFind($results, $primary = false)
	{
		return $results;
	}

	function ValidateAddBA()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'name' => array(
				'notEmpty' => array(
					'rule'				=>	"notEmpty",
					'message'			=>	"Please insert name"
				),
				'maxLength' => array(
					'rule' 				=>	array("maxLength",80),
					'message'			=>	"Name is too long"
				),
				'minLength' => array(
					'rule' 				=>	array("minLength",3),
					'message'			=>	"Name is too short"
				)
			),
			'email' => array(
				'notEmpty' => array(
					'rule'				=>	"notEmpty",
					'message'			=>	"Please insert email"
				),
				'email' => array(
					'rule' 				=>	"email",
					'message'			=>	"Email format is wrong"
				),
				'isUnique'		=>	array(
					'rule'  			=>  'isUnique',
					'message' 			=>	"Email already exists, please put another email"
				)
			),
			'password' => array(
				'notEmpty' => array(
					'rule'				=>	'notEmpty',
					'message' 			=>	"Please insert password"
				),
				'minLength' => array(
					'rule' 				=>	array("minLength",8),
					'message'			=>	"Password is too short"
				),
				'maxLength' => array(
					'rule' 				=>	array("maxLength",80),
					'message'			=>	"Password is too long"
				),
				'NoSpace' => array(
					'rule' 				=>	'NoSpace',
					'message'			=>	"Please do not put space in password field"
				)
			),
			'retype' => array(
				'equalToField' => array(
					'rule' 				=>	array('equalToField','password'),
					'message'			=>	"Password is not same, please retype your password"
				)
			),
			'gender' => array(
				'notEmpty' => array(
					'rule'				=>	'notEmpty',
					'message' 			=>	"Please select gender"
				)
			),
			'phone' => array(
				'notEmpty' => array(
					'rule'				=>	'notEmpty',
					'message' 			=>	"Please insert phone"
				),
				'minLength' => array(
					'rule' 				=>	array("minLength",4),
					'message'			=>	"Phone number is too short"
				),
				'maxLength' => array(
					'rule' 				=>	array("maxLength",80),
					'message'			=>	"Phone number is too long"
				)
			),
			'address' => array(
				'notEmpty' => array(
					'rule'				=> 'notEmpty',
					'message' 			=> "Please insert address"
				),
				'minLength' => array(
					'rule' 				=>	array("minLength",6),
					'message'			=>	"Address is too short"
				),
				'maxLength' => array(
					'rule' 				=>	array("maxLength",2000),
					'message'			=>	"Address is too long"
				)
			)
		);
	}

	function ValidateEditBA()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'id' => array(
				'notEmpty' => array(
					'rule'		=>	"notEmpty",
					'message'	=>	"Your profile not found"
				),
				'IsExists' => array(
					'rule' 		=>	"IsExists",
					'message'	=>	"Your profile not found"
				),
				'IsActive' => array(
					'rule' 		=>	"IsActive",
					'message'	=>	"Your account has been block"
				)
			),
			'name' => array(
				'notEmpty' => array(
					'rule'		=>	"notEmpty",
					'message'	=>	"Please insert name"
				),
				'maxLength' => array(
					'rule' 		=>	array("maxLength",80),
					'message'	=>	"Name is too long"
				),
				'minLength' => array(
					'rule' 		=>	array("minLength",3),
					'message'	=>	"Name is too short"
				)
			),
			'email' => array(
				'notEmpty' => array(
					'rule'			=>	"notEmpty",
					'message'		=>	"Please insert email"
				),
				'email' => array(
					'rule' 			=>	"email",
					'message'		=>	"Email format is wrong"
				),
				'isEmailExistEdit'	=> array(
					'rule'  		=>  'isEmailExistEdit',
					'message' 		=>	"Email already exists, please put another email"
				)
			)
		);
	}

	function ValidateLogin()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'email' => array(
				'notEmpty' => array(
					'rule'			=>	"notEmpty",
					'message'		=>	"Please insert your email"
				),
				'email' => array(
					'rule' 			=>	"email",
					'message'		=>	"Your email format is wrong"
				),
				'IsEmailExists' => array(
					'rule' 			=> "IsEmailExists",
					'message' 		=> "Sorry your email has not registered. Please contact your administrator to register your email"
				)
			),
			'password' => array(
				'notEmpty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> "Please insert your password"
				),
				'CheckPassword' => array(
					'rule'			=> 'CheckPassword',
					'message' 		=> "Your password is wrong"
				)
			)
		);
	}


	function ValidateForgotPassword()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'email' => array(
				'notEmpty' => array(
					'rule'		=>	"notEmpty",
					'message'	=>	__("Masukkan alamat email Anda")
				),
				'email' => array(
					'rule' 		=>	"email",
					'message'	=>	__("Format email Anda salah")
				),
				'isEmailExist'		=> array(
					'rule'  		=>  'isEmailExist',
					'message' 		=>__( 'Maaf Anda belum terdaftar di layanan kami, silahkan Anda registrasi terlebih dahulu')
				)
			)
		);
	}



	function CheckPassword()
	{
		App::Import("Component","General");
		$GENERAL	=	new GeneralComponent();

		$email		=	strtolower($this->data[$this->name]['email']);
		$password	=	$GENERAL->my_encrypt($this->data[$this->name]['password']);
		$find		=	$this->find('first',array(
							'conditions'	=>	array(
								'User.email'			=>	$email,
								'User.password'			=>	$password,
								'User.status'			=>	"1"
							),
							'order'	=>	array('User.id DESC')
						));

		if(!empty($find))
		{
			return true;
		}
		return false;
	}

	public function BindDefault($reset	=	true)
	{
		$this->bindModel(array(
			"belongsTo"	=>	array(
				"UserType"
			),
			"hasOne"	=>	array(
				"Image"	=>	array(
					"className"	=>	"Content",
					"foreignKey"	=>	"model_id",
					"conditions"	=>	array(
						"Image.model"	=>	$this->name,
						"Image.type"	=>	"small"
					)
				)
			)
		),$reset);
	}

	public function CheckSchedule()
	{
		$email			=	strtolower($this->data[$this->name]['email']);

		$Schedule		=	ClassRegistry::Init("Schedule");
		$ScheduleBa		=	ClassRegistry::Init("ScheduleBa");
		$ScheduleBa->BindDefault(false);

		$userData		=	$this->find("first",array(
								"conditions"	=>	array(
									"{$this->name}.email"	=>	$email
								)
							));

		if(!empty($email))
		{
			$user_id		=	$userData[$this->name]["id"];
			$user_type_id	=	$userData[$this->name]["user_type_id"];

			if($user_type_id == "2")
			{
				$findSchedule	=	$Schedule->find('first',array(
										"conditions"	=>	array(
											"Schedule.tl_id"	=>	$user_id,
											"NOW() BETWEEN Schedule.start_date AND Schedule.end_date"
										)
									));
			}
			elseif($user_type_id == "1")
			{
				$findSchedule	=	$ScheduleBa->find('first',array(
										"conditions"	=>	array(
											"ScheduleBa.ba_id"	=>	$user_id,
											"NOW() BETWEEN Schedule.start_date AND Schedule.end_date"
										)
									));
			}

			if(empty($findSchedule))
			{
				return "Sorry ".$userData[$this->name]["name"].", you dont have schedule to day!";
			}
			else
			{
				return true;
			}
		}
		return true;
	}

	public function BindImageContent($reset	=	true)
	{
		$this->bindModel(array(
			"hasOne"	=>	array(
				"Image"	=>	array(
					"className"	=>	"Content",
					"foreignKey"	=>	"model_id",
					"conditions"	=>	array(
						"Image.model"	=>	$this->name,
						"Image.type"	=>	"small"
					)
				)
			)
		),$reset);
	}

	public function VerifyCode($fields = array())
	{
		$ValidationCode	=	ClassRegistry::Init("ValidationCode");
		foreach($fields as $k => $v)
		{
			$code		=	$this->data[$this->name]["code"];
			$email		=	$this->data[$this->name]["email"];
			$check		=	$ValidationCode->find('first',array(
								"conditions"	=>	array(
									"ValidationCode.email"		=>	$email,
									"ValidationCode.code"		=>	$code
								)
							));

			return !empty($check);
		}
		return false;
	}

	public function IsCodeExpired($fields = array())
	{

		$ValidationCode	=	ClassRegistry::Init("ValidationCode");
		foreach($fields as $k => $v)
		{
			$code		=	$this->data[$this->name]["code"];
			$email		=	$this->data[$this->name]["email"];
			$check		=	$ValidationCode->find('first',array(
								"conditions"	=>	array(
									"ValidationCode.email"		=>	$email,
									"ValidationCode.code"		=>	$code
								)
							));

			if(!empty($check))
				return (strtotime($check['ValidationCode']['expired']) >= time());
		}
		return false;
	}

	public function IsEmailExists($fields = array())
	{
		foreach($fields as $k => $v)
		{
			$check	=	$this->findByEmail($v);
			return !empty($check);
		}
		return false;
	}

	public function IsEmailHasActivated($fields = array())
	{
		foreach($fields as $k => $v)
		{
			$check	=	$this->findByEmail($v);
			return ($check[$this->name]['status'] == 0);
		}
		return false;
	}

	public function EmailHasNotActivated($fields = array())
	{
		foreach($fields as $k => $v)
		{
			$check	=	$this->findByEmail($v);
			return ($check[$this->name]['status'] == 1);
		}
		return false;
	}

	function getValidation($email) {

        $VALIDATIONS = ClassRegistry::Init("ValidationCode");

        //FIND FIRST
        $find = $VALIDATIONS->find('first', array(
                    'conditions' => array(
                        'ValidationCode.email' => $email
                    )
                ));

        $validation =	$this->rand_number(5);
        $expired 	=	date("Y-m-d h:i:s", mktime(date("h"), date("i"), date("s"), date("m"), date("d") + 30, date("Y")));

        if ($find == false) {
            $save = $VALIDATIONS->saveAll(
                            array(
                                'code' 		=> $validation,
                                'email' 	=> $email,
                                'expired' 	=> $expired
                            ),
                            array(
								"validate"	=>	false
							)
            );
        } else {
            if ($find["ValidationCode"]['expired'] < date("Y-m-d H:i:s")) {
                $update = $VALIDATIONS->updateAll(
                                array(
                                    'code' 		=> "'" . $validation . "'",
                                    'expired'	=> "'" . $expired . "'"
                                ),
                                array(
                                    "ValidationCode.email" => $find["ValidationCode"]["email"]
                                )
                );
            } else {
                return $find["ValidationCode"]["code"];
            }
        }
        return $validation;
    }

	function ValidateChangePassword()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'email' => array(
				'notEmpty' => array(
					'rule'		=>	"notEmpty",
					'message'	=>	"Please insert your email"
				),
				'email' => array(
					'rule' 		=>	"email",
					'message'	=>	"Your email format is wrong"
				)
			),
			'password' => array(
				'notEmpty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> 'Please insert your password'
				),
				'CheckPassword' => array(
					'rule'			=> 'CheckPassword',
					'message' 		=> 'Your password is wrong'
				)
			),
			'new_password' => array(
				'notEmpty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> 'Please insert your new password'
				),
				'minLength' => array(
					'rule' 		=>	array("minLength",8),
					'message'	=>	"Your new password is too sort"
				),
				'maxLength' => array(
					'rule' 		=>	array("maxLength",80),
					'message'	=>	"Your new password is too long"
				)
			),
			'retype_password' => array(
				'notEmpty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> 'Please retype your new password'
				),
				'equalToField' => array(
					'rule'			=> array('equalToField','new_password'),
					'message' 		=> 'Your password is not same'
				)
			)
		);
	}

	function rand_string( $length ) {
		$chars	=	"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$str	=	"";

		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $str;
	}

	function rand_number( $length ) {
		$chars	=	"0123456789";
		$str	=	"";

		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $str;
	}
	public function notEmptyImage($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			if(empty($value['name']))
			{
				return false;
			}
		}
		return true;
	}
	public function IsExists($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$data	=	$this->findById($value);
			if(!empty($data)) return true;
		}
		return false;
	}

	public function IsActive($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$data	=	$this->find("first",array(
							"conditions"	=>	array(
								"{$this->name}.id"		=>	$value,
								"{$this->name}.status"	=>	"1"
							)
						));
			if(!empty($data)) return true;
		}
		return false;
	}

	public function isEmailExist($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$data	=	$this->findByEmail($value);
			if(!empty($data)) return true;
		}
		return false;
	}

	public function isEmailExistEdit($fields = array())
	{
		$ID	=	$this->data[$this->name]["id"];
		foreach($fields as $key=>$value)
		{
			$data	=	$this->find("first",array(
							"conditions"	=>	array(
								"{$this->name}.email"	=>	$value,
								"NOT"					=>	array(
									"{$this->name}.id"		=>	$ID
								)
							)
						));
			return empty($data);
		}
		return false;
	}

	public function size( $field=array(), $aloowedsize)
    {
		foreach( $field as $key => $value ){
            $size = intval($value['size']);
            if($size > $aloowedsize) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }
	public function validateName($file=array(),$ext=array())
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

	public function imagewidth($field=array(), $allowwidth=0)
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

	public function imageheight($field=array(), $allowheight=0)
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

	function equalToField($field, $equalToField)
	{
		foreach($field as $key => $value)
		{
			return($value === $this->data[$this->name][$equalToField]);
		}
	}

	function NoSpecialChar($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$regex	=	"/^[a-zA-Z0-9]{1,}$/";
			$out	=	preg_match($regex,$value);
			return $out;
		}
		return false;
	}

	function NoSpace($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$regex	=	"/^[a-zA-Z0-9\S]{1,}$/";
			$out	=	preg_match($regex,$value);
			return $out;
		}
		return false;
	}

	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'jenis_kelamin'		=>	'IF(('.$this->name.'.gender=\'female\'),\'Wanita\',\'Pria\')',
			'SStatus'			=>	'IF(('.$this->name.'.status=\'1\'),\'Active\',\'Not Active\')',
			'codeName' 			=>	'CONCAT('.$this->name.'.code, " - ", '.$this->name.'.name)'
		);
	}
	function MustIncludeOneNumber($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$regex	=	"#[0-9]+#";
			$out	=	preg_match($regex,$value);
			return $out;
		}
		return false;
	}

	function MustIncludeOneLetter($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$regex	=	"#[a-zA-Z]+#";
			$out	=	preg_match($regex,$value);
			return $out;
		}
		return false;
	}
}
?>
