@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$category_id = isset($categories->id) ? $categories->id : '';
$category_name = isset($categories->category_name) ? $categories->category_name : '';
$category_description = isset($categories->category_description) ? $categories->category_description : '';


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
                  <h4 class="page-title">{{ $page_Heading }}</h4>
              </div>
          </div>
      </div>
      <!--  start page title -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h4 class="header-title">{{ $page_Heading }}</h4>

             <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

        {{ csrf_field() }}

        <input type="hidden" value="{{ $category_id }}">
                <div class="mb-3">
                  <label for="fullname" class="form-label">Category Name  * :</label>
                   <input class="form-control mb-3" type="text" name="category_name" id="category_name"  value="{{ old('category_name',$category_name) }}" placeholder="Category Name" aria-label="default input example">

                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">Category Description :</label>
                 <textarea class="form-control mb-3"name="category_description" id="category_description"  placeholder="Write Category Description .........." aria-label="default input example">{{ old('category_description',$category_description) }}</textarea>
                </div>

                <div>
                  <input type="submit" class="btn btn-success" value="Submit">
                </div>

              </form>
            </div>
          </div> <!-- end card-->
        </div> <!-- end col-->
      </div>

    </div>
  </div>
</div>

@include('admin.common.footer')

