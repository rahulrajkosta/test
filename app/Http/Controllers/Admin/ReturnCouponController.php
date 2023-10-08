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

use App\Course;
use App\Exam;
use App\Rank;
use App\Question;
use App\Subject;
use App\Topic;
use App\Coupon;
use App\AssignCoupon;
use App\ReturnCoupon;
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



Class ReturnCouponController extends Controller
{


	private $ADMIN_ROUTE_NAME;

  public function __construct(){

    $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


  }



  public function index(Request $request){
   $data = [];
   $user_id = Auth::guard('admin')->user()->id;
   $coupons = [];

   $parent_total_coupons = AssignCoupon::where('is_return',0)->where('is_used',0)->where('child_id',$user_id)->pluck('coupon_id')->toArray();
   $distribute_coupons = AssignCoupon::where('is_return',0)->where('is_used',0)->where('parent_id',$user_id)->pluck('coupon_id')->toArray();
   // $total_coupons = array_merge($parent_total_coupons,$distribute_coupons);
   $couponArr = [];
   if(!empty($parent_total_coupons)){
    foreach ($parent_total_coupons as $key => $value){
      if(!in_array($value, $distribute_coupons))
        $couponArr[$key]=$value;
    }
    $coupons = Coupon::whereIn('id',$couponArr)->paginate(10);
  }




  $data['coupons'] = $coupons;
  $data['couponArr'] = $couponArr;
  return view('admin.return_coupon.index',$data);
}




public function return(Request $request){
  $method = $request->method();
  $user_id = Auth::guard('admin')->user()->id??'';
  if($method == 'POST' || $method == 'post'){
    $rules = [];
    $rules['couponids'] = 'required';
    $this->validate($request,$rules);
    // echo $user_id;
    // prd($user_id);
    //prd($request->couponids);
    $couponids = $request->couponids ??'';
    $child_id = 0;
    if(!empty($couponids)){
      foreach ($couponids as $key => $value) {
        $exist = AssignCoupon::where('is_return',0)->where('coupon_id',$value)->where('child_id',$user_id)->first();

        $exist->is_return = 1;
        $exist->save();
        // if(!empty($exist)){
        $exist_parent = AssignCoupon::where('is_return',0)->where('coupon_id',$value)->where('child_id',$exist->parent_id)->first();
        $dbArray = [];
        $parent = CustomHelper::getUserDetails($exist_parent->parent_id??1);
        $child = CustomHelper::getUserDetails($exist->parent_id);
        $child_id = $exist->parent_id;
        $dbArray['parent_id']= $exist_parent->parent_id??1;
        $dbArray['child_id']= $exist->parent_id;
        $dbArray['coupon_id']= $value;
        $dbArray['parent_role_id']= $parent->role_id;
        $dbArray['child_role_id']= $child->role_id;
        $dbArray['date'] = date('Y-m-d');
        $dbArray['time'] = date('H:i');
        AssignCoupon::insert($dbArray);

        $returnCouponArr = [];
        $returnCouponArr['parent_id'] = $user_id;
        $returnCouponArr['child_id'] = $exist->parent_id;
        $returnCouponArr['coupon_id'] = $value;
        $returnCouponArr['status'] = 1;

        $returnCouponArr['date'] = date('Y-m-d');
        ReturnCoupon::insert($returnCouponArr);

        AssignCoupon::where('coupon_id',$value)->where('child_id',$user_id)->where('parent_id',$exist->parent_id)->delete();
        AssignCoupon::where('id',$exist->id)->delete();
        // }
      }

      $count = count($couponids);
      $parent = Admin::where('id',$user_id)->first();
      $child = Admin::where('id',$child_id)->first();
      Admin::where('id',$parent->id)->update(['no_of_coupons'=>$parent->no_of_coupons-$count]);

      $couponArr1 = [];
      $couponArr1['parent_id'] = $parent->id;
      $couponArr1['no_of_coupons'] = '-'.$count;
      $couponArr1['date'] = date('Y-m-d');
      $couponArr1['time'] = date('H:i');
      $couponArr1['type'] = 'debit';

      CouponTransaction::insert($couponArr1);

      Admin::where('id',$child->id)->update(['no_of_coupons'=>$child->no_of_coupons+$count]);

      $couponArr = [];
      $couponArr['parent_id'] = $child_id;
      $couponArr['no_of_coupons'] = $count;
      $couponArr['date'] = date('Y-m-d');
      $couponArr['time'] = date('H:i');
      $couponArr1['type'] = 'credit';

      CouponTransaction::insert($couponArr);
    }
  }

  return back()->with('alert-success', 'Coupon Return Successfully');

}




}