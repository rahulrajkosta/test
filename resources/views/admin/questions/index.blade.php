@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'exams/thumb/';
?>


<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left"> Questions List</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active"> Questions List</li>
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


           <div class="card-header  d-flex">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
              <h3>Import Questions</h3>
            </div>
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">

             </div>
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
            <a href="{{ url('public/assets/uploads/stpaulquestionsample.xlsx') }}" class="btn btn-success">Sample</a>
            </div>
          </div>



          <form method="POST" action="{{route('admin.questions.import')}}" enctype="multipart/form-data">
            {{csrf_field()}}
              <div class="card-body d-flex">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
            <input type="file" name="importfile" value=""  class="form-control">
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-1">
             <button class="btn btn-success">Submit</button>

           </div>

           </div>

         </div>
         </form>

       </div>

     </div>
 
















      <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="card mb-3">
            <div class="card-header">
              <h3>Exams Question List</h3>
              <span class="pull-right">
                <a href="{{ route('admin.questions.add_question',['back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New Question</a>
              </span>
            </div>

            <div class="card-body">
              <div class="table-responsive">
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
  var i = 1;

  var table = $('#dataTable').DataTable({
   ordering: false,
   processing: true,
   serverSide: true,
   ajax: '{{ route("admin.questions.get_questions")}}',
   columns: [
   { data: 'id', name: 'id' },
   { data: 'question_name', name: 'question_name'},
   { data: "option_1",name: 'option_1',},
   { data: "option_2",name: 'option_2',},
   { data: "option_3",name: 'option_3',},
   { data: "option_4",name: 'option_4',},
   { data: "right_option",name: 'right_option',},
   { data: "difficulti_level",name: 'difficulti_level',},
   { data: 'status', name: 'status' },
   { data: 'created_at', name: 'created_at' },
   { data: 'action', searchable: false, orderable: false }

   ],
 });




</script>

<script>
function change_status(que_id){
  var status = $('#change_status'+que_id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.questions.change_status') }}",
                type: "POST",
                data: {que_id:que_id, status:status},
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