<?php
class ProductEditLog extends AppModel
{
	var $name		= 'ProductEditLog';
	
	
	function SaveLogEdit($product_id,$data)
	{
		$PRODUCT	=	ClassRegistry::Init('Product');
		App::import('Helper', 'Number');
		$Number 		= 	new NumberHelper();
		
		//CEHCK PRODUCT ID PARAMETER
		if(empty($product_id)) return false;
		
		//CHECK PRODUCT ID EXISITNG
		$detail	=	$PRODUCT->findById($product_id);
		if(empty($detail)) return false;
		
		//DEFINE CHANGE LOG
		$arr_type	=	array("1"=>"Profile","2"=>"Dealer");
		$arr_cond	=	array("1"=>"Baru","2"=>"Bekas");
		
		$text		=	"";
		if($data['seo_name'] !== $detail['Product']['seo_name']) $text	.= "- Seo name dari \"".$detail['Product']['seo_name']."\" menjadi \"".$data['seo_name']."\"<br>";
		if($data['category_id'] !== $detail['Product']['category_id']) $text	.= "- Category ID dari \"".$detail['Product']['category_id']."\" menjadi \"".$data['category_id']."\"<br>";
		if($data['data_type'] !== $detail['Product']['data_type']) $text	.= "- Data Type dari \"".$arr_type[$detail['Product']['data_type']]."\" menjadi \"".$arr_type[$data['data_type']]."\"<br>";
		if($data['contact_name'] !== $detail['Product']['contact_name']) $text	.= "- Contact Name dari \"".$detail['Product']['contact_name']."\" menjadi \"".$data['contact_name']."\"<br>";
		if($data['phone'] !== $detail['Product']['phone']) $text	.= "- Phone dari \"".$detail['Product']['phone']."\" menjadi \"".$data['phone']."\"<br>";
		if($data['ym'] !== $detail['Product']['ym']) $text	.= "- Yahoo Messenger dari \"".$detail['Product']['ym']."\" menjadi \"".$data['ym']."\"<br>";
		if($data['address'] !== $detail['Product']['address']) $text	.= "- Address dari \"".$detail['Product']['address']."\" menjadi \"".$data['address']."\"<br>";
		if($data['province_id'] !== $detail['Product']['province_id']) $text	.= "- Province ID dari \"".$detail['Product']['province_id']."\" menjadi \"".$data['province_id']."\"<br>";
		if($data['city_id'] !== $detail['Product']['city_id']) $text	.= "- City ID dari \"".$detail['Product']['city_id']."\" menjadi \"".$data['city_id']."\"<br>";
		if($data['condition_id'] !== $detail['Product']['condition_id']) $text	.= "- Condition ID dari \"".$arr_cond[$detail['Product']['condition_id']]."\" menjadi \"".$arr_cond[$data['condition_id']]."\"<br>";
		if((string)$data['nopol'] !== (string)$detail['Product']['nopol']) $text	.= "- Nopol dari \"".$detail['Product']['nopol']."\" menjadi \"".$data['nopol']."\"<br>";
		if($data['thn_pembuatan'] !== $detail['Product']['thn_pembuatan']) $text	.= "- Nopol dari \"".$detail['Product']['thn_pembuatan']."\" menjadi \"".$data['thn_pembuatan']."\"<br>";
		if($data['color'] !== $detail['Product']['color']) $text	.= "- Color dari \"".$detail['Product']['color']."\" menjadi \"".$data['color']."\"<br>";
		if((int)$data['kilometer'] !== (int)$detail['Product']['kilometer']) $text	.= "- Kilometer dari \"".$detail['Product']['kilometer']."\" menjadi \"".$data['kilometer']."\"<br>";
		if($data['description'] !== $detail['Product']['description']) $text	.= "- Description dari \"".$detail['Product']['description']."\" menjadi \"".$data['description']."\"<br>";
		if((int)$data['stnk_id'] !== (int)$detail['Product']['stnk_id']) $text	.= "- STNK ID dari \"".$detail['Product']['stnk_id']."\" menjadi \"".$data['stnk_id']."\"<br>";
		if((int)$data['bpkb_id'] !== (int)$detail['Product']['bpkb_id']) $text	.= "- BPKB ID dari \"".$detail['Product']['bpkb_id']."\" menjadi \"".$data['bpkb_id']."\"<br>";
		
		if($Number->format($data['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) !== $Number->format($detail['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))) $text	.= "- PRICE  dari \"".$Number->format($detail['Product']['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))."\" menjadi \"".$Number->format($data['price'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))."\"<br>";
		
		if($data['is_credit'] !== $detail['Product']['is_credit']) $text	.= "- Is Credit dari \"".$detail['Product']['is_credit']."\" menjadi \"".$data['is_credit']."\"<br>";
		
		if($Number->format($data['first_credit'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) !== $Number->format($detail['Product']['first_credit'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))) $text	.= "- First Credit dari \"".$Number->format($detail['Product']['first_credit'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))."\" menjadi \"".$Number->format($data['first_credit'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))."\"<br>";
		
		if((int)$data['credit_interval'] !== (int)$detail['Product']['credit_interval']) $text	.= "- Credit Interval dari \"".$detail['Product']['credit_interval']."\" menjadi \"".$data['credit_interval']."\"<br>";
		
		if($Number->format($data['credit_per_month'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null)) !== $Number->format($detail['Product']['credit_per_month'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))) $text	.= "- Credit per Month dari \"".$Number->format($detail['Product']['credit_per_month'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))."\" menjadi \"".$Number->format($data['credit_per_month'],array("thousands"=>".","before"=>"Rp.","places"=>null,"after"=>null))."\"<br>";
		
		if($data['facebook_share'] !== $detail['Product']['facebook_share']) $text	.= "- Facebook Share dari \"".$detail['Product']['facebook_share']."\" menjadi \"".$data['facebook_share']."\"<br>";
		
		if($data['twitter_share'] !== $detail['Product']['twitter_share']) $text	.= "- Twitter Share dari \"".$detail['Product']['twitter_share']."\" menjadi \"".$data['twitter_share']."\"<br>";
		
		
		$text			=	(!empty($text)) ? "Perubahan yang terjadi : <br>".$text : "";
		
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		//SAVEL LOG
		$data										=	array();
		$data['ProductEditLog']						=	$detail['Product'];
		$data['ProductEditLog']['product_id']		=	$detail['Product']['id'];
		$data['ProductEditLog']['modified_by']		= 	"Owner(".$detail['Product']['contact_name'].")";
		$data['ProductEditLog']['text_modified']	=	$text;
		
		unset($data['ProductEditLog']['id']);
		$this->save($data);
		
		//SAVE IMAGES LOG
		$IMG			=	ClassRegistry::Init('ProductImage');
		$IMGLOG			=	ClassRegistry::Init('ProductImageLog');
		$dataimg		=	array();
		$detail_img		=	$IMG->find('all',array(
								'conditions'	=>	array(
									'ProductImage.product_id'	=>	$detail['Product']['id']
								)
							));
		
		//UPDATE HAVE LOG
		$PRODUCT->updateAll(
			array(
				'have_log'					=>	"'1'"
			),
			array(
				"Product.id"				=>	$product_id
			)
		);
		
		/*if(!empty($detail_img))
		{
			foreach($detail_img as $detail_img)
			{
				$IMGLOG->create();
				$dataimg['ProductImageLog']							=	$detail_img['ProductImage'];
				$FILE												=	$this->GetFileImages($detail_img['ProductImage']['id'],$settings);
				$dataimg['ProductImageLog']['producteditlog_id']	=	$this->getLastInsertId();
				$dataimg['ProductImageLog']['type']					=	$FILE['type'];
				unset($dataimg['ProductImageLog']['id']);
				$IMGLOG->save($dataimg);
				
				$img_id												=	$IMGLOG->GetLastInsertId();
				$source												=	$FILE['file'];
				$dest_folder										=	$settings['path_content']."ProductImageLog/{$img_id}/";
				$dest												=	$settings['path_content']."ProductImageLog/{$img_id}/".$img_id.".".$FILE['type'];
				if(!is_dir($dest_folder)) mkdir($dest_folder,0777);
				copy($source,$dest);
			}
		}*/
		return true;
	}
	
	function GetFileImages($img_id,$settings)
	{
		$CHEKFOLDER	=	$settings['path_content']."ProductImage/{$img_id}/{$img_id}";
		
		if(is_file($CHEKFOLDER.'.jpg'))
		{
			$FILE	=	$CHEKFOLDER.'.jpg';
			$type	=	"jpg";
		} 
		elseif(is_file($CHEKFOLDER.'.jpeg'))
		{
			$FILE	=	$CHEKFOLDER.'.jpeg';
			$type	=	"jpeg";
		}
		elseif(is_file($CHEKFOLDER.'.gif'))
		{
			$FILE	=	$CHEKFOLDER.'.gif';
			$type	=	"gif";
		} 
		elseif(is_file($CHEKFOLDER.'.png'))
		{
			$FILE	=	$CHEKFOLDER.'.png';
			$type	=	"png";
		}
		else
		{
			return array('','');
		}
		
		//$data = file_get_contents($FILE);
		return array('type'=>$type,'file'=>$FILE);
	}
}
?>