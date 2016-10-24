<?php
class EmailToken extends AppModel
{
	var $userTable	=	"email_tokens";
	var $name		=	"EmailToken";
	
	
	
	function GetToken($user_id,$product_id)
	{
		$token	=	$this->rand_string(40);
		$find	=	$this->findByToken($token);
		
		if($find)
		{
			$token = $this->GetToken($user_id,$product_id);
		}
		
		$this->create();
		
		$this->save(
			array(
				'token'			=>	$token,
				'user_id'		=>	$user_id,
				'product_id'	=>	$product_id
			)
		);
		return $token;
	}
	
	function rand_string( $length )
	{
		$chars	=	"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		$str	=	"";
		
		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
		
		return $str;
	}
}
?>