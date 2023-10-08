@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

// $batch_id = isset($batches->id) ? $batches->id : '';
// $course_id = isset($batches->course_id) ? $batches->course_id : '';
// $category_id = isset($batches->category_id) ? $batches->category_id : '';
// $subject_name = isset($batches->subject_name) ? $batches->subject_name : '';



?>


<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">{{ $page_Heading }}</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $page_Heading }}</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <div class="btn-group">

          <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                            <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
        </div>


      </div>
    </div>
  </div>
  <!--end breadcrumb-->
  <div class="row">
    <div class="col-xl-9 mx-auto">
      <h6 class="mb-0 text-uppercase">{{ $page_Heading }}</h6>
      <hr/>
        @include('snippets.errors')
            @include('snippets.flash')

      <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

        {{ csrf_field() }}

        <input type="hidden" id="batch_id" value="">

        <div class="card">
          <div class="card-body"> 

            <div class="form-group row">
            <label class="col-md-3 col-form-label">Category <span class="text-danger">*</span></label>
            <div class="col-md-7">
              <select id="category_id" name="category_id" class="form-control mb-3">
                <option value="" selected disabled>Select Category</option>
               
                <option value=""></option>
                        
              </select>
            </div>
        </div>         
           

            <div class="form-group row">
              <label class="col-md-3 col-form-label">Course</label>
              <div class="col-md-7">
                <select class="form-control mb-3" name="course_id" id="course_id">

                    <option value="" selected disabled>Select Course</option>                  
                  <option value=""></option>
                
                </select>
              </div>
          </div>

           <div class="form-group row">
              <label class="col-md-3 col-form-label">Subject</label>
              <div class="col-md-7">
                <select class="form-control mb-3" name="subject_id" id="subject_id">

                    <option value="" selected disabled>Select Subject</option>                  
                  <option value=""></option>
                
                </select>
              </div>
          </div>



            <div class="form-group row">
            <label class="col-md-3 col-form-label">Title<span class="text-danger">*</span></label>
            <div class="col-md-7"><input type="text" class="form-control mb-3" name="title" value="" id="title" placeholder="Enter Title" aria-label="default input example"></div>
        </div>

          <div class="form-group row">
            <label class="col-md-3 col-form-label">HLS<span class="text-danger">*</span></label>
            <div class="col-md-7"><input type="text" class="form-control mb-3" name="hls" value="" id="hls" placeholder="Enter HLS" aria-label="default input example"></div>
        </div>

         <div class="form-group row">
            <label class="col-md-3 col-form-label">HLS Type<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <select class="form-control mb-3" name="hls_type" id="hls_type">

                    <option value="" selected disabled>Select HLS Type</option>                  
                  <option value="notes">Notes</option>
                  <option value="videos">Videos</option>
                
                </select>

            </div>
        </div>

        
         </div>
         <button type="submit" class="btn btn-success btn-md">Submit</button>
       </div>
     </form>
   </div>
 </div>
 <!--end row-->
</div>
</div>



@include('admin.common.footer')

<script type="text/javascript">
    $(document).ready(function() {
       $('.ckeditor').ckeditor();
    });

    $('#category_id').on('change',function() {
        
        var _token = '{{ csrf_token() }}';
        var category_id = this.value;
        // alert("category_id = "+category_id);

        $.ajax({
          url: "",
            type: "POST",
            data: {category_id:category_id},
            dataType:"HTML",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){

               $('#course_id').html(resp);
           }
       });

    })




</script>


