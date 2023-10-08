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

use App\Imports\CouponImport;







Class CouponController extends Controller

{





	private $ADMIN_ROUTE_NAME;



    public function __construct(){



        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();





    }







    public function index(Request $request){

        $method = $request->method();

        if($method == 'post' || $method == 'POST'){

            $rules = [];

            $rules['importfile'] = 'required';

            $message = ['importfile.required' => 'You have to choose the file!',];

            $this->validate($request,$rules,$message);

            $sucess = Excel::import(new CouponImport,request()->file('importfile'));



        }

        $data = [];

        $search = $request->search??'';

        $coupons = Coupon::latest();

        if(!empty($search)){

            $coupons->where('couponID',$search);

            $coupons->orWhere('coupon_code',$search);

        }



        $coupons = $coupons->orderBy('couponID','desc')->paginate(10);



        $data['coupons'] = $coupons;

        return view('admin.coupons.index',$data);

    }











    public function add(Request $request){

        $data = [];



        $id = (isset($request->id))?$request->id:0;



        $coupons = '';

        if(is_numeric($id) && $id > 0){

            $coupons = Coupon::find($id);

            if(empty($coupons)){

                return redirect($this->ADMIN_ROUTE_NAME.'/coupons');

            }

        }



        if($request->method() == 'POST' || $request->method() == 'post'){



            if(empty($back_url)){

                $back_url = $this->ADMIN_ROUTE_NAME.'/coupons';

            }



            $name = (isset($request->name))?$request->name:'';





            $rules = [];

            $rules['role_id'] = 'required';

            $rules['parent_id'] = 'required';

            $rules['no_of_coupons'] = 'required';

            $this->validate($request, $rules);



            $createdCat = $this->save($request, $id);



            if (!empty($createdCat)) {



                $start_no = $createdCat['start_no']??'';

                $end_no = $createdCat['end_no']??'';



                $alert_msg = 'Coupons Assign successfully From '.$start_no.' to '.$end_no.' ';



                // $alert_msg = 'Coupons has been added successfully.';

                if(is_numeric($id) && $id > 0){

                    // $alert_msg = 'Coupons has been updated successfully.';

                    $alert_msg = 'Coupons Assign successfully From '.$start_no.' to '.$end_no.' ';

                }

                return redirect(url($back_url))->with('alert-success', $alert_msg);

            } else {

                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');

            }

        }





        $page_heading = 'Add Coupons';



        if(isset($exams->couponID)){



            $page_heading = 'Update Coupons';

        }  



        $data['page_heading'] = $page_heading;

        $data['id'] = $id;

        $data['coupons'] = $coupons;



        $last_coupon = Coupon::orderby('id','desc')->first();

        $data['last_coupon'] = $last_coupon;





        return view('admin.coupons.form', $data);



    }







    public function generate_random_password($length = 10) {



        $numbers = range('0','9');

        $alphabets = range('A','Z');

        $final_array = array_merge($alphabets,$numbers);

        $password = '';      

        while($length--) {

          $key = array_rand($final_array);

          $password .= $final_array[$key];

      }



      return $password;



  }





  public function save(Request $request, $id=0){



    $start = [];

    $start_no = '';

    $end_no = '';

    $no_of_coupons = $request->no_of_coupons;

    for($i = 0;$i<$no_of_coupons;$i++){

     $last_coupon_id = 'AP1';

     $last_coupon = Coupon::orderby('id','desc')->first();

     if(!empty($last_coupon)){
        $cou = (int)$last_coupon->id+1;
        $last_coupon_id = 'MATRIX'.$cou;

    }

    $start[] = $last_coupon_id;

    $dbArray = [];

    $dbArray['parent_id'] = $request->parent_id;

    $dbArray['coupon_code'] = strtoupper(substr('APPLOCK', 0,2)).$this->generate_random_password(6);

    $dbArray['couponID'] = $last_coupon_id;

    $dbArray['invoice_no'] = $request->invoice_no;
    $dbArray['sdist_id'] = $request->parent_id;
    $dbArray['sdist_date'] = date('Y-m-d');

    $dbArray['date'] = date('Y-m-d');

    $dbArray['time'] = date('H:i');

    $coupon_id = Coupon::insertGetId($dbArray);

    $child = CustomHelper::getUserDetails($request->parent_id);

    $dbArray1 = [];

    $dbArray1['coupon_id'] = $coupon_id;

    $dbArray1['parent_id'] = 1;

    $dbArray1['child_id'] = $request->parent_id;

    $dbArray1['parent_role_id'] = 1;

    $dbArray1['child_role_id'] = $child->role_id;

    $dbArray1['date'] = date('Y-m-d');

    $dbArray1['time'] = date('H:i');

    AssignCoupon::insert($dbArray1);

}

$admins = Admin::where('id',$request->parent_id)->first();

Admin::where('id',$request->parent_id)->update(['no_of_coupons'=>$admins->no_of_coupons+$no_of_coupons]);



$couponArr = [];

$couponArr['parent_id'] = $request->parent_id;

$couponArr['no_of_coupons'] = $no_of_coupons;

$couponArr['date'] = date('Y-m-d');

$couponArr['time'] = date('H:i');



CouponTransaction::insert($couponArr);

$dbArray = [];

$dbArray['start_no'] = $start[0];

$dbArray['end_no'] = $start[$no_of_coupons-1];







return $dbArray;

// prd($start_no);

// return back()->with('alert-success','Coupons Assign successfully From '.$start_no.' to '.$end_no.' ');



// return true;

}


public function assign_coupons(Request $request){

    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['parent_id'] = 'required';
        $rules['child_id'] = 'required';
        $rules['coupon_qty'] = 'required';
        $this->validate($request,$rules);
        $parent_coupons = CustomHelper::getParentCoupons($request->parent_id);
        $parent = CustomHelper::getUserDetails($request->parent_id);
        $child = CustomHelper::getUserDetails($request->child_id);

        if(count($parent_coupons) >= $request->coupon_qty){
            for ($i=0; $i < $request->coupon_qty; $i++) {
                $dbArrayass = [];
                if($child->role_id == 2){
                    $dbArrayass['sdist_id'] = $child->id;
                    $dbArrayass['sdist_date'] = date('Y-m-d');
                }
                if($child->role_id == 3){
                    $dbArrayass['dist_id'] = $child->id;
                    $dbArrayass['dist_date'] = date('Y-m-d');
                }
                if($child->role_id == 4){
                    $dbArrayass['tsm_id'] = $child->id;
                    $dbArrayass['tsm_date'] = date('Y-m-d');
                }
                if($child->role_id == 5){
                    $dbArrayass['seller_id'] = $child->id;
                    $dbArrayass['seller_date'] = date('Y-m-d');
                }
                Coupon::where('id',$parent_coupons[$i]->id)->update($dbArrayass);
                $dbArray = [];
                $dbArray['coupon_id'] = $parent_coupons[$i]->id;
                $dbArray['parent_id'] = $request->parent_id;
                $dbArray['child_id'] = $request->child_id;
                $dbArray['parent_role_id'] = $parent->role_id;
                $dbArray['child_role_id'] = $child->role_id;
                $dbArray['date'] = date('Y-m-d');
                $dbArray['time'] = date('H:i');
                AssignCoupon::insert($dbArray); 

            }
            Admin::where('id',$parent->id)->update(['no_of_coupons'=>$parent->no_of_coupons-$request->coupon_qty]);
            Admin::where('id',$child->id)->update(['no_of_coupons'=>$child->no_of_coupons+$request->coupon_qty]);
            $couponArr = [];
            $couponArr['parent_id'] = $request->child_id;
            $couponArr['no_of_coupons'] = $request->coupon_qty;
            $couponArr['date'] = date('Y-m-d');
            $couponArr['time'] = date('H:i');
            CouponTransaction::insert($couponArr);
            return back()->with('alert-success', 'Coupons Assign successfully');
        }else{
         return back()->with('alert-danger', 'Coupon Qty Should Be less Than Available Qty'); 
     }

 }




    // $coupons = $request->coupons;

    // if(!empty($coupons)){
    //     $count = count($coupons);
    //     foreach ($coupons as $key => $value) {
    //         $parent = CustomHelper::getUserDetails($request->parent_id);
    //         $child = CustomHelper::getUserDetails($request->child_id);
    //         $dbArrayass = [];
    //         if($child->role_id == 2){
    //             $dbArrayass['sdist_id'] = $child->id;
    //             $dbArrayass['sdist_date'] = date('Y-m-d');
    //         }
    //         if($child->role_id == 3){
    //             $dbArrayass['dist_id'] = $child->id;
    //             $dbArrayass['dist_date'] = date('Y-m-d');
    //         }
    //         if($child->role_id == 4){
    //             $dbArrayass['tsm_id'] = $child->id;
    //             $dbArrayass['tsm_date'] = date('Y-m-d');
    //         }
    //         if($child->role_id == 5){
    //             $dbArrayass['seller_id'] = $child->id;
    //             $dbArrayass['seller_date'] = date('Y-m-d');
    //         }
    //         Coupon::where('id',$value)->update($dbArrayass);
    //         $dbArray = [];
    //         $dbArray['coupon_id'] = $value;
    //         $dbArray['parent_id'] = $request->parent_id;
    //         $dbArray['child_id'] = $request->child_id;
    //         $dbArray['parent_role_id'] = $parent->role_id;
    //         $dbArray['child_role_id'] = $child->role_id;
    //         $dbArray['date'] = date('Y-m-d');
    //         $dbArray['time'] = date('H:i');
    //         AssignCoupon::insert($dbArray);
    //     }
    //     $parent = Admin::where('id',$request->parent_id)->first();
    //     $child = Admin::where('id',$request->child_id)->first();
    //     Admin::where('id',$parent->id)->update(['no_of_coupons'=>$parent->no_of_coupons-$count]);
    //     Admin::where('id',$child->id)->update(['no_of_coupons'=>$child->no_of_coupons+$count]);
    //     $couponArr = [];
    //     $couponArr['parent_id'] = $request->child_id;
    //     $couponArr['no_of_coupons'] = $count;
    //     $couponArr['date'] = date('Y-m-d');
    //     $couponArr['time'] = date('H:i');
    //     CouponTransaction::insert($couponArr);
    // }



    // return back()->with('alert-success', 'Coupons Assign successfully');



}







