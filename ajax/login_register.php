
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('../admin/inc/essentials.php');
require('../admin/inc/db_config.php');
require('../inc/PHPMailer/src/Exception.php');
require('../inc/PHPMailer/src/PHPMailer.php');
require('../inc/PHPMailer/src/SMTP.php');

date_default_timezone_set("Asia/Kolkata");

function send_mail($email, $token,$type)
{

  if($type == "email_confirmation")
    {
      $page = 'email_confirm.php';
      $subject = "Account Verification Link";
      $content = "Confirm your email";
    }
    else
    {
      $page = 'index.php';
      $subject = "Account Reset Link";
      $content = "Reset your Account";
    }
  $mail = new PHPMailer(true);

  try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL; // Replace with your Gmail address
    $mail->Password = PASS; // Replace with your Gmail password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Sender info
    $mail->setFrom(EMAIL, NAME); // Replace with your name and Gmail address

    // Recipient info
    $mail->addAddress($email, NAME);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;

    $mail->msgHTML("
      <html>
        <body>
          <p>Click link to $content:</p>
          <p><a href = '" . SITE_URL . "$page?$type&email=$email&token=$token" . "'>CLICK HERE</a></p>
        </body>
      </html>
    ");

    

    if($mail->send($email)) 
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }
  catch(Exception $e)
  {}

  

}
//<!-- xjte bhdn yxag urdq -->

  //  if (isset($_POST['register'])) {
  
  //   $data = filteration($_POST);

  //   //match password and confirm password

  //   if ($data['pass'] != $data['cpass']) {
  //     echo 'password_mismatch';
  //     exit;
  //   }

  
  //   $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1", 
  //   [$data['email'], $data['phonenum']], "ss");

  //   if (mysqli_num_rows($u_exist) != 0) {
  //     $u_exist_fetch = mysqli_fetch_assoc($u_exist);
  //     echo ($u_exist_fetch['email'] == $data['email']) ? 'Email_already' : 'Phone_already';
  //     exit;
  //   }


  //   //Uploading user image to the Server

  //   $img = uploadUserImage($_FILES['profile']);

  //   if ($img == 'inv_img') {
  //     echo 'inv_img';
  //     exit;
  //   } else if ($img == 'upd_failed') {
  //     echo 'upd_failed';
  //     exit;
  //   }

  //   // Send Confirmation Link to User's Email for verification

  //   $token = bin2hex(random_bytes(16));

  //   if (!send_mail($data['email'],$token,"email_confirmation")) {
  //     //if (!send_mail($data['email'], $data['name'], $token)) {
  //     echo 'mail_failed';
  //     exit;
  //   }

  //   $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);


  //   $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `token`) VALUES (?,?,?,?,?,?,?,?,?)";

  //   $values = [$data['name'], $data['email'], $data['address'], $data['phonenum'], $data['pincode'], $data['dob'], $img, $enc_pass, $token];


  //   if (insert($query, $values, 'sssssssss')) {
  //     echo 1;
  //   } else {
  //     echo 'insertion-failed';
  //   }
  // }


  if (isset($_POST['register'])) {
  $data = filteration($_POST);

  //match password and confirm password

  if ($data['pass'] != $data['cpass']) {
    echo 'password_mismatch';
    exit;
  }

  //User exists or not ?
  $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1", [$data['email'], $data['phonenum']], "ss");

  if (mysqli_num_rows($u_exist) != 0) {
    $u_exist_fetch = mysqli_fetch_assoc($u_exist);
    echo ($u_exist_fetch['email'] == $data['email']) ? 'Email_already' : 'Phone_already';
    exit;
  }


  //Uploading user image to the Server

  $img = uploadUserImage($_FILES['profile']);

  if ($img == 'inv_img') {
    echo 'inv_img';
    exit;
  } else if ($img == 'upd_failed') {
    echo 'upd_failed';
    exit;
  }

  // Send Confirmation Link to User's Email for verification

  $token = bin2hex(random_bytes(16));

  if (!send_mail($data['email'], $token, "email_confirmation")) {  
    echo 'mail_failed';
    exit;
  }

  $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);


  $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `token`) VALUES (?,?,?,?,?,?,?,?,?)";

  $values = [$data['name'], $data['email'], $data['address'], $data['phonenum'], $data['pincode'], $data['dob'], $img, $enc_pass, $token];

  if (insert($query, $values, 'sssssssss')) {
    echo 1;
  } else {
    echo 'insertion-failed';
  }
  }


  if (isset($_POST['login'])) 
  {
    $data = filteration($_POST);

    //Checking if user exists or not
    $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1", [$data['email_mob'], $data['email_mob']], "ss");

    if (mysqli_num_rows($u_exist) == 0) {
      echo 'inv_email_mob';
    }
    else
    {
    $u_fetch = mysqli_fetch_assoc($u_exist);
    if($u_fetch['is_verified']==0)
    {
      echo 'not_verified';
    }
    else if($u_fetch['status']==0){
      echo 'inactive';
    }
    else
    {
      if(!password_verify($data['pass'],$u_fetch['password']))
      {
        echo 'invalid_pass';
      }
      else
      {
        session_start();
        $_SESSION['login'] = true;
        $_SESSION['uId'] = $u_fetch['id'];
        $_SESSION['uName'] = $u_fetch['name'];
        $_SESSION['uPic'] = $u_fetch['profile'];
        $_SESSION['uPhone'] = $u_fetch['phonenum'];
        echo 1;
      }
    }
    }
  }

  if (isset($_POST['forgot_pass'])) 
  {
    $data = filteration($_POST);

    //Checking if user exists or not
    $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? LIMIT 1", [$data['email']], "s");

    if (mysqli_num_rows($u_exist) == 0) {
      echo 'inv_email';
    }
    else
    {
      $u_fetch = mysqli_fetch_assoc($u_exist);
      if($u_fetch['is_verified']==0)
      {
        echo 'not_verified';
      }
      else if($u_fetch['status']==0){
        echo 'inactive';
      }
      else
      {
        //sending reset link
        $token = bin2hex(random_bytes(16)); //binarytohexadeciaml

        if (!send_mail($data['email'], $token, 'account_recovery'))
        {
          echo 'mail_failed';
        }
        else
        {         
          $date = date("Y-m-d");

          $query = mysqli_query($con, "UPDATE `user_cred` SET `token`='$token',`t_expire`='$date'  WHERE `id`='$u_fetch[id]'");

          if($query)
          {
            echo 1;
          }
          else
          {
            echo 'upd_failed';
          }
        }
      }
    }

  }

  if (isset($_POST['recover_user'])) 
  {
    $data = filteration($_POST);

    $enc_pass = password_hash($data['pass'],PASSWORD_BCRYPT);

    $query = "UPDATE `user_cred` SET `password`=? , `token`=? ,`t_expire`=?  WHERE `email`=? AND `token`=?";

    $values = [$enc_pass,null,null,$data['email'],$data['token']];

    if(update($query,$values,'sssss'))
    {
      echo 1;
    }
    else
    {
      echo 'failed';
    }
  }
  //send_mail($data['email'],$data['name']);
?>
