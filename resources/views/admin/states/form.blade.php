@include('admin.common.header')
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
    $back_url = 'admin/states';
}

$name = (isset($states->name))?$states->name:'';
$country_id = (isset($states->country_id))?$states->country_id:'';

$id = (isset($states->id))?$states->id:'';

$status = (isset($states->status))?$states->status:1;

$storage = Storage::disk('public');

    //pr($storage);

$path = 'states/';
?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">{{ $page_Heading }}</h3>
    </div>
    <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">{{ $page_Heading }}
              </li>
          </ol>
      </div>
  </div>
</div>
</div>
<div class="content-body">
  <section class="input-validation">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{ $page_Heading }}</h4>
            <a class="heading-elements-toggle">
              <i class="la la-ellipsis-v font-medium-3"></i>
          </a>
          <div class="heading-elements">
              <ul class="list-inline mb-0">
                <li>
                 <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                 <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
             </li>
         </ul>
     </div>
 </div>
 @include('snippets.errors')
 @include('snippets.flash')

 <div class="card-content collapse show">
  <div class="card-body">
    <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

      {{ csrf_field() }}

      <input type="hidden" name="id" value="{{$id}}">

      <div class="form-row">

         <div class="col-md-6">
           <label for="exampleInputEmail1" class="form-label">Country Name</label>
           <select name="country_id" id="country_id" class="form-control select2-single">                                        
               <?php                                   
               foreach($countries as $country)
               {
                   ?>
                   <option value="{{$country->id}}" <?php if($country_id == $country->id) echo 'selected'; ?>>{{$country->name}}</option>
               <?php } ?>
           </select>
       </div>
       <div class="col-md-6">
        <label for="exampleInputEmail1" class="form-label">State Name</label>
        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter State Name" name="name" value="{{ old('name', $name) }}">
        @include('snippets.errors_first', ['param' => 'name'])
    </div>
    
    <div class="col-md-6 mb-3">
     <label>Status</label>
     <div>
       Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
       &nbsp;
       Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

       @include('snippets.errors_first', ['param' => 'status'])
   </div>
</div>


</div>

<button class="btn btn-primary" type="submit">Submit </button>
</form>
</div>
</div>
</div>
</div>
</div>
</section>

</div>
</div>
</div>
@include('admin.common.footer')
<script type="text/javascript">
  $(document).ready(function() {
    $('.select2-single').select2();
});
</script>