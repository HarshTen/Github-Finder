<?php
require 'config.php';

if(isset($_GET['user']) && $_GET['user'] != ""){
  $unsubemail = base64_decode($_GET['user']);
  if (!filter_var($unsubemail, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        die($emailErr);
      }

  // mysql_query("SELECT * FROM email WHERE email='$email'");
  
   
   $stmt = mysqli_prepare($con, "DELETE FROM user WHERE email= ? LIMIT 1");
   mysqli_stmt_bind_param($stmt, "s", $unsubemail);
   $res = mysqli_stmt_execute($stmt);
   //$res is a boolean
  
  //$res = mysqli_query($con,"DELETE FROM user WHERE email='$unsubemail' LIMIT 1");...1

  if($res){
  //if($res > 0)
  echo ("You have been successfully unsubscribed from our services.");
  }
}
?>