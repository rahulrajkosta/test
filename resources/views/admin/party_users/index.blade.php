@include('admin.common.header')



<?php

$BackUrl = CustomHelper::BackUrl();

$routeName = CustomHelper::getAdminRouteName();

$uri = Route::currentRouteName();
$back_url = '';
if($uri == 'admin.tsm.index'){
  $back_url = 'distributor';
}

if($uri == 'admin.seller.index'){
  $back_url = 'tsm';
}
if($uri == 'admin.distributor.index'){
  $back_url = 'superdistributor';
}



$storage = Storage::disk('public');

$path = 'influencer/thumb/';

// $roleId = Auth::guard('admin')->user()->role_id; 

$search = isset($search) ? $search :'';

$role_id = $_GET['role_id']??'';

$state_id = $_GET['state_id']??'';

$city_id = $_GET['city_id']??'';

$parent_id = $_GET['parent_id']??'';

$find_id = $_GET['find_id']??'';



$parent_user_id = Auth::guard('admin')->user()->id;

$parent_role_id = Auth::guard('admin')->user()->role_id;



$child = \App\Roles::where('parent_id',$parent_role_id)->first();



?>









<div class="app-content content">

  <div class="content-wrapper">

    <div class="content-wrapper-before"></div>

    <div class="content-header row">

      <div class="content-header-left col-md-4 col-12 my-2">

        <h3 class="content-header-title">{{$title??''}} - {{$users->total()}}</h3>

      </div>

      <div class="content-header-right col-md-8 col-12 my-2">

        <div class="breadcrumbs-top float-md-right">

          <div class="breadcrumb-wrapper mr-1">

            <ol class="breadcrumb">

              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>

              </li>

              <li class="breadcrumb-item active">{{$title??''}}

              </li>

            </ol>

          </div>

        </div>

      </div>

    </div>

    <div class="content-body">

      @include('snippets.errors')

      @include('snippets.flash')

      <div class="row">

        <div class="col-12">

          <div class="card">

            <div class="card-header">

              <h4 class="card-title">{{$title??''}}</h4>

              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

              <div class="heading-elements">

                <ul class="list-inline mb-0">

                  <li>

                    <!-- <a href="{{ route($routeName.'.'.$slug.'.export', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-file-export" aria-hidden="true"></i>Export</a> -->

                    &nbsp;&nbsp;&nbsp;



                    <?php //if($parent_role_id == 4){?>

                      <a href="{{ route($routeName.'.'.$slug.'.add', ['parent_id'=>$parent_id,'back_url' => $BackUrl]) }}" class="btn btn-info btn-sm" >Add {{$title}}</a>

                      <?php //}?>

                      &nbsp;&nbsp;&nbsp;

                      <?php //if(request()->has('back_url')){ $back_url= request('back_url');  ?>

                      <a href="{{ $back_url }}" class="btn btn-info btn-sm" >Back</a><?php //} ?>

                    </li>

                  </ul>

                </div>

              </div>



              <div class="row">

                <div class="col-12">

                  <div class="card">

                    <div class="card-body">

                      <div class="">

                        <form method="">

                          <input type="hidden" name="parent_id" value="{{$parent_id??''}}">

                          <div class="row">

                            <div class="col-8">

                              <label class="form-label">Search By Name,Email,Phone</label>

                              <div class="input-group">

                                <input type="text" name="search" value="{{old('search',$search)}}" class="form-control" placeholder="Search...." aria-label="Recipient's username">



                              </div>

                            </div>

                            <div class="col-4">

                              <label class="form-label">Search By ID</label>

                              <div class="input-group">

                                <input type="number" name="find_id" value="{{old('find_id',$find_id)}}" class="form-control" placeholder="Search By ID...." aria-label="Recipient's username">



                              </div>

                            </div>






                            <div class="col-3">



                              <div class="input-group">

                                <button style="margin-top:27px;" class="btn input-group-text btn-dark waves-effect waves-light" type="submit">Search</button>

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

                        <th>{{$roles->name}} Details</th>

                        <th>{{CustomHelper::getRoleName($roles->parent_id)}} Details</th>

                        @if(CustomHelper::isAllowedSection('login_details' , 'list'))

                        <th>Login Details</th>

                        @endif

                        <th>Locality Details</th>

                        <th>Coupons</th>

                        <th>Action</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php if(!empty($users)){

                        foreach ($users as $user){

                          $parent_coupons = [];

                          $parent = CustomHelper::getUserDetails($user->parent_id);

                          $get_parents = CustomHelper::getParents($user->parent_id);

                          $business_name = $parent->business_name??'';

                          if(empty($business_name)){

                            $business_name = $parent->name??'';

                          }

                          $used_coupon = \App\AssignCoupon::select('id')->where('is_used',1)->where('is_return',0)->where('child_id',$user->id)->count();


                          $total_coupon = \App\AssignCoupon::select('id')->where('is_return',0)->where('child_id',$user->id)->count();

                          $remaining_coupon = $total_coupon-$used_coupon;



                          ?>

                          <tr <?php if($user->status == 0){?>
                            style="background: #e86d6d; color: black;"

                          <?php }?>
                            >

                           <td>{{$user->id}}</td>

                           <td>{{$user->unique_id??''}}</td>

                           <td>

                            <strong>Business Name: </strong>{{$user->business_name??''}}<br>

                            <strong>Owner Name: </strong>{{$user->name??''}}<br>

                            <strong>Email: </strong>{{$user->email??''}}

                            <br><strong>Phone: </strong>{{$user->phone??''}}

                            <br><strong>Alternate Phone: </strong>{{$user->alternate_phone??''}}

                            <br><strong>Status: </strong><?php if ($user->status == 1){echo "Active";}else{echo "InActive";}?> 

                            <br><strong>Date & Time: </strong>{{date('d M Y h:i A',strtotime($user->created_at))}} 

                          </td>

                          <td>

                            <strong>Business Name: </strong>{{$business_name??''}} --- {{$parent->unique_id??''}}<br>

                            <br><strong>Phone: </strong>{{$parent->phone??''}}

                            <br><strong>Owner Name: </strong>{{$parent->name??''}}

                          </td>

                          @if(CustomHelper::isAllowedSection('login_details' , 'list'))

                          

                          <td>

                            <?php 

                            if($child->id == $user->role_id){

                              ?>

                              <strong>Username: </strong><strong style="color:red;">{{$user->username??''}}</strong><br>
                              
                              <strong class="password">Password: </strong>{{$user->password_value??''}}<i class="fa fa-eye ind togglePassword" ></i><br>

                            <?php }if($parent_role_id == 0){?>

                              <strong>Username: </strong><strong style="color:red;">{{$user->username??''}}</strong><br>
                              
                              <strong class="password">Password: </strong>{{$user->password_value??''}}<i class="fa fa-eye ind togglePassword" ></i><br>

                            <?php }?>

                          </td>





                          @endif

                          <td><strong>Address: </strong>{{$user->address??''}}<br>

                            <strong>State: </strong>{{CustomHelper::getStateName($user->state_id)}}<br>

                            <strong>City: </strong>{{CustomHelper::getCityName($user->city_id)}}<br>

                            <strong>Pincode: </strong>{{$user->pincode??''}}</td>

                            <td>

                              @if(CustomHelper::isAllowedSection('show_coupon_history' , 'list'))



                              <a href="{{route($routeName.'.'.$slug.'.coupons_history', ['id'=>$user->id,'back_url'=>$BackUrl])}}"><label class="badge badge-sucess" style="background: #29d91d;">Show History

                              </label></a>

                              <br>

                              @endif

                              @if(CustomHelper::isAllowedSection('view_coupons' , 'list'))



                              <!-- <strong>Coupons: </strong>{{$user->no_of_coupons??0}} / {{$remaining_coupon??''}} -->

                              <!-- <strong>Coupons: </strong>{{$user->no_of_coupons??0}} -->

                              <strong>Coupons: </strong><p>{{CustomHelper::getMyCouponCount($user->id)}}



                                <?php if($slug == 'seller'){?>

                                 / {{$remaining_coupon??0}}

                               <?php }?>

                             </p>









                             <br>

                             <a href="{{route($routeName.'.'.$slug.'.view_coupons', ['id'=>$user->id,'back_url'=>$BackUrl])}}"><label class="badge badge-sucess" style="background: #29d91d;">View Coupons

                             </label></a>

                             @endif





                           </td>

                           <td>

                            @if(CustomHelper::isAllowedSection('edit_party_user' , 'list'))



                            <a href="{{route($routeName.'.'.$slug.'.edit', ['id'=>$user->id,'parent_id'=>$user->id,'back_url'=>$BackUrl])}}"><label  class="badge badge-sucess" style="background:teal; padding: 10px;" ><i class="fa fa-edit"></i> Edit</label>

                              <br>

                              @endif

                              @if(CustomHelper::isAllowedSection('change_password' , 'list'))



                              <a href="javascript:void(0);" onclick=""><label class="badge badge-sucess" style="background: #29d91d;"> Change password

                              </label></a>

                              <br>

                              @endif

                              @if(CustomHelper::isAllowedSection('assign_coupon' , 'list'))



                              <?php 

                              if($child->id == $user->role_id || $parent_role_id == 0){



                                ?>



                                <!-- <a data-toggle="modal" onclick="getparent_coupons('{{$user->parent_id}}','{{$user->id}}')" data-target="#assignCouponModal{{$user->id}}"><label class="badge badge-sucess" style="background: #29d91d;">  Assign Coupon</label></a> -->





                                <a data-toggle="modal" onclick="getparent_coupons('{{$user->parent_id}}','{{$user->id}}')" data-target="#assignCouponModal{{$user->id}}"><label class="badge badge-sucess" style="background: #29d91d;">  Assign Coupon

                                </label></a>



                                <div class="modal fade text-left" id="assignCouponModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1" aria-hidden="true">

                                  <?php 
                                  $business_name_child = $user->business_name??'';
                                  if(empty($business_name_child)){
                                    $business_name_child = $user->name??'';
                                  }
                                  ?>

                                  <div class="modal-dialog" role="document">

                                    <div class="modal-content">

                                      <div class="modal-header">

                                        <h4 class="modal-title" id="basicModalLabel1" >Assign Coupon <br>
                                         <strong> From : </strong>{{$parent->business_name??''}} -- {{$parent->unique_id??''}}<br>
                                          <strong>To : </strong>{{$business_name_child??''}} -- {{$user->unique_id??''}}</h4>

                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                          <span aria-hidden="true">&times;</span>

                                        </button>

                                      </div>

                                      <div class="modal-body">                      

                                        <form action="{{ route($routeName.'.coupons.assign_coupons') }}" method="post">

                                          @csrf

                                          <input type="hidden" name="parent_id" value="{{$user->parent_id}}">

                                          <input type="hidden" name="child_id" value="{{$user->id}}">



                                          <div class="row">

                                            <div class="col-md-12 d-none">

                                              <label>Choose Coupons</label>

                                              <select class="form-control" name="coupons[]" multiple id="parent_coupon{{$user->id}}">

                                                <!-- <option value="" selected disabled>Choose Coupons</option> -->

                                                <?php 

                                                if(!empty($parent_coupons)){

                                                  $i = 0;

                                                  foreach($parent_coupons as $coup){?>

                                                    <option value="{{$coup->id}}">{{++$i}}. {{$coup->couponID}}</option>

                                                  <?php }}?>



                                                </select>

                                              </div>


                                              <div class="col-md-12">

                                              <label>Enter No. Of Coupons</label>
                                              <input type="number" name="coupon_qty" value="" class="form-control" placeholder="Enter No. Of Coupons">
                                              </div>



                                            </div>





                                          </div>

                                          <div class="modal-footer">

                                            <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>

                                            <button type="submit" class="btn btn-danger">Save</button>

                                          </div>

                                        </form>   

                                      </div>

                                    </div>

                                  </div>















                                <?php }?>

                                <br>

                                @endif





                                <?php 

                                if(!empty($child_role)){?>

                                  @if(CustomHelper::isAllowedSection('add_party_user' , 'list'))



                                  <a href="{{ route($routeName.'.'.$child_role->slug.'.add', ['parent_id'=>$user->id,'back_url' => $BackUrl]) }}"><label class="badge badge-sucess" style="background:red; padding: 10px;" ><i class="fa fa-plus"></i> Add  {{$child_role->name}}</label>

                                  </a>

                                  <br>

                                  @endif



                                  @if(CustomHelper::isAllowedSection('view_party_user' , 'list'))

                                  <a href="{{ route($routeName.'.'.$child_role->slug.'.index', ['parent_id'=>$user->id,'back_url' => $BackUrl]) }}"><label  class="badge badge-sucess" style="background:#8d8d15; padding: 10px;" ><i class="fa fa-eye"></i> View  {{$child_role->name}} ({{CustomHelper::getNoOfChild($user->id)}})</label>

                                  </a>

                                  @endif







                                  @if(CustomHelper::isAllowedSection('telecaller_remarks' , 'list'))

                                 <!--  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#remarksModal{{$user->id}}">

                                    Add Remarks

                                  </button> -->

                                  <br>



                                  <div class="modal fade" id="remarksModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                    <div class="modal-dialog" role="document">

                                      <div class="modal-content">

                                        <div class="modal-header">

                                          <h5 class="modal-title" id="exampleModalLabel">{{$user->business_name??$user->name??''}} --- {{$user->unique_id??''}}</h5>

                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                            <span aria-hidden="true">&times;</span>

                                          </button>

                                        </div>

                                        <form action="{{ route($routeName.'.telecaller.admins_list') }}" method="post">

                                          @csrf

                                          <input type="hidden" name="admin_id" value="{{$user->id}}">

                                          <div class="modal-body">

                                            <label>Remarks</label>

                                            <textarea class="form-control" placeholder="Enter Remarks" name="remarks"></textarea>

                                          </div>

                                          <div class="modal-footer">

                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                            <button type="submit" class="btn btn-primary">Save</button>

                                          </div>

                                        </form>

                                      </div>

                                    </div>

                                  </div>





                                  @endif







                                <?php }?>



                                @if(CustomHelper::isAllowedSection('return_coupon_admin' , 'list'))

                                <a data-toggle="modal" onclick="return_coupons('{{$user->id}}')" data-target="#returnCoupon{{$user->id}}"><label class="badge badge-sucess" style="background: green;">Return Coupon

                               </label></a> 

                               <br>



                               <div class="modal fade text-left" id="returnCoupon{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1" aria-hidden="true">

                                <div class="modal-dialog" role="document">

                                  <div class="modal-content">

                                    <div class="modal-header">

                                      <h4 class="modal-title" id="basicModalLabel1" style="text-align: center;">Return Coupon<br>{{$user->business_name??$user->name??''}} -- {{$user->unique_id??''}}</h4>

                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                        <span aria-hidden="true">&times;</span>

                                      </button>

                                    </div>

                                    <div class="modal-body">                      

                                      <form action="{{ route($routeName.'.'.$slug.'.return_coupon') }}" method="post">

                                        @csrf

                                        <input type="hidden" name="party_user_id" value="{{$user->id}}">

                                        <div class="row">

                                          <div class="col-md-12">

                                            <label>Choose Coupons</label>

                                            <select class="form-control " name="couponids[]" multiple id="return_counds_html{{$user->id}}">




                                            </select>

                                          </div>



                                        </div>





                                      </div>

                                      <div class="modal-footer">

                                        <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>

                                        <button type="submit" class="btn btn-danger">Save</button>

                                      </div>

                                    </form>   

                                  </div>

                                </div>

                              </div>











                              @endif





                              @if(CustomHelper::isAllowedSection('change_parent' , 'list'))

                              <!--   <a data-toggle="modal" data-target="#changeParent{{$user->id}}"><label class="badge badge-sucess" style="background: #E000FF;">Change Parent

                              </label></a> -->

                              <br>



                              <div class="modal fade text-left" id="changeParent{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel1" aria-hidden="true">

                                <div class="modal-dialog" role="document">

                                  <div class="modal-content">

                                    <div class="modal-header">

                                      <h4 class="modal-title" id="basicModalLabel1" style="text-align: center;">Change Parent <br>{{$user->business_name??''}} -- {{$user->unique_id??''}}</h4>

                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                        <span aria-hidden="true">&times;</span>

                                      </button>

                                    </div>

                                    <div class="modal-body">                      

                                      <form action="{{ route($routeName.'.'.$slug.'.change_parent') }}" method="post">

                                        @csrf

                                        <input type="hidden" name="party_user_id" value="{{$user->id}}">

                                        <div class="row">

                                          <div class="col-md-12">

                                            <label>Choose Parent</label>

                                            <select class="form-control " name="parent_id">

                                              <option value="" selected disabled>Choose Parent</option>

                                              <?php 

                                              if(!empty($get_parents)){

                                                $i = 0;

                                                foreach($get_parents as $use){

                                                  $business_name = $use->business_name??'';

                                                  if(empty($business_name)){

                                                    $business_name = $use->name??'';



                                                  }

                                                  ?>

                                                  <option value="{{$use->id}}" <?php if($user->parent_id == $use->id) echo "selected"?>>{{$business_name??''}} -- {{$use->unique_id??''}}</option>

                                                <?php }}?>



                                              </select>

                                            </div>



                                          </div>





                                        </div>

                                        <div class="modal-footer">

                                          <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>

                                          <button type="submit" class="btn btn-danger">Save</button>

                                        </div>

                                      </form>   

                                    </div>

                                  </div>

                                </div>











                                @endif



                              </td>



                            </tr>



                          <?php }}?>

                        </tbody>

                      </table>

                      {{ $users->appends(request()->input())->links('admin.pagination') }}



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



      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>





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

        function getparent_coupons(parent_id,user_id){

          // alert('okk');



          var _token = '{{ csrf_token() }}';

          $.ajax({

            url: "{{ route($routeName.'.getparent_coupons') }}",

            type: "POST",

            data: {parent_id:parent_id, user_id:user_id},

            dataType:"HTML",

            headers:{'X-CSRF-TOKEN': _token},

            cache: false,

            success: function(resp){

              $('#parent_coupon'+user_id).html(resp);

                  // $('#assignCouponModal'+user_id).modal('toggle');

            }

          });

        }

        function return_coupons(user_id){

          // alert('okk');



          var _token = '{{ csrf_token() }}';

          $.ajax({

            url: "{{ route($routeName.'.return_coupons') }}",

            type: "POST",

            data: {user_id:user_id},

            dataType:"HTML",

            headers:{'X-CSRF-TOKEN': _token},

            cache: false,

            success: function(resp){

              $('#return_counds_html'+user_id).html(resp);

                  // $('#assignCouponModal'+user_id).modal('toggle');

            }

          });

        }











      </script>

