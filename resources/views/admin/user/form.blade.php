@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$user_id = isset($users->id) ? $users->id : '';
$name = isset($users->name) ? $users->name : '';
$email = isset($users->email) ? $users->email : '';
$phone = isset($users->phone) ? $users->phone : '';
$dob = isset($users->dob) ? $users->dob : '';
$gender = isset($users->gender) ? $users->gender : '';
$status = isset($users->status) ? $users->status : '';
$commission = isset($users->commission) ? $users->commission : '';

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

             <div class="card-content collapse show">
              <div class="card-body">
                <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

                  {{ csrf_field() }}

                  <input type="hidden" id="id" value="{{$user_id}}">

                  <div class="form-row">

                     <div class="col-md-6">
                       <label for="fullname" class="form-label">Name</label>
                   <input class="form-control mb-3" type="text" name="name" id="name"  value="{{old('name',$name)}}" placeholder="Name" aria-label="default input example">
                    </div>
                     <div class="col-md-6">
                      <label for="email" class="form-label">Email</label>
                <input class="form-control mb-3" type="email" name="email" id="email"  value="{{old('email',$email)}}" placeholder="Email" aria-label="default input example">
                    </div>

                     <div class="col-md-6">
                       <label for="email" class="form-label">Password</label>
                      <i class="fa fa-eye" id="togglePassword"></i>
                     <input class="form-control mb-3" type="password" name="password" id="password"  value="" placeholder="Password" aria-label="default input example">
                    </div>

                    <div class="col-md-6">
                      <label for="email" class="form-label">Commission (In %)</label>
                     <input class="form-control mb-3" type="number" name="commission" id="commission"  value="{{old('commission',$commission)}}" placeholder="Enter Commission In %" aria-label="default input example">
                    </div>

                     <div class="col-md-6">
                      <label for="fullname" class="form-label">Phone</label>
                   <input class="form-control mb-3" type="number" name="phone" value="{{old('phone',$phone)}}" id="phone"  aria-label="default input example">
                    </div>



                     <div class="col-md-6">
                       <label for="email" class="form-label">Password</label>
                      <i class="fa fa-eye" id="togglePassword"></i>
                      <label for="fullname" class="form-label">Date Of Birth</label>
                   <input class="form-control mb-3" type="date" name="dob" id="dob" value="{{old('dob',$dob)}}"  aria-label="default input example">
                    </div>




            
                    <div class="col-md-6">
                         <label for="fullname" class="form-label">Gender</label>
                       <select id="gender" name="gender" class="form-control mb-3">
                    <option value="" selected disabled>Select Gender</option>
                     <option value="male" <?php if($gender == 'male'){echo "selected";}?>>Male</option>

                           <option value="female"  <?php if($gender == 'female'){echo "selected";}?>>Female</option>
                   
                  </select>
                    </div>
                     <div class="col-md-6 ">
                      <label for="fullname" class="form-label">Upload Image</label>
                      <input class="form-control mb-3" type="file" name="image" id="image" aria-label="default input example">

                       <?php
                    if(!empty($image)){
                      if($storage->exists($path.$image)){ ?>
                        <div class=" image_box" style="display: inline-block">
                          <a href="{{ url('storage/app/public/'.$path.$image) }}" target="_blank">
                            <img src="{{ url('storage/app/public/'.$path.$image) }}" style="width:70px;">
                          </a>


                        </div>
                      <?php  }
                    }
                    ?>

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

