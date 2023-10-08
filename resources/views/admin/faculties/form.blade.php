@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$id = isset($faculties->id) ? $faculties->id : '';
$name = isset($faculties->name) ? $faculties->name : '';
$email = isset($faculties->email) ? $faculties->email : '';
$phone = isset($faculties->phone) ? $faculties->phone : '';
$password = isset($faculties->password) ? $faculties->password : '';
$image = isset($faculties->image) ? $faculties->image : '';
$education = isset($faculties->education) ? $faculties->education : '';
$total_exp = isset($faculties->total_exp) ? $faculties->total_exp : '';
$speciality = isset($faculties->speciality) ? $faculties->speciality : '';
$status = isset($faculties->status) ? $faculties->status : '1';
$is_approve = isset($faculties->is_approve) ? $faculties->is_approve : '0';
$about = isset($faculties->about) ? $faculties->about : '';
$username = isset($faculties->username) ? $faculties->username : '';



?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">{{ $page_heading }}</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
          <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
      </ol>
  </div>

</div>
</div>
</div>
@include('snippets.errors')
@include('snippets.flash')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ $page_heading }}</h4>

                <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{$id}}">

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Name</label>
                        <input type="text" class="form-control mb-3" name="name" id="exampleInputName"  value="{{ old('name',$name) }}" placeholder="Enter Name" aria-label="default input example">
                    </div>

                    <div class="mb-3">
                        <label for="fullname" class="form-label">UserName</label>
                        <input type="text" class="form-control mb-3" name="username" id="exampleInputName"  value="{{ old('username',$username) }}" placeholder="Enter UserName For Login" aria-label="default input example">
                    </div>


                    <div class="mb-3">
                        <label for="fullname" class="form-label">Contact No.</label>
                        <input type="number" class="form-control mb-3" name="phone" id="exampleInputNumber"  value="{{ old('phone',$phone) }}" placeholder="Enter Name" aria-label="default input example">
                    </div>

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Email address</label>
                        <input type="email" class="form-control mb-3" name="email" id="email"  value="{{ old('email',$email) }}" placeholder="Enter Email" aria-label="default input example"></div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Password</label>
                            <input type="password" class="form-control mb-3" name="password" id="password"  value="" placeholder="Enter Password" aria-label="default input example">
                        </div>


                        <div class="mb-3">
                            <label for="fullname" class="form-label">Qualification</label>
                            <input type="text" class="form-control mb-3" name="education" id="education"  value="{{ old('education',$education) }}" placeholder="Enter Qualification" aria-label="default input example">
                        </div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Total Experience (in years)</label>
                            <input type="number" class="form-control mb-3" name="total_exp" id="total_exp"  value="{{ old('total_exp',$total_exp) }}" placeholder="Total Experience (in years)" aria-label="default input example">
                        </div>
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Specialization</label>
                            <input type="text" class="form-control mb-3" name="speciality" id="speciality"  value="{{ old('speciality',$speciality) }}" placeholder="Enter Specialization" aria-label="default input example">
                        </div>
                        <div class="mb-3">
                            <label for="fullname" class="form-label">About</label>
                            <textarea name="about" class="form-control" id="description">{{old('about',$about)}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Upload Image</label>
                            <input type="file" class="form-control mb-3" name="image" id="image"   aria-label="default input example">
                        </div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Status</label>
                            <br>
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                        </div>
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Approval</label>
                            <br>
                            Approved: <input type="radio" name="is_approve" value="1" <?php echo ($is_approve == '1')?'checked':''; ?> >
                            &nbsp;
                            Not Approved: <input type="radio" name="is_approve" value="0" <?php echo ( strlen($is_approve) > 0 && $is_approve == '0')?'checked':''; ?> >

                        </div>


                        <div>
                            <input type="submit" class="btn btn-success" value="Submit">
                        </div>


                    </form>
                </div>
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>

</div>
</div>
</div>


@include('admin.common.footer')


