<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");
session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

$CUST_ID = $_SESSION['uId'];
$TXN_AMOUNT = $_SESSION['room']['payment'];

$user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$CUST_ID], "i");
$user_data = mysqli_fetch_assoc($user_res);

$frm_data = filteration($_POST);

if (isset($frm_data['checkin1']) && isset($frm_data['checkout1'])) {
    $_SESSION['checkin1'] = $frm_data['checkin1'];
    $_SESSION['checkout1'] = $frm_data['checkout1'];
}

$_SESSION['name'] = isset($frm_data['name']) ? $frm_data['name'] : '';
$_SESSION['phonenum'] = isset($frm_data['phonenum']) ? $frm_data['phonenum'] : '';
$_SESSION['address'] = isset($frm_data['address']) ? $frm_data['address'] : '';

echo "<center><h2>Phonepe Payment Gateway Integration</h2></center>";
echo "<center><h2>Please wait, do not refresh</h2></center>";

$merchantId = 'PGTESTPAYUAT'; // sandbox or test merchantId
$apiKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399"; // sandbox or test APIKEY
$keyIndex = 1;

$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => "PS123",
    "merchantUserId" => $frm_data['name'],
    'amount' => $TXN_AMOUNT * 100, // Amount in paisa (10 INR)
    'redirectUrl' => "http://localhost/plkr/phonepaytest/payment-success.php",
    'redirectMode' => "POST",
    'callbackUrl' => "http://localhost/plkr/phonepaytest/payment-failure.php",
    "merchantOrderId" => "12345",
    "mobileNumber" => "9494949485",
    "message" => "Order Le Lo",
    "email" => "abc@gmail.com",
    "shortName" => "Test",
    "paymentInstrument" => array(
        "type" => "PAY_PAGE",
    ),
);

$jsonencode = json_encode($paymentData);
$payloadMain = base64_encode($jsonencode);

$payload = $payloadMain . "/pg/v1/pay" . $apiKey;
$sha256 = hash("sha256", $payload);
$final_x_header = $sha256 . '###' . $keyIndex;
$request = json_encode(array('request' => $payloadMain));

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay", //change this to production url while uploading
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $request,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-VERIFY: " . $final_x_header,
        "accept: application/json",
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $res = json_decode($response);

    if (isset($res->success) && $res->success == '1') {
        $paymentCode = $res->code;
        $paymentMsg = $res->message;
        $payUrl = $res->data->instrumentResponse->redirectInfo->url;

        // Styling for Pay Now link
        $payNowStyle = "display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; font-size: 1.2rem;";

        echo "<center><a href='" . $payUrl . "' class='paynow-link' style='" . $payNowStyle . "'>Pay Now</a></center>";
    }
}
?>
