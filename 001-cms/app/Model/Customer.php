<?php

App::uses('Sanitize', 'Utility');
class Customer extends AppModel
{
	public function beforeSave($options = array())
	{
		if(strlen($this->data[$this->name]['device_id']) == 0)
		{
		    unset($this->data[$this->name]['device_id']);
		}

		if(strlen($this->data[$this->name]['device_model']) == 0)
		{
		    unset($this->data[$this->name]['device_model']);
		}

		if(strlen($this->data[$this->name]['device_date']) == 0)
		{
		    unset($this->data[$this->name]['device_date']);
		}

		if(strlen($this->data[$this->name]['instagram']) == 0)
		{
		    unset($this->data[$this->name]['instagram']);
		}

		if(isset($this->data[$this->name]['email']))
		{
			$this->data[$this->name]['email']	=	strtolower(trim($this->data[$this->name]['email']));
			$checkEmail	=	$this->find("first",array(
							"conditions"	=>	array(
								"{$this->name}.email"	=>	$this->data[$this->name]['email'],
								"{$this->name}.status"	=>	"1",
								"NOT"	=>	array(
									"{$this->name}.id"		=>	$this->data[$this->name]['id']
								)

							)
						));

			if(!empty($checkEmail))
			{
				$this->data[$this->name]['is_valid']	=	"0";
			}
			else
			{
				$this->data[$this->name]['is_valid']	=	"1";
			}
		}

		if(isset($this->data[$this->name]['name']))
		{
			$this->data[$this->name]['name']	=	Sanitize::html(trim($this->data[$this->name]['name']));
		}
		if(isset($this->data[$this->name]['instagram']))
		{
			$this->data[$this->name]['instagram']	=	Sanitize::html(trim($this->data[$this->name]['instagram']));
		}

		/*if(isset($this->data[$this->name]['id']))
		{
			$checkId	=	$this->find("first",array(
								"conditions"	=>	array(
									"{$this->name}.id"	=>	$this->data[$this->name]['id']
								)
							));

			if(!empty($checkId))
			{
				return false;
			}
		}*/
		return true;
	}


	function ValidateAdd()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'user_id' => array(
				'ValidateBa'
			),
			'name' => array(
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message' 	=> "Please insert customer name"
				),
				'minLength' => array(
					'rule' 			=>	array("minLength","3"),
					'message' 		=>	"Customer name is too short",
					"allowEmpty"	=>	true
				),
				'maxLength' => array(
					'rule' 			=>	array("maxLength","100"),
					'message' 		=>	"Customer name is too long"
				),
				'scfName' => array(
					'rule' 			=>	"scfName",
					'message' 		=>	"[A-Za-z] space, dot(.) and comas(,) only, do not put number or other character string."
				)

			),
			'email' => array(
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message' 	=> "Please insert customer email"
				),
				'email' => array(
					'rule' 		=> "email",
					'message' 	=> "Customer email is not valid, please insert valid format email"
				)
			),
			'mobile_phone' => array(
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message' 	=> "Please insert customer phone"
				),
				'minLength' => array(
					'rule' 			=>	array("minLength","6"),
					'message' 		=>	"Phone number is too short"
				),
				'maxLength' => array(
					'rule' 			=>	array("maxLength","17"),
					'message' 		=>	"Phone number is too long"
				),
				'scfPhone' => array(
					'rule' 			=>	"scfPhone",
					'message' 		=>	"Please insert numeric only 6-17 characters"
				)
			),
			'instagram' => array(
				'minLength' => array(
					'rule' 			=>	array("minLength","3"),
					'message' 		=>	"Instagram account name is too short",
					"allowEmpty"	=>	true
				),
				'maxLength' => array(
					'rule' 			=>	array("maxLength","100"),
					'message' 		=>	"Instagram account name is too long",
					"allowEmpty"	=>	true
				)
			),
			'gender' => array(
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message' 	=> "Please select customer gender"
				),
				'ValidateGender' => array(
					'rule' 		=> "ValidateGender",
					'message' 	=> "Gender is not define !, please select male or female only"
				)
			),
			'cigarette_brand_id' => array(
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message' 	=> "Please select customer current cigarette"
				),
				'ValidateCigaretteBrand' => array(
					'rule' 		=> "ValidateCigaretteBrand",
					'message' 	=> "Cigarette not available"
				)
			),
			'cigarette_brand_product_id' => array(
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message' 	=> "Please select customer cigarette product"
				),
				'ValidateCigaretteBrandProduct' => array(
					'rule' 		=> "ValidateCigaretteBrandProduct",
					'message' 	=> "Cigarette product not available"
				)
			)
		);
	}

	function ValidateGender()
	{
		$arrayGender	=	array("male","female");
		$gender			=	strtolower($this->data[$this->name]["gender"]);
		return in_array($gender,$arrayGender);
	}

	function ValidateCigaretteBrand()
	{
		$CigaretteBrand				=	ClassRegistry::Init("CigaretteBrand");
		$cigarette_brand_id			=	$this->data[$this->name]["cigarette_brand_id"];

		$checkCigarette				=	$CigaretteBrand->find("first",array(
											"conditions"	=>	array(
												"CigaretteBrand.id"	 	=> $cigarette_brand_id,
												"CigaretteBrand.status" => "1"
											)
										));
		return !empty($checkCigarette);

	}

	function ValidateCigaretteBrandProduct()
	{
		$CigaretteBrandProduct		=	ClassRegistry::Init("CigaretteBrandProduct");
		$cigarette_brand_id			=	$this->data[$this->name]["cigarette_brand_id"];
		$cigarette_brand_product_id	=	$this->data[$this->name]["cigarette_brand_product_id"];

		$checkCigarette				=	$CigaretteBrandProduct->find("first",array(
											"conditions"	=>	array(
												"CigaretteBrandProduct.id"	 				=> $cigarette_brand_product_id,
												"CigaretteBrandProduct.status" 				=> "1",
												"CigaretteBrandProduct.cigarette_brand_id" 	=> $cigarette_brand_id
											)
										));
		return !empty($checkCigarette);

	}

	function ValidateBa()
	{
		$user_id	=	$this->data[$this->name]["user_id"];
		$User 		=	ClassRegistry::Init("User");

		$checkBa	=	$User->find("first",array(
							"conditions"	=>	array(
								"User.id"	=>	$user_id
							)
						));

		if(empty($checkBa))
		{
			return "Sorry your login account not found!, please contact your administrator if this is wrong.";
		}
		else if($checkBa["User"]["status"] == "0")
		{
			return "Sorry your account no longer active";
		}
		return true;
	}

	public function BindDefault($reset	=	true)
	{
		$this->bindModel(array(
			"belongsTo"	=>	array(
				"CigaretteBrand",
				"CigaretteBrandProduct",
				"User"
			)
		),$reset);
	}

	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'SStatus'		=> 'IF(('.$this->name.'.status=\'1\'),\'Active\',\'Hide\')',
			'SValid'		=> 'IF(('.$this->name.'.is_valid=\'1\'),\'Valid\',\'Not Valid\')',
		);
	}
}
?>
