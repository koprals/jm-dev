<?php
class Admin extends AppModel
{
	var $aco_id;
	public function beforeSave($options = array())
	{
		App::Import("Components","General");
		$General		=	new GeneralComponent();
		if (!empty($this->data[$this->name]['password'])) {
		    $this->data[$this->name]['password'] 	=	$General->my_encrypt($this->data[$this->name]['password']);
			$this->data[$this->name]['username']	=	strtolower($this->data[$this->name]['username']);
		}
		
		
		return true;
	}
	
	function afterSave($created,$options = array())
	{
		if($created)
		{
			$Aro	=	ClassRegistry::Init("MyAro");
			$add	=	$Aro->updateAll(
							array(
								"total_admin"	=>	"total_admin + 1"
							),
							array(
								"MyAro.id"		=>	$this->data[$this->name]['aro_id']
							)
						);
		}
	}
	
	public function afterDelete()
	{
		//DELETE IMAGE CONTENT
		App::import('Component','General');
		$General		=	new GeneralComponent();
		$General->DeleteContent($this->id,"Admin");
		
		
		$Aro	=	ClassRegistry::Init("MyAro");
		$add	=	$Aro->updateAll(
						array(
							"total_admin"	=>	"total_admin - 1"
						),
						array(
							"MyAro.id"		=>	$this->aco_id
						)
					);
		
	}
	
