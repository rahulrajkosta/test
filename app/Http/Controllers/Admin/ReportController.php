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

use App\Roles;
use App\Category;
use App\City;
use App\SubCategory;
use App\AssignCoupon;
use App\Reports;
use App\MobileDetails;
use App\Coupon;

use App\SubscriptionHistory;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use Rap2hpoutre\FastExcel\FastExcel;
use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;
use App\Imports\MobileDetailsImport;
// use Rap2hpoutre\FastExcel\FastExcel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
// use Maatwebsite\Excel\Facades\Excel;
// use PhpOffice\PhpSpreadsheet\IOFactory;




Class ReportController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

    }

    public function index(Request $request){
        $data = [];

        $user_role_id =Auth::guard('admin')->user()->role_id??'';
        $user_id = Auth::guard('admin')->user()->id??'';


        $reports = Reports::latest();

        if($user_role_id != 0 ){
            $reports->where('added_by',$user_id);
        }
        // prd($request->toArray());

        $reports = $reports->paginate(10);


        $data['reports'] = $reports;

        return view('admin.reports.index',$data);
    }









    public function export(Request $request){
        $search = isset($request->search) ? $request->search:'';
        $users = User::select('id','name','email','wallet','referral_code');
        if(!empty($search)){
            $users->where('name', 'like', '%' . $search . '%');
            $users->orWhere('email', 'like', '%' . $search . '%');

        }
        $users = $users->get();
        if(!empty($users) && $users->count() > 0){
            foreach($users as $user){
               $userArr = [];
               $userArr['ID'] = $user->id;
               $userArr['Name'] = $user->name ?? '';
               $userArr['Email'] = $user->email ?? '';
               $userArr['Wallet'] = $user->wallet ?? 0;
               $userArr['Referal Code'] = $user->referral_code ?? 0;
               $exportArr[] = $userArr;
           }
           $filedNames = array_keys($exportArr[0]);
           $fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';
           // return Excel::download(new ReportExport($exportArr, $filedNames), $fileName);
           Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');
       }

   }

   public function export_reports_tsm(){



     Coupon::select('id','parent_id')
        // ->where('seller_id',null)
        ->chunk(1000, function($inspectors) {
            foreach ($inspectors as $inspector) {
            // Coupon::where('id',$inspector->id)->update(['sdist_id'=>$inspector->parent_id]);
                $assign = AssignCoupon::select('id','date')->where('coupon_id',$inspector->id)->where('child_role_id',4)->first();
                if(!empty($assign)){
                    Coupon::where('id',$inspector->id)->update(['tsm_date'=>$assign->date]);
                }

            }
        });
        die;

        

    $assign_coupons = DB::select("SELECT * FROM `mobile_details` WHERE  date(`date_of_purchase`) >='2022-12-01' AND date(`date_of_purchase`) <= '2022-12-31'");

    $exportArr = [];
    foreach($assign_coupons as $coup){
        $userArr = [];

        $userArr['coupon_id'] = $coup->coupon_id??'';
        $coupons_data = Coupon::where('couponID',$coup->coupon_id)->first();
        $assign = AssignCoupon::where('coupon_id',$coupons_data->id)->where('child_role_id',5)->first();
        $tsm = Admin::select('name','unique_id')->where('id',$assign->parent_id)->first();
        $userArr['tsm_id'] = $tsm->name . $tsm->unique_id;

        $seller = Admin::select('business_name','unique_id')->where('id',$assign->child_id)->first();
        $userArr['seller_id'] = $seller->business_name .'-' .$seller->unique_id;
        $userArr['date'] = $coup->date_of_purchase??'';
        $userArr['used_status'] = "Used" ;
        $userArr['user_name'] = $coup->user_name??'';
        $userArr['user_phone'] =$coup->user_phone??'';
        $userArr['user_alt_phone'] =$coup->user_phone??'';
        $userArr['mobile_name'] =$coup->mobile_name??'';
        $userArr['imei'] =$coup->imei_no??'';
        $userArr['date_of_purchase'] =$coup->date_of_purchase??'';
        $userArr['phone_status'] =$coup->phone_status??'';
        $userArr['finance_name'] =$coup->loan_provider??'';
        $exportArr[] = $userArr;
        DB::table('export_data')->insert($userArr);
    }






}

public function export_reports(){


    $report = Reports::where('is_complete',0)->first();
    if(!empty($report)){

        if($report->type == 'all_coupon_allocation'){
            $this->all_coupon_allocation($report);
        }
        if($report->type == 'role_coupon_allocation'){
                // echo 'test allocation report';
            $this->role_coupon_allocation($report);
        }
        if($report->type == 'role_closing_coupon'){
            $this->role_closing_coupon($report);
        }
        if($report->type == 'role_info'){
            $this->role_info($report);
        }
        if($report->type == 'user_export'){
            $this->user_export($report);
        }

    }
    echo 1;
}

