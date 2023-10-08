@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'category/';
// $roleId = Auth::guard('admin')->user()->role_id; 
$search = $_GET['search']??'';
?>


<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Telecaller Remarks</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Telecaller Remarks
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
            <h4 class="card-title">Telecaller Remarks</h4>
            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
              <ul class="list-inline mb-0">
                <li>
                   <a href="{{ route($routeName.'.telecaller.export', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-file" aria-hidden="true"></i>  Export</a>
                </li>
              </ul>
            </div>
          </div>

          <div class="card-content collapse show">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">UserName</th>
                    <th scope="col">UniqueId</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Role</th>
                    <th scope="col">Date Created</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($admins)){

                    $i = 1;
                    foreach($admins as $adm){

                      $admin = \App\Admin::where('id',$adm->admin_id)->first();
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$admin->name ?? ''}}</td>
                        <td>{{$admin->username ?? ''}}</td>
                        <td>{{$admin->unique_id ?? ''}}</td>
                        <td>{{$admin->email ?? ''}}</td>
                        <td>{{$admin->phone ?? ''}}</td>
                        <td>{{CustomHelper::getRoleName($admin->role_id??'')}}
                        </td>
                        <td>{{date('d M Y h:i A',strtotime($adm->created_at))}}</td>
                        <td>
                            {!!$adm->remarks!!}
                        </td>
                      </tr>



                    <?php }}?>
                  </tbody>
                </table>
                <?php if(!empty($admins)){?>
                  {{ $admins->appends(request()->input())->links('admin.pagination') }}
                <?php }?>
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

  function change_admins_status(admin_id){
    var status = $('#change_admins_status'+admin_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.admins.change_admins_status') }}",
      type: "POST",
      data: {admin_id:admin_id, status:status},
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


  function change_admins_approve(admin_id){
    var approve = $('#change_admins_approve'+admin_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.admins.change_admins_approve') }}",
      type: "POST",
      data: {admin_id:admin_id, approve:approve},
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

  function change_admins_role(admin_id){
    var role_id = $('#change_admins_role'+admin_id).val();

    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.admins.change_admins_role') }}",
      type: "POST",
      data: {admin_id:admin_id, role_id:role_id},
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