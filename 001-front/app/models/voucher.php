<?php 
class Voucher extends Model
{
	var $name	=	"Voucher";
	
	
	function ValidateBeliPoin()
	{
		$this->validate 	= array(
			'user_id' => array(
				'MemberActive' => array(
					'rule' => "MemberActive",
					'message' => 'Maaf data user anda tidak kami temukan.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Maaf session login anda telah habis, silahkan login kembali.'	
				)
			),
			'id' => array(
				'IsExists' => array(
					'rule' => "IsExists",
					'message' => 'Silahkan voucher yang anda pilih belum tersedia.'	
				),
				'notEmpty' => array(
					'rule' => "notEmpty",
					'message' => 'Silahkan pilih voucher yang disediakan.'	
				)
			)
		);
	}
	
	
	function IsExists($fields	=	array())
	{
		foreach($fields as $k => $v)
		{
			$find	=	$this->find("first",array(
				"conditions"	=>	array(
					"{$this->name}.status"	=>	"1",
					"{$this->name}.id"		=>	$v
				)
			));
			
			if(empty($find))
			return false;
		}
		return true;
	}
	
	function MemberActive($fields	=	array())
	{
		$User	=	ClassRegistry::Init("User");
		foreach($fields as $k => $v)
		{
			$fUser	=	$User->find("first",array(
				"conditions"	=>	array(
					"User.userstatus_id"	=>	"1",
					"User.id"				=>	$v
				)
			));
			if(empty($fUser))
			return false;
		}
		return true;
	}
	
}

?>