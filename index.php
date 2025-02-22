<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <?php require('inc/links.php');?>
      <title><?php echo $settings_r['site_title'] ?> - Home</title>
      <!-- SwiperJs -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

      <style>
         .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
         }
      
         @media screen and (max-width: 575px) {
            .availability-form {
               margin-top: 25px;
               padding: 0 35px;
            }
         }
      </style>
      
   </head>
   <body class="bg-light">

      <?php require('inc/header.php');?>
     
      <!-- Sliding BG -->
      <div class="container-fluid px-lg-4 mt-4">
         
         <div class="swiper mySwiper">
            <div class="swiper-wrapper">

         <?php
            $res = selectAll('carousel');
            while($row = mysqli_fetch_assoc($res))
            {
               $path = CAROUSEL_IMG_PATH;
               echo <<< data
                  
                  <div class="swiper-slide">
                     <img src="$path$row[image]" class="w-100 d-block" />
                  </div>
               data;
            }


         ?>

             
            </div>
         </div>
      </div>

      

      <div class="container availability-form">
    <div class="row">
      <div class="col-lg-12 bg-white shadow p-4 rounded">
        <h5 class="mb-4">Check Booking Availability</h5>
        <form action="rooms.php">
          <div class="row align-items-end">
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;">Check-in</label>
              <input type="date" class="form-control shadow-none" name="checkin" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;">Check-out</label>
              <input type="date" class="form-control shadow-none" name="checkout" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;">Adult</label>
              <select class="form-select shadow-none" name="adult">
                <?php 
                  $guests_q = mysqli_query($con,"SELECT MAX(adult) AS `max_adult`, MAX(children) AS `max_children` 
                    FROM `rooms` WHERE `status`='1' AND `removed`='0'");  
                  $guests_res = mysqli_fetch_assoc($guests_q);
                  
                  for($i=1; $i<=$guests_res['max_adult']; $i++){
                    echo"<option value='$i'>$i</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight: 500;">Children</label>
              <select class="form-select shadow-none" name="children">
                <?php 
                  for($i=1; $i<=$guests_res['max_children']; $i++){
                    echo"<option value='$i'>$i</option>";
                  }
                ?>
              </select>
            </div>
            <input type="hidden" name="check_availability">
            <div class="col-lg-1 mb-lg-3 mt-2">
              <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


      <!-- Rooms -->
      <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">OUR ROOMS</h2>
      <!-- room cards start -->
      <div class="container">
         <div class="row">

         <?php

$room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` LIMIT 3 ",[1,0],'ii');

while($room_data = mysqli_fetch_assoc($room_res))
{
    //getting features from room

    $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f INNER JOIN `room_features` 
    rfea ON f.id = rfea.features_id WHERE rfea.room_id = '$room_data[id]'");  
    
    $features_data = "";
    while($fea_row = mysqli_fetch_assoc($fea_q))
    {
        $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>$fea_row[name]</span>";
    }

    
    //getting facilities from room

    $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f INNER JOIN `room_facilities` 
    rfac ON f.id = rfac.facilities_id WHERE rfac.room_id = '$room_data[id]'");

    $facilities_data = "";
    while($fac_row = mysqli_fetch_assoc($fac_q))
    {
        $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>$fac_row[name]</span>";
    }

    //getting thumbnail for the rooms

    $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
    $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` WHERE `room_id`='$room_data[id]' AND
    `thumb` ='1' ");

    if(mysqli_num_rows($thumb_q)>0)     
    {
        $thumb_res = mysqli_fetch_assoc($thumb_q);
        $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
    }

    $book_btn = "";

          if(!$settings_r['shutdown']){
            $login=0;
            if(isset($_SESSION['login']) && $_SESSION['login']==true){
              $login=1;
            }

            $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>Book Now</button>";
          }

          $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
            WHERE `room_id`='$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

          $rating_res = mysqli_query($con,$rating_q);
          $rating_fetch = mysqli_fetch_assoc($rating_res);

          $rating_data = "";

          if($rating_fetch['avg_rating']!=NULL)
          {
            $rating_data = "<div class='rating mb-4'>
              <h6 class='mb-1'>Rating</h6>
              <span class='badge rounded-pill bg-light'>
            ";

            for($i=0; $i<$rating_fetch['avg_rating']; $i++){
              $rating_data .="<i class='bi bi-star-fill text-warning'></i> ";
            }

            $rating_data .= "</span>
              </div>
            ";
          }


    echo <<<data

        <div class="col-lg-4 col-md-6 my-3">
            
            <!-- Executive Room -->
            <!-- col-md-6 is for column on medium devices -->
            <div class="card border-0 shadow" style="max-width: 400px; height: 700px; margin: auto;">
                <img src="$room_thumb" class="card-img-top" style="height:250px;">

                <div class="card-body">
                    <h5>$room_data[name]</h5>
                    <h6 class="mb-4"> ₹ $room_data[price] per night</h6>

                    <!-- Features section -->
                    <div class="features mb-3">
                        <h6 class="mb-1">Features</h6>
                        $features_data
                    </div>

                    <!-- Facilities section -->
                    <div class="facilities mb-3">
                        <h6 class="mb-1">Facilities</h6>
                        $facilities_data
                    </div>

                    <!-- Guests section -->
                    <div class="guests mb-3">
                        <h6 class="mb-1">Guests</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">
                            $room_data[adult] Adults
                        </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">
                            $room_data[children] Children
                        </span>
                    </div>

                    <!-- Rating section -->
                    <div class="rating mb-3">
                        $rating_data
                    </div>

                    <div class="d-flex justify-content-evenly mb-2 ">
                    $book_btn
                    <a href="room_details.php?id=$room_data[id]" class="btn btn-md btn-outline-dark shadow-none">More Details</a>
                </div>

                </div>

            </div>

        </div>

    data;



}
?>



            <div class="col-lg-12 text-center mt-5">
               <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms -></a>
            
            </div>
         </div>
      </div>
      <!-- room card end -->

      <!-- Facilities Section -->
      <h2 class="mt-5 pt-4 mb-4 text-center fw-bold ">OUR FACILITIES</h2>

   <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
    
        <?php 
            $res = mysqli_query($con,"SELECT * FROM `facilities` ORDER BY `id` LIMIT 5 ");
            $path = FACILITIES_IMG_PATH;

            while($row = mysqli_fetch_assoc($res))
            {
                echo<<<data

                    <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                        <img src="$path$row[icon]" alt="img" width="80px">
                        <h5 class="mt-3">$row[name]</h5>                            
                    </div>

                    
    

                data;

            }
        ?>

            <div class="col-lg-12 text-center mt-5">
               <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities -></a>
            </div>

         </div>
      </div>

      <!-- Testimonial Section-->
      
<!-- Swiper for Testimonials-->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>

  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <?php

          $review_q = "SELECT rr.*,uc.name AS uname, uc.profile, r.name AS rname FROM `rating_review` rr
            INNER JOIN `user_cred` uc ON rr.user_id = uc.id
            INNER JOIN `rooms` r ON rr.room_id = r.id
            ORDER BY `sr_no` DESC LIMIT 6";

          $review_res = mysqli_query($con,$review_q);
          $img_path = USERS_IMG_PATH;

          if(mysqli_num_rows($review_res)==0){
            echo 'No reviews yet!';
          }
          else
          {
            while($row = mysqli_fetch_assoc($review_res))
            {
              $stars = "<i class='bi bi-star-fill text-warning'></i> ";
              for($i=1; $i<$row['rating']; $i++){
                $stars .= " <i class='bi bi-star-fill text-warning'></i>";
              }

              echo<<<slides
                <div class="swiper-slide bg-white p-4">
                  <div class="profile d-flex align-items-center mb-3">
                    <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="30px">
                    <h6 class="m-0 ms-2">$row[uname]</h6>
                  </div>
                  <p>
                    $row[review]
                  </p>
                  <div class="rating">
                    $stars
                  </div>
                </div>
              slides;
            }
          }
        
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
      <a href="aboutus.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More >>></a>
    </div>
  </div>

          
            <!-- <div class="swiper-slide bg-white p-4">
                <div class="profile d-flex align-items-center mb-3">
                    <img src="images/testimonials/1.JPG" alt="img" width="60px">
                    <h6 class="m-0 ms-2">Smurti</h6>
                </div>
                <p>This place is perfect and matches all my expectations for what I paid. Friendly staff, nice cozy room.
                    Breakfast is awesome. I will definitely come back here when I will be in Kathmandu. Recommended to everyone.
                </p>
                <div class="rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-half text-warning"></i>
                </div>
            </div>

            
            <div class="swiper-slide bg-white p-4">
                <div class="profile d-flex align-items-center mb-3">
                    <img src="images/testimonials/1.JPG" alt="img" width="70px" height="50px">
                    <h6 class="m-0 ms-2">Rohan</h6>
                </div>
                <p>
                    Rooms were clean, well equipped. Family friendly place. Location was great. Staffs were friendly.
                    <br>
                    <br>

                    The bad side i faced was that there was not enough space for Parking. we were ensured we would get a proper parking during
                    the night, but our car was left outside on the roadside for parking.
                </p>
                <div class="rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-half text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                </div>
            </div>

        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
    <a href="aboutus.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More >>></a>
    </div> -->


</div>




      <!-- Reach Us -->

     
      <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">REACH US</h2>
      <div class="container">
         <div class="row">
            <div class="col-lg-8 col-md-8 p-3 mb-lg-0 mb-1 bg-white rounded " >
               <!-- update the map location with the orignal location ++++++++++ desktop googlemaps me jaa kr sharpe pr click krna wha dikh jayega -->
               <iframe class="w-100 rounded"src="<?php echo $contact_r['iframe']?>"  height="320px"  loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            
            <div class="col-lg-4 col-md-4">


               <div class="bg-white p-4 rounded mb-4 ">
                  <h5>Call Us</h5>
                  <a href="tel: +918433920217" class="d-inline-block mb-2 text-decoration-none text-dark">
                     <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1']?></a>
                  <br>
                  <!-- agar user 2nd no. na de uske liye  -->
                  <?php

                     if($contact_r['pn2']!='')
                     {
                        // hero doc method
                        echo<<<data
                           <a href="tel: +$contact_r[pn2]" class="d-inline-block  text-decoration-none text-dark">
                           <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]</a>
                        data;
                     }

                  ?>
              </div>
 <!--_______________________________________________________________________________________________________________________________________ -->

               <div class="bg-white p-4 rounded mb-4 ">
                  <h5>Follow Us</h5>


                   <!-- agar user twitter na de uske liye  -->
                  <?php

                     if($contact_r['tw']!='')
                     {
                        // hero doc method
                     echo<<<data
                        <a href="$contact_r[tw]" class="d-inline-block mb-3 ">
                           <span class="badge bg-white text-dark fs-6">
                              <i class="bi bi-twitter-x me-1" ></i> Twitter
                           </span>
                        </a>
                        <br>
                     data;
                     }

                  ?>


                      <!-- agar user FaceBook na de uske liye  -->
                  <?php

                     if($contact_r['fb']!='')
                     {
                        // hero doc method
                        echo<<<data
                           <a href="$contact_r[fb]" class="d-inline-block mb-3 ">
                              <span class="badge bg-white text-dark fs-6">
                                 <i class="bi bi-facebook me-1" ></i> Facebook
                              </span>
                           </a>
                           <br>
                        data;
                     }

                  ?>

                   <!-- agar user Insta na de uske liye  -->
                  <?php

                     if($contact_r['insta']!='')
                     {
                        // hero doc method
                        echo<<<data
                           <a href="$contact_r[insta]" class="d-inline-block mb-3 ">
                              <span class="badge bg-white text-dark fs-6">
                                 <i class="bi bi-instagram me-1" ></i> Instagram
                              </span>
                           </a>
                           <br>
                        data;
                     }

                  ?>


                  <!-- <a href="#" class="d-inline-block mb-3 ">
                     <span class="badge bg-white text-dark fs-6">
                        <i class="bi bi-twitter-x me-1" ></i> Twitter
                     </span>
                  </a>
                  <br> -->


                  <!-- <a href="#" class="d-inline-block mb-3 ">
                     <span class="badge bg-white text-dark fs-6">
                        <i class="bi bi-facebook me-1" ></i> Facebook
                     </span>
                  </a>
                  <br>
                  <a href="#" class="d-inline-block mb-3 ">
                     <span class="badge bg-white text-dark fs-6">
                        <i class="bi bi-instagram me-1" ></i> Instagram
                     </span>
                  </a> -->

               </div>
            </div>
         </div>
      </div>

      <!-- Password reset modal  -->
      <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="recovery-form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-flex align-items-center">
                                <i class="bi bi-shield-lock-fill fs-3 me-2"></i>Setup new Password
                            </h5>
                        </div>

                        <div class="modal-body">

                            <div class="mb-4">
                                <label class="form-label">New Password</label>
                                <input type="password" name="pass" required class="form-control shadow-none">
                                <input type="hidden" name="email">
                                <input type="hidden" name="token">
                            </div>

                            <!-- Login button and forgot password link here  -->
                            <div class="mb-3 text-end">
                                <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">
                                    CANCEL
                                </button>
                                <button type="submit" class="btn btn-primary shadow-none">Reset</button>
                            </div>
                        </div>                        
                    </div>
                </form>
            </div>
        </div>

      <?php require('inc/footer.php');?>

      <?php 
            if(isset($_GET['account_recovery']))
            {
                $data = filteration($_GET);

                $t_date = date("Y-m-d");

                $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
                [$data['email'],$data['token'],$t_date],'sss');

                if(mysqli_num_rows($query)==1)
                {
                    echo<<<showModal
                        <script>
                            var myModal = document.getElementById('recoveryModal');

                            myModal.querySelector("input[name='email']").value = '$data[email]';
                            myModal.querySelector("input[name='token']").value = '$data[token]';

                            var modal = bootstrap.Modal.getOrCreateInstance(myModal);
                            modal.show();
                        </script>

                    showModal;
                }
                else
                {
                    alert("error", "The link has Expired !!");
                }

            }
        ?>
      <!-- Initialize Swiper images -->
      <script>
                  /* Swiper for top display with effect fade */
                  var swiper = new Swiper(".swiper-container", {
                     spaceBetween: 30,
                     effect: "fade",
                     loop: true,
                     autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                     }
                  });

         // /* Swiper for Testimonials with Effect Coverflow  */
         // var swiper = new Swiper(".swiper-testimonials", {
         //       effect: "coverflow",
         //       grabCursor: true,
         //       loop: true,
         //       centeredSlides: true,
         //       slidesPerView: "auto",
         //       slidesPerView: "3",
         //       coverflowEffect: {
         //          rotate: 50,
         //          stretch: 0,
         //          depth: 100,
         //          modifier: 1,
         //          slideShadows: true,
         //       },
         //       pagination: {
         //          el: ".swiper-pagination",
         //       },
         //       breakpoints: {
         //          320: {
         //             slidesPerView: 1,
         //          },
         //          640: {
         //             slidesPerView: 1,
         //          },
         //          768: {
         //             slidesPerView: 2,
         //          },
         //          1024: {
         //             slidesPerView: 3,
         //          },
         //       }
         // });           

         let recovery_form = document.getElementById('recovery-form');

            recovery_form.addEventListener('submit', function(e) 
            {
                e.preventDefault();

                let data = new FormData();

                data.append('email', recovery_form.elements['email'].value);
                data.append('token', recovery_form.elements['token'].value);
                data.append('pass', recovery_form.elements['pass'].value);
                data.append('recover_user', '');


                var myModal = document.getElementById('recoveryModal');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();


                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/login_register.php", true);


                xhr.onload = function() {
                  //   if (this.responseText == 'failed') {
                  //       alert('error', "Reset Failed!!");
                  //   } 
                  //   else {
                  //       alert('success', "Reset Success!!");
                  //       recovery_form.reset();
                  //   }

                  if (this.responseText == 'failed') {
                 alert('error', "Account Reset Failed!!");
             } 
            else {
                alert('success', "Reset Success!!");
                forgot_form.reset();
             }

                }

                xhr.send(data);
            });
         </script>

      <h6 class="text-center bg-dark text-white p-3 m-0">Designed and Developed by SinRem</h6>
      
   </body>
</html>