public function role_info($report){
    $role = $report->role??'';
    $state_id = $report->state_id??'';
    $city_id = $report->city_id??'';
    $exportArr = [];
    if(!empty($role)){
        $query = Admin::where('role_id',$role);
        if(!empty($state_id)){
            $query->where('state_id',$state_id);
        }
        if(!empty($city_id)){
            $query->where('city_id',$city_id);
        }

        $query->chunk(50, function($users) use (&$exportArr,&$role) {
            foreach ($users as $user) {
               $roles = Roles::where('id',$role)->first();
               $parent = CustomHelper::getUserDetails($user->parent_id);
               $userArr = [];
               $userArr["Unique ID"] = $user->unique_id??'';
               $userArr["Business Name"] = $user->business_name??$user->name??'';
               $userArr["Parent Details"] = $parent->business_name??'' . " - " .$parent->unique_id;
               $userArr["Name"] = $user->name??'';
               $userArr["Email"] = $user->email??'';
               $userArr["Phone"] = $user->phone??'';
               $userArr["Alternate Phone"] = $user->alternate_phone??'';
               $userArr["GST"] = $user->gst??'';
               $userArr["Address"] = $user->address??'';
               $userArr["Pincode"] = $user->pincode??'';
               $userArr['State'] = CustomHelper::getStateName($user->state_id??'');
               $userArr['City'] = CustomHelper::getStateName($user->city_id??'');
               $userArr['Registerd At'] = $user->created_at??'';
               $exportArr[] = $userArr;

           }
       });
        if(!empty($exportArr) && count($exportArr) > 0){
         $filedNames = array_keys($exportArr[0]);
         $fileName = 'role_informaion_'.date('Y-m-d-H-i-s').'.xlsx';
         Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');
         $report->file = $fileName;
         $report->is_complete = 1;
         $report->save();
     }else{
         $report->remarks = 'No Data FOund';
         $report->save();
     }

 }else{
    $report->remarks = 'Choose Role';
    $report->save();
}
}
public function role_closing_coupon($report){
    $role = $report->role??'';
    $exportArr = [];
    if(!empty($role)){
        $query = Admin::where('role_id',$role);
        $query->chunk(50, function($users) use (&$exportArr,&$role) {
            foreach ($users as $user) {
               $roles = Roles::where('id',$role)->first();
               $userArr = [];
               $userArr[$roles->name." Details"] = $user->business_name??$user->name??'' . $user->unique_id??'';
               $userArr['State'] = CustomHelper::getStateName($user->state_id??'');
               $userArr['City'] = CustomHelper::getStateName($user->city_id??'');

               // $parent_total_couponsall = AssignCoupon::select('coupon_id')->where('child_id',$user->id)->where('is_return',0)->groupBy('coupon_id')->pluck('coupon_id')->count();
               // $distribute_couponsall = AssignCoupon::select('coupon_id')->where('parent_id',$user->id)->where('is_return',0)->pluck('coupon_id')->count();

               // $userArr['Closing Coupons'] = $parent_total_couponsall - $distribute_couponsall;
               $userArr['Closing Coupons'] = $user->no_of_coupons??'';
               $exportArr[] = $userArr;

           }
       });
        if(!empty($exportArr) && count($exportArr) > 0){
         $filedNames = array_keys($exportArr[0]);
         $fileName = 'role_closingcoupon_'.date('Y-m-d-H-i-s').'.xlsx';
         Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');
         $report->file = $fileName;
         $report->is_complete = 1;
         $report->save();
     }else{
         $report->remarks = 'No Data FOund';
         $report->save();
     }

 }else{
    $report->remarks = 'Choose Role';
    $report->save();
}
}







