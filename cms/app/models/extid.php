<?PHP
class Extid extends AppModel
{
	var $name		= 'Extid';
	var $useTable 	= 'extid';
	
	var $belongsTo 	= array(
		'Users' => array(
			'className' 	=> 'Users',
			'foreignKey' 	=> 'user_id'
		)
	);
	
	
	function sett($usedID,$extName)
	{
		$find			=	$this->find("first",array(
								'conditions'	=> array(
									'Extid.user_id'	=> $usedID,
									'Extid.extName'	=> $extName,
								)	
							));
							
		return $find;
	}
}
?>