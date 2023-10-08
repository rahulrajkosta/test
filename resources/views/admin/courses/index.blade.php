@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 
$search = isset($_GET['search']) ? $_GET['search'] :'';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] :'';
?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Courses</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.courses.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Course</a>
        </ol>
      </div>

    </div>
  </div>
</div>


<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-body">
          <form action="" method="get">
            <div class="row">
            <div class="col-md-4">
              <div class="input-group">
               <select class="form-control" name="category_id">
                <option value="" selected="" disabled="">Select Category</option>
                <?php if(!empty($categories)){
                  foreach ($categories as $cat){?>
                    <option value="{{$cat->id}}" <?php if($category_id == $cat->id) echo "selected"?>>{{$cat->category_name}}</option>


                  <?php }}?>
              </select>
            </div>
          </div>

           <div class="col-md-4">
              <div class="input-group">
               <input type="text" class="form-control" name="search" value="{{$search}}" placeholder="Enter Course Name To Search">

            </div>
          </div>

          <div class="col-md-4">
            <div class="input-group">
             <button class="btn btn-success" type="submit">Search</button>
             &nbsp;&nbsp;&nbsp;
             <a class="btn btn-danger" href="">Reset</a>
          </div>
        </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>










<!-- end page title --> 
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Courses</h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>

                <th>S.No.</th>
                <th>Image</th>
                <th>Category</th>                   
                <th>Course Name</th>                                                  
                <th>Duration(Im Months)</th>
                <th>Amount</th>

                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($courses)){

                $i = 1;
                foreach($courses as $cat){
                  ?>
                  <tr>
                    <td>{{$i++}}</td>
                    <td>

                      <?php

                      $image = isset($cat->image) ? $cat->image : '';
                      $storage = Storage::disk('public');
                      $path = 'courses/';

                      if(!empty($image))
                      {
                        if($storage->exists($path.$image)){
                          ?>

                          <a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" style='width:50px;heith:50px;'></a>


                        <?php } }?>

                      </td>
                      <?php
                      $category =  App\Category::select('id','category_name')->where('id',$cat->category_id)->first();
                      ?>
                      <td>{{$category->category_name}}</td>
                      <td>{{$cat->course_name}}</td>
                      <td>{{$cat->duration}}</td>
                      
                      <td>{{$cat->full_amount}}</td>

                      <td>
                        <select id='change_course_status{{$cat->id}}' onchange='change_course_status({{$cat->id}})' class="form-control">
                          <option value='1' <?php if($cat->status ==1)echo "selected";?> >Active</option>
                          <option value='0' <?php if($cat->status ==0)echo "selected";?>>InActive</option>
                        </select> 


                      </td>

                      <td>

                        <a class="btn btn-success" href="{{ route($routeName.'.courses.edit', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                        <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.courses.delete', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                        <!-- <a class="btn btn-danger" href="{{ route($routeName.'.courses.payment', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a> -->


                      </td>
                    </tr>
                  <?php }}?>
                </tbody>


              </table>

              {{ $courses->appends(request()->input())->links('admin.pagination') }}
            </div>
          </div> <!-- end card body-->
        </div> <!-- end card -->
      </div><!-- end col-->
    </div>


    @include('admin.common.footer')

    <script>
     function change_course_status(id){
      var status = $('#change_course_status'+id).val();


      var _token = '{{ csrf_token() }}';

      $.ajax({
        url: "{{ route($routeName.'.courses.change_course_status') }}",
        type: "POST",
        data: {id:id, status:status},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){
          if(resp.success){
            alert(resp.message);
          }else{
            alert(resp.message);

          }
        }
      });


    }



  </script>
