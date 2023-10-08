<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
    $back_url = 'admin/countries';
}

$id = (isset($countries->id)) ? $countries->id : '';
$name = (isset($countries->name))?$countries->name:'';
$status = (isset($countries->status))?$countries->status:1;
$storage = Storage::disk('public');
$path = 'countries/';
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


          <input type="hidden" name="id" value="<?php echo e($id); ?>">

          <div class="form-row">

           <div class="col-md-6">
            <label for="exampleInputEmail1" class="form-label">Country Name</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Country Name" name="name" value="<?php echo e(old('name', $name)); ?>">
            <?php echo $__env->make('snippets.errors_first', ['param' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
<?php /**PATH /var/www/html/makesecurepro/resources/views/admin/countries/form.blade.php ENDPATH**/ ?>