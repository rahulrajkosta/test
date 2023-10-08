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
      <h4 class="mb-0">Exams</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.exams.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Exams</a>
        </ol>
      </div>

    </div>
  </div>
</div>

<!-- end row -->
@include('snippets.errors')
@include('snippets.flash')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Exams</h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>
               <th scope="col">#</th>
               <th scope="col">Title</th>
               <th scope="col">Exam Type</th>
               <th scope="col">Exam Type Wise</th>
               <th scope="col">Category Name</th>
               <th scope="col">Course Name</th>              
              
               <th scope="col">Start Date</th>
               <th scope="col">No of Questions</th>
               <th scope="col">Status</th>
               <th scope="col">Date Created</th>
               <th scope="col">Action</th>
             </tr>
           </thead>

           <tbody>
            <?php if(!empty($exams)){

              $i = 1;
              foreach($exams as $exam){
                ?>
                <tr>
                  <td>{{$i++}}</td>
                  <td>{{$exam->title??''}}</td>
                  <td><?php
                  if($exam->type == 'test'){
                    echo "Test Series";

                  }else{
                    echo "Quiz";
                  }

                ?></td>
                <td><?php
                if($exam->key == 'course'){
                  echo "Course Wise";

                }else{
                  echo "Topic Wise";
                }

              ?></td>
              <td>{{$exam->category_name->category_name??''}}</td>
              <td>{{$exam->course_name->course_name??''}}</td>
              <td>{{$exam->start_date??''}}</td>
              <td>{{$exam-> no_of_questions??''}}</td>

              <td>
                <select id='change_status{{$exam->id}}' onchange='change_status({{$exam->id}})' class="form-control">
                  <option value='1' <?php if($exam->status ==1)echo "selected";?> >Active</option>
                  <option value='0' <?php if($exam->status ==0)echo "selected";?>>InActive</option>
                </select> 


              </td>
              <td>{{date('d M Y',strtotime($exam->created_at))}}</td>

              <td>
               <div class="d-flex">
                <a class="btn btn-success" href="{{ route($routeName.'.exams.edit', $exam->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.exams.delete', $exam->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>
                <br>
                &nbsp;&nbsp;&nbsp;

                <a href="{{ route($routeName.'.exams.import', $exam->id.'?back_url='.$BackUrl) }}" class="btn btn-success"><i class="fa fa-question-circle"></i></a> &nbsp;&nbsp;&nbsp;


               

                 <!-- <a href="{{ route($routeName.'.exams.results', $exam->id.'?back_url='.$BackUrl) }}" class="btn btn-success"><i class="fas fa-list-alt"></i></a> -->
                &nbsp;&nbsp;&nbsp;



              </div>
            </td>
          </tr>
        <?php }}?>
      </tbody>



    </table>
    {{ $exams->appends(request()->input())->links('admin.pagination') }}
  </div>
</div> <!-- end card body-->
</div> <!-- end card -->
</div><!-- end col-->
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
</script>