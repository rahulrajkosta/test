<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<?php

$BackUrl = CustomHelper::BackUrl();

$routeName = CustomHelper::getAdminRouteName();

$uri = Route::currentRouteName();
$back_url = '';
if($uri == 'admin.tsm.index'){
  $back_url = 'distributor';
}

if($uri == 'admin.seller.index'){
  $back_url = 'tsm';
}
if($uri == 'admin.distributor.index'){
  $back_url = 'superdistributor';
}



$storage = Storage::disk('public');

$path = 'influencer/thumb/';

// $roleId = Auth::guard('admin')->user()->role_id; 

$search = isset($search) ? $search :'';

$role_id = $_GET['role_id']??'';

$state_id = $_GET['state_id']??'';

$city_id = $_GET['city_id']??'';

$parent_id = $_GET['parent_id']??'';

$find_id = $_GET['find_id']??'';



$parent_user_id = Auth::guard('admin')->user()->id;

$parent_role_id = Auth::guard('admin')->user()->role_id;



$child = \App\Roles::where('parent_id',$parent_role_id)->first();



?>









<div class="app-content content">

  <div class="content-wrapper">

    <div class="content-wrapper-before"></div>

    <div class="content-header row">

      <div class="content-header-left col-md-4 col-12 my-2">

        <h3 class="content-header-title"><?php echo e($title??''); ?> - <?php echo e($users->total()); ?></h3>

      </div>

      <div class="content-header-right col-md-8 col-12 my-2">

        <div class="breadcrumbs-top float-md-right">

          <div class="breadcrumb-wrapper mr-1">

            <ol class="breadcrumb">

              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>

              </li>

              <li class="breadcrumb-item active"><?php echo e($title??''); ?>


              </li>

            </ol>

          </div>

        </div>

      </div>

    </div>

    <div class="content-body">

      <?php echo $__env->make('snippets.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

      <?php echo $__env->make('snippets.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

      <div class="row">

        <div class="col-12">

          <div class="card">

            <div class="card-header">

              <h4 class="card-title"><?php echo e($title??''); ?></h4>

              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

              <div class="heading-elements">

                <ul class="list-inline mb-0">

                  <li>

                    <!-- <a href="<?php echo e(route($routeName.'.'.$slug.'.export', ['back_url' => $BackUrl])); ?>" class="btn btn-primary"><i class="fas fa-file-export" aria-hidden="true"></i>Export</a> -->

                    &nbsp;&nbsp;&nbsp;



                    <?php //if($parent_role_id == 4){?>

                      <a href="<?php echo e(route($routeName.'.'.$slug.'.add', ['parent_id'=>$parent_id,'back_url' => $BackUrl])); ?>" class="btn btn-info btn-sm" >Add <?php echo e($title); ?></a>

                      <?php //}?>

                      &nbsp;&nbsp;&nbsp;

                      <?php //if(request()->has('back_url')){ $back_url= request('back_url');  ?>

                      <a href="<?php echo e($back_url); ?>" class="btn btn-info btn-sm" >Back</a><?php //} ?>

                    </li>

                  </ul>

                </div>

              </div>



              <div class="row">

                <div class="col-12">

                  <div class="card">

                    <div class="card-body">

                      <div class="">

                        <form method="">

                          <input type="hidden" name="parent_id" value="<?php echo e($parent_id??''); ?>">

                          <div class="row">

                            <div class="col-8">

                              <label class="form-label">Search By Name,Email,Phone</label>

                              <div class="input-group">

                                <input type="text" name="search" value="<?php echo e(old('search',$search)); ?>" class="form-control" placeholder="Search...." aria-label="Recipient's username">



                              </div>

                            </div>

                            <div class="col-4">

                              <label class="form-label">Search By ID</label>

                              <div class="input-group">

                                <input type="number" name="find_id" value="<?php echo e(old('find_id',$find_id)); ?>" class="form-control" placeholder="Search By ID...." aria-label="Recipient's username">



                              </div>

                            </div>






                            <div class="col-3">



                              <div class="input-group">

                                <button style="margin-top:27px;" class="btn input-group-text btn-dark waves-effect waves-light" type="submit">Search</button>

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

                        <th>Unique ID </th>

                        <th><?php echo e($roles->name); ?> Details</th>

                        <th><?php echo e(CustomHelper::getRoleName($roles->parent_id)); ?> Details</th>

                        <?php if(CustomHelper::isAllowedSection('login_details' , 'list')): ?>

                        <th>Login Details</th>

                        <?php endif; ?>

                        <th>Locality Details</th>

                        <th>Coupons</th>

                        <th>Action</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php if(!empty($users)){

                        foreach ($users as $user){

                          $parent_coupons = [];

                          $parent = CustomHelper::getUserDetails($user->parent_id);

                          $get_parents = CustomHelper::getParents($user->parent_id);

                          $business_name = $parent->business_name??'';

                          if(empty($business_name)){

                            $business_name = $parent->name??'';

                          }

                          $used_coupon = \App\AssignCoupon::select('id')->where('is_used',1)->where('is_return',0)->where('child_id',$user->id)->count();


                          $total_coupon = \App\AssignCoupon::select('id')->where('is_return',0)->where('child_id',$user->id)->count();

                          $remaining_coupon = $total_coupon-$used_coupon;



                          ?>

                          <tr <?php if($user->status == 0){?>
                            style="background: #e86d6d; color: black;"

                          <?php }?>
                            >

                           <td><?php echo e($user->id); ?></td>

                           <td><?php echo e($user->unique_id??''); ?></td>

                           <td>

                            <strong>Business Name: </strong><?php echo e($user->business_name??''); ?><br>

                            <strong>Owner Name: </strong><?php echo e($user->name??''); ?><br>

                            <strong>Email: </strong><?php echo e($user->email??''); ?>


                            <br><strong>Phone: </strong><?php echo e($user->phone??''); ?>


                            <br><strong>Alternate Phone: </strong><?php echo e($user->alternate_phone??''); ?>


                            <br><strong>Status: </strong><?php if ($user->status == 1){echo "Active";}else{echo "InActive";}?> 

                            <br><strong>Date & Time: </strong><?php echo e(date('d M Y h:i A',strtotime($user->created_at))); ?> 

                          </td>

                          <td>

                            <strong>Business Name: </strong><?php echo e($business_name??''); ?> --- <?php echo e($parent->unique_id??''); ?><br>

                            <br><strong>Phone: </strong><?php echo e($parent->phone??''); ?>


                            <br><strong>Owner Name: </strong><?php echo e($parent->name??''); ?>


                          </td>

                          <?php if(CustomHelper::isAllowedSection('login_details' , 'list')): ?>

                          

                          <td>

                            <?php 

                            if($child->id == $user->role_id){

                              ?>

                              <strong>Username: </strong><strong style="color:red;"><?php echo e($user->username??''); ?></strong><br>

                              <strong>Password: </strong><?php echo e($user->password_value??''); ?><br>

                            <?php }if($parent_role_id == 0){?>

                              <strong>Username: </strong><strong style="color:red;"><?php echo e($user->username??''); ?></strong><br>

                              <strong>Password: </strong><?php echo e($user->password_value??''); ?><br>

                            <?php }?>

                          </td>





                          <?php endif; ?>

                          <td><strong>Address: </strong><?php echo e($user->address??''); ?><br>

                            <strong>State: </strong><?php echo e(CustomHelper::getStateName($user->state_id)); ?><br>

                            <strong>City: </strong><?php echo e(CustomHelper::getCityName($user->city_id)); ?><br>

                            <strong>Pincode: </strong><?php echo e($user->pincode??''); ?></td>

                            <td>

                              <?php if(CustomHelper::isAllowedSection('show_coupon_history' , 'list')): ?>



                              <a href="<?php echo e(route($routeName.'.'.$slug.'.coupons_history', ['id'=>$user->id,'back_url'=>$BackUrl])); ?>"><label class="badge badge-sucess" style="background: #29d91d;">Show History

                              </label></a>

                              <br>

                              <?php endif; ?>

                              <?php if(CustomHelper::isAllowedSection('view_coupons' , 'list')): ?>



                              <!-- <strong>Coupons: </strong><?php echo e($user->no_of_coupons??0); ?> / <?php echo e($remaining_coupon??''); ?> -->

                              <!-- <strong>Coupons: </strong><?php echo e($user->no_of_coupons??0); ?> -->

                              <strong>Coupons: </strong><p><?php echo e(CustomHelper::getMyCouponCount($user->id)); ?>




                                <?php if($slug == 'seller'){?>

                                 / <?php echo e($remaining_coupon??0); ?>


                               <?php }?>

                             </p>









                             <br>

                             <a href="<?php echo e(route($routeName.'.'.$slug.'.view_coupons', ['id'=>$user->id,'back_url'=>$BackUrl])); ?>"><label class="badge badge-sucess" style="background: #29d91d;">View Coupons

                             </label></a>

                             <?php endif; ?>





                           </td>

                           <td>

                            <?php if(CustomHelper::isAllowedSection('edit_party_user' , 'list')): ?>



                            <a href="<?php echo e(route($routeName.'.'.$slug.'.edit', ['id'=>$user->id,'parent_id'=>$user->id,'back_url'=>$BackUrl])); ?>"><label  class="badge badge-sucess" style="background:teal; padding: 10px;" ><i class="fa fa-edit"></i> Edit</label>

                              <br>

                              <?php endif; ?>

                              <?php if(CustomHelper::isAllowedSection('change_password' , 'list')): ?>



                              <a href="javascript:void(0);" onclick=""><label class="badge badge-sucess" style="background: #29d91d;"> Change password

                              </label></a>

                              <br>

                              <?php endif; ?>

                              <?php if(CustomHelper::isAllowedSection('assign_coupon' , 'list')): ?>



                              <?php 

                              if($child->id == $user->role_id || $parent_role_id == 0){



                                ?>



                                <!-- <a data-toggle="modal" onclick="getparent_coupons('<?php echo e($user->parent_id); ?>','<?php echo e($user->id); ?>')" data-target="#assignCouponModal<?php echo e($user->id); ?>"><label class="badge badge-sucess" style="background: #29d91d;">  Assign Coupon</label></a> -->





                                <a data-toggle="modal" onclick="getparent_coupons('<?php echo e($user->parent_id); ?>','<?php echo e($user->id); ?>')" data-target="#assignCouponModal<?php echo e($user->id); ?>"><label class="badge badge-sucess" style="background: #29d91d;">  Assign Coupon

                                </label></a>



                                <div class="modal fade text-left" id="assignCouponModal<?php echo e($user->id); ?>" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1" aria-hidden="true">

                                  <?php 
                                  $business_name_child = $user->business_name??'';
                                  if(empty($business_name_child)){
                                    $business_name_child = $user->name??'';
                                  }
                                  ?>

                                  <div class="modal-dialog" role="document">

                                    <div class="modal-content">

                                      <div class="modal-header">

                                        <h4 class="modal-title" id="basicModalLabel1" >Assign Coupon <br>
                                         <strong> From : </strong><?php echo e($parent->business_name??''); ?> -- <?php echo e($parent->unique_id??''); ?><br>
                                          <strong>To : </strong><?php echo e($business_name_child??''); ?> -- <?php echo e($user->unique_id??''); ?></h4>

                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                          <span aria-hidden="true">&times;</span>

                                        </button>

                                      </div>

                                      <div class="modal-body">                      

                                        <form action="<?php echo e(route($routeName.'.coupons.assign_coupons')); ?>" method="post">

                                          <?php echo csrf_field(); ?>

                                          <input type="hidden" name="parent_id" value="<?php echo e($user->parent_id); ?>">

                                          <input type="hidden" name="child_id" value="<?php echo e($user->id); ?>">



                                          <div class="row">

                                            <div class="col-md-12 d-none">

                                              <label>Choose Coupons</label>

                                              <select class="form-control" name="coupons[]" multiple id="parent_coupon<?php echo e($user->id); ?>">

                                                <!-- <option value="" selected disabled>Choose Coupons</option> -->

                                                <?php 

                                                if(!empty($parent_coupons)){

                                                  $i = 0;

                                                  foreach($parent_coupons as $coup){?>

                                                    <option value="<?php echo e($coup->id); ?>"><?php echo e(++$i); ?>. <?php echo e($coup->couponID); ?></option>

                                                  <?php }}?>



                                                </select>

                                              </div>


                                              <div class="col-md-12">

                                              <label>Enter No. Of Coupons</label>
                                              <input type="number" name="coupon_qty" value="" class="form-control" placeholder="Enter No. Of Coupons">
                                              </div>



                                            </div>





                                          </div>

                                          <div class="modal-footer">

                                            <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>

                                            <button type="submit" class="btn btn-danger">Save</button>

                                          </div>

                                        </form>   

                                      </div>

                                    </div>

                                  </div>















                                <?php }?>

                                <br>

                                <?php endif; ?>





                                <?php 

                                if(!empty($child_role)){?>

                                  <?php if(CustomHelper::isAllowedSection('add_party_user' , 'list')): ?>



                                  <a href="<?php echo e(route($routeName.'.'.$child_role->slug.'.add', ['parent_id'=>$user->id,'back_url' => $BackUrl])); ?>"><label class="badge badge-sucess" style="background:red; padding: 10px;" ><i class="fa fa-plus"></i> Add  <?php echo e($child_role->name); ?></label>

                                  </a>

                                  <br>

                                  <?php endif; ?>



                                  <?php if(CustomHelper::isAllowedSection('view_party_user' , 'list')): ?>

                                  <a href="<?php echo e(route($routeName.'.'.$child_role->slug.'.index', ['parent_id'=>$user->id,'back_url' => $BackUrl])); ?>"><label  class="badge badge-sucess" style="background:#8d8d15; padding: 10px;" ><i class="fa fa-eye"></i> View  <?php echo e($child_role->name); ?> (<?php echo e(CustomHelper::getNoOfChild($user->id)); ?>)</label>

                                  </a>

                                  <?php endif; ?>







                                  <?php if(CustomHelper::isAllowedSection('telecaller_remarks' , 'list')): ?>

                                 <!--  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#remarksModal<?php echo e($user->id); ?>">

                                    Add Remarks

                                  </button> -->

                                  <br>



                                  <div class="modal fade" id="remarksModal<?php echo e($user->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                    <div class="modal-dialog" role="document">

                                      <div class="modal-content">

                                        <div class="modal-header">

                                          <h5 class="modal-title" id="exampleModalLabel"><?php echo e($user->business_name??$user->name??''); ?> --- <?php echo e($user->unique_id??''); ?></h5>

                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                            <span aria-hidden="true">&times;</span>

                                          </button>

                                        </div>

                                        <form action="<?php echo e(route($routeName.'.telecaller.admins_list')); ?>" method="post">

                                          <?php echo csrf_field(); ?>

                                          <input type="hidden" name="admin_id" value="<?php echo e($user->id); ?>">

                                          <div class="modal-body">

                                            <label>Remarks</label>

                                            <textarea class="form-control" placeholder="Enter Remarks" name="remarks"></textarea>

                                          </div>

                                          <div class="modal-footer">

                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                            <button type="submit" class="btn btn-primary">Save</button>

                                          </div>

                                        </form>

                                      </div>

                                    </div>

                                  </div>





                                  <?php endif; ?>







                                <?php }?>



                                <?php if(CustomHelper::isAllowedSection('return_coupon_admin' , 'list')): ?>

                                <a data-toggle="modal" onclick="return_coupons('<?php echo e($user->id); ?>')" data-target="#returnCoupon<?php echo e($user->id); ?>"><label class="badge badge-sucess" style="background: green;">Return Coupon

                               </label></a> 

                               <br>



                               <div class="modal fade text-left" id="returnCoupon<?php echo e($user->id); ?>" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1" aria-hidden="true">

                                <div class="modal-dialog" role="document">

                                  <div class="modal-content">

                                    <div class="modal-header">

                                      <h4 class="modal-title" id="basicModalLabel1" style="text-align: center;">Return Coupon<br><?php echo e($user->business_name??$user->name??''); ?> -- <?php echo e($user->unique_id??''); ?></h4>

                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                        <span aria-hidden="true">&times;</span>

                                      </button>

                                    </div>

                                    <div class="modal-body">                      

                                      <form action="<?php echo e(route($routeName.'.'.$slug.'.return_coupon')); ?>" method="post">

                                        <?php echo csrf_field(); ?>

                                        <input type="hidden" name="party_user_id" value="<?php echo e($user->id); ?>">

                                        <div class="row">

                                          <div class="col-md-12">

                                            <label>Choose Coupons</label>

                                            <select class="form-control " name="couponids[]" multiple id="return_counds_html<?php echo e($user->id); ?>">




                                            </select>

                                          </div>



                                        </div>





                                      </div>

                                      <div class="modal-footer">

                                        <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>

                                        <button type="submit" class="btn btn-danger">Save</button>

                                      </div>

                                    </form>   

                                  </div>

                                </div>

                              </div>











                              <?php endif; ?>





                              <?php if(CustomHelper::isAllowedSection('change_parent' , 'list')): ?>

                              <!--   <a data-toggle="modal" data-target="#changeParent<?php echo e($user->id); ?>"><label class="badge badge-sucess" style="background: #E000FF;">Change Parent

                              </label></a> -->

                              <br>



                              <div class="modal fade text-left" id="changeParent<?php echo e($user->id); ?>" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1" aria-hidden="true">

                                <div class="modal-dialog" role="document">

                                  <div class="modal-content">

                                    <div class="modal-header">

                                      <h4 class="modal-title" id="basicModalLabel1" style="text-align: center;">Change Parent <br><?php echo e($user->business_name??''); ?> -- <?php echo e($user->unique_id??''); ?></h4>

                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                        <span aria-hidden="true">&times;</span>

                                      </button>

                                    </div>

                                    <div class="modal-body">                      

                                      <form action="<?php echo e(route($routeName.'.'.$slug.'.change_parent')); ?>" method="post">

                                        <?php echo csrf_field(); ?>

                                        <input type="hidden" name="party_user_id" value="<?php echo e($user->id); ?>">

                                        <div class="row">

                                          <div class="col-md-12">

                                            <label>Choose Parent</label>

                                            <select class="form-control " name="parent_id">

                                              <option value="" selected disabled>Choose Parent</option>

                                              <?php 

                                              if(!empty($get_parents)){

                                                $i = 0;

                                                foreach($get_parents as $use){

                                                  $business_name = $use->business_name??'';

                                                  if(empty($business_name)){

                                                    $business_name = $use->name??'';



                                                  }

                                                  ?>

                                                  <option value="<?php echo e($use->id); ?>" <?php if($user->parent_id == $use->id) echo "selected"?>><?php echo e($business_name??''); ?> -- <?php echo e($use->unique_id??''); ?></option>

                                                <?php }}?>



                                              </select>

                                            </div>



                                          </div>





                                        </div>

                                        <div class="modal-footer">

                                          <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>

                                          <button type="submit" class="btn btn-danger">Save</button>

                                        </div>

                                      </form>   

                                    </div>

                                  </div>

                                </div>











                                <?php endif; ?>



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



      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>





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

        function getparent_coupons(parent_id,user_id){

          // alert('okk');



          var _token = '<?php echo e(csrf_token()); ?>';

          $.ajax({

            url: "<?php echo e(route($routeName.'.getparent_coupons')); ?>",

            type: "POST",

            data: {parent_id:parent_id, user_id:user_id},

            dataType:"HTML",

            headers:{'X-CSRF-TOKEN': _token},

            cache: false,

            success: function(resp){

              $('#parent_coupon'+user_id).html(resp);

                  // $('#assignCouponModal'+user_id).modal('toggle');

            }

          });

        }

        function return_coupons(user_id){

          // alert('okk');



          var _token = '<?php echo e(csrf_token()); ?>';

          $.ajax({

            url: "<?php echo e(route($routeName.'.return_coupons')); ?>",

            type: "POST",

            data: {user_id:user_id},

            dataType:"HTML",

            headers:{'X-CSRF-TOKEN': _token},

            cache: false,

            success: function(resp){

              $('#return_counds_html'+user_id).html(resp);

                  // $('#assignCouponModal'+user_id).modal('toggle');

            }

          });

        }











      </script>

<?php /**PATH /var/www/html/makesecurepro/resources/views/admin/party_users/index.blade.php ENDPATH**/ ?>