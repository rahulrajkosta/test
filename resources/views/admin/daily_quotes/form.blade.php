@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'banners/';

 $quote_id = isset($daily_quotes->id) ? $daily_quotes->id : '';

 $image = isset($daily_quotes->image) ? $daily_quotes->image : '';
 $quote = isset($daily_quotes->thought) ? $daily_quotes->thought : '';
 $date = isset($daily_quotes->date) ? $daily_quotes->date : '';
 
 $status = isset($daily_quotes->status) ? $daily_quotes->status : '';

 //print_r($banners);


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

                 <input type="hidden" id="id" value="{{$quote_id}}">

                <div class="mb-3">
                  <label for="email" class="form-label">Daily Quote</label>
                <textarea class="form-control mb-3" name="thought" id="thought" placeholder="Enter Link" >{{ old('quote',$quote) }}</textarea>
                </div>

               
                      <div class="mb-3">
                        <label for="email" class="form-label">Date</label>
                      <input class="form-control mb-3" type="date" name="date" id="date" placeholder="Enter Date" value="{{ old('date',$date) }}">
                      </div>
                    
                 

                <div class="mb-3">
                  <label for="fullname" class="form-label">Upload Image</label>
                   <input class="form-control mb-3" type="file" name="bg_image" id="bg_image" aria-label="default input example">

                    <?php
                        if(!empty($image)){
                            if($storage->exists($path.$image)){ ?>
                    <div class=" image_box" style="display: inline-block">
                        <a href="{{ url('public/storage/'.$path.$image) }}" target="_blank">
                            <img src="{{ url('public/storage/'.$path.$image) }}" style="width:70px;">
                        </a>


                    </div>
                           <?php  }
                        }
                    ?>


                </div>


                <div class="form-group">
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

