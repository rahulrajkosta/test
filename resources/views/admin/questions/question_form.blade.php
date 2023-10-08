@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$question_name = (isset($question->question_name))?$question->question_name:'';
$option_1 = (isset($question->option_1))?$question->option_1:'';
$option_2 = (isset($question->option_2))?$question->option_2:'';
$option_3 = (isset($question->option_3))?$question->option_3:'';
$option_4 = (isset($question->option_4))?$question->option_4:'';
$right_option = (isset($question->right_option))?$question->right_option:'';
$difficulti_level = (isset($question->difficulti_level))?$question->difficulti_level:'';
$status = (isset($question->status))?$question->status:'';


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

                            <input type="hidden" name="exam_id" value="{{$exam_id}}">
                            <input type="hidden" name="question_id" value="{{$question_id}}">


                            <div class="form-group">
                                <label for="userName">Question Name<span class="text-danger">*</span></label>

                                <textarea class="form-control" id="description" name="question_name">
                                    {{ old('question_name', $question_name) }}
                                </textarea>

                                @include('snippets.errors_first', ['param' => 'question_name'])
                            </div>




                            <div class="form-group">
                                <label for="userName">Option 1<span class="text-danger">*</span></label>

                                <input type="text" name="option_1" class="form-control" name="option_1" value="{{ old('option_1', $option_1) }}">

                                @include('snippets.errors_first', ['param' => 'option_1'])
                            </div>


                            <div class="form-group">
                                <label for="userName">Option 2<span class="text-danger">*</span></label>

                                <input type="text" name="option_2" class="form-control" name="option_2" value="{{ old('option_2', $option_2) }}">

                                @include('snippets.errors_first', ['param' => 'option_2'])
                            </div>



                            <div class="form-group">
                                <label for="userName">Option 3<span class="text-danger">*</span></label>

                                <input type="text" name="option_3" class="form-control" name="option_3" value="{{ old('option_3', $option_3) }}">

                                @include('snippets.errors_first', ['param' => 'option_3'])
                            </div>



                            <div class="form-group">
                                <label for="userName">Option 4<span class="text-danger">*</span></label>

                                <input type="text" name="option_4" class="form-control" name="option_4" value="{{ old('option_4', $option_4) }}">

                                @include('snippets.errors_first', ['param' => 'option_4'])
                            </div>



                            <div class="form-group">
                                <label for="userName">Right Option<span class="text-danger">*</span></label>

                               <select class="form-control" name="right_option">
                                   <option value="1" <?php if($right_option == 1) echo "selected"?>>Option 1</option>
                                   <option value="2" <?php if($right_option == 2) echo "selected"?>>Option 2</option>
                                   <option value="3" <?php if($right_option == 3) echo "selected"?>>Option 3</option>
                                   <option value="4" <?php if($right_option == 4) echo "selected"?>>Option 4</option>
                               </select>

                                @include('snippets.errors_first', ['param' => 'option_4'])
                            </div>


                              <div class="form-group">
                                <label for="userName">Difficulty Level<span class="text-danger">*</span></label>

                               <select class="form-control" name="difficulti_level">
                                   <option value="1" <?php if($difficulti_level == 1) echo "selected"?>>Low</option>
                                   <option value="2" <?php if($difficulti_level == 2) echo "selected"?>>Moderate</option>
                                   <option value="3" <?php if($difficulti_level == 3) echo "selected"?>>High</option>
                               </select>

                                @include('snippets.errors_first', ['param' => 'option_4'])
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
                            <a type="reset" href="#" class="btn btn-secondary m-l-5">
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