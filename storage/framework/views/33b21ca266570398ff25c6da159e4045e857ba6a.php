<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 
$search = isset($search) ? $search :'';
$role_id = $_GET['role_id']??'';
$state_id = $_GET['state_id']??'';
$city_id = $_GET['city_id']??'';
$parent_id = $_GET['parent_id']??'';

$parent_user_id = Auth::guard('admin')->user()->id;



$role_id_admin = Auth::guard('admin')->user()->role_id;
if($role_id_admin == 0){
	$roles = \App\Roles::where('parent_id','>=',$role_id_admin)->where('parent_id','!=',0)->get();
}elseif($role_id_admin == 7){
	$roles = \App\Roles::where('parent_id','>=',0)->where('parent_id','!=',0)->get();
}else{
	$roles = \App\Roles::where('parent_id',$role_id_admin)->get();

}


?>

<div class="app-content content">
	<div class="content-wrapper">
		<div class="content-wrapper-before"></div>
		<div class="content-header row">
			<div class="content-header-left col-md-4 col-12 mb-2">
				<h3 class="content-header-title">Coupons - <?php echo e($user->business_name??''); ?></h3>
			</div>
			<div class="content-header-right col-md-8 col-12">
				<div class="breadcrumbs-top float-md-right">
					<div class="breadcrumb-wrapper mr-1">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo e(url('/admin')); ?>">Home</a>
							</li>
							<li class="breadcrumb-item active">Coupons - <?php echo e($user->business_name??''); ?>

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
							<h4 class="card-title">Coupons - <?php echo e($user->business_name??''); ?></h4>
							<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
							<div class="heading-elements">
								<ul class="list-inline mb-0">
									<li>
										<?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
										<a href="<?php echo e(url($back_url)); ?>" class="btn btn-info btn-sm" >Back</a><?php } ?>
									</li>
								</ul>
							</div>
						</div>

						<div class="card-content collapse show">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>ID</th>
											<th>Coupon ID</th>
											<?php 
											if(!empty($roles)){
												foreach($roles as $role){?>

													<th><?php echo e($role->name??''); ?></th>
												<?php }}?>
												<th>Used/UnUsed</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if(!empty($coupons)){
												$i = 1;
												foreach($coupons as $coup){
													?>
													<tr>
														<td><?php echo e($i++); ?></td>
														<td><?php echo e($coup->couponID??''); ?></td>
														<?php 
														if(!empty($roles)){
															foreach($roles as $role){

																$coupon_data = \App\AssignCoupon::where('coupon_id',$coup->id)->where('child_role_id',$role->id)->first();
																$user = [];
																if(!empty($coupon_data)){
																	$user = CustomHelper::getUserDetails($coupon_data->child_id);
																	
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
																		<p><strong>Role : </strong><?php echo e(CustomHelper::getRoleName($user->role_id)); ?></p>
																	<?php }?>
																</td>
															<?php }}?>
															<td><?php if($coup->is_used == 1){echo "Used";}else{echo "UnUsed";}?></td>
															<td></td>
														</tr>
													<?php }}?>
												</tbody>
											</table>
											<?php if(!empty($coupons)){?>
												<?php echo e($coupons->appends(request()->input())->links('admin.pagination')); ?>


											<?php }?>

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
<?php /**PATH D:\after_emilocket_\makesecurepro\resources\views/admin/party_users/view_coupons.blade.php ENDPATH**/ ?>