public function role_coupon_allocation($report)
{
    $start_date = $report->start_date??'';
    $end_date = $report->end_date??'';
    $role = $report->role??'';
    $user_id = $report->user_id??'';
    $exportArr = [];
    \DB::enableQueryLog();
    $query = Coupon::latest('id');
    if(!empty($start_date) && !empty($end_date)){
            if($role == 2){
                $query->where('sdist_date','>=',$start_date)->where('sdist_date','<=',$end_date);
            }
            if($role == 3){
                $query->where('dist_date','>=',$start_date)->where('dist_date','<=',$end_date);
            }
            if($role == 4){
                $query->where('tsm_date','>=',$start_date)->where('tsm_date','<=',$end_date);
            }
            if($role == 5){
                $query->where('seller_date','>=',$start_date)->where('seller_date','<=',$end_date);
            }
    }
    if(!empty($user_id)){
        if($role == 2){
                $query->where('sdist_id',$user_id);
            }
            if($role == 3){
                $query->where('dist_id',$user_id);
            }
            if($role == 4){
               $query->where('tsm_id',$user_id);
            }
            if($role == 5){
                $query->where('seller_id',$user_id);
            }
    }   

    $couponsArr = [];

    $query->chunk(500, function($coupons) use (&$couponsArr) {
        foreach ($coupons as $coup) {


// dd(\DB::getQueryLog());
                $userArr = [];
            $userArr['couponID'] = $coup->couponID;
            $sdist = CustomHelper::getUserDetails($coup->sdist_id);
            $userArr['SuperDistributor Details'] = $sdist->business_name??'' ;
            $userArr['SuperDistributor Allocation Date'] = $coup->sdist_date??'';
            $userArr['SuperDistributor State'] = CustomHelper::getStateName($sdist->state_id??'');
            $dist = CustomHelper::getUserDetails($coup->dist_id);
            $userArr['Distributor Details'] = $dist->business_name??'';
            $userArr['Distributor Allocation Date'] = $coup->dist_date??'';
            $userArr['Distributor State'] = CustomHelper::getStateName($dist->state_id??'');
            $tsm = CustomHelper::getUserDetails($coup->tsm_id);
            $userArr['TSM Details'] = $tsm->name??'';
            $userArr['TSM Allocation Date'] = $coup->tsm_date??'';
            $userArr['TSM State'] = CustomHelper::getStateName($tsm->state_id??'');
            $seller = CustomHelper::getUserDetails($coup->seller_id);
            $userArr['Seller Details'] = $seller->business_name??'';
            $userArr['Seller Allocation Date'] = $coup->seller_date??'';
            $userArr['Seller State'] = CustomHelper::getStateName($seller->state_id??'');
            $mobile_details = MobileDetails::where('coupon_id',$coup->couponID)->first();
            $userArr['Uses/UnUsed'] = $coup->is_used ? "Used" : "UnUsed";
            $userArr['User Name'] = $mobile_details->user_name??'';
            $userArr['User Phone'] =$mobile_details->user_phone??'';
            $userArr['User Alt Phone'] =$mobile_details->user_phone??'';
            $userArr['Mobile Name'] =$mobile_details->mobile_name??'';
            $userArr['Imei No'] =$mobile_details->imei_no??'';
            $userArr['Date Of Purchase'] =$mobile_details->date_of_purchase??'';
            $userArr['Phone Status'] =$mobile_details->phone_status??'';
            $userArr['Finance Name'] =$mobile_details->loan_provider??'';
            
            $couponsArr[] = $userArr;
        }
    });


    if(!empty($couponsArr)){
        $filedNames = array_keys($couponsArr[0]);
        $fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';
        Excel::store(new ReportExport($couponsArr, $filedNames), $fileName, 'excel_uploads');
        $report->file = $fileName;
        $report->is_complete = 1;
        $report->save();
    }else{
         $report->is_complete = 1;
         $report->remarks = 'No data Found';
         $report->save();
    }

}





