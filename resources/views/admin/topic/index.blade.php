
@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'topics/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Topics</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.topics.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>Add Topic</a>
        </ol>
      </div>

    </div>
  </div>
</div>

<!-- end page title --> 
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Topics</h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>

               <th>S.No.</th>
               <th>Image</th>
               <th>Category</th>                   
               <th>Course Name</th>
               <th>Subject Name</th>                                       
               <th>Topic Name</th>                                       
               <th>Status</th>
               <th>Action</th>
             </tr>
           </thead>
           <tbody>
            <?php

            if(!empty($topics)){
              $path = 'topics/';
              foreach($topics as $b){
                $image = isset($b->image) ? $b->image : '';
                ?>   

                <tr>
                  <td>{{$b->id}}</td>
                  <td>
                   <?php 
                   if(!empty($image)){
                //echo $path.$image;
                //if($storage->exists($path.$image))
                  //{
                    ?>
                    <a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" style='width:50px;heith:50px;'></a>

                  <?php }//}
                  ?>          
                </td>
                <?
                $category = App\Category::select('id','category_name')->where('id',$b->category_id)->first();
                $course = App\Course::select('id','course_name')->where('id',$b->course_id)->first();
                $subject = App\Subject::select('id','subject_name')->where('id',$b->subject_id)->first();


                ?>
                <td>{{$category->category_name ?? ''}}</td>           

                <td>
                  {{$course->course_name ?? ''}}
                </td>
                <td>{{$subject->subject_name}}</td>
                <td>{{$b->topic_name}}</td>                 


                <td>
                  <?php
                  if($b->status == 1){ ?>
                    Active

                  <?php   }else{  ?>
                    Inactive

                  <?php } ?>

                </td>                
                <td>
                  <?php

                  $htmls = '';

                  $htmls.='<a title="Edit" class="btn btn-primary btn-sm" href="' . route($routeName.'.topics.edit',$b->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a title="Delete" class="btn btn-danger btn-sm" href="' . route($routeName.'.topics.delete',$b->id.'?back_url='.$BackUrl) . '"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;&nbsp;<a title="Content" class="btn btn-info btn-sm" href="' . route($routeName.'.topics.getcontent',$b->id.'?back_url='.$BackUrl) . '">Content</a>';
                  echo $htmls;

                  ?>    
                </td>
              </tr> 

            <?php } } ?>
          </tbody>


        </table>

        {{ $topics->appends(request()->input())->links('admin.pagination') }}
      </div>
    </div> <!-- end card body-->
  </div> <!-- end card -->
</div><!-- end col-->
</div>



@include('admin.common.footer')

<script>
 function change_course_status(id){
  var status = $('#change_course_status'+id).val();


  var _token = '{{ csrf_token() }}';

  $.ajax({
    url: "{{ route($routeName.'.courses.change_course_status') }}",
    type: "POST",
    data: {id:id, status:status},
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
