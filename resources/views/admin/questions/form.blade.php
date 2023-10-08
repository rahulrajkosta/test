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
$course_id = (isset($exams->course_id))?$exams->course_id:'';
$description = (isset($exams->description))?$exams->description:'';
$price = (isset($exams->price))?$exams->price:'';
$status = (isset($exams->status))?$exams->status:'';
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'course/thumb/';


// pr($storage);
?>



<div class="content-page">

    <!-- Start content -->
    <div class="content">

        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-holder">
                        <h1 class="main-title float-left">{{ $page_heading }}</h1>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active">{{ $page_heading }}</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->


            @include('snippets.errors')
            @include('snippets.flash')


            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3><i class="far fa-hand-pointer"></i>{{ $page_heading }}</h3>

                            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                            <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
                        </div>

                        <div class="card-body">

                         <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{$exams_id}}">



                            <div class="form-group">
                                <label for="userName">Choose Course<span class="text-danger">*</span></label>
                                <select name="course_id" class="form-control select2">
                                    <option value="" selected disabled>Select Course Name</option>
                                    <?php if(!empty($courses)){
                                        foreach($courses as $course){
                                            ?>

                                            <option value="{{$course->id}}" <?php if($course->id == $course_id) echo "selected"?>>{{$course->name}}</option>
                                        <?php }}?>
                                    </select>

                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>





                                <div class="form-group">
                                    <label for="userName">Title<span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255" placeholder="Enter Title" />

                                    @include('snippets.errors_first', ['param' => 'title'])
                                </div>



                                <div class="form-group">
                                    <label for="userName">Start Date<span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" value="{{ old('start_date', $start_date) }}" id="start_date" class="form-control"  maxlength="255"/>

                                    @include('snippets.errors_first', ['param' => 'start_date'])
                                </div>



                                <div class="form-group">
                                    <label for="userName">End Date<span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" value="{{ old('end_date', $end_date) }}" id="end_date" class="form-control"  maxlength="255" />

                                    @include('snippets.errors_first', ['param' => 'end_date'])
                                </div>

                                
                                <div class="form-group">
                                    <label for="userName">Start Time<span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" value="{{ old('start_time', $start_time) }}" id="start_time" class="form-control"  maxlength="255"/>

                                    @include('snippets.errors_first', ['param' => 'start_time'])
                                </div>



                                <div class="form-group">
                                    <label for="userName">End Time<span class="text-danger">*</span></label>
                                    <input type="time" name="end_time" value="{{ old('end_time', $end_time) }}" id="end_time" class="form-control"  maxlength="255" />

                                    @include('snippets.errors_first', ['param' => 'end_time'])
                                </div>

                                
                                <div class="form-group">
                                    <label for="userName">Description<span class="text-danger">*</span></label>
                                    <!-- <input type="time" name="end_time" value="{{ old('end_time', $end_time) }}" id="end_time" class="form-control"  maxlength="255" /> -->
                                    <textarea id="description" name="description" class="form-control">{{old('description', $description)}}</textarea>

                                    @include('snippets.errors_first', ['param' => 'description'])
                                </div>

                                <div class="form-group">
                                    <label for="userName">Price<span class="text-danger">*</span></label>
                                    <input type="text" name="price" value="{{ old('price', $price) }}" id="price" class="form-control"  maxlength="255" />

                                    @include('snippets.errors_first', ['param' => 'price'])
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
                                <a type="reset" href="{{ route('admin.course.index') }}" class="btn btn-secondary m-l-5">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div><!-- end card-->
            </div>






            <script type="text/javascript">

            </script>

            @include('admin.common.footer')
            <script>
                CKEDITOR.replace( 'description' );
            </script>