public function role_coupon_allocation1($report)
{

    $start_date = $report->start_date??'';
    $end_date = $report->end_date??'';
    $role = $report->role??'';
    $user_id = $report->user_id??'';
    $exportArr = [];
    \DB::enableQueryLog();


    // $query = AssignCoupon::select('coupon_id','date')->latest();
    $query = AssignCoupon::select('coupon_id','date','parent_id','child_id')->latest();

    
    if(!empty($user_id)){
        $query->where('parent_id',$user_id);
    }else{

        $query->where('parent_role_id',$role);
    }
    if(!empty($start_date) && !empty($end_date)){

        $query->whereDate('date','>=',date('Y-m-d',strtotime($start_date)))->whereDate('date','<=',date('Y-m-d',strtotime($end_date)));
    }

    // $data = $query->limit(2000)->get()->toArray();
    $data = $query->get()->toArray();
    // $data = $query->get()->toArray();

    foreach($data as $dt)
    {

      $userArr = [];
      $coupon_id = $dt['coupon_id'];
      $coupons_data = Coupon::where('id',$coupon_id)->first();
      $userArr['couponID'] = $coupons_data->couponID??'';
      $tsm_id = $dt['parent_id'];
      $child_id = $dt['child_id'];

      if($role == 4)
      {
        $tsm_details = DB::table('admins')->select('business_name','unique_id')->where(['id'=>$tsm_id , 'role_id'=>4])->get()->toArray();

        foreach($tsm_details as $tsm_dt)
        {
         $tsm_business_name = $tsm_dt->business_name;
         $tsm_unique_id = $tsm_dt->unique_id;
         $userArr['Business Name'] =  $tsm_business_name;
         $userArr['Unique ID'] =  $tsm_unique_id;
     } 
 }

      // echo 'coupon id = '.$coupons_data->couponID;
 $mobile_details = MobileDetails::select('user_name','user_phone','user_phone','mobile_name','imei_no','date_of_purchase','phone_status','loan_provider')->where('coupon_id',$coupons_data->couponID)->first();

 $userArr['Uses/UnUsed'] = $coupons_data->is_used? "Used" : "UnUsed";
 $userArr['User Name'] = $mobile_details->user_name??'';
 $userArr['User Phone'] =$mobile_details->user_phone??'';
 $userArr['User Alt Phone'] =$mobile_details->user_phone??'';
 $userArr['Mobile Name'] =$mobile_details->mobile_name??'';
 $userArr['Imei No'] =$mobile_details->imei_no??'';
 $userArr['Date Of Purchase'] =$mobile_details->date_of_purchase??'';
 $userArr['Phone Status'] =$mobile_details->phone_status??'';
 $userArr['Finance Name'] =$mobile_details->loan_provider??'';

        // dd(\DB::getQueryLog());
 $exportArr[] = $userArr;


}



if(!empty($exportArr) && count($exportArr) > 0){
        // echo 'test  export';
 $filedNames = array_keys($exportArr[0]);

 $fileName = $user_id.'role_coupon_allocation_'.date('Y-m-d-H-i-s').'.xlsx';
 Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');
 $report->file = $fileName;
 $report->is_complete = 1;
           // print_r($filedNames);

 $report->save();
}else{

 $report->is_complete = 1;

 $report->remarks = 'No Data Found';
 $report->save();
}




    // $query->limit(100)->chunk(20, function($coupons) use (&$exportArr,&$role,&$user_id) {

    //     foreach ($coupons as $coup) {
    //         $userArr = [];
    //         $coupons_data = Coupon::where('id',$coup->coupon_id)->first();
    //         $userArr['couponID'] = $coupons_data->couponID??'';
    //         $tsm_id = $coup->parent_id;



    //         if($role == 2){
    //             ////////////////Super DIstributor  2//////
    //         //////////////// DIstributor   3//////
    //         ////////////////TSM    4//////
    //         ////////////////Seller   5//////

    //         }
    //         if($role == 3){
    //         //////////////// DIstributor   3//////
    //         ////////////////TSM    4//////
    //         ////////////////Seller   5//////

    //         }
    //         if($role == 4){
    //              ////////////////TSM    4//////
    //         ////////////////Seller   5//////

    //             $tsm_details = DB::table('admins')->select('business_name','unique_id')->where(['id'=>$tsm_id , 'role_id'=>4])->get()->toArray();

    //             foreach($tsm_details as $tsm_dt)
    //             {
    //                $tsm_business_name = $tsm_dt->business_name;
    //                $tsm_unique_id = $tsm_dt->unique_id;
    //                $userArr['Business Name'] =  $tsm_business_name;
    //                $userArr['Unique ID'] =  $tsm_unique_id;
    //             } 


    //             // $userArr['TSM Unique ID'] = $tsm_details->unique_id ?? '';
    //             // $userArr['TSM Business Name'] = $tsm_details->business_name ?? '';


    //         }
    //         if($role == 5){
    //         ////////////////Seller   5//////

    //         }



    //         $mobile_details = MobileDetails::select('user_name','user_phone','user_phone','mobile_name','imei_no','date_of_purchase','phone_status','loan_provider')->where('coupon_id',$coupons_data->couponID)->first();

    //         $userArr['Uses/UnUsed'] = $coupons_data->is_used? "Used" : "UnUsed";
    //         $userArr['User Name'] = $mobile_details->user_name??'';
    //         $userArr['User Phone'] =$mobile_details->user_phone??'';
    //         $userArr['User Alt Phone'] =$mobile_details->user_phone??'';
    //         $userArr['Mobile Name'] =$mobile_details->mobile_name??'';
    //         $userArr['Imei No'] =$mobile_details->imei_no??'';
    //         $userArr['Date Of Purchase'] =$mobile_details->date_of_purchase??'';
    //         $userArr['Phone Status'] =$mobile_details->phone_status??'';
    //         $userArr['Finance Name'] =$mobile_details->loan_provider??'';

    //         // dd(\DB::getQueryLog());
    //        $exportArr[] = $userArr;
    //     }
    // });

    // print_r($exportArr);
    // die;
    // echo 'test count = '.$exportArr->count();


    //  if(!empty($exportArr) && count($exportArr) > 0){
    //     // echo 'test  export';
    //        $filedNames = array_keys($exportArr[0]);

    //        $fileName = $user_id.'role_coupon_allocation_'.date('Y-m-d-H-i-s').'.xlsx';
    //        Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');
    //        $report->file = $fileName;
    //        $report->is_complete = 1;
    //        // print_r($filedNames);

    //        $report->save();
    // }else{

    //        $report->is_complete = 1;
    //        $report->remarks = 'No Data Found';
    //        $report->save();
    // }




}







