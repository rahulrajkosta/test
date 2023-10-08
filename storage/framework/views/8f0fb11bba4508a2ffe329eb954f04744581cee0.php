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
        <h3 class="content-header-title">Countries</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
              </li>
              <li class="breadcrumb-item active">Countries
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
              <h4 class="card-title">Countries</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                    <a href="<?php echo e(route($routeName.'.countries.add', ['back_url' => $BackUrl])); ?>" class="btn btn-info btn-sm" style='float: right;'>Create Country</a>
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
                     <th scope="col">Name</th>
                     <th scope="col">Status</th>
                     <th scope="col">Date Created</th>
                     <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($countries) && $countries->count() > 0){
                    $i = 1;
                    foreach ($countries as $country){
                      ?>
                      <tr>
                        <td><?php echo e($i++); ?></td>
                        <td><?php echo e($country->name); ?></td>
                        <td><?php  echo ($country->status==1)?'Active':'Inactive';  ?></td>
                        <td><?php echo e(date('d M Y',strtotime($country->created_at))); ?></td>

                        <td>
                          <a class="btn btn-success" href="<?php echo e(route($routeName.'.countries.edit', ['id'=>$country->id,'back_url'=>$BackUrl])); ?>" title="Edit"><i class="fas fa-edit"></i></a>
                            <a class="btn btn-danger" href="<?php echo e(route($routeName.'.countries.delete', ['id'=>$country->id,'back_url'=>$BackUrl])); ?>" title="Delete"><i class="fas fa-trash" onclick="return confirm('Are You Want To Delete ?')"></i></a>
                        </td>
                        
                      </tr>

                    <?php }}?>

                    </tbody>
                    <?php echo e($countries->appends(request()->input())->links('admin.pagination')); ?>

                  </table>

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
<?php /**PATH /var/www/html/example-app/resources/views/admin/countries/index.blade.php ENDPATH**/ ?>