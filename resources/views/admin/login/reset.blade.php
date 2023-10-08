<?php 
  $settings = DB::table('settings')->where('id',1)->first();
  $title = $settings->app_name ?? 'JUSTDIAL';

  $logo = config('custom.NO_IMG');

  $storage = Storage::disk('public');
  $path = 'settings/';

  $image_name = $settings->logo ?? '';
  if(!empty($image_name)){
      if($storage->exists($path.$image_name)){
    $logo =  url('public/storage/'.$path.'/'.$image_name);
}
  }

?>
<!doctype html>
    <html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>{{$title ?? ''}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{$logo}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js"> </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js">
        </script>
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
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Reset Password</h5>
                                    <p class="text-muted">Reset Password with {{$title ?? ''}}.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <div class="alert alert-success text-center small mb-4" role="alert">
                                        Enter your Email and instructions will be sent to you!
                                    </div>
                                    @include('snippets.errors')
                                    @include('snippets.flash')

                                    <form class="row g-3" action="" method="post">
                                        {{ csrf_field() }}
                                        
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                             <input type="text" class="form-control" name="email" id="email" placeholder="Email Address" value="{{old('email')}}">
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password</label>
                                             <input type="text" class="form-control" name="password" id="password" placeholder="Password" value="{{old('password')}}">
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label" for="confirm_password">Confirm Password</label>
                                             <input type="text" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password " value="{{old('confirm_password')}}">
                                        </div>
                                        
                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Submit</button>
                                        </div>
                                        
                                        <div class="mt-4 text-center">
                                            <p class="mb-0">Remember It ? <a href="{{url('admin/login')}}" class="fw-medium text-primary"> Sign in </a></p>
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

</body>

<!-- Mirrored from themesbrand.com/symox/layouts/auth-recoverpw.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 11 Jan 2022 12:15:12 GMT -->
</html>






