<?php 
$title = 'DSL English';

// $name = isset($name) ? $name : old('name');
// $email = isset($email) ? $email : old('email');
// $phone = isset($phone) ? $phone : old('phone');
// $google_id = isset($google_id) ? $google_id : old('google_id');




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
                            <a href="{{url('/admin')}}">
                                <img src="{{asset('assets/images/logo.png')}}" alt="" height="150">
                            </a>
                       </div>

                        <div class="card">
                            <div class="card-body p-4"> 
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Register As A Faculty</h5>
                                    <p class="text-muted">{{$title??''}}</p>
                                </div>
                                <div class="p-2 mt-4">
                                     @include('snippets.errors')
                                 @include('snippets.flash')


                                    <form action="{{route('admin.register')}}" method="post">
                                   {{ csrf_field() }}
                                        <div class="mb-3">
                                            <label class="form-label" for="useremail">Name</label>
                                            <input type="text" value="{{$name ?? ''}}" class="form-control" placeholder="Enter name" name="name">        
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="useremail">Email</label>
                                            <input type="email" value="{{$email ?? ''}}" class="form-control" id="useremail" placeholder="Enter email" name="email" <?php if(!empty($google_id)){ echo "readonly";}?>>        
                                        </div>
                                        <input type="hidden" name="google_id" value="{{$google_id??''}}">

                                        <div class="mb-3">
                                            <label class="form-label" for="useremail">Phone</label>
                                            <input type="text" value="{{$phone ?? ''}}" class="form-control" id="useremail" placeholder="Enter phone" name="phone">        
                                        </div>
                
                                        <div class="mb-3">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password" name="password" class="form-control" id="userpassword" placeholder="Enter password">        
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="auth-terms-condition-check">
                                            <label class="form-check-label" for="auth-terms-condition-check">I accept <a href="javascript: void(0);" class="text-dark">Terms and Conditions</a></label>
                                        </div>
                                        
                                        <div class="mt-3 text-end">

                                            <?php if(empty($google_id)){?>
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Send OTP</button>
                                            <?php }else{?>
                                                <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Register</button>

                                            <?php }?>



                                        </div>

                                        <div class="mt-4 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="font-size-14 mb-3 title">Sign in with</h5>
                                            </div>
            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-primary text-white border-primary">
                                                        <i class="mdi mdi-facebook"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-info text-white border-info">
                                                        <i class="mdi mdi-twitter"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <p class="text-muted mb-0">Already have an account ? <a href="{{route('admin.login')}}" class="fw-medium text-primary"> Login</a></p>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenujs/metismenujs.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>

    </body>

</html>
