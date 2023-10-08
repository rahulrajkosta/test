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
                            <form method="POST" enctype="multipart/form-data" action="">
                                @csrf
                <div class="form-group">
                    <label>Select video</label>
                    <input type="file" name="video" class="form-control" required="" accept="video/*">
                </div>
 
                <div class="form-group">
                    <label>Select bitrate</label>
                    <select name="bitrate" class="form-control">
                        <option value="350k">240p</option>
                        <option value="700k">360p</option>
                        <option value="1200k">480p</option>
                        <option value="2500k">720p</option>
                        <option value="5000k">1080p</option>
                    </select>
                </div>
 
                <input type="submit" name="change_bitrate" class="btn btn-info" value="Change bitrate">
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
    
</script>

