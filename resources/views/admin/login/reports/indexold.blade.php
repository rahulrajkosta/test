@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();

$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>




<div class="app-content content">

  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Reports</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Reports
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
              <h4 class="card-title">Reports</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>

                   <a onclick="export_reports()" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i></a>
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                   <a href="{{ route($ADMIN_ROUTE_NAME.'.reports.add').'?back_url='.$BackUrl }}" class="btn btn-info btn-sm" style='float: right;'>Generate New Report</a>
                 </li>
               </ul>
             </div>
           </div>
           <div class="card-content collapse show">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">S.No.</th>
                    <th scope="col">Type</th>                                                 
                    <th scope="col">Role</th>                                                 
                    <th scope="col">User Details</th>                                                 
                    <th scope="col">File</th>
                    <th scope="col">Remarks</th>
                    <th scope="col">Refresh</th>

                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($reports)){

                    $i = 1;
                    foreach($reports as $cat){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td><?php 
                        if($cat->type == 'user_export'){
                          echo "User Data";
                        }
                        if($cat->type == 'role_info'){
                          echo "Party Users Data";
                        }
                        if($cat->type == 'all_coupon_allocation'){
                          echo "All Coupon Allocation";
                        }
                        if($cat->type == 'role_closing_coupon'){
                          echo "Closing Coupon";
                        }if($cat->type == 'role_coupon_allocation'){
                          echo "Party User Coupon Allocation";
                        }

                      ?></td>  
                      <td>{{CustomHelper::getRoleName($cat->role)}}</td>                       
                      <td><?php if(!empty($cat->user_id)){?>

                        {!!CustomHelper::getUserDetailsById(htmlspecialchars($cat->user_id))!!}
                        <?php }?></td>                       
                        <td>
                          <?php if(!empty($cat->file) ){
                            if($cat->type == 'role_coupon_allocation'){
                              $url = 'https://reports.reptileindia.co.in/images/'.$cat->file;
                            }else{
                              $url = url('public/reports/'.$cat->file);
                            }
                            ?>
                            <a class="btn btn-success" href="{{$url}}"><i class="fa fa-download"></i></a>
                          <?php }?>

                        </td>                       
                        <td>{{$cat->remarks??''}}</td> 
                        <?php 
                        if($cat->type == 'role_coupon_allocation'){?>  
                          <td><a onclick="export_reports_node('{{$cat->start_date}}','{{$cat->end_date}}','{{$cat->role}}','{{$cat->user_id}}','{{$cat->id}}')"><i class="fa fa-refresh"></i></a></td>               
                        <?php }else{?>
                          <td></td>
                        <?php }?>
                      </tr>
                    <?php }}?>
                  </tbody>
                </table>
                {{ $reports->appends(request()->input())->links('admin.pagination') }}

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
<script type="text/javascript">
  function export_reports() {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url('export_reports') }}",
      type: "POST",
      data: {},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      beforeSend: function() {
        $("#loader").show();
      },
      success: function(resp){
        $("#loader").hide();
        // location.reload();
      }
    });
  }


  function export_reports_node(start_date,end_date,role,user_id,report_id){
    var url = 'https://reports.reptileindia.co.in/add_test_data?start_date='+start_date+'&end_date='+end_date+'&role='+role+'&user_id='+user_id+'&report_id='+report_id+'';
   
    $.ajax({
      url: url,
      type: "GET",
      data: {},
      dataType:"HTML",
      headers:{'Access-Control-Allow-Origin': '*'},
      cache: false,
      success: function(resp){
        // $("#loader").hide();
        // location.reload();
      }
    });
  }
</script>