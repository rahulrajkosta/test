@include('admin.common.header')

<?php
 $BackUrl = CustomHelper::BackUrl();
 $routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 
?>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Home</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Contents</li>
              </ol>
            </nav>
          </div>
          <div class="ms-auto">
            <div class="btn-group">
              <a href="{{ route('admin.contents.add') }}" class="btn btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Add Content</a>
            </div>
          </div>
        </div>



        @include('snippets.errors')
            @include('snippets.flash')
        <!--end breadcrumb-->
        <h6 class="mb-0 text-uppercase">All Content</h6>
        <hr/>
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                  
                    <th>S.No.</th>
                    <th>HLS</th>
                    <th>Course</th>
                    <th>Subject</th> 
                    <th>Topic</th>
                    <th>Title</th>                        
                    <th>HLS Type</th>                                       
                    <th>Status</th>
                    <th>Action</th>

                  </tr>
                </thead>
                <tbody>

                @if(!empty($contents))
                @foreach($contents as $content)
              <?php
                 $course =  App\Course::select('id','course_name')->where('id',$content->course_id)->first();
                 $subject = App\Subject::select('id','subject_name')->where('id',$content->subject_id)->first();
                 $topic = App\Topic::select('id','topic_name')->where('id',$content->topic_id)->first();


              ?>
                  <tr>
                    <td>{{$content->id}}</td>
                     <td>                       
                        <?php

                               $image = isset($content->hls) ? $content->hls : '';
                                $storage = Storage::disk('public');
                                $path = 'contents';
                              if($content->hls_type == "notes"){

                                if(!empty($image))
                                {
                            ?>
                                <a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" style='width:100px;heigth:100px;'></a>
                     

                      <?php   } }else{

                          if($content->type == "youtube" || $content->type == "vimeo"){
                       ?>
                       <span>{{$content->hls ?? ''}}</span>

                     <?php }else{ ?>
                            <iframe src="{{ url('public/storage/'.$path.'/'.$image) }}" alt="gallery" class="all studio isotope-item video" target='_blank'></iframe>

                          <?php } } ?>
                     </td>
                    <td>{{$course->course_name ?? ''}}</td>
                    <td>{{$subject->subject_name ?? ''}}</td>
                    <td>{{$topic->topic_name ?? ''}}</td>
                    <td>{{$content->title ?? ''}}</td>                   
                    <td>{{$content->type ?? ''}}</td>
                    <td>{{$content->hls_type ?? ''}}</td>
                    <td>
                      
                         <a class="btn btn-success" href="{{ route($routeName.'.contents.edit', $content->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                          <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.contents.delete', $content->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                    </td>
                  </tr>
                @endforeach
                @endif               

                </tbody>
              </table>

             {{ $contents->appends(request()->input())->links('admin.pagination') }}
             
            </div>
          </div>
        </div>



      </div>
    </div>




@include('admin.common.footer')

<script>
 


  function change_course_status(id){
  var status = $('#change_course_status'+id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.subject.change_course_status') }}",
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
