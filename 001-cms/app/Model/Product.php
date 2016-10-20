<?php
App::uses('Sanitize','Utility');
class Product extends AppModel
{
	var $dataOld;
	var $name 		=	"Product";

	public function BindList($reset=true)
	{
		$this->bindModel(array(
			"belongsTo" => array(
				"Category", "User", "Province", "Stnk", "Bpkb", "Productstatus"
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

	public function afterSave($created,$options = array())
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

	function BindDefault($reset	=	true)
	{

	}

	function UnBindDefault($reset	=	true)
	{

	}

	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			//'SStatus'		  =>  "IF((".$this->name.".status='0'), 'Hide', IF((".$this->name.".status='1'), 'Publish', 'Draft'))",
      'CConditions' =>  'IF(('.$this->name.'.condition_id=\'1\'),\'Baru\',\'Bekas\')',
      'SSold'  =>  'IF(('.$this->name.'.sold=\'1\'),\'Sudah Terjual\',\'Belum Terjual\')'
		);
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
}
?>
