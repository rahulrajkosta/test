@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'category/';
// $roleId = Auth::guard('admin')->user()->role_id; 
$states = \App\State::orderBy('name')->get();
$superdistributors = \App\Admin::where('role_id',2)->where('is_delete',0)->get();
?>


<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Admins</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Admins
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
              <h4 class="card-title">Admins</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                   <a href="{{ route($routeName.'.admins.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Admins</a>
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
                    <th scope="col">User Details</th>
                    <th scope="col">Role</th>
                    <th scope="col">Super Distributors</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date Created</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($admins)){

                    $i = 1;
                    foreach($admins as $admin){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td><strong>Name: </strong>{{$admin->name ?? ''}} <br>
                          <strong>UserName :</strong> {{$admin->username ?? ''}}<br>
                          <strong>Email: </strong>{{$admin->email ?? ''}}
                        </td>

                        <td>
                          <select id='change_admins_role{{$admin->id}}' class="form-control" onchange='change_admins_role({{$admin->id}})'>
                            <option value="" selected disabled>Select Role</option>
                            <?php 
                            $roles = \App\Roles::where('id','!=',1)->where('status',1)->get();
                            if(!empty($roles)){
                              foreach($roles as $role){
                                ?>
                                <option value='{{$role->id}}' <?php if($admin->role_id == $role->id) echo 'selected'?>>{{$role->name}}</option>
                              <?php  }
                            }
                            ?>
                          </select>


                        </td>
                        <td>
                            <?php if($admin->role_id == 7){
                            $assign_superdist = [];
                            $all_superdist = $admin->superdist_id??'';
                            if(!empty($all_superdist)){
                              $assign_superdist = explode(",", $all_superdist);
                              if(!empty($assign_superdist)){
                                $i = 1;
                                foreach ($assign_superdist as $key) {
                                    $superdist_name = \App\Admin::where('id',$key)->first();
                                    echo $i++.".".$superdist_name->business_name??'';
                                    echo "-- ".$superdist_name->unique_id??'';
                                    echo "<br>";
                                }
                              }

                            }


                            ?>


                                <a  class="btn btn-primary" data-toggle="modal" data-target="#remarksModal{{$admin->id}}">
                                  <i class="fa fa-plus" style="color:white;"></i>
                                </a>&nbsp;&nbsp;&nbsp;
                                <div class="modal fade" id="remarksModal{{$admin->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{$admin->name??''}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <form action="{{ route($routeName.'.admins.change_admin_superdist') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                                        <div class="modal-body">
                                          <div class="row">
                                              <label>Select Super Distributors</label>
                                            <div class="col-md-12">
                                              <select  class="form-control" name="superdistributors[]" multiple>
                                                <?php if(!empty($superdistributors)){
                                                  foreach($superdistributors as $sup){?>
                                                    <option value="{{$sup->id??''}}" <?php if(in_array($sup->id,$assign_superdist)){echo "selected";}?>>{{$sup->business_name??''}} -- {{$sup->unique_id??''}}</option>
                                                  <?php }}?>
                                                </select>

                                              </div>

                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                <?php }?>
                        </td>
                        <td style="display:none;">
                          <?php if($admin->role_id == 7){
                            $assign_states = [];
                            $all_states = $admin->states??'';
                            if(!empty($all_states)){
                              $assign_states = explode(",", $all_states);
                              if(!empty($assign_states)){
                                foreach ($assign_states as $key) {
                                    $state_name = \App\State::where('id',$key)->first();
                                    echo $state_name->name??'';
                                    echo "<br>";
                                }
                              }

                            }


                            ?>


                                <a  class="btn btn-primary" data-toggle="modal" data-target="#remarksModal{{$admin->id}}">
                                  <i class="fa fa-plus" style="color:white;"></i>
                                </a>&nbsp;&nbsp;&nbsp;
                                <div class="modal fade" id="remarksModal{{$admin->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{$admin->name??''}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <form action="{{ route($routeName.'.admins.change_admin_state') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                                        <div class="modal-body">
                                          <div class="row">
                                              <label>Select States</label>
                                            <div class="col-md-12">
                                              <select  class="form-control" name="states[]" multiple>
                                                <?php if(!empty($states)){
                                                  foreach($states as $state){?>
                                                    <option value="{{$state->id??''}}" <?php if(in_array($state->id,$assign_states)){echo "selected";}?>>{{$state->name??''}}</option>
                                                  <?php }}?>
                                                </select>

                                              </div>

                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                <?php }?>
                        </td>

                          <td>
                            <select id='change_admins_status{{$admin->id}}' class="form-control" onchange='change_admins_status({{$admin->id}})'>
                              <option value='1'<?php if($admin->status == 1) echo 'selected'?>>Active</option>
                              <option value='0'<?php if($admin->status == 0) echo 'selected'?>>InActive</option>
                            </select>
                          </td>

                          <td>{{date('d M Y',strtotime($admin->created_at))}}</td>
                          <td>
                            <div class="d-flex">
                              <a class="btn btn-success" href="{{route($routeName.'.admins.edit',$admin->id.'?back_url='.$BackUrl)}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;
                              


                                <a class="btn btn-danger" href="{{route($routeName.'.admins.delete',$admin->id.'?back_url='.$BackUrl)}}"><i class="fa fa-trash"></i></a>

                              </div>
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