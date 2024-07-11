<?php
    
    //frontend ke liye
    //hosting kw waqt website ka naam dalo google karo
    define('SITE_URL','http://127.0.0.1/plkr/');
    define('ABOUT_IMG_PATH',SITE_URL.'images/about/');
    define('CAROUSEL_IMG_PATH',SITE_URL.'images/carousel/');
    define('FACILITIES_IMG_PATH',SITE_URL.'images/facilities/');
    define('ROOMS_IMG_PATH',SITE_URL.'images/rooms/');
    define('USERS_IMG_PATH',SITE_URL.'images/users/');

    
    //Backend data pr upload krne ka purpose
    define('UPLOAD_IMAGE_PATH',$_SERVER['DOCUMENT_ROOT'].'/plkr/images/');
    define('ABOUT_FOLDER','about/');
    define('CAROUSEL_FOLDER','carousel/');
    define('FACILITIES_FOLDER','facilities/');
    define('ROOMS_FOLDER','rooms/');
    define('USERS_FOLDER','users/');
    
    //PHPMAILER DATA
    define('PASS','xjtebhdnyxagurdq');
    define('EMAIL','t95290595@gmail.com');
    define('NAME','Purshottam');
    
function adminLogin() {
    session_start();
    if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>
                window.location.href='index.php';
              </script>";
    }
}


    function redirect($url)
    {
        echo"<script>window.location.href='$url'</script>";
    }



    function alert($type,$msg)
    
    {
        $bs_class = ($type == "success") ? "alert-success":"alert-danger";
        echo <<<alert
        <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
            <strong class="me-3">$msg</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
alert;
    }

    function uploadImage($image, $folder) 
    {
        $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
        $img_mime = mime_content_type($image['tmp_name']); // Check file content type
    
        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_img'; // Invalid image MIME type or format
        } else if (($image['size'] / (1024 * 1024)) > 8) {
            return 'inv_size'; // Invalid size greater than 8MB
        } else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $rname = 'IMG_' . time() . '_' . bin2hex(random_bytes(4)) . ".$ext"; // Using timestamp and random bytes for uniqueness
    
            $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
    
            if (move_uploaded_file($image['tmp_name'], $img_path)) {
                return $rname;
            } else {
                return 'upd_failed';
            }
        }
    }
    function deleteImage($image, $folder)
    {
      if(unlink(UPLOAD_IMAGE_PATH.$folder.$image)){
        return true;
      }
      else{
        return false;
      }
    }


function uploadSVGImage($image, $folder) {
    $valid_mime = ['image/svg+xml', 'image/png'];
    $img_mime = mime_content_type($image['tmp_name']); // Check file content type

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img'; // Invalid image MIME type or format
    } else if (($image['size'] / (1024 * 1024)) > 1) {
        return 'inv_size'; // Invalid size greater than 1MB
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . time() . '_' . bin2hex(random_bytes(4)) . ".$ext"; // Using timestamp and random bytes for uniqueness

        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;

        if (move_uploaded_file($image['tmp_name'], $img_path)) {
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
}


function uploadUserImage($image)
    {
        $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
        $img_mime = mime_content_type($image['tmp_name']); // Check file content type
    
        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_img'; // Invalid image MIME type or format
        
        } else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $rname = 'IMG_' . time() . '_' . bin2hex(random_bytes(4)) . ".jpeg"; // Using timestamp and random bytes for uniqueness
    
            $img_path = UPLOAD_IMAGE_PATH . USERS_FOLDER . $rname;

            if($ext =='png' || $ext == 'PNG')
            {
                $img = imagecreatefrompng($image['tmp_name']);
            }
 
            else if ($ext =='webp' || $ext == 'WEBP') {
                $img = imagecreatefromwebp($image['tmp_name']);
            } 

            else
            {
                $img = imagecreatefromjpeg($image['tmp_name']);
            }


            if(imagejpeg($img, $img_path, 75))
            {
                return $rname;
            }
            else
            {
                return 'upd_failed';
            }
    
            // if (move_uploaded_file($image['tmp_name'], $img_path)) {
            //     return $rname;
            // } else {
            //     return 'upd_failed';
            // }
        }
    }
    
?>