public function assign_coupons_old(Request $request){

    $coupons = $request->coupons;

    if(!empty($coupons)){

        $count = count($coupons);

        foreach ($coupons as $key => $value) {

            $parent = CustomHelper::getUserDetails($request->parent_id);

            $child = CustomHelper::getUserDetails($request->child_id);

            $dbArrayass = [];

            if($child->role_id == 2){
                $dbArrayass['sdist_id'] = $child->id;
                $dbArrayass['sdist_date'] = date('Y-m-d');
            }
            if($child->role_id == 3){
                $dbArrayass['dist_id'] = $child->id;
                $dbArrayass['dist_date'] = date('Y-m-d');

            }
            if($child->role_id == 4){
                $dbArrayass['tsm_id'] = $child->id;
                $dbArrayass['tsm_date'] = date('Y-m-d');

            }
            if($child->role_id == 5){
                $dbArrayass['seller_id'] = $child->id;
                $dbArrayass['seller_date'] = date('Y-m-d');
            }
            Coupon::where('id',$value)->update($dbArrayass);

            $dbArray = [];

            $dbArray['coupon_id'] = $value;

            $dbArray['parent_id'] = $request->parent_id;

            $dbArray['child_id'] = $request->child_id;

            $dbArray['parent_role_id'] = $parent->role_id;

            $dbArray['child_role_id'] = $child->role_id;

            $dbArray['date'] = date('Y-m-d');

            $dbArray['time'] = date('H:i');

            AssignCoupon::insert($dbArray);



        }



        $parent = Admin::where('id',$request->parent_id)->first();

        $child = Admin::where('id',$request->child_id)->first();

        Admin::where('id',$parent->id)->update(['no_of_coupons'=>$parent->no_of_coupons-$count]);

        Admin::where('id',$child->id)->update(['no_of_coupons'=>$child->no_of_coupons+$count]);



        $couponArr = [];

        $couponArr['parent_id'] = $request->child_id;

        $couponArr['no_of_coupons'] = $count;

        $couponArr['date'] = date('Y-m-d');

        $couponArr['time'] = date('H:i');

        CouponTransaction::insert($couponArr);

    }



    return back()->with('alert-success', 'Coupons Assign successfully');



}





