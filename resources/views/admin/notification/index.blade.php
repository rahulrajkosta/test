@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Notification</h3>
    </div>
    <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Notification
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
            <h4 class="card-title">Notification</h4>
            <a class="heading-elements-toggle">
              <i class="la la-ellipsis-v font-medium-3"></i>
          </a>
          <div class="heading-elements">
              <ul class="list-inline mb-0">

              </ul>
          </div>
      </div>
      @include('snippets.errors')
      @include('snippets.flash')

      <div class="card-content collapse show">
          <div class="card-body">
            <form class="card-body" action="{{route('admin.notifications.send_users')}}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

              {{ csrf_field() }}

           <!--    <div class="col-md-6 mb-3">
                  <label for="fullname" class="form-label">Choose User</label>
                  <select class="form-control select2" name="user_id">
                    <option selected disabled value="">Select User</option>
                    <option value="0">All</option> -->

                    <?php 
                    // \App\User::latest('id')
                    // ->select('id', 'name')
                    // ->where('status',1)
                    // ->where('role_id',2)
                    // ->where('is_delete',0)
                    // ->chunk(50, function($users) {
                    //     foreach ($users as $user) {?>
                            <!-- <option value=""></option> -->
                        <?php // }  });   ?>
                <!-- </select>
            </div> -->
            <div class="col-md-6 mb-3">
              <label for="fullname" class="form-label">Enter Title</label>
              <input type="text" name="title1" class="form-control" value="{{old('title1')}}" placeholder="Enter Title">
          </div>

          <div class="col-md-6 mb-3">
           <label for="fullname" class="form-label">Enter Description</label>
           <textarea name="text1" class="form-control" placeholder="Enter Description">{{old('text1')}}</textarea>
       </div>


      <!--  <div class="col-md-6 mb-3">
           <label for="fullname" class="form-label">Image</label>
           <input type="file" name="image1" class="form-control">
       </div> -->


   <button class="btn btn-primary" type="submit">Send </button>

   </div>

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


@include('admin.common.footer')

