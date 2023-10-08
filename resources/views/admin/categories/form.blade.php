@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'category/';

$category_id = isset($categories->id) ? $categories->id : '';
$name = isset($categories->name) ? $categories->name : '';
$image = isset($categories->image) ? $categories->image : '';
$type = isset($categories->type) ? $categories->type : '';
$priority = isset($categories->priority) ? $categories->priority : '';
$status = isset($categories->status) ? $categories->status : '1';


?>


<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">{{ $page_Heading }}</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
           <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
              <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
        </ol>
      </div>

    </div>
  </div>
</div>

  @include('snippets.errors')
    @include('snippets.flash')





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
                   <input class="form-control mb-3" type="text" name="name" id="name"  value="{{ old('name',$name) }}" placeholder="Category Name" aria-label="default input example">

                </div>

                <div class="mb-3">
                  <label for="fullname" class="form-label">Type  * :</label>
                  <select class="form-control" name="type">
                    <option value="" selected disabled>Select Type</option>
                    <option value="shop" <?php if($type == 'shop')echo "selected"?>>Shop</option>
                    <option value="service" <?php if($type == 'service')echo "selected"?>>Service</option>
                  </select>

                </div>

                


                 <div class="mb-3">
                  <label for="fullname" class="form-label">Priority  * :</label>
                   <input class="form-control mb-3" type="text" name="priority" id="priority"  value="{{ old('priority',$priority) }}" placeholder="Priority" aria-label="default input example">

                </div>


                <div class="mb-3">
                  <label for="fullname" class="form-label">Upload Image   :</label>
                   <input class="form-control mb-3" type="file" name="image" id="image"  value="" aria-label="default input example">

                </div>

                 <div class="mb-3">
                      <label>Status</label>
                      <div>
                       Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                       &nbsp;
                       Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                       @include('snippets.errors_first', ['param' => 'status'])
                   </div>
               </div>



                <div>
                  <input type="submit" class="btn btn-success" value="Submit">
                </div>

              </form>
            </div>
          </div> <!-- end card-->
        </div> <!-- end col-->
      </div>

@include('admin.common.footer')

