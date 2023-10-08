<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';


?>


<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">State List</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
              </li>
              <li class="breadcrumb-item active">State List
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
              <h4 class="card-title">State List</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                    <a href="<?php echo e(route($routeName.'.states.add', ['back_url' => $BackUrl])); ?>" class="btn btn-info btn-sm" style='float: right;'>Create State</a>
                  </li>
                </ul>
              </div>
            </div>

    
            <div class="card-content collapse show">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                  <th scope="col">#ID</th>
                     <th scope="col">Country</th>
                     <th scope="col">Name</th>
                     <th scope="col">Status</th>
                     <th scope="col">Date Created</th>
                     <th scope="col">Action</th>

                    </tr>
                  </thead>
                  <tbody>
                  <?php if(!empty($states) && $states->count() > 0){
                    $i = 1;   


                    foreach ($states as $state){

                       $country = $state->country->name??'';

                      

                      ?>
                      <tr>
                        <td><?php echo e($i++); ?></td>
                        <td><?php echo e($country); ?></td>
                        <td><?php echo e($state->name); ?></td>
                        <td><?php  echo ($state->status==1)?'Active':'Inactive';  ?></td>
                        <td><?php echo e(date('d M Y',strtotime($state->created_at))); ?></td>

                        <td>
                          <a class="btn btn-success" href="<?php echo e(route($routeName.'.states.edit', ['id'=>$state->id,'back_url'=>$BackUrl])); ?>" title="Edit"><i class="fas fa-edit"></i></a>
                          <a href="<?php echo e(route($routeName.'.states.delete', ['id'=>$state->id,'back_url'=>$BackUrl])); ?>" onclick="return confirm('Are You Want To delete?')" title="Delete" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                        
                      </tr>

                    <?php }}?>

                    </tbody>
                  </table>
                    <?php echo e($states->appends(request()->input())->links('admin.pagination')); ?>


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
<?php /**PATH /var/www/html/makesecurepro/resources/views/admin/states/index.blade.php ENDPATH**/ ?>