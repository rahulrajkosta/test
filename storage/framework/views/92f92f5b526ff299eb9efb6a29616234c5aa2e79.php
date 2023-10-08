<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();

$states = \App\State::where('status',1)->get();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
$roleId = Auth::guard('admin')->user()->role_id; 
$user_id = Auth::guard('admin')->user()->id; 
$search = isset($search) ? $search :'';
$role_id = $_GET['role_id']??'';
$state_id = $_GET['state_id']??'';
$city_id = $_GET['city_id']??'';
$pincode = $_GET['pincode']??'';
$phone_status = $_GET['phone_status']??'';
$roles = CustomHelper::getChildRolesAll($roleId);
$key = '';
$superdist_id = [];
if($roleId == 7){
 $roles = CustomHelper::getChildRolesAll(0);
 $user_id = 1;
 $admin_id = Auth::guard('admin')->user()->id; 

 $key = 'statehead';
 $admin_data = \App\Admin::where('id',$admin_id)->first();
 $superdist_id= $admin_data->superdist_id??'';
 if(!empty($superdist_id)){
  $superdist_id = explode(",", $superdist_id);

}
}



$cities = [];
if(!empty($state_id)){
  $cities = \App\City::where('state_id',$state_id)->get();
}
?>




<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 my-2">
        <h3 class="content-header-title">Users</h3>
      </div>
      <div class="content-header-right col-md-8 col-12 my-2">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
              </li>
              <li class="breadcrumb-item active">Users
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>




    <div class="content-body">

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Users - (<?php echo e($users->total()); ?>)</h2>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                    <!-- <a href="<?php echo e(route($routeName.'.user.export', ['back_url' => $BackUrl])); ?>" class="btn btn-primary"><i class="fas fa-file-export" aria-hidden="true"></i>Export</a> -->
                    &nbsp;&nbsp;&nbsp;
                    <!-- <a href="<?php echo e(route($routeName.'.user.add', ['back_url' => $BackUrl])); ?>" class="btn btn-info btn-sm" style='float: right;'>Add User</a> -->
                  </li>
                </ul>
              </div>
            </div>

            <?php echo $__env->make('snippets.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('snippets.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="">
                      <form>

                        <input type="hidden" name="is_export" value="0" id="is_export">
                        <div class="row">
                          <?php if(!empty($roles)){
                            $i=1;
                            $j=4;
                            foreach($roles as $role){
                              $child_role = \App\Roles::where('parent_id',$role->id)->first();
                              $allusers = [];
                              $getChildIds = CustomHelper::getChildIds($role->id,$user_id,$i,'');
                              if(!empty($getChildIds)){
                                if($i == 1){
                                  $allusers = \App\Admin::whereIn('id',$getChildIds)->get();
                                }
                              }
                              ?>
                              <!-- <input type="hidden" name="key<?php echo e($role->id); ?>" value="<?php echo e($j); ?>">
                                <input type="hidden" name="role_id<?php echo e($role->id); ?>" value="<?php echo e($role->id); ?>"> -->
                                <div class="col-4 mt-3">
                                  <label class="form-label"><?php echo e($role->name??''); ?></label>
                                  <div class="input-group">
                                    <select class="form-control select2" name="<?php echo e($role->slug.'_id'); ?>" id="role<?php echo e($role->slug); ?>" onchange="get_childs(this.value,'<?php echo e($child_role->slug??''); ?>')">
                                      <option value="" selected disabled>Select User Type</option>
                                      <?php if(!empty($allusers)){
                                        foreach($allusers as $user){
                                          $business_name = $user->business_name??'';
                                          if(empty($business_name)){
                                            $business_name = $user->name??'';
                                          }

                                          if($roleId == 7){
                                            if(in_array($user->id,$superdist_id)){
                                              ?>
                                              <option value="<?php echo e($user->id); ?>"><?php echo e($business_name??''); ?> -- <?php echo e($user->unique_id??''); ?></option>
                                            <?php }}else{?>
                                              <option value="<?php echo e($user->id); ?>"><?php echo e($business_name??''); ?> -- <?php echo e($user->unique_id??''); ?></option>
                                            <?php }}}?>
                                          </select>

                                        </div>
                                      </div>
                                      <?php $i++;
                                      $j--;
                                    }}?>






                                    <div class="col-4 mt-3">
                                      <label class="form-label">Start Date</label>
                                      <input type="date" name="start_date" value="" class="form-control">
                                    </div>

                                    <div class="col-4 mt-3">
                                      <label class="form-label">End Date</label>
                                      <input type="date" name="end_date" value="" class="form-control">
                                    </div>



                                    <div class="col-4 mt-3">
                                      <label class="form-label">State</label>
                                      <select name="state_id" class="form-control select2" id="state_id">
                                        <option value="" selected>State State</option>
                                        <?php 
                                        if(!empty($states)){
                                          foreach($states as $state){?>
                                            <option value="<?php echo e($state->id??''); ?>" <?php if($state_id == $state->id) echo "selected"?>><?php echo e($state->name??''); ?></option>
                                          <?php }
                                        }
                                        ?>
                                      </select>
                                    </div>

                                    <div class="col-4 mt-3">
                                      <label class="form-label">City</label>
                                      <select name="city_id" class="form-control select2" id="city_id">
                                        <option value="" selected>Select City</option>
                                        <?php 
                                        if(!empty($cities)){
                                          foreach($cities as $city){?>
                                            <option value="<?php echo e($city->id??''); ?>" <?php if($city_id == $city->id) echo "selected"?>><?php echo e($city->name??''); ?></option>
                                          <?php }
                                        }
                                        ?>
                                      </select>
                                    </div>

                                    <div class="col-4 mt-3">
                                      <label class="form-label">Pincode</label>
                                      <input type="text" name="pincode" value="<?php echo e($pincode??''); ?>" class="form-control" placeholder="Enter Pincode">
                                    </div>




                                    <div class="col-6 mt-3">
                                      <label class="form-label">Search By Name,Email,Phone</label>
                                      <div class="input-group">
                                        <input type="text" name="search" value="<?php echo e(old('search',$search)); ?>" class="form-control" placeholder="Search...." aria-label="Recipient's username">

                                      </div>
                                    </div>

                                    <div class="col-6 mt-3">
                                      <label class="form-label">Phone Status</label>
                                      <div class="input-group">
                                        <select class="form-control" name="phone_status">
                                          <option value="" selected>Select Phone Status</option>
                                          <option value="locked" <?php if($phone_status == 'locked') echo "selected"?>>Locked</option>
                                          <option value="unlock" <?php if($phone_status == 'unlock') echo "selected"?>>Unlock</option>
                                          <option value="remove" <?php if($phone_status == 'remove') echo "selected"?>>Remove</option>
                                        </select>

                                      </div>
                                    </div>

                                    <div class="col-4 mt-3">

                                     <div class="d-flex">

                                       <div class="input-group">
                                        <button style="margin-top:27px;" class="btn input-group-text btn-dark waves-effect waves-light" type="submit">Search</button>
                                      </div>
                                      <div class="input-group">
                                        <a href="<?php echo e(route('admin.user.index')); ?>" style="margin-top:27px;" class="btn input-group-text btn-dark waves-effect waves-light" type="submit">Reset</a>
                                      </div>
                                      <?php if($roleId == 0){?>

                                    <!--   <div class="input-group">
                                        <button style="margin-top:27px;" class="btn input-group-text btn-dark waves-effect waves-light" onclick="export_user()">Export</button>
                                      </div> -->

                                    <?php }?>                                       
                                  </div>
                                </div>
                              </div>

                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
        


                    <div class="card-content collapse show">
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>

                              <th>#ID</th>
                              <th>User Details</th>
                              <th>Coupon Details</th>
                              <th width="50">Phone Details</th>
                              <th>Emi Details</th>
                              <th>Location</th>
                              <th>Phone Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(!empty($users)){
                              foreach ($users as $user){

                                // $image = url('public/storage/user/'.$user->user_image);

                                // $ch = curl_init($image);
                                // curl_setopt($ch, CURLOPT_NOBODY, true);
                                // curl_exec($ch);
                                // $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                // curl_close($ch);
                                // if($responseCode == 200){
                                //   $image = url('public/storage/user/'.$user->user_image);

                                // }else{
                                if($user->image_version == 1){
                                 $image = 'https://admin.reptileindia.co.in/uploads/user/'.$user->user_image;
                               }else{
                                $image = url('public/storage/user/'.$user->user_image);
                              }

                               // }
                                //  if(!file_exists($image)){
                                //    $image = 'https://admin.reptileindia.co.in/uploads/user/'.$user->user_image;
                                //  }else{

                                //   $image = url('public/storage/user/'.$user->user_image);
                                // }
                              ?>
                              <tr>
                               <td><?php echo e($user->user_id); ?></td>
                               <td><?php echo e($user->user_name ?? ''); ?><br><?php echo e($user->user_phone ?? ''); ?> <br>
                                <!-- <img src="<?php echo e(url('public/storage/user/'.$user->user_image)); ?>" style="height:70px;width:70px"></td> -->

                                <a target="_blank" href="<?php echo e($image); ?>"><img src="<?php echo e($image); ?>" style="height:70px;width:70px"></a>

                                <p>Phone Status : <?php if($user->is_locked == 1){
                                  echo "<span style='color:red;'>Screen Locked</span>";
                                }else{
                                  echo "<span style='color:green;'>Screen UnLocked</span>";
                                }?></p>


                                <?php if(!empty($user->screenshot)){?>
                                  <p>ScreenShot</p>
                                  <a href="<?php echo e(url('public/storage/screenshot/'.$user->screenshot)); ?>" target="_blank"><img src="<?php echo e(url('public/storage/screenshot/'.$user->screenshot)); ?>" height="50px" width="50px"></a>
                                <?php }?>

                              </td>
                              <td>
                                <?php 
                                $admin = \App\Admin::where('id',$user->coupon_parent_id)->first();
                                ?>
                                <strong>Business Name : </strong><?php echo e($admin->business_name ?? ''); ?><br><strong>Unique ID :</strong> <?php echo e($admin->unique_id ?? ''); ?>

                                <br><strong>Coupon Code :</strong> <?php echo e($user->coupon_code ?? ''); ?><br><strong>Used At : </strong><?php echo e($user->date_of_purchase ?? ''); ?>

                              </td>
                              <td width="30%">
                                <strong>Phone Model : </strong><?php echo e($user->phone_model ?? ''); ?><br><strong>Mobile Name :</strong> <?php echo e($user->mobile_name ?? ''); ?>

                                <br><strong>IMEI No :</strong> <?php echo e($user->imei_no ?? ''); ?><br><strong>Date Of Purchase : </strong><?php echo e($user->date_of_purchase ?? ''); ?>

                                <br><span style="line-break:anywhere;"><strong>Device Token : </strong><?php echo e($user->device_token ?? ''); ?></span>

                              </td>

                              <td>
                                <strong>Total Price : </strong><?php echo e($user->total_price ?? ''); ?><br><strong>Down Payment :</strong> <?php echo e($user->downpayment ?? ''); ?>

                                <br><strong>Emi :</strong> <?php echo e($user->emi ?? ''); ?><br><strong>Total Months : </strong><?php echo e($user->total_months ?? ''); ?><br><strong>Start Date : </strong><?php echo e($user->start_date ?? ''); ?>


                              </td>
                              <td><strong>Latitute : </strong><?php echo e($user->latitute ?? ''); ?> <br><strong>Longitude : </strong><?php echo e($user->longitude?? ''); ?></td>
                              <td>
                                <h2><?php echo e(ucfirst($user->phone_status)); ?></h2>
                                <br>
                                <br>
                                <?php if($user->phone_status == 'locked'){?>
                                  <a  onclick="change_phone_status('<?php echo e($user->user_id); ?>','unlock')"  class="btn btn-success">Unlock</a> &nbsp;&nbsp;
                                  <br>
                                  <br>
                                  <a href="" onclick="change_phone_status('<?php echo e($user->user_id); ?>','remove')" class="btn btn-info">Remove</a> &nbsp;&nbsp;
                                <?php }?>

                                <?php if($user->phone_status == 'unlock'){?>
                                  <a onclick="change_phone_status('<?php echo e($user->user_id); ?>','locked')"  class="btn btn-danger">Lock</a> &nbsp;&nbsp;
                                  <br>
                                  <br>
                                  <a href="" onclick="change_phone_status('<?php echo e($user->user_id); ?>','remove')" class="btn btn-info">Remove</a> &nbsp;&nbsp;
                                <?php }?>




                                    <!--  <select class="form-control" name="phone_status" id="change_phone_status<?php echo e($user->user_id); ?>" onchange="change_phone_status(<?php echo e($user->user_id); ?>);" style="width:auto;">
                                          <option value="" selected>Select Phone Status</option>
                                          <option value="locked" <?php //if($user->phone_status == 'locked'){ echo 'selected'; }?>>Locked</option>
                                          <option value="unlock" <?php //if($user->phone_status == 'unlock'){ echo 'selected'; }?>>Unlock</option>
                                          <option value="remove" <?php //if($user->phone_status == 'remove'){ echo 'selected'; }?>>Remove</option>
                                        </select> -->
                                      </td>

                                      <td> 
                              <!-- <div class="d-flex">
                                 <a class="btn btn-warning" title="Transaction" href="<?php echo e(route($routeName.'.user.transaction', $user->id.'?back_url='.$BackUrl)); ?>"><i class="fa fa-rupee"></i></a>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-info" title="User List" href="<?php echo e(route($routeName.'.user.user_list', $user->id.'?back_url='.$BackUrl)); ?>"><i class="fa fa-user-circle"></i></a>
                               </div>
                               <br> 
                                <div class="d-flex">
                                 <a class="btn btn-warning" title="Transaction" href="<?php echo e(route($routeName.'.user.transaction', $user->id.'?back_url='.$BackUrl)); ?>"><i class="fa fa-rupee"></i></a>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-info" title="User List" href="<?php echo e(route($routeName.'.user.user_list', $user->id.'?back_url='.$BackUrl)); ?>"><i class="fa fa-user-circle"></i></a>
                              </div> -->

                            </td>
                          </tr>

                        <?php }}?>
                      </tbody>
                    </table>
                    <?php echo e($users->appends(request()->input())->links('admin.pagination')); ?>


                  </div>
                </div>

            </div>
          </div>
        </div>
        <!-- Responsive tables end -->
      </div>
    </div>
  </div>
  <!-- END: Content-->






  <?php echo $__env->make('admin.common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



  <script>
    function change_user_role(id){
      var role_id = $('#change_user_role'+id).val();
      var _token = '<?php echo e(csrf_token()); ?>';
      $.ajax({
        url: "<?php echo e(route($routeName.'.user.change_user_role')); ?>",
        type: "POST",
        data: {id:id, role_id:role_id},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){
          if(resp.success){
            alert(resp.message);
          }else{
            alert(resp.message);

          }
        }
      });
    }

    function get_childs(user_id,role_name){
      var _token = '<?php echo e(csrf_token()); ?>';
      $.ajax({
        url: "<?php echo e(route($routeName.'.get_childs')); ?>",
        type: "POST",
        data: {user_id:user_id},
        dataType:"HTML",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){

          $("#role"+role_name).html(resp);
        }
      });
    }

    function export_user(){
      $('#is_export').val(1);
    }


    function change_phone_status(id,phonestatus)
    {
      if(confirm('Are You Want To Sure To Change Status')){
       var _token = '<?php echo e(csrf_token()); ?>';
       $.ajax({
        url: "<?php echo e(route($routeName.'.user.change_phone_status')); ?>",
        type: "POST",
        data: {phonestatus:phonestatus,id:id},
        dataType:"HTML",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){
          alert(resp);
          location.reload();
        }
      });
     }else{
      return false;
    }

  }
</script>
<?php /**PATH /var/www/html/example-app/resources/views/admin/user/index.blade.php ENDPATH**/ ?>