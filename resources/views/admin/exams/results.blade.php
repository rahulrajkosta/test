@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'exams/thumb/';
?>


<div class="content-page">



      <div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">{{$exam->title ?? ''}} Analysis</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
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
                     <th scope="col">User Name</th>
                     <th scope="col">Correct Answer</th>
                     <th scope="col">Wrong Answer</th>
                     <th scope="col">Skip Answer</th>
                     <th scope="col">Time(In Sec.)</th>
                     <th scope="col">Rank</th>
                     <th scope="col">Obtain Marks</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if(!empty($results)){

                    $i = 1;
                    foreach($results as $result){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                          <td><?php 
                          $user = User::where('id',$result->user_id)->first();
                            echo  $user->name ?? '';
                        ?></td>
                          <td>{{$result->correct_ans??''}}</td>
                          <td>{{$result->wrong_ans??''}}</td>
                          <td>{{$result->skipped_ans??''}}</td>
                          <td>{{$result->time_taken??''}}</td>
                          <td>{{$result->rank??''}}</td>
                          <td>{{$result-> marks??''}}</td>
                        
                        <td>



                        </td>
                      </tr>

                    <?php }}?>

                   

                 </tbody>
               
         </table>
         {{ $results->appends(request()->input())->links('admin.pagination') }}
        </div>
     </div> <!-- end card body-->
   </div> <!-- end card -->
 </div><!-- end col-->
</div>


@include('admin.common.footer')
