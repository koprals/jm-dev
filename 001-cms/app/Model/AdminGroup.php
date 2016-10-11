<?php
App::uses('Sanitize','Utility');
class AdminGroup extends AppModel
{
	public function BindDefault($reset	=	true)
	{
		$this->bindModel(array(
			"hasOne"	=>	array(
				"Aro"	=>	array(
					"foreignKey"	=>	false,
					"conditions"	=>	array(
						"Aro.model"			=>	"AdminGroup",
						"Aro.foreign_key = AdminGroup.id",
					)
				)
			)
		),$reset);
	}
	
	public function beforeSave($options = array())
	{
		if(!empty($this->data))
		{
			foreach($this->data[$this->name] as $key => $name)
			{
				if(!is_array($this->data[$this->name][$key]))
					$this->data[$this->name][$key]		=	trim($this->data[$this->name][$key]);
				
				if($key == "name")
				{
					$this->data[$this->name][$key]	=	Sanitize::html($this->data[$this->name][$key]);
				}
				if($key == "description")
				{
					$this->data[$this->name][$key]	=	Sanitize::html($this->data[$this->name][$key]);
				}
			}
		}
		return true;
	}
	
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val[$this->name]['description'])) {
				$results[$key][$this->name]['description'] = $this->DecodeBBCode($val[$this->name]['description']);
			}
		}
		return $results;
	}
	
	function DecodeBBCode($string)
	{
		App::import('Vendor','Decoda' ,array('file'=>'decoda/Decoda.php'));
		$code 				= 	new Decoda();
		$code->addFilter(new DefaultFilter());
		$code->addFilter(new TextFilter());
		$code->addFilter(new UrlFilter());
		$code->addFilter(new ListFilter());
		$code->addFilter(new ImageFilter());
		$code->addHook(new EmoticonHook());
		$code->reset($string);
		return $code->parse();
	}
	
	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'SStatus'		=> 'IF(('.$this->name.'.status=\'1\'),\'Active\',\'Hide\')',
		);
	}
	
	function ValidateAdd()
	{
		App::uses('CakeNumber', 'Utility');
		$this->validate 	= array(
			'name' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please enter Categgory Name"
				),
				"UniqueName" => array(
					'rule' => "UniqueName",
					'message' => "This name already exists"
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
			'name' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert admin name"
				),
				"UniqueNameEdit" => array(
					'rule' => "UniqueNameEdit",
					'message' => "This name already exists"
				)
			)
		);
	}
	
	function UniqueName($fields = array())
	{
		foreach($fields as $key=>$value)
		{
			$data	=	$this->find("first",array(
							"conditions"	=>	array(
								"LOWER({$this->name}.name)"	=>	strtolower($value)
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
								"LOWER({$this->name}.name)"	=>	strtolower($value),
								"NOT"						=>	array(
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
}
?>