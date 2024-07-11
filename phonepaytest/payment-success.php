<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('../index.php');
}

function ord_id($uId)
{
    global $con; // Assuming $con is your database connection object

    // Loop until a unique order ID is generated
    do {
        $order_id = 'ORD_' . $uId . random_int(1111111111, 99999999999);
        $check_query = "SELECT COUNT(*) AS count FROM `booking_order` WHERE `order_id` = ?";
        $result = select($check_query, [$order_id], 's');
        $count = mysqli_fetch_assoc($result)['count'];
    } while ($count > 0); // Continue if the order ID already exists

    return $order_id;
}

// Function to generate transaction ID
function trans_id()
{
    global $con; // Assuming $con is your database connection object

    // Loop until a unique transaction ID is generated
    do {
        $transaction_id = 'TXN_' . uniqid();
        $check_query = "SELECT COUNT(*) AS count FROM `booking_order` WHERE `trans_id` = ?";
        $result = select($check_query, [$transaction_id], 's');
        $count = mysqli_fetch_assoc($result)['count'];
    } while ($count > 0); // Continue if the transaction ID already exists

    return $transaction_id;
}

// Retrieve session variables
$checkin1 = $_SESSION['checkin1'];
$checkout1 = $_SESSION['checkout1'];
$name = $_SESSION['name'];
$phonenum = $_SESSION['phonenum'];
$address = $_SESSION['address'];

$CUST_ID = $_SESSION['uId'];
$TXN_AMOUNT = $_SESSION['room']['payment'];

// Retrieve user data from the database
$user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$CUST_ID], "i");
$user_data = mysqli_fetch_assoc($user_res);

$frm_data = filteration($_POST);

// Insert booking order into the database
$query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `booking_status`, `order_id`, `trans_id`, `trans_amt`, `trans_status`) VALUES (?,?,?,?,?,?,?,?,?)";
insert($query1, [$CUST_ID, $_SESSION['room']['id'], $_SESSION['checkin1'], $_SESSION['checkout1'], 'Booked', ord_id($CUST_ID), trans_id(), $TXN_AMOUNT, 'Success'], 'iisssssss');

$booking_id = mysqli_insert_id($con);

// Insert booking details into the database
$query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";
insert($query2, [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $TXN_AMOUNT, $name, $phonenum, $address], 'issssss');

// Remove session variables
// unset($_SESSION['checkin1']);
// unset($_SESSION['checkout1']);
// unset($_SESSION['name']);
// unset($_SESSION['phonenum']);
// unset($_SESSION['address']);
// //unset($_SESSION['uId']);
// unset($_SESSION['room']);

// Destroy the session
session_destroy();

// Clear cookies if any
setcookie(session_name(), '', time()-3600, '/');

//echo "Payment Successful";
?>


<!DOCTYPE html>
<html>
<head>
 <title>Payment Successful</title>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
 <style>
  body {
   background-color: #e6f2ff;
  }
  .payment-container {
   display: flex;
   justify-content: center;
   align-items: center;
   height: 100vh;
  }
  .payment-success {
   text-align: center;
   padding: 20px;
   background-color: #fff;
   border-radius: 5px;
   box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
   max-width: 600px;
   margin: 0 auto;
   margin-top: -100px;
   padding-top: 100px;
   box-sizing: border-box;
  }
  .payment-success h1 {
   font-size: 2rem;
   margin-bottom: 0.5rem;
   color: #28a745;
  }
  .payment-success p {
   font-size: 1.1rem;
   margin-bottom: 1rem;
   color: #6c757d;
  }
  .countdown-container {
   font-size: 2rem;
   font-weight: bold;
   color: #28a745;
   margin-top: 2rem;
   text-align: center;
   animation: fadeIn 2s;
  }

  @keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
  }
 </style>
</head>
<body>
 <div class="payment-container">
  <div class="payment-success">
   <h1>Payment Successful</h1>
   <p>Your payment has been processed successfully. Thank you for your business!</p>
   <div class="countdown-container">
     You will be redirected to the homepage in <span id="countdown">5</span> seconds.
   </div>
  </div>
 </div>

 <script>
  var countdown = 5;
  var countdownInterval = setInterval(function() {
    countdown--;
    if (countdown <= 0) {
      clearInterval(countdownInterval);
      window.location.href = "../index.php";
    }
    document.querySelector("#countdown").innerHTML = countdown;
  }, 1000);
 </script>
</body>
</html>