<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


    if (isset($_POST['get_bookings'])) 
    {

        $frm_data = filteration($_POST);

        $query = "SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) 
      AND (bo.booking_status=? AND bo.arrival=?) ORDER BY bo.booking_id ASC";

    $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","booked",0],'sssss');
    
                $i=1;
        
        $table_data = "";

        if(mysqli_num_rows($res)==0){
            echo"<b>No Data Found!</b>";
            exit;
          }
          
        while($data = mysqli_fetch_assoc($res))
    {
      $date = date("d-m-Y",strtotime($data['dateandtime']));
      $checkin = date("d-m-Y",strtotime($data['check_in']));
      $checkout = date("d-m-Y",strtotime($data['check_out']));

      $table_data .="
            <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>
                Order ID: $data[order_id]
                </span>
                <br>
                <b>Name:</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
            </td>
            <td>
                <b>Room:</b> $data[room_name]
                <br>
                <b>Price:</b> ₹$data[price]
            </td>
            <td>
                <b>Check-in:</b> $checkin
                <br>
                <b>Check-out:</b> $checkout
                <br>
                <b>Paid:</b> ₹$data[trans_amt]
                <br>
                <b>Date:</b> $date
            </td>
            <td>
            <button type='button' onclick='assign_room($data[booking_id])' class='btn text-white btn-sm fw-bold custom-bg shadow-none' data-bs-toggle='modal' data-bs-target='#assign-room'>
              <i class='bi bi-check2-square'></i> Assign Room
            </button>
            <br>
            <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none'>
              <i class='bi bi-trash'></i> Cancel Booking
            </button>
          </td>
            </tr>
        ";

      $i++;
    }
        echo $table_data;
    }



    if(isset($_POST['assign_room']))
  {
    $frm_data = filteration($_POST);

    $query = "UPDATE `booking_order` bo INNER JOIN `booking_details` bd
      ON bo.booking_id = bd.booking_id
      SET bo.arrival = ?, bo.rate_review = ?, bd.room_no = ? 
      WHERE bo.booking_id = ?";

    $values = [1,0,$frm_data['room_no'],$frm_data['booking_id']];

    $res = update($query,$values,'iisi'); // it will update 2 rows so it will return 2

    echo ($res==2) ? 1 : 0;
  }


  if(isset($_POST['cancel_booking']))
  {
    $frm_data = filteration($_POST);
    
    $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
    $values = ['cancelled',0,$frm_data['booking_id']]; //booking status=cancelled   redund=0  booking id se ye values database me edit hoyege
    $res = update($query,$values,'sii');

    echo $res;
  }







    if (isset($_POST['remove_user']))
    {
        $frm_data = filteration(($_POST));
        
        $res = delete("DELETE FROM `user_cred` WHERE `id`=? AND `is_verified`=?",[$frm_data['user_id'],0],'ii');

        if($res)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }


    if (isset($_POST['search_user'])) {

        $frm_data = filteration($_POST);

        $query = "SELECT * FROM `user_cred` WHERE 
        `name` LIKE ? OR 
        `email` LIKE ? OR 
        `phonenum` LIKE ? OR 
        `address` LIKE ?";      //LIKE operator searches for a specific pattern or patterns

        $res = select($query, ["%{$frm_data['name']}%", "%{$frm_data['name']}%", "%{$frm_data['name']}%", "%{$frm_data['name']}%"], 'ssss');
        $i = 1;
        $path = USERS_IMG_PATH;

        $data = "";
       
        while ($row = mysqli_fetch_assoc($res)) {

            $del_btn = " <button type='button' onclick='remove_user($row[id])' class='btn btn-danger text-white fw-bold shadow-none btn-sm mb-1'>
                <i class='bi bi-trash'></i>
            </button>";
           
            $verified = " <span class='badge bg-danger'><i class='bi bi-x-lg'></i></span>";
           
            if($row['is_verified']){
                $verified = "<span class='badge bg-success '><i class='bi bi-person-check-fill'></i></span>";
                $del_btn = "";
            }

            $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-sm shadow rounded text-white fw-bold custom-bg'>Active</button>";

            if(!$row['status']){
                $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-sm shadow rounded text-dark btn-danger'>Inactive</button>";                
            }


            $date = date("d-m-Y : H:m:s",strtotime($row['datentime']));

            $data .= "
                    <tr>
                        <td>$i</td>
                        <td>
                            <img src = '$path$row[profile]' width = '50px' class='mt-2'>
                            <br>
                            $row[name]
                        </td>                        
                        <td>$row[email]</td>                        
                        <td>$row[phonenum]</td>                        
                        <td>$row[address] | $row[pincode]</td>                        
                        <td>$row[dob]</td>                        
                        <td>$verified</td>                        
                        <td>$status</td>                        
                        <td>$date</td>                        
                        <td>$del_btn</td>                        
                    </tr>
                ";
            $i++;
        }

        echo $data;
    }




?>
