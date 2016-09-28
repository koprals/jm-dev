<?PHP
class EmailLog extends AppModel
{
	var $name		= 'EmailLog';
	var $useTable 	= 'email_logs';
	
	var $belongsTo 	= array(
		'EmailSettings' => array(
			'className' 	=> 'EmailSettings',
			'foreignKey' 	=> 'email_setting_id'
		)
	);
}

?>