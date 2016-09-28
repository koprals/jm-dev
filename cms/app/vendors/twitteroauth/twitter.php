<?php

class twitter
{
  //FILL IN THESE VALUES!!
  /*private $consumerKey      = CONSUMER_KEY;
  private $consumerSecret   = CONSUMER_SECRET;
  private $oauthToken       = 'DnXFviROD69o0noVgEfKO47bSUG4Zzh5dYNHlwGESM';
  private $oauthTokenSecret = 'ilFVeRXVZLoVR2QhbEAyIHPF8wOzbYE10wL7kyo';*/
  
  
  
  function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
	$this->consumerKey      = $consumer_key;
	$this->consumerSecret   = $consumer_secret;
	$this->oauthToken       = $oauth_token;
	$this->oauthTokenSecret = $oauth_token_secret;
  }
  
  /**
   * Posts status to a twitter account. Returns true if successful, result
   * of curl_getinfo() if failure. 
   */ 
  function postStatus($status)
  {
    return $this->apiCall('https://api.twitter.com/1/statuses/update.xml', array('status'=>$status));
  }
  
  //separate function to leave the door open to other API calls...
  private function apiCall($url, $params)
  {
    $method = 'POST';
    
    //postString covers what will *actually* be posted
    $postString = $this->joinParams($params);
    
    //now adding to $params other OAuth properties...
    $params['oauth_nonce']            = sha1(time() . mt_rand());
    $params['oauth_timestamp']        = time();
    $params['oauth_signature_method'] = 'HMAC-SHA1';
    $params['oauth_version']          = '1.0';
    $params['oauth_consumer_key']     = $this->consumerKey;
    $params['oauth_token']            = $this->oauthToken;
    
    ksort($params); //IMPORTANT!
    $paramString = $this->joinParams($params);
    
    $signatureBaseString = $method . '&' . rawurlencode($url) . '&' . rawurlencode($paramString);
    $signatureKey = $this->consumerSecret . '&' . $this->oauthTokenSecret;
    $params['oauth_signature'] = base64_encode(hash_hmac('sha1', $signatureBaseString, $signatureKey, true));
    
    $authHeader = 'Authorization: OAuth realm=""';
    foreach($params as $key => $val)
      $authHeader .= ", $key=\"" . rawurlencode($val) . "\"";
    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //required for HTTPS URL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader));
    
    $content = curl_exec($ch);
    $resultInfo = curl_getinfo($ch);
    curl_close($ch);
    
    if ($resultInfo['http_code'] == 200)
      return true;
    
    $resultInfo['content'] = $content;
    return $resultInfo;
  }
  
  //Join key/value pairs together in url string format, encoding values.
  private function joinParams($params)
  {
    $paramString = '';
    foreach($params as $key => $val)
    {
      if($paramString !== '')
        $paramString .= '&';
      $paramString .= $key . '=' . rawurlencode($val);
    }
    return $paramString;
  }
}
?>