@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$admins_id = (isset($admins->id))?$admins->id:'';
$name = (isset($admins->name))?$admins->name:'';
$description = (isset($admins->description))?$admins->description:'';
$location = (isset($admins->location))?$admins->location:'';
$state_id = (isset($admins->state_id))?$admins->state_id:'';
$city_id = (isset($admins->city_id))?$admins->city_id:'';
$username = (isset($admins->username))?$admins->username:'';
$phone = (isset($admins->phone))?$admins->phone:'';
$address = (isset($admins->address))?$admins->address:'';
$society_id = (isset($admins->society_id))?$admins->society_id:'';
$is_approve = (isset($admins->is_approve))?$admins->is_approve:'';
$role_id = (isset($admins->role_id))?$admins->role_id:'';
$email = (isset($admins->email))?$admins->email:'';




$status = (isset($admins->status))?$admins->status:'';


$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';



?>


<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">{{ $page_heading }}</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">{{ $page_heading }}
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
                <h4 class="card-title">{{ $page_heading }}</h4>
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

             <div class="card-content collapse show">
              <div class="card-body">
                <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

                  {{ csrf_field() }}

                  <input type="hidden" id="id" value="{{$admins_id}}">
                  <div class="form-row">
                    <div class="col-md-6 mb-3">
                     <label for="userName">Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                    @include('snippets.errors_first', ['param' => 'name'])
                   </div>


                   <div class="col-md-6 mb-3">
                      <label for="userName">UserName<span class="text-danger">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $username) }}" id="username" class="form-control"  maxlength="255" placeholder="Enter Username For Login" />

                    @include('snippets.errors_first', ['param' => 'username'])
                     
                   </div>
                   

                    <div class="col-md-6 mb-3">
                     <label for="userName">Email<span class="text-danger">*</span></label>
                    <input type="text" name="email" value="{{ old('email', $email) }}" id="email" class="form-control"  maxlength="255" placeholder="Enter Email" />

                    @include('snippets.errors_first', ['param' => 'email'])
                   </div>


                    <div class="col-md-6 mb-3">
                      <label for="userName">Role<span class="text-danger">*</span></label>
                    <select name="role_id" class="form-control">
                        <option value="" selected disabled>Select Role</option>
                        <?php if(!empty($roles)){
                            foreach($roles as $role){
                                ?>
                                <option value="{{$role->id}}" <?php if($role->id == $role_id) echo "selected"?>>{{$role->name}}</option>
                            <?php }}?>
                        </select>

                        @include('snippets.errors_first', ['param' => 'role_id'])
                   </div>

                     <div class="col-md-6 mb-3">
                     <label for="userName">Phone<span class="text-danger">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $phone) }}" id="phone" class="form-control" placeholder="Enter Phone"  maxlength="255" />


                        @include('snippets.errors_first', ['param' => 'phone'])
                   </div>


                     <div class="col-md-6 mb-3">
                      <label for="userName">Address<span class="text-danger">*</span></label>
                        <input type="text" name="address" value="{{ old('address', $address) }}" id="address" class="form-control" placeholder="Enter Address"  maxlength="255" />


                        @include('snippets.errors_first', ['param' => 'phone'])
                   </div>

                     <div class="col-md-6 mb-3">
                      <label for="userName">State<span class="text-danger">*</span></label>
                        <select name="state_id" class="form-control select2" id="state_id">
                            <option value="" selected disabled>Select State</option>
                            <?php if(!empty($states)){
                                foreach($states as $state){
                                    ?>
                                    <option value="{{$state->id}}" <?php if($state->id == $state_id) echo "selected"?>>{{$state->name}}</option>
                                <?php }}?>
                            </select>

                            @include('snippets.errors_first', ['param' => 'state_id'])
                   </div>
                         <div class="col-md-6 mb-3">
                      <label for="userName">City<span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-control select2">
                                <option value="" selected disabled>Select City</option>
                                <?php if(!empty($cities)){
                                    foreach($cities as $city){
                                        ?>
                                        <option value="{{$city->id}}" <?php if($city->id == $city_id) echo "selected"?>>{{$city->name}}</option>
                                    <?php }}?>
                                </select>

                                @include('snippets.errors_first', ['param' => 'city_id'])
                   </div>



                     <div class="col-md-6 mb-3">
                      <label for="userName">Password<span class="text-danger">*</span></label>
                                <input type="password" name="password" value="" id="password" class="form-control" placeholder="Enter Password"  maxlength="255" autocomplete="off" />


                                @include('snippets.errors_first', ['param' => 'password'])
                   </div>
                 
                  

                  <div class="col-md-6 mb-3">
                   <label>Status</label>
                   <div>
                     Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                     &nbsp;
                     Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                     @include('snippets.errors_first', ['param' => 'status'])
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


@include('admin.common.footer')
<script>
    CKEDITOR.replace( 'description' );
</script>

<script type="text/javascript">
   $('#state_id').on('change', function()
   {

    var _token = '{{ csrf_token() }}';
    var state_id = $('#state_id').val();
    $.ajax({
      url: "{{ route('get_city') }}",
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