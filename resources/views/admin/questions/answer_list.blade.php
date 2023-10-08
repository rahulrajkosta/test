@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';


$right = 0;
$wrong = 0;
if(!empty($answers)){
  foreach ($answers as $key) {

    if($question->right_option == $key->option_id){
      $right+=1;
    }else{
       $wrong+=1;
    }

  }
}













?>

<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">






      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left">Answerd User List</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Answerd User List</li>
            </ol>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->


      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="card mb-3">
            <div class="card-header">

            </div>

            <div class="card-body">
              <canvas id="resultChat"></canvas>
            </div>
          </div>
          <!-- end card-->
        </div>
      </div>
















      @include('snippets.errors')
      @include('snippets.flash')
      <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="card mb-3">
            <div class="card-header">
              <h3>Answerd User List</h3>
              <span class="pull-right">
                <a href="{{ route('admin.events.questions', ['back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm">Back</a>
              </span>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">ID</th>
                     <th scope="col">User Name</th>
                     <th scope="col">Answer</th>
                     <th scope="col">Right/Wrong</th>
                     <th scope="col">Time (In Seconds)</th>
                     <th scope="col">Created At</th>



                   </tr>
                 </thead>
               </table>
             </div>
             <!-- end table-responsive-->

           </div>
           <!-- end card-body-->

         </div>
         <!-- end card-->

       </div>

     </div>
     <!-- end row-->

   </div>
   <!-- END container-fluid -->

 </div>
 <!-- END content -->

</div>
<!-- END content-page -->



@include('admin.common.footer')

<script>

  var question_id = '{{$question_id}}';


  var table = $('#dataTable').DataTable({
   ordering: false,
   processing: true,
   serverSide: true,
   ajax: '{{ route("admin.events.answered_user_list",["question_id"=>$question_id])}}',
   columns: [
   { data: 'id', name: 'id' },
   { data: 'user_name', name: 'user_name' ,searchable: false, orderable: false},
   { data: 'option', name: 'option' },
   { data: 'correct_option', name: 'correct_option' },
   { data: 'time', name: 'time' },

   { data: 'created_at', name: 'created_at' },



   ],
 });

  function change_status(userid){
    var status = $('#change_status'+userid).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.users.change_status') }}",
      type: "POST",
      data: {userid:userid, status:status},
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

  function question_ask(question_id){
    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.events.question_ask') }}",
      type: "POST",
      data: {question_id:question_id},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.success){

         $('#dataTable').DataTable().ajax.reload();


       }else{
        alert(resp.message);

      }
    }
  });



  }

</script>