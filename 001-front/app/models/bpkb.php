<?php
class Bpkb extends AppModel
{
	var $name		= 'Bpkb';
	var $useTable	= 'bpkb';
	
	function DisplayBpkb()
	{
		$fstnk = $this->find('all', array(
				 	'order'			=>	array('Bpkb.id ASC'),
				 	'conditions'	=>	array(
						'Bpkb.id > '	=>	-1
					)
				 ));
		
        foreach ($fstnk as $k => $v) {
            $stnk[$v['Bpkb']['id']] = $v['Bpkb']['name'];
        }
		return $stnk;
	}
}
?>