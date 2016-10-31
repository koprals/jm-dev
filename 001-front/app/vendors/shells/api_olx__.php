<?php
class ApiOlxShell extends Shell
{
	var $name 			=	'ApiOlx';
	var $uses 			=	array("Product");
	var $General;
	
	function Main()
	{
		$Setting			=	ClassRegistry::Init('Setting');
		$settings			=	$Setting->find('first');
		$settings			=	$settings['Setting'];
		$destination		=	$settings['path_content']."rss/api_olx.xml";
		
		$this->Product->unbindModel(array(
			"belongsTo"	=>	array(
				'Productstatus',
				'User'
			)	
		));
		
		$this->Product->bindModel(array(
			"hasOne"	=>	array(
				"ProductImage"	=>	array(
					'className'		=> 'ProductImage',
					'foreignKey'	=> 'product_id',
					'conditions'	=> array('ProductImage.status' => '1','ProductImage.is_primary'=>1),
					'order'			=> 'ProductImage.is_primary DESC, ProductImage.number ASC'
				),
			),
			'belongsTo'	=>	array(
				'Province' => array(
					'className' 	=>	'Province',
					'foreignKey' 	=>	false,
					'conditions'	=>	'Product.city_id = Province.id'
				),
				'ProvinceGroup' => array(
					'className' 	=>	'ProvinceGroup',
					'foreignKey' 	=>	false,
					'conditions'	=>	'Province.group_id = ProvinceGroup.id'
				)
			)
		));
		
		$data	=	$this->Product->find("all",array(
						"conditions"	=>	array(
							"Product.productstatus_id"		=>	1,
							"Product.productstatus_user"	=>	1
						),
						"fields"		=>	array(
							"Product.id",
							"Category.name",
							"Parent.name",
							"Product.description",
							"Product.approved",
							"Product.address",
							"Product.thn_pembuatan",
							"Product.price",
							"ProductImage.id",
							"Province.name",
							"ProvinceGroup.name",
							"ProvinceGroup.olx_code"
						),
						"limit"			=>30,
						"order"			=>	array("Product.id ASC")
					));
		
		$xml	=	
		'<?xml version="1.0" encoding="utf-8"?>
		<ADS>
		';
		foreach($data as $data)
		{
			$data["Product"]["description"]		=	$this->stripBBCode($data["Product"]["description"]);
			$xml	.='
			<AD>
				<ID>'.$data['Product']["id"].'</ID>
				<TITLE>'.$data['Parent']["name"].' '.$data['Category']["name"].'</TITLE>
				<DESCRIPTION><![CDATA['.$data['Product']["description"].'\n\n<a href="'.$settings['site_url'].'Iklan/Detail/'.$data["Product"]["id"]."/".$this->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).'.html">'.$settings['site_url'].'Iklan/Detail/'.$data["Product"]["id"]."/".$this->seoUrl("motor dijual ".$data["Parent"]["name"]." ".$data["Category"]["name"]." (".$data["Product"]["thn_pembuatan"].") ".$data["ProvinceGroup"]["name"]).'.html</a>]]></DESCRIPTION>
				
				<DATE>'.$data['Product']["approved"].'</DATE>
				<EMAIL>admin@jualanmotor.com</EMAIL>
				<LOCATION_COUNTRY>100</LOCATION_COUNTRY>
				<LOCATION_STATE>'.$data['ProvinceGroup']['olx_code'].'</LOCATION_STATE>
				<LOCATION_CITY><![CDATA['.$data['Province']["name"].']]></LOCATION_CITY>
				<ZIP_CODE></ZIP_CODE>
				<ADDRESS><![CDATA['.$data['Product']["address"].']]></ADDRESS>
				<CATEGORY>379</CATEGORY>
			';
			if(!empty($data['ProductImage']))
			{
				$xml	.= '<IMAGE_URL><![CDATA['.$settings['showimages_url'].'/'.$data['ProductImage']['id'].'.jpg?code='.$data['ProductImage']['id'].'&prefix=_580_380&content=ProductImage&w=580&h=380&watermark=1]]></IMAGE_URL>';
			}
			else
			{
				$xml	.= '<IMAGE_URL></IMAGE_URL>';	
			}
				
			$xml	.= '
			<PRICE>'.number_format($data['Product']["price"],0,'','').'</PRICE>
				<SELLER_TYPE></SELLER_TYPE>
				<PHONE></PHONE>
			</AD>';
		}
		$xml	.='
		</ADS>
		';
		
		$handle = fopen($destination, 'wb');
		if($handle)
		{
			if (fwrite($handle, $xml) === FALSE) 
			{
				echo "Falied to write xml";
				exit;
			}
		}
		fclose($handle);
	}
	
	function seoUrl($string)
	{
		//Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
		$string = strtolower($string);
		//Strip any unwanted characters
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
	
	function stripBBCode($text_to_search)
	{
		$pattern = '|[[\/\!]*?[^\[\]]*?]|si';
		$replace = '';
		$replace = preg_replace($pattern, $replace, $text_to_search);
		return $replace;
	} 
}
?>