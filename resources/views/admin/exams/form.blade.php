@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$exams_id = (isset($exams->id))?$exams->id:'';
$title = (isset($exams->title))?$exams->title:'';
$start_date = (isset($exams->start_date))?$exams->start_date:'';
$end_date = (isset($exams->end_date))?$exams->end_date:'';
$start_time = (isset($exams->start_time))?$exams->start_time:'';
$end_time = (isset($exams->end_time))?$exams->end_time:'';
$category_id = (isset($exams->category_id))?$exams->category_id:'';
$course_id = (isset($exams->course_id))?$exams->course_id:'';
$subject_id = (isset($exams->subject_id))?$exams->subject_id:'';
$topic_id = (isset($exams->topic_id))?$exams->topic_id:'';
$key = (isset($exams->key))?$exams->key:'';
$type = (isset($exams->type))?$exams->type:'';



$description = (isset($exams->description))?$exams->description:'';
$price = (isset($exams->price))?$exams->price:'';
$status = (isset($exams->status))?$exams->status:'';
$time_per_question = (isset($exams->time_per_question))?$exams->time_per_question:'';
$no_of_questions = (isset($exams->no_of_questions))?$exams->no_of_questions:'';
$marks_per_question = (isset($exams->marks_per_question))?$exams->marks_per_question:'';
$negetive_mark = (isset($exams->negetive_mark))?$exams->negetive_mark:'';


$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'course/thumb/';
?>



