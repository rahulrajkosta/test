@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'subcategory/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">Sub Categories</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <a href="{{ route($routeName.'.subcategories.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Sub Category</a>
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
          <label class="form-label">Search By SubCategory Name</label>
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
        <h4 class="header-title">Sub Categories</h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Category Name</th>
                <th>Business Type</th>

                <th>SubCategory Name</th>

                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($subcategories)){

                $i = 1;
                foreach($subcategories as $cat){
                  ?>
                  <tr>
                    <td>{{$i++}}</td>
                    <td>{{$cat->category->name ?? ''}}</td>
                    <td>{{$cat->category->type ?? ''}}</td>
                    <td>{{$cat->name ?? ''}}</td>

                  <td>
                    <select id='change_subcategory_status{{$cat->id}}' onchange='change_subcategory_status({{$cat->id}})' class="form-control">
                      <option value='1' <?php if($cat->status ==1)echo "selected";?> >Active</option>
                      <option value='0' <?php if($cat->status ==0)echo "selected";?>>InActive</option>
                    </select> 


                  </td>

                  <td>

                    <a class="btn btn-success" href="{{ route($routeName.'.subcategories.edit', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                    <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.subcategories.delete', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                  </td>
                </tr>
              <?php }}?>
            </tbody>


          </table>

          {{ $subcategories->appends(request()->input())->links('admin.pagination') }}
        </div>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>



@include('admin.common.footer')

<script>
  function change_subcategory_status(id){
    var status = $('#change_subcategory_status'+id).val();
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ route($routeName.'.subcategories.change_subcategory_status') }}",
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
