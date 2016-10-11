<?php
class Setting extends AppModel
{
	function BindDefault($reset	=	true)
	{
	}
	
	function BindImageBig($reset	=	true)
	{
		$this->bindModel(array(
			"hasOne"	=>	array(
				"Big"	=>	array(
					"className"		=>	"Content",
					"foreignKey"	=>	"model_id",
					"conditions"	=>	array(
						"Big.model"	=>	$this->name,
						"Big.type"	=>	"big"
					)
				)
			)
		),$reset);
	}
	
	function BindImageSmall($reset	=	true)
	{
		$this->bindModel(array(
			"hasOne"	=>	array(
				"Small"	=>	array(
					"className"		=>	"Content",
					"foreignKey"	=>	"model_id",
					"conditions"	=>	array(
						"Small.model"	=>	$this->name,
						"Small.type"	=>	"small"
					)
				)
			)
		),$reset);
	}
	
	function VirtualFieldActivated()
	{
		
	}
	
	function ValidateAdd()
	{
		App::import('Helper', 'Number');
		$Number 		=	new NumberHelper();
		
		$this->validate 	= array(
			'title' => array(
				'maxLength' => array(
					'rule'		=> array('maxLength', 255),
					'message'	=> "Title {$this->name} is too long, please insert less than or equals 255 character."
				),
				'minLength' => array(
					'rule'		=> array('minLength', 3),
					'message'	=> "Title {$this->name} name is too short, please insert greater than or equals 3 character."
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert {$this->name} title."
				)
			),
			'description' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert {$this->name} description."
				)
			),
			'images' => array(
				'imagewidth' => array(
					'rule' => array('imagewidth',666),
					'message' => 'Please upload image with minimum width is 666px'	
				),
				'size' => array(
					'rule' => array('size',5242880),
					'message' => 'Your image size is too big, please upload less than '.$Number->toReadableSize(5242880).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Only (*.gif,*.jpeg,*.jpg,*.png) are allowed.'	
				),
				'notEmptyImage' => array(
					'rule' 			=>	"notEmptyImage",
					'message'		=>	'Please upload {$this->name} images.'
				)
			)
		);
	}
	
	function ValidateEdit()
	{
		App::import('Helper', 'Number');
		$Number 		=	new NumberHelper();
		
		$this->validate 	= array(
			"id"	=>	array(
				'IsExists' => array(
					'rule' => "IsExists",
					'message' => 'Sorry we cannot find your details data.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Sorry we cannot find your ID.'	
				)
			),
			'site_name' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert site name."
				)
			),
			'site_title' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert site title."
				)
			),
			'site_description' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert site description."
				)
			),
			'site_keywords' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert site keywords."
				)
			),
			'facebook_app_id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert facebook app id."
				)
			),
			'facebook_app_secret' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert facebook app secret."
				)
			),
			'twitter_consumer_key' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert twitter consumer key."
				)
			),
			'twitter_consumer_secret' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => "Please insert twitter consumer secret."
				)
			)
		);
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