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
      <h4 class="mb-0">Daily Quotes</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.daily_quotes.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Quote</a>
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
              <h4 class="header-title">Daily Quotes</h4>
        <div class="table-responsive">

              <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                  <tr>
                    
                    <th>S.No.</th>
                    <th>Image</th>                                                 
                    <th>Daily Quotes</th> 
                    <th>Date</th> 
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                  if(!empty($quotes)){

                    $i = 1;
                    foreach($quotes as $quote){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>
                          
                            <?php

                               $image = isset($quote->bg_image) ? $quote->bg_image : '';
                                $storage = Storage::disk('public');
                                $path = 'daily_quotes';

                                if(!empty($image))
                                {
                            ?>
                                <a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" style='width:100px;heigth:100px;'></a>

                          <?php }else{ ?>
                            <a href="{{ url('public/storage/'.$path.'/default-quote.jpg') }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/default-quote.jpg') }}" style='width:100px;heigth:100px;'></a>

                          <?php } ?>

                        </td>

                        
                         <td>{{$quote->date ?? ''}}</td>
                      
                        <td>{{$quote->thought ?? ''}}</td>
                                            
                        <td>
                          <select id='change_quote_status{{$quote->id}}' onchange='change_quote_status({{$quote->id}})' class="form-control">
                            <option value='1' <?php if($quote->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($quote->status ==0)echo "selected";?>>InActive</option>
                          </select> 


                        </td>

                        <td>
                         
                          <a class="btn btn-success" href="{{ route($routeName.'.daily_quotes.edit', $quote->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                          <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.daily_quotes.delete', $quote->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>


                </table>

                {{ $quotes->appends(request()->input())->links('admin.pagination') }}
</div>
              </div> <!-- end card body-->
            </div> <!-- end card -->
          </div><!-- end col-->
        </div>


  @include('admin.common.footer')

  <script>
   function change_quote_status(id){
  var status = $('#change_quote_status'+id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.daily_quotes.change_quote_status') }}",
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
