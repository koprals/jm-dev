<?PHP
class ValidationCode extends AppModel
{
	var $name		= 'ValidationCode';
	var $useTable 	= 'validation_codes';
	
	
	function ValidateByApps()
	{
		//SET GENERAL SETTINGS
		if (($settings = Cache::read('settings')) === false)
		{
			$SETTING		=	ClassRegistry::Init('Setting');
			$settings		=	$SETTING->find('first');
			Cache::write('settings', $settings);
		}
		
		$settings		=	$settings['Setting'];
		
		$this->validate	=	array(
			'user_id' => array(
				'IsActive' => array(
					'rule' 		=> "IsActive",
					'message'	=> 'Akun anda telah diaktifkan sebelumnya, silahkan anda login menggunakan email dan password anda. Gunakan menu forgot password jika anda lupa password anda.'	
				),
				'IsBlock' => array(
					'rule' 		=> "IsBlock",
					'message'	=> 'Maaf anda tidak dapat mengaktifkan akun anda, akun anda saat ini sedang dalam status terblokir.'	
				),
				'detail' => array(
					'rule' 		=> "Detail",
					'message'	=> 'Maaf terjadi kegagalan koneksi ke server.'	
				)
			),
			'code' => array(
				'IsValidCode' => array(
					'rule' 		=> "IsValidCode",
					'message'	=> 'Maaf code yang anda masukkan salah.'	
				),
				'notEmpty' => array(
					'rule' 		=> "notEmpty",
					'message'	=> 'Masukkan kode yang kami kirimkan melalui email anda.'	
				)
			)
		);
	}
	
	
	function Detail($fields = array())
	{
		$user_id	=	$this->data[$this->name]['user_id'];
		$USER		=	ClassRegistry::Init("User");
		
		$data		=	$USER->findById($user_id);
		if(empty($data))
		{
			return false;
		}
		return true;
	}
	
	function IsBlock($fields = array())
	{
		$user_id	=	$this->data[$this->name]['user_id'];
		$USER		=	ClassRegistry::Init("User");
		$data		=	$USER->findById($user_id);
		
		if(empty($data) or $data['User']['userstatus_id'] < 0)
		{
			return false;
		}
		return true;
	}
	
	function IsActive($fields = array())
	{
		$user_id	=	$this->data[$this->name]['user_id'];
		$USER		=	ClassRegistry::Init("User");
		$data		=	$USER->findById($user_id);
		
		if(empty($data) or $data['User']['userstatus_id'] == "1")
		{
			return false;
		}
		return true;
	}
	
	
	function IsValidCode($fields = array())
	{
		$user_id	=	$this->data[$this->name]['user_id'];
		$USER		=	ClassRegistry::Init("User");
		
		$data		=	$USER->findById($user_id);
		if(!empty($data))
		{
			$CODE			=		ClassRegistry::Init("ValidationCode");
			$CodeDetail		=		$CODE->findById($data['User']['email']);
			$user_code		=		$this->data[$this->name]['code'];
			
			if($CodeDetail['ValidationCode']['code'] == $user_code)
			{
				return true;
			}
		}
		return false;
	}
	
	
}

?>