<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Kolkata");


session_start();


    $user_q = select($con, "SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$uid], 'i');
    $user_fetch = mysqli_fetch_assoc($user_q);

    $_SESSION['login'] = true;
    $_SESSION['uId'] = $user_fetch['id'];
    $_SESSION['uName'] = $user_fetch['name'];
    $_SESSION['uPic'] = $user_fetch['profile'];
    $_SESSION['uPhone'] = $user_fetch['phonenum'];

// Check if user is logged in
if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    regenrate_session($slct_fetch['user_id'], $con); // Assuming $slct_fetch['user_id'] and $con are defined
}

// Generate random transaction ID
$transaction_id = 'TXN_' . uniqid();

// Update booking order with transaction details
$upd_query = "UPDATE `booking_order` SET `booking_status`='booked', `trans_id`='$transaction_id', `trans_amt`='{$_POST['TXN_AMOUNT']}',
    `trans_status`='Transaction Success' WHERE `booking_id`='{$slct_fetch['booking_id']}'";

mysqli_query($con, $upd_query);

redirect('index.php'); // Redirect to index page after updating booking order

?>
