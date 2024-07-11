<?php 

  require('admin/inc/essentials.php');
  require('admin/inc/db_config.php');
  require('admin/inc/mpdf/vendor/autoload.php');



  if(isset($_GET['gen_pdf']) && isset($_GET['id']))
  {
    $frm_data = filteration($_GET);

    $query = "SELECT bo.*, bd.*,uc.email FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      INNER JOIN `user_cred` uc ON bo.user_id = uc.id
      WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR (bo.booking_status='payment failed')) 
      AND bo.booking_id = '$frm_data[id]'";

    $res = mysqli_query($con,$query);
    $total_rows = mysqli_num_rows($res);

    if($total_rows==0){
      header('location: index.php');
      exit;
    }

    $data = mysqli_fetch_assoc($res);

    $date = date("h:ia | d-m-Y",strtotime($data['dateandtime']));
    $checkin = date("d-m-Y",strtotime($data['check_in']));
    $checkout = date("d-m-Y",strtotime($data['check_out']));

    $table_data = "
              <style>
            body {
              font-family: Arial, sans-serif;
              background-color: #f8f9fa;
              margin: 0;
              padding: 20px;
            }
            h2 {
              color: #333;
              text-align: center;
              margin-bottom: 20px;
            }
            table {
              width: 100%;
              border-collapse: collapse;
              margin: 0 auto;
              background-color: #fff;
              border-radius: 8px;
              overflow: hidden;
              box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            th, td {
              padding: 12px;
              text-align: left;
              border-bottom: 1px solid #ddd;
            }
            th {
              background-color: #f2f2f2;
            }
            .highlight {
              background-color: #ffc107;
              font-weight: bold;
            }
            .odd {
              background-color: #f5f5f5;
            }
            .bold {
              font-weight: bold;
            }
          </style>

  
          <h2>BOOKING RECEIPT</h2>
          <table border='1'>
            <tr>
              <td class='bold'>Order ID:</td>
              <td>$data[order_id]</td>
            </tr>
            <tr>
              <td class='bold'>Booking Date:</td>
              <td>$date</td>
            </tr>
            <tr class='highlight'>
              <td colspan='2'>Status: $data[booking_status]</td>
            </tr>
            <tr class='odd'>
              <td class='bold'>Name:</td>
              <td>$data[user_name]</td>
            </tr>
            <tr>
              <td class='bold'>Email:</td>
              <td>$data[email]</td>
            </tr>
            <tr class='odd'>
              <td class='bold'>Phone Number:</td>
              <td>$data[phonenum]</td>
            </tr>
            <tr>
              <td class='bold'>Address:</td>
              <td>$data[address]</td>
            </tr>
            <tr class='odd'>
              <td class='bold'>Room Name:</td>
              <td>$data[room_name]</td>
            </tr>
            <tr>
              <td class='bold'>Cost:</td>
              <td>₹$data[price] per day</td>
            </tr>
            <tr class='odd'>
              <td class='bold'>Check-in:</td>
              <td>$checkin</td>
            </tr>
            <tr>
              <td class='bold'>Check-out:</td>
              <td>$checkout</td>
            </tr>
      
    ";

    if($data['booking_status']=='cancelled')
    {
      $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";

      $table_data.="<tr>
        <td  class='bold'>Amount Paid: ₹$data[trans_amt]</td>
        <td  class='bold'>Refund: $refund</td>
      </tr>";
    }
    else if($data['booking_status']=='payment failed')
    {
      $table_data.="<tr>
        <td  class='bold'>Transaction Amount: ₹$data[trans_amt]</td>
        <td  class='bold'>Failure Response: $data[trans_resp_msg]</td>
      </tr>";
    }
    else
    {
      $table_data.="<tr>
        <td  class='bold'>Room Number: $data[room_no]</td>
        <td  class='bold'>Amount Paid: ₹$data[trans_amt]</td>
      </tr>";
    }

    $table_data.="</table>";

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($table_data);
    $mpdf->Output($data['order_id'].'.pdf','D');

  }
  else{
    header('location: index.php');
  }
  
?>