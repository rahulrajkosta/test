@include('admin.common.header')

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
?>

<div class="app-content content">
	<div class="content-wrapper">
		<div class="content-wrapper-before"></div>
		<div class="content-header row">
			<div class="content-header-left col-md-4 col-12 mb-2">
				<h3 class="content-header-title">Coupons History - {{$user->business_name??''}}</h3>
			</div>
			<div class="content-header-right col-md-8 col-12">
				<div class="breadcrumbs-top float-md-right">
					<div class="breadcrumb-wrapper mr-1">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
							</li>
							<li class="breadcrumb-item active">Coupons History - {{$user->business_name??''}}
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content-body">
			@include('snippets.errors')
			@include('snippets.flash')
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Coupons History - {{$user->business_name??''}}</h4>
							<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
							<div class="heading-elements">
								<ul class="list-inline mb-0">
									<li>
										<?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
										<a href="{{ url($back_url)}}" class="btn btn-info btn-sm" >Back</a><?php } ?>
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
											<th>Date</th>
											<th>Count</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;
										if(!empty($transactions)){
											foreach($transactions as $trans){
												?>
												<tr>
													<td>{{$i++}}</td>
													<td>{{$trans->date??''}}</td>
													<td><a href="{{route($routeName.'.'.$slug.'.view_coupons', ['id'=>$user_id,'date'=>$trans->date,'type'=>$trans->type,'back_url'=>$BackUrl])}}">{{$trans->no_of_coupons??0}}</a></td>
												</tr>
											<?php }}?>
										</tbody>
									</table>
										{{ $transactions->appends(request()->input())->links('admin.pagination') }}

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




	@include('admin.common.footer')
