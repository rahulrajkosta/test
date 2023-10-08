
<?php 
$settings = DB::table('settings')->where('id',1)->first();
$title = $settings->app_name ?? 'JUSTDIAL';
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$logo = config('custom.NO_IMG');

$storage = Storage::disk('public');
$path = 'settings/';

$image_name = $settings->logo ?? '';
if(!empty($image_name)){
  if($storage->exists($path.$image_name)){
    $logo =  url('public/storage/'.$path.'/'.$image_name);
}
}


$colorArr = ['card bg-gradient-x-purple-red','card bg-gradient-x-blue-green','card bg-gradient-x-orange-yellow','card bg-gradient-directional-success'];

$user_id = Auth::guard('admin')->user()->id??'';
$phonestatusArr = ['locked','unlock','remove'];
?>

<?php echo $__env->make('admin.common.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- start page title -->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before cus-bg" style=""></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 my-2">
        <h3 class="content-header-title">Home</h3>
    </div>
    <div class="content-header-right col-md-8 col-12 my-2">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a>
              </li>
              <li class="breadcrumb-item active">Dashboard
              </li>
          </ol>
      </div>
  </div>
</div>
</div>
<div class="content-body">
    <section id="minimal-statistics-bg">
        <div class="row">




          <?php
          $role_id = Auth::guard('admin')->user()->role_id??'';

          if($role_id !=0 && $role_id !=7){

             $user_id = Auth::guard('admin')->user()->id;
             $coupons = [];
             $parent_total_coupons = \App\AssignCoupon::where('is_used',0)->where('is_return',0)->where('child_id',$user_id)->pluck('coupon_id')->toArray();
             $distribute_coupons = \App\AssignCoupon::where('is_used',0)->where('is_return',0)->where('parent_id',$user_id)->pluck('coupon_id')->toArray();
             $couponArr = [];


             if(!empty($parent_total_coupons)){
                foreach ($parent_total_coupons as $key => $value){
                  if(!in_array($value, $distribute_coupons))
                    $couponArr[$key]=$value;
            }
            $coupons = \App\Coupon::whereIn('id',$couponArr)->paginate(10);
            $countcoupons = \App\Coupon::whereIn('id',$couponArr)->get()->count();
        }
       $total_coupon = 0;
        if(!empty($coupons)){
            $total_coupon = $coupons->total();
        }

        
        ?>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-gradient-x-purple-blue">
                <div class="card-content">
                    <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.my_coupons.coupons_history')); ?>">
                        <div class="card-body" style="height:176px">
                            <div class="media d-flex">
                                <div class="align-self-top">
                                    <i class="icon-users icon-opacity text-white font-large-4 float-left"></i>
                                </div>
                                <div class="media-body text-white text-right align-self-bottom mt-3">
                                    <span class="d-block mb-1 font-medium-1">My Coupons</span>
                                    
                                    <h1 class="text-white mb-0"><?php echo e($total_coupon ?? 0); ?></h1>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php }?>
    <?php 
    $role_id = Auth::guard('admin')->user()->role_id??'';
    $parent_id = Auth::guard('admin')->user()->parent_id??'';
    $user_id = Auth::guard('admin')->user()->id??'';
    $roles = [];
    $role_ids = [];

    $role = \App\Roles::where('id',$role_id)->first();
    if($parent_id == 0){
        $roles = \App\Roles::where('parent_id','!=',0)->get();
    }else{
        $roles = \App\Roles::where('parent_id','>',$role->parent_id)->get();
    }
    
    if(!empty($roles)){
        $i= 1 ;
        foreach($roles as $role){
            $no_of_child = 0;
            $no_of_child = CustomHelper::getCountChild($role->id,$user_id,$i);

            // if($role->id == 3)
            // {
            //     $distributor = \App\Admin::where(['role_id'=>3,'status'=>1,'is_delete'=>0])->count();
            //     $no_of_child = $distributor;
            // }

            // if($role->id == 4)
            // {
            //     $sales_person = \App\Admin::where(['role_id'=>4,'status'=>1,'is_delete'=>0])->count();
            //     $no_of_child = $sales_person;
            // }

            //  if($role->id == 5)
            // {
            //     $retailer = \App\Admin::where(['role_id'=>5,'status'=>1,'is_delete'=>0])->count();
            //     $no_of_child = $retailer;
            // }
            
            ?>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="<?php echo e($colorArr[$i-1]); ?>">
                    <div class="card-content">
                     <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.'.$role->slug.'.index',['fetch_role_id'=>$role->id,'user_id'=>$user_id,'key'=>$i])); ?>">
                         <!-- <a href="#"> -->
                            <div class="card-body" style="height:176px">
                                <div class="media d-flex">
                                    <div class="align-self-top">
                                        <i class="icon-users icon-opacity text-white font-large-4 float-left"></i>
                                    </div>
                                    <div class="media-body text-white text-right align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Total <?php echo e($role->name ??''); ?></span>
                                        <h1 class="text-white mb-0"><?php echo e($no_of_child??0); ?></h1>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>  
                </div>
            </div>
            <?php
            $i++;
        }}?>





        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card bg-gradient-directional-success">
                <div class="card-content">
                    <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.user.index')); ?>">
                        <!-- <a href="#"> -->
                            <div class="card-body" style="height:176px">
                                <div class="media d-flex">
                                    <div class="align-self-top">
                                        <i class="icon-users icon-opacity text-white font-large-4 float-left"></i>
                                    </div>
                                    <div class="media-body text-white text-right align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Total User</span>
                                        <h1 class="text-white mb-0"><?php echo e($total_users??0); ?></h1>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card bg-gradient-x-purple-blue">
                    <div class="card-content">
                        <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.user.index',['date'=>date('Y-m-d')])); ?>">
                            <!-- <a href="#"> -->
                                <div class="card-body" style="height:176px">
                                    <div class="media d-flex">
                                        <div class="align-self-top">
                                            <i class="icon-users icon-opacity text-white font-large-4 float-left"></i>
                                        </div>
                                        <div class="media-body text-white text-right align-self-bottom mt-3">
                                            <span class="d-block mb-1 font-medium-1">Today User</span>
                                            <h1 class="text-white mb-0"><?php echo e($today_users??0); ?></h1>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>



                <?php if($role_id == 4){
                    $back_url = 'admin/seller';
                    ?>
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card bg-gradient-x-purple-blue">
                            <div class="card-content">
                                <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.seller.add',['parent_id'=>'','back_url'=>$back_url])); ?>">
                                    <!-- <a href="#"> -->
                                        <div class="card-body" style="height:176px">
                                            <div class="media d-flex">
                                                <div class="align-self-top">
                                                    <i class="icon-users icon-opacity text-white font-large-4 float-left"></i>
                                                </div>
                                                <div class="media-body text-white text-right align-self-bottom mt-3">
                                                    <span class="d-block mb-1 font-medium-1">Add Seller</span>
                                                    <h1 class="text-white mb-0"></h1>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php }?>
                    <?php if($role_id == 0){
                        $i = 1;
                        foreach($phonestatusArr as $key=>$phone){
                            $users_count = \App\MobileDetails::select('id')->where('phone_status',$phone)->count();
                            ?>
                            <div class="col-xl-3 col-lg-6 col-12">
                                <div class="<?php echo e($colorArr[$i-1]); ?>">
                                    <div class="card-content">
                                        <a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.user.index',['phone_status'=>$phone])); ?>">
                                            <!-- <a href="#"> -->
                                                <div class="card-body" style="height:176px">
                                                    <div class="media d-flex">
                                                        <div class="align-self-top">
                                                            <i class="icon-users icon-opacity text-white font-large-4 float-left"></i>
                                                        </div>
                                                        <div class="media-body text-white text-right align-self-bottom mt-3">
                                                            <span class="d-block mb-1 font-medium-1"><?php echo e(ucfirst($phone)); ?> User</span>
                                                            <h1 class="text-white mb-0"><?php echo e($users_count??0); ?></h1>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++;}}?>


               <!--  <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card bg-gradient-x-purple-red">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-top">
                                        <i class="icon-users icon-opacity text-white font-large-4 float-left"></i>
                                    </div>
                                    <div class="media-body text-white text-right align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Total Customers</span>
                                        <h1 class="text-white mb-0">8654</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card bg-gradient-x-blue-green">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-top">
                                        <i class="icon-basket-loaded icon-opacity text-white font-large-4 float-left"></i>
                                    </div>
                                    <div class="media-body text-white text-right align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Total Order</span>
                                        <h1 class="text-white mb-0">1562</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card bg-gradient-x-orange-yellow">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="align-self-top">
                                        <i class="icon-wallet icon-opacity text-white font-large-4 float-left"></i>
                                    </div>
                                    <div class="media-body text-white text-right align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Total Revenue</span>
                                        <h1 class="text-white mb-0">$18,123</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">User & Seller Statitics</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body chartjs">
                                <div class="height-500">
                                    <canvas id="area-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Top 5 Seller</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">

                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="height-400">
                                    <canvas id="simple-pie-chart-new"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
            $mobile_details = \App\MobileDetails::selectRaw("coupon_parent_id,count(id) as id")->groupBy('coupon_parent_id')->orderBy('id','DESC')->limit('5')->get();

            ?>


            <!-- <div class="row">
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card bg-gradient-directional-warning">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-white text-left align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">New Tickets</span>
                                        <h1 class="text-white mb-0">6235</h1>
                                    </div>
                                    <div class="align-self-top">
                                        <i class="icon-tag icon-opacity text-white font-large-4 float-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card bg-gradient-directional-success">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-white text-left align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Agents</span>
                                        <h1 class="text-white mb-0">486</h1>
                                    </div>
                                    <div class="align-self-top">
                                        <i class="icon-earphones-alt icon-opacity text-white font-large-4 float-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card bg-gradient-directional-danger">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-white text-left align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Response Time</span>
                                        <h1 class="text-white mb-0">18 Hrs</h1>
                                    </div>
                                    <div class="align-self-top">
                                        <i class="icon-speedometer icon-opacity text-white font-large-4 float-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card bg-gradient-directional-info">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <div class="media-body text-white text-left align-self-bottom mt-3">
                                        <span class="d-block mb-1 font-medium-1">Closed Tickets</span>
                                        <h1 class="text-white mb-0">5681</h1>
                                    </div>
                                    <div class="align-self-top">
                                        <i class="icon-like icon-opacity text-white font-large-4 float-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </section>
        <!-- // Minimal statistics with bg color section end -->
    </div>
</div>
</div>

<?php echo $__env->make('admin.common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<script type="text/javascript">
    $(window).on("load", function () {
        var o = $("#area-chart");
        new Chart(o, {
            type: "line",
            options: {
                responsive: !0,
                maintainAspectRatio: !1,
                legend: { position: "bottom" },
                hover: { mode: "label" },
                scales: {
                    xAxes: [{ display: !0, gridLines: { color: "#f3f3f3", drawTicks: !1 }, scaleLabel: { display: !0, labelString: "Month" } }],
                    yAxes: [{ display: !0, gridLines: { color: "#f3f3f3", drawTicks: !1 }, scaleLabel: { display: !0, labelString: "Value" } }],
                },
                title: { display: !0, text: "User & Seller Statitics" },
            },
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July","August","September","October","November","December"],
                datasets: [
                {
                    label: "User",
                    data: [<?php 
                        $year = date('Y');
                        for ($i = 1; $i <= 12; $i++) {
                            $sub_count = \App\MobileDetails::whereMonth('date_of_purchase',$i)->whereYear('date_of_purchase',$year)->count();
                            ?>
                            "<?php echo $sub_count?>",

                            <?php } ?>],
                            backgroundColor: "rgba(209,212,219,.4)",
                            borderColor: "transparent",
                            pointBorderColor: "#D1D4DB",
                            pointBackgroundColor: "#FFF",
                            pointBorderWidth: 2,
                            pointHoverBorderWidth: 2,
                            pointRadius: 4,
                        },
                        {
                            label: "Seller",
                            data: [<?php 
                                $year = date('Y');
                                for ($i = 1; $i <= 12; $i++) {

                                    $sub_count = \App\Admin::whereMonth('created_at',$i)->whereYear('created_at',$year)->count();
                                    ?>
                                    "<?php echo $sub_count?>",

                                    <?php } ?>],
                                    backgroundColor: "rgba(255,145,73,.7)",
                                    borderColor: "transparent",
                                    pointBorderColor: "#5175E0",
                                    pointBackgroundColor: "#FFF",
                                    pointBorderWidth: 2,
                                    pointHoverBorderWidth: 2,
                                    pointRadius: 4,
                                },
                                
                                ],
                            },
                        });
    });

</script>

<script type="text/javascript">
    $(window).on("load", function () {
        var a = $("#simple-pie-chart-new");
        new Chart(a, {
            type: "pie",
            options: { responsive: !0, maintainAspectRatio: !1, responsiveAnimationDuration: 500 },
            data: { labels: [
                <?php                
                foreach ($mobile_details as $key) {
                    $sub_count = \App\Admin::where('id',$key->coupon_parent_id)->first();
                    if(!empty($sub_count)){
                    ?>
                    "<?php echo ucfirst($sub_count->business_name)??''?>",

                <?php } }?>
            ], datasets: [{ label: "My First dataset", data: [<?php                
                foreach ($mobile_details as $key) {
                    ?>
                    "<?php echo $key->id??''?>",

                    <?php } ?>], backgroundColor: ["#666EE8", "#28D094", "#FF4961", "#1E9FF2", "#FF9149"] }] },
                });
    });

</script><?php /**PATH /var/www/html/makesecurepro/resources/views/admin/home/index.blade.php ENDPATH**/ ?>