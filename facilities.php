<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <?php require('inc/links.php');?>
      <title><?php echo $settings_r['site_title'] ?> - Facilities</title>
      <!-- SwiperJs -->
      <style>
         .pop:hover{
            border-top-color: var(--clr) !important;
            transform: scale(1.03);
            transition: all 0.3s;
         }
      </style>


      
   </head>
   <body class="bg-light">

      <?php require('inc/header.php');?>
     
      <div class="my-5 px-4">
         <h2 class="fw-bold text-center">OUR FACILITIES</h2>

         <!-- line under our faciliteis -->
         <div class="h-line bg-dark"></div>

         <p class="text-center mt-3">Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
               Accusamus molestias eaque natus sint blanditiis iste corrupti 
               adipisci labore ea soluta.</p>
      </div>

      <div class="container">
         <div class="row">

         <?php
            $res = selectAll('facilities');
            $path = FACILITIES_IMG_PATH;

            while ($row = mysqli_fetch_assoc($res)) {
               echo <<<data
                     <div class="col-lg-4 col-md-6 mb-5 px-4">
                        <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                           <div class="d-flex align-items-center mb-2">
                                 <img src="$path{$row['icon']}" width="40px">
                                 <h5 class="m-0 ms-3">{$row['name']}</h5>
                           </div>
                           
                           <p>{$row['description']}</p>
                        </div>
                     </div>
               data;
            }
         ?>

            

           

            
            
         </div>
      </div>
      <?php require('inc/footer.php');?>

      <h6 class="text-center bg-dark text-white p-3 m-0">Designed and Developed by SinRem</h6>
      
   </body>
</html>
