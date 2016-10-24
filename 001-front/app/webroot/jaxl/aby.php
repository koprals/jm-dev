<?php
/* Include Key file */
include_once("config.ini.php");
  
/* Include JAXL Class */
include_once("jaxl.class.php");


$jaxl = new JAXL(array(  
		'user'		=>	'abyfajar@gmail.com',  
		'pass'		=>	'05011983', // Not required, we will use user session key instead  
		'host'=>'talk.google.com',
    	'domain'=>'gmail.com',
    	'authType'=>'PLAIN',
    	'logLevel'=>4
)); 
var_dump($jaxl);
?>