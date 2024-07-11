<?php 
   require('inc/essentials.php');
   require('inc/db_config.php');
   adminLogin();
   
   //    mark read
   if (isset($_GET['seen'])) 
   {
    $frm_data = filteration($_GET);
    if ($frm_data['seen'] == 'all') {
        $q = "UPDATE `user_queries` SET `seen`=?";
        $values = [1];
        if (update($q, $values, 'i')) {
            alert('success', 'Marked all as Read!');
        } else {
            alert('error', 'Operation Failed!');
        }
    } else {
        $q = "UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?";
        $values = [1, $frm_data['seen']];
        if (update($q, $values, 'ii')) {
            alert('success', 'Marked as Read');
        } else {
            alert('error', 'Operation Failed!');
        }
    }
   }
   
   // delete all read/ or all 
   if (isset($_GET['del'])) 
   {
    $frm_data = filteration($_GET);
    if ($frm_data['del'] == 'all') 
    {
        $q = "DELETE FROM `user_queries`";
        if (mysqli_query($con,$q)) 
        {
            alert('success', 'All Data Deleted !');
        }
            else 
            {
            alert('error', 'Operation Failed!');
        }
    } 
    else {
        $q = "DELETE FROM `user_queries` WHERE `sr_no`=?";
        $values = [$frm_data['del']];
        if (update($q, $values, 'i')) {
            alert('success', 'Data Deleted');
        } else {
            alert('error', 'Operation Failed!');
        }
    }
   }
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin Panel-Rooms</title>
      <?php require('inc/links.php'); ?>
   </head>
   <body class="bg-light ">
      <?php require('inc/header.php'); ?>
      <div class="container-fluid" id="main-content">
         <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
               <h3 class="mb-4">Rooms</h3>
               <!-- Rooms -->
                <div class="text-end mb-4">
                  <div class="card-body">

                     <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0"></h5>
                        <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>

                     <div class="table-responsive-lg" style="height: 450px; overflow-y:scroll">
                        <table class="table table-hover border text-center">
                           <thead >
                              <tr class="bg-dark text-light">
                                 <th scope="col">#</th>
                                 <th scope="col">Name</th>
                                 <th scope="col">Area</th>
                                 <th scope="col">Guest</th>
                                 <th scope="col">Price</th>
                                 <th scope="col">Quantity</th>
                                 <th scope="col">Status</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody id ="room-data">
                             
                           </tbody>
                        </table>
                     </div>

                  </div>
               
                </div>

                
            </div>
         </div>
      </div>

       <!-- Add Room Modal -->
<div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="add_room_form" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Rooms</h5>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Area</label>
                            <input type="number" min="1" name="area" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <input type="number" min="1" name="price" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantity</label>
                            <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Adult (Max.)</label>
                            <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Children (Max.)</label>
                            <input type="number" min="0" name="children" class="form-control shadow-none" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Features</label>
                            <div class="row">
                                <?php
                                $res=selectAll('features');
                                while($opt=mysqli_fetch_assoc($res))
                                {
                                    echo "
                                        <div class='col-md-3'>
                                            <label>
                                                <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                     ";
                                }
                                ?>
                            </div>
                        </div>


                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Facilities</label>
                            <div class="row">
                                <?php
                                $res = selectAll('facilities');
                                while ($opt = mysqli_fetch_assoc($res)) {
                                    echo "
                                        <div class='col-md-3'>
                                            <label>
                                                <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                    ";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description"  rows="4" class="form-control"></textarea>
                        </div>

                    </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="reset"
                            class="btn text-secondary shadow-none" data-bs-dismiss="modal">Reset</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

       <!-- Edit Room Modal -->
       <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="edit_room_form" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Room</h5>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Area</label>
                            <input type="number" min="1" name="area" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <input type="number" min="1" name="price" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantity</label>
                            <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Adult (Max.)</label>
                            <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Children (Max.)</label>
                            <input type="number" min="0" name="children" class="form-control shadow-none" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Features</label>
                            <div class="row">
                                <?php
                                $res=selectAll('features');
                                while($opt=mysqli_fetch_assoc($res))
                                {
                                    echo "
                                        <div class='col-md-3'>
                                            <label>
                                                <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                     ";
                                }
                                ?>
                            </div>
                        </div>


                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Facilities</label>
                            <div class="row">
                                <?php
                                $res = selectAll('facilities');
                                while ($opt = mysqli_fetch_assoc($res)) {
                                    echo "
                                        <div class='col-md-3'>
                                            <label>
                                                <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                    ";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description"  rows="4" class="form-control"></textarea>
                        </div>

                        <input type="hidden" name="room_id">

                    </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="reset"
                            class="btn text-secondary shadow-none" data-bs-dismiss="modal">Reset</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
   
    
    <!-- room images -->
    <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Room Name</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="image-alert">
            
        </div>
        <div class="border-bottm border-3 pb-3 mb">
            <form id="add_images_form">
            <label class="form-label fw-bold">Add Image</label>
                            <input type="file" name="image"
                                accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none mb-3" required>

            <button type="submit" class="btn custom-bg text-white shadow-none">Add</button>         
            <input type="hidden" name="room_id">          
            </form>
        </div>

        <div class="table-responsive-lg" style="height: 350px; overflow-y:scroll">
                        <table class="table table-hover border text-center">
                           <thead>
                              <tr class="bg-dark text-light sticky-top">
                                 <th scope="col" width="40%">Image</th>
                                 <th scope="col">Thumb</th>
                                 <th scope="col">Delete</th>
                              </tr>
                           </thead>
                           <tbody id ="room-image-data">
                           </tbody>
                        </table>
                     </div>
      </div>
      
    </div>
  </div>
</div>

<script src="scripts/rooms.js"></script>
    <?php require('inc/scripts.php') ?>
   
   </body>
</html>