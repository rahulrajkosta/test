@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'category/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Categories</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.categories.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Category</a>
        </ol>
      </div>

    </div>
  </div>
</div>



<div class="row">
  <div class="col-6">
  </div>
  <div class="col-6">
    <div class="card">
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Search By Category Name</label>
          <form>
            <div class="input-group">
              <input type="text" name="search" value="{{$_GET['search'] ?? ''}}" class="form-control" placeholder="Search...." aria-label="Recipient's username">
              <button class="btn input-group-text btn-dark waves-effect waves-light" type="submit">Search</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>








<!-- end page title --> 
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title"></h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Category Name</th>
                <th>Business Type</th>
                <th>Priority</th>

                <th>Category Image</th>

                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($categories)){

                $i = 1;
                foreach($categories as $cat){
                  ?>
                  <tr>
                    <td>{{$i++}}</td>
                    <td>{{$cat->name ?? ''}}</td>
                    <td>{{$cat->type ?? ''}}</td>
                    <td>{{$cat->priority ?? ''}}</td>


                    <td>
                     <?php
                     if(!empty($cat->image)){
                        $image_name = $cat->image;
                        if($storage->exists($path.$image_name)){
                          ?>
                          <div class=" image_box" style="display: inline-block">
                            <a href="{{ url('public/storage/'.$path.$image_name) }}" target="_blank">
                              <img src="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" style="width:70px;">
                            </a>
                          </div>
                          <?php
                        }
                    }
                    ?>
                  </td>

                  <td>
                    <select id='change_category_status{{$cat->id}}' onchange='change_category_status({{$cat->id}})' class="form-control">
                      <option value='1' <?php if($cat->status ==1)echo "selected";?> >Active</option>
                      <option value='0' <?php if($cat->status ==0)echo "selected";?>>InActive</option>
                    </select> 


                  </td>

                  <td>

                    <a class="btn btn-success" href="{{ route($routeName.'.categories.edit', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                    <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.categories.delete', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                  </td>
                </tr>
              <?php }}?>
            </tbody>


          </table>

          {{ $categories->appends(request()->input())->links('admin.pagination') }}
        </div>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>



@include('admin.common.footer')

<script>
  function change_category_status(id){
    var status = $('#change_category_status'+id).val();
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ route($routeName.'.categories.change_category_status') }}",
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
