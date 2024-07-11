<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
         <div class="container-fluid">
            <a class="navbar-brand me-5 fs-3" href="index.php"><?php echo $settings_r['site_title'] ?></a>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                     <a class="nav-link  me-2"  href="index.php">Home</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link me-2" href="rooms.php">Rooms</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link me-2" href="facilities.php">Facilities</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link me-2" href="contactus.php">Contact Us</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="aboutus.php">About Us</a>
                  </li>
               </ul>
               <div class="d-flex">

               <?php  
                    if(isset($_SESSION['login']) && $_SESSION['login']==true)
                    {
                        $path = USERS_IMG_PATH;
                        echo<<<data
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-none shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                    <img src="$path$_SESSION[uPic]" style="width: 35px; height: 35px; border-radius: 50%;" class="me-1">
                                    $_SESSION[uName]
                                </button>
                                <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="bookings.php">Bookings</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        data;
                    }

                    else
                    {
                        echo<<<data

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                            <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>

                        data;

                    }
                    ?>

                  <!-- <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                  <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button> -->
               </div>
            </div>
         </div>
      </nav>

      <!--Login Modal-->
      <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
         <div class="modal-dialog ">
            <div class="modal-content">
            <form id="login-form">
                  <div class="modal-header ">
                     <h5 class="modal-title d-flex align-items-center"><i class="bi bi-person-circle fs-3 me-2"></i>User Login</h5>
                     <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>        
                  </div>
                  <div class="modal-body">
                     <div class="mb-3">
                     <label class="form-label">Email / Mobile</label>
                            <input type="text" name="email_mob" required class="form-control shadow-none">
                     </div>
                     <div class="mb-4">
                     <label class="form-label">Password</label>
                            <input type="password" name="pass" required class="form-control shadow-none">
                     </div>
                     <div class="d-flex aling-items-center justify-content-between mb-3">
                     <button type="submit" class="btn btn-dark shadow-none">LOGIN</button>
                            <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
                                Forgot password ?
                            </button>
                        </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      
      <!--Register Modal-->
      <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <form id="register-form">
                  <div class="modal-header ">
                     <h5 class="modal-title d-flex align-items-center"><i class="bi bi-person-lines-fill fs-3 me-2"></i>User Registration</h5>
                     <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>        
                  </div>
                  <div class="modal-body">
                     <span class="badge bg-light text-dark mb-3 text-wrap lh-base">Note: Your Details must match with your ID (Aadhar,Passport,Driving Licence, etc..) 
                     that'll be required during Check-In</span>
                     <div class="container-fluid">
                        <div class="row">

                                <!-- Name -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" placeholder="Full Name" class="form-control shadow-none" required>
                                </div>

                                <!-- Email  -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label ">Email</label>
                                    <input name="email" type="email" placeholder="E-mail" class="form-control shadow-none" required>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label ">Phone Number</label>
                                    <input name="phonenum" type="number" placeholder="Phone number" class="form-control shadow-none" required>
                                </div>

                                <!-- Picture -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label ">Picture</label>
                                    <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none" required>
                                </div>

                                <!-- Address -->
                                <div class="col-md-12 ps-0 mb-3">
                                    <label class="form-label ">Address</label>
                                    <textarea name="address" class="form-control shadow-none " rows="5" required></textarea>
                                </div>

                                <!-- Pin Code  -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label ">Pin Code</label>
                                    <input name="pincode" type="number" placeholder="Pin Code" class="form-control shadow-none" required>
                                </div>

                                <!-- D.O.B  -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label ">Date of Birth</label>
                                    <input name="dob" type="date" class="form-control shadow-none" required>
                                </div>

                                <!-- Password  -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label ">Password</label>
                                    <input name="pass" type="password" placeholder="Password" class="form-control shadow-none" required>
                                </div>

                                <!-- Confirm Password  -->
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label ">Confirm Password</label>
                                    <input name="cpass" type="password" placeholder="Confirm password" class="form-control shadow-none" required>
                                </div>
                            </div>
                        <!-- <div class="row">
                           <div class="col-md-6 ps-0 mb-3">
                              <label  class="form-label">Name</label>
                              <input type="text" class="form-control shadow-none " >
                           </div>
                           <div class="col-md-6 p-0 mb-3">
                              <label  class="form-label">Email</label>
                              <input type="email" class="form-control shadow-none " >
                           </div>
                           <div class="col-md-6 ps-0 mb-3">
                              <label  class="form-label">Phone</label>
                              <input type="number" class="form-control shadow-none " >
                           </div>
                           <div class="col-md-6 p-0 mb-3">
                              <label  class="form-label">Picture</label>
                              <input type="file" class="form-control shadow-none " >
                           </div>
                           <div class="col-md-12 p-0 mb-3">
                              <label  class="form-label">Address</label>
                              <textarea class="form-control shadow-none"  rows="2"></textarea>
                           </div>
                           <div class="col-md-6 ps-0 mb-3">
                              <label  class="form-label">PinCode</label>
                              <input type="number" class="form-control shadow-none " >
                           </div>
                           <div class="col-md-6 p-0 mb-3">
                              <label  class="form-label">Date Of Birth</label>
                              <input type="date" class="form-control shadow-none " >
                           </div>
                           <div class="col-md-6 ps-0 mb-3">
                              <label  class="form-label">Password</label>
                              <input type="password" class="form-control shadow-none " >
                           </div>
                           <div class="col-md-6 p-0 mb-3">
                              <label  class="form-label">Confirm Password</label>
                              <input type="password" class="form-control shadow-none " >
                           </div>
                        </div> -->
                     </div>
                     <div class="text-center my-1">
                        <button type="submit" class="btn btn-dark shadow-none">Register</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <!-- forgot password modal  -->
    <div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="forgot-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-person-circle fs-3 me-2"></i>Forgot Password
                        </h5>
                    </div>

                    <div class="modal-body">

                        <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
                            Note: A link will be sent to youe Email to reset your password !
                        </span>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" required class="form-control shadow-none">
                        </div>

                        <!-- Login button and forgot password link here  -->
                        <div class="mb-3 text-end">
                                <button type="button" class="btn shadow-none p-0 me-2" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
                                    CANCEL
                                </button>
                                <button type="submit" class="btn btn-primary shadow-none">SEND LINK</button>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Understood</button>
                            </div> -->
                </div>
            </form>
        </div>
    </div>