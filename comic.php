<?php


include('db.php');

$result=mysqli_query($con,"SELECT email FROM user WHERE verification_status='1'");
while($row = mysqli_fetch_assoc($result)){
    $email = $row['email'];
    SendMailnow($email);

}

function get_json_data($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}



function getRandomComic(){
  $random = random_int(1, 620);
  
  $comic = get_json_data("https://xkcd.com/$random/info.0.json");
  
  return $comic['img'];
}

function SendMailnow($email){




    $comic = getRandomComic();
    $file = 'comic.png';
    
    $current = file_get_contents($comic);
    
    file_put_contents($file, $current);
    $filePath = dirname(__FILE__); 
    $mail = $email;

    $email=base64_encode($email);
    $name = "xkcd comic";
    $body = "<img src=$comic><br><div style='text-align:center;'><a href='localhost/comics/unsubscribe.php?user=$email'>Click here to Unsubscribe</a></div>";
    

    $subject = "Verification mail";

    $headers = array(
        'Authorization: ',
        'Content-Type: application/json'
    );

    $data = array(
        "personalizations" => array(
            array(
                "to" => array(
                    array(
                        "email" => $mail,
                        "name" => $name
                    )
                )
            )
        ),
        "from" => array(
            "email" => '' 

        ),
        "subject" => $subject,
        "content" => array(
            array(
                "type" => "text/html",
                "value" => $body
            )
        ), 
        'attachments' =>array(
          array(
              'content' => base64_encode($current), 
              'filename' => 'comic.png', 
               'type'=> 'image',
               //'disposition'
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

    echo $response;

   }

?>