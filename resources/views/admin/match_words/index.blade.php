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
          <a href="{{ route($routeName.'.match_words.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
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
                    <th>Word</th>                    
                    <th>Match</th> 
                    <th>Date</th> 
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!empty($match_words))
                    @foreach($match_words as $mw)
                  <tr>
                    <td>{{$mw->id}}</td>
                    <td>{{$mw->word}}</td>
                    <td>{{$mw->matches}}</td>
                    <td>{{$mw->created_at}}</td>
                    <td>
                      <select id='change_word_status{{$mw->id}}' onchange='change_word_status({{$mw->id}})' class="form-control">
                            <option value='1' <?php if($mw->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($mw->status ==0)echo "selected";?>>InActive</option>
                      </select> 
                    </td>
                    <td>
                      <a class="btn btn-success" href="{{ route($routeName.'.match_words.edit', $mw->id.'?back_url='.$BackUrl) }}" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center{{$mw->id}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                      <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.match_words.delete', $mw->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>

                    </td>
                  </tr>


                   <div class="modal fade bs-example-modal-center{{$mw->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    <form action="{{ route($routeName.'.match_words.edit', $mw->id.'?back_url='.$BackUrl) }}" method="post">
                      @csrf
                        <div class="modal-body">
                          <input type="hidden" name="id" value="{{$mw->id}}">
                            
                            <div class="mb-3">
                              <label for="email" class="form-label">Word <span id="mandate">*</span></label>
                            <input class="form-control mb-3" type="text" name="word" id="word" placeholder="Enter Word" value="{{$mw->word}}">
                            </div> 

                            <div class="mb-3">
                              <label for="email" class="form-label">Match <span id="mandate">*</span></label>
                            <input class="form-control mb-3" type="text" name="matches" id="matches" placeholder="Enter Match" value="{{$mw->matches}}">
                            </div> 


                        </div>
                        <div>
                          <input type="submit" class="btn btn-success" value="Submit">
                        </div>
                    </form>
                    </div>
                    </div>
            </div>


                    @endforeach
                   @endif

                 
                </tbody>


                </table>


                

              
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
                url: "{{ route($routeName.'.match_words.change_word_status') }}",
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
