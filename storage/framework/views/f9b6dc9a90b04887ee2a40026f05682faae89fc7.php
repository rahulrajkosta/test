<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$session_parent_id = Auth::guard('admin')->user()->id??'';


$parent_id1 = !empty($_GET['parent_id']) ? $_GET['parent_id'] : $session_parent_id;

$user_id = isset($users->id) ? $users->id : '';
$name = isset($users->name) ? $users->name : '';
$email = isset($users->email) ? $users->email : '';
$username = isset($users->username) ? $users->username : '';
$parent_id = isset($users->parent_id) ? $users->parent_id : $parent_id1;
// $role_id = isset($users->role_id) ? $users->role_id : '';
$password = isset($users->password) ? $users->password : '';
$phone = isset($users->phone) ? $users->phone : '';
$address = isset($users->address) ? $users->address : '';
$status = isset($users->status) ? $users->status : '';
$image = isset($users->image) ? $users->image : '';
$state_id = isset($users->state_id) ? $users->state_id : '';
$country_id = isset($users->country_id) ? $users->country_id : '';
$city_id = isset($users->city_id) ? $users->city_id : '';
$is_approve = isset($users->is_approve) ? $users->is_approve : '';
$unique_id = isset($users->unique_id) ? $users->unique_id : '';
$business_name = isset($users->business_name) ? $users->business_name : '';
$adhar_no = isset($users->adhar_no) ? $users->adhar_no : '';
$pan_no = isset($users->pan_no) ? $users->pan_no : '';
$pincode = isset($users->pincode) ? $users->pincode : '';
$pan_image = isset($users->pan_image) ? $users->pan_image : '';
$adhar_image = isset($users->adhar_image) ? $users->adhar_image : '';
$gst_image = isset($users->gst_image) ? $users->gst_image : '';
$bussiness_name = isset($users->bussiness_name) ? $users->bussiness_name : '';
$alternate_phone = isset($users->alternate_phone) ? $users->alternate_phone : '';
$gst = isset($users->gst) ? $users->gst : '';
// $states = CustomHelper::GetStates();
$countries = DB::table('countries')->get();
$states = [];
$cities = [];
if(!empty($country_id)){
  $states = DB::table('states')->where('country_id',$country_id)->get();
}
if(!empty($state_id)){
  $cities = CustomHelper::GetCity($state_id);
}
$role_id = Auth::guard('admin')->user()->role_id??'';
// echo $parent_id = $parent_id??'';

