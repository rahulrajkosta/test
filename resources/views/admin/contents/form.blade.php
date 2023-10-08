@include('admin.common.header')

<?php

$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$content_id = isset($contents->id) ? $contents->id : '';
$category_id = isset($contents->category_id) ? $contents->category_id : '';
$course_id = isset($contents->course_id) ? $contents->course_id : '';
$subject_id = isset($contents->subject_id) ? $contents->subject_id : '';
$topic_id = isset($contents->topic_id) ? $contents->topic_id : '';
$hls_types = isset($contents->hls_type) ? $contents->hls_type : '';
$types = isset($contents->type) ? $contents->type : '';

$title = isset($contents->title) ? $contents->title : '';
$description = isset($contents->description) ? $contents->description : '';


$type = config('custom.type');
$hls_type = config('custom.hls_type');



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

        <input type="hidden" id="content_id" value="{{$content_id}}">

        <div class="card">
          <div class="card-body"> 

            <div class="form-group row">
              <label class="col-md-3 col-form-label">Course</label>
              <div class="col-md-7">
                <select class="form-control mb-3" name="course_id" id="course_id">


                    <option value="" selected disabled>Select Course</option> 
                @if(!empty($courses))
                @foreach($courses as $course)                 
                  <option value="{{$course->id}}" <?php if($course->id == $course_id) { echo "selected"; } ?>>{{$course->course_name}}</option>
                @endforeach
                @endif
                
                </select>
              </div>
          </div>

           <div class="form-group row">
              <label class="col-md-3 col-form-label">Subject</label>
              <div class="col-md-7">
                <select class="form-control mb-3" name="subject_id" id="subject_id">
                    <option value="" selected disabled>Select Subject</option>
                  <?php
                $subjects = App\Subject::where('course_id',$course_id)->get();
                if(!empty($subjects)){
                  foreach($subjects as $sub){

                  ?>                  
                  <option value="{{$sub->id}}" <?php if($sub->id == $subject_id) {echo "selected";} ?>>{{$sub->subject_name}}</option>
                <?php } } ?>
                
                </select>
              </div>
          </div>

           <div class="form-group row">
              <label class="col-md-3 col-form-label">Topic</label>
              <div class="col-md-7">
                <select class="form-control mb-3" name="topic_id" id="topic_id">
                    <option value="" selected disabled>Select Topic</option> 
                   <?php
                $topics = App\Topic::where('subject_id',$subject_id)->get();
                if(!empty($topics)){
                  foreach($topics as $topic){


                  ?>                   
                  <option value="{{$topic->id}}" <?php if($topic->id == $topic_id) {echo "selected";} ?>>{{$topic->topic_name}}</option>
                  <?php } } ?>
                
                </select>
              </div>
          </div>

            <div class="form-group row">
            <label class="col-md-3 col-form-label">Title<span class="text-danger">*</span></label>
            <div class="col-md-7"><input type="text" class="form-control mb-3" name="title" value="{{old('title',$title)}}" id="title" placeholder="Enter Batch" aria-label="default input example"></div>
        </div>


           <div class="form-group row">
            <label class="col-md-3 col-form-label">Description<span class="text-danger">*</span></label>
            <div class="col-md-7"><textarea type="text" class="form-control mb-3" name="description" value="{{old('description',$description)}}" id="description">{!! old('description',$description) !!}</textarea></div>
        </div>

        <div class="form-group row">
              <label class="col-md-3 col-form-label">HLS Type<span class="text-danger">*</span></label>
              <div class="col-md-7">
                <select class="form-control mb-3" name="hls_type" id="hls_type">

                    <option value="" selected disabled>Select HLS Type</option>
                 @if(!empty($hls_type))
                @foreach($hls_type as $key => $value)                    
                  <option value="{{$key}}" <?php if($key == $hls_types) {echo "selected";} ?>>{{$value}}</option>
                  @endforeach
                @endif
                
                </select>
              </div>
          </div>

          <div class="form-group row" id="types">
              <label class="col-md-3 col-form-label">Type<span class="text-danger">*</span></label>
              <div class="col-md-7">
                <select class="form-control mb-3" name="type" id="type">

                    <option value="" selected disabled>Type</option>   
                @if(!empty($type))
                @foreach($type as $key => $value)               
                  <option value="{{$key}}" <?php if($key == $types) {echo "selected";} ?>>{{$value}}</option>
                @endforeach
                @endif
                
                </select>
              </div>
          </div>


           <div class="form-group row" id="links">
            <label class="col-md-3 col-form-label">Enter Link<span class="text-danger">*</span></label>
            <div class="col-md-7"><input type="text" class="form-control mb-3" name="hls" value="" id="link" aria-label="default input example"></div>
         </div>
          

           <div class="form-group row" id="upload_image">
            <label class="col-md-3 col-form-label">Upload<span class="text-danger">*</span></label>
            <div class="col-md-7"><input type="file" class="form-control mb-3" multiple name="hls[]" value="" id="hls" aria-label="default input example"></div>
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
          url: "{{ route($routeName.'.contents.get_courses') }}",
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


     $('#course_id').on('change',function() {
        
        var _token = '{{ csrf_token() }}';
        var course_id = this.value;
        // alert("category_id = "+category_id);

        $.ajax({
          url: "{{ route($routeName.'.contents.get_subjects') }}",
            type: "POST",
            data: {course_id:course_id},
            dataType:"HTML",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){

               $('#subject_id').html(resp);
           }
       });

    })


     $('#subject_id').on('change',function() {
        
        var _token = '{{ csrf_token() }}';
        var subject_id = this.value;

        $.ajax({
          url: "{{ route($routeName.'.contents.get_topics') }}",
            type: "POST",
            data: {subject_id:subject_id},
            dataType:"HTML",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){

               $('#topic_id').html(resp);
           }
       });

    })

    $('#hls_type').on('change',function() {
      
      var _token = '{{ csrf_token() }}';
      var hls_type = this.value;

      var types = $('#types');

      if(hls_type == 'notes'){          
          types.hide();
      }else{         
          types.show();
      }
    });

 $('#types').hide();


 $('#types').on('change',function() {
      
      var _token = '{{ csrf_token() }}';
      var type = $("#type").val();
      var upload_image = $("#upload_image");
      var links = $("#links");

      console.log(type);
      if(type == 'local'){          
          upload_image.show();
          links.hide();
      }else{         
          upload_image.hide();
          links.show();
      }
    });
//$("#upload_image").hide();
$("#links").hide();





</script>


