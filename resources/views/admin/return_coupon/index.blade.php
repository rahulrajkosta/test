@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

?>
<style>
/* The container */
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Return Coupon</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Return Coupon
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
                <h4 class="card-title">Return Coupon</h4>
                <a class="heading-elements-toggle">
                  <i class="la la-ellipsis-v font-medium-3"></i>
                </a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li>

                    </li>
                  </ul>
                </div>
              </div>
              @include('snippets.errors')
              @include('snippets.flash')

              <div class="card-content collapse show">
                <div class="card-body">
                  <form class="card-body" action="{{ route('admin.return_coupon.return') }}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <div class="form-row">
                      <div class="col-md-12 mt-2">
                        <label class="container">Select All
                          <input type="checkbox" id="ckbCheckAll" >
                          <span class="checkmark"></span>
                        </label>
                      </div>


                      <?php 
                      if(!empty($couponArr)){
                        \App\Coupon::latest('id')->whereIn('id',$couponArr)->chunk(50, function($coupons) {
                          foreach ($coupons as $coupon) {?>
                            <div class="col-md-4 mt-2">
                              <label class="container">{{$coupon->couponID}}
                                <input type="checkbox" name="couponids[]" class="checkBoxClass" id="ckbCheckAll{{$coupon->id}}" value="{{$coupon->id}}" >
                                <span class="checkmark"></span>
                              </label>
                            </div>
                          <?php }
                        });
                      }
                      ?>
                      

                    </div>
                    <br>
                    <br>
                    <br>
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
   $(document).ready(function () {
        $("#ckbCheckAll").click(function () {
            $(".checkBoxClass").attr('checked', this.checked);
        });
    });

</script>

