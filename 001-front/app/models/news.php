<?php
class News extends AppModel
{
	var $useTable	=	"news";
	
	function VirtualFieldActivated()
	{
		$this->virtualFields = array(
			'SStatus'	=> 'IF((News.status=\'1\'),\'Publish\',\'Hide\')',
			'ShortDesc'	=> 'SUBSTRING(News.description,1,70)',
		);
	}
	
	function InititateValidate()
	{
		$Number 		=	new NumberHelper();
		
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
			'title' => array(
				'maxLength' => array(
					'rule' => array('maxLength',100),
					'message' => 'Your title is too long, maximum character is 100 characters'
				),
				'minLength' => array(
					'rule' => array('minLength',10),
					'message' => 'Your title is too short, please insert at least 10 characters.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Please insert news title.'	
				)
			),
			'description' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Please insert news description.'	
				)
			),
			'photo' => array(
				'size' => array(
					'rule' => array('size',$settings['max_photo_upload']),
					'message' => 'File is to big, please upload less than '.$Number->toReadableSize($settings['max_photo_upload']).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Only (*.gif,*.jpeg,*.jpg,*.png) are allowed.'	
				),
				'notEmpty' => array(
					'rule' => "notEmptyFileName",
					'message' => 'Please select news photo.'	
				)
			)
		);
	}
	
	
	function InititateValidateEdit()
	{
		$Number 		=	new NumberHelper();
		
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
			'id' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'News ID not found.'	
				)
			),
			'title' => array(
				'maxLength' => array(
					'rule' => array('maxLength',100),
					'message' => 'Your title is too long, maximum character is 100 characters'
				),
				'minLength' => array(
					'rule' => array('minLength',10),
					'message' => 'Your title is too short, please insert at least 10 characters.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Please insert news title.'	
				)
			),
			'description' => array(
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Please insert news description.'	
				)
			),
			'photo' => array(
				'size' => array(
					'rule' => array('size',$settings['max_photo_upload']),
					'message' => 'File is to big, please upload less than '.$Number->toReadableSize($settings['max_photo_upload']).'.'	
				),
				'extension' => array(
					'rule' => array('validateName', array('gif','jpeg','jpg','png')),
					'message' => 'Only (*.gif,*.jpeg,*.jpg,*.png) are allowed.'	
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
}
?>