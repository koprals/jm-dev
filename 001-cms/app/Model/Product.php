<?php
App::uses('Sanitize','Utility');
class Product extends AppModel
{
	var $dataOld;
	var $name 		=	"Product";
	var $inserted_ids 	=	array();

	public function BindList($reset=true)
	{
		$this->bindModel(array(
			"belongsTo" => array(
				"Category", "User", "Province", "City", "Stnk", "Bpkb", "ProductStatus"
			)
		), $reset);
	}

	public $validate = array(
		"name" => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'name not empty'
			),
			'email' => array(
				'rule' => 'email',
				'message' => 'please enter valid email'
			),
			'mobile_phone' => array(
				'rule' => 'notEmpty',
				'message' => 'mobile_phone not empty'
			)
		),
		"city_id" => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'city_id not empty'
			),
		),
		"gender" => array(
			'notEmpty' => array(
				'rule' 		=> 'notEmpty',
				'message' 	=> 'gender not empty'
			),
		),
		"nationality_id" => array(
			'notEmpty' 	=> array(
				'rule'		=> 'notEmpty',
				'message'	=> 'nationality_id not empty'
			),
		),
		"occupation_id" => array(
			'notEmpty'	=> array(
				'rule'		=> 'notEmpty',
				'message'	=> 'occupation_id not empty'
			),
		),
		"age_range_id" => array(
			'notEmpty' => array(
				'rule' 		=> 'notEmpty',
				'message' 	=> 'age_range_id not empty'
			),
		),
		"current_cigarette_id" => array(
			'notEmpty' => array(
				'rule' 		=> 'notEmpty',
				'message' 	=> 'current_cigarette_id not empty'
			),
		),
		"generation_id" => array(
			'notEmpty' => array(
				'rule' 		=> 'notEmpty',
				'message' 	=> 'generation_id not empty'
			),
		),
	);

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

				if($key == "email")
				{
					$this->data[$this->name][$key]			=	strtolower(trim($this->data[$this->name][$key]));
				}
			}
		}
		if($this->id)
		{
			$this->dataOld	=	$this->findById($this->id);
		}
		return true;
	}

	 function setExistingId()
	 {
        if (!$this->id)
		{
            $data = $this->data[$this->alias];
            if (isset($data['email']))
			{
                //CHECK EMAIL
				$email		=	$this->data[$this->alias]["email"];
				if($email != "no_email@jti.com")
				{
					$check		=	$this->find("first",array(
										"conditions"	=>	array(
											"{$this->name}.email"	=>	$email
										)
									));

					if(!empty($check))
					{
						$this->id		=	$check[$this->name]["id"];
						$this->__exists = true;
					}
				}
            }
        }
    }


	public function afterSave($created,$options = array())
	{
		if($created)
		{
			$this->inserted_ids[] = $this->getInsertID();
		}
		return true;
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

	function ValidateAdd()
	{

	}

	function BindDefault($reset	=	true)
	{

	}

	function UnBindDefault($reset	=	true)
	{
	}

	public function BindPanel($reset	=	true)
	{
		$this->bindModel(array(
			"hasMany"	=>	array(
				"PanelContent"	=>	array(
					"foreignKey"	=>	"model_id",
					"conditions"	=>	array(
						"PanelContent.model"		=>	$this->name
					)
				)
			)
		),$reset);
	}

	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'SStatus'		  =>  "IF((".$this->name.".status='0'), 'Hide', IF((".$this->name.".status='1'), 'Publish', 'Draft'))",
      'CConditions' =>  'IF(('.$this->name.'.status=\'2\'),\'Baru\',\'Bekas\')',
      'DDataTypes'  =>  'IF(('.$this->name.'.status=\'1\'),\'Profile\',\'Company\')'
		);
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

	function notEmptyLength($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			return (strlen($value) > 0);
		}
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

	function paginateCount($conditions = null, $recursive = 0, $extra = array())
	{
		$parameters 			=	compact('conditions');
		$parameters["fields"]	=	array('Product.id');
	    $this->recursive 		=	$recursive;

		if (isset($extra['group']))
		{
			$paginationcount = $this->find('all', array_merge($parameters, $extra));
			$paginationcount = count($paginationcount);
		}
		else
		{
			$paginationcount = $this->find('count', array_merge($parameters, $extra));
		}
		return $paginationcount;
	}
}
?>
