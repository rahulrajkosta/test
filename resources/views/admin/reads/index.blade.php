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
      <h4 class="mb-0">Read & Respond Section</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.read_respond.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
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
              <h4 class="header-title">Read & Respond Section</h4>
        <div class="table-responsive">

              <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Text</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($reads)){

                    $i = 1;
                    foreach($reads as $read){
                      $text = mb_strlen(strip_tags($read->text),'utf-8') > 50 ? mb_substr(strip_tags($read->text),0,50,'utf-8').'...' : strip_tags($read->text);
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$text}}</td>
                        <td>
                          <select id='change_read_status{{$read->id}}' onchange='change_read_status({{$read->id}})' class="form-control">
                            <option value='1' <?php if($read->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($read->status ==0)echo "selected";?>>InActive</option>
                          </select> 


                        </td>

                        <td>
                         
                          <a class="btn btn-success" href="{{ route($routeName.'.read_respond.edit', $read->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                          <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.read_respond.delete', $read->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>


                </table>

                {{ $reads->appends(request()->input())->links('admin.pagination') }}
</div>
              </div> <!-- end card body-->
            </div> <!-- end card -->
          </div><!-- end col-->
        </div>



  @include('admin.common.footer')

  <script>
    function change_read_status(id){
      var status = $('#change_read_status'+id).val();
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route($routeName.'.read_respond.change_read_status') }}",
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