public function role_coupon_allocation_old($report){
    // prd($report);
    $start_date = $report->start_date??'';
    $end_date = $report->end_date??'';
    $role = $report->role??'';
    $user_id = $report->user_id??'';
    $exportArr = [];
    // if(!empty($user_id)){
    if(!empty($start_date) && !empty($end_date)){
        $query = AssignCoupon::where('parent_id',$user_id)->whereDate('date','>=',date('Y-m-d',strtotime($start_date)))->whereDate('date','<=',date('Y-m-d',strtotime($end_date)));
    }else{
        if($report->added_by == 1){
            $query = AssignCoupon::where('parent_role_id',$role)->latest();
        }else{
            $query = AssignCoupon::where('parent_id',$user_id);
        }
    }
    $query->chunk(50, function($coupons) use (&$exportArr,&$role,&$user_id) {
        foreach ($coupons as $coup) {
           $userArr = [];
           $assigns = AssignCoupon::where('coupon_id',$coup->coupon_id)->get();
           $coupons = Coupon::where('id',$coup->coupon_id)->first();
           $userArr['couponID'] = $coupons->couponID??'';
           $role_single = Roles::where('id',$role)->first();
           $parent_id = $role_single->parent_id??'';
           $roles = Roles::where('parent_id','>=',$parent_id)->get();
           $adminIds = [];
           $assignDate = [];
           if(!empty($assigns)){
            foreach($assigns as $ass){
                $adminIds[] = $ass->parent_id;
                $adminIds[] = $ass->child_id;
                $assignDate[] = $ass->date;
            }
            $adminIds = array_unique($adminIds);
        }          
        if(!empty($roles)){
            $i=0;
            foreach($roles as $role_new){
                $userArr[$role_new->name.' Details'] = '';
                $userArr[$role_new->name.' Allocation Date'] = '';
                $userArr[$role_new->name.' State'] = '';
                $user_details = [];
                foreach ($adminIds as $key => $value) {
                    $user_details = CustomHelper::getUserDetails($value);
                    if($user_details->role_id == $role_new->id){

                        $business_name = $user_details->business_name??'';
                        if(empty($business_name)){
                            $business_name = $user_details->name??'';
                        }
                        $userArr[$role_new->name.' Details'] = $business_name??'' . $user_details->unique_id??'';
                            // $userArr[$role_new->name.' Allocation Date'] = $assignDate[$i]??'';
                        $userArr[$role_new->name.' Allocation Date'] = $coup->date??'';
                        $userArr[$role_new->name.' State'] = CustomHelper::getStateName($user_details->state_id??'');
                    }
                }
                $i++;
            }
        }

        $mobile_details = MobileDetails::where('coupon_id',$coupons->couponID)->first();

        $userArr['Uses/UnUsed'] = $coupons->is_used? "Used" : "UnUsed";
        $userArr['User Name'] = $mobile_details->user_name??'';
        $userArr['User Phone'] =$mobile_details->user_phone??'';
        $userArr['User Alt Phone'] =$mobile_details->user_phone??'';
        $userArr['Mobile Name'] =$mobile_details->mobile_name??'';
        $userArr['Imei No'] =$mobile_details->imei_no??'';
        $userArr['Date Of Purchase'] =$mobile_details->date_of_purchase??'';
        $userArr['Phone Status'] =$mobile_details->phone_status??'';
        $userArr['Finance Name'] =$mobile_details->loan_provider??'';
        $exportArr[] = $userArr;

    }
});
    if(!empty($exportArr) && count($exportArr) > 0){
     $filedNames = array_keys($exportArr[0]);
     $fileName = $user_id.'role_coupon_allocation_'.date('Y-m-d-H-i-s').'.xlsx';

     Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');

     $report->file = $fileName;
     $report->is_complete = 1;
     $report->save();
 }else{
     $report->is_complete = 1;

     $report->remarks = 'No Data Found';
     $report->save();
 }

//    }else{
//     $report->remarks = 'Choose User';
//     $report->save();
// }
}



