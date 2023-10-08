@include('admin.common.header')

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
$state_ids = [];
$user_role_id =Auth::guard('admin')->user()->role_id??'';
                $user_id_login =Auth::guard('admin')->user()->id??'';

if($user_role_id == 7){
  $admin_data = \App\Admin::where('id',$user_id_login)->first();
  $states= $admin_data->states??'';
  if(!empty($states)){
    $states = explode(",", $states);
    foreach($states as $state){
      $state_ids[] = $state??'';
    }
  }

}

?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">{{ $page_Heading }}</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">{{ $page_Heading }}
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
                <h4 class="card-title">{{ $page_Heading }}</h4>
                <a class="heading-elements-toggle">
                  <i class="la la-ellipsis-v font-medium-3"></i>
                </a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li>
                     <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                     <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
                   </li>
                 </ul>
               </div>
             </div>
             @include('snippets.errors')
             @include('snippets.flash')

             @if(CustomHelper::isAllowedSection('allocation_admin' , 'list'))

             <div class="row">
              <div class="col-md-12">
                <div class="card" style="">
                  <div class="card-header">
                    <label class="card-title danger" for="inputDanger"><strong>Allocation coupans report Admin</strong></label>
                  </div>
                  <div class="card-block">
                    <div class="card-body">
                      <form action="" method="post">
                        @csrf
                        <input type="hidden" name="type" value="all_coupon_allocation">
                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                           <label>Start Date</label>
                           <input type="date" class="form-control" name="start_date">
                         </div>

                         <div class="col-md-3 mb-3">
                          <label>End Date</label>
                          <input type="date" class="form-control" name="end_date">
                        </div>

                        <div class="col-md-3 mb-3">
                         <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Export</button>
                       </div>
                     </div>
                   </form>
                 </div>
               </div>
             </div>
           </div>
         </div>




         <div class="row">
              <div class="col-md-12">
                <div class="card" style="">
                  <div class="card-header">
                    <label class="card-title danger" for="inputDanger"><strong>User Data</strong></label>
                  </div>
                  <div class="card-block">
                    <div class="card-body">
                      <form action="" method="post">
                        @csrf
                        <input type="hidden" name="type" value="user_export">
                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                           <label>Start Date</label>
                           <input type="date" class="form-control" name="start_date">
                         </div>

                         <div class="col-md-3 mb-3">
                          <label>End Date</label>
                          <input type="date" class="form-control" name="end_date">
                        </div>

                        <div class="col-md-3 mb-3">
                         <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Export</button>
                       </div>
                     </div>
                   </form>
                 </div>
               </div>
             </div>
           </div>
         </div>
         @endif


         <?php 
         $roles = \App\Roles::where('parent_id','!=',0)->get();

         if(!empty($roles)){
          foreach($roles as $role){
           ?>
           @if(CustomHelper::isAllowedSection('allocation_'.$role->slug , 'list'))

           <div class="row">
            <div class="col-md-12">
              <div class="card" style="">
                <div class="card-header">
                  <label class="card-title danger" for="inputDanger"><strong>Allocation Coupans Report {{$role->name??''}}</strong></label>
                </div>
                <div class="card-block">
                  <div class="card-body">
                    <form action="" method="post">
                      @csrf
                      <input type="hidden" name="role" value="{{$role->id}}">
                      <input type="hidden" name="type" value="role_coupon_allocation">
                      <div class="form-row">
                        <div class="col-md-3 mb-3">
                         <label>Choose User Type </label>
                         <select class="form-control select2" name="user_id">
                           <option value="" selected>Select User Type</option>
                           <?php 
                           $all_users = \App\Admin::latest('id')->select('id', 'business_name', 'unique_id','name')->where('role_id',$role->id);

                           if($user_role_id == 7){
                              $all_users->whereIn('state_id',$state_ids);
                           }

                           $all_users->chunk(50, function($users) use (&$user_id_login,&$user_role_id) {
                            foreach ($users as $user) {

                            $business_name = $user->business_name??'';
                            if(empty($business_name)){
                              $business_name = $user->name??'';

                            }
                            if($user_role_id !=0){
                              if($user_id_login == $user->id){
                              ?>
                              <option value="{{$user->id??''}}">{{$business_name??''}} - {{$user->unique_id??''}}</option>


                            <?php }}else{?>
                              <option value="{{$user->id??''}}">{{$business_name??''}} - {{$user->unique_id??''}}</option>

                            <?php }?>
                            <?php  }
                          });?>
                        </select>
                      </div>


                      <div class="col-md-3 mb-3">
                       <label>Start Date</label>
                       <input type="date" class="form-control" name="start_date">
                     </div>

                     <div class="col-md-3 mb-3">
                      <label>End Date</label>
                      <input type="date" class="form-control" name="end_date">
                    </div>

                    <div class="col-md-3 mb-3">
                     <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Export</button>
                   </div>


                 </div>

               </form>
             </div>
           </div>
         </div>
       </div>
     </div>
     @endif
   <?php }}?>
   @if(CustomHelper::isAllowedSection('contact_info' , 'list'))

   <div class="row">
    <div class="col-md-12">
      <div class="card" style="">
        <div class="card-header">
          <label class="card-title danger" for="inputDanger"><strong>Contact information Party wise</strong></label>
        </div>
        <div class="card-block">
          <div class="card-body">
            <form action="" method="post">
              @csrf
              <input type="hidden" name="type" value="role_info">
              <div class="form-row">
               <div class="col-md-3 mb-3">

                 <label >Select User Type</label>

                 <select class="form-control select2" name="role">
                  <option value="" selected disabled>Select User Type</option>
                  <?php 
                  $roles = \App\Roles::where('parent_id','!=',0)->get();
                  if(!empty($roles)){
                    foreach($roles as $role){
                     ?>
                     <option value="{{$role->id}}">{{$role->name}}</option>

                   <?php }}?>
                 </select>
               </div>


               <div class="col-md-3 mb-3">
                 <label>State</label>
                 <select class="form-control select2" name="state_id" id="state_id">
                  <option value="" selected>Select State</option>
                  <?php 
                  $states = \App\State::get();
                  if(!empty($states)){
                    foreach($states as $state){
                      ?>
                      <option value="{{$state->id}}">{{$state->name}}</option>

                    <?php }}?>
                  </select>
                </div>

                <div class="col-md-3 mb-3">
                  <label>City</label>
                  <select class ="form-control select2" name="city_id" id="city_id">
                    <option value="" selected>Select City</option>

                  </select>
                </div>

                <div class="col-md-3 mb-3">
                 <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Export</button>
               </div>
             </div>
           </form>
         </div>
       </div>
     </div>
   </div>
 </div>
 @endif

 @if(CustomHelper::isAllowedSection('coupon_closing' , 'list'))


 <div class="row">
  <div class="col-md-12">
    <div class="card" style="">
      <div class="card-header">
        <label class="card-title danger" for="inputDanger"><strong>Coupon Closing Report</strong></label>
      </div>
      <div class="card-block">
        <div class="card-body">
          <form action="" method="post">
            @csrf
            <input type="hidden" name="type" value="role_closing_coupon">
            <div class="form-row">
              <div class="col-md-3 mb-3">
               <label>Start Date</label>
               <select class="form-control select2" name="role">
                <option value="" selected disabled>Select User Type</option>
                <?php 
                $roles = \App\Roles::where('parent_id','!=',0)->get();
                if(!empty($roles)){
                  foreach($roles as $role){?>
                   <option value="{{$role->id}}">{{$role->name}}</option>
                 <?php }}?>
               </select>
             </div>


             <div class="col-md-3 mb-3">
               <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Export</button>
             </div>
           </div>
         </form>
       </div>
     </div>
   </div>
 </div>
</div>
@endif















</div>
</div>
</div>
</section>

</div>
</div>
</div>


@include('admin.common.footer')

