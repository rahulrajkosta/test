@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'business_docs/';
$path1 = 'business_gallery/';

$businesses_id = isset($businesses->id) ? $businesses->id : '';
$business_name = isset($businesses->business_name) ? $businesses->business_name : '';
$business_type = isset($businesses->business_type) ? $businesses->business_type : '';
$owner_name = isset($businesses->owner_name) ? $businesses->owner_name : '';
$state_id = isset($businesses->state_id) ? $businesses->state_id : '';
$city_id = isset($businesses->city_id) ? $businesses->city_id : '';
$mobile = isset($businesses->mobile) ? $businesses->mobile : '';
$land_line_no = isset($businesses->land_line_no) ? $businesses->land_line_no : '';
$address = isset($businesses->address) ? $businesses->address : '';
$pincode = isset($businesses->pincode) ? $businesses->pincode : '';
$doc_no = isset($businesses->doc_no) ? $businesses->doc_no : '';
$doc_image = isset($businesses->doc_image) ? $businesses->doc_image : '';
$availability = isset($businesses->availability) ? $businesses->availability : '';
$latitude = isset($businesses->latitude ) ? $businesses->latitude  : '';
$longitude = isset($businesses->longitude) ? $businesses->longitude : '';
$gst_no = isset($businesses->gst_no) ? $businesses->gst_no : '';
$status = isset($businesses->status) ? $businesses->status : '';
$image = isset($businesses->image) ? $businesses->image : '';
$referred_by = isset($businesses->referred_by) ? $businesses->referred_by : '';


$contact_person_no = isset($businesses->contact_person_no) ? $businesses->contact_person_no : '';
$register_mobile = isset($businesses->register_mobile) ? $businesses->register_mobile : '';
$contact_name = isset($businesses->contact_name) ? $businesses->contact_name : '';




?>


<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">{{ $page_Heading }}</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
         <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
         <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
       </ol>
     </div>

   </div>
 </div>
</div>


@include('snippets.errors')
@include('snippets.flash')





