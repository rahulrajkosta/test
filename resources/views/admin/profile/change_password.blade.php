@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'live_class/';
// $roleId = Auth::guard('admin')->user()->role_id; 
$name = isset($user->name) ? $user->name : '';
$email = isset($user->email) ? $user->email : '';
$image = isset($user->image) ? $user->image : '';
$username = isset($user->username) ? $user->username : '';
$phone = isset($user->phone) ? $user->phone : '';
$address = isset($user->address) ? $user->address : '';
$state_id = isset($user->state_id) ? $user->state_id : '';
$city_id = isset($user->city_id) ? $user->city_id : '';
$unique_id = isset($user->unique_id) ? $user->unique_id : '';
$business_name = isset($user->business_name) ? $user->business_name : '';
$adhar_no = isset($user->adhar_no) ? $user->adhar_no : '';
$pan_no = isset($user->pan_no) ? $user->pan_no : '';
$pincode = isset($user->pincode) ? $user->pincode : '';
$pan_image = isset($user->pan_image) ? $user->pan_image : '';
$adhar_image = isset($user->adhar_image) ? $user->adhar_image : '';
$gst_image = isset($user->gst_image) ? $user->gst_image : '';
$bussiness_name = isset($user->bussiness_name) ? $user->bussiness_name : '';
$alternate_phone = isset($user->alternate_phone) ? $user->alternate_phone : '';

$status = isset($user->status) ? $user->status : '1';
$is_approve = isset($user->is_approve) ? $user->is_approve : '0';

$username = isset($user->username) ? $user->username : '';


?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Edit Profile</h3>
    </div>
    <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Edit Profile
              </li>
          </ol>
      </div>
  </div>
</div>
</div>
<div class="content-body">
  <section class="input-validation">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Change Password</h4>
            <a class="heading-elements-toggle">
              <i class="la la-ellipsis-v font-medium-3"></i>
          </a>
          <div class="heading-elements">
              <ul class="list-inline mb-0">

              </ul>
          </div>
      </div>
       @include('snippets.errors')
 @include('snippets.flash')
      <div class="card-content collapse show">
          <div class="card-body">
            <form class="card-body" action="{{route('admin.change_password')}}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

              {{ csrf_field() }}

              <div class="col-md-12 ">
                  <label for="fullname" class="form-label">Name</label>
                  <input type="password" class="form-control" name="old_password" id="inputEmail3" placeholder="Old Password" aria-label="default input example">
              </div>

              <br>
              <div class="col-md-12 ">
                  <label for="fullname" class="form-label">New Password</label>
                  <input type="password" class="form-control" name="new_password" id="inputPassword3" placeholder="New Password" aria-label="default input example">
              </div>
              <br>

              <div class="col-md-12 ">
                  <label for="fullname" class="form-label">Confirm Password</label>
                 <input type="password" class="form-control" name="confirm_password" id="inputPassword5" placeholder="Confirm Password" aria-label="default input example">
              </div>

              <br>
              <br>
              <button class="btn btn-primary" type="submit">Change Password </button>
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



@include('admin.common.footer')
