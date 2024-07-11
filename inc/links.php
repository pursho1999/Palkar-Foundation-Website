<!--Bootstrap CSS-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<!--Fonts-->
<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="admin\css\common.css"/>


<?php
   session_start();
   require('admin/inc/essentials.php');
   require('admin/inc/db_config.php'); 
   
   date_default_timezone_set("Asia/Kolkata");

   $contact_q="SELECT * FROM `contact_details` WHERE `sr_no`=?";
   $settings_q = "SELECT * FROM `settings` WHERE `sr_no` = ?";
   $values=[1];
   $contact_r=mysqli_fetch_assoc(select($contact_q,$values,'i'));
   $settings_r = mysqli_fetch_assoc(select($settings_q, $values, 'i'));
   //require_once('C:\wamp64\www\plkr\admin\inc\essentials.php');

   if($settings_r['shutdown']){
      echo<<<alertbar
        <div class = 'bg-danger text-center p-2 fw-bold'>
         <i class="bi bi-exclamation-triangle-fill"></i>
          Bookings are temporarily closed!!
        </div>
      alertbar;
  
    } 
?>