<div class="content-page">



    <div class="row">
      <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
          <h4 class="mb-0">{{ $page_heading }}</h4>
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
          <h4 class="header-title">{{ $page_heading }}</h4>
          <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <input type="hidden" name="id" value="{{$exams_id}}">

                <div class="form-group">
                <label for="userName">Choose Exam Type<span class="text-danger">*</span></label>
                <select name="type" class="form-control select2" id="type">
                    <option value="" selected disabled>Select Type</option>
                    <option value="test" <?php if($type == 'test')echo "selected"?>>Test Series</option>
                    <option value="quiz" <?php if($type == 'quiz')echo "selected"?>>Quiz</option>
                    </select>
                    @include('snippets.errors_first', ['param' => 'type'])
                </div>



                    <div class="row">
                        <div class="col-md-4">
                             <div class="form-group">
                            <label for="userName">Choose Exam Type Wise<span class="text-danger">*</span></label>
                            <select name="key" class="form-control select2" id="key">
                                <option value="" selected disabled>Select key</option>
                                <option value="topic" <?php if($key == 'topic')echo "selected"?>>Topic</option>
                                <option value="course" <?php if($key == 'course')echo "selected"?>>Course</option>
                                </select>
                                @include('snippets.errors_first', ['param' => 'key'])
                            </div>
                            
                        </div>

                         <div class="col-md-4">
                              <div class="form-group">
                                <label for="userName">Choose Category<span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control select2" id="category_id">
                                    <option value="" selected disabled>Select Category Name</option>
                                    <?php if(!empty($categories)){
                                        foreach($categories as $cat){
                                            ?>
                                            <option value="{{$cat->id}}" <?php if($cat->id == $category_id) echo "selected"?>>{{$cat->category_name}}</option>
                                        <?php }}?>
                                    </select>
                                    @include('snippets.errors_first', ['param' => 'category_id'])
                                </div>
                            
                        </div>


                        <div class="col-md-4">
                            
                             <div class="form-group">
                            <label for="userName">Choose Course<span class="text-danger">*</span></label>
                            <select name="course_id" class="form-control select2" id="course_id">
                                <option value="" selected disabled>Select Course Name</option>
                                <?php if(!empty($courses)){
                                    foreach($courses as $course){
                                        ?>
                                        <option value="{{$course->id}}" <?php if($course->id == $course_id) echo "selected"?>>{{$course->course_name}}</option>
                                    <?php }}?>
                                </select>
                                @include('snippets.errors_first', ['param' => 'course_id'])
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            
                            
                        </div>

                       

                       <!--  <div class="col-md-6">
                               <div class="form-group">
                                    <label for="userName">Choose Topic<span class="text-danger">*</span></label>
                                    <select name="topic_id" class="form-control select2" id="topic_id">
                                        <option value="" selected disabled>Select Topic/Chapter Name</option>
                                        <?php if(!empty($topics)){
                                            foreach($topics as $topic){
                                                ?>
                                                <option value="{{$topic->id}}" <?php if($topic->id == $topic_id) echo "selected"?>>{{$topic->topic_name}}</option>
                                            <?php }}?>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'topic_id'])
                                    </div>
                            
                        </div> -->
                    </div>



                            <div class="form-group">
                                <label for="userName">Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255" placeholder="Enter Title" />

                                @include('snippets.errors_first', ['param' => 'title'])
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="userName">Start Date<span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" value="{{ old('start_date', $start_date) }}" id="start_date" class="form-control"  maxlength="255"/>

                                        @include('snippets.errors_first', ['param' => 'start_date'])
                                    </div>                                    
                                </div>

                                <div class="col-md-6">
                                     <div class="form-group">
                                    <label for="userName">Start Time<span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" value="{{ old('start_time', $start_time) }}" id="start_time" class="form-control"  maxlength="255"/>

                                    @include('snippets.errors_first', ['param' => 'start_time'])
                                </div>
                                    
                                </div>
                            </div>


                                <div class="form-group">
                                    <label for="userName">Description<span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" class="form-control">{{old('description', $description)}}</textarea>

                                    @include('snippets.errors_first', ['param' => 'description'])
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group">
                                            <label for="userName">No of Question<span class="text-danger">*</span></label>
                                            <input type="text" name="no_of_questions" value="{{ old('no_of_questions', $no_of_questions) }}" id="no_of_questions" class="form-control" placeholder="No of Question"  maxlength="255" />

                                            @include('snippets.errors_first', ['param' => 'no_of_questions'])
                                        </div>
                                        
                                    </div>

                                    <div class="col-md-6">
                                           <div class="form-group">
                                            <label for="userName">Total Time(In Minute)<span class="text-danger">*</span></label>
                                            <input type="text" name="time_per_question" value="{{ old('time_per_question', $time_per_question) }}" id="time_per_question" class="form-control" placeholder="Time Per Question(In Second)"  maxlength="255" />

                                            @include('snippets.errors_first', ['param' => 'time_per_question'])
                                        </div>

                                        
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group">
                                            <label for="userName">Marks Per Question<span class="text-danger">*</span></label>
                                            <input type="text" name="marks_per_question" value="{{ old('marks_per_question', $marks_per_question) }}" id="marks_per_question" class="form-control" placeholder="Marks Per Question"  maxlength="255" />

                                            @include('snippets.errors_first', ['param' => 'marks_per_question'])
                                        </div>

                                        
                                    </div>

                                    <div class="col-md-6">
                                         <div class="form-group">
                                            <label for="userName">Negetive Mark Per Question<span class="text-danger">*</span></label>
                                            <input type="text" name="negetive_mark" value="{{ old('negetive_mark', $negetive_mark) }}" id="negetive_mark" class="form-control" placeholder="Negetive Mark Per Question"  maxlength="255" />

                                            @include('snippets.errors_first', ['param' => 'negetive_mark'])
                                        </div>
                                        
                                    </div>
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



                             <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary" type="submit">
                                    Submit
                                </button>
                            </div>

                        </form>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>

        @include('admin.common.footer')
        <script>
            CKEDITOR.replace( 'description' );
        </script>


        <script type="text/javascript">
           $('#category_id').on('change',function() {

            var _token = '{{ csrf_token() }}';
            var category_id = this.value;
            $.ajax({
              url: "{{ route($routeName.'.subject.get_courses') }}",
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
        // alert("course_id = "+course_id);

        $.ajax({
          url: "{{ route($routeName.'.topics.get_subject') }}",
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
        // alert("subject_id = "+subject_id);

        $.ajax({
          url: "{{ route($routeName.'.topics.get_topics') }}",
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

</script>