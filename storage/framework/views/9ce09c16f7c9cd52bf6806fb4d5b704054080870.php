<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$coupon_id = isset($coupons->id) ? $coupons->id :"";
$status = isset($coupons->status) ? $coupons->status :"";

$roles = CustomHelper::getChildRoles();
$min = '1';
if(!empty($last_coupon)){
  $min = (int)ltrim($last_coupon->couponID, 'MATRIX')+1 ;
}

?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title"><?php echo e($page_heading); ?></h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
              </li>
              <li class="breadcrumb-item active"><?php echo e($page_heading); ?>

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
                <h4 class="card-title"><?php echo e($page_heading); ?></h4>
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
                <h1 style="color:red;">Last Coupon Serial No is <?php echo e($min??1); ?></h1>
                <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

                  <?php echo e(csrf_field()); ?>


                  <input type="hidden" id="id" value="<?php echo e($status); ?>">
                  <div class="form-row">



                    <div class="col-md-6 mb-3">
                     <label for="fullname" class="form-label">Roles</label>
                     <select class="form-control select2" name="role_id" id="role_id">
                      <option value="" selected disabled>Choose Role</option>
                      <?php if(!empty($roles)){
                        foreach($roles as $role){
                          if($role->id == 2){
                          ?>
                          <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                        <?php }}}?>
                      </select>

                    </div>


                    <div class="col-md-6 mb-3">
                     <label for="fullname" class="form-label">Party User</label>
                     <select class="form-control select2" name="parent_id" id="parent_id">
                      <option value="" selected disabled>Choose Party User</option>
                      
                    </select>

                  </div>
                  <div class="col-md-4 mb-3">
                   <label for="fullname" class="form-label">Minimum coupon No</label>
                   <input class="form-control mb-3" type="text" name="min_coupon" id="min_coupon"  value="<?php echo e(old('min_coupon',$min)); ?>" disabled placeholder="No Of Coupons" aria-label="default input example">
                 </div>

                 <div class="col-md-4 mb-3">
                   <label for="fullname" class="form-label">No Of Coupons</label>
                   <input class="form-control mb-3" type="number" name="no_of_coupons" id="no_of_coupons"  value="<?php echo e(old('no_of_coupons')); ?>" placeholder="No Of Coupons" aria-label="default input example">
                 </div>
                 <div class="col-md-4 mb-3">
                   <label for="fullname" class="form-label">Maximum coupon No</label>
                   <input class="form-control mb-3" type="text" name="max_coupon" id="max_coupon"  value="<?php echo e(old('max_coupon')); ?>" disabled placeholder="No Of Coupons" aria-label="default input example">
                 </div>


                 <div class="col-md-6 mb-3">
                   <label for="fullname" class="form-label">Invoice No</label>
                   <input class="form-control mb-3" type="text" name="invoice_no" id="invoice_no"  value="<?php echo e(old('invoice_no')); ?>" placeholder="No Of Coupons" aria-label="default input example">
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

<script type="text/javascript">
  $('#role_id').on('change', function() {
    var role_id = this.value;
    _token = '<?php echo e(csrf_token()); ?>';
    $.ajax({
      url: "<?php echo e(route($routeName.'.coupons.get_party_user')); ?>",
      method: 'POST',
      data:{role_id:role_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      success: function(resp) {
       $('#parent_id').html(resp);
     }
   });


  });


  
  $("#no_of_coupons").keyup(function(){
    var count = $('#no_of_coupons').val();
    if(count != ''){
      var start = '<?php echo e($min); ?>';
      value = parseInt(start) + parseInt(count) - 1;
      $('#max_coupon').val(value);
    }else{
      $('#max_coupon').val('0');

    }
    
  });
</script><?php /**PATH D:\after_emilocket_\makesecurepro\resources\views/admin/coupons/form.blade.php ENDPATH**/ ?>