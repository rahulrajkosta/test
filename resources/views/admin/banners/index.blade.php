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
      <div class="content-header-left col-md-4 col-12 my-2">
        <h3 class="content-header-title">Banners</h3>
      </div>
      <div class="content-header-right col-md-8 col-12 my-2">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Banners
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
              <h4 class="card-title">Banners</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>

                   <a href="{{ route($ADMIN_ROUTE_NAME.'.banners.add').'?back_url='.$BackUrl }}" class="btn btn-info btn-sm" style='float: right;'>Add Banner</a>
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
                    <th scope="col">Image</th>
                    <th scope="col">Type</th>                                                 

                    <th scope="col">Link</th>                     
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($banners)){

                    $i = 1;
                    foreach($banners as $cat){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>

                          <?php

                          $image = isset($cat->image) ? $cat->image : '';
                          $storage = Storage::disk('public');
                          $path = 'banners';

                          if(!empty($image) && $cat->type == 'image')
                          {
                            ?>

                            <a href="{{ url('storage/app/public/banners/'.$image) }}" target='_blank'><img src="{{ url('storage/app/public/banners/'.$image) }}" style='width:100px;heith:100px;'></a>


                          <?php } else{?>
                            <video width="400" controls>
                              <source src="{{url('storage/app/public/banners/'.$cat->image)}}" type="video/mp4">

                                </video>

                              <?php }?>

                              <td>{{$cat->type}}</td>  
                              <td>{{$cat->link}}</td>                       

                              <td>
                                <select id='change_banner_status{{$cat->id}}' onchange='change_banner_status({{$cat->id}})' class="form-control">
                                  <option value='1' <?php if($cat->status ==1)echo "selected";?> >Active</option>
                                  <option value='0' <?php if($cat->status ==0)echo "selected";?>>InActive</option>
                                </select> 


                              </td>

                              <td>

                                <a class="btn btn-success" href="{{ route($routeName.'.banners.edit', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                                <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.banners.delete', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                              </td>
                            </tr>
                          <?php }}?>
                        </tbody>
                        {{ $banners->appends(request()->input())->links('admin.pagination') }}
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
       function change_banner_status(id){
        var status = $('#change_banner_status'+id).val();


        var _token = '{{ csrf_token() }}';

        $.ajax({
          url: "{{ route($routeName.'.banners.change_banner_status') }}",
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
