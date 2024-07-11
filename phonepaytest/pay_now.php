	<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

	
	echo "<center><h2>Phonepe Payment Gateway Integration</h2></center>";
	echo "<center><h2>Please wait so not refresh</h2></center>";

	// $merchantId = 'PGTESTPAYUAT'; // sandbox or test merchantId
	// $apiKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399"; // sandbox or test APIKEY
	// $keyIndex = 1;

	// // Prepare the payment request data (you should customize this)
	// $paymentData = array(
	// 	'merchantId' => $merchantId,
	// 	'merchantTransactionId' => "PS123",
	// 	"merchantUserId" => "301Ps",
	// 	'amount' => 10000, // Amount in paisa (10 INR)
	// 	'redirectUrl' => "https://curst-lists.000webhostapp.com/payment-success.php",
	// 	'redirectMode' => "POST",
	// 	'callbackUrl' => "https://curst-lists.000webhostapp.com/payment-failure.php",
	// 	"merchantOrderId" => "12345",
	// 	"mobileNumber" => "9494949485",
	// 	"message" => "Order Le Lo",
	// 	"email" => "abc@gmail.com",
	// 	"shortName" => "Test",
	// 	"paymentInstrument" => array(
	// 		"type" => "PAY_PAGE",
	// 	),
	// );

	// $jsonencode = json_encode($paymentData);
	// $payloadMain = base64_encode($jsonencode);

	// $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
	// $sha256 = hash("sha256", $payload);
	// $final_x_header = $sha256 . '###' . $keyIndex;
	// $request = json_encode(array('request' => $payloadMain));

	// $curl = curl_init();
	// curl_setopt_array($curl, [
	// 	CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay", //change this to production url while uploading
	// 	CURLOPT_RETURNTRANSFER => true,
	// 	CURLOPT_ENCODING => "",
	// 	CURLOPT_MAXREDIRS => 10,
	// 	CURLOPT_TIMEOUT => 30,
	// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 	CURLOPT_CUSTOMREQUEST => "POST",
	// 	CURLOPT_POSTFIELDS => $request,
	// 	CURLOPT_HTTPHEADER => [
	// 		"Content-Type: application/json",
	// 		"X-VERIFY: " . $final_x_header,
	// 		"accept: application/json"
	// 	],
	// ]);

	// $response = curl_exec($curl);
	// $err = curl_error($curl);

	// curl_close($curl);

	// if ($err) {
	// 	echo "cURL Error #:" . $err;
	// } else {
	// 	$res = json_decode($response);

	// if(isset($res->success) && $res->success=='1'){
	// $paymentCode=$res->code;
	// $paymentMsg=$res->message;
	// $payUrl=$res->data->instrumentResponse->redirectInfo->url;
	// echo "<a href='" . $payUrl . "'>PayNow</a>";
	// header('Location:'.$payUrl) ;
	// }
	// }


	// ?>
