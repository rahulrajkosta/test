@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();

$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>




<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Videos</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Videos
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
              <h4 class="card-title">Videos</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>

                   <a href="{{ route($ADMIN_ROUTE_NAME.'.videos.add').'?back_url='.$BackUrl }}" class="btn btn-info btn-sm" style='float: right;'>Add Videos</a>
                 </li>
               </ul>
             </div>
           </div>
           <div class="card-content collapse show">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">S.No.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Video</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($videos)){

                    $i = 1;
                    foreach($videos as $vid){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                      

                              <td>{{$vid->title??''}}</td>  
                              <td>{{$vid->video_id??''}}</td>                       

                              <td>
                                <select id='change_video_status{{$vid->id}}' onchange='change_video_status({{$vid->id}})' class="form-control">
                                  <option value='1' <?php if($vid->status ==1)echo "selected";?> >Active</option>
                                  <option value='0' <?php if($vid->status ==0)echo "selected";?>>InActive</option>
                                </select> 


                              </td>

                              <td>

                                <a class="btn btn-success" href="{{ route($routeName.'.videos.edit', $vid->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                                <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.videos.delete', $vid->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                              </td>
                            </tr>
                          <?php }}?>
                        </tbody>
                        {{ $videos->appends(request()->input())->links('admin.pagination') }}
                      </table>

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

      <script>
       function change_video_status(id){
        var status = $('#change_video_status'+id).val();


        var _token = '{{ csrf_token() }}';

        $.ajax({
          url: "{{ route($routeName.'.videos.change_video_status') }}",
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
