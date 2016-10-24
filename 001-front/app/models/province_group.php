<?PHP
class ProvinceGroup extends AppModel
{
	var $name		= 'ProvinceGroup';
	
	function DisplayProvinceGroup()
	{
		$fprovince		= $this->find('all', array(
								
								'order'	=>	array('IF( ProvinceGroup.pos > 0 , 1, 0) DESC,ProvinceGroup.pos ASC,ProvinceGroup.name ASC')
							));
		
		$province["all_cities"] 	= "Semua Kota";
        foreach ($fprovince as $k => $v) {
			
            $province[$v['ProvinceGroup']['id']] = $v['ProvinceGroup']['name'];
        }
		return $province;
	}
}

?>