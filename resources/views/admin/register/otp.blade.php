<?php 
$title = 'DSL English';

$name = isset($name) ? $name : old('name');
$email = isset($email) ? $email : old('email');
$phone = isset($phone) ? $phone : old('phone');
$google_id = isset($google_id) ? $google_id : old('google_id');




?>

<!doctype html>
<html lang="en">

    
<!-- Mirrored from themesbrand.com/symox/layouts/auth-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 11 Jan 2022 12:15:12 GMT -->
<head>
        
        <meta charset="utf-8" />
        <title>{{$title ?? ''}} Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
          <script src="https://code.jquery.com/jquery-3.6.0.slim.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    </head>
<body data-layout="horizontal" data-topbar="dark">

    <div class="authentication-bg min-vh-100">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-5">

                        <div class="text-center mb-4">
                            <a href="index.html">
                        <img src="{{asset('assets/images/logo.png')}}" alt="" height="150">

                            </a>
                       </div>

                        <div class="card">
                            <div class="card-body p-4"> 
                                <div class="avatar-lg mx-auto">
                                    <div class="avatar-title rounded-circle bg-light">
                                        <i class="bx bxs-envelope h2 mb-0 text-primary"></i>
                                    </div>
                                </div>
                                <div class="p-2 mt-4">
                                    <div class="text-center">
                                        <h4>Verify your email</h4>
                                        <p class="mb-5">Please enter the 4 digit code sent to <span class="fw-bold">{{$email ?? ''}}</span></p>
                                    </div>
                                    
                                    <form action="{{route('admin.verify_otp')}}" method="post">
                                        @csrf

                                        <input type="hidden" name="name" value="{{$name}}">
                                        <input type="hidden" name="phone" value="{{$phone}}">
                                        <input type="hidden" name="email" value="{{$email}}">
                                        <input type="hidden" name="password" value="{{$password}}">

                                        <div class="mb-3">
                                            <label class="form-label" for="useremail">Enter OTP</label>
                                            <input type="text" value="" class="form-control" id="useremail" placeholder="Enter otp" name="otp">        
                                        </div>



                                        <div class="row d-none">
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit1-input" class="visually-hidden">Dight 1</label>
                                                    <input type="text" name="" 
                                                        class="form-control form-control-lg text-center two-step"
                                                        maxLength="1"
                                                        data-value="1"
                                                        id="digit1-input">
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit2-input" class="visually-hidden">Dight 2</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg text-center two-step"
                                                        maxLength="1"
                                                        data-value="2"
                                                        id="digit2-input">
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit3-input" class="visually-hidden">Dight 3</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg text-center two-step"
                                                        maxLength="1"
                                                        data-value="3"
                                                        id="digit3-input">
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit4-input" class="visually-hidden">Dight 4</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg text-center two-step"
                                                        maxLength="1"
                                                        data-value="4"
                                                        id="digit4-input">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Confirm</button>
                                        </div>
                                    </form>

                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">Didn't receive an email ? <a href="#" 
                                        class="text-primary fw-semibold"> Resend </a> </p>
                                </div>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

            </div>
        </div><!-- end container -->
    </div>


      <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenujs/metismenujs.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>

        <!-- two-step-verification js -->
        <script src="{{asset('assets/js/pages/two-step-verification.init.js')}}"></script>

    </body>

</html>
