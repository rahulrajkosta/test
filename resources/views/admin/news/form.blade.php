@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'news/';

$news_id = isset($news->id) ? $news->id : '';
$title = isset($news->title) ? $news->title : '';
$description = isset($news->description) ? $news->description : '';
$image = isset($news->image) ? $news->image : '';
$video = isset($news->video) ? $news->video : '';
$status = isset($news->status) ? $news->status : '1';
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

         <input type="hidden" name="id" value="{{ $news_id }}">
         <div class="mb-3">
          <label for="fullname" class="form-label">Title</label>
          <textarea class="form-control mb-3"  name="title" id="questions" placeholder="Write Title" aria-label="default input example">{{ old('title',$title) }}</textarea>

        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Description</label>
          <textarea class="form-control mb-3"name="description" id="description"  placeholder="Write Description .........." aria-label="default input example">{{ old('description',$description) }}</textarea>
        </div>


        <div class="mb-3">
          <label for="fullname" class="form-label">Upload Image</label>
          <input class="form-control mb-3" type="file" name="image" id="image" aria-label="default input example">

          <?php if(!empty($image)){
            if($storage->exists($path.$image)){
              ?>
              <div class=" image_box" style="display: inline-block">
                <a href="{{ url('public/storage/'.$path.$image) }}" target="_blank">
                  <img src="{{ url('public/storage/'.$path.'thumb/'.$image) }}" style="width:70px;">
                </a>
                <br>
              </div>
            <?php } }?>




          </div>


          <div class="mb-3">
            <label for="fullname" class="form-label">Video Id</label>
            <input class="form-control mb-3" type="text" value="{{ old('video',$video) }}" name="video" id="video" aria-label="default input example">


          </div>







          <div class="mb-3">
            <label for="fullname" class="form-label">Status</label>
            <br>
            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
            &nbsp;
            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

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