public function all_coupon_allocation(Request $report){

    $start_date = $report->start_date??'';
    $end_date = $report->end_date??'';

    if(!empty($start_date) && !empty($end_date)){
        $coupons = DB::select("SELECT * FROM `coupons` WHERE `sdist_date` >= '$start_date' AND `sdist_date` <= '$end_date'");
    }else{
        // $coupons = DB::select("SELECT * FROM `assign_coupons`");
    }

    $couponsArr = [];
    if(!empty($coupons)){
        foreach($coupons as $coup){
            $userArr = [];
            $userArr['couponID'] = $coup->couponID;
            $sdist = CustomHelper::getUserDetails($coup->sdist_id);
            $userArr['SuperDistributor Details'] = $sdist->business_name??'' ;
            $userArr['SuperDistributor Allocation Date'] = $coup->sdist_date??'';
            $userArr['SuperDistributor State'] = CustomHelper::getStateName($sdist->state_id??'');
            $dist = CustomHelper::getUserDetails($coup->dist_id);
            $userArr['Distributor Details'] = $dist->business_name??'';
            $userArr['Distributor Allocation Date'] = $coup->dist_date??'';
            $userArr['Distributor State'] = CustomHelper::getStateName($dist->state_id??'');
            $tsm = CustomHelper::getUserDetails($coup->tsm_id);
            $userArr['TSM Details'] = $tsm->name??'';
            $userArr['TSM Allocation Date'] = $coup->tsm_date??'';
            $userArr['TSM State'] = CustomHelper::getStateName($tsm->state_id??'');
            $seller = CustomHelper::getUserDetails($coup->seller_id);
            $userArr['Seller Details'] = $seller->business_name??'';
            $userArr['Seller Allocation Date'] = $coup->seller_date??'';
            $userArr['Seller State'] = CustomHelper::getStateName($seller->state_id??'');
            $mobile_details = MobileDetails::where('coupon_id',$coup->couponID)->first();
            $userArr['Uses/UnUsed'] = $coup->is_used ? "Used" : "UnUsed";
            $userArr['User Name'] = $mobile_details->user_name??'';
            $userArr['User Phone'] =$mobile_details->user_phone??'';
            $userArr['User Alt Phone'] =$mobile_details->user_phone??'';
            $userArr['Mobile Name'] =$mobile_details->mobile_name??'';
            $userArr['Imei No'] =$mobile_details->imei_no??'';
            $userArr['Date Of Purchase'] =$mobile_details->date_of_purchase??'';
            $userArr['Phone Status'] =$mobile_details->phone_status??'';
            $userArr['Finance Name'] =$mobile_details->loan_provider??'';
            
            $couponsArr[] = $userArr;

        }
    }
    echo "<pre>";
    print_r($couponsArr);
    die("==");

    if(!empty($couponsArr)){
        $filedNames = array_keys($couponsArr[0]);
        $fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';
        Excel::store(new ReportExport($couponsArr, $filedNames), $fileName, 'excel_uploads');

        $report->file = $fileName;
        $report->is_complete = 1;
        $report->save();
    }else{
     $report->is_complete = 1;
     $report->remarks = 'No data Found';
     $report->save();
 }
    // die;

}

