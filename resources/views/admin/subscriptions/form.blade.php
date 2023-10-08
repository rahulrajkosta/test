@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'subscription/';

$subscription_id = isset($subscription->id) ? $subscription->id : '';
$title = isset($subscription->title) ? $subscription->title : '';
$price = isset($subscription->price) ? $subscription->price : '';
$duration = isset($subscription->duration) ? $subscription->duration : '';
$image = isset($subscription->image) ? $subscription->image : '';
$status = isset($subscription->status) ? $subscription->status : '0';



?>


<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">{{ $page_Heading }}</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">{{ $page_Heading }}
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">
      <section class="input-validation">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{ $page_Heading }}</h4>
                <a class="heading-elements-toggle">
                  <i class="la la-ellipsis-v font-medium-3"></i>
                </a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li>
                     <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                     <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
                   </li>
                 </ul>
               </div>
             </div>
             @include('snippets.errors')
             @include('snippets.flash')

             <div class="card-content collapse show">
              <div class="card-body">
                <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

                  {{ csrf_field() }}

                  <input type="hidden" id="id" value="{{$subscription_id}}">

                  <div class="form-row">

                    <div class="col-md-6">
                      <label for="email" class="form-label">Title</label>
                      <input type="text" class="form-control mb-1" name="title" id="title" placeholder="Enter Title" value="{{ old('title',$title) }}">
                    </div>

                    <div class="col-md-6">
                      <label for="email" class="form-label">Duration (In Months)</label>
                      <input type="text" class="form-control mb-1" name="duration" id="duration" placeholder="Enter Duration" value="{{ old('duration',$duration) }}">
                    </div>


                    <div class="col-md-6">
                      <label for="email" class="form-label">Price</label>
                      <input type="text" class="form-control mb-1" name="price" id="price" placeholder="Enter Price" value="{{ old('price',$price) }}">
                    </div>





                    <div class="col-md-6">
                      <label for="fullname" class="form-label">Upload Image</label>
                      <input class="form-control mb-1" type="file" name="image" id="image" aria-label="default input example">

                      <?php
                      if(!empty($image)){
                        if($storage->exists($path.$image)){ ?>
                          <div class=" image_box" style="display: inline-block">
                            <a href="{{ url('storage/app/public/'.$path.$image) }}" target="_blank">
                              <img src="{{ url('storage/app/public/'.$path.$image) }}" style="width:70px;">
                            </a>


                          </div>
                        <?php  }
                      }
                      ?>

                    </div>

                    <div class="col-md-6">
                     <label>Status</label>
                     <div>
                       Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                       &nbsp;
                       Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                       @include('snippets.errors_first', ['param' => 'status'])
                     </div>
                   </div>


                 </div>

                 <button class="btn btn-primary" type="submit">Submit </button>
               </form>
             </div>
           </div>
         </div>
       </div>
     </div>
   </section>

 </div>
</div>
</div>


@include('admin.common.footer')
