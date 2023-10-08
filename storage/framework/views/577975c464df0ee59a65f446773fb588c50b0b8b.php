<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'category/';
// $roleId = Auth::guard('admin')->user()->role_id; 
$role_id = Auth::guard('admin')->user()->role_id;
if($role_id == 0){
  $roles = \App\Roles::where('parent_id','>=',$role_id)->where('parent_id','!=',0)->get();
}elseif($role_id == 7){
  $roles = \App\Roles::where('parent_id','>=',0)->where('parent_id','!=',0)->get();
}else{
  $roles = \App\Roles::where('parent_id',$role_id)->get();

}
$search = isset($_GET['search']) ? $_GET['search'] :'';
?>


<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Coupons</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
              </li>
              <li class="breadcrumb-item active">Coupons
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
              <h4 class="card-title">Coupons</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                   <a href="<?php echo e(route($routeName.'.coupons.add', ['back_url' => $BackUrl])); ?>" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Coupons</a>
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
                        
                        <div class="row">
                          <div class="col-8">
                            <label class="form-label">Search By Coupon ID , Coupon Code</label>
                            <div class="input-group">
                              <input type="text" name="search" value="<?php echo e(old('search',$search)); ?>" class="form-control" placeholder="Search...." aria-label="Recipient's username">

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
                   <th>S.No.</th>
                   <th>Coupon ID</th>
                   <?php 
                   if(!empty($roles)){
                    foreach($roles as $role){?>

                      <th><?php echo e($role->name??''); ?></th>
                    <?php }}?>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Time</th>
                  </tr>
                </thead>
                <tbody>
                 <?php if(!empty($coupons)){

                  $i = 1;
                  foreach($coupons as $coupon){
                    ?>
                    <tr>
                      <td><?php echo e($coupon->id); ?></td>
                      <td><?php echo e($coupon->couponID ?? ''); ?></td>

                      <?php 
                      if(!empty($roles)){
                        foreach($roles as $role){

                          $coupon_data = \App\AssignCoupon::select('child_id')->where('coupon_id',$coupon->id)->where('child_role_id',$role->id)->first();
                          $user = [];
                          if(!empty($coupon_data)){
                            // $user = CustomHelper::getUserDetails($coupon_data->child_id);
                            $user = \App\Admin::select('business_name','name','unique_id')->where('id',$coupon_data->child_id)->first();
                            
                          }

                          ?>
                          <td>
                            <?php if(!empty($user)){
                              $business_name = $user->business_name??'';
                              if(empty($business_name)){
                              $business_name = $user->name??'';

                              }
                              ?>
                            <p><strong>Business Name :</strong> <?php echo e($business_name??''); ?></p>
                            <p><strong>Unique ID :</strong> <?php echo e($user->unique_id??''); ?></p>
                            
                          <?php }?>
                          </td>
                        <?php }}?>
                        <td><?php echo e($coupon->invoice_no ?? ''); ?></td>
                        <td><?php echo e(date('d M Y',strtotime($coupon->date)) ?? ''); ?></td>
                        <td><?php echo e(date('h:i A',strtotime($coupon->time)) ?? ''); ?></td>
                      </tr>
                    <?php }}?>
                  </tbody>
                </table>
                <?php echo e($coupons->appends(request()->input())->links('admin.pagination')); ?>


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

<?php /**PATH /var/www/html/example-app/resources/views/admin/coupons/index.blade.php ENDPATH**/ ?>