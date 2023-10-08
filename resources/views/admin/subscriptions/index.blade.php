@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'subscription/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 
?>





<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Subscription</h3>
    </div>
    <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Subscription
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
          <h4 class="card-title">Subscription</h4>
          <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
          <div class="heading-elements">
              <ul class="list-inline mb-0">
                <li>
                    
                   <a href="{{ route($routeName.'.subscriptions.add').'?back_url='.$BackUrl }}" class="btn btn-info btn-sm" style='float: right;'>Add Subscription</a>
               </li>
           </ul>
       </div>
   </div>
   <div class="card-content collapse show">
      <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Title</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Image</th>
                <th>Status</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
         <?php
         if(!empty($subscription)){
            $i = 1;
            foreach($subscription as $sub_his){
                ?>
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$sub_his->title ?? ''}}</td>
                    <td>{{$sub_his->duration ?? ''}}</td>
                    <td>{{$sub_his->price ?? ''}}</td>
                    <td><?php

                    $image = isset($sub_his->image) ? $sub_his->image : '';
                    $storage = Storage::disk('public');
                    $path = 'subscription';

                    if(!empty($image))
                    {
                        ?>

                        <a href="{{ url('storage/app/public/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('storage/app/public/'.$path.'/'.$image) }}" style='width:100px;heith:100px;'></a>


                        <?php } ?></td>
                        <td><select id='change_subscription_status{{$sub_his->id}}' onchange='change_subscription_status({{$sub_his->id}})' class="form-control">
                          <option value='1' <?php if($sub_his->status ==1)echo "selected";?> >Active</option>
                          <option value='0' <?php if($sub_his->status ==0)echo "selected";?>>InActive</option>
                      </select> </td>
                      <td>

                        <a class="btn btn-success" href="{{ route($routeName.'.subscriptions.edit', $sub_his->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                        <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.subscriptions.delete', $sub_his->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                    </td>

                </tr>

            <?php } }?>
        </tbody>
        {{ $subscription->appends(request()->input())->links('admin.pagination') }}
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



  function change_subscription_status(id){
    var status = $('#change_subscription_status'+id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.subscriptions.change_subscription_status') }}",
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