// pr($parent_id);
?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title"><?php echo e($page_Heading); ?></h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
              </li>
              <li class="breadcrumb-item active"><?php echo e($page_Heading); ?>

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
                <h4 class="card-title"><?php echo e($page_Heading); ?></h4>
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


                  <input type="hidden" id="id" value="<?php echo e($user_id); ?>">
                  <input type="hidden" name="role_id" value="<?php echo e($roles->id); ?>">
                  <div class="form-row">

                    <?php
                     if(!empty($parents) && count($parents) > 0){
                      ?>
                      <div class="col-md-6">
                       <label for="fullname" class="form-label"><?php echo e($parent_role->name??''); ?></label>
                       <select class="form-control select2" name="parent_id">
                        <option value="" selected disabled>Select <?php echo e($parent_role->name??''); ?></option>
                        <?php if(!empty($parents)  && count($parents) > 0){
                          foreach($parents as $parent){
                            $business_name_new = $parent->business_name??'';
                            if(empty($business_name_new)){
                              $business_name_new = $parent->name??'';

                            }
                            if($role_id !=0){
                              if($parent_id== $parent->id){
                            ?>
                            <option value="<?php echo e($parent->id); ?>" <?php if($parent->id == $parent_id) echo "selected"?>><?php echo e($business_name_new??''); ?> -  <?php echo e($parent->unique_id); ?></option>
                          <?php }}else{?>

                            <option value="<?php echo e($parent->id); ?>" <?php if($parent->id == $parent_id) echo "selected"?>><?php echo e($business_name_new??''); ?> -  <?php echo e($parent->unique_id); ?></option>

                          <?php }   }}?>

                        </select>
                      </div>
                    <?php }?>
                      
                      

                    <div class="col-md-6">
                     <label for="fullname" class="form-label">Business Name</label>
                     <input class="form-control mb-3" type="text" name="business_name" id="business_name"  value="<?php echo e(old('business_name',$business_name)); ?>" placeholder="Business Name" aria-label="default input example">
                   </div>
                   <div class="col-md-6">
                     <label for="fullname" class="form-label">Business Address</label>
                     <input class="form-control mb-3" type="text" name="address" id="address"  value="<?php echo e(old('address',$address)); ?>" placeholder="Business Address" aria-label="default input example">
                   </div>

                   <div class="col-md-6">
                     <label for="fullname" class="form-label">Owner Name</label>
                     <input class="form-control mb-3" type="text" name="name" id="name"  value="<?php echo e(old('name',$name)); ?>" placeholder="Name" aria-label="default input example" placeholder="Owner Name">
                   </div>
                   <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input class="form-control mb-3" type="email" name="email" id="email"  value="<?php echo e(old('email',$email)); ?>" placeholder="Email" aria-label="default input example" placeholder="Email">
                  </div>

                  
                  <div class="col-md-6">
                    <label for="fullname" class="form-label">Phone</label>
                    <input class="form-control mb-3" type="number" name="phone" value="<?php echo e(old('phone',$phone)); ?>" id="phone"  aria-label="default input example" placeholder="Phone">
                  </div>
                  <div class="col-md-6">
                    <label for="fullname" class="form-label">Alternate Phone</label>
                    <input class="form-control mb-3" type="number" name="alternate_phone" value="<?php echo e(old('alternate_phone',$alternate_phone)); ?>" placeholder="Alternate Phone" id="alternate_phone"  aria-label="default input example">
                  </div>


                  <div class="col-md-6">
                    <label for="fullname" class="form-label">Username</label>
                    <input class="form-control mb-3" type="text" name="username" value="<?php echo e(old('username',$username)); ?>" id="username"  aria-label="default input example" placeholder="Username">
                  </div>

                  <div class="col-md-6">
                     <label for="email" class="form-label">Password</label>
                      <i class="fa fa-eye" id="togglePassword"></i>
                    <input class="form-control mb-3" type="password" name="password" id="password"  value="" placeholder="Password" aria-label="default input example" placeholder="Password">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Country</label>
                    <select class="form-control" name="country_id" id="country_id">
                      <option value="" selected>Select Country</option>
                      <?php if(!empty($countries)){
                        foreach($countries as $coun){
                          ?>
                          <option value="<?php echo e($coun->id); ?>" <?php if($coun->id == $country_id) echo "selected"?>><?php echo e($coun->name??''); ?></option>
                        <?php }}?>
                      </select>
                    </div>




                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">State</label>
                    <select class="form-control" name="state_id" id="state_id">
                      <?php if(!empty($states)){
                        foreach($states as $state){
                          ?>
                          <option value="<?php echo e($state->id); ?>" <?php if($state->id == $state_id) echo "selected"?>><?php echo e($state->name??''); ?></option>
                        <?php }}?>
                      </select>
                    </div>


                    <div class="col-md-6 mb-3">
                      <label for="email" class="form-label">City</label>
                      <select class="form-control" name="city_id" id="city_id">

                        <?php if(!empty($cities)){
                          foreach($cities as $city){
                            ?>
                            <option value="<?php echo e($city->id); ?>" <?php if($city->id == $city_id) echo "selected"?>><?php echo e($city->name??''); ?></option>
                          <?php }}?>
                        </select>
                      </div>



                      <div class="col-md-6">
                        <label for="fullname" class="form-label">GST No</label>
                        <input class="form-control mb-3" type="text" name="gst" value="<?php echo e(old('gst',$gst)); ?>" id="gst"  aria-label="default input example" placeholder="GST No">
                      </div>

                      <div class="col-md-6">
                        <label for="fullname" class="form-label">Locality (Pincode)</label>
                        <input class="form-control mb-3" type="text" name="pincode" value="<?php echo e(old('pincode',$pincode)); ?>" id="pincode" placeholder="Pincode"  aria-label="default input example">
                      </div>





                      <div class="col-md-6 ">
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



                      <div class="col-md-6 mb-3">
                       <label>Status</label>
                       <div>
                         Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                         &nbsp;
                         Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                         <?php echo $__env->make('snippets.errors_first', ['param' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                       </div>
                     </div>


                   </div>

                   <button class="btn btn-primary" type="submit">Submit </button>
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

<?php /**PATH /var/www/html/makesecurepro/resources/views/admin/party_users/form.blade.php ENDPATH**/ ?>