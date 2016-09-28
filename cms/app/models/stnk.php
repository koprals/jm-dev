<?php
class Stnk extends AppModel
{
	var $name		= 'Stnk';
	var $useTable	= 'stnk';
	
	function DisplayStnk()
	{
		$fstnk = $this->find('all', array(
				 	'order'			=>	array('Stnk.id ASC')
				 ));
		
        foreach ($fstnk as $k => $v) {
            $stnk[$v['Stnk']['id']] = $v['Stnk']['name'];
        }
		return $stnk;
	}
	
	function GetStatusStnk($id)
	{
		$fstnk = $this->findById($id);
		return $fstnk['Stnk']['name'];
	}
}
?>