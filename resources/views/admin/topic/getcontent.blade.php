@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 
?>

<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <h4 class="mb-0">All Contents List ({{$topics->topic_name ?? ''}})</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
              <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php }else{?>

                 <a href="{{ url('admin/topics')}}" class="btn btn-info btn-sm" style='float: right;'>Back</a>

              <?php }?>
              &nbsp;&nbsp;&nbsp;

              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Content</button>
          </ol>
      </div>



  </div>
</div>
</div>




<!-- Standard modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Content</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

                {{ csrf_field() }}
                <input type="hidden" name="topic_id" value={{$topic_id}}>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10"><input type="text" class="form-control form-control-rounded mb-3" name="title" id="title"  value="" required placeholder="Enter Title" aria-label="default input example"></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Content Type</label>
                        <div class="col-md-10">
                            <select class="form-control mb-3" name="hls_type" id="hls_type" >
                                <option value="" selected disabled>Select Type</option>
                                <option value="notes">Notes</option>
                                <option value="video">Video</option>

                            </select>
                        </div>
                    </div>


                    <div class="form-group row" id="file_type">
                        <label class="col-md-2 col-form-label"> Type</label>
                        <div class="col-md-10">
                            <select class="form-control mb-3" name="type" id="type" >
                                <option value="" selected disabled>Select Type</option>
                                <option value="local">Local</option>
                                <option value="youtube">Youtube</option>
                                <option value="vimeo">Vimeo</option>

                            </select>
                        </div>
                    </div>


                    <div class="form-group row" id="file_upload_txt">
                        <label class="col-sm-2 col-form-label" >Enter Video Id</label>
                        <div class="col-sm-10"><input type="text" class="form-control form-control-rounded mb-3" name="hls" aria-label="default input example"></div>
                    </div>


                    <div class="form-group row" id="file_upload">
                        <label class="col-sm-2 col-form-label" >Upload File</label>
                        <div class="col-sm-10"><input type="file" class="form-control form-control-rounded mb-3" name="hls" aria-label="default input example"></div>
                    </div>


                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label" >Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description"></textarea>
                    </div>
                </div>





                </div>







                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->















<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">All Video List({{ $videos->total() }})</h4>
                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Video</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    $storage = Storage::disk('public');
                    $path = 'subjects/contents/';
                    if(!empty($videos)){
                        foreach($videos as $video){
                            ?>
                            <tr>

                                <td>{{$video->id}}</td>
                                <td>{{$video->title}}</td>
                                <td>
                                    <?php
                                    $image_name = $video->hls;
                                    if($storage->exists($path.$image_name)){
                                        ?>
                                        <video width='400' height='200' controls>
                                            <source src="{{ url('public/storage/'.$path.$image_name) }}" type='video/mp4'>
                                            </video>

                                        <?php }?>



                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm" href="{{ route($routeName.'.topics.delete_content', $video->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i>
                                        </a>
                                    </td>

                                </tr>


                            <?php } }?>


                        </table>
                        {{ $videos->appends(['videos' => $videos->currentPage()])->links('admin.pagination') }}


                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>



        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">All Notes List({{ $notes->total() }})</h4>
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Notes</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            $storage = Storage::disk('public');
                            $path = 'topics/contents/';
                            if(!empty($notes)){
                                foreach($notes as $note){
                                    ?>

                                    <tr>
                                        <td>{{$note->id}}</td>
                                        <td>{{$note->title}}</td>
                                        <td>
                                            <?php
                                            $image_name = $note->hls;
                                            if($storage->exists($path.$image_name)){
                                                ?>
                                                <a href="{{ url('storage/app/public/'.$path.$image_name) }}" target="_blank">
                                                    View PDF
                                                </a>

                                            <?php }?>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-sm" href="{{ route($routeName.'.topics.delete_content', $note->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i>
                                            </a>
                                        </td>

                                    </tr>
                                <?php }}?>


                            </table>

                            {{ $notes->appends(['notes' => $notes->currentPage()])->links('admin.pagination') }}

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>


            @include('admin.common.footer')



            <script type="text/javascript">
                $( document ).ready(function() {
                 $('#file_type').hide();
                 $('#file_upload').hide();
                 $('#file_upload_txt').hide();
             });

                $('#hls_type').on('change', function() {
                    var hls_type = this.value;
                    if(hls_type=='notes'){
                        $('#file_type').hide();
                        $('#file_upload_txt').hide();

                        $('#file_upload').show();

                    }
                    else{
                        $('#file_upload').hide();
                        $('#file_upload_txt').hide();

                        $('#file_type').show();
                    }
                });

                $('#type').on('change', function() {
                    var type = this.value;
                    if(type == 'local'){
                        $('#file_upload_txt').hide();
                        $('#file_upload').show();
                    }
                    if(type == 'youtube'){
                        $('#file_upload').hide();
                        $('#file_upload_txt').show();
                    }
                    if(type == 'vimeo'){
                        $('#file_upload').hide();
                        $('#file_upload_txt').show();
                    }


                });


            </script>