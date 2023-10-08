@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
?>

<div class="content-page">

  <!-- Start content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-12">
          <div class="breadcrumb-holder">
            <h1 class="main-title float-left">Permission</h1>
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Permission</li>
            </ol>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->



      @include('snippets.errors')
      @include('snippets.flash')

      <?php if(!empty($modules)){
        foreach ($modules as $key => $value) {
            $title = '';
          if(!empty($allowedwithval)){
            foreach ($allowedwithval as $key1 => $value1) {
              if($key1 == $value){
                $title = $value1;
              }
            }
          }



          ?>
          <form method="post" name="permission{{$value}}">
            {{csrf_field()}}

            <input type="hidden" name="section_name" value="{{$value}}">

            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card mb-3">

                 <div class="card-header d-flex" >
                  <div class="col-sm-4" >

                    <h3>Permission For {{$title}}</h3>
                  </div>
                  <div class="col-sm-2" style="left: 54px;">
                    <h3>Add</h3>
                  </div>
                  <div class="col-sm-2" style="left: 54px;">
                    <h3>Edit</h3>
                  </div>
                  <div class="col-sm-2" style="left: 54px;">
                    <h3>Delete</h3>
                  </div>
                  <div class="col-sm-2" style="left: 54px;">
                    <h3>Show</h3>
                  </div>
                </div>

                <?php if(!empty($roles)){
                  foreach($roles as $role){

                      $add_checked = '';
                      $edit_checked = '';
                      $delete_checked = '';
                      $show_checked = '';



                      $checked = \App\Permission::where('section_name',$value)->where('role_id',$role->id)->first();
                      if(!empty($checked)){
                        if($checked->add_section == 1){
                          $add_checked = 'checked';
                        }
                         if($checked->edit_section == 1){
                          $edit_checked = 'checked';
                        }
                         if($checked->delete_section == 1){
                          $delete_checked = 'checked';
                        }
                         if($checked->show_section == 1){
                          $show_checked = 'checked';
                        }
                      }

                    ?>
                    <div class="card-body d-flex">
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4">
                        <b>Role</b> :: {{$role->name ?? ''}}
                      </div>

                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
                        <input type="checkbox" name="add_section{{$role->id}}"  id="add_section{{$role->id}}" value="1" class="form-control" {{$add_checked}}>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
                        <input type="checkbox" name="edit_section{{$role->id}}" id="edit_section{{$role->id}}" value="1" class="form-control" {{$edit_checked}}>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
                        <input type="checkbox" name="delete_section{{$role->id}}" id="delete_section{{$role->id}}" value="1" class="form-control" {{$delete_checked}}>
                      </div>

                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-2">
                        <input type="checkbox" name="show_section{{$role->id}}" id="show_section{{$role->id}}" value="1" class="form-control" {{$show_checked}}>
                      </div>
                    </div>

                  <?php }}?>

                  <div class="card-body">
                    <button class="btn btn-primary" type="submit">
                      Submit
                    </button>
                  </div>


                </div>

              </div>
            </div>


          </form>
        <?php }}?>












      </div>
      <!-- END container-fluid -->

    </div>
    <!-- END content -->

  </div>
  <!-- END content-page -->



  @include('admin.common.footer')
