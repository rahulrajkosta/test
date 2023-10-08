@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 
$search = isset($search) ? $search :'';
$role_id = $_GET['role_id']??'';
$state_id = $_GET['state_id']??'';
$city_id = $_GET['city_id']??'';

?>




<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 my-2">
        <h3 class="content-header-title">Super Distributor</h3>
      </div>
      <div class="content-header-right col-md-8 col-12 my-2">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Super Distributor
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
              <h4 class="card-title">Super Distributor</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                    <a href="{{ route($routeName.'.superdistributor.export', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-file-export" aria-hidden="true"></i>Export</a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="{{ route($routeName.'.superdistributor.add', ['back_url' => $BackUrl]) }}" class="btn btn-info btn-sm" style='float: right;'>Add Super Distributor</a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="row d-none">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="">

                      <form>
                        <div class="row">
                          <div class="col-3">
                            <label class="form-label">User Type</label>
                            <div class="input-group">
                              <select class="form-control" name="role_id">
                                <option value="">Select User Type</option>
                                <option value="1" <?php if($role_id == 1){echo "selected";}?>>User</option>
                                <option value="2" <?php if($role_id == 2){echo "selected";}?>>Seller</option>
                              </select>

                            </div>
                          </div>
                          <div class="col-3">
                            <label class="form-label">Search By Name,Email,Phone</label>
                            <div class="input-group">
                              <input type="text" name="search" value="{{old('search',$search)}}" class="form-control" placeholder="Search...." aria-label="Recipient's username">

                            </div>
                          </div>

                          <div class="col-3">
                            <label class="form-label">Search By State</label>
                            <select class="form-control select2" name="state_id" id="state_id">
                              <option value="" selected disabled>Select State Name</option>
                              <?php 
                              $states = \App\State::get();
                              if(!empty($states)){
                                foreach($states as $state){?>
                                    <option value="{{$state->id}}" <?php if($state_id == $state->id) echo 'selected'; ?>>{{$state->name}}</option>
                                  <?php }}?>
                                </select>
                              </div>

                              <div class="col-3">
                                <label class="form-label">Search By City</label>
                                 <select class="form-control select2" name="city_id" id="city_id">
                                  <option value="" selected disabled>Select City Name</option>
                                </select>
                              </div>

                              <br>
                              <br>
                              <br>

                              <div class="col-3">

                                <div class="input-group">
                                  <button style="margin-top:20px;" class="btn input-group-text btn-dark waves-effect waves-light" type="submit">Search</button>
                                </div>
                              </div>
                            </div>

                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-content collapse show">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>

                          <th>#ID</th>
                          <th>Unique ID </th>
                          <th>Super Distributor Details</th>
                          <th>Other Details</th>
                          <th>Locality Details</th>
                          <th>Coupons</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($users)){
                          foreach ($users as $user){
                            ?>
                            <tr>
                             <td>{{$user->id}}</td>
                             <td>{{$user->name ?? ''}}<br>{{$user->email ?? ''}}<br>{{$user->phone ?? ''}}<br>{{$user->referral_code ?? ''}}</td>
                             <td>
                              <select id='change_user_role{{$user->id}}' onchange='change_user_role({{$user->id}})' class="form-control">
                                <option value='1' <?php if($user->role_id ==1)echo "selected";?> >User</option>
                                <option value='2' <?php if($user->role_id ==2)echo "selected";?>>Seller</option>
                              </select> 
                            </td>
                            <td>{{$user->getState->name ?? ''}} <br>{{$user->getCity->name ?? ''}}</td>
                            <td>{{$user->getParent->name ?? ''}}<br>{{$user->getParent->referral_code ?? ''}}</td>
                            <td>{{$user->commission??''}}</td>
                              <td>  
                               <div class="d-flex">
                                 <a class="btn btn-success" title="Edit" href="{{ route($routeName.'.superdistributor.edit', $user->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger" title="Delete" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.superdistributor.delete', $user->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>
                                 
                               </div>
                              

                              </td>
                            </tr>

                          <?php }}?>
                        </tbody>
                        {{ $users->appends(request()->input())->links('admin.pagination') }}
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
        function change_user_role(id){
          var role_id = $('#change_user_role'+id).val();
          var _token = '{{ csrf_token() }}';
          $.ajax({
            url: "{{ route($routeName.'.user.change_user_role') }}",
            type: "POST",
            data: {id:id, role_id:role_id},
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
