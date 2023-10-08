
<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');

?>
<style>
.switch {
position: relative;
display: inline-block;
width: 60px;
height: 34px;
}

.switch input { 
opacity: 0;
width: 0;
height: 0;
}

.slider {
position: absolute;
cursor: pointer;
top: 0;
left: 0;
right: 0;
bottom: 0;
background-color: #ccc;
-webkit-transition: .4s;
transition: .4s;
}

.slider:before {
position: absolute;
content: "";
height: 26px;
width: 26px;
left: 4px;
bottom: 4px;
background-color: white;
-webkit-transition: .4s;
transition: .4s;
}

input:checked + .slider {
background-color: #2196F3;
}

input:focus + .slider {
box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
-webkit-transform: translateX(26px);
-ms-transform: translateX(26px);
transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
border-radius: 34px;
}

.slider.round:before {
border-radius: 50%;
}
</style>


<div class="app-content content">
<div class="content-wrapper">
<div class="content-wrapper-before"></div>
<div class="content-header row">
<div class="content-header-left col-md-4 col-12 my-2">
<h3 class="content-header-title">Permissions</h3>
</div>
<div class="content-header-right col-md-8 col-12 my-2">
<div class="breadcrumbs-top float-md-right">
<div class="breadcrumb-wrapper mr-1">
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
	</li>
	<li class="breadcrumb-item active">Permissions
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
	<h4 class="card-title">Permissions</h4>
	<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
	<div class="heading-elements">
		<ul class="list-inline mb-0">
		</ul>
	</div>
</div>
<div class="card-content collapse show">
	<div class="card-body">
		<div class="form-row">
			<div class="col-md-12">
				<label class="form-label">Search By Name,Email,Phone</label>
				<form action="" method="get">
					<div class="input-group">
						<select class="form-control" name="role_id">
							<option value="" selected disabled>Select Role</option>
							<?php if(!empty($roles)){
								foreach($roles as $role){
									?>
									<option value="<?php echo e($role->id); ?>" <?php if($role->id == $role_id) echo "selected"?>><?php echo e($role->name); ?></option>
								<?php }}?>
							</select>
							<button class="btn input-group-text btn-dark waves-effect waves-light" type="submit">Search</button>
						</div>
					</form>
					
				</div>
				<div class="col-md-12">
					<?php if(!empty($sectionArr)){?>
						<div class="row">
							<div class="col-lg-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-md-4">
												<h4 class="mb-3 header-title">Permission For <?php echo e($singlerole->name ?? ''); ?></h4>
											</div>
											<!-- <div class="col-md-2">
												<h4 class="mb-3 header-title">List</h4>
											</div>
											<div class="col-md-2">
												<h4 class="mb-3 header-title">Add</h4>
											</div>
											<div class="col-md-2">
												<h4 class="mb-3 header-title">Update</h4>
											</div>
											<div class="col-md-2">
												<h4 class="mb-3 header-title">Delete</h4>
											</div> -->
										</div>
										
										<div class="row">

											<?php 
											foreach ($sectionArr as $key => $value) {
												$add = '';
												$edit = '';
												$list = '';
												$delete = '';

												$exist = \App\Permission::where('role_id',$role_id)->where('section',$key)->first();
												if(!empty($exist)){
													if($exist->add == 1){
														$add = 'checked';
													}
													if($exist->list == 1){
														$list = 'checked';
													}
													if($exist->edit == 1){
														$edit = 'checked';
													}
													if($exist->delete == 1){
														$delete = 'checked';
													}



												}


												?>
												<div class="col-md-4">
													<div class="mb-3">
														<h4><?php echo e($value); ?></h4>
													</div>
												</div>

												<div class="col-md-2">
													<div class="mb-3">
														<label class="switch">
															<input type="checkbox" <?php echo e($list); ?> onclick="update_permission('<?php echo e($key); ?>','<?php echo e($role_id); ?>','list',this)" id="checkboxlist<?php echo e($key); ?>">
															<span class="slider round"></span>
														</label>
													</div>
												</div>

												<!-- <div class="col-md-2">
													<div class="mb-3">
														<label class="switch">
															<input type="checkbox" <?php echo e($add); ?> onclick="update_permission('<?php echo e($key); ?>','<?php echo e($role_id); ?>','add',this)">
															<span class="slider round"></span>
														</label>
													</div>
												</div>

												<div class="col-md-2">
													<div class="mb-3">
														<label class="switch">
															<input type="checkbox" <?php echo e($edit); ?> onclick="update_permission('<?php echo e($key); ?>','<?php echo e($role_id); ?>','edit',this)">
															<span class="slider round"></span>
														</label>
													</div>
												</div>
												<div class="col-md-2">
													<div class="mb-3">
														<label class="switch">
															<input type="checkbox" <?php echo e($delete); ?> onclick="update_permission('<?php echo e($key); ?>','<?php echo e($role_id); ?>','delete',this)">
															<span class="slider round"></span>
														</label>
													</div>
												</div> -->

											<?php }?>
										</div>

										

									</div> <!-- end card-body-->
								</div> <!-- end card-->
							</div>
							<!-- end col -->

						</div>

					<?php }?>
					
				</div>




			</div>
			
			


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



<script type="text/javascript">
function update_permission(key,role_id,section,permission) {
if(permission.checked){
permission = 1;
}
else{
permission = 0;
}

var _token = '<?php echo e(csrf_token()); ?>';

$.ajax({
url: "<?php echo e(route($routeName.'.update_permission')); ?>",
type: "POST",
data: {key:key,section:section,permission:permission,role_id:role_id},
dataType:"JSON",
headers:{'X-CSRF-TOKEN': _token},
cache: false,
success: function(resp){
}
});
}
</script><?php /**PATH /var/www/html/example-app/resources/views/admin/profile/permission.blade.php ENDPATH**/ ?>