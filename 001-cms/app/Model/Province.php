<?PHP
class Province extends AppModel
{
	var $name		= 'Province';
	var $useTable 	= 'province';
	
	function DisplayProvince()
	{
		$fprovince = $this->find('all', array(
                    'conditions' => array(
                        'Province.status' => 1
                    ),
					'group'	=>	array('Province.province_id'),
					'order'	=>	array('Province.province ASC')
                ));
		
        foreach ($fprovince as $k => $v) {
            $province[$v['Province']['province_id']] = $v['Province']['province'];
        }
		return $province;
	}
	
	function GetNameProvince($province_id)
	{
		$fprovince = $this->findByProvinceId($province_id);
		return $fprovince['Province']['province'];
	}
	
	function GetNameCity($city_id)
	{
		$fprovince = $this->findById($city_id);
		return $fprovince['Province']['name'];
	}
}

?>