<?php
class Stnk extends AppModel
{
	var $name		= 'Stnk';
	var $useTable	= 'stnk';
	
	function DisplayStnk()
	{
		$fstnk = $this->find('all', array(
				 	'order'			=>	array('Stnk.id ASC'),
				 	'conditions'	=>	array(
						'Stnk.id > '	=>	-1
					)
				 ));
		
        foreach ($fstnk as $k => $v) {
            $stnk[$v['Stnk']['id']] = $v['Stnk']['name'];
        }
		return $stnk;
	}
}
?>