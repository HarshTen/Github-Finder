<?php
require 'config.php';
if(isset($_GET['id']) && !empty($_GET['id'])){ 
$id=mysqli_real_escape_string($con,$_GET['id']);
mysqli_query($con,"update user set verification_status='1' where verification_token='$id'");
echo "Your account has been successfully verified";
}

?>