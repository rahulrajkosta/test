<?php 
$settings = DB::table('settings')->where('id',1)->first();
$title = $settings->app_name ?? 'AntiTheft';

$logo = config('custom.NO_IMG');

$storage = Storage::disk('public');
$path = 'settings/';

$image_name = $settings->logo ?? '';
if(!empty($image_name)){
  if($storage->exists($path.$image_name)){
    $logo =  url('public/storage/'.$path.'/'.$image_name);
  }
}

$route = \Request::route()->getName();
$role_id = Auth::guard('admin')->user()->role_id??'';
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="">
  <meta name="keywords" content="a">
  <meta name="author" content="ThemeSelect">
  <meta name="csrf-token" content="{!! csrf_token() !!}" />

  <title>Dashboard - {{$title}}</title>

  <link rel="shortcut icon" type="image/x-icon" href="{{$logo}}">
  <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700"
    rel="stylesheet">

  <!-- BEGIN: Vendor CSS-->
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/vendors.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/forms/toggle/switchery.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/plugins/forms/switch.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/core/colors/palette-switch.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/charts/chartist.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/charts/chartist-plugin-tooltip.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/file-uploaders/dropzone.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/forms/icheck/icheck.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/forms/icheck/custom.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/vendors/css/timeline/vertical-timeline.css">
  <!-- END: Vendor CSS-->

  <!-- BEGIN: Theme CSS-->
  <link rel="stylesheet" type="text/css" href="{{url('/assets/antitheft/')}}/app-assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/bootstrap-extended.min.css">
  <link rel="stylesheet" type="text/css" href="{{url('/assets/antitheft/')}}/app-assets/css/colors.min.css">
  <link rel="stylesheet" type="text/css" href="{{url('/assets/antitheft/')}}/app-assets/css/components.min.css">
  <!-- END: Theme CSS-->
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/pages/chat-application.css">

  <!-- BEGIN: Page CSS-->
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/core/colors/palette-gradient.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/fonts/simple-line-icons/style.min.css">

  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/pages/timeline.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/plugins/file-uploaders/dropzone.min.css">
  <link rel="stylesheet" type="text/css"
    href="{{url('/assets/antitheft/')}}/app-assets/css/pages/dashboard-ecommerce.min.css">
  <!-- END: Page CSS-->

  <!-- BEGIN: Custom CSS-->
  <link rel="stylesheet" type="text/css" href="{{url('/assets/antitheft/')}}/assets/css/style.css">
  <!-- END: Custom CSS-->


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<!-- END: Head-->

<style type="text/css">
  .main-menu.menu-light .navigation>li.active>a {
    font-weight: 700;
    color: #fa626b;
    background: #f0f0f0;
  }

  #button {
    display: block;
    margin: 20px auto;
    padding: 10px 30px;
    background-color: #eee;
    border: solid #ccc 1px;
    cursor: pointer;
  }

  #loader {
    position: fixed;
    top: 0;
    z-index: 100;
    width: 100%;
    height: 100%;
    display: none;
    background: rgba(0, 0, 0, 0.6);
  }

  .cv-spinner {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .spinner {
    width: 40px;
    height: 40px;
    border: 4px #ddd solid;
    border-top: 4px #2e93e6 solid;
    border-radius: 50%;
    animation: sp-anime 0.8s infinite linear;
  }

  @keyframes sp-anime {
    100% {
      transform: rotate(360deg);
    }
  }

  .is-hide {
    display: none;
  }

  ul.nav li a.dropdown-user-link .avatar {
    width: 117px !important;
    margin-right: 0.5rem;
  }

  .header-navbar .navbar-container ul.nav li a.dropdown-user-link {
    line-height: 23px !important;
    padding: 0.2rem 1rem !important;
  }
  .avatar img { 
      border-radius: 0px!important;
}
.fa.fa-eye {
  position: absolute;
  top: 43px;
  right: 30px;
  cursor: pointer;
}
.ind{
  position: unset !important;
}
</style>

<script type="text/javascript">
  $(document).ready(function () {

    // $(window).load(function(){
    // $('#loader').show();
    // });
  });

  $(window).load(function () {
    $('#loader').show();

  });
</script>

<div id="loader">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>


<!-- BEGIN: Body-->
<?php if($route == 'admin.chats.index' || $route == 'admin.firebasechats.index'){?>

<body class="vertical-layout vertical-menu content-left-sidebar chat-application  fixed-navbar" data-open="click"
  data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="content-left-sidebar">
  <?php }else{?>

  <body class="vertical-layout vertical-menu 2-columns fixed-navbar" data-open="click" data-menu="vertical-menu"
    data-color="bg-chartbg" data-col="2-columns" onload="loadingAjax('myDiv');">
    <?php }?>
    <!--  -->


    <!-- BEGIN: Header-->
    <nav
      class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light">
      <div class="navbar-wrapper">
        <div class="navbar-container content bg-cus">
          <div class="collapse navbar-collapse show" id="navbar-mobile">
            <ul class="nav navbar-nav mr-auto float-left">
              <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                  href="#"><i class="fa fa-bars font-large-1"></i></a></li>
              <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                    class="fa fa-bars"></i></a></li>
            </ul>
            <ul class="nav navbar-nav float-right">
              <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
                  href="#" data-toggle="dropdown">
                  <span class="avatar avatar-online  ">
                    <?php if($role_id == 2){  
                          $authdata = Auth::guard('admin')->user(); 
                          if(!empty($authdata->image)){

                         $image = isset($authdata->image) ? $authdata->image : '';
                          $storage = Storage::disk('public');
                          $path = 'users';  ?>

                           <img src="{{ url('storage/app/public/user/'.$image) }}" alt="avatar" class="img-fluid">

                        <?php }else{ ?>
                           <img src="{{$logo??''}}" alt="avatar" class="img-fluid">
                            
                          <?php } ?>
                  <?php }else{?>
                     <img src="{{$logo??''}}" alt="avatar" class="img-fluid">
                  <?php } ?>
                  </span></a>
                <div class="dropdown-menu dropdown-menu-right">
                  <div class="arrow_box_right"><a class="dropdown-item" href="#"><span
                        class="avatar avatar-online"><span
                          class="user-name text-bold-700 ml-1">{{Auth::guard('admin')->user()->name??''}}</span></span></a>
                    <div class="dropdown-divider"></div>
                    @if(CustomHelper::isAllowedSection('edit_profile' , 'list'))

                    <a class="dropdown-item" href="{{route('admin.profile')}}"><i class="fa fa-user"></i> Edit
                      Profile</a>
                    @endif
                    <?php if($role_id == 0){?>

                    <a class="dropdown-item" href="{{route('admin.lock_phone')}}"><i class="fa fa-lock"></i>Lock Phone</a>

                    <?php }?>

                    <a class="dropdown-item" href="{{route('admin.change_password')}}"><i class="fa fa-lock"></i>Change
                      Password</a>

                    <!--<a class="dropdown-item" href="email-application.html"><i class="fa fa-mail"></i> My Inbox</a>
                    <a class="dropdown-item" href="project-summary.html"><i class="fa fa-check-square"></i> Task</a>
                    <a class="dropdown-item" href="chat-application.html"><i class="fa fa-message-square"></i> Chats</a> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('admin.logout')}}"><i class="fa fa-power"></i> Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <!-- END: Header-->

    @include('admin.common.sidebar')