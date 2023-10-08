  <!-- ========== Left Sidebar Start ========== -->

  <?php 
  $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

  $url = url()->current();
// echo $url;

  $baseurl = url('/');

  $roleId = Auth::guard('admin')->user()->role_id; 
  $settings = DB::table('settings')->where('id',1)->first();
  $title = $settings->app_name ?? 'AntiTheft';

  // $logo = config('custom.NO_IMG');
  $newlogo = url('/').'/public/assets/logoold.png';

  

  $storage = Storage::disk('public');
  $path = 'settings/';

  $image_name = $settings->logo ?? '';
  if(!empty($image_name)){
    if($storage->exists($path.$image_name)){
      $logo =  url('public/storage/'.$path.'/'.$image_name);
    }
  }

   $business_name = Auth::guard('admin')->user()->business_name??'';
  if(empty($business_name)){
    $business_name = Auth::guard('admin')->user()->name??'';
  }

  ?>


  <style type="text/css">
    .main-menu ul.navigation-main {
      overflow-x: hidden;
      padding-top: 50px;
    }
    body.vertical-layout.vertical-menu.menu-expanded .main-menu .navigation li.has-sub>a:not(.mm-next):after {
      content: '\2193';

    }
    .vertical-overlay-menu .main-menu .navigation li.has-sub>a:not(.mm-next):after {
      content: '\2193';
    }
    .m-catrio {
    text-overflow: ellipsis;
    font-size: 12px !important;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}
  </style>
  <!-- BEGIN: Main Menu-->
  <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="<?php echo e(url('/public/assets/antitheft/')); ?>/app-assets/images/backgrounds/02.jpg">
    <div class="navbar-header">
      <ul class="nav navbar-nav flex-row">
        <div class="d-flex flex-column bg-white">
          <li class="nav-item mr-auto"><a class="navbar-brand" href="<?php echo e(url('/admin')); ?>">
            <?php if($role_id == 2){  
                $authdata = Auth::guard('admin')->user(); 
                if(!empty($authdata->image)){

               $image = isset($authdata->image) ? $authdata->image : '';
                $storage = Storage::disk('public');
                $path = 'users';  ?>
               <img src="<?php echo e(url('storage/app/public/user/'.$image)); ?>" alt="avatar" class="img-fluid">
            <?php }else{ ?>
               <img class="brand-logo" alt="Chameleon admin logo" src="<?php echo e($newlogo); ?>" style="height: 100%;width: 95%;" />
              <?php } ?>
                  <?php }else{?>
                <img class="brand-logo" alt="Chameleon admin logo" src="<?php echo e($newlogo); ?>" style="height: 100%;width: 95%;" />
            <?php } ?>
            <!-- <h3 class="brand-text">Anti Theft</h3></a></li> -->
            <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="fa fa-times"></i></a></li>

            <li class="nav-item" style="text-align: center;">
            <?php if($role_id != 4){?>
              <h6><b><?php echo e(Auth::guard('admin')->user()->business_name??''); ?></b></h6>
              <h6><b>Name: <?php echo e(Auth::guard('admin')->user()->name??''); ?></b></h6>

            <?php }else{?>
              <h6><b>Name: <?php echo e(Auth::guard('admin')->user()->name??''); ?></b></h6>
            <?php }?>
              
            <h6><b>Phone:</b><?php echo e(Auth::guard('admin')->user()->phone??''); ?></h6>
            <h6 class="text-break"><b>Email:</b><?php echo e(Auth::guard('admin')->user()->email??''); ?></h6>
            <h6><b>Role:</b><?php echo e(CustomHelper::getRoleName(Auth::guard('admin')->user()->role_id??'')); ?></h6>
          </li>
          </div>
          
        </ul>
      </div>
      <div class="navigation-background"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" style="margin-top: 140px;">

          <!-- <li class="nav-item" style="text-align: center;">
            <?php if($role_id != 4){?>
              <h6><b><?php echo e(Auth::guard('admin')->user()->business_name??''); ?></b></h6>
              <h6><b>Name: <?php echo e(Auth::guard('admin')->user()->name??''); ?></b></h6>

            <?php }else{?>
              <h6><b>Name: <?php echo e(Auth::guard('admin')->user()->name??''); ?></b></h6>
            <?php }?>
              
            <h6><b>Phone:</b><?php echo e(Auth::guard('admin')->user()->phone??''); ?></h6>
            <h6 style="text-overflow: clip;white-space: normal; "><b>Email:</b><?php echo e(Auth::guard('admin')->user()->email??''); ?></h6>
            <h6><b>Role:</b><?php echo e(CustomHelper::getRoleName(Auth::guard('admin')->user()->role_id??'')); ?></h6>
          </li> -->



          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME) echo "active"?>"><a href="<?php echo e(url('/admin')); ?>"><i class="fa fa-home"></i><span class="menu-title" data-i18n="">Dashboard</span></a>
          </li>

          <?php if(CustomHelper::isAllowedModule('categories') && CustomHelper::isAllowedSection('categories' , 'list') ||CustomHelper::isAllowedModule('subcategories') && CustomHelper::isAllowedSection('subcategories' , 'list') || CustomHelper::isAllowedModule('courses') && CustomHelper::isAllowedSection('courses' , 'list') || CustomHelper::isAllowedModule('subject') && CustomHelper::isAllowedSection('subject' , 'list') || CustomHelper::isAllowedModule('banners') && CustomHelper::isAllowedSection('banners' , 'list') || CustomHelper::isAllowedSection('videos' , 'list') && CustomHelper::isAllowedModule('videos')|| CustomHelper::isAllowedSection('faqs' , 'list') && CustomHelper::isAllowedModule('faqs')){?>
            <li class=" nav-item"><a href="#"><i class="fas fa-layer-group"></i><span class="menu-title" data-i18n="">Master</span></a>
              <ul class="menu-content">
                <?php if(CustomHelper::isAllowedModule('categories')): ?>
                <?php if(CustomHelper::isAllowedSection('categories' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'categories') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.categories.index')); ?>">Categories</a></li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if(CustomHelper::isAllowedModule('subcategories')): ?>
                <?php if(CustomHelper::isAllowedSection('subcategories' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'subcategories') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.subcategories.index')); ?>">Sub Categories</a></li>
                <?php endif; ?>
                <?php endif; ?>


                <?php if(CustomHelper::isAllowedModule('banners')): ?>
                <?php if(CustomHelper::isAllowedSection('banners' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'banners') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.banners.index')); ?>">Banners</a></li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if(CustomHelper::isAllowedModule('videos')): ?>
                <?php if(CustomHelper::isAllowedSection('videos' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'videos') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.videos.index')); ?>">Videos</a></li>
                <?php endif; ?>
                <?php endif; ?>


                <?php if(CustomHelper::isAllowedModule('roles')): ?>
                <?php if(CustomHelper::isAllowedSection('roles' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'roles') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.roles.index')); ?>">Roles</a></li>
                <?php endif; ?>
                <?php endif; ?>
                <?php if(CustomHelper::isAllowedModule('permission')): ?>
                <?php if(CustomHelper::isAllowedSection('permission' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'permission') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.permission')); ?>">Permission</a></li>
                <?php endif; ?>
                <?php endif; ?>


                <?php if(CustomHelper::isAllowedModule('faqs')): ?>
                <?php if(CustomHelper::isAllowedSection('faqs' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'faqs') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.faqs.index')); ?>">Faqs</a></li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if(CustomHelper::isAllowedModule('subscriptions')): ?>
                <?php if(CustomHelper::isAllowedSection('subscriptions' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'subscriptions') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.subscriptions.index')); ?>">Subscriptions</a></li>
                <?php endif; ?>
                <?php endif; ?>


              </ul>
            </li>
          <?php }?>

          <?php if(CustomHelper::isAllowedModule('countries') && CustomHelper::isAllowedSection('countries' , 'list') ||CustomHelper::isAllowedModule('states') && CustomHelper::isAllowedSection('states' , 'list') || CustomHelper::isAllowedModule('cities') && CustomHelper::isAllowedSection('cities' , 'list')){?>
            <li class=" nav-item"><a href="#"><i class="fa fa-map-marker"></i><span class="menu-title" data-i18n="">Location</span></a>
              <ul class="menu-content">
                <?php if(CustomHelper::isAllowedModule('countries')): ?>
                <?php if(CustomHelper::isAllowedSection('countries' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'countries') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.countries.index')); ?>">Country</a></li>
                <?php endif; ?>
                <?php endif; ?>
                <?php if(CustomHelper::isAllowedModule('states')): ?>
                <?php if(CustomHelper::isAllowedSection('states' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'states') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.states.index')); ?>">States</a></li>
                <?php endif; ?>
                <?php endif; ?>
                <?php if(CustomHelper::isAllowedModule('cities')): ?>
                <?php if(CustomHelper::isAllowedSection('cities' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'cities') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.cities.index')); ?>">Cities</a></li>
                <?php endif; ?>
                <?php endif; ?>

              </ul>
            </li>
          <?php }?>
          <?php if(CustomHelper::isAllowedModule('admins')): ?>
          <?php if(CustomHelper::isAllowedSection('admins' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'admins') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.admins.index')); ?>"><i class="fa fa-users"></i><span class="menu-title" data-i18n="">Admins</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>

          <?php 
        
          $role_id = Auth::guard('admin')->user()->role_id;
          if($role_id == 0){
            $roles = \App\Roles::where('parent_id','>=',$role_id)->where('parent_id','!=',0)->get();
          }elseif($role_id == 7){
            $roles = \App\Roles::where('parent_id','>=',0)->where('parent_id','!=',0)->get();
          }else{
            $roles = \App\Roles::where('parent_id',$role_id)->get();

          }
          // $roles = \App\Roles::where('parent_id','>=',$role_id)->get();
          if(!empty($roles)){
            foreach($roles as $role){?>
             <li class="nav-item <?php //if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.$role->slug) echo "active"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.'.$role->slug.'.index')); ?>"><i class="fa fa-users"></i><span class="menu-title" data-i18n=""><?php echo e($role->name??''); ?></span></a>
             </li>
           <?php }}  ?>






           <?php /* if(CustomHelper::isAllowedModule('superdistributor') && CustomHelper::isAllowedSection('superdistributor' , 'list') ||CustomHelper::isAllowedModule('distributor') && CustomHelper::isAllowedSection('distributor' , 'list') || CustomHelper::isAllowedModule('tsm') && CustomHelper::isAllowedSection('tsm' , 'list')|| CustomHelper::isAllowedModule('seller') && CustomHelper::isAllowedSection('seller' , 'list')){?>
            <li class=" nav-item"><a href="#"><i class="fa fa-map-marker"></i><span class="menu-title" data-i18n="">Party Users</span></a>
              <ul class="menu-content">
                @if(CustomHelper::isAllowedModule('superdistributor'))
                @if(CustomHelper::isAllowedSection('superdistributor' , 'list'))
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'superdistributor') echo "active"?>"><a class="menu-item" href="{{ route($ADMIN_ROUTE_NAME.'.superdistributor.index') }}">Super Distributor</a></li>
                @endif
                @endif
                @if(CustomHelper::isAllowedModule('distributor'))
                @if(CustomHelper::isAllowedSection('distributor' , 'list'))
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'distributor') echo "active"?>"><a class="menu-item" href="{{ route($ADMIN_ROUTE_NAME.'.distributor.index') }}">Distributor</a></li>
                @endif
                @endif
                @if(CustomHelper::isAllowedModule('tsm'))
                @if(CustomHelper::isAllowedSection('tsm' , 'list'))
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'tsm') echo "active"?>"><a class="menu-item" href="{{ route($ADMIN_ROUTE_NAME.'.tsm.index') }}">TSM</a></li>
                @endif
                @endif

                @if(CustomHelper::isAllowedModule('seller'))
                @if(CustomHelper::isAllowedSection('seller' , 'list'))
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'seller') echo "active"?>"><a class="menu-item" href="{{ route($ADMIN_ROUTE_NAME.'.seller.index') }}">Seller</a></li>
                @endif
                @endif

              </ul>
            </li>
          <?php } */?>


          <?php 
          if($roleId !=0){?>
            <?php if(CustomHelper::isAllowedModule('my_coupons')): ?>
          <?php if(CustomHelper::isAllowedSection('my_coupons' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'my_coupons') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.my_coupons.index')); ?>"><i class="fa fa-users"></i><span class="menu-title" data-i18n="">My Coupons</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>

           <?php if(CustomHelper::isAllowedModule('return_coupon')): ?>
          <?php if(CustomHelper::isAllowedSection('return_coupon' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'return_coupon') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.return_coupon.index')); ?>"><i class="fa fa-users"></i><span class="menu-title" data-i18n="">Return Coupons</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?> 

          <?php if(CustomHelper::isAllowedModule('coupon_history')): ?>
          <?php if(CustomHelper::isAllowedSection('coupon_history' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'coupons/my_coupon_history') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.coupons.my_coupon_history')); ?>"><i class="fa fa-users"></i><span class="menu-title" data-i18n="">Return Coupons</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>
          <?php  }
          ?>


          <?php if(CustomHelper::isAllowedModule('coupons')): ?>
          <?php if(CustomHelper::isAllowedSection('coupons' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'coupons') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.coupons.index')); ?>"><i class="fa fa-users"></i><span class="menu-title" data-i18n="">Coupons</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>









          <?php if(CustomHelper::isAllowedModule('user')): ?>
          <?php if(CustomHelper::isAllowedSection('user' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'user') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.user.index')); ?>"><i class="fa fa-users"></i><span class="menu-title" data-i18n="">Users</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>

          <?php if(CustomHelper::isAllowedModule('notifications')): ?>
          <?php if(CustomHelper::isAllowedSection('notifications' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'notifications') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.notifications.index')); ?>"><i class="fa fa-bell"></i><span class="menu-title" data-i18n="">Notification</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>

          <?php if(CustomHelper::isAllowedModule('invoice')): ?>
          <?php if(CustomHelper::isAllowedSection('invoice' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'invoice') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.invoice.index')); ?>"><i class="fa fa-bell"></i><span class="menu-title" data-i18n="">Invoice</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>


          <?php if(CustomHelper::isAllowedModule('reports')): ?>
          <?php if(CustomHelper::isAllowedSection('reports' , 'list')): ?>
          <li class="nav-item <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'reports') echo "active hover"?>"><a href="<?php echo e(route($ADMIN_ROUTE_NAME.'.reports.index')); ?>"><i class="fa fa-bar-chart"></i><span class="menu-title" data-i18n="">Reports</span></a>
          </li>
          <?php endif; ?>
          <?php endif; ?>

          <?php if(CustomHelper::isAllowedModule('admins_list') && CustomHelper::isAllowedSection('admins_list' , 'list') ||CustomHelper::isAllowedModule('telecaller_remarks') && CustomHelper::isAllowedSection('telecaller_remarks' , 'list') ){?>
            <li class=" nav-item"><a href="#"><i class="fa fa-map-marker"></i><span class="menu-title" data-i18n="">Telecaller Data</span></a>
              <ul class="menu-content">
                <?php if(CustomHelper::isAllowedModule('admins_list')): ?>
                <?php if(CustomHelper::isAllowedSection('admins_list' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'admins_list') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.telecaller.admins_list')); ?>">Admins List</a></li>
                <?php endif; ?>
                <?php endif; ?>
                <?php if(CustomHelper::isAllowedModule('telecaller_remarks')): ?>
                <?php if(CustomHelper::isAllowedSection('telecaller_remarks' , 'list')): ?>
                <li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'telecaller_remarks') echo "active"?>"><a class="menu-item" href="<?php echo e(route($ADMIN_ROUTE_NAME.'.telecaller.telecaller_remarks')); ?>">Remarks</a></li>
                <?php endif; ?>
                <?php endif; ?>
              </ul>
            </li>
          <?php }?>
          <li class="nav-item"><a onclick="return confirm('Are You Want To Logout?')" href="<?php echo e(route('admin.logout')); ?>"><i class="fas fa-sign-out-alt"></i><span class="menu-title" data-i18n="">Logout</span></a>
          </li>
         
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
<?php /**PATH /var/www/html/makesecure/resources/views/admin/common/sidebar.blade.php ENDPATH**/ ?>