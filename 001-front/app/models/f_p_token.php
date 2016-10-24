<?php 
class FPToken extends AppModel
{
	var $name		=	"FPToken";
	var $useTable 	=	'forgot_password_tokens';
	
	function GetToken($email,$user_id=null)
	{
		$token	=	$this->rand_string(40);
		$find	=	$this->findByToken($token);
		
		if($find)
		{
			$token = $this->GetToken($email);
		}
		$expired	=	date("Y-m-d H:i:s",mktime(date("H")+1,date("i"),date("s"),date("m"),date("d"),date("Y")));
		$this->save(
			array(
				'email'		=>	$email,
				'token'		=>	$token,
				'expired'	=>	$expired,
				'user_id'	=>	$user_id
			)
		);
		return $token;
	}
	function GetTokenAndroid($email,$user_id=null)
	{
		$token	=	$this->rand_string(8);
		$find	=	$this->findByToken($token);
		
		if($find)
		{
			$token = $this->GetTokenAndroid($email);
		}
		$expired	=	date("Y-m-d H:i:s",mktime(date("H")+1,date("i"),date("s"),date("m"),date("d"),date("Y")));
		$this->save(
			array(
				'email'		=>	$email,
				'token'		=>	$token,
				'expired'	=>	$expired,
				'user_id'	=>	$user_id
			)
		);
		return $token;
	}
	function rand_string( $length ) {
		$chars	=	"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		$str	=	"";
		
		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
		
		return $str;
	}
	
	function FindToken($email,$token,$text="ubah password")
	{
		$find		=	$this->find('first',
						  array(
							  'conditions'	=>	array(
								  'token'		=>	$token,
								  'email'		=>	$email
							  )
						));
		
		$text_status	=	"";
		$validtoken		=	true;
		$data			=	null;
		
		if($find ==	false)
		{
			$text_status = "Maaf kami tidak menemukan kode token yang sesuai.";
			$validtoken		=	false;
		}
		elseif($find['FPToken']['is_validate']==1)
		{
			$data			=	$find['FPToken'];
			$text_status = "Maaf kami tidak menemukan permintaan ".$text." untuk member ini.";
			$validtoken		=	false;
		}
		elseif($find['FPToken']['expired'] < date("Y-m-d H:i:s"))
		{
			$data			=	$find['FPToken'];
			$text_status 	= "Maaf link ini telah expired, silahkan ulangi proses ".$text.".";
			$validtoken		=	false;
		}
		elseif($find['FPToken']['expired'] >= date("Y-m-d H:i:s") && $find['FPToken']['is_validate']==0)
		{
			$data			=	$find['FPToken'];
			$validtoken		=	true;
		}
		else
		{
			$text_status 	= "Maaf kami tidak menemukan kode token yang sesuai.";
			$validtoken		=	false;
		}
		
		return array("status"	=>	$validtoken,"msg"	=>	$text_status,"data"=>$data);
	}
	
	function UpdateToken($token,$status)
	{
		$updt	=	$this->updateAll(
						array(
							"is_validate"	=>	"'$status'"
						),
						array(
							"token"			=>	$token
						)
					);
		return $updt;
	}
}
?>