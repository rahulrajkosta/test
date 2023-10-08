@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>
<style>
  
  .dataTables_wrapper{

    overflow: hidden !important;
  }


/*
.news_ans{
  white-space: normal !important;
}*/
</style>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">News</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.news.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add News</a>
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
        <h4 class="header-title">News</h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Title</th>
                <th>Image</th>
                <th>Description</th>                 
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($news)){

                $i = 1;
                foreach($news as $new){
                  $title =  mb_strlen(strip_tags($new->title),'utf-8') > 50 ? mb_substr(strip_tags($new->title),0,50,'utf-8').'...' : strip_tags($new->title);
                  $description =  mb_strlen(strip_tags($new->description),'utf-8') > 50 ? mb_substr(strip_tags($new->description),0,50,'utf-8').'...' : strip_tags($new->description);
                  ?>
                  <tr>
                    <td>{{$i++}}</td>
                    <td>{{$title}}</td>
                    <td>
                      
                      <?php 
                      $image = $new->image;
                      $storage = Storage::disk('public');

                      $path = 'news/';


                      if(!empty($image)){
                        if($storage->exists($path.$image)){
                          ?>
                          <div class=" image_box" style="display: inline-block">
                            <a href="{{ url('public/storage/'.$path.$image) }}" target="_blank">
                              <img src="{{ url('public/storage/'.$path.'thumb/'.$image) }}" style="width:70px;">
                            </a>
                            <br>
                          </div>
                        <?php } }?>

                      </td>
                      <td>{{$description}}</td>
                      <td>
                        <select id='change_news_status{{$new->id}}' onchange='change_news_status({{$new->id}})' class="form-control">
                          <option value='1' <?php if($new->status ==1)echo "selected";?> >Active</option>
                          <option value='0' <?php if($new->status ==0)echo "selected";?>>InActive</option>
                        </select> 


                      </td>

                      <td>
                       
                        <a class="btn btn-success" href="{{ route($routeName.'.news.edit', $new->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                        <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.news.delete', $new->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                      </td>
                    </tr>
                  <?php }}?>
                </tbody>


              </table>

              {{ $news->appends(request()->input())->links('admin.pagination') }}
            </div>
          </div> <!-- end card body-->
        </div> <!-- end card -->
      </div><!-- end col-->
    </div>


    @include('admin.common.footer')

    <script>
     

      function change_news_status(news_id){
        var status = $('#change_news_status'+news_id).val();


        var _token = '{{ csrf_token() }}';

        $.ajax({
          url: "{{ route($routeName.'.news.change_news_status') }}",
          type: "POST",
          data: {news_id:news_id, status:status},
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
