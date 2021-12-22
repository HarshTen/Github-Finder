<?php 
$setup = array(
'LIVE'=>array(
   'database'=>array(
     'host'=>'',
     'username'=>'',
     'password'=>'',
     'database_name'=>'',
   ),
   'MAIL_API'=>array(

     'sender'=>'',
     'api_key'=>'',
   )
),

'TEST'=>array(
   'database'=>array(
     'host'=>'',
     'username'=>'',
     'password'=>'',
     'database_name'=>'',
   ),
   'MAIL_API'=>array(

     'sender'=>'',
     'api_key'=>'',
   )
),

);
// SITE_MODE can be LIVE  |  TEST
define('SITE_MODE', 'TEST');

$db_args =  $setup[SITE_MODE]['database'];

$con=mysqli_connect($db_args['host'],$db_args['username'],$db_args['password'],$db_args['database_name']);
$mail_api = $setup[SITE_MODE]['MAIL_API'];

// MAIL_API will have array
define('SENDER', $mail_api['sender']);
// MAIL_API will have array
define('API_KEY', $mail_api['api_key']);

?>