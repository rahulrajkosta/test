@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
 $routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
 $roleId = Auth::guard('admin')->user()->role_id; 

?>

      <div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Faculties</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.faculties.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Faculty</a>
        </ol>
      </div>

    </div>
  </div>
</div>  
      <!-- end page title --> 
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h4 class="header-title">Faculties</h4>
        <div class="table-responsive">

              <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    
                    <th>S.No.</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                     <th>Image</th>
                      <th>Education</th>
                    <th>Total Experience</th>                   
                    <th>Speciality</th>                    
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($faculties)){

                    $i = 1;
                    foreach($faculties as $faculty){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>  
                        <td>
                          <?php

                            $storage = Storage::disk('public');
                            $path = 'faculties/';
                             $html = '';
                            $image = isset($faculty->image) ? $faculty->image :'';
                            if(!empty($image)){
                              if($storage->exists($path,$image))
                              {

                          ?>

                           <a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" style='width:50px;heigth:50px;'></a>
                          <?php }}else{  ?> 

                             <a href="{{ url('public/storage/'.$path.'/defaultimg.png') }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/defaultimg.png') }}" style='width:50px;heigth:50px;'></a> 
                           <?php } ?>

                        </td>                 
                        
                        <td>{{$faculty->name}}</td>
                        <td>{{$faculty->email}}</td>
                        <td>{{$faculty->phone}}</td>
                        <td>{{$faculty->phone}}</td>
                        <td>{{$faculty->education}}</td>
                        <td>{{$faculty->total_exp}}</td>
                        <td>{{$faculty->speciality}}</td>

                        
                        <td>
                          <select id='change_faculty_status{{$faculty->id}}' onchange="change_faculty_status({{$faculty->id}})" class="form-control">
                            <option value='1' <?php if($faculty->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($faculty->status ==0)echo "selected";?>>InActive</option>
                          </select> 


                        </td>

                        <td>
                         
                          <a class="btn btn-success" href="{{ route($routeName.'.faculties.edit', $faculty->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                          <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.faculties.delete', $faculty->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>

                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>


                </table>

                {{ $faculties->appends(request()->input())->links('admin.pagination') }}
              </div>
              </div> <!-- end card body-->
            </div> <!-- end card -->
          </div><!-- end col-->
        </div>

  @include('admin.common.footer')

  <script>
    function change_faculty_status(id){
   var status = $('#change_faculty_status'+id).val();


    var _token = '{{ csrf_token() }}';

             $.ajax({
                 url: "{{ route($routeName.'.faculties.change_faculty_status') }}",
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