public function all_coupon_allocation11($report){
    $start_date = $report->start_date??'';
    $end_date = $report->end_date??'';
    $exportArr = [];
    if(!empty($start_date) && !empty($end_date)){
        Coupon::where('date','>=',date('Y-m-d',strtotime($start_date)))->where('date','<=',date('Y-m-d',strtotime($end_date)))
        ->chunk(50, function($coupons) use (&$exportArr) {
            foreach ($coupons as $coup) {
               $userArr = [];
               $assigns = AssignCoupon::where('coupon_id',$coup->id)->get();
               $userArr['couponID'] = $coup->couponID;
               $roles = Roles::where('parent_id','!=',0)->get();
               $adminIds = [];
               $assignDate = [];
               if(!empty($assigns)){
                foreach($assigns as $ass){
                    $adminIds[] = $ass->parent_id;
                    $adminIds[] = $ass->child_id;
                    $assignDate[] = $ass->date;
                }

                $adminIds = array_unique($adminIds);
            }
            if(!empty($roles)){
                $i=0;
                foreach($roles as $role){
                    $userArr[$role->name.' Details'] = '';
                    $userArr[$role->name.' Allocation Date'] = '';
                    $userArr[$role->name.' State'] = '';
                    $user_details = [];
                    foreach ($adminIds as $key => $value) {
                        $user_details = CustomHelper::getUserDetails($value);
                        if($user_details->role_id == $role->id){
                            $userArr[$role->name.' Details'] = $user_details->business_name??$user_details->name??'' . $user_details->unique_id??'';
                            $userArr[$role->name.' Allocation Date'] = $assignDate[$i]??'';
                            $userArr[$role->name.' State'] = CustomHelper::getStateName($user_details->state_id??'');
                        }
                    }
                    $i++;
                }
            }

            $mobile_details = MobileDetails::where('coupon_id',$coup->couponID)->first();

            $userArr['Uses/UnUsed'] = $coup->is_used? "Used" : "UnUsed";
            $userArr['User Name'] = $mobile_details->user_name??'';
            $userArr['User Phone'] =$mobile_details->user_phone??'';
            $userArr['User Alt Phone'] =$mobile_details->user_phone??'';
            $userArr['Mobile Name'] =$mobile_details->mobile_name??'';
            $userArr['Imei No'] =$mobile_details->imei_no??'';
            $userArr['Date Of Purchase'] =$mobile_details->date_of_purchase??'';
            $userArr['Phone Status'] =$mobile_details->phone_status??'';
            $userArr['Finance Name'] =$mobile_details->loan_provider??'';
            
            $exportArr[] = $userArr;

        }
    });
        if(!empty($exportArr)){
            $filedNames = array_keys($exportArr[0]);
            $fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';

            Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');

            $report->file = $fileName;
            $report->is_complete = 1;
            $report->save();
        }else{
         $report->is_complete = 1;

         $report->remarks = 'No data Found';
         $report->save();
     }

 }else{
    $report->remarks = 'Please Choose Start Date And End Date';
    $report->save();
}
}




public function all_coupon_allocation_test(){
   $coupan_all = Coupon::where('is_active','Y')->orderby('id')->get();
   foreach ($coupan_all as $key_c) {
       $coupan_data = AssignCoupon::where('coupon_id',$key_c->id)->orderby('created_at','asc')->get();
       $c_id = array();
       $coupon_id = array();
       foreach ($coupan_data as $key) {
        array_push($c_id,$key->parent_id);
        array_push($c_id,$key->child_id);
        array_push($coupon_id,$key->coupon_id); 
        
    }

    if (!empty($c_id)) {
        $admin_ids = array_unique($c_id);
        $admin_ids = implode(",", $admin_ids);
        DB::table('test_table_coupan')->insert(array('coupan_id'=>$coupon_id[0],'admin_ids'=>$admin_ids));
    }
}
}

public function all_coupon_allocation_test11(){
   $test_table_coupan = DB::table('test_table_coupan')->orderby('id')->get();
   foreach ($test_table_coupan as $key_c) {
    $admin_ids = explode(",", $key_c);

    if(!empty($admin_ids)){
        foreach($admin_ids as $ids){
            $data[] = [
                'coupan_id'=>$ids->coupan_id,
                'coupan_id'=>$ids->coupan_id,
                'coupan_id'=>$ids->coupan_id,
                'coupan_id'=>$ids->coupan_id,
            ];
        }
    }

    
}
}


public function user_export($report){

    $data = $report->data??'';
    $data = json_decode($data);
    $search = '';
    $child_ids = [];
    // $child_id = json_decode($data->child_ids);
    if(!empty($data)){
        $child_ids = $data[0]->child_ids??'';
        $search = $data[0]->search??'';
        $child_ids = json_decode($child_ids);
        
    }



    $exportArr = [];
    $mobile_details = MobileDetails::latest('id');
    if(!empty($search)){
     $mobile_details->where('user_name', 'like', '%' . $search . '%');
     $mobile_details->orWhere('user_phone', 'like', '%' . $search . '%');
 }
 if(!empty($child_ids)){
    $mobile_details->whereIn('coupon_parent_id',$child_ids);
}
$mobile_details->chunk(50, function($mobile_details) use (&$exportArr){
    foreach ($mobile_details as $mobile_detail) {
        $userArr = [];
        $userArr['CouponID'] = $mobile_detail->coupon_id??'';
        $userArr['Coupon Code'] = $mobile_detail->coupon_code??'';
        $userArr['Uses/UnUsed'] = "Used";
        $userArr['User Name'] = $mobile_detail->user_name??'';
        $userArr['User Phone'] =$mobile_detail->user_phone??'';
        $userArr['User Alt Phone'] =$mobile_detail->user_phone??'';
        $userArr['Mobile Name'] =$mobile_detail->mobile_name??'';
        $userArr['Imei No'] =$mobile_detail->imei_no??'';
        $userArr['Date Of Purchase'] =date('Y-m-d',strtotime($mobile_detail->date_of_purchase))??'';
        $userArr['Phone Status'] =$mobile_detail->phone_status??'';
        $userArr['Finance Name'] =$mobile_detail->loan_provider??'';
        $exportArr[] = $userArr;


    }
});
    // prd($exportArr);
$filedNames = array_keys($exportArr[0]);
$fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';

if(!empty($exportArr)){
   Excel::store(new ReportExport($exportArr, $filedNames), $fileName, 'excel_uploads');
    $report->file = $fileName;
    $report->is_complete = 1;
    $report->save();
}

}


