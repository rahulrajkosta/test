@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'business_gallery/';
// $roleId = Auth::guard('admin')->user()->role_id; 
/*

<div class="col-6">
                <div class="input-group">
                  <select class="form-control select2" name="category_id">
                    <option value="" disabled selected>Select Category</option>
                    <?php if(!empty($categories)){
                      $cat_id = $_GET['category_id']??0;
                      foreach($categories as $cat){
                        ?>
                        <option value="{{$cat->id}}" <?php if($cat_id == $cat->id) echo "selected"?>>{{$cat->name}}</option>

                      <?php }}?>
                    </select>
                  </div>
                </div>

*/
                ?>
                <div class="row">
                  <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                      <h4 class="mb-0">Business List</h4>
                      <div class="page-title-right">
                        <ol class="breadcrumb m-0">
        <!--  <a href="{{ route($routeName.'.businesses.export', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-file-export" aria-hidden="true"></i> Export</a>
          &nbsp;&nbsp;&nbsp; -->

          <a href="{{ route($routeName.'.businesses.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Business</a>
        </ol>
      </div>

    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Search</label>
          <form id="submit_form">

            <div class="row">
              <div class="col-12">
                <div class="input-group">
                  <input type="text" name="search" value="{{$_GET['search'] ?? ''}}" class="form-control" placeholder="Search...." aria-label="Recipient's username">
                </div>
              </div>
              

            </div>
            <br>
            <div class="row">
             <div class="col-6">
              <div class="input-group">
                <input type="date" name="start_date" value="{{$_GET['start_date'] ?? ''}}" class="form-control"  >
              </div>
            </div>
            <div class="col-6">
              <div class="input-group">
                <input type="date" name="end_date" value="{{$_GET['end_date'] ?? ''}}" class="form-control"  >
              </div>
            </div>
          </div>

          <br>
          <input type="hidden" name="is_export" id="is_export" value="0">
          <div class="row">
           <div class="col-6">
            <button type="submit" class="btn btn-success">Search</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{ route($routeName.'.businesses.index') }}" class="btn btn-danger">Reset</a>
            &nbsp;&nbsp;&nbsp;&nbsp;

            <a onclick="export_excel()" class="btn btn-primary"><i class="fas fa-file-export" aria-hidden="true"></i> Export</a>
          </div>
          <div class="col-6">

          </div>
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
        <h4 class="header-title">Business List</h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>S.No.</th>
                <th width="250px">Business Name</th>
                <th>Business Type</th>
                <th>Image</th>
                <th>Categories & SubCategories</th>
                <th>Refered By</th>
                <th>Added On</th>
                <th>Lat/Long</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($businesses)){

                $i = 1;
                foreach($businesses as $cat){
                  ?>
                  <tr>
                    <td>{{$i++}}</td>
                    <td>
                      <b>{{$cat->business_name ?? ''}}</b><br>
                     <b> Owner Name: </b>{{$cat->owner_name ?? ''}}<br>
                      <b>Phone: </b>{{$cat->mobile ?? ''}}<br>
                      <b>Address:</b> {{$cat->address ?? ''}}<br>
                      <b>Contact Person Name:</b> {{$cat->owner_name ?? ''}}<br>
                     <b> Contact Person Phone: </b>{{$cat->mobile ?? ''}}<br>
                      
                      <select id='change_business_status{{$cat->id}}' onchange='change_business_status({{$cat->id}})' class="form-control">
                        <option value='1' <?php if($cat->status ==1)echo "selected";?> >Active</option>
                        <option value='0' <?php if($cat->status ==0)echo "selected";?>>InActive</option>
                      </select>
                      <br>


                    </td>
                    
                    <td>{{$cat->business_type ?? ''}}</td>
                    

                    <td>
                     <?php
                     if(!empty($cat->image)){
                      $image_name = $cat->image;
                        //echo $image_name;
                        //print_r($storage);
                        //if($storage->exists($path.$image_name)){
                      ?>
                      <div class=" image_box" style="display: inline-block">
                        <a href="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" target="_blank">
                          <img src="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" style="width:70px;">
                        </a>
                      </div>
                      <?php
                       // }
                    }else{?>
                      <div class=" image_box" style="display: inline-block">
                        <a href="{{ url('public/storage/'.$path.'default-image.png') }}" target="_blank">
                          <img src="{{ url('public/storage/'.$path.'default-image.png') }}" style="width:70px;">
                        </a>
                      </div>
                    <?php  }
                    ?>
                  </td>

                  <td>
                    <ul data-role="treeview">
                      <?php
                      $categories = \App\BusinessCategory::where('business_id',$cat->id)->get();
                      if(!empty($categories)){
                        foreach ($categories as $key) {
                          $category = \App\Category::where('id',$key->cat_id)->first();
                          ?>
                          <li>{{$category->name??''}}</li>


                          <?php if(!empty($key->sub_cat_id)){
                            $sub_cat_id = explode(",", $key->sub_cat_id);
                            if(!empty($sub_cat_id)){
                              foreach ($sub_cat_id as $key=>$value){
                                $sub_category = DB::table('sub_categories')->where('id',$value)->first();
                                if(!empty($sub_category)){
                                  ?>


                                  <ul>
                                    <li>{{$sub_category->name ??'' }}</li>
                                  </ul>
                                <?php } }}}
                                ?>




                              <?php }}?>
                            </ul>

                          </td>
                          
                          <td>
                            {{$cat->referred_by??''}}
                          </td>

                          <td>{{date('d M Y h:i A',strtotime($cat->created_at))}}</td>
                          <td>
                            {{$cat->latitude??''}}/ {{$cat->longitude??''}}
                          </td>

                          <td style="display: flex;">

                           <a class="btn btn-success" href="{{ route($routeName.'.businesses.show', $cat->id.'?back_url='.$BackUrl) }}" target="_blank"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;

                           <a class="btn btn-success" href="{{ route($routeName.'.businesses.edit', $cat->id.'?back_url='.$BackUrl) }}" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                           <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.businesses.delete', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                         </td>
                       </tr>
                     <?php }}?>
                   </tbody>


                 </table>

                 {{ $businesses->appends(request()->input())->links('admin.pagination') }}
               </div>
             </div> <!-- end card body-->
           </div> <!-- end card -->
         </div><!-- end col-->
       </div>



       @include('admin.common.footer')

       <script>
        function change_business_status(id){
          var status = $('#change_business_status'+id).val();
          var _token = '{{ csrf_token() }}';
          $.ajax({
            url: "{{ route($routeName.'.businesses.change_business_status') }}",
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

        function export_excel(){
          $('#is_export').val(1);
          $('#submit_form').submit();
        }


      </script>
