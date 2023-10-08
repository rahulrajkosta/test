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
      <h4 class="mb-0">List</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.rearrange.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
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
              <h4 class="header-title">List</h4>
        <div class="table-responsive">

              <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    
                    <th>S.No.</th>                                                                  
                    <th>Sentence</th>                    
                    <th>Right Answer</th> 
                    <th>Date</th> 
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                  if(!empty($words)){

                    $i = 1;
                    foreach($words as $wrd){
                      ?>
                      <tr>

                        <td>{{$wrd->id}}</td>
                        <td>{{$wrd->sentence ?? ''}}</td>                       
                        <td>{{$wrd->right_answer ?? ''}}</td>                       
                        
                        <td>{{$wrd->created_at ?? ''}}</td>
                        
                                            
                        <td>
                          <select id='change_word_status{{$wrd->id}}' onchange='change_word_status({{$wrd->id}})' class="form-control">
                            <option value='1' <?php if($wrd->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($wrd->status ==0)echo "selected";?>>InActive</option>
                          </select> 


                        </td>

                        <td>
                         
                          <a class="btn btn-success" href="{{ route($routeName.'.rearrange.edit', $wrd->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                          <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.rearrange.delete', $wrd->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>


                </table>

                {{ $words->appends(request()->input())->links('admin.pagination') }}
</div>
              </div> <!-- end card body-->
            </div> <!-- end card -->
          </div><!-- end col-->
        </div>


  @include('admin.common.footer')

  <script>
   function change_word_status(id){
  var status = $('#change_word_status'+id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.rearrange.change_word_status') }}",
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