private function saveImage($request, $course, $oldImg=''){



    $file = $request->file('image');

    if ($file) {

        $path = 'course/';

        $thumb_path = 'course/thumb/';

        $storage = Storage::disk('public');

            //prd($storage);

        $IMG_WIDTH = 768;

        $IMG_HEIGHT = 768;

        $THUMB_WIDTH = 336;

        $THUMB_HEIGHT = 336;



        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);



           // prd($uploaded_data);

        if($uploaded_data['success']){



            if(!empty($oldImg)){

                if($storage->exists($path.$oldImg)){

                    $storage->delete($path.$oldImg);

                }

                if($storage->exists($thumb_path.$oldImg)){

                    $storage->delete($thumb_path.$oldImg);

                }

            }



            $image = $uploaded_data['file_name'];



            $course->image = $image;

            $course->save();         

        }



        if(!empty($uploaded_data)){   

            return $uploaded_data;

        }  



    }



}









public function delete(Request $request){



        //prd($request->toArray());



    $id = (isset($request->id))?$request->id:0;



    $is_delete = '';



    if(is_numeric($id) && $id > 0){

        $is_delete = Exam::where('id', $id)->update(['is_delete'=>1]);

    }



    if(!empty($is_delete)){

        return back()->with('alert-success', 'Exams has been deleted successfully.');

    }

    else{

        return back()->with('alert-danger', 'something went wrong, please try again...');

    }

}





public function get_party_user(Request $request){

    $role_id = $request->role_id??'';

    $html = '<option value="" selected disabled>Choose Party User</option>';

    if(!empty($role_id)){

        $users = Admin::where('role_id',$role_id)->get();

        if(!empty($users)){

            foreach($users as $user){

                $html.='<option value='.$user->id.'>'.$user->business_name . "--". $user->unique_id.'</option>';

            }

        }



    }

    echo $html;



}















}