@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'category/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>


<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">My Coupons</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">My Coupons
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
              <h4 class="card-title">My Coupons</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                 
               </ul>
             </div>
           </div>

           <div class="card-content collapse show">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                   <th>S.No.</th>
                   <th>Coupon ID</th>
                   <th>Parent</th>
                   <th>Invoice No</th>
                   <th>Date</th>
                   <th>Time</th>
                 </tr>
               </thead>
               <tbody>
                 <?php if(!empty($coupons)){

                  $i = 1;
                  foreach($coupons as $coupon){
                    ?>
                    <tr>
                      <td>{{$i++}}</td>
                      <td>{{$coupon->couponID ?? ''}}</td>
                      <td>
                        <?php 
                        $user = CustomHelper::getUserDetails($coupon->parent_id);
                        ?>
                        <p><strong>Business Name :</strong> {{$user->business_name??''}}</p>
                        <p><strong>Unique ID :</strong> {{$user->unique_id??''}}</p>
                        <p><strong>Role : </strong>{{CustomHelper::getRoleName($user->role_id)}}</p>

                      </td>
                      <td>{{$coupon->invoice_no ?? ''}}</td>
                      <td>{{date('d M Y',strtotime($coupon->date)) ?? ''}}</td>
                      <td>{{date('h:i A',strtotime($coupon->time)) ?? ''}}</td>
                    </tr>
                  <?php }}?>
                </tbody>
              </table>
              <?php if(!empty($coupons)){?>
                {{ $coupons->appends(request()->input())->links('admin.pagination') }}
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






@include('admin.common.footer')

