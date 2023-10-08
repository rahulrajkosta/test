@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();

$course_id = isset($courses->id) ? $courses->id : '';
$category_id = isset($courses->category_id) ? $courses->category_id : '';
$course_name = isset($courses->course_name) ? $courses->course_name : '';
$course_description = isset($courses->course_description) ? $courses->course_description : '';
$image = isset($courses->image) ? $courses->image : '';

$type = isset($courses->type) ? $courses->type : '';
$start_date = isset($courses->start_date) ? $courses->start_date : '';
$duration = isset($courses->duration) ? $courses->duration : '';
$monthly_month = isset($courses->monthly_amount) ? $courses->monthly_amount : '';
$full_amount = isset($courses->full_amount) ? $courses->full_amount : '';
$emi_available = isset($courses->emi_available) ? $courses->emi_available : '';
$syllabus = isset($courses->syllabus) ? $courses->syllabus : '';
$status = isset($courses->status) ? $courses->status : '1';

$storage = Storage::disk('public');

$path = 'courses/';


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

                 <input type="hidden" value="{{ $course_id }}">

                 <div class="row">
                    <div class="col-md-6">
                          <div class="mb-3">
                          <label for="fullname" class="form-label">Category :</label>
                               <select id="category_id" name="category_id" class="form-control mb-3">
                            <option value="" selected disabled>Select Category</option>
                            <?php   if(!empty($category)){
                                   foreach($category as $cat){ ?>
                            <option value="{{$cat->id}}" <?php if($category_id == $cat->id) echo "selected";?>>{{$cat->category_name}}</option>
                            <?php } }?>
                           
                          </select>

                        </div> 
                      
                    </div>

                    <div class="col-md-6">
                         <div class="mb-3">
                          <label for="fullname" class="form-label">Course Name</label>
                           <input class="form-control mb-3" type="text" name="course_name" id="course_name"  value="{{ old('course_name',$course_name) }}" placeholder="Course Name" aria-label="default input example">

                        </div>                      
                    </div>
                   
                 </div>


                

               

                <div class="mb-3">
                  <label for="email" class="form-label">Course Description</label>
                 <textarea class="form-control mb-3" name="course_description" id="course_description"  placeholder="Write Category Description .........." aria-label="default input example">{{ old('course_description',$course_description) }}</textarea>
                </div>

              
                <div class="row">
                    <div class="col-md-6">
                         <div class="mb-3">
                          <label for="fullname" class="form-label">Start Date</label>
                           <input class="form-control mb-3" type="date" name="start_date" value="{{ old('start_date', $start_date) }}" id="start_date" placeholder="Start Date" aria-label="default input example">

                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                        <label for="fullname" class="form-label">Course Duration (in months)</label>
                         <input class="form-control mb-3" type="number" name="duration" id="duration" value="{{ old('duration',$duration) }}" placeholder="Course Duration" aria-label="default input example">

                      </div>
                    </div>
                </div>
                

                
                <div class="row">
                    <div class="col-md-4">

                        <div class="mb-3">
                        <label for="fullname" class="form-label">Total Fee</label>
                         <input class="form-control mb-3" type="number" name="full_amount" value="{{ old('full_amount',$full_amount) }}" id="full_amount" placeholder="Enter Monthly Amount" aria-label="default input example">
                      </div>
                    </div>

                      <div class="col-md-4">

                        <div class="mb-3">
                        <label for="fullname" class="form-label">Emi Available</label>
                          <select class="form-control mb-3" type="number" name="emi_available" value="{{ old('emi_available',$emi_available) }}" id="emi_available" >
                                 <option>Select Emi Type</option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                          </select>
                      </div>
                    </div>

                     <div class="col-md-4">
                              <div class="mb-3">
                              <label for="type" class="form-label">Type</label>
                               <select class="form-control mb-3" type="number" name="type" value="{{ old('type',$type) }}" id="type" >
                                 <option>Select Type</option>
                                 <option value="pre-recorded">Pre-Recorded</option>
                                 <option value="live">Live</option>
                               </select>
                               </div>                    
                      </div>                
               
                </div>
                 


                 <div class="mb-3">
                  <label for="fullname" class="form-label">Syllabus</label>
                   <textarea class="form-control mb-3" name="syllabus" id="syllabus"  placeholder="Write Syllabus Description .........." aria-label="default input example">{{ old('syllabus',$syllabus) }}</textarea>

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

<script type="text/javascript">
  CKEDITOR.replace('course_description');
  CKEDITOR.replace('description');
</script>