@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$roles_id = (isset($roles->id))?$roles->id:'';
$name = (isset($roles->name))?$roles->name:'';
$status = (isset($roles->status))?$roles->status:'';
$parent_id = (isset($roles->parent_id))?$roles->parent_id:0;


$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

?>







<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">{{ $page_heading }}</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">{{ $page_heading }}
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
                <h4 class="card-title">{{ $page_heading }}</h4>
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

                  <input type="hidden" id="id" value="{{$roles_id}}">

                  <div class="form-row">
                    <div class="col-md-6 mb-3">
                      <label for="userName">Name<span class="text-danger">*</span></label>
                      <input type="text" name="name" placeholder="Enter Name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" />

                      @include('snippets.errors_first', ['param' => 'name'])

                    </div>


                    <div class="col-md-6 mb-3">
                      <label for="userName">Parent<span class="text-danger">*</span></label>
                      <select class="form-control" name="parent_id">
                        <option value="" selected disabled>Select Parent</option>
                        <?php if(!empty($all_roles)){
                          foreach($all_roles as $all){
                          ?>
                          <option value="{{$all->id}}" <?php if($all->id == $parent_id) echo "selected"?>>{{$all->name??''}}</option>
                        <?php }}?>
                      </select>

                      @include('snippets.errors_first', ['param' => 'parent_id'])

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
<script>
  CKEDITOR.replace( 'description' );
</script>