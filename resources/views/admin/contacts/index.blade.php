@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>

      <div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Contact Us</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          
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
              <h4 class="header-title">Contact Us List</h4>
        <div class="table-responsive">

              <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Description</th>                 
                    <th>Status</th>                 
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($contacts)){
                    $i = 1;
                    foreach($contacts as $cat){
                      $user = \App\User::where('id',$cat->user_id)->first();

                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$cat->name ?? $user->name}}</td>
                        <td>{{$cat->email ?? $user->email}}</td>
                         <td>{!!$cat->message!!}</td>
                        <td>
                          <select id='change_contacts_status{{$cat->id}}' onchange='change_contacts_status({{$cat->id}})' class="form-control">
                            <option value='1' <?php if($cat->status ==1)echo "selected";?> >Solved</option>
                            <option value='0' <?php if($cat->status ==0)echo "selected";?>>Not Solve</option>
                          </select> 


                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>


                </table>

                {{ $contacts->appends(request()->input())->links('admin.pagination') }}
              </div>
              </div> <!-- end card body-->
            </div> <!-- end card -->
          </div><!-- end col-->
        </div>


      </div>
    </div>
  </div>


  @include('admin.common.footer')

  <script>
    function change_contacts_status(id){
      var status = $('#change_contacts_status'+id).val();
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route($routeName.'.contacts.change_contacts_status') }}",
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
