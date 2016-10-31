<?php
class InvitedLog extends AppModel
{
	var $name	=	"InvitedLog";
	
	
	function AlreadyRegister($email,$vendor)
	{
		$USERS		=	ClassRegistry::Init("User");
		$EXTID		=	ClassRegistry::Init("Extid");
		
		$user		=	$USERS->findByEmail($email);
		$ext_id		=	$EXTID->find('first',array(
							'conditions'	=>	array(
								'Extid.extID'	=>	$email
							)
						));
		$invited	=	$this->find('first',array(
							'conditions'	=>	array(
								'InvitedLog.ext_id'	=>	$email,
								'InvitedLog.vendor'	=>	$vendor
							)
						));
		
		if($user==false && $ext_id==false && $invited==false)
		{
			return false;
		}
		return true;
	}
	
	function SaveLog($ext_id,$vendor,$user_id=null)
	{
		$find	=	$this->find('first',array(
						'conditions'	=>	array(
							'ext_id'	=>	$ext_id,
							'vendor'	=>	strtolower($vendor)
						)
					));
		
		if($find == false)
		{
			$save	=	$this->saveAll(array(
							'ext_id'	=>	$ext_id,
							'vendor'	=>	strtolower($vendor),
							'user_id'	=>	$user_id
						));
		}
		return $save;
	}
	
}
?>