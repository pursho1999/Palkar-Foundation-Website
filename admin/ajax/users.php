<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


    if (isset($_POST['get_users'])) {
        $res = selectAll('user_cred');
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


            // $date = "";
            // if (isset($row['dateandtime']) && !empty($row['dateandtime'])) {
                $date = date("d-m-Y : H:m:s", strtotime($row['dateandtime']));
            // }

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


    if (isset($_POST['toggle_status'])) {
        $frm_data = filteration($_POST);

        $q = "UPDATE `user_cred` SET`status`=? WHERE `id`=?";
        $v = [$frm_data['value'], $frm_data['toggle_status']];

        if (update($q, $v, 'ii')) {
            echo 1;
        } else {
            echo 0;
        }
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


            $date = date("d-m-Y : H:m:s",strtotime($row['dateandtime']));

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