<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">{{ $page_Heading }}</h4>

        <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

         {{ csrf_field() }}

         <input type="hidden" value="{{ $businesses_id }}">


         <div class="mb-3">
          <label for="fullname" class="form-label">Business Name  * :</label>
          <input class="form-control mb-3" type="text" name="business_name" id="business_name"  value="{{ old('business_name',$business_name) }}" placeholder="Business Name" aria-label="default input example">

        </div>


        <?php /*
        <div class="mb-3">
          <label for="fullname" class="form-label">Business Category  * :(Choose Max 4)</label>
          <select class="form-control select2" name="category_ids[]" id="category_ids" multiple="multiple" data-maxoption="4">
            <?php if(!empty($categories)){
              foreach ($categories as $cat) {

                $subcategories = \App\SubCategory::where('category_id',$cat->id)->get();
                if(!empty($subcategories)){
                  foreach ($subcategories as $sub_cat) {?>
                      <option value="subcat{{$sub_cat->id}}" >{{$cat->name}} ---- {{$sub_cat->name}}</option>
                  <?php }}else{?>
                  
                <?php }?>
                 <option value="cat{{$cat->id}}">{{$cat->name}}</option>
            <?php }}
            ?>

          </select>
        </div>

        */?>

        <div class="mb-3">
          <label for="fullname" class="form-label">Business Category1  * :</label>
          <select class="form-control select2" name="category_ids1" id="category_ids1">
            <option value="" selected disabled>Select Category1</option>

            <?php if(!empty($categories)){
              foreach ($categories as $cat) {?>
                <option value="{{$cat->id}}" <?php if(!empty($categoryIds[0])){ if($cat->id == $categoryIds[0]){echo "selected";}}?>>{{$cat->name}}</option>
              <?php }}
              ?>

            </select>
          </div>

          <?php //if(!empty($subcategories[0])){?>
           <div class="mb-3" id="subcat1">
            <label for="fullname" class="form-label">Choose SubCategory1  * :</label>
            <select class="form-control select2" name="subcategory_ids1[]" id="subcategory_ids1" multiple>
              <?php if(!empty($subcategories[0])){
                foreach ($subcategories[0] as $cat0) {
                  ?>
                  <option value="{{$cat0->id}}" 
                    <?php 
                    if(!empty($subcategories[0])){
                      if(in_array($cat0->id,$subcategoriesids1)){echo "selected";} }?>

                      >{{$cat0->name}}</option>
                    <?php }}
                    ?>

                  </select>
                </div>
              <?php //}?>


              <div class="mb-3">
                <label for="fullname" class="form-label">Business Category2  * :</label>
                <select class="form-control select2" name="category_ids2" id="category_ids2">
                  <option value="" selected disabled>Select Category2</option>
                  <?php if(!empty($categories)){
                    foreach ($categories as $cat) {?>
                      <option value="{{$cat->id}}" <?php 
                      if(!empty($categoryIds[1])){
                        if($cat->id==$categoryIds[1]){echo "selected";} }?>

                        >{{$cat->name}}</option>
                      <?php }}
                      ?>

                    </select>
                  </div>

                  <?php //if(!empty($subcategories[1])){?>
                   <div class="mb-3" id="subcat2">
                    <label for="fullname" class="form-label">Choose SubCategory2  * :</label>
                    <select class="form-control select2" name="subcategory_ids2[]" id="subcategory_ids2" multiple>
                      <?php if(!empty($subcategories[1])){
                        foreach ($subcategories[1] as $cat1) {?>
                          <option value="{{$cat1->id}}" <?php 
                          if(!empty($subcategories[1])){
                            if(in_array($cat1->id,$subcategoriesids2)){echo "selected";} }?>>{{$cat1->name}}</option>
                          <?php }}
                          ?>

                        </select>
                      </div>
                    <?php //}?>





          <!-- <div class="mb-3">
          <label for="fullname" class="form-label">Business Category  * :(Choose Max 4)</label>
          <select class="form-control" name="subcategory_ids[]" id="subcategory_ids" multiple="multiple" data-maxoption="4">
            <?php //if(!empty($subcategories)){
              //foreach ($subcategories as $cat) {?>
                <option value="{{$cat->id}}" <?php //if(!empty($subcategoryIds)){if(in_array($cat->id,$subcategoryIds)) {echo"selected";}}?>>{{$cat->name}}</option>
              <?php //}}
              ?>

            </select>
          </div>
        -->





        <div class="mb-3">
          <label for="fullname" class="form-label">Business Image </label>
          <input type="file" name="image" class="form-control">

        </div>



        <?php
        if(!empty($image)){
          if($storage->exists($path1.$image)){
            ?>
            <div class=" image_box" style="display: inline-block">
              <a href="{{ url('public/storage/'.$path1.$image) }}" target="_blank">
                <img src="{{ url('public/storage/'.$path1.'thumb/'.$image) }}" style="width:70px;">
              </a>
            </div>
            <?php
          }
        }
        ?>



        <div class="mb-3">
          <label for="fullname" class="form-label">Business Type  * :</label>
          <select class="form-control" name="business_type">
           <option value="shop" <?php if($business_type == 'shop') echo"selected"?>>Shop</option>
           <option value="service" <?php if($business_type == 'service') echo"selected"?>>Service</option>
         </select>
       </div>

       <div class="mb-3">
        <label for="fullname" class="form-label">Owner Name  * :</label>
        <input class="form-control mb-3" type="text" name="owner_name" id="owner_name"  value="{{ old('owner_name',$owner_name) }}" placeholder="Owner Name" aria-label="default input example">

      </div>



      <div class="mb-3">
        <label for="fullname" class="form-label">State  * :</label>
        <select class="form-control" name="state_id" id="state_id">
          <option value="" selected disabled>Select State</option>
          <?php if(!empty($states)){
            foreach ($states as $state) {?>
              <option value="{{$state->id}}" <?php if($state->id == $state_id) echo"selected"?>>{{$state->name}}</option>
            <?php }}
            ?>

          </select>
        </div>



        <div class="mb-3">
          <label for="fullname" class="form-label">City  * :</label>
          <select class="form-control" name="city_id" id="city_id">
            <option value="" selected disabled>Select City</option>

            <?php if(!empty($cities)){
              foreach ($cities as $city) {?>
                <option value="{{$city->id}}" <?php if($city->id == $city_id) echo"selected"?>>{{$city->name}}</option>
              <?php }}
              ?>

            </select>
          </div>


          <div class="mb-3">
            <label for="fullname" class="form-label">Mobile No  * :</label>
            <input class="form-control mb-3" type="text" name="mobile" id="mobile"  value="{{ old('mobile',$mobile) }}" placeholder="Mobile No" aria-label="default input example">

          </div>

          <div class="mb-3">
            <label for="fullname" class="form-label">Land Line No </label>
            <input class="form-control mb-3" type="text" name="land_line_no" id="land_line_no"  value="{{ old('land_line_no',$land_line_no) }}" placeholder="Land Line No" aria-label="default input example">

          </div>


          <div class="mb-3">
            <label for="fullname" class="form-label">Address </label>
            <input class="form-control mb-3" type="text" name="address" id="address"  value="{{ old('address',$address) }}" placeholder="Address" aria-label="default input example">

          </div>

          <div class="mb-3">
            <label for="fullname" class="form-label">Latitude </label>
            <input class="form-control mb-3" type="text" name="latitude" id="latitude"  value="{{ old('latitude',$latitude) }}" placeholder="Latitude" aria-label="default input example">

          </div>

           <div class="mb-3">
            <label for="fullname" class="form-label">Longitude </label>
            <input class="form-control mb-3" type="text" name="longitude" id="longitude"  value="{{ old('longitude',$longitude) }}" placeholder="Longitude" aria-label="default input example">

          </div>

          

          <div class="mb-3">
            <label for="fullname" class="form-label">Pincode </label>
            <input class="form-control mb-3" type="text" name="pincode" id="pincode"  value="{{ old('pincode',$pincode) }}" placeholder="Pincode" aria-label="default input example">

          </div>

          <div class="mb-3">
            <label for="fullname" class="form-label">Document No </label>
            <input class="form-control mb-3" type="text" name="doc_no" id="doc_no"  value="{{ old('doc_no',$doc_no) }}" placeholder="Document No" aria-label="default input example">

          </div>

          <div class="mb-3">
            <label for="fullname" class="form-label">Document Image </label>
            <input type="file" name=" doc_image" class="form-control">

          </div>



          <?php
          if(!empty($doc_image)){
            if($storage->exists($path.$doc_image)){
              ?>
              <div class=" image_box" style="display: inline-block">
                <a href="{{ url('public/storage/'.$path.$doc_image) }}" target="_blank">
                  <img src="{{ url('public/storage/'.$path.'thumb/'.$doc_image) }}" style="width:70px;">
                </a>
              </div>
              <?php
            }
          }
          ?>




          <div class="mb-3">
            <label for="fullname" class="form-label">Availability</label>
            <select class="form-control" name="availability">
             <option value="online" <?php if($availability == 'online') echo"selected"?>>Online</option>
             <option value="offline" <?php if($availability == 'offline') echo"selected"?>>Offline</option>
             <option value="both" <?php if($availability == 'both') echo"selected"?>>Both</option>
           </select>

         </div>



         <div class="mb-3">
          <label for="fullname" class="form-label">GST No </label>
          <input class="form-control mb-3" type="text" name="gst_no" id="gst_no"  value="{{ old('gst_no',$gst_no) }}" placeholder="GST No" aria-label="default input example">

        </div>


        <div class="mb-3">
          <label for="fullname" class="form-label">Refered By</label>
          <input class="form-control mb-3" type="text" name="referred_by" id="referred_by"  value="{{ old('referred_by',$referred_by) }}" placeholder="Refered By" aria-label="default input example">

        </div>




         <div class="mb-3">
          <label for="fullname" class="form-label">Contact Person No</label>
          <input class="form-control mb-3" type="text" name="contact_person_no" id="contact_person_no"  value="{{ old('contact_person_no',$contact_person_no) }}" placeholder="Contact Person No" aria-label="default input example">

        </div>


       <!--  <div class="mb-3">
          <label for="fullname" class="form-label">Register Mobile</label>
          <input class="form-control mb-3" type="text" name="register_mobile" id="register_mobile"  value="{{ old('register_mobile',$register_mobile) }}" placeholder="Register Mobile" aria-label="default input example">

        </div> -->

        <div class="mb-3">
          <label for="fullname" class="form-label">Contact Name</label>
          <input class="form-control mb-3" type="text" name="contact_name" id="contact_name"  value="{{ old('contact_name',$contact_name) }}" placeholder="Contact Name" aria-label="default input example">

        </div>



        <div class="mb-3">
          <label>Status</label>
          <div>
           Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
           &nbsp;
           Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

           @include('snippets.errors_first', ['param' => 'status'])
         </div>
       </div>



       <div>
        <input type="submit" class="btn btn-success" value="Submit">
      </div>

    </form>
  </div>
