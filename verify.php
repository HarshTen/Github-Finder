<?php

$msg="";

require 'config.php';


if (isset($_POST['submit'])){
    verifySubscriber();
}

regenerateVerify();

function regenerateVerify(){

    if(!empty($_GET['reverify']) && !empty($_GET['email'])){
        global $con;

        $email =   $_GET['email'];
        $str= "abcdefghijklmnopqrstuvwxyz";
        $verification_token=md5($str);

     
        $stmt = mysqli_prepare($con, "update user set verification_token =?,verification_status= ? where email=? ");
        mysqli_stmt_bind_param($stmt, "sis", $verification_token, $verification_status, $email);
        mysqli_stmt_execute($stmt);
        $msg="We've just sent verification link to $email <br/>Please click on the link to confirm subscription";

        $mail = "$email";
        $name = "xkcd comics on your mail!";
        $body = "Please click on the below button to confirm subscription: <a href='localhost/comics/check.php?id=$verification_token'><br><div style='text-align:center;'><button><strong>Click here to Verify</strong></button></div></a>";
        $subject = "Verification mail";

        $headers = array(
        'Authorization: Bearer '.API_KEY,
        'Content-Type: application/json'
        );

        $data = array(
          "personalizations" => array(
             array(
                "to" => array(
                    array(
                        "email" => $email,
                        "name" => $name
                    )
                )
            )
        ),
         "from" => array(
            "email" => SENDER
         ),
         "subject" => $subject,
         "content" => array(
            array(
                "type" => "text/html",
                "value" => $body
            )
         )
     );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        if($response);
        {
        unset($_SESSION['flash_msg']);

        $_SESSION['flash_msg'] = $msg;

        header("location:index.php");
        die;
        }
    }  
}

function addSubscriber($email){
    global $con;

    $str= "abcdefghijklmnopqrstuvwxyz";
    $verification_token=md5($str);

     
    $stmt = mysqli_prepare($con, "insert into user(email,verification_token,verification_status) values(?,?,0)");
    mysqli_stmt_bind_param($stmt, "ss", $email, $verification_token);
    mysqli_stmt_execute($stmt);
    


    $id=mysqli_insert_id($con);
    $msg="We've just sent verification link to $email <br/>Please click on the link to confirm subscription";

    $mail = "$email";
    $name = "xkcd comics on your mail!";
    $body = "Please click on the below link to confirm subscription: <a href='localhost/comics/check.php?id=$verification_token'><br><button><div style='text-align:center;'><strong>Click here to Verify</strong></div></a></button>";
    $subject = "Verification mail";

    $headers = array(
        'Authorization: Bearer '.API_KEY,
        'Content-Type: application/json'
    );

    $data = array(
        "personalizations" => array(
            array(
                "to" => array(
                    array(
                        "email" => $email,
                        "name" => $name
                    )
                )
            )
        ),
        "from" => array(
            "email" => SENDER
        ),
        "subject" => $subject,
        "content" => array(
            array(
                "type" => "text/html",
                "value" => $body
            )
        )
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

      unset($_SESSION['flash_msg']);
      $_SESSION['flash_msg'] = $msg;
}


function verifySubscriber(){
    global $con;

      /* Sanitization: remove all illegal characters from the email address */
  if(isset($_POST['email']) && !empty($_POST['email'])){ 

       $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
  } else {
    $email = '';
    $mesg = 'Invalid email';
  }
    // tags

    // Validation: Check if this input is a valid email.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
      die($emailErr);
    }

     $stmt = mysqli_prepare($con, "select * from user where email= ?");
     mysqli_stmt_bind_param($stmt, "s", $email);
     mysqli_stmt_execute($stmt);

    $result = $stmt->get_result(); 
    $user = $result->fetch_assoc();

    $result = $stmt->get_result();

    if ($user){

         if($user['verification_status'] == 1){
            $msg="You have already subscribed to our services";
          }else {           
           $msg="We have already sent the verification link on your email. Please check your inbox.<br>If not found, 
           <a href='index.php?reverify=1&email=$email'><button>click here </a></button> to get the new verification link";
          }
             unset($_SESSION['flash_msg']);
           $_SESSION['flash_msg'] = $msg;
    }else{
           addSubscriber($email);
         }    
        }
?>