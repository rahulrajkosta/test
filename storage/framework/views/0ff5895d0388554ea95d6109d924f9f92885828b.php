<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';


$storage = Storage::disk('public');
$path = 'cities/';


?>

<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">City List</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
              </li>
              <li class="breadcrumb-item active">City List
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
              <h4 class="card-title">City List</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                    <a href="<?php echo e(route($routeName.'.cities.add', ['back_url' => $BackUrl])); ?>" class="btn btn-info btn-sm" style='float: right;'>Create City</a>
                  </li>
                </ul>
              </div>
            </div>

            
            <div class="card-content collapse show">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="">S.No</th>

                      <th class="">Country</th>                     
                      <th class="">State </th>
                      <th class="">City Name </th>
                      <th class="">Image </th>
                      <th class="">Status</th>
                      <th class="">Action</th>

                    </tr>
                  </thead>
                  <tbody>
                   
                   <?php if(!empty($cities) && $cities->count() > 0){
                    $i = 1;
                    foreach ($cities as $city){

                     $countryname = $city->cityCountry->name;

                     $cityState = (isset($city->cityState))?$city->cityState:'';
                     $stateName = (isset($cityState->name))?$cityState->name:'';

                     $icon = isset($city->icon) ? $city->icon :'';
                     ?>
                     <tr>
                      <td><?php echo e($i++); ?></td>
                      <td><?php echo e($countryname); ?></td>
                      <td><?php echo e($stateName); ?></td>
                      <td><?php echo e($city->name); ?></td>
                      <td>
                       <?php 
                       if(!empty($icon)){
                         if($storage->exists($path.$icon)){
                          ?>
                          <div class=" image_box" style="display: inline-block">
                            <a href="<?php echo e(url('public/storage/'.$path.$icon)); ?>" target="_blank">
                              <img src="<?php echo e(url('public/storage/'.$path.'thumb/'.$icon)); ?>" style="width:70px;">
                            </a>
                          </div>
                          <?php
                        }
                      }
                      ?>
                    </td>
                    <td><?php  echo ($city->status==1)?'Active':'Inactive';  ?></td>
                    <td>
                      <a class="btn btn-success" href="<?php echo e(route($routeName.'.cities.edit', ['id'=>$city->id,'back_url'=>$BackUrl])); ?>" title="Edit"><i class="fas fa-edit"></i></a>
                      <a href="<?php echo e(route($routeName.'.cities.delete', ['id'=>$city->id,'back_url'=>$BackUrl])); ?>" title="Delete" class="btn btn-danger" onclick="return confirm('Are You Want To Delete ?')"><i class="fas fa-trash"></i></a>
                    </td>

                  </tr>

                <?php }}?>

              </tbody>
            </table>
            <?php echo e($cities->appends(request()->input())->links('admin.pagination')); ?>


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

<?php /**PATH /var/www/html/makesecurepro/resources/views/admin/cities/index.blade.php ENDPATH**/ ?>