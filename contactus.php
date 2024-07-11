<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
     
      <?php require('inc/links.php');
      ?>
    <title><?php echo $settings_r['site_title'] ?> - Contact</title>
    


      
   </head>
   <body class="bg-light">

      <?php require('inc/header.php');?>
     
      <div class="my-5 px-4">
         <h2 class="fw-bold text-center">CONTACT</h2>

         <!-- line under our faciliteis -->
         <div class="h-line bg-dark"></div>

         <p class="text-center mt-3">Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
               Accusamus molestias eaque natus sint blanditiis iste corrupti 
               adipisci labore ea soluta.</p>
      </div>



      <div class="container">
         <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
               <div class="bg-white rounded shadow p-4">
                <!-- update the map location with the orignal location ++++++++++ desktop googlemaps me jaa kr sharpe pr click krna wha dikh jayega -->                 
                  <iframe class="w-100 rounded mb-4"src="<?php echo $contact_r['iframe']?>"  height="320px"  loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                     <h5>Address</h5>
                     <!-- location update kar -->
                     <a href="<?php echo $contact_r['gmap']?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                     <i class="bi bi-geo-alt-fill"></i>
                     <?php echo $contact_r['address']?>
                     </a>

                     <!-- contact information -->
                     <h5 class="mt-4">Call Us</h5>
                  <a href="tel: +<?php echo $contact_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
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

                  <!-- <a href="tel: +919433920217" class="d-inline-block  text-decoration-none text-dark">
                     <i class="bi bi-telephone-fill">
                  </i> +919433920217</a> -->
                     <h5 class="mt-4">Email</h5>
                     <a href="<?php echo $contact_r['email']?>" class="d-inline-block text-decoration-none text-dark mb-2">
                     <i class="bi bi-envelope-at-fill"></i>  <?php echo $contact_r['email']?></a>





                     
                     <!-- Links  -->
                     <h5 class="mt-4">Follow Us</h5>

                     <!-- Twitter -->
                  <?php

                     if($contact_r['tw']!='')
                     {
                        // hero doc method
                        echo<<<data
                           <a href="$contact_r[tw]" class="d-inline-block text-dark fs-5 me-2">
                              <i class="bi bi-twitter-x me-1" ></i>
                           </a>
                        data;
                     }

                  ?>

                  
                     <!-- fb -->
                  <?php

                     if($contact_r['fb']!='')
                     {
                        // hero doc method
                        echo<<<data
                           <a href="$contact_r[fb]" class="d-inline-block text-dark fs-5 me-2">
                              <i class="bi bi-facebook me-1" ></i>
                           </a>
                        data;
                     }

                  ?>

                  
                     <!-- insta -->
                  <?php

                     if($contact_r['insta']!='')
                     {
                        // hero doc method
                        echo<<<data
                           <a href="$contact_r[insta]" class="d-inline-block text-dark fs-5 me-2">
                              <i class="bi bi-instagram me-1" ></i>
                           </a>
                        data;
                     }

                  ?>
               
               </div>
            </div>

            <div class="col-lg-6 col-md-6  px-4">
               <div class="bg-white rounded shadow p-4">
                  <form method="POST">
                     <h5>Send a Message</h5>

                     <div class="mt-3">
                        <label  class="form-label" style="font-weight:500">Name</label>
                        <input name="name" required ="text" class="form-control shadow-none font" >
                     </div>

                     <div class="mt-3">
                        <label  class="form-label" style="font-weight:500">Email</label>
                        <input name="email" required  type="email" class="form-control shadow-none font" >
                     </div>


                     <div class="mt-3">
                        <label  class="form-label" style="font-weight:500">Subject</label>
                        <input name="subject" required type="text" class="form-control shadow-none font" >
                     </div>

                     <div class="mt-3">
                        <label  class="form-label" style="font-weight:500">Message</label>
                        <textarea name="message" required class="form-control shadow-none"  rows="5" style="resize: none;"></textarea>
                     </div>
                     <button name="send" required  type="submit" class="btn text-white custom-bg mt-3">Send</button>

                  </form>
               </div>
            </div>

      

            
            
         </div>
      </div>

      <?php 
         if(isset($_POST['send']))
         {
            $frm_data=filteration($_POST);
            $q="INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
            $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

            $res = insert($q,$values,'ssss');
            if($res==1)
            {
               alert('success','Mail Sent');
            }
            else
            {
               alert('error','Server Down Try Again Later!');
            }
         }
      ?>
      <?php require('inc/footer.php');?>

      <h6 class="text-center bg-dark text-white p-3 m-0">Designed and Developed by SinRem</h6>
      
   </body>
</html>
