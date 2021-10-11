<?php session_start(); ?>
<?php
require 'verify.php';

// var_dump($_SESSION);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
<div class="subscription form">	
	<form method="post" >
		<h2> Subscribe to xkcd comics!</h2>
		<div class="form-group">
				<input type="email" class="form-control" name="email" placeholder="Email" required="required">
			</div>
		<div class="form-group">
			<button type="submit" name="submit" class="btn btn-success btn-lg btn-block">Subscribe</button>
		</div>
<?php 

 if(!empty($_SESSION['flash_msg'])){
 	echo $_SESSION['flash_msg'];
 	unset($_SESSION['flash_msg']);
 }
 ?>
</form>
</body>
</html>