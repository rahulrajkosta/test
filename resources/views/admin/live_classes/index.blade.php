@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'live_class/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>

      <div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Live Class</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.live_class.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Live Class</a>
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
              <h4 class="header-title">Live Class</h4>
        <div class="table-responsive">

              <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Title</th>
                    <th>Image</th>

                    <th>Course Name</th>                 
                    <th>Faculty Name</th>
                    <th>Start Date & Time</th>  
                    <th>End Date & Time</th>  
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($live_classes)){

                    $i = 1;
                    foreach($live_classes as $live){
                      $course = \App\Course::where('id',$live->course_id)->first();
                      $faculty = \App\Admin::where('id',$live->faculty_id)->first();
                      $liveclass = 0;
                      $limeImg = url('public/storage/live_class/live.gif');
                      if(date('Y-m-d') >= $live->start_date && date('Y-m-d') <= $live->end_date){
                        if(date('H:i') >=$live->start_time && date('H:i') <=$live->end_time){
                          $liveclass = 1;
                        }
                      }

                      // if(date('Y-m-d') <= $live->end_date){
                      //   if(date('H:i:s') <=$live->end_time){
                      //     $liveclass = 1;
                      //   }
                      // }


                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$live->title}}
                          <?php if($liveclass == 1){
                            ?>
                          <img src="{{$limeImg}}" height="70px" width="100px">
                        <?php }?>
                        </td>

                        <td>
                          <?php 
                          $image = $live->image;
                          if(!empty($image)){
                          if($storage->exists($path.$image)){
                          ?>
                          <div class=" image_box" style="display: inline-block">
                            <a href="{{ url('public/storage/'.$path.$image) }}" target="_blank">
                                <img src="{{ url('public/storage/'.$path.'thumb/'.$image) }}" style="width:70px;">
                            </a>
                            <br>
                        </div>
                        <?php }}?>
                        </td>

                        <td>{{$course->course_name ?? ''}}</td>
                        <td>{{$faculty->name ?? ''}}</td>
                        <td>{{date('d M Y',strtotime($live->start_date))}} {{date('h:i A',strtotime($live->start_time))}}</td>
                        <td>{{date('d M Y',strtotime($live->end_date))}} {{date('h:i A',strtotime($live->end_time))}}</td>


                        <td>
                          <select id='change_liveclass_status{{$live->id}}' onchange='change_liveclass_status({{$live->id}})' class="form-control">
                            <option value='1' <?php if($live->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($live->status ==0)echo "selected";?>>InActive</option>
                          </select> 


                        </td>

                        <td>
                          <?php if($liveclass == 1){?>
                          <!-- <a class="btn btn-success" href="#"><i class="fa fa-television"></i></a>&nbsp;&nbsp;&nbsp; -->
                        <?php }?>

                          <a class="btn btn-success" href="{{ route($routeName.'.live_class.edit', $live->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                          <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.live_class.delete', $live->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>


                </table>

                {{ $live_classes->appends(request()->input())->links('admin.pagination') }}
</div>
              </div> <!-- end card body-->
            </div> <!-- end card -->
          </div><!-- end col-->
        </div>


  @include('admin.common.footer')

  <script>
    function change_liveclass_status(id){
      var status = $('#change_liveclass_status'+id).val();
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route($routeName.'.live_class.change_liveclass_status') }}",
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
