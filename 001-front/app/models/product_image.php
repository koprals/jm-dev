<?php
class ProductImage extends AppModel
{
	var $name		= 'ProductImage';
	var $useTable	= 'product_images';
	
	function GetImages($product_id)
	{
		$img	=	array();
		$data	=	$this->find("all",array(
			'conditions'	=>	array(
				'ProductImage.product_id'	=>	$product_id
			)
		));
		if(!empty($data))
		{
			foreach($data as $data)
			{
				$img[$data['ProductImage']['number']]	=	array("id"=>$data['ProductImage']['id'],"is_primary"=>$data['ProductImage']['is_primary']) ;
			}
		}
		return $img;
	}
}
?>