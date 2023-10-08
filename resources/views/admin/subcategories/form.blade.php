@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'subcategory/';

$subcategory_id = isset($subcategories->id) ? $subcategories->id : '';
$name = isset($subcategories->name) ? $subcategories->name : '';

$category_id = isset($subcategories->category_id) ? $subcategories->category_id : '';

$image = isset($subcategories->image) ? $subcategories->image : '';
$status = isset($subcategories->status) ? $subcategories->status : '1';


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






<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">{{ $page_Heading }}</h4>

        <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

          {{ csrf_field() }}

          <input type="hidden" value="{{ $subcategory_id }}">

          <div class="mb-3">
            <label for="fullname" class="form-label">Category Name  * :</label>
            <select class="form-control" name="category_id">
              <option>Select Category</option>

              <?php if(!empty($categories)){
                foreach ($categories as $sub_cat) {?>
                  <option value="{{$sub_cat->id}}" <?php if($sub_cat->id == $category_id) echo "selected";?>> {{$sub_cat->name}}</option>
               <?php  }}
                ?>
            </select>

          </div>

          <div class="mb-3">
            <label for="fullname" class="form-label">Sub Category Name  * :</label>
            <input class="form-control mb-3" type="text" name="name" id="name"  value="{{ old('name',$name) }}" placeholder="Category Name" aria-label="default input example">

          </div>




          <div class="mb-3">
            <label for="fullname" class="form-label">Upload Image   :</label>
            <input class="form-control mb-3" type="file" name="image" id="image"  value="" aria-label="default input example">

          </div>

              <?php
                     if(!empty($image)){
                        if($storage->exists($path.$image)){
                          ?>
                          <div class=" image_box" style="display: inline-block">
                            <a href="{{ url('public/storage/'.$path.$image) }}" target="_blank">
                              <img src="{{ url('public/storage/'.$path.'thumb/'.$image) }}" style="width:70px;">
                            </a>
                          </div>
                          <?php
                        }
                    }
                    ?>



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

