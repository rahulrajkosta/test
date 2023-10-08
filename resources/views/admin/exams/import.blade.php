<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
  .select2-container{
    width: 80% !important;
  }
  .select2-container .select2-selection--multiple .select2-selection__rendered{
    display: flex !important;
    margin: 0px !important;
  }
</style>



@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'exams/thumb/';
?>



<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Exams Questions List</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">

        </ol>
      </div>

    </div>
  </div>
</div>



@include('snippets.errors')
@include('snippets.flash')


<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">


     <div class="card-header  d-flex">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <h3>Import Questions</h3>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">

      </div>
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <a href="{{ url('public/assets/krantikariexamquestion.xlsx') }}" class="btn btn-success">Sample</a>
      </div>
    </div>



    <form method="POST" action="" enctype="multipart/form-data">
      {{csrf_field()}}
      <div class="card-body d-flex">
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <input type="file" name="importfile" value=""  class="form-control">
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-1">
       <button class="btn btn-success" style="margin-left: 35px;">Submit</button>

     </div>

   </div>

 </div>
</form>

</div>

</div>


<div class="col-xl-12">
      <div class="card mb-0">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#exam_question_list" role="tab" aria-selected="true">
              <i class="bx bx-user-circle font-size-20"></i>
              <span class="d-none d-sm-block">EXAM QUESTION LIST</span> 
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link"  data-bs-toggle="tab" href="#allocate_exam" role="tab" aria-selected="false">
              <i class="fas fa-credit-card"></i>

              <span class="d-none d-sm-block">ALLOCATE EXAM</span> 
            </a>
          </li>

        
        </ul>

        <div class="tab-content p-4">

          <!--  ///////////////////////////// EXAM QUESTION LIST //////////////////////////////-->

            <div class="tab-pane active" id="exam_question_list" role="tabpanel">
            <div>
              <div>
              <div class="d-flex">
                <h5 class="font-size-16 mb-4">EXAM QUESTION LIST</h5>
                      

                  <a href="{{ route('admin.exams.add_question',['exam_id'=>$exam_id, 'back_url'=>$routeName.'/exams/import/'.$exam_id]) }}" class="btn mb-3 btn-primary btn-sm" style="margin-left: auto;"><i class="fas fa-user-plus ml-3" aria-hidden="true"></i> Add New Question</a>
                </div>
              

                <div class="col-md-12 col-lg-12">
                    <div class="card mb-3 bg-transparent">
                            <div class="card border-0 mb-1">

                                <div class="card-body d-flex  flex-column flex-md-row">

                                     <div class="table-responsive  w-100">
                                        <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                                          <thead>
                                            <tr>
                                             <th scope="col">#</th>
                                             <th scope="col">Question Title</th>
                                             <th scope="col">Option 1</th>
                                             <th scope="col">Option 2</th>
                                             <th scope="col">Option 3</th>
                                             <th scope="col">Option 4</th>
                                             <th scope="col">Right Option</th>
                                             <th scope="col">Difficulti Level</th>
                                             <th scope="col">Status</th>
                                             <th scope="col">Date Created</th>
                                             <th scope="col">Action</th>
                                           </tr>
                                         </thead>


                                         <tbody>


                                          <?php

          

                                           if(!empty($allquestions)){
                                            

                                            $i = 1;
                                            foreach($allquestions as $question){

                                              $question_name =  mb_strlen(strip_tags($question->question_name),'utf-8') > 50 ? mb_substr(strip_tags($question->question_name),0,50,'utf-8').'...' : strip_tags($question->question_name);

                                              $option_1 =  mb_strlen(strip_tags($question->option_1),'utf-8') > 50 ? mb_substr(strip_tags($question->option_1),0,50,'utf-8').'...' : strip_tags($question->option_1);

                                              $option_2 =  mb_strlen(strip_tags($question->option_2),'utf-8') > 50 ? mb_substr(strip_tags($question->option_2),0,50,'utf-8').'...' : strip_tags($question->option_2);

                                              $option_3 =  mb_strlen(strip_tags($question->option_3),'utf-8') > 50 ? mb_substr(strip_tags($question->option_3),0,50,'utf-8').'...' : strip_tags($question->option_3);

                                              $option_4 =  mb_strlen(strip_tags($question->option_4),'utf-8') > 50 ? mb_substr(strip_tags($question->option_4),0,50,'utf-8').'...' : strip_tags($question->option_4);



                                              ?>
                                              <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$question_name??''}}</td>
                                                <td>{{$option_1??''}}</td>
                                                <td>{{$option_2??''}}</td>
                                                <td>{{$option_3??''}}</td>
                                                <td>{{$option_4??''}}</td>
                                                <td>{{$question->right_option??''}}</td>
                                                <td>{{$question->difficulti_level??''}}</td>


                                                <td>
                                                  <select id='change_status{{$question->id}}' onchange='change_status({{$question->id}})' class="form-control">
                                                    <option value='1' <?php if($question->status ==1)echo "selected";?> >Active</option>
                                                    <option value='0' <?php if($question->status ==0)echo "selected";?>>InActive</option>
                                                  </select> 


                                                </td>
                                                <td>{{date('d M Y',strtotime($question->created_at))}}</td>

                                                <td>
                                                 <div class="d-flex">
                                                  <a class="btn btn-success" href="{{ route($routeName.'.exams.edit_question', $question->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;


                                                </div>
                                              </td>
                                            </tr>
                                          <?php }}?>
                                         </tbody>
                                      </table>
                                        {{ $allquestions->appends(request()->input())->links('admin.pagination') }}
                                    </div>

                                </div>


                            </div>
                    </div>

                </div>


              </div>           

            </div>
          </div>



          <!--  ///////////////////////////// COMPLETED CLASS //////////////////////////////-->


            <div class="tab-pane" id="allocate_exam" role="tabpanel">
            <div>
              <div>
                <h5 class="font-size-16 mb-4">ALLOCATE EXAM</h5>
              <div class="d-flex">
                <form action="{{route('admin.exams.allocate_exam')}}" method="post" style="width: 100%;display: flex;">
                  @csrf
                  <input type="hidden" name="exam_id" value="{{$exam_id}}">



                  <select class="js-example-basic-multiple" name="course_id[]" multiple="multiple">
                    <option>Select Course</option>
                     <?php 
                  
                    if(!empty($get_course)){ 

                      foreach($get_course as $course){
                     ?>
                    <option value="{{$course->id}}">{{$course->course_name ?? ''}}</option>
                    <?php } } ?>
                  </select>

                        <!-- 
                  <select class="form-select" multiple name="course_id[]" id="choices-multiple-groups">
                    <option>Select Course</option>
                  <?php 
                  
                  if(!empty($get_course)){ 

                      foreach($get_course as $course){
                  ?>
                    <option value="{{$course->id}}">{{$course->course_name ?? ''}}</option>
                  <?php } } ?>
                  </select> -->
               
                  <button type="submit" class="btn btn-info" style="margin-left: auto;">Submit</button>
                </form>
              <!-- </div> -->


              </div>           

            </div>
          </div>
          <hr>

        <div class="table-responsive  w-100">
          <table id="dataTable" class="table table-bordered table-hover display">
            <thead>
              <th><b>S.No</b></th>
              <th><b>Exam</b></th>
              <th><b>Allocated Course</b></th>             
            </thead>
            <tbody>
              <?php if(!empty($allocated_exam)){ 

                foreach($allocated_exam as $allocate){
                    $course_name = App\Course::select('id','course_name')->where('id',$allocate->course_id)->first();                  
                  $exam_name = App\Exam::where('id',$allocate->exam_id)->first();

              ?>
              <tr>
                <td></td>
              <td>{{$exam_name->title ?? ''}}</td>
              <td>{{$course_name->course_name ?? ''}}</td>
            </tr>
            <?php } } ?>
              
            </tbody>
          </table>
        </div>

        
         


        </div>






      </div>
</div>



@include('admin.common.footer')


<script>
  function change_status(exam_id){
    var status = $('#change_status'+exam_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.exams.change_status') }}",
      type: "POST",
      data: {exam_id:exam_id, status:status},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.success){
          alert(resp.message);
        }else{
          alert(resp.message);

        }
      }
    });


  }









//In your Javascript (external .js resource or <script> tag):

  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>