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
use App\Category;
use App\ReturnCoupon;

use App\Course;
use App\Exam;
use App\Rank;
use App\Question;
use App\Subject;
use App\Roles;
use App\Topic;
use App\Coupon;
use App\AssignCoupon;
use App\CouponTransaction;
use Yajra\DataTables\DataTables;


use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

use App\Imports\QuestionImport;
use App\Exports\UserExport;

use Maatwebsite\Excel\Facades\Excel;

use App\QuestionNotValid;

use Storage;
use DB;
use Hash;



Class MyCouponController extends Controller
{


	private $ADMIN_ROUTE_NAME;

    public function __construct(){

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


    }



    public function index(Request $request){
       $data = [];
       $user_id = Auth::guard('admin')->user()->id;
       $coupons = [];
     // echo $user_id;
       $parent_total_coupons = AssignCoupon::where('is_used',0)->where('is_return',0)->where('child_id',$user_id)->pluck('coupon_id')->toArray();
       $distribute_coupons = AssignCoupon::where('is_used',0)->where('is_return',0)->where('parent_id',$user_id)->pluck('coupon_id')->toArray();
       $couponArr = [];

       if(!empty($parent_total_coupons)){
        foreach ($parent_total_coupons as $key => $value){
          if(!in_array($value, $distribute_coupons))
            $couponArr[$key]=$value;
    }
    $coupons = Coupon::whereIn('id',$couponArr)->paginate(10);
}




$data['coupons'] = $coupons;
return view('admin.my_coupons.index',$data);
}





public function coupons_history(Request $request){
    $data = [];

    $user_id = Auth::guard('admin')->user()->id;

    $user = Admin::where('id',$user_id)->first();
    $data['user'] = $user;
    $transactions = DB::table('coupon_transaction')->where('parent_id',$user_id)->where('parent_id',$user_id)->paginate(10);

    $data['transactions'] = $transactions;
    $data['user_id'] = $user_id;
    $data['slug'] = 'my_coupons';


    return view('admin.my_coupons.coupons_history',$data);
    
}


public function view_coupons(Request $request){
    $data = [];

    $user_id = Auth::guard('admin')->user()->id;
    $date = $request->date??'';
    $type = $request->type??'';

    $role_slug = $request->route()->getPrefix();

    $user = Admin::where('id',$user_id)->first();
    $data['user'] = $user;
    if($type == 'debit'){
        $coupon_ids = ReturnCoupon::where("parent_id",$user_id);
        if(!empty($date)){
            $coupon_ids->whereDate('date',$date);
        }
        $coupon_ids = $coupon_ids->pluck('coupon_id')->toArray();
        $coupons = Coupon::whereIn('id',$coupon_ids)->paginate(10);

    }else{
        $parent_total_coupons = AssignCoupon::where('child_id',$user_id)->pluck('coupon_id')->toArray();
        $distribute_coupons = AssignCoupon::where('parent_id',$user_id)->pluck('coupon_id')->toArray();

        if(!empty($date)){
            $parent_total_coupons = AssignCoupon::where('child_id',$user_id)->whereDate('date',$date)->pluck('coupon_id')->toArray();
            $distribute_coupons = AssignCoupon::where('parent_id',$user_id)->whereDate('date',$date)->pluck('coupon_id')->toArray();
        }

        $coupons = [];
        $couponArr = [];

        $coupons = Coupon::whereIn('id',$parent_total_coupons)->paginate(10);

    }
    $data['coupons'] = $coupons;
    $data['user_id'] = $user_id;


    return view('admin.my_coupons.view_coupons',$data);
}

}