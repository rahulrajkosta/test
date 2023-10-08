<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Auth;
use Validator;
use App\User;
use App\Admin;
use App\Course;

use App\Category;
use App\City;
use App\Roles;
use App\SubCategory;
use App\MobileDetails;
use App\AssignCoupon;
use App\Coupon;

use App\SubscriptionHistory;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use Rap2hpoutre\FastExcel\FastExcel;
use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class MigrationController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {


        // UPDATE `admins` SET `parent_id` = '5' WHERE `admins`.`id` = 13;


        // $admins = Admin::where('role_id',4)->get();
        // foreach($admins as $admin){
        //     $iid = $admin->old_id;
        //     $tsms = Admin::where('parent_id',$iid)->where('role_id',5)->get();
        //     if(!empty($tsms)){
        //         foreach($tsms as $tsm){
        //             Admin::where('id',$tsm->id)->update(['parent_id'=>$admin->id]);
        //         }
        //     }
        // }

        // $partyusers = DB::table('partyusers')->get();
        // foreach($partyusers as $user){
        //     $exist = DB::table('admins')->where('role_id',4)->where('iid',$user->tsm_id)->first();
        //     $dbArray = [];
        //     $dbArray['name'] = $user->name??'';
        //     $dbArray['email'] = $user->email??'';
        //     $dbArray['business_name'] = $user->bussiness_name??'';
        //     $dbArray['phone'] = $user->phone??'';
        //     $dbArray['alternate_phone'] = $user->alternate_phone??'';
        //     $dbArray['gst'] = $user->gst??'';
        //     $dbArray['username'] = $user->username??'';
        //     $dbArray['unique_id'] = $user->unique_id??'';
        //     $dbArray['password_value'] = $user->password??'';
        //     $dbArray['adhar_no'] = $user->adhar_no??'';
        //     $dbArray['pan_no'] = $user->pan_no??'';
        //     $dbArray['address'] = $user->bussiness_address??'';
        //     $dbArray['state_id'] = $user->state_id??'';
        //     $dbArray['city_id'] = $user->city_id??'';
        //     $dbArray['pincode'] = $user->pincode??'';
        //     $dbArray['role_id'] = 5;
        //     $dbArray['parent_id'] = $exist->id??'';
        //     $dbArray['iid'] = $user->id??'';
        //     if($user->status == 'N'){
        //         $dbArray['status'] = 0;

        //     }

        //     // Admin::insert($dbArray);

        // }


        // $mobile_details = DB::table('mobile_details')->get();
        // foreach($mobile_details as $mob){
        //     $admin = Admin::where('role_id',5)->where('iid',$mob->coupon_parent_id)->first();
        //     if(!empty($admin)){
        //         DB::table('mobile_details')->where('id',$mob->id)->update(['coupon_parent_id'=>$admin->id]);
        //     }
        // }



        // DB::table('couponsall')->orderBy('id','asc')->chunk(50, function($couponsall) {
        //     foreach ($couponsall as $coupons) {




        //         $parent = [];

        //         $parent_admin = Admin::where('role_id',4)->where('iid',$coupons->tsm_id)->first();
        //         if(!empty($parent_admin)){
        //             $parent = CustomHelper::getUserDetails($parent_admin->id);
        //         }

        //         $child = [];
        //         if(!empty($coupons->seller_id)){
        //             $child_admin = Admin::where('role_id',5)->where('iid',$coupons->seller_id)->first();
        //                 if(!empty($child_admin)){
        //                     $child = CustomHelper::getUserDetails($child_admin->id);

        //                 }
        //         }



        //         $coupon = Coupon::where('coupon_code',$coupons->coupon_code)->first();
        //         $coupon_id = $coupon->id;
                ////////////////////////////////////////////////////////////////////////////////
                /*$dbArray = [];
                $dbArray['parent_id'] = $child->id??'';
                $dbArray['coupon_code'] = $coupons->coupon_code;
                $dbArray['couponID'] = $coupons->couponID;
                $dbArray['invoice_no'] = $coupons->invoice_no;
                if($coupons->seller_viewed == 'Y'){
                    $dbArray['is_view'] = 1;
                }else{
                    $dbArray['is_view'] = 0;
                }
                if(!empty($coupons->user_id)){
                    $dbArray['is_used'] = 1;
                }else{
                    $dbArray['is_used'] = 0;
                }
                $dbArray['date'] = date('Y-m-d',strtotime($coupons->superdistributor_assign_date));
                $dbArray['time'] = date('H:i:s',strtotime($coupons->superdistributor_assign_date));
                $coupon_id = Coupon::insertGetId($dbArray);*/


                ////////////////////////////////////////////////////////////////////////////////


        //         if(!empty($child)){
        //         $dbArray1 = [];
        //         $dbArray1['coupon_id'] = $coupon_id;
        //         $dbArray1['parent_id'] = $parent->id??'';
        //         $dbArray1['child_id'] = $child->id??'';
        //         $dbArray1['parent_role_id'] = 4;
        //         $dbArray1['child_role_id'] = 5;
        //         if($coupons->seller_viewed == 'Y'){
        //             $dbArray1['is_view'] = 1;
        //         }else{
        //             $dbArray1['is_view'] = 0;
        //         }
        //         if(!empty($coupons->user_id)){
        //             $dbArray1['is_used'] = 1;
        //         }else{
        //             $dbArray1['is_used'] = 0;
        //         }
        //         $dbArray1['date'] = date('Y-m-d',strtotime($coupons->seller_assign_date));
        //         $dbArray1['time'] = date('H:i:s',strtotime($coupons->seller_assign_date));
        //             AssignCoupon::insert($dbArray1);
        //         }
        //     }
        // });


            //     Admin::orderBy('id','asc')->chunk(50, function($admins) {
            //        foreach ($admins as $admin) {
            //         $no_of_coupons = CustomHelper::getOwnCoupons($admin->id);
            //         Admin::where('id',$admin->id)->update(['password'=>bcrypt($admin->password_value),'no_of_coupons'=>count($no_of_coupons)]);
            //     }
            // });





                
                    // $cities = DB::table('cities_old')->get();
                    // foreach($cities as $city){
                    //     $dbArray = [];
                    //     $dbArray['name'] = $city->city??''; 
                    //     $dbArray['state_id'] = $city->state_id??''; 
                    //     $dbArray['country_id'] = '101'; 
                    //     $dbArray['icon'] = ''; 
                    //     $dbArray['priority'] = 0; 
                    //     $dbArray['is_delete'] = 0; 
                    //     $dbArray['status'] = 1; 
                    //     DB::table('cities')->insert($dbArray);
                    // }

            //  Admin::orderBy('id','asc')->chunk(50, function($admins) {
            //        foreach ($admins as $admin) {
            //         $assigncoupon = AssignCoupon::select('date')->where('child_id',$admin->id)->groupBy('date')->get();
            //         if(!empty($assigncoupon)){
            //             foreach($assigncoupon as $ass){
            //                 $asssss = AssignCoupon::select('date')->where('child_id',$admin->id)->whereDate('date',$ass->date)->count();
            //                 $time = AssignCoupon::select('time')->where('child_id',$admin->id)->where('date',$ass->date)->first();

            //                 $dbArray = [];
            //                 $dbArray['parent_id'] = $admin->id??'';
            //                 $dbArray['no_of_coupons'] = $asssss??'0';
            //                 $dbArray['date'] = $ass->date??'';
            //                 $dbArray['time'] = $time->time??'';
            //                 $dbArray['status'] = 1;
            //                DB::table('coupon_transaction')->insert($dbArray);
            //             }
            //         }
            //     }
            // }); 
















                $admins = Admin::where('role_id',2)->get();
                foreach($admins as $admin){
                    $zero = '';
                    if($admin->id <=9){
                        $zero = '0';
                    }
                    $unique_id = 'CCTSDST'.$zero.$admin->id;
                    Admin::where('id',$admin->id)->update(['unique_id'=>$unique_id]);
                }

                die;










                $role_slug = $request->route()->getPrefix();

                $slug = str_replace('admin/', '', $role_slug) ;

                $roles = Roles::where('slug',$slug)->first();

                $search = isset($request->search) ? $request->search :'';
                $role_id = isset($request->role_id) ? $request->role_id :'';
                $user_role_id =Auth::guard('admin')->user()->role_id??'';
                $user_id_login =Auth::guard('admin')->user()->id??'';

                $parent_ids = [];

                $fetch_role_id = $request->fetch_role_id ??0;
                $user_id = $request->user_id ??0;
                $key = $request->key ??0;
                $ids = [];
                $superdist_ids = [];
                // echo $role_id;



                if(!empty($fetch_role_id) && !empty($user_id) && !empty($key) && $user_role_id !=0){
                    $ids = CustomHelper::getChildIds($fetch_role_id,$user_id,$key);
                }

                if(!empty($search)){
                    $fetch_role_id = $roles->id;
                    $user_id = $user_id_login;
                    $key = 1;
                    $ids = CustomHelper::getChildIds($fetch_role_id,$user_id,$key);

                }




                $parent_id = '';
                // $parent_id = Auth::guard('admin')->user()->parent_id;
                if($user_role_id != 7){
                    $parent_id = Auth::guard('admin')->user()->id;
                }
                $parent_id = isset($request->parent_id) ? $request->parent_id :$parent_id;
                $data['search'] = $search;

                if(!empty($parent_id) && $parent_id != 1){
                    $parent_ids[] = $parent_id;

                }

                if($user_role_id == 7){
                    $admin_data = Admin::where('id',$user_id_login)->first();
                    $superdist_id= $admin_data->superdist_id??'';
                    if(!empty($superdist_id)){
                        $superdist_id = explode(",", $superdist_id);
                        // foreach($superdist_id as $sdist_id){
                        if($roles->slug == 'superdistributor'){
                            foreach($superdist_id as $sdist_id){
                                $superdist_ids[] = $sdist_id??'';
                            }
                        }elseif($roles->slug == 'distributor'){
                            foreach($superdist_id as $sdist_id){
                                $parent_ids[] = $sdist_id;
                            }
                        }elseif($roles->slug == 'tsm'){
                            $dis_ids = [];
                            foreach($superdist_id as $sdist_id){
                                $dis_ids[] = $sdist_id;
                            }

                            $distributors = Admin::whereIn('parent_id',$dis_ids)->pluck('id')->toArray();
                            if(!empty($distributors)){
                                foreach ($distributors as $key => $value) {
                                    $parent_ids[] = $value;

                                }
                            }
                        }elseif($roles->slug == 'seller'){
                            $dis_ids = [];
                            foreach($superdist_id as $sdist_id){
                                $dis_ids[] = $sdist_id;
                            }
                            $distributors_ids = [];
                            $distributors = Admin::whereIn('parent_id',$dis_ids)->pluck('id')->toArray();
                            if(!empty($distributors)){
                                foreach ($distributors as $key => $value) {
                                    $distributors_ids[] = $value;    
                                }
                            }
                            if(!empty($distributors_ids)){
                                $tsms = Admin::whereIn('parent_id',$distributors_ids)->pluck('id')->toArray();
                                if(!empty($tsms)){
                                    foreach ($tsms as $key => $value) {
                                        $parent_ids[] = $value;    
                                    }
                                }
                            }
                        }

                    }

                }



                
                if(!empty($ids)){
                    foreach ($ids as $key => $value) {
                        $parent_ids[] = $value;
                    }
                }


// \DB::enableQueryLog();
                $users = Admin::where('is_delete','0')->where('role_id',$roles->id)->orderBy('id','desc');

                if(!empty($parent_ids) && $role_id !=0){
                   $users->whereIn('parent_id', $parent_ids);
               }
               if(!empty($search)){
                $users->where('name', 'like', '%' . $search . '%');
                $users->orWhere('email', 'like', '%' . $search . '%');
                $users->orWhere('phone', 'like', '%' . $search . '%');
                $users->orWhere('business_name', 'like', '%' . $search . '%');
                $users->orWhere('unique_id', 'like', '%' . $search . '%');
            }
        // prd($state_ids);
        // if(!empty($state_ids)){
        //      $users->whereIn('state_id', $state_ids);

        // }
            if(!empty($superdist_ids)){
               $users->whereIn('id', $superdist_ids);

           }
           $users = $users->paginate(10);
       // dd(\DB::getQueryLog());
           $data['users'] = $users;
           $data['slug'] = $slug;
           $data['roles'] = $roles;
           $data['title'] = $roles->name??'';
           $data['parent_id'] = $parent_id??'';
           $child_role = Roles::where('parent_id',$roles->id)->first();
           $data['child_role'] = $child_role;
           return view('admin.party_users.index',$data);
       }


       public function super_distributor(Request $request){

        $super_distributors = DB::table('distributor')->where('distributor_id',0)->get();
        if(!empty($super_distributors)){
            foreach($super_distributors as $user){
                $exist = Admin::where('role_id',2)->where('username',$user->username)->where('iid',$user->id)->first();
                
                $dbArray = [];
                $dbArray['name'] = $user->name??'';
                $dbArray['email'] = $user->email??'';
                $dbArray['business_name'] = $user->business_name??'';
                $dbArray['phone'] = $user->phone??'';
                $dbArray['alternate_phone'] = $user->alternate_phone??'';
                $dbArray['gst'] = $user->gst??'';
                $dbArray['username'] = $user->username??'';
                $dbArray['unique_id'] = $user->referral_code??'';
                $dbArray['password_value'] = $user->password??'';
                $dbArray['adhar_no'] = $user->adhar_no??'';
                $dbArray['password'] = bcrypt($user->password)??'';
                $dbArray['pan_no'] = $user->pan_no??'';
                $dbArray['address'] = $user->bussiness_address??'';
                $dbArray['state_id'] = $user->state_id??'';
                $dbArray['city_id'] = $user->city_id??'';
                $dbArray['pincode'] = $user->pincode??'';
                $dbArray['role_id'] = 2;
                $dbArray['parent_id'] = '1';
                $dbArray['iid'] = $user->id??'';
                $dbArray['created_at'] = $user->added_on??'';
                if($user->status == 'N'){
                    $dbArray['status'] = 0;
                }
                if(empty($exist)){
                    Admin::insert($dbArray);
                }else{
                    Admin::where('id',$exist->id)->update($dbArray);

                }
            }
        }   
    }
    




    public function distributor(Request $request){
        $distributors = DB::table('distributor')->where('distributor_id','!=',0)->get();
        if(!empty($distributors)){
            foreach($distributors as $user){
                $exist = Admin::where('role_id',3)->where('username',$user->username)->where('iid',$user->id)->first();
                
                $exist_new = DB::table('admins')->where('role_id',2)->where('iid',$user->distributor_id)->first();
                $dbArray = [];
                $dbArray['name'] = $user->name??'';
                $dbArray['email'] = $user->email??'';
                $dbArray['business_name'] = $user->business_name??'';
                $dbArray['phone'] = $user->phone??'';
                $dbArray['alternate_phone'] = $user->alternate_phone??'';
                $dbArray['gst'] = $user->gst??'';
                $dbArray['username'] = $user->username??'';
                $dbArray['unique_id'] = $user->referral_code??'';
                $dbArray['password_value'] = $user->password??'';
                $dbArray['adhar_no'] = $user->adhar_no??'';
                $dbArray['pan_no'] = $user->pan_no??'';
                $dbArray['address'] = $user->bussiness_address??'';
                $dbArray['state_id'] = $user->state_id??'';
                $dbArray['city_id'] = $user->city_id??'';
                $dbArray['pincode'] = $user->pincode??'';
                $dbArray['password'] = bcrypt($user->password)??'';
                $dbArray['created_at'] = $user->added_on??'';

                $dbArray['role_id'] = 3;
                $dbArray['parent_id'] = $exist_new->id??'';
                $dbArray['iid'] = $user->id??'';
                if($user->status == 'N'){
                    $dbArray['status'] = 0;
                }
                if(empty($exist)){
                    Admin::insert($dbArray);
                }else{
                    Admin::where('id',$exist->id)->update($dbArray);

                }
            }   
        }
    }


    public function tsm(Request $request){
        $tsms = DB::table('tsm')->get();
        if(!empty($tsms)){
            foreach($tsms as $user){
                $exist = Admin::where('role_id',4)->where('username',$user->username)->where('iid',$user->id)->first();
                
                $exist_new = DB::table('admins')->where('role_id',3)->where('iid',$user->distributor_id)->first();
                $dbArray = [];
                // $dbArray['name'] = $user->name??'';
                // $dbArray['email'] = $user->email??'';
                // $dbArray['business_name'] = $user->bussiness_name??'';
                // $dbArray['phone'] = $user->phone??'';
                // $dbArray['alternate_phone'] = $user->alternate_phone??'';
                // $dbArray['gst'] = $user->gst??'';
                // $dbArray['username'] = $user->username??'';
                // $dbArray['unique_id'] = $user->unique_id??'';
                // $dbArray['password_value'] = $user->password??'';
                // $dbArray['adhar_no'] = $user->adhar_no??'';
                // $dbArray['pan_no'] = $user->pan_no??'';
                // $dbArray['address'] = $user->bussiness_address??'';
                // $dbArray['state_id'] = $user->state_id??'';
                // $dbArray['city_id'] = $user->city_id??'';
                // $dbArray['pincode'] = $user->pincode??'';
                // $dbArray['role_id'] = 4;
                // $dbArray['password'] = bcrypt($user->password)??'';
                $dbArray['created_at'] = $user->added_on??'';

                // $dbArray['parent_id'] = $exist_new->id??'';
                // $dbArray['iid'] = $user->id??'';
                // if($user->status == 'N'){
                //     $dbArray['status'] = 0;
                // }
                if(empty($exist)){
                    Admin::insert($dbArray);
                }else{
                    Admin::where('id',$exist->id)->update($dbArray);

                }
            }   
        }
    }


    public function seller(Request $request){
        $sellers = DB::table('seller')->get();
        if(!empty($sellers)){
            foreach($sellers as $user){
                $exist = Admin::where('role_id',5)->where('username',$user->username)->where('iid',$user->id)->first();
                
                $exist_new = DB::table('admins')->where('role_id',4)->where('iid',$user->tsm_id)->first();
                $dbArray = [];
                    // $dbArray['name'] = $user->name??'';
                    // $dbArray['email'] = $user->email??'';
                    // $dbArray['business_name'] = $user->bussiness_name??'';
                    // $dbArray['phone'] = $user->phone??'';
                    // $dbArray['alternate_phone'] = $user->alternate_phone??'';
                    // $dbArray['gst'] = $user->gst??'';
                    // $dbArray['username'] = $user->username??'';
                    // $dbArray['unique_id'] = $user->unique_id??'';
                    // $dbArray['password_value'] = $user->password??'';
                    // $dbArray['password'] = bcrypt($user->password)??'';
                    // $dbArray['adhar_no'] = $user->adhar_no??'';
                    // $dbArray['pan_no'] = $user->pan_no??'';
                    // $dbArray['address'] = $user->bussiness_address??'';
                    // $dbArray['state_id'] = $user->state_id??'';
                    // $dbArray['city_id'] = $user->city_id??'';
                    // $dbArray['pincode'] = $user->pincode??'';
                    // $dbArray['role_id'] = 5;
                    // $dbArray['parent_id'] = $exist_new->id??'';
                    // $dbArray['iid'] = $user->id??'';


                $dbArray['created_at'] = $user->added_on??'';

                    // if($user->status == 'N'){
                    //     $dbArray['status'] = 0;
                    // }
                if(empty($exist)){
                    Admin::insert($dbArray);
                }else{
                    Admin::where('id',$exist->id)->update($dbArray);
                    
                }
            }   
        }
    }

    public function super_distributor_coupons(Request $request){
        DB::table('couponsall')->orderBy('id','asc')->chunk(100, function($couponsall) {
            foreach ($couponsall as $coupons) {
                $parent = [];
                // $parent_admin = Admin::where('role_id',4)->where('iid',$coupons->tsm_id)->first();
                // if(!empty($parent_admin)){
                //     $parent = CustomHelper::getUserDetails($parent_admin->id);
                // }
                $child = [];
                if(!empty($coupons->distributor_id)){
                    $child_admin = Admin::where('role_id',2)->where('iid',$coupons->distributor_id)->first();
                    if(!empty($child_admin)){
                        $child = CustomHelper::getUserDetails($child_admin->id);
                    }
                }
                // $coupon = Coupon::where('coupon_code',$coupons->coupon_code)->first();
                // $coupon_id = $coupon->id;
                ////////////////////////////////////////////////////////////////////////////////
                $dbArray = [];
                $dbArray['parent_id'] = $child->id??'';
                $dbArray['coupon_code'] = $coupons->coupon_code;
                $dbArray['couponID'] = $coupons->couponID;
                $dbArray['invoice_no'] = $coupons->invoice_no;
                if($coupons->seller_viewed == 'Y'){
                    $dbArray['is_view'] = 1;
                }else{
                    $dbArray['is_view'] = 0;
                }
                if(!empty($coupons->user_id)){
                    $dbArray['is_used'] = 1;
                }else{
                    $dbArray['is_used'] = 0;
                }
                $dbArray['date'] = date('Y-m-d',strtotime($coupons->superdistributor_assign_date));
                $dbArray['time'] = date('H:i:s',strtotime($coupons->superdistributor_assign_date));
                // $coupons_exist = Coupon::where('coupon_code',$coupons->coupon_code)->where('parent_id',$child->id)->first();
                // if(empty($coupons_exist)){
                $coupon_id = Coupon::insertGetId($dbArray);
                if(!empty($child)){
                    $dbArray1 = [];
                    $dbArray1['coupon_id'] = $coupon_id;
                    $dbArray1['parent_id'] = 1;
                    $dbArray1['child_id'] = $child->id??'';
                    $dbArray1['parent_role_id'] = 1;
                    $dbArray1['child_role_id'] = 2;
                    if($coupons->seller_viewed == 'Y'){
                        $dbArray1['is_view'] = 1;
                    }else{
                        $dbArray1['is_view'] = 0;
                    }
                    if(!empty($coupons->user_id)){
                        $dbArray1['is_used'] = 1;
                    }else{
                        $dbArray1['is_used'] = 0;
                    }
                    $dbArray1['date'] = date('Y-m-d',strtotime($coupons->superdistributor_assign_date));
                    $dbArray1['time'] = date('H:i:s',strtotime($coupons->superdistributor_assign_date));
                        // $assign_exist = AssignCoupon::where('coupon_id',$coupon_id)->where('parent_id',1)->where('child_id',$child->id)->first();
                        // if(empty($assign_exist)){
                    AssignCoupon::insert($dbArray1);
                        // }
                }
                // }
                ////////////////////////////////////////////////////////////////////////////////

            }
        });
    }

    public function distributor_coupons(Request $request){
        DB::table('couponsall')->select('distributor_id','sub_distributor_id','coupon_code','seller_viewed','user_id','distributor_assign_date')->orderBy('id','asc')->chunk(100, function($couponsall) {
            foreach ($couponsall as $coupons) {
                $parent = [];
                $parent_admin = Admin::where('role_id',2)->where('iid',$coupons->distributor_id)->first();
                if(!empty($parent_admin)){
                    $parent = CustomHelper::getUserDetails($parent_admin->id);
                }
                $child = [];
                if(!empty($coupons->sub_distributor_id)){
                    $child_admin = Admin::where('role_id',3)->where('iid',$coupons->sub_distributor_id)->first();
                    if(!empty($child_admin)){
                        $child = CustomHelper::getUserDetails($child_admin->id);
                    }
                }
                $coupon = Coupon::where('coupon_code',$coupons->coupon_code)->first();
                $coupon_id = $coupon->id??'';

                if(!empty($child)){
                    $dbArray1 = [];
                    $dbArray1['coupon_id'] = $coupon_id;
                    $dbArray1['parent_id'] = $parent->id??'';
                    $dbArray1['child_id'] = $child->id??'';
                    $dbArray1['parent_role_id'] = 2;
                    $dbArray1['child_role_id'] = 3;
                    if($coupons->seller_viewed == 'Y'){
                        $dbArray1['is_view'] = 1;
                    }else{
                        $dbArray1['is_view'] = 0;
                    }
                    if(!empty($coupons->user_id)){
                        $dbArray1['is_used'] = 1;
                    }else{
                        $dbArray1['is_used'] = 0;
                    }
                    $dbArray1['date'] = date('Y-m-d',strtotime($coupons->distributor_assign_date));
                    $dbArray1['time'] = date('H:i:s',strtotime($coupons->distributor_assign_date));

                    AssignCoupon::insert($dbArray1);

                }
            }
        });
    }



    public function tsm_coupons(Request $request){
        DB::table('couponsall')->select('tsm_id','sub_distributor_id','coupon_code','seller_viewed','user_id','tsm_assign_date')->orderBy('id','asc')->chunk(100, function($couponsall) {
            foreach ($couponsall as $coupons) {
                $parent = [];
                $parent_admin = Admin::where('role_id',3)->where('iid',$coupons->sub_distributor_id)->first();
                if(!empty($parent_admin)){
                    $parent = CustomHelper::getUserDetails($parent_admin->id);
                }
                $child = [];
                if(!empty($coupons->tsm_id)){
                    $child_admin = Admin::where('role_id',4)->where('iid',$coupons->tsm_id)->first();
                    if(!empty($child_admin)){
                        $child = CustomHelper::getUserDetails($child_admin->id);
                    }
                }
                $coupon = Coupon::where('coupon_code',$coupons->coupon_code)->first();
                $coupon_id = $coupon->id??'';

                if(!empty($child)){
                    $dbArray1 = [];
                    $dbArray1['coupon_id'] = $coupon_id;
                    $dbArray1['parent_id'] = $parent->id??'';
                    $dbArray1['child_id'] = $child->id??'';
                    $dbArray1['parent_role_id'] = 3;
                    $dbArray1['child_role_id'] = 4;
                    if($coupons->seller_viewed == 'Y'){
                        $dbArray1['is_view'] = 1;
                    }else{
                        $dbArray1['is_view'] = 0;
                    }
                    if(!empty($coupons->user_id)){
                        $dbArray1['is_used'] = 1;
                    }else{
                        $dbArray1['is_used'] = 0;
                    }
                    $dbArray1['date'] = date('Y-m-d',strtotime($coupons->tsm_assign_date));
                    $dbArray1['time'] = date('H:i:s',strtotime($coupons->tsm_assign_date));
                        // $assign_exist = AssignCoupon::where('coupon_id',$coupon_id)->where('parent_id',$parent->id)->where('child_id',$child->id)->first();

                        // if(empty($assign_exist)){
                            // prd($dbArray1);
                    AssignCoupon::insert($dbArray1);
                        // }
                }
            }
        });
    }



    public function seller_coupons(Request $request){
        DB::table('couponsall')->select('tsm_id','seller_id','coupon_code','seller_viewed','user_id','seller_assign_date')->orderBy('id','asc')->chunk(100, function($couponsall) {
            foreach ($couponsall as $coupons) {
                $parent = [];
                $parent_admin = Admin::where('role_id',4)->where('iid',$coupons->tsm_id)->first();
                if(!empty($parent_admin)){
                    $parent = CustomHelper::getUserDetails($parent_admin->id);
                }
                $child = [];
                if(!empty($coupons->seller_id)){
                    $child_admin = Admin::where('role_id',5)->where('iid',$coupons->seller_id)->first();
                    if(!empty($child_admin)){
                        $child = CustomHelper::getUserDetails($child_admin->id);
                    }
                }
                $coupon = Coupon::where('coupon_code',$coupons->coupon_code)->first();
                $coupon_id = $coupon->id??'';

                if(!empty($child)){
                    $dbArray1 = [];
                    $dbArray1['coupon_id'] = $coupon_id;
                    $dbArray1['parent_id'] = $parent->id??'';
                    $dbArray1['child_id'] = $child->id??'';
                    $dbArray1['parent_role_id'] = 4;
                    $dbArray1['child_role_id'] = 5;
                    if($coupons->seller_viewed == 'Y'){
                        $dbArray1['is_view'] = 1;
                    }else{
                        $dbArray1['is_view'] = 0;
                    }
                    if(!empty($coupons->user_id)){
                        $dbArray1['is_used'] = 1;
                    }else{
                        $dbArray1['is_used'] = 0;
                    }
                    $dbArray1['date'] = date('Y-m-d',strtotime($coupons->seller_assign_date));
                    $dbArray1['time'] = date('H:i:s',strtotime($coupons->seller_assign_date));
                        // $assign_exist = AssignCoupon::where('coupon_id',$coupon_id)->where('parent_id',$parent->id)->where('child_id',$child->id)->first();

                        // if(empty($assign_exist)){
                            // prd($dbArray1);
                    AssignCoupon::insert($dbArray1);
                        // }
                }
            }
        });
    }


    public function update_no_of_coupons(){
       Admin::orderBy('id','asc')->chunk(50, function($admins) {
         foreach ($admins as $admin) {
            $parent_total_coupons = AssignCoupon::select('id')->where('is_return',0)->where('child_id',$admin->id)->count();
            $distribute_coupons = AssignCoupon::select('id')->where('is_return',0)->where('parent_id',$admin->id)->count();
            $no_of_coupons = $parent_total_coupons - $distribute_coupons;
            Admin::where('id',$admin->id)->update(['no_of_coupons'=>$no_of_coupons]);
        }
    });
   }


   public function update_coupon_transaction(){
       Admin::orderBy('id','asc')->chunk(50, function($admins) {
         foreach ($admins as $admin) {
            $assigncoupon = AssignCoupon::select('date')->where('child_id',$admin->id)->groupBy('date')->get();
            if(!empty($assigncoupon)){
                foreach($assigncoupon as $ass){
                    $asssss = AssignCoupon::select('date')->where('child_id',$admin->id)->whereDate('date',$ass->date)->count();
                    $time = AssignCoupon::select('time')->where('child_id',$admin->id)->where('date',$ass->date)->first();

                    $dbArray = [];
                    $dbArray['parent_id'] = $admin->id??'';
                    $dbArray['no_of_coupons'] = $asssss??'0';
                    $dbArray['date'] = $ass->date??'';
                    $dbArray['time'] = $time->time??'';
                    $dbArray['status'] = 1;
                    DB::table('coupon_transaction')->insert($dbArray);
                }
            }
        }
    }); 

   }



   public function import_users(){
       DB::table('users_old')->orderBy('id','asc')->chunk(50, function($admins) {
         foreach ($admins as $admin) {
            $dbArray = [];
            $dbArray['id'] = $admin->id??'';
            $dbArray['name'] = $admin->name??'';
            $dbArray['email'] = $admin->email??'';
            $dbArray['mobile'] = $admin->mobile??'';
            $dbArray['alternate_phone'] = $admin->alternate_phone??'';
            $dbArray['adhar_no'] = $admin->adhar_no??'';
            $dbArray['pan_no'] = $admin->pan_no??'';
            $dbArray['phone_model'] = $admin->phone_model??'';
            $dbArray['image'] = $admin->image??'';
            $dbArray['android_version'] = $admin->android_version??'';
            $dbArray['longitude'] = $admin->longitude??'';
            $dbArray['latitude'] = $admin->latitude??'';
            DB::table('users')->insert($dbArray);

        }
    }); 

   }
   public function import_user_logins(){
       DB::table('user_login_old')->orderBy('loginID')->chunk(50, function($admins) {
         foreach ($admins as $admin) {
            $dbArray = [];
            $dbArray['id'] = $admin->loginID??'';
            $dbArray['user_id'] = $admin->userID??'';
            $dbArray['deviceType'] = $admin->device_type??'';
            $dbArray['role'] = $admin->role??'';
            $dbArray['deviceID'] = $admin->deviceID??'';
            $dbArray['deviceToken'] = $admin->device_token??'';
            $dbArray['ip_address'] = $admin->ip_address??'';
            DB::table('user_logins')->insert($dbArray);

        }
    }); 

   }




   public function import_mobile_details(){
       DB::table('all_mobile_detttttt')->orderBy('id')->chunk(50, function($admins) {
         foreach ($admins as $admin) {
            $parent = Admin::where('role_id',5)->where('iid',$admin->coupon_parent_id)->first();
            $admin->coupon_parent_id = $parent->id;

            $admin = (array)$admin;

            DB::table('mobile_details')->insert($admin);

        }
    }); 

   }


   public function update_is_used(){
       DB::table('coupons')->orderBy('id')->chunk(500, function($assign_coupons) {
         foreach ($assign_coupons as $coupon) {
            if($coupon->is_used == 1){
                AssignCoupon::where('coupon_id',$coupon->id)->update(['is_used'=>1,'is_view'=>1]);
            }

            if($coupon->is_used == 1){
                    // AssignCoupon::where('coupon_id',$coupon->id)->update(['is_used'=>1,'is_view'=>1]);
            }
        }
    }); 

   }
   public function update_emis(){
     MobileDetails::orderBy('id')->chunk(10, function($assign_coupons) {
       foreach ($assign_coupons as $mob) {
           for ($i=0; $i < $mob->total_months; $i++) { 
            $start_date1 = date('Y-m-d',strtotime("+".$i." months", strtotime($mob->start_date)));
            $dbArray2 = [];
            $dbArray2['mobile_id'] = $mob->id;
            $dbArray2['user_id'] = $mob->user_id;
            $dbArray2['date'] = $start_date1;
            $dbArray2['amount'] = $mob->emi;
            $dbArray2['status'] = 1;
            $dbArray2['paid_status'] = 0;
            // echo "<pre>";
            // print_r($start_date1);
            DB::table('emis')->insert($dbArray2);
        }
    }
}); 


 }

 public function update_all_emi($total_months,$start_date,$coupon_id,$coupon_user_id,$emi){
    for ($i=0; $i < $total_months; $i++) { 
        echo "<pre>";
        echo $start_date;
        $start_date1 = date('Y-m-d',strtotime("+".$i." months", strtotime($start_date)));
        $dbArray2 = [];
        $dbArray2['mobile_id'] = $coupon_id;
        $dbArray2['user_id'] = $coupon_user_id;
        $dbArray2['date'] = $start_date1;
        $dbArray2['amount'] = $emi;
        $dbArray2['status'] = 1;
        $dbArray2['paid_status'] = 0;

        // print_r($dbArray2);
        // DB::table('emis')->insert($dbArray2);
    }
}

}




