<?php
class ProductEditLog extends AppModel
{
	var $name		= 'ProductEditLog';
	
	var $belongsTo 	= array(
		'Productstatus' => array(
			'className' 	=> 'Productstatus',
			'foreignKey' 	=> 'productstatus_id'
		),
		'Category' => array(
			'className' 	=> 'Category',
			'foreignKey' 	=> 'category_id'
		),
		'Parent' => array(
			'className' 	=>	'Category',
			'foreignKey' 	=>	false,
			'conditions'	=>	'Category.parent_id = Parent.id'
		),
		'User' => array(
			'className' 	=>	'User',
			'foreignKey' 	=>	'user_id'
		)
	);
	
	function SaveLogEdit($product_id,$notice=null,$modified_by=null)
	{
		$PRODUCT	=	ClassRegistry::Init('Product');
		
		//CEHCK PRODUCT ID PARAMETER
		if(empty($product_id)) return false;
		
		//CHECK PRODUCT ID EXISITNG
		$detail	=	$PRODUCT->findById($product_id);
		if(empty($detail)) return false;
		
		$SETTING		=	ClassRegistry::Init('Setting');
		$settings		=	$SETTING->find('first');
		$settings		=	$settings['Setting'];
		
		//SAVEL LOG
		$data									=	array();
		$data['ProductEditLog']					=	$detail['Product'];
		$data['ProductEditLog']['product_id']	=	$detail['Product']['id'];
		$data['ProductEditLog']['notice']		=	$notice;
		$data['ProductEditLog']['modified_by']	=	is_null($modified_by) ? $detail['Product']['modified_by'] : "Admin(".$modified_by.")";
		
		unset($data['ProductEditLog']['id']);
		$save	=	$this->save($data);
		
		if($save)
		{
			//UPDATE HAVE LOG
			$PRODUCT->updateAll(
				array(
					'have_log'					=>	"'1'",
					'notice'					=>	"'".$notice."'"
				),
				array(
					"Product.id"				=>	$product_id
				)
			);
			
			
			//SAVE IMAGES LOG
			/*$IMG			=	ClassRegistry::Init('ProductImage');
			$IMGLOG			=	ClassRegistry::Init('ProductImageLog');
			$dataimg		=	array();
			$detail_img		=	$IMG->find('all',array(
									'conditions'	=>	array(
										'ProductImage.product_id'	=>	$detail['Product']['id']
									)
								));
			
			if(!empty($detail_img))
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
		return false;
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
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
	    $parameters = compact('conditions');
	    $this->recursive = $recursive;
	    $count = $this->find('count', array_merge($parameters, $extra));
	    if (isset($extra['group'])) {
	    	$count = $this->find('all', array_merge($parameters, $extra));
	        $count = $this->getAffectedRows();
	    }
	   
	    return $count;
	}
	
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
	    
		
		if(empty($order)){
	        $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
	    }
		
	    $group = $extra['group'];
	    return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
	}
}
?>