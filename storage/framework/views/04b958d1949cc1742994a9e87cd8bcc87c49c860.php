<?php 
$settings = DB::table('settings')->where('id',1)->first();
$title = $settings->app_name ?? 'JUSTDIAL';

$logo = config('custom.NO_IMG');

$storage = Storage::disk('public');
$path = 'settings/';

$image_name = $settings->logo ?? '';
if(!empty($image_name)){
  if($storage->exists($path.$image_name)){
    $logo =  url('storage/'.$path.'/'.$image_name);
}
}

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>Login - </title>
    <link rel="apple-touch-icon" href="<?php echo e(url('public/storage/settings/reptile-rem.png')); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(url('public/storage/settings/reptile-rem.png')); ?>">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/vendors/css/forms/toggle/switchery.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/plugins/forms/switch.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/core/colors/palette-switch.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/components.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/css/pages/login-register.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/antitheft/')); ?>//assets/css/style.css">
    <!-- END: Custom CSS-->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu 1-column  bg-full-screen-image blank-page blank-page" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="flexbox-container">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0">
                    <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                        <div class="card-header border-0">
                            <div class="text-center mb-1">
                                <img src="<?php echo e($logo); ?>" style="height: 100%; width: 100%;" alt="branding logo">
                            </div>
                            <div class="font-large-1  text-center">                       
                               Powered By <br>
                               <h2 style="color:green;">MATRIX MY EMI LOCKER</h2>
                           </div>
                           <div class="font-large-1  text-center">                       
                            MakeSecure Pro Admin Login
                        </div>
                    </div>
                    <div class="card-content">
                       <?php echo $__env->make('snippets.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                       <?php echo $__env->make('snippets.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                       <div class="card-body">
                        <form class="form-horizontal" action="" method="post">
                           <?php echo e(csrf_field()); ?>


                           <fieldset class="form-group position-relative has-icon-left">
                            <input type="text" name="email" class="form-control round" id="user-name" placeholder="Your Email Address" >
                            <div class="form-control-position">
                                <i class="fa fa-users"></i>
                            </div>
                        </fieldset>
                        <fieldset class="form-group position-relative has-icon-left">
                            <input type="password" name="password" class="form-control round" id="user-password" placeholder="Enter Password" >
                            <div class="form-control-position">
                                <i class="fa fa-lock"></i>
                            </div>
                        </fieldset>

                        <!-- <fieldset class="form-group position-relative has-icon-left">
                            <input type="text" name="otp" class="form-control round" id="user-password" placeholder="Enter OTP" >
                            <div class="form-control-position">
                                <i class="fa fa-lock"></i>
                            </div>
                        </fieldset> -->
                        <div class="form-group row">
                            <div class="col-md-6 col-12 text-center text-sm-left">
                             
                            </div>
                            <!-- <div class="col-md-6 col-12 float-sm-left text-center text-sm-right"><a href="<?php echo e(route('admin.forgot')); ?>" class="card-link">Forgot Password?</a></div> -->
                        </div>                           
                        <div class="form-group text-center">
                            <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Login</button>    
                        </div>
                        
                    </form>
                </div>                   
            </div>
        </div>
    </div>
</div>
</section>

</div>
</div>
</div>
<!-- END: Content-->


<!-- BEGIN: Vendor JS-->
<script src="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/vendors/js/forms/toggle/switchery.min.js" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/js/scripts/forms/switch.min.js" type="text/javascript"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/vendors/js/forms/validation/jqBootstrapValidation.js" type="text/javascript"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/js/core/app.min.js" type="text/javascript"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="<?php echo e(asset('assets/antitheft/')); ?>//app-assets/js/scripts/forms/form-login-register.min.js" type="text/javascript"></script>
<!-- END: Page JS-->

</body>
<!-- END: Body-->

<!-- Mirrored from demos.themeselection.com/chameleon-admin-template/html/ltr/vertical-menu-template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 16 Aug 2022 06:24:00 GMT -->
</html><?php /**PATH D:\after_emilocket_\makesecurepro\resources\views/admin/login/index.blade.php ENDPATH**/ ?>