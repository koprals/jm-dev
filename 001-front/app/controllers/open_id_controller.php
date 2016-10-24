<?php 
class OpenIdController extends AppController
{
	var $name 			= 	'OpenId';
	var $uses 			=	null;
	
	var $components 	=	array(
								'Action',
								'General',
								'Recaptcha.Captcha' => array( 
									'private_key'	=> "6LcM_MQSAAAAADfgmPNQnZkP6s6gBk5T9vR2yymc",  
									'public_key'	=> "6LcM_MQSAAAAAEfA5vaAIBkI4sWxnJ601AEj_tL-"
								),
								'RequestHandler',
								'Openid' => array(
											'use_database'			=> false, 
											'accept_google_apps'	=> true
										)
							);
	
	var $helpers		= array('Recaptcha.CaptchaTool','Xml');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = "login";
	}
	
	function FacebookProfile()
	{
		App::import('Vendor','FacebookApiException' ,array('file'=>'facebook.php'));
		$facebook = new Facebook(array(
		  'appId'  => $this->settings['facebook_app_id'],
		  'secret' => $this->settings['facebook_app_secret'],
		  'cookie' => true,
		));
		$user = $facebook->getUser();
		$user_profile = $facebook->api('/me');
		
		$this->autoRender	=	false;
	}
	
	function TwitterProfile()
	{
		App::import('Vendor','TwitterOAuth' ,array('file'=>'twitteroauth/twitteroauth.php'));
		$connection 		= new TwitterOAuth($this->settings['twitter_consumer_key'], $this->settings['twitter_consumer_secret'],"82521972-G2QbRUM4hGxxztnKnnR94jvRXTaYD35f75GJOAC0f","G98bCd0WrYG9Y0NEyLq87bV0Tu2GxhdzqQD4yymvgy0");
		$status = $connection->get('users/show/82521972');
	
	}
	
	
	function FacebookUrl($redirect=1)
	{
		$this->layout	=	"json";
		App::import('Vendor','FacebookApiException' ,array('file'=>'facebook/facebook2.php'));
		$logintype	=	"?login_type=window";
		
		$facebook = new Facebook(array(
			'appId' 	=>	$this->settings['facebook_app_id'],
		  	'secret'	=>	$this->settings['facebook_app_secret'],
		));
		
		$url	=	$facebook->getLoginUrl(
								array(
									"redirect_uri"	=>	$this->settings['site_url']."OpenId/Facebook/".$redirect,
									"scope"			=>	"email,user_birthday,publish_stream",
									"display"		=>	"popup"
								)
							);
		if(!empty($url))
		{
			 $msg	= array("status"=>true,"uri"=>$url);
		}
		else
		{
			$msg	= array("status"=>false,"uri"=>"");
		}
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function Facebook($redirect=1)
	{
		$this->layout = ajax;
		Configure::write("debug",0);
		$results	=	array('status'=>false);
		$ok			=	"0";
		
		if(!isset($_GET['error']) or empty($_GET['error']) )
		{
			App::import('Vendor','FacebookApiException' ,array('file'=>'facebook/facebook2.php'));
			$logintype	=	"?login_type=window";
			
			$facebook = new Facebook(array(
				'appId' 	=>	$this->settings['facebook_app_id'],
				'secret'	=>	$this->settings['facebook_app_secret'],
			));
			$user 		= $facebook->getUser();
			
			if($user != "0")
			{
				$fql = 'SELECT name,email,birthday from user where uid = ' . $user_id;
				$profile = $facebook->api(array(
										   'method' => 'fql.query',
										   'query' => $fql,
										 ));
				//$profile = $facebook->api('/me');
				if(!empty($profile))
				{
					$results				=	array('status'=>true);
					$ok						=	1;		
					$birthdate				=	date("Y-m-d",strtotime($profile[0]['birthday']));
					$results['ext_id']		=	$user_id;
					$results['full_name']	=	$profile[0]['name'];
					$results['email']		=	str_replace("\u0040","@",$profile[0]['email']);
					$results['vendor']		=	"facebook";
					$results['birthdate']	=	$birthdate;
				}
			}
		}
		
		$this->Session->write("RESULTS",$results);
		
		if($redirect==0)
		{
			$ok	=	0;
		}
		$this->set("ok",$ok);
	}
	
	/*
	* FACEBOOK OLD API
	*
	function FacebookUrl($redirect=1)
	{
		$this->layout	=	"json";
		
		App::import('Vendor','FacebookApiException' ,array('file'=>'facebook.php'));
		$logintype	=	($_GET['login_type']=="window") ? "?login_type=window" : "?login_type=popup"; 
		
		$facebook = new Facebook(array(
		  'appId'  => $this->settings['facebook_app_id'],
		  'secret' => $this->settings['facebook_app_secret'],
		  'cookie' => true,
		));
		$session 	=	$facebook->getSession();
		$url		=	$facebook->getLoginUrl(
						array(
							"next"			=>	$this->settings['site_url']."OpenId/Facebook/1/".$redirect."/".$logintype,
							"cancel_url"	=>	$this->settings['site_url']."OpenId/Facebook/0/".$redirect."/".$logintype,
							"display"		=>	"popup",
							'req_perms' 	=>  'email,user_birthday,publish_stream'
						));
		
		if(!empty($url))
		{
			 $msg	= array("status"=>true,"uri"=>$url);
		}
		else
		{
			$msg	= array("status"=>false,"uri"=>"");
		}
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function Facebook($ok="1",$redirect=1)
	{
		$this->layout = ajax;
		Configure::write("debug",0);
		
		if($ok=="1")
		{
			App::import('Vendor','FacebookApiException' ,array('file'=>'facebook.php'));
			$facebook = new Facebook(array(
			  'appId'  => $this->settings['facebook_app_id'],
			  'secret' => $this->settings['facebook_app_secret'],
			  'cookie' => true,
			));
			$session = $facebook->getSession();
			$this->__GetFacebookCookie();
		}
		
		if($redirect==0)
		{
			$ok	=	0;
		}
		
		$this->set("ok",$ok);
		
		if($_GET['login_type']=="window")
		{
			$this->redirect(array("controller"=>"OpenId","action"=>"Results"));
		}
	}*/
	
	
	function __GetFacebookCookie() {
		$args 				= array();
		$app_id				= $this->settings['facebook_app_id'];
		$application_secret	= $this->settings['facebook_app_secret'];

		if(isset($_COOKIE['fbs_' . $app_id]))
		{
			parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
			ksort($args);
			$payload = '';
			foreach ($args as $key => $value) {
				if ($key != 'sig') {
					$payload .= $key . '=' . $value;
				}
			}
			
			if (md5($payload . $application_secret) != $args['sig']) {
				$results				=	array('status'=>false);
				$this->Session->write("RESULTS",$results);
				return false;
			}
			else
			{
				
				$tuCurl 				= 	curl_init();
				curl_setopt($tuCurl, CURLOPT_URL, 'https://graph.facebook.com/me?access_token=' .$args['access_token']); 
				curl_setopt($tuCurl, CURLOPT_HEADER, 0);
				curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, false);
				$tuData 				= 	curl_exec($tuCurl);
				
				$content				= 	json_decode($tuData);
				
				$results				=	array('status'=>true);
				$birthdate				=	date("Y-m-d",strtotime(str_replace("\\","",$content->birthday)));
				$results['ext_id']		=	$content->id;
				$results['full_name']	=	$content->name;
				$results['email']		=	str_replace("\u0040","@",$content->email);
				$results['vendor']		=	"facebook";
				$results['birthdate']	=	$birthdate;
				$this->Session->write("RESULTS",$results);
				return true;
			}
		}
		return false;
	}
	
	
	
	
	function Test()
	{
		$this->autoRender	=	false;
		App::import('Vendor','TwitterOAuth' ,array('file'=>'twitteroauth/twitteroauth.php'));
		
		/* Build TwitterOAuth object with client credentials. */
		$connection = new TwitterOAuth($this->settings['twitter_consumer_key'], $this->settings['twitter_consumer_secret']);
		
		/*Step1. Get Rquest Token*/
		$request_token = $connection->getRequestToken();
		$token	=	 $request_token['oauth_token'];
		
		
		//TRY TO SEND PARAMETER
		$post	=	array("oauth_token"	=>	$token,"session[username_or_email]"=>"elis.dahliawati@gmail.com","session[password]"=>"15061984");
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://twitter.com/oauth/authenticate");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$out = curl_exec($ch);
		//print_r(curl_error($ch));
		curl_close($ch);
		//print_r($out);
		
		if (preg_match('~"code-desc"><code>([^<]*)</code~', $out, $m))
		{
			$pin	=	$m[1] ;
			
			
			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$connection = new TwitterOAuth($this->settings['twitter_consumer_key'], $this->settings['twitter_consumer_secret'], $request_token['oauth_token'], $request_token['oauth_token_secret']);

			/* Request access tokens from twitter */
			$access_token = $connection->getAccessToken($pin);
		
			
			$connection 		= new TwitterOAuth($settings['twitter_consumer_key'], $settings['twitter_consumer_secret'],$access_token['oauth_token'],$access_token['oauth_token_secret']);
		    $post				= $connection->post('statuses/update', array('status' => "Bisa neiiih"));
			
		}
		else
		{
			echo "username salah";
		}
	}
	
	
	function TwitterUrl($redirect=1)
	{
		
		//http://www.jualanmotor.com/OpenId/Twitter;
		
		$this->layout	=	"json";
		Configure::write("debug",0);
		
		App::import('Vendor','TwitterOAuth' ,array('file'=>'twitteroauth/twitteroauth.php'));
		
		/* Build TwitterOAuth object with client credentials. */
		$connection = new TwitterOAuth($this->settings['twitter_consumer_key'], $this->settings['twitter_consumer_secret']);
		$logintype	=	($_GET['login_type']=="window") ? "?login_type=window" : "?login_type=popup"; 

		/* Get temporary credentials. */
		$request_token = $connection->getRequestToken($this->settings['site_url'].'OpenId/Twitter/'.$redirect.$logintype);
		
		/* Save temporary credentials to session. */
		$_SESSION['oauth_token'] 		= $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		
		switch ($connection->http_code) {
		  case 200:
		    /* Build authorize URL and redirect user to Twitter. */
		    $url = $connection->getAuthorizeURL($token);
		    $msg	= array("status"=>true,"uri"=>$url);
		    break;
		  default:
		    $msg	= array("status"=>false,"uri"=>"");
		}
		
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function Twitter($redirect=1)
	{
		$this->layout = "ajax";
		if(isset($_GET['denied']))
		{
			$session_back	=	$this->Session->read('back_url');
			$back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
			$this->redirect($back_url);
		}
		
		App::import('Vendor','TwitterOAuth' ,array('file'=>'twitteroauth/twitteroauth.php'));
		
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth($this->settings['twitter_consumer_key'], $this->settings['twitter_consumer_secret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$_SESSION['access_token'] = $access_token;
		$content = $connection->get('account/verify_credentials');
		
		//SET PROFILES DATA
		$results				=	array('status'=>true);
		$results['ext_id']		=	$content->id;
		$results['full_name']	=	$content->name;
		$results['email']		=	"";
		$results['vendor']		=	"twitter";
		
		$results['oauth_token']				=	$access_token['oauth_token'];
		$results['oauth_token_secret']		=	$access_token['oauth_token_secret'];
		$this->Session->write("RESULTS",$results);
	
		/*Remove no longer needed request tokens*/
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		unset($_SESSION['access_token']);
		
		if($_GET['login_type']=="window")
		{
			$this->redirect(array("controller"=>"OpenId","action"=>"Results"));
		}
		$this->set("redirect",$redirect);
	}
	
	/*function YahooUrl()
	{
		header('X-XRDS-Location: '.$this->settings['site_url'].'OpenId/xrds');
		$this->layout	=	"json";
		$openIdGoogle	=	"me.yahoo.com";
		$returnTo		= 	$this->settings['site_url']."OpenId/Yahoo";
		$url			=	$this->Openid->authenticate($openIdGoogle, $returnTo,$this->settings['site_url']);
		$out	= array("status"=>true,"uri"=>$url);
		$this->set("data",$out);
		$this->render(false);
	}
	
	function Yahoo()
	{
		$this->layout = "ajax";

	}
	*/
	private function handleOpenIDResponse($returnTo) {
        $response = $this->Openid->getResponse($returnTo);
		
        if ($response->status == Auth_OpenID_CANCEL) {
			$this->redirect(Configure::read('WEB_URL'));
            echo 'Verification cancelled';
        } elseif ($response->status == Auth_OpenID_FAILURE) {
			$this->redirect(array("controller"=>"User","action"=>"Login"));
            echo 'OpenID verification failed: '.$response->message;

        } elseif ($response->status == Auth_OpenID_SUCCESS) {
            //echo 'Successfully authenticated!<br />';

            $openid = $response->identity_url;
           

            $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
            $sreg = $sregResponse->contents();
            

            $axResponse = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($response);
            
			$results					=	array();
            
			if ($axResponse) {
				$results['status']		=	true;
				$results['ext_id']		=	str_replace("\u0040","@",$axResponse->getSingle('http://axschema.org/contact/email'));
				$results['full_name']	=	$axResponse->getSingle('http://axschema.org/namePerson');
				$results['email']		=	str_replace("\u0040","@",$axResponse->getSingle('http://axschema.org/contact/email'));
            	$vendor					=	explode("@",$results['email']);
				$vendor					=	explode(".",$vendor[1]);
				$results['vendor']		=	($vendor[0]=="gmail" || $vendor[0]=="google") ? "google" : (($vendor[0]=="yahoo" || $vendor[0]=="ymail") ? "yahoo" : $vendor[0]);
			}
			

			$this->Session->write("RESULTS",$results);
        }
    }

	private function debug($var = false, $showHtml = false, $showFrom = true) {
        if ($showFrom) {
            $calledFrom = debug_backtrace();
            echo '<strong>' . substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1) . '</strong>';
            echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
        }
        echo "\n<pre class=\"cake-debug\">\n";

        $var = print_r($var, true);
        if ($showHtml) {
            $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
        }
        echo $var . "\n</pre>\n";
    }

	public function xrds() {
        $this->layout = 'xml/default';
        header('Content-type: application/xrds+xml');
        $this->set('returnTo', $this->settings['site_url'].'OpenId/Yahoo/');
    }
	
	private function makeOpenIDRequest($openid, $returnTo) {
        try {
            // used by Google, Yahoo
            $axSchema = 'axschema.org';
            $attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$axSchema.'/namePerson', 1, true, 'ax_fullname');
            $attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$axSchema.'/contact/email', 1, true, 'ax_email');
			$attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$axSchema.'/person/gender', 1, true, 'ax_gender');
			$attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$axSchema.'/birthDate', 1, true, 'ax_birthdate');

            // used by MyOpenID (Google supports this schema for /contact/email only)
            $openidSchema = 'schema.openid.net';
            $attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$openidSchema.'/namePerson', 1, true, 'fullname');
            $attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$openidSchema.'/contact/email', 1, true, 'email');
			$attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$openidSchema.'/person/gender', 1, true, 'gender');
			$attributes[] = Auth_OpenID_AX_AttrInfo::make('http://'.$openidSchema.'/birthDate', 1, true, 'birthDate');

            

			$url	=	$this->Openid->authenticate($openid, $returnTo, Configure::read('WEB_URL'), array('ax' => $attributes));

			return $url;
			
        } catch (Exception $e) {
            $this->debug($e);
        }
    }
	
	
	
	function YahooUrl()
	{
		$this->layout = "json";
		Configure::write("debug",0);
		App::import('Vendor','Yahoo' ,array('file'=>'yahoo/Yahoo.inc'));
		$callback	= $this->settings['site_url']."OpenId/Yahoo?in_popup";
		$url 		= YahooSession::createAuthorizationUrl($this->settings['yahoo_consumer_key'], $this->settings['yahoo_consumer_secret'], $callback);
		
		if(!empty($url))
		{
			 $msg	= array("status"=>true,"uri"=>$url);
		}
		else
		{
			$msg	= array("status"=>false,"uri"=>"");
		}
		$this->set("data",$msg);
		$this->render(false);
	}
	
	function Yahoo()
	{
		$this->layout = "ajax";
		Configure::write("debug",0);
		
		App::import('Vendor','Yahoo' ,array('file'=>'yahoo/Yahoo.inc'));
		$hasSession = YahooSession::hasSession($this->settings['yahoo_consumer_key'], $this->settings['yahoo_consumer_secret'], $this->settings['yahoo_app_id']);
		
		
		if($hasSession == FALSE) {
			$callback	= $this->settings['site_url']."OpenId/Yahoo?in_popup";
			$url 		= YahooSession::createAuthorizationUrl($this->settings['yahoo_consumer_key'], $this->settings['yahoo_consumer_secret'], $callback);
			$this->redirect(array('controller'=>"OpenId","action"=>"Yahoo"));
		}
		else
		{			
			$session 	= YahooSession::requireSession($this->settings['yahoo_consumer_key'], $this->settings['yahoo_consumer_secret'], $this->settings['yahoo_app_id']);
			
			// Get the currently sessioned user.
    		$user = $session->getSessionedUser();
    		
			// Load the profile for the current user.
   			$content 	= $user->getProfile();
			
   			//set email
   			$emails	=	$content->emails;
   			
   			foreach($emails as $emails)
   			{
   				$val	=	explode("@",$emails->handle);
   				$valid	=	explode(".",$val[1]);
   				
   				if(in_array(strtolower($valid[0]),array("yahoo","ymail")))
   				{
   					$email		=	$emails->handle;
   					break;
   				}
   			}
   			
   			//SET PROFILES DATA
			$results				=	array('status'=>true);
			$results['ext_id']		=	$content->guid;
			$results['full_name']	=	$content->nickname;
			$results['email']		=	$email;
			$results['vendor']		=	"yahoo";
			$this->Session->write("RESULTS",$results);
		}
	}
	
	
	function GoogleURL()
	{
		$this->layout	=	"ajax";
		$url			=	"https://accounts.google.com/o/oauth2/auth";
		$params			=	 array(
								"client_id" 			=> "755589510290.apps.googleusercontent.com",
								"redirect_uri" 			=> "http://www.jualanmotor.com/OpenId/GoogleHash/",
								"scope" 				=> 'https://www.google.com/m8/feeds/',
								"response_type" 		=> 'token'
							);
		$header			=	array('Content-Type: application/x-www-form-urlencoded');
		$payload 		=	'';
		$out 			= array();
		foreach ($params as $k=>$v)
		{
			$out [] = "$k=" . urlencode($v);
		}
		$payload 		= implode('&', $out);
		header('Location: '.$url."?".$payload);
		
	}
	
	function GoogleHash()
	{
		$this->layout	=	"ajax";
	}
	
	function GoogleResults()
	{
		/*$access_token	=	$_GET['access_token'];
		$url			=	"https://www.google.com/m8/feeds/contacts/default/full?oauth_token=".$access_token;
		
		//TRY TO SEND PARAMETER
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($ch);
		curl_close($ch);
		echo $out;*/
		
		App::import('Vendor','apiClient' ,array('file'=>'google/src/apiClient.php'));
		App::import('Vendor','apiBuzzService' ,array('file'=>'google/src/contrib/apiBuzzService.php'));
		
		global $apiConfig;
		$apiConfig['oauth2_client_id'] 		= '755589510290.apps.googleusercontent.com';
		$apiConfig['oauth2_client_secret']	= '2JgHctb7srnUrVhTzG8guCGO';

		$apiConfig['oauth2_redirect_uri'] 	= $this->settings['site_url'].'OpenId/GoogleResults?debug=1';
		$apiConfig['authClass'] 			= 'apiOAuth';
		
		$client = new apiClient();
		$buzz = new apiBuzzService($client);
		
		if (isset($_SESSION['access_token'])) {
		  $client->setAccessToken($_SESSION['access_token']);
		} else {
		  $client->setAccessToken($client->authenticate());
		}
		// Make an API call
		$activities = $buzz->activities->listActivities('@consumption', '@me');
		var_dump($activities);
		$this->autoRender	=	false;
	}
	
	function GoogleResults2()
	{
	}
		
	function Google()
	{
		$this->layout = "ajax";
		$email		=	$_GET['openid_ext1_value_email'];
		$firstname	=	$_GET['openid_ext1_value_first'];
		$lastname	=	$_GET['openid_ext1_value_last'];
		$status		=	$_GET['openid_mode'];//return "id_res" or "cancel" 
		$openid_sig	=	$_GET['openid_sig'];

		if($status=="id_res")
		{
			//SET PROFILES DATA
			$results				=	array('status'=>true);
			$results['ext_id']		=	$email;
			$results['full_name']	=	$firstname." ".$lastname;
			$results['email']		=	$email;
			$results['vendor']		=	"google";
			$this->set("ok",1);
		}
		else
		{
			//SET PROFILES DATA
			$results				=	array('status'=>false);
			$results['ext_id']		=	"";
			$results['full_name']	=	"";
			$results['email']		=	"";
			$results['vendor']		=	"google";
			$this->set("ok",0);
		}
		
		$this->Session->write("RESULTS",$results);
		if($_GET['login_type']=="window")
		{
			$this->redirect(array("controller"=>"OpenId","action"=>"Results"));
		}
	}
	
	function Results()
	{
		
		$results		=	$this->Session->read("RESULTS");
		/*if($results['vendor']=="facebook" OR is_null($results))
		{
			$this->__GetFacebookCookie();
			$results		=	$this->Session->read("RESULTS");
		}*/
		$results['email']	=	str_replace("\u0040","@",$results['email']);
		
		//LOAD MODEL
		$this->loadModel('Extid');
		$this->loadModel('User');
		
		//START IF STATUS TRUE
		if($results['status']==true)
		{
			//CHECK IF EXISTS IN EXTID
			$findExt		=	$this->Extid->find('first',array(
									'conditions'	=> array(
										'Extid.extID'		=> $results['ext_id'],
										'Extid.extName'		=> $results['vendor']
									)
								));
			
			//START IF EXTID NOT FOUND
			if($findExt==false)
			{
				//SET IF ISSET USERID
				if(isset($this->user_id))
				{
					//INSERT INTO EXT ID TABLE
					$saveExt	=	$this->Extid->saveAll(
										array(
											'extID'						=>	$results['ext_id'],
											'extName'					=>	$results['vendor'],
											'user_id'					=>	$this->user_id,
											'oauth_token'				=> 	empty($results['oauth_token']) ? NULL : $results['oauth_token'],
											'oauth_token_secret'		=> 	empty($results['oauth_token_secret']) ? NULL : $results['oauth_token_secret']
										),
										false
									);
					
					//SAVE USER ACTIONS
					$text = $this->Action->generateHTML("signin_sonet", array('[username]','[sonet]'), array($this->profile['Profile']['fullname'],ucfirst($results['vendor'])), array("Anda",ucfirst($results['vendor'])));
					$this->Action->save($this->profile['User']['id']);
					
					$search 	=	array('[sonet_name]', '[fullname]', '[email]', '[user_id]', '[external_id]','[date]','[site_name]','[site_url]','[logo_url]');
					$replace 	=	array($results['vendor'],$this->profile['Profile']['fullname'],$this->profile['User']['email'],$this->user_id,$results['ext_id'],date("Y-m-d H:i:s"),$this->settings['site_name'],$this->settings['site_url'],$this->settings['logo_url']);
					$this->Action->EmailSend('admin_alert_sonet_activation',$this->settings['admin_mail'], $search, $replace);
						
					echo "<script>window.close();</script>";
					$this->render(false);
					return;
				}
				
				//START IF NOT EMPTY RESULT EMAIL
				if(!empty($results['email']))
				{
					//CHECK IN TABLE USERS IF EXISTS USERS WITH SAME EMAIL
					$findUsers	=	$this->User->find('first',array(
										'conditions'	=>	array(
											'User.email'			=> $results['email'],
											'User.userstatus_id >= '=> 0
										),
										'order'			=>	array('User.id DESC')
									));
					
					//START IF FIND USER
					if($findUsers)
					{
						//START IF USER STATUS=0
						if($findUsers['User']['userstatus_id']==0)
						{
							$ACTIVATED	=	date("Y-m-d H:i:s");
							//UPDATE USER
							$this->User->updateAll(
								array(
									'userstatus_id'	=>	"'1'",
									'activated'		=>	"'".$ACTIVATED."'"
								),
								array(
									'User.id'		=> $findUsers['User']['id']
								)
							);
							
							//DELETE ALL USER WITH SAME EMAIL
							$this->User->updateAll(
								array(
									'userstatus_id'			=>	"'-2'"
								),
								array(
									'User.email'			=>	$results['email'],
									'User.userstatus_id'	=>	0
								)
							);
							
							
							//UPDATE RANDOM USER
							$this->loadModel('RandomUser');
							$cookie_rand	=	$this->Cookie->read('rand_user');
							$this->RandomUser->updateAll(
								array(
									'user_id'			=>	"'".$findUsers['User']['id']."'"
								),
								array(
									'rand_id'			=>	$cookie_rand,
									"user_id IS NULL"
								)
							);

							//SAVE USER ACTIONS
							$text = $this->Action->generateHTML("register_sonet", array('[some]','[site_name]','[sonet]'), array($findUsers['Profile']['fullname'],$this->settings['site_name'],ucfirst($results['vendor'])), array("Anda",$this->settings['site_name'],ucfirst($results['vendor'])));
							$this->Action->save($findUsers['User']['id']);
							
						}//END IF USER STATUS=0
						else
						{
							//SAVE USER ACTIONS
							$text = $this->Action->generateHTML("signin_sonet", array('[username]','[sonet]'), array($findUsers['Profile']['fullname'],ucfirst($results['vendor'])), array("Anda",ucfirst($results['vendor'])));
							$this->Action->save($findUsers['User']['id']);
							$ACTIVATED	=	$findUsers['User']['activated'];
						}
						
						//CREATE COOKIE
						$this->Cookie->write('userlogin',$this->General->my_encrypt($findUsers["User"]["id"]),false,"1 days",$this->settings['site_url']);
						
						//INSERT INTO EXT ID TABLE
						$saveExt	=	$this->Extid->saveAll(
											array(
												'extID'						=>	$results['ext_id'],
												'extName'					=>	$results['vendor'],
												'user_id'					=>	$findUsers['User']['id'],
												'oauth_token'				=> 	$results['oauth_token'],
												'oauth_token_secret'		=> 	$results['oauth_token_secret']
											),
											false
										);
						$ext_id		=	$results['ext_id'];
						
						//EMAIL TO ADMIN
						$fullname	=	$findUsers['Profile']['fullname'];
						$email		=	$findUsers['User']['email'];
						$user_id	=	$findUsers['User']['id'];
						$sonet_name	=	$results['vendor'];
						$search 	=	array('[sonet_name]', '[fullname]', '[email]', '[user_id]', '[external_id]','[date]','[site_name]','[site_url]','[logo_url]');
						$replace 	=	array($sonet_name,$fullname,$email,$user_id,$ext_id,$ACTIVATED,$this->settings['site_name'],$this->settings['site_url'],$this->settings['logo_url']);
						$this->Action->EmailSend('admin_alert_sonet_activation',$this->settings['admin_mail'], $search, $replace);
							
						//REDIRECT
						$session_back	=	$this->Session->read('back_url');
						$back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
						$this->redirect($back_url);
						
					}//END IF FIND USER WITH EMAIL $result['email']
					$this->set("readonly",true);
					$this->set("bg","#bbbbbb");
				}//END IF NOT EMPTY RESULT EMAIL
				else
				{
					$this->set("readonly",false);
					$this->set("bg","#ffffff");
				}
				$this->set("email",$results['email']);
				$this->set("fullname",$results['full_name']);
				$this->set("vendor",$results['vendor']);
				$this->render('register');
			}//END IF EXTID NOT FOUND
			else
			{
				//SET IF ISSET USERID
				if(isset($this->user_id))
				{
					if($findExt["Extid"]["user_id"]!=$this->user_id)
					{
						$this->set("vendor",$results['vendor']);
						$this->render("open_id_use");
						return;
					}
				}
				
				//SAVE OAUTH IF USER REGISTER BY TWITTER
				if($findExt["Extid"]['extName'] == 'twitter')
				{
					$update		=	$this->Extid->updateAll(
										array(
											'oauth_token'				=> "'".$results['oauth_token']."'",
											'oauth_token_secret'		=> "'".$results['oauth_token_secret']."'"
										),
										array(
											'Extid.extID'				=> $findExt["Extid"]['extID'],
											'Extid.user_id'				=> $findExt["Extid"]['user_id'],
											'Extid.extName'				=> 'twitter'
										)
									);
				}
				
				//CHECK USERS
				$findUsers	=	$this->User->findById($findExt["Extid"]["user_id"]);
				
				//CREATE COOKIE LOGIN
				$this->Cookie->write('userlogin',$this->General->my_encrypt($findUsers["User"]["id"]),false,"1 days",$this->settings['site_url']);
				
				//GET VITUAL ID FOR USER
				$this->Cookie->delete('rand_user');
				$rand	=	$this->Action->GetRandomUser();
				
				//SAVE USER ACTIONS
				$text = $this->Action->generateHTML("signin_sonet", array('[username]','[sonet]'), array($findUsers['Profile']['fullname'],ucfirst($results['vendor'])), array("Anda",ucfirst($results['vendor'])));
				$this->Action->save($findUsers['User']['id']);
				
				//REDIRECT
				$session_back	=	$this->Session->read('back_url');
                $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
				$this->redirect($back_url);
			}//END ELSE IF EXTID FOUND
		}//END IF STATUS TRUE
		else
		{
			$results['vendor']	=	"facebook";
			$this->set("vendor",ucfirst($results['vendor']));
			$this->render("failed");
			return;
		}//END ELSE IF STATUS RESULTS IS FALSE
	}
	
	function UserLogin()
	{
		$this->layout	=	"json";
		$err			=	array();
		if(!empty($this->data))
		{
			$this->data['User']				=	$this->data['Login'];
			$this->data['User']['email']	=	trim($this->data['User']['email']);
			
			$this->loadModel('User');
			$this->loadModel('Extid');
			$results				=	$this->Session->read("RESULTS");
			$this->User->set($this->data);
			$this->User->validateLogin();
			
			if($this->User->validates())
			{
				if ($this->data['User']['keep_login'] == "1") 
				{
					$expired = "365 days";
				} else {
					$expired = "1 days";
				}
				$data			=	$this->User->CheckLogin();
				$session_back	=	$this->Session->read('back_url');
                $back_url		=	isset($session_back) ? $session_back : $this->settings['site_url'];
				$this->Cookie->write('userlogin', $this->General->my_encrypt($data['User']['id']), false, $expired,$this->settings['site_url']);
				
				//GET VITUAL ID FOR USER
				$this->Cookie->delete('rand_user');
				$rand	=	$this->Action->GetRandomUser();
				
				//SAVE USER ACTIONS
				$text = $this->Action->generateHTML("signin_sonet", array('[username]','[sonet]'), array($data['Profile']['fullname'],ucfirst($results['vendor'])), array("Anda",ucfirst($results['vendor'])));
				$this->Action->save($data['User']['id']);
				
				//INSERT INTO EXT ID TABLE
				$saveExt	=	$this->Extid->saveAll(
									array(
										'extID'					=>	$results['ext_id'],
										'extName'				=>	$results['vendor'],
										'user_id'				=>	$data['User']['id'],
										'oauth_token'			=>	!empty($results['oauth_token']) ? $results['oauth_token'] : NULL,
										'oauth_token_secret'	=>	!empty($results['oauth_token_secret']) ? $results['oauth_token_secret'] : NULL
									),
									false
								);
				
				//EMAIL TO ADMIN
				$fullname	=	$data['Profile']['fullname'];
				$email		=	$data['User']['email'];
				$user_id	=	$data['User']['id'];
				$sonet_name	=	$results['vendor'];
				$ACTIVATED	=	$data['User']['activated'];

				$search 	=	array('[sonet_name]', '[fullname]', '[email]', '[user_id]', '[external_id]','[date]','[site_name]','[site_url]','[logo_url]');
				$replace 	=	array($sonet_name,$fullname,$email,$user_id,$results['ext_id'],$ACTIVATED,$this->settings['site_name'],$this->settings['site_url'],$this->settings['logo_url']);
				$this->Action->EmailSend('admin_alert_sonet_activation', $this->settings['admin_mail'], $search, $replace);
				$out		=	array("status"=>true,"error"=>$back_url);
			}
			else
			{
				$error	=	$this->User->InvalidFields();
				foreach($this->data['User'] as $k=>$v)
				{
					if(array_key_exists($k,$error))
					{
						$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);		
					}
					elseif(empty($v) OR (is_array($v) AND empty($v["name"])))
					{
						$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
					}
					else
					{
						$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
					}
				}
				$out	=	array("status"=>false,"error"=>$err);
			}
		}
		$this->set("data",$out);
		$this->render(false);
	}
	
	function UserRegister()
	{
		$this->layout	=	"json";
		$this->loadModel('User');
		$this->loadModel('Profile');
		$this->loadModel('Extid');
		App::import('Sanitize');
		
		$results				=	$this->Session->read("RESULTS");
		$err			=	array();
		
		if(!empty($this->data))
		{
			$this->User->set($this->data);
			$this->User->InitiateValidate();
			if($this->User->validates())
			{
				$this->data['User']['password'] = md5($this->data['User']['password']);
				if(!empty($results['email']))
				{
					$this->data['User']['userstatus_id'] =	1;
                    $this->data['User']['activated']	=	date("Y-m-d H:i:s");
				}

				$user		=	$this->User->save($this->data,false);
				$user_id	=	$this->User->getLastInsertId();
				
				//SAVE PROFILE
				$this->loadModel('Profile');
				$this->data['User']['fullname']		=	Sanitize::html($this->data['User']['fullname']);
				
				$profile	=	$this->Profile->saveAll(
									array(
										'user_id'		=>	$user_id,
										'fullname'		=>	$this->data['User']['fullname']
									)
								);
				
				if(empty($results['email']))
				{
					$vcode	= $this->User->getValidation(trim($this->data['User']['email']));
					
					//SEND VERIFICATION CODES
					App::import('vendor', 'encryption_class');
					$encrypt	= new encryption;
					$param		= $encrypt->my_encrypt($user_id . "|" . $vcode);
					$link 		= $this->settings['site_url'] . "Users/Verification/param:" . $param;
					
					$search 	= array('[logo_url]', '[username]', '[site_name]', '[link]', '[site_url]');
					$replace 	= array($this->settings['logo_url'], $this->data['User']['fullname'], $this->settings['site_name'], $link,$this->settings['site_url']);
					$this->Action->EmailSend('regver', $this->data['User']['email'], $search, $replace);
					
					$out		=	array("status"=>true,"error"=>$this->settings['site_url']."Users/SuccessRegister");
				}
				else
				{
					//SAVE USER ACTIONS
					$text = $this->Action->generateHTML("register_sonet", array('[some]','[site_name]','[sonet]'), array($this->data['User']['fullname'],$this->settings['site_name'],ucfirst($results['vendor'])), array("Anda",$this->settings['site_name'],ucfirst($results['vendor'])));
					$this->Action->save($user_id);
					
					//EMAIL TO ADMIN
					$fullname	=	$this->data['User']['fullname'];
					$email		=	$this->data['User']['email'];
					
					$sonet_name	=	$results['vendor'];
					$search 	=	array('[sonet_name]', '[fullname]', '[email]', '[user_id]', '[external_id]','[date]','[site_name]','[site_url]','[logo_url]');
					$replace 	=	array($sonet_name,$fullname,$email,$user_id,$results['ext_id'],$this->data['User']['activated'],$this->settings['site_name'],$this->settings['site_url'],$this->settings['logo_url']);
					$this->Action->EmailSend('admin_alert_sonet_activation',$this->settings['admin_mail'], $search, $replace);
                                        
                     //CREATE COOKIE
					$this->Cookie->write('userlogin',$this->General->my_encrypt($user_id),false,"1 days",$this->settings['site_url']);
					$out		=	array("status"=>true,"error"=>$this->settings['site_url']."Cpanel/UpdateProfile");
					
				}
				
				//INSERT INTO EXT ID TABLE
				$saveExt	=	$this->Extid->saveAll(
									array(
										'extID'					=>	$results['ext_id'],
										'extName'				=>	$results['vendor'],
										'user_id'				=>	$user_id,
										'oauth_token'			=>	!empty($results['oauth_token']) ? $results['oauth_token'] : NULL,
										'oauth_token_secret'	=>	!empty($results['oauth_token_secret']) ? $results['oauth_token_secret'] : NULL
									),
									false
								);
				
				
				if(!empty($this->data['User']['publish']) and in_array(strtolower($results['vendor']),array("facebook","twitter")))
				{
					$vendorName	=	"Share".ucfirst($results['vendor']);
					$text		=	"Register on ".$this->settings['site_url']." : \n";
					$linktext	=	"See my profile on ".$this->settings['site_url'];
					$linkurl	=	$this->settings['site_url']."Users/UserProfile/".$user_id;
					$this->Action->$vendorName($text,$user_id,$linktext,$linkurl);
				}
			}
			else
			{
				$error	=	$this->User->InvalidFields();
				foreach($this->data['User'] as $k=>$v)
				{
					if(array_key_exists($k,$error))
					{
						$err[]	=	array("key"=>$k,"status"=>"false","value"=>$error[$k]);		
					}
					elseif(empty($v) OR (is_array($v) AND empty($v["name"])))
					{
						$err[]	=	array("key"=>$k,"status"=>"blank","value"=>"");
					}
					else
					{
						$err[]	=	array("key"=>$k,"status"=>"true","value"=>"");
					}
				}
				$out	=	array("status"=>false,"error"=>$err);
			}
		}
		
		$this->set("data",$out);
		$this->render(false);
	}
}
?>