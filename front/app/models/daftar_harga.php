<?php
class DaftarHarga extends AppModel
{
	var $name			=	'DaftarHarga';
	var $useTable		=	"products";
	var $belongsTo 	= array(
		
		'Category' => array(
			'className' 	=> 'Category',
			'foreignKey' 	=> 'category_id'
		),
		'Parent' => array(
			'className' 	=>	'Category',
			'foreignKey' 	=>	false,
			'conditions'	=>	'Category.parent_id = Parent.id'
		)
	);
	
	var $virtualFields	=	array(
								'MIN'			=>	'CAST(CAST(MIN(DaftarHarga.price) AS UNSIGNED) AS SIGNED)',
								'MAX'			=>	'CAST(CAST(MAX(DaftarHarga.price) AS UNSIGNED) AS SIGNED)',
							);
								
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$args			=	func_get_args();
		$uniqueCacheId	=	'';
		
		foreach ($args as $arg)
		{
			$uniqueCacheId .= serialize($arg);
		}
		
		if (!empty($extra['contain']))
		{
			$contain = $extra['contain'];
		}
		
		$uniqueCacheId = md5($uniqueCacheId);
		//var_dump('pagination-'.$this->alias.'-'.$uniqueCacheId);
		
		if(empty($order))
		{
	        $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
	    }
	    $group = $extra['group'];
		
		if (($pagination  = Cache::read('pagination-'.$this->alias.'-'.$uniqueCacheId,'paginate_daftarharga')) === false)
		{
			$pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
			Cache::write('pagination-'.$this->alias.'-'.$uniqueCacheId, $pagination,'paginate_daftarharga');
		}
	    return $pagination;
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array())
	{
		$args 					=	func_get_args();
		$uniqueCacheId 			=	'';
		
		foreach ($args as $arg)
		{
			$uniqueCacheId 		.= serialize($arg);
		}
		$uniqueCacheId 			= md5($uniqueCacheId);
		
		if (!empty($extra['contain']))
		{
			$contain = $extra['contain'];
		}

		$parameters 			=	compact('conditions');
		$parameters["fields"]	=	array('DaftarHarga.id');
	    $this->recursive 		=	$recursive;
	    
	    if (($paginationcount  = Cache::read('paginationcount-'.$this->alias.'-'.$uniqueCacheId,'paginate_daftarharga')) === false)
		{
			if (isset($extra['group']))
			{	
				$paginationcount = $this->find('all', array_merge($parameters, $extra));
				$paginationcount = $this->getAffectedRows();
			}
			else
			{
				$paginationcount = $this->find('count', array_merge($parameters, $extra));
			}
			Cache::write('paginationcount-'.$this->alias.'-'.$uniqueCacheId, $paginationcount,'paginate_daftarharga');
		}
	    return $paginationcount;
	}
}
?>