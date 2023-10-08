<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'banners/';

$banner_id = isset($banners->id) ? $banners->id : '';

$image = isset($banners->image) ? $banners->image : '';
$category_id = isset($banners->category_id) ? $banners->category_id : '';
$link = isset($banners->link) ? $banners->link : '';
$type = isset($banners->type) ? $banners->type : '';
$status = isset($banners->status) ? $banners->status : '';

 //print_r($banners);


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


                  <input type="hidden" id="id" value="<?php echo e($banner_id); ?>">

                  <div class="form-row">
                    <div class="col-md-6 mb-3">
                      <label for="fullname" class="form-label">Upload File</label>
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
                      <label for="email" class="form-label">Type</label>
                      <select name="type" class="form-control">
                        <option value="" selected disabled>Select Type</option>
                        <option value="image" <?php if($type == 'image') echo "selected";?>>Image</option>
                        <option value="video" <?php if($type == 'video') echo "selected";?>>Video</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="email" class="form-label">Link</label>
                      <input type="text" class="form-control mb-3" name="link" id="link" placeholder="Enter Link" value="<?php echo e(old('link',$link)); ?>">
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

<?php /**PATH /var/www/html/example-app/resources/views/admin/banners/form.blade.php ENDPATH**/ ?>