<?php
class Category extends AppModel
{
	var $actsAs 		=	array('Tree'=>array('scope'=>"`Category`.`status` = 1"));
	var $components 	=	array('General');
	var $belongsTo 	= array(
		'Parent' => array(
			'className' 	=>	'Category',
			'foreignKey' 	=>	false,
			'conditions'	=>	'Category.parent_id = Parent.id'
		));
	
	var $validate 	= array(
		'name' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => 'Nama kategori tidak boleh melebihi 100 karakter.',
			),
			'minLength' => array(
				'rule' => array('minLength', 2),
				'message' => 'Nama kategori harus lebih dari 2 karakter.',
			),
			'notsame'	=> array(
				'rule' => 'notsame',
				'message' => 'Kategori yang anda masukkan sudah ada.'
			),
			'notempty'	=> array(
				'rule' => 'notEmpty',
				'message' => 'Silahkan masukkan nama kategori.'
			)
		)
	);
	
	function DisplayCategory()
	{
		$top		= $this->findByName("TOP");
		
		$fcategory	= $this->find('all', array(
						'conditions' => array(
							'Category.parent_id' => $top['Category']['id'],
							'Category.status'	 => 1
						),
						'order'	=>	array('Category.lft ASC')
					));
		
        foreach ($fcategory as $k => $v) {
            $category[$v['Category']['id']] = $v['Category']['name'];
        }
		return $category;
	}
	
	function DisplayCategoryEdit($category_id)
	{
		$top		= $this->findByName("TOP");
		
		$fcategory	= $this->find('all', array(
						'conditions' => array(
							'OR'	=>	array(
								array(
									'Category.parent_id'	=> $top['Category']['id'],
									'Category.status'		=> 1
								),
								array(
									'Category.parent_id'	=> $top['Category']['id'],
									'Category.status'		=> 0,
									'Category.id'			=> $category_id
								),
							)
						),
						'order'	=>	array('Category.lft ASC')
					));
        foreach ($fcategory as $k => $v) {
            $category[$v['Category']['id']] = $v['Category']['name'];
        }
		return $category;
	}
	
	function DisplayCategorySearch()
	{
		$top		= $this->findByName("TOP");
		
		$fcategory	= $this->find('all', array(
						'conditions' => array(
							'Category.parent_id' => $top['Category']['id'],
							'Category.status'	 => 1
						),
						'order'	=>	array('Category.lft ASC')
					));
		
		
		$category					=	array();
		$category['all_categories']	=	"Semua Merk Motor";
        foreach ($fcategory as $k => $v) {
            $category[$v['Category']['id']] = $v['Category']['name'];
        }
		
		return $category;
	}
	
	function DisplaySubCategory($parent_id,$category_id)
	{
		$fcategory	= $this->find('all', array(
						'conditions' => array(
							'OR'	=>	array(
								array(
									'Category.parent_id'	=> $parent_id,
									'Category.status'		=> 1
								),
								array(
									'Category.parent_id'	=> $parent_id,
									'Category.status'		=> 0,
									'Category.id'			=> $category_id
								),
							)
						),
						'order'	=>	array('Category.lft ASC')
					));
		
        foreach ($fcategory as $k => $v) {
            $category[$v['Category']['id']] = $v['Category']['name'];
        }
		return $category;
	}
	
	function DisplaySubCategoryEdit($parent_id,$category_id)
	{
		$fcategory	= $this->find('all', array(
						'conditions' => array(
							'OR'	=>	array(
								array(
									'Category.parent_id'	=> $parent_id,
									'Category.status'		=> 1
								),
								array(
									'Category.parent_id'	=> $parent_id,
									'Category.status'		=> 0,
									'Category.id'			=> $category_id
								),
							)
						),
						'order'	=>	array('Category.lft ASC')
					));
		return $fcategory;
	}
	
	
	function GetCatId($name,$parent_id,$status=0)
	{
		$this->Behaviors->detach('Tree');
		$this->Behaviors->attach('Tree');
		$findSame	=	$this->find('first',array(
										'conditions'	=> array(
											'LOWER(Category.name)'		=> strtolower($name),
											'Category.parent_id'		=> $parent_id,
											'Category.status !='		=> -2
										)
									));
		
		if($findSame==false)
		{
			//SAVE CATEGORY NAME
			$datacat['Category']['name'] 			=  strtoupper($name);
			$datacat['Category']['status'] 			=  $status;
			$datacat['Category']['parent_id'] 		=  $parent_id;
			$this->create();
			$save	=	$this->save($datacat);
			$cat_id									=  $this->getLastInsertId();
		}
		else
		{
			$cat_id									=  $findSame['Category']['id'];
		}
		return $cat_id;
	}
	
	function FindTop()
	{
		$findSame	=	$this->find('first',array(
										'conditions'	=> array(
											'Category.parent_id IS NULL'
										)
									));
		
		return $findSame['Category']['id'];
	}
	
	function GetCatAndSubcat($cat_id)
	{
		$child		=	$this->findById($cat_id);
		$parent		=	$this->findById($child['Category']['parent_id']);
		return array($parent['Category']['name'],$child['Category']['name']);
	}
	
	function notsame($field=array())
	{
		foreach($field as $key=>$value)
		{
			$parent_id			=  (empty($this->data['Category']['parent_id'])) ? 'NULL' : $this->data['Category']['parent_id'];
			
			if(!empty($this->data['Category']["id"]))
			{
				$cond	=	array(
								'Category.name'			=>	$value,
								'Category.parent_id'	=>	$parent_id,
								'Category.status >= '	=>	0,
								'Category.id !='		=>	$this->data['Category']["id"],
				);	
			}
			else
			{
				$cond	=	array(
								'Category.name'			=>	$value,
								'Category.parent_id'	=>	$parent_id,
								'Category.status >= '	=>	0			
				);
			
			}
			
			
			//CHEKCK NAME FIRST
			$check	=	$this->find('first',array(
							'conditions'	=>	$cond  
						));
			
			if(!empty($check))
			{
				return false;	
			}
		}
		return true;
	}
	
	function GetSeoName($cat_id)
	{
		$path	=	$this->getpath($cat_id,array('name'));
		$seo	=	"";
		App::import('Component', 'GeneralComponent'); 
		$General = new GeneralComponent();

		foreach($path as $path)
		{
			if($path['Category']['name']!="TOP")
			{
				$seo	.=	$path['Category']['name']." ";
			}
		}
		$seo	=	substr($seo,0,-1);
		return $General->seoUrl($seo);
	}
	
	function GetTop()
	{
		$top	=	$this->find('first',array(
						"conditions"	=>	array(
							"Category.parent_id"	=>	NULL
						)
					));
		
		return	$top['Category']['id'];
	}
}
?>