public function add(Request $request){
   $data = [];

   $method = $request->method();
   if($method == 'post' || $method == 'POST'){
       $user_id =Auth::guard('admin')->user()->id??'';
       $dbArray = [];
       $dbArray['type'] = $request->type??null;
       $dbArray['start_date'] = $request->start_date??null;
       $dbArray['end_date'] = $request->end_date??null;
       $dbArray['role'] = $request->role??null;
       $dbArray['user_id'] = $request->user_id??null;
       $dbArray['added_by'] = $user_id;
       Reports::insert($dbArray);


       if($request->type == 'role_coupon_allocation'){
        // $this->get_report_role_coupon_allocation($request);
    }


    return back()->with('alert-success', 'Report will be generate Shortly..');


}
$data['page_Heading'] = 'Generate Report';
return view('admin.reports.form',$data);

}





public function get_report_role_coupon_allocation($request){

    $start_date = $request->start_date??'';
    $end_date = $request->end_date??'';
    $role = $request->role??'';
    $user_id = $request->user_id??'';
    $exportArr = [];

    $filename = "TSM-".date('d-m-y h:i:s');
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=\"$filename".".csv\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    $couponsArr = [];
    \DB::enableQueryLog();
    $query = AssignCoupon::select('coupon_id','date','time','parent_id','child_id')->latest();
    if(!empty($start_date) && !empty($end_date)){
        $query->whereDate('date','>=',date('Y-m-d',strtotime($start_date)))->whereDate('date','<=',date('Y-m-d',strtotime($end_date)));
    }
    if(!empty($user_id)){
        $query->where('parent_id',$user_id);
    }
    if($request->added_by == 1){
        $query->where('parent_role_id',$role);
    }

    $coupons = $query->get();

     // prd($request->toArray());
// dd(\DB::getQueryLog());

    if(!empty($coupons)){
        foreach($coupons as $coup){
            $coupons_data = Coupon::select('couponID')->where('id',$coup->coupon_id)->first();
            $tsm_business_name = '';
            $tsm_referral_code = '';
            $seller_business_name ='';
            $seller_referral_code = '';
            if($role == 4){
                $tsm = Admin::select('name','unique_id')->where('id',$coupons_data->parent_id)->first();
                $tsm_business_name = $tsm->name??'';
                $tsm_referral_code =  $tsm->unique_id??'';

                $seller = Admin::select('business_name','unique_id')->where('id',$coupons_data->child_id)->first();
                $seller_business_name = $seller->business_name??'';
                $seller_referral_code = $seller->unique_id??'';

            }

            $mobile_details = MobileDetails::select('user_name','user_phone','user_phone','mobile_name','imei_no','date_of_purchase','phone_status','loan_provider')->where('coupon_id',$coupons_data->couponID)->first();

            $couponsArr[]=[
                'couponID'=>$coupons_data->couponID,
                'TSM Details'=>$tsm_business_name."    ".$tsm_referral_code."  ",
                'Seller Details'=>$seller_business_name."    ".$seller_referral_code."  ",
                'TSM Allocation Date'=>$coupons_data->date,
                'Uses/UnUsed'=>$coupons_data->is_used? "Used" : "UnUsed",
                'User Name'=>$mobile_details->user_name??'',
                'User Phone'=>$mobile_details->user_phone??'',
                'User Alt Phone'=>$mobile_details->user_phone??'',
                'Mobile Name'=>$mobile_details->mobile_name??'',
                'Imei No'=>$mobile_details->imei_no??'',
                'Date Of Purchase'=>$mobile_details->date_of_purchase??'',
                'Phone Status'=>$mobile_details->phone_status??'',

            ];
        }
    }

    $handle = fopen('php://output', 'w');
    fputcsv($handle, array_keys($couponsArr[0]));
    foreach ($couponsArr as $un) {
        fputcsv($handle, $un);
    }

    fclose($handle);


}
}