	public function beforeDelete($cascade = false)
	{
		//GET DETAIL
		$detail				=	$this->find("first",array(
									"conditions"	=>	array(
										"{$this->name}.id"	=>	$this->id
									)
								));
		$this->aco_id		=	$detail[$this->name]["aro_id"];
	}
	
	
	public function afterFind($results, $primary = false) {
		App::Import("Components","General");
		$General		=	new GeneralComponent();
		
		foreach ($results as $key => $val)
		{
			if(isset($results[$key][$this->name]['password']))
			{
				$results[$key][$this->name]['password'] 	=	$General->my_decrypt($val[$this->name]['password']);
			}
		}
		return $results;
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
						"Image.type"	=>	"original"
					)
				)
			)
		),$reset);
	}
	
	public function BindDefault($reset	=	true)
	{
		$this->bindModel(array(
			"belongsTo"	=>	array(
				"MyAro"	=>	array(
					"foreignKey"	=>	"aro_id"
				)
			)
		),$reset);
	}
	
	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'SStatus'		=> 'IF(('.$this->name.'.status=\'1\'),\'Active\',\'Hide\')',
		);
	}
	
	function ValidateAdmin()
	{
		$this->validate 	= array(
			'username' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Please insert your username.'	
				)
			),
			'password' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Please insert your password.'	
				),
				'CheckPassword' => array(
					'rule' 		=> "CheckPassword",
					'message' 	=> 'Username or password is wrong!.'	
				)
			)
		);
	}
	
	function ValidateAdd()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'username' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please enter username"
				),
				'minLength' => array(
					'rule' 		=> array("minLength","4"),
					'message'	=> "Please insert less than 4 characters"
				),
				'maxLength' => array(
					'rule' 		=> array("maxLength","20"),
					'message'	=> "Please insert greater or equal than 20 characters"
				),
				"UniqueName" => array(
					'rule' => "UniqueName",
					'message' => "This name already exists"
				),
				"NoSpcaeAndOtherCharacter"	=> array(
					'rule'		=> "NoSpcaeAndOtherCharacter",
					'message'	=> "Not allowed character, please insert A-Z,a-z,0-9,_ and no space"
				),
			),
			'password' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please enter password"
				),
				'minLength' => array(
					'rule' 		=> array("minLength","4"),
					'message'	=> "Please insert less than 4 characters"
				),
				'maxLength' => array(
					'rule' 		=> array("maxLength","20"),
					'message'	=> "Please insert greater or equal than 20 characters"
				),
				"NoSpcaeAndOtherCharacter"	=> array(
					'rule'		=> "NoSpcaeAndOtherCharacter",
					'message'	=> "Not allowed character, please insert A-Z,a-z,0-9,_ and no space"
				),
			),
			'aro_id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please select admin group"
				)
			),
			'fullname' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert fullname"
				),
				'minLength' => array(
					'rule' 		=> array("minLength","3"),
					'message'	=> "Please insert less than 3 characters"
				),
				'maxLength' => array(
					'rule' 		=> array("maxLength","20"),
					'message'	=> "Please insert greater or equal than 20 characters"
				)
			),
			'images' => array(
				'notEmptyImage' => array(
					'rule'		=> "notEmptyImage",
					'message'	=> "Please upload photos"
				),
				'imagewidth'	=> array(
					'rule' 		=> array('imagewidth',300),
					'message' 	=> 'Please upload image with minimum width is 300px'	
				),
				'size' => array(
					'rule' 		=> array('size',1048576),
					'message' 	=> 'Your image size is too big, please upload less than '.CakeNumber::toReadableSize(1048576).'.'
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Only (*.gif,*.jpeg,*.jpg,*.png) are allowed.'	
				)
			)
		);
	}
	
	
	function ValidateEdit()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			"id"	=>	array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Sorry we cannot find your ID.'	
				),
				'IsExists' => array(
					'rule' => "IsExists",
					'message' => 'Sorry we cannot find your details data.'	
				)
			),
			'username' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please enter username"
				),
				'minLength' => array(
					'rule' 		=> array("minLength","4"),
					'message'	=> "Please insert less than 4 characters"
				),
				'maxLength' => array(
					'rule' 		=> array("maxLength","20"),
					'message'	=> "Please insert greater or equal than 20 characters"
				),
				"UniqueNameEdit" => array(
					'rule' => "UniqueNameEdit",
					'message' => "This name already exists"
				),
				"NoSpcaeAndOtherCharacter"	=> array(
					'rule'		=> "NoSpcaeAndOtherCharacter",
					'message'	=> "Not allowed character, please insert A-Z,a-z,0-9,_ and no space"
				),
			),
			'password' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please enter password"
				),
				'minLength' => array(
					'rule' 		=> array("minLength","4"),
					'message'	=> "Please insert less than 4 characters"
				),
				'maxLength' => array(
					'rule' 		=> array("maxLength","20"),
					'message'	=> "Please insert greater or equal than 20 characters"
				),
				"NoSpcaeAndOtherCharacter"	=> array(
					'rule'		=> "NoSpcaeAndOtherCharacter",
					'message'	=> "Not allowed character, please insert A-Z,a-z,0-9,_ and no space"
				),
			),
			'aro_id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please select admin group"
				)
			),
			'fullname' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert fullname"
				),
				'minLength' => array(
					'rule' 		=> array("minLength","3"),
					'message'	=> "Please insert less than 3 characters"
				),
				'maxLength' => array(
					'rule' 		=> array("maxLength","20"),
					'message'	=> "Please insert greater or equal than 20 characters"
				)
			),
			'images' => array(
				
				'imagewidth'	=> array(
					'rule' 		=> array('imagewidth',300),
					'message' 	=> 'Please upload image with minimum width is 300px'	
				),
				'size' => array(
					'rule' 		=> array('size',1048576),
					'message' 	=> 'Your image size is too big, please upload less than '.CakeNumber::toReadableSize(1048576).'.'
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Only (*.gif,*.jpeg,*.jpg,*.png) are allowed.'	
				)
			)
		);
	}
	
	function IsAllowed($fields	=	array())
	{
		$this->BindDefault();
		$this->AdminGroup->BindDefault();
		
		App::Import("Components","General");
		$General		=	new GeneralComponent();
		
		$Aco			=	ClassRegistry::Init("Aco");
		$aco			=	$Aco->find("first",array(
								"conditions"	=>	array(
									"LOWER(Aco.alias)"	=>	"login"
								)
							));
		$aco_id			=	$aco["Aco"]["id"];
		
		$ArosAco		=	ClassRegistry::Init("ArosAco");
		
		
		foreach($fields as $k => $v)
		{
			$General	=	new GeneralComponent();
			$username	=	$this->data[$this->name]['username'];
			$password	=	$General->my_encrypt(trim($this->data[$this->name]['password']));
			
			$find		=	$this->find('first',array(
								'conditions'	=>	array(
									'LOWER(Admin.username)'			=>	strtolower($username),
									'Admin.password'				=>	$password
								),
								'order'								=>	array('Admin.id DESC'),
								"recursive"							=>	2
							));
		
			if(!empty($find))
			{
				if($find["Admin"]["status"]=="1")
				{
					$aro_id			=	$find["AdminGroup"]["Aro"]["id"];
					$prev			=	$ArosAco->find("first",array(
											"conditions"	=>	array(
												"aro_id"	=>	$aro_id,
												"aco_id"	=>	$aco_id
											)
										));
					return !empty($prev);
				}
			}
		}
		return false;
	}
	
	
	function CheckPassword()
	{
		App::Import("Components","General");
		$General	=	new GeneralComponent();
		$username	=	$this->data[$this->name]['username'];
		$password	=	$General->my_encrypt($this->data[$this->name]['password']);
		
		$data		=	$this->find('first',array(
							'conditions'	=>	array(
								"username"	=>	$username,
								'password'	=>	$password
							),
							"order"	=>	array("{$this->name}.id ASC")
						));
		if(!empty($data)) return true;
		return false;
	}
	
	function NoSpcaeAndOtherCharacter($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$regex	=	"/^[a-zA-Z0-9_]{1,}$/";
			$out	=	preg_match($regex,$value);
			return $out;
		}
		return false;
	}
	
	function UniqueName($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$data	=	$this->find("first",array(
							"conditions"	=>	array(
								"LOWER({$this->name}.username)"	=>	strtolower($value)
							)
						));
						
			return empty($data);
		}
		return false;
	}
	
	function UniqueNameEdit($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$data	=	$this->find("first",array(
							"conditions"	=>	array(
								"LOWER({$this->name}.username)"	=>	strtolower($value),
								"NOT"							=>	array(
									"{$this->name}.id"			=>	$this->data[$this->name]["id"]
								)
							)
						));
						
			return empty($data);
		}
		return false;
	}
	
	function IsExists($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$data	=	$this->findById($value);
			if(!empty($data)) return true;
		}
		return false;
	}
	
	function size( $field=array(), $aloowedsize) 
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
	
	
	function notEmptyImage($fields = array())
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