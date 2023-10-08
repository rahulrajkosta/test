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
            <h1 class="main-title float-left">Exams</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Exams</li>
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
              <h3>Exams List</h3>
              <span class="pull-right">
                <a href="{{ route('admin.exams.add', ['back_url'=>$BackUrl]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus" aria-hidden="true"></i> Add New Exams</a>
              </span>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover display" style="width:100%">
                  <thead>
                    <tr>
                     <th scope="col">#</th>
                     <th scope="col">Title</th>
                     <th scope="col">Course Name</th>
                     <th scope="col">Start Date</th>
                     <th scope="col">Price</th>

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
   ajax: '{{ route("admin.exams.get_exams")}}',
   columns: [
   { data: 'id', name: 'id' },
   { data: 'title', name: 'title'},
   { data: "course_id",name: 'course_id',},
   { data: "start_date",name: 'start_date',},
   { data: "price",name: 'price',},
   { data: 'status', name: 'status' },
   { data: 'created_at', name: 'created_at' },
   { data: 'action', searchable: false, orderable: false }

   ],
 });




</script>

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