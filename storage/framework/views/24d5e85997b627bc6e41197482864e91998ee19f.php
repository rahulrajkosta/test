<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'live_class/';
$user = Auth::guard('admin')->user(); 
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
$states = CustomHelper::GetStates();
$cities = [];
if(!empty($state_id)){
  $cities = CustomHelper::GetCity($state_id);
}

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
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
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
                <h4 class="card-title">Edit Profile</h4>
                <a class="heading-elements-toggle">
                  <i class="la la-ellipsis-v font-medium-3"></i>
                </a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li>
                     <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                     <a href="<?php echo e(url($back_url)); ?>" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
                   </li>
                 </ul>
               </div>
             </div>
             <?php echo $__env->make('snippets.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
             <?php echo $__env->make('snippets.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

             <div class="card-content collapse show">
              <div class="card-body">
                <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

                  <?php echo e(csrf_field()); ?>

                  <div class="col-md-12 ">
                    <label for="fullname" class="form-label">Business Name</label>
                    <input type="text" class="form-control mb-3" name="business_name" id="exampleInputName"  value="<?php echo e(Auth::guard('admin')->user()->business_name ?? ''); ?>" placeholder="Enter Business Name" aria-label="default input example">
                  </div>




                  <div class="col-md-12 ">
                    <label for="fullname" class="form-label">Name</label>
                    <input type="text" class="form-control mb-3" name="name" id="exampleInputName"  value="<?php echo e(Auth::guard('admin')->user()->name ?? ''); ?>" placeholder="Enter Name" aria-label="default input example">
                  </div>


                  <div class="col-md-12 ">
                    <label for="fullname" class="form-label">UserName</label>
                    <input type="text" class="form-control mb-3" name="username" id="exampleInputName"  value="<?php echo e(old('username',$username)); ?>" placeholder="Enter UserName For Login" aria-label="default input example">
                  </div>


                  <div class="col-md-12 ">
                    <label for="fullname" class="form-label">Contact No.</label>
                    <input type="number" class="form-control mb-3" name="phone" id="exampleInputNumber"  value="<?php echo e(Auth::guard('admin')->user()->phone ?? ''); ?>" placeholder="Enter Name" aria-label="default input example">
                  </div>
                  <div class="col-md-12 ">
                    <label for="fullname" class="form-label">Alt Contact No.</label>
                    <input type="number" class="form-control mb-3" name="alternate_phone" id="exampleInputNumber"  value="<?php echo e(Auth::guard('admin')->user()->alternate_phone ?? ''); ?>" placeholder="Enter Name" aria-label="default input example">
                  </div>


                  <div class="col-md-12 ">
                    <label for="fullname" class="form-label">Email address</label>
                    <input type="email" class="form-control mb-3" name="email" id="email"  value="<?php echo e(old('email',$email)); ?>" placeholder="Enter Email" aria-label="default input example">
                  </div>



                  <div class="col-md-12 ">
                    <label for="fullname" class="form-label"> Address</label>
                    <input type="text" class="form-control mb-3" name="address" id="address"  value="<?php echo e(old('address',$address)); ?>" placeholder="Enter address" aria-label="default input example">
                  </div>

                  <div class="col-md-12 ">
                   <label for="email" class="form-label">State</label>
                   <select class="form-control mb-3" name="state_id" id="state_id">


                    <?php if(!empty($states)){
                      foreach($states as $state){
                        ?>
                        <option value="<?php echo e($state->id); ?>" <?php if($state->id == $state_id) echo "selected"?>><?php echo e($state->name??''); ?></option>
                      <?php }}?>
                    </select>
                  </div>
                  <div class="col-md-12 ">
                   <label for="email" class="form-label">City</label>
                   <select class="form-control mb-3" name="city_id" id="city_id">

                    <?php if(!empty($cities)){
                      foreach($cities as $city){
                        ?>
                        <option value="<?php echo e($city->id); ?>" <?php if($city->id == $city_id) echo "selected"?>><?php echo e($city->name??''); ?></option>
                      <?php }}?>
                    </select>
                  </div>








                  <div class="col-md-12">
                    <label for="fullname" class="form-label">Upload Image</label>
                    <input class="form-control mb-3" type="file" name="image" id="image" aria-label="default input example">

                    <?php
                    if(!empty($image)){
                      if($storage->exists($path.$image)){ ?>
                        <div class=" image_box" style="display: inline-block">
                          <a href="<?php echo e(url('storage/app/public/'.$path.$image)); ?>" target="_blank">
                            <img src="<?php echo e(url('storage/app/public/'.$path.$image)); ?>" style="width:70px;">
                          </a>


                        </div>
                      <?php  }
                    }
                    ?>

                  </div>

                  <button class="btn btn-primary" type="submit">Update Profile </button>
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



<?php echo $__env->make('admin.common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /var/www/html/example-app/resources/views/admin/profile/index.blade.php ENDPATH**/ ?>