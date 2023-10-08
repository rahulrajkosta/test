@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'category/';
?>





<div class="content-page">

    <!-- Start content -->
    <div class="content">

        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-holder">
                        <h1 class="main-title float-left">Settings</h1>
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->


            @include('snippets.errors')
            @include('snippets.flash')


                        <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3><i class="far fa-hand-pointer"></i>Settings</h3>
                        </div>

                        <div class="card-body">

                         <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}

                         
                            <div class="form-group">
                                <label for="userName">Privacy<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="privacy" name="privacy">{{$settings->privacy ?? ''}}</textarea>
                                @include('snippets.errors_first', ['param' => 'privacy'])
                            </div>



                            <div class="form-group">
                                <label for="userName">Terms & Condition<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="terms" name="terms">{{$settings->terms ?? ''}}</textarea>
                                @include('snippets.errors_first', ['param' => 'terms'])
                            </div>


  
                            <div class="form-group">
                                <label for="userName">About<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="about" name="about">{{$settings->about ?? ''}}</textarea>
                                @include('snippets.errors_first', ['param' => 'about'])
                            </div>


                   

                 <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Submit
                    </button>
                </div>

            </form>

        </div>
    </div><!-- end card-->
</div>



</div>

</div>
<!-- END container-fluid -->

</div>
<!-- END content -->

</div>
<!-- END content-page -->







  @include('admin.common.footer')

 <script>
             CKEDITOR.replace( 'privacy' );
              CKEDITOR.replace( 'terms' );
              CKEDITOR.replace( 'about' );

              
   


          </script>


<script>
    
</script>

