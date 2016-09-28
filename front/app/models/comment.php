<?php
class Comment extends AppModel
{
	var $validate	=	array(
							'product_id' => array(
								'notEmpty' => array(
									'rule' => "notEmpty",
									'message' => 'ID Iklan tidak ditemukan'	
								)
							),
							'name' => array(
								'maxSumComment' => array(
									'rule' => array('maxSumComment','rand_id','product_id',3),
									'message' => 'Maaf anda sudah melebihi batas maksimum pemberian komentar.'	
								),
								'maxLength' => array(
									'rule' => array('maxLength',100),
									'message' => 'Maksimum jumlah karakter nama adalah 100 karakter.'	
								),
								'minLength' => array(
									'rule' => array('minLength',3),
									'message' => 'Minimum jumlah karakter nama adalah 3 karakter.'	
								),
								'notEmpty' => array(
									'rule' => "notEmpty",
									'message' => 'Masukkan nama anda.'	
								)
							),
							'email' => array(
								'email' => array(
									'rule' => "email",
									'message' => 'Format email anda salah.'	
								),
								'notEmpty' => array(
									'rule' => "notEmpty",
									'message' => 'Masukkan email anda.'	
								)
							),
							'comment' => array(
								'minLength' => array(
									'rule' => array('minLength',10),
									'message' => 'Minimum jumlah karakter komentar adalah 10 karakter.'	
								),
								'notEmpty' => array(
									'rule' => "notEmpty",
									'message' => 'Masukkan komentar anda.'	
								)
							)
						);
	
	
	
	function maxSumComment($fields=array(),$rand_id,$product_field,$max=3)
	{
		$rand_val	=	$this->data[$this->name][$rand_id];
		$product_id	=	$this->data[$this->name][$product_field];
		
		$sum		=	$this->find("count",array(
							"conditions"	=>	array(
								"{$this->name}.{$rand_id}"							=>	$rand_val,
								"{$this->name}.{$product_field}"					=>	$product_id,
								"DATE_FORMAT( {$this->name}.created, '%Y-%m-%d' )" 	=>	date("Y-m-d"),
							)
						));
		
		if($sum >= $max)
		{
			return false;
		}
		return true;
	}
}
?>