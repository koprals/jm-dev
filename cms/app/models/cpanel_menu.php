<?php
class CpanelMenu extends AppModel
{
	var $actsAs 	= array('Tree');
	
	
	
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
		),
		'code' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => 'Nama Kode tidak boleh melebihi 100 karakter.',
			),
			'minLength' => array(
				'rule' => array('minLength', 2),
				'message' => 'Nama Kode harus lebih dari 2 karakter.',
			),
			'notsame'	=> array(
				'rule' => 'notsame',
				'message' => 'Kode yang anda masukkan sudah ada.'
			),
			'notempty'	=> array(
				'rule' => 'notEmpty',
				'message' => 'Silahkan masukkan nama kode.'
			)
		)
	);
	
	function notsame($field=array())
	{

		foreach($field as $key=>$value)
		{
			$parent_id			=  (empty($this->data['Category']['parent_id'])) ? 'NULL' : $this->data['Category']['parent_id'];
			
			if(!empty($this->data['Category']["id"]))
			{
				$cond	=	array(
								'Category.'.$this->data[$this->name][$key]			=>	$value,
								'Category.parent_id'								=>	$parent_id,
								'Category.status >= '								=>	0,
								'Category.id !='									=>	$this->data['Category']["id"],
				);	
			}
			else
			{
				$cond	=	array(
								'Category.'.$this->data[$this->name][$key]			=>	$value,
								'Category.parent_id'								=>	$parent_id,
								'Category.status >= '								=>	0			
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
}
?>