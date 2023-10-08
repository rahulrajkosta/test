@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';


?>

<div class="content-page">
  <div class="content">

    <!-- Start Content-->
    <div class="container-fluid">
      <div class="row">
          <div class="col-12">
              <div class="page-title-box">
                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                         <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                        <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
                      </ol>
                  </div>
                  <h4 class="page-title">Details</h4>
              </div>
          </div>
      </div>
      <!--  start page title -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h4 class="header-title">Details</h4>

            
            </div>
          </div> <!-- end card-->
        </div> <!-- end col-->
      </div>

    </div>
  </div>
</div>

@include('admin.common.footer')

