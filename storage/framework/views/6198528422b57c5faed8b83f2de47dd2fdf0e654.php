<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php



$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
  $back_url = 'admin/cities';
}

$id = isset($cities->id) ? $cities->id : '';
$name = (isset($cities->name))?$cities->name:'';
$country_id=(isset($cities->country_id))?$cities->country_id:'';
$state_id=(isset($cities->state_id))?$cities->state_id:'';

$status = (isset($cities->status))?$cities->status:1;
$icon = (isset($cities->icon))?$cities->icon:'';
$storage = Storage::disk('public');

    //pr($storage);

$path = 'cities/';

?>
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
                    <select class="form-control select2-single" name="country_id" id="country_id">
                     <option value="" selected disabled>Select Country Name</option>
                     <?php 

                     
                     if(!empty($countries)){
                      foreach($countries as $c) 
                      {

                        ?>
                        <option value="<?php echo e($c->id); ?>" <?php if($country_id == $c->id) echo 'selected'; ?>><?php echo e($c->name); ?></option>
                      <?php  } }  ?>
                    </select>
                    <?php echo $__env->make('snippets.errors_first', ['param' => 'country_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  </div>
                  <div class="col-md-6">
                   <label for="exampleInputEmail1" class="form-label">State Name</label>
                   <select class="form-control select2-single" name="state_id" id="state_id">
                     <option value="" selected disabled>Select State Name</option>
                     <?php 

                     if(!empty($states)){
                      foreach($states as $state) 
                        {?>
                          <option value="<?php echo e($state->id); ?>" <?php if($state_id == $state->id) echo 'selected'; ?>><?php echo e($state->name); ?></option>
                        <?php  } }  ?>
                      </select>
                      <?php echo $__env->make('snippets.errors_first', ['param' => 'state_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="col-md-6">
                      <label for="exampleInputEmail1" class="form-label">City Name</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter City Name" name="name" value="<?php echo e(old('name', $name)); ?>">
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

<script>

  $('#country_id').change( function()
  {

    var _token = '<?php echo e(csrf_token()); ?>';
    var country_id = $('#country_id').val();
    $.ajax({
      url: "<?php echo e(route('admin.cities.get_state')); ?>",
      type: "POST",
      data: {country_id:country_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
       $('#state_id').html(resp);
     }
   });
  });

  
  $('#state_id').change( function()
  {

    var _token = '<?php echo e(csrf_token()); ?>';
    var state_id = $('#state_id').val();
    $.ajax({
      url: "<?php echo e(route('admin.cities.get_city')); ?>",
      type: "POST",
      data: {state_id:state_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
       $('#city_id').html(resp);
     }
   });
  });




</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.select2-single').select2();
  });
</script>
<?php /**PATH /var/www/html/makesecurepro/resources/views/admin/cities/form.blade.php ENDPATH**/ ?>