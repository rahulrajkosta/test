@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'category/';
?>



<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Settings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                   
                </ol>
            </div>

        </div>
    </div>
</div>   
@include('snippets.errors')
@include('snippets.flash')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title">Settings</h4>

                <form action="" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}


                    <div class="mb-3">
                        <label for="fullname" class="form-label">App Name</label>
                        <input type="text" name="app_name" class="form-control" value="{{$settings->app_name ?? ''}}" placeholder="Enter App Name">
                        @include('snippets.errors_first', ['param' => 'app_name'])
                    </div>




                    <div class="mb-3">
                        <label for="fullname" class="form-label">Refer Earn Amount</label>
                        <input type="text" name="refer_earn_amount" class="form-control" value="{{$settings->refer_earn_amount ?? ''}}" placeholder="Enter Refer Earn Amount">
                        @include('snippets.errors_first', ['param' => 'refer_earn_amount'])
                    </div>

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Contact Email</label>
                        <input type="text" name="contact_email" class="form-control" value="{{$settings->contact_email ?? ''}}" placeholder="Enter Contact Email">
                        @include('snippets.errors_first', ['param' => 'contact_email'])
                    </div>

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{$settings->contact_phone ?? ''}}" placeholder="Enter Contact Phone">
                        @include('snippets.errors_first', ['param' => 'contact_phone'])
                    </div>



                    <div class="mb-3">
                        <label for="fullname" class="form-label">Privacy</label>
                        <textarea class="form-control" id="privacy" name="privacypolicy">{{$settings->privacypolicy ?? ''}}</textarea>
                        @include('snippets.errors_first', ['param' => 'privacypolicy'])
                    </div>


                    <div class="mb-3">
                        <label for="userName">Terms & Condition<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="terms" name="terms">{{$settings->terms ?? ''}}</textarea>
                        @include('snippets.errors_first', ['param' => 'terms'])
                    </div>


                    <div class="mb-3">
                      <label for="userName">About<span class="text-danger">*</span></label>
                      <textarea class="form-control" id="about" name="about_us">{{$settings->about_us ?? ''}}</textarea>
                      @include('snippets.errors_first', ['param' => 'about_us'])
                  </div>



                  <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
              </form>

          </div> <!-- end card-body-->
      </div> <!-- end card-->
  </div>
  <!-- end col -->


</div>


@include('admin.common.footer')

<script>
   CKEDITOR.replace( 'privacy' );
   CKEDITOR.replace( 'terms' );
   CKEDITOR.replace( 'about' );





</script>


<script>

</script>