</div> <!-- end card-->
</div> <!-- end col-->
</div>

@include('admin.common.footer')

<script type="text/javascript">
  $(document).ready(function() {
    var arr = new Array();
    $("select[multiple]").change(function() {
      $(this).find("option:selected")
      if ($(this).find("option:selected").length > 4) {
        $(this).find("option").removeAttr("selected");
        $(this).val(arr);
      }
      else {
        arr = new Array();
        $(this).find("option:selected").each(function(index, item) {
          arr.push($(item).val());
        });
      }
    });
  });


  $('#category_ids1').change(function() {
    var category_ids1 = $(this).val();
    var _token = '{{ csrf_token() }}';
    $('#subcat1').hide();
    $.ajax({
      url: "{{ route($routeName.'.businesses.getsubcategories') }}",
      type: "POST",
      data: {category_ids1:category_ids1},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        $('#subcategory_ids1').html(resp);
        if(resp != ''){
          $('#subcat1').show();
        }else{
          $('#subcat1').hide();

        }
        
    }
  });
    
  });




  $('#category_ids2').change(function() {
     var category_ids2 = $(this).val();
    var _token = '{{ csrf_token() }}';
    $('#subcat2').hide();
    $.ajax({
      url: "{{ route($routeName.'.businesses.getsubcategories1') }}",
      type: "POST",
      data: {category_ids2:category_ids2},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        $('#subcategory_ids2').html(resp);
        if(resp != ''){
          $('#subcat2').show();
        }else{
          $('#subcat2').hide();

        }
        
    }
  });
  });










</script>