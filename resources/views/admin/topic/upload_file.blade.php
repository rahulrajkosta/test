@include('admin.common.header')




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
                                <label for="userName">File<span class="text-danger">*</span></label>
                                <input class="form-control" id="file" type="file" name="file">
                                @include('snippets.errors_first', ['param' => 'file'])
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


<textarea class="form-control">
    {{$html ?? ''}}
</textarea>
















</div>
<!-- END container-fluid -->

</div>
<!-- END content -->

</div>
<!-- END content-page -->







  @include('admin.common.footer')


<script>
    
</script>

