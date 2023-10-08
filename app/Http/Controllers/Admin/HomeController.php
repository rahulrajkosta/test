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
use App\City;
use App\MobileDetails;
use App\SubCategory;
use App\SubscriptionHistory;

use App\Course;
use App\AssignCoupon;
use App\Coupon;
use App\Business;
use App\Permission;
use App\Roles;




use Storage;
use DB;
use Hash;
use FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;

use PhpOffice\PhpWord\IOFactory;




Class HomeController extends Controller
{

	public function index(Request $request){

            
   

        $data = [];
        $data['users'] = User::select('id')->count();
        $data['business'] = 0;
        $data['ratings'] = 0;
        $data['category'] = 0;
        $user_id = Auth::guard('admin')->user()->id;
        $parent_total_coupons = AssignCoupon::where('is_return',0)->where('child_id',$user_id)->pluck('coupon_id')->toArray();
        $distribute_coupons = AssignCoupon::where('is_return',0)->where('parent_id',$user_id)->pluck('coupon_id')->toArray();
        $total_coupons = array_merge($parent_total_coupons,$distribute_coupons);
        $couponArr = [];
        if(!empty($parent_total_coupons)){
            foreach ($parent_total_coupons as $key => $value){
              if(!in_array($value, $distribute_coupons))
                $couponArr[$key]=$value;
        }
    }

    $data['my_coupon_count'] = count($couponArr);

        /////////////////////////////////////
    $parent_id = Auth::guard('admin')->user()->parent_id??'';
    $date = date('Y-m-d');
    $role_id = Auth::guard('admin')->user()->role_id??'';
    $i = $role_id-1;
    $child_ids = CustomHelper::getParentIds($role_id,$user_id,$i);
    $users = MobileDetails::orderBy('id','desc');
    if($parent_id !=0){
        $users->whereIn('coupon_parent_id',$child_ids);
    }
    $total_users = $users->count();

    $today_users = MobileDetails::orderBy('id','desc');
    if($parent_id !=0){
        $today_users->whereIn('coupon_parent_id',$child_ids);
    }
    if(!empty($date)){
        $today_users->whereDate('date_of_purchase',$date);
    }
    $today_users = $today_users->count();


    $data['total_users'] = $total_users;
    $data['today_users'] = $today_users;

        // prd(Auth::guard('admin')->user());
    return view('admin.home.index',$data);
}





public function profile(Request $request)
{
        // echo $request->method();

  $data = [];
  $method = $request->method();
  $user = Auth::guard('admin')->user();

  if($method == 'post' || $method == 'POST')
  {

       // prd($request->toArray());


   $request->validate([
    'email' => 'required',        
    'phone' => 'required',
    'username' => 'required',
    'image' => '',
]);

   $name = isset($request->name) ? $request->name : '';
   $email = isset($request->email) ? $request->email : '';      
   $phone = isset($request->phone) ? $request->phone : '';
   $username = isset($request->username) ? $request->username : '';
   $education = isset($request->education) ? $request->education : '';
   $total_exp = isset($request->total_exp) ? $request->total_exp : '';
   $speciality = isset($request->speciality) ? $request->speciality : '';
   $about = isset($request->about) ? $request->about : '';
   $image = isset($request->image) ? $request->image : '';

   if(!empty($request->name)){
       $dbArray['name'] = $request->name; 
   }
   if(!empty($request->email)){
       $dbArray['email'] = $request->email; 
   }
   if(!empty($request->phone)){
       $dbArray['phone'] = $request->phone; 
   }
   if(!empty($request->username)){
       $dbArray['username'] = $request->username; 
   }
   if(!empty($request->education)){
       $dbArray['education'] = $request->education; 
   }
   if(!empty($request->total_exp)){
       $dbArray['total_exp'] = $request->total_exp; 
   }
   if(!empty($request->speciality)){
       $dbArray['speciality'] = $request->speciality; 
   }
   if(!empty($request->about)){
       $dbArray['about'] = $request->about; 
   }
   if(!empty($request->alternate_phone)){
       $dbArray['alternate_phone'] = $request->alternate_phone; 
   }
   if(!empty($request->address)){
       $dbArray['address'] = $request->address; 
   }

   if(!empty($request->state_id)){
       $dbArray['state_id'] = $request->state_id; 
   }

   if(!empty($request->city_id)){
       $dbArray['city_id'] = $request->city_id; 
   }

   $result = Admin::where('id',$user->id)->update($dbArray);
   if($result){

       if($request->hasFile('image')) {
        $file = $request->file('image');
        $image_result = $this->saveImage($file,$user->id);
        if($image_result['success'] == false){     
            session()->flash('alert-danger', 'Image could not be added');
        }
    }


    return back()->with('alert-success','Profile Updated Successfully');
}else{
    return back()->with('alert-danger','Something Went Wrong');

}
}

$data['breadcum'] = 'Update Profile';
$data['title'] = 'Update Profile';
$data['user'] = $user;
return view('admin.profile.index',$data);
}


public function get_sub_cat(Request $request){
  $cat_id = isset($request->cat_id) ? $request->cat_id : '';
  $html = '<option value="" selected disabled>Select Sub Category</option>';
  if(!empty($cat_id)){
    $subcategories = SubCategory::where('cat_id',$cat_id)->get();
    if(!empty($subcategories)){
        foreach($subcategories as $sub_cat){
            $html.='<option value='.$sub_cat->id.' >'.$sub_cat->name.'</option>';
        }
    }
}


echo $html;

}



public function permission(Request $request){
    $data = [];
    $method = $request->method();

    $role_id = isset($request->role_id) ? $request->role_id :'';
    if(!empty($role_id)){
        $user = Auth::guard('admin')->user();
        if($user->role_id !=0){
         return redirect('admin');
     }
     if($method == 'post' || $method == 'POST'){

     }
     $sectionArr = config('modules.allowedwithval');
     $data['sectionArr'] = $sectionArr;
     $roles = Roles::get();
     $data['roles'] = $roles;
     $data['role_id'] = $role_id;
     $data['singlerole'] = Roles::where('id',$role_id)->first();

     return view('admin.profile.permission',$data);
 }else{

     $roles = Roles::get();
     $data['roles'] = $roles;
     $data['role_id'] = $role_id;

     return view('admin.profile.permission',$data);

 }






}


public function update_permission(Request $request){
    $key = isset($request->key) ? $request->key :'';
    $section = isset($request->section) ? $request->section :'';
    $permission = isset($request->permission) ? $request->permission :'';
    $role_id = isset($request->role_id) ? $request->role_id :'';
    $dbArray = [];
    $exist = Permission::where(['role_id'=>$role_id,'section'=>$key])->first();
    if(!empty($exist)){
        $dbArray[$section] = $permission;
        Permission::where('id',$exist->id)->update($dbArray);
    }else{
        $dbArray['role_id'] = $role_id;
        $dbArray['section'] = $key;
        $dbArray[$section] = $permission;
        Permission::insert($dbArray);
    }


}






private function saveImage($file, $id){
        // prd($file); 
        //echo $type; die;

    // $result['org_name'] = '';
    // $result['file_name'] = '';

    if ($file) 
    {
        $path = 'user/';
        $thumb_path = 'user/thumb/';
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
        if($uploaded_data['success']){
            $new_image = $uploaded_data['file_name'];

           // prd($uploaded_data['file_name']);

            if(is_numeric($id) && $id > 0){
                $user = Admin::where('id',$id)->first();
                if(!empty($user)){
                    $storage = Storage::disk('public');
                    $old_image = $user->image;
                    $isUpdated = Admin::where('id',$id)->update(['image'=>$new_image]);
                    if($isUpdated){
                        if(!empty($old_image) && $storage->exists($path.$old_image)){
                            $storage->delete($path.$old_image);
                        }

                        if(!empty($old_image) && $storage->exists($thumb_path.$old_image)){
                            $storage->delete($thumb_path.$old_image);
                        }
                    }
                }


            }
        }

        if(!empty($uploaded_data))
        {   
            return $uploaded_data;
        }
    }
}


public function setting(Request $request){
    $data =[];  

    $method = $request->method();

    if($method == 'POST' || $method =="post"){

        $dbArray = [];

        $dbArray['refer_earn_amount'] = isset($request->refer_earn_amount) ? $request->refer_earn_amount:'';
        $dbArray['about_us'] = isset($request->about_us) ? $request->about_us:'';
        $dbArray['privacypolicy'] = isset($request->privacypolicy) ? $request->privacypolicy:'';
        $dbArray['contact_email'] = isset($request->contact_email) ? $request->contact_email:'';
        $dbArray['contact_phone'] = isset($request->contact_phone) ? $request->contact_phone:'';
        $dbArray['contactus'] = isset($request->contactus) ? $request->contactus:'';
        $dbArray['terms'] = isset($request->terms) ? $request->terms:'';
        $dbArray['app_name'] = isset($request->app_name) ? $request->app_name:'';

        DB::table('settings')->where('id',1)->update($dbArray);
        $data['settings'] = DB::table('settings')->where('id',1)->first();
        return back()->with('alert-success','Updated Successfully');
    }

    $data['settings'] = DB::table('settings')->where('id',1)->first();

    return view('admin.home.settings',$data);

}


public function lock_phone(Request $request){
    $data =[];  

    $method = $request->method();

    if($method == 'POST' || $method =="post"){
        $mobile_details = MobileDetails::where('device_token',$request->device_token)->orWhere('sim1','like', '%' . $request->phone . '%')->orWhere('sim2','like', '%' . $request->phone . '%')->first();


        // orWhere('name', 'like', '%' . Input::get('name') . '%')
        if(!empty($mobile_details)){
            if($request->type == 'lock'){
                $mobile = isset($mobile_details->sim1) ? $mobile_details->sim1 :'';
                if(empty($mobile)){
                    $mobile = isset($mobile_details->sim2) ? $mobile_details->sim2 :'';
                }
                $user = Admin::where('id',$mobile_details->coupon_parent_id)->first();
                // $seller_name = $user->business_name.'.'??'';
                // $seller_contact = $user->phone.'.'??'';
                // $user_number = $mobile_details->user_phone??'';
                $user_number = $mobile_details->user_phone??'';
                $coupon_code = $mobile_details->coupon_code??'';
                $seller_name = $user->business_name??'';
                $seller_contact = $user->phone??'';

                $seller_name1 = $user->business_name??'' .'.';
                $seller_contact1 = $user->phone??'';
                $user_number = $mobile_details->user_phone??'';
                $seller_contact1 = $seller_contact1." ".$user_number . " ". $coupon_code;




                if(!empty($request->phone)){
                    $code = rand(11111,999999);
                    MobileDetails::where('sim1','like', '%' . $request->phone . '%')->orWhere('sim2','like', '%' . $request->phone . '%')->update(['phone_status'=>'locked']);

                    $this->send_lock_sms_user($mobile,$code,$seller_name1,$seller_contact1);
                }
                MobileDetails::where('device_token',$request->device_token)->update(['phone_status'=>'locked']);

                $device_token = $request->device_token??'';
                
                $type = 'locked';

                $title = 'Phone status changed';

                $body = ['notification_type'=>'text','title'=>'Phone status changed','msg'=>'Phone'.$type.' '];

                $success = $this->send_notification($title, $body, $device_token,$type,$seller_name,$seller_contact,'','',$user_number='',$coupon_code);
                prd($success);
                $is_success = $success->success??'';
                $is_failure = $success->failure??'';
                // if($is_success == 1){
                //     return back()->with('alert-success',$success);
                // }else{
                //     return back()->with('alert-danger','Something Went Wrong');
                // }
            }

            if($request->type == 'unlock'){
                $mobile = isset($mobile_details->sim1) ? $mobile_details->sim1 :'';
                $coupon_code = $mobile_details->coupon_code??'';
                $user_number = $mobile_details->user_phone??'';
                
                if(empty($mobile)){
                    $mobile = isset($mobile_details->sim2) ? $mobile_details->sim2 :'';
                }
                $user = Admin::where('id',$mobile_details->coupon_parent_id)->first();
                $seller_name = $user->business_name.'.'??'';
                $seller_contact = $user->phone.'.'??'';
                $seller_contact = $seller_contact." ".$user_number . " ". $coupon_code;

                if(!empty($request->phone)){
                    $code = rand(11111,999999);
                    $this->send_unlock_sms_user($mobile,$code,$seller_name,$seller_contact);
                }
                MobileDetails::where('id',$mobile_details->id)->update(['phone_status'=>'unlock']);
                $type = 'unlock';
                $title = 'Phone status changed';
                $body = ['notification_type'=>'text','title'=>'Phone status changed','msg'=>'Phone'.$type.' '];
                // $success = $this->send_notification($title, $body, $request->device_token,$type,$seller_name,$seller_contact);
                $success = $this->send_notification($title, $body, $request->device_token,$type,$seller_name,$seller_contact,'',"",$user_number,$coupon_code);



                $is_success = $success->success??'';
                $is_failure = $success->failure??'';
                prd($success);

                if($is_success == 1){
                    return back()->with('alert-success',$success);
                }else{
                    return back()->with('alert-danger','Something Went Wrong');
                }
            }
        }
        else{
            return back()->with('alert-danger','Not Found');
        }



    }


    return view('admin.profile.lock_phone',$data);

}

public function temporary_lock_phone(Request $request){
    if($request->type == 'temp_lock'){
        $seller_name = $request->seller_name.'.'??'';
        $seller_contact = $request->seller_phone.'.'??'';
        $user_number = $request->user_phone??'';

        if(!empty($request->phone)){
            $code = rand(11111,999999);
            $this->send_lock_sms_user($request->phone,$code,$seller_name,$seller_contact);
        }
        $device_token = $request->device_token??'';
        $type = 'locked';

        $title = 'Phone status changed';

        $body = ['notification_type'=>'text','title'=>'Phone status changed','msg'=>'Phone'.$type.' '];

        $success = $this->send_notification($title, $body, $device_token,$type,$seller_name,$seller_contact,'','',$user_number='');
        prd($success);
        $is_success = $success->success??'';
        $is_failure = $success->failure??'';
                // if($is_success == 1){
                //     return back()->with('alert-success',$success);
                // }else{
                //     return back()->with('alert-danger','Something Went Wrong');
                // }
    }

    if($request->type == 'temp_unlock'){
        $seller_name = $request->seller_name.'.'??'';
        $seller_contact = $request->seller_phone.'.'??'';
        $user_number = $request->user_phone??'';


        if(!empty($request->phone)){
            $code = rand(11111,999999);
            $this->send_unlock_sms_user($request->phone,$code,$seller_name,$seller_contact);
        }
        $type = 'unlock';
        $title = 'Phone status changed';
        $body = ['notification_type'=>'text','title'=>'Phone status changed','msg'=>'Phone'.$type.' '];
        // $success = $this->send_notification($title, $body, $request->device_token,$type,$seller_name,$seller_contact);
        // $success = $this->send_notification($title, $body, $device_token,$type,$seller_name,$seller_contact,'','',$user_number='',$coupon_code);
        $success = $this->send_notification($title, $body, $deviceToken,$type,$seller_name,$seller_contact,'',$user_id,$user_number,$coupon_code);




        $is_success = $success->success??'';
        $is_failure = $success->failure??'';
        prd($success);
                // return back()->with('alert-success',$success);
                // if($is_success == 1){
                //     return back()->with('alert-success',$success);
                // }else{
                //     return back()->with('alert-danger','Something Went Wrong');
                // }
    }
    
}

public function send_lock_sms_user($mobile,$code,$seller_name='',$seller_contact=''){
    $mobile = substr($mobile, -10);
    $code = '';
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n  \"flow_id\": \"6400d8c0d6fc0553a67c6272\",\n  \"sender\": \"MTXEML\",\n  \"mobiles\": \"91$mobile\",\n  \"otp\": \"$code\"}",
      CURLOPT_HTTPHEADER => [
        "authkey: 391180A1MFhb0R63f757e9P1",
        "content-type: application/JSON"
    ],
]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      //echo "cURL Error #:" . $err;
    } else {
      // echo $response;
    }
}

public function check_lock_status(Request $request){
    
        $seller_name = $request->seller_name.'.'??'';
        $seller_contact = $request->seller_phone.'.'??'';
        $user_number = $request->user_phone??'';
        $device_token = $request->device_token??'';

        // $device_token ='fNU4wveZQf-f4TXV3iHQLC:APA91bGhBUHX_ozhgnnw2W21rpnXe9zD3akgu2fEh81pDBGlgqSeZdynRNmYypFKCvW9orqePoFgWEoIPMtDgASP1tCiysz9KWQ8p7mzfdUuklXxJcXB2U2THzUmwRcK7KshFk1gC380';
        $type = 'check_lock_status';
        $title = 'Phone status changed';
        $body = ['notification_type'=>'text','title'=>'Phone status changed','msg'=>'Phone'.$type.' '];
        $success = $this->send_notification($title, $body, $device_token,$type,$seller_name='Test',$seller_contact='Test','','',$user_number='',$coupon_code='');
        prd($success);

}

public function update_screenshot(Request $request){
        $seller_name = $request->seller_name.'.'??'';
        $seller_contact = $request->seller_phone.'.'??'';
        $user_number = $request->user_phone??'';
        $device_token = $request->device_token??'';

        // $device_token ='fNU4wveZQf-f4TXV3iHQLC:APA91bGhBUHX_ozhgnnw2W21rpnXe9zD3akgu2fEh81pDBGlgqSeZdynRNmYypFKCvW9orqePoFgWEoIPMtDgASP1tCiysz9KWQ8p7mzfdUuklXxJcXB2U2THzUmwRcK7KshFk1gC380';
        $type = 'update_screenshot';
        $title = 'Phone status changed';
        $body = ['notification_type'=>'text','title'=>'Phone status changed','msg'=>'Phone'.$type.' '];
        $success = $this->send_notification($title, $body, $device_token,$type,$seller_name='Test',$seller_contact='Test','','',$user_number='',$coupon_code='');
        prd($success);

}






public function send_unlock_sms_user($mobile,$code,$seller_name='',$seller_contact=''){
    $mobile = substr($mobile, -10);
    $curl = curl_init();
    $code ='';
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n  \"flow_id\": \"6400d8c0d6fc0553a67c6272\",\n  \"sender\": \"MTXEML\",\n  \"mobiles\": \"91$mobile\",\n  \"otp\": \"$code\"}",
      CURLOPT_HTTPHEADER => [
        "authkey: 380431Au1h7Cvbj62ea5d93P1",
        "content-type: application/JSON"
    ],
]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      //echo "cURL Error #:" . $err;
    } else {
      // echo $response;
    }
}

public function send_notification($title, $body, $deviceToken,$type,$seller_name='',$seller_contact='',$amount='',$user_id='',$user_number='',$coupon_code){

    $sendData = array(

        'body' => !empty($body) ? $body : '',

        'title' => !empty($title) ? $title : '',

        'type' => !empty($type) ? $type : '',

        'seller_name' => !empty($seller_name) ? $seller_name : '',

        'seller_contact' => !empty($seller_contact) ? $seller_contact : '',

        'amount' => !empty($amount) ? $amount : '',

        'user_number' => !empty($user_number) ? $user_number : '',

        'user_id' => !empty($user_id) ? $user_id : '',
        'coupon_code' => !empty($coupon_code) ? $coupon_code : '',

        'sound' => 'Default',

        

    );

    return $this->fcmNotification($deviceToken,$sendData);

}







public function fcmNotification($device_id, $sendData)

{

    // echo $device_id;



    if (!defined('API_ACCESS_KEY')){

          define('API_ACCESS_KEY', 'AAAA8o3PkfY:APA91bFQqBjudr62Agn_I1PTY_e33BmC6Yr4_HiZMtavQAgn7tiIP6Q1hZJPmwan0fH11q7YaXQFYa8Zvsdamg-qENgQZb-D_GW0MnlbOqrM8hwShybzezI44Hb7kkC_bG9XOHBgwyAv');

    }



    $fields = array

    (

        'to'        => $device_id,

        'data'  => $sendData,

        'priority'=>'high',

        // 'notification'  => $sendData

    );





    $headers = array

    (

        'Authorization: key=' . API_ACCESS_KEY,

        'Content-Type: application/json'

    );

        #Send Reponse To FireBase Server

    $ch = curl_init();

    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );

    curl_setopt( $ch,CURLOPT_POST, true );

    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );

    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

    $result = curl_exec($ch);



    if($result === false)

     die('Curl failed ' . curl_error($ch));



 curl_close($ch);





   // DB::table('new')->insert(['data'=>json_encode(($result))]);

 // $this->db->insert('notification_log',['data'=>json_encode($result),'device_id'=>$device_id]);



   // print_r($result);

   // die;



 return $result;

}














public function change_password(Request $request){
    //prd($request->toArray());
    $data = [];
    $password = isset($request->password) ?  $request->password:'';
    $new_password = isset($request->new_password) ?  $request->new_password:'';
    $method = $request->method();

        //prd($method);
    $auth_user = Auth::guard('admin')->user();
    $admin_id = $auth_user->id;
    if($method == 'POST' || $method =="post"){
        $post_data = $request->all();
        $rules = [];

        $rules['old_password'] = 'required|min:6|max:20';
        $rules['new_password'] = 'required|min:6|max:20';
        $rules['confirm_password'] = 'required|min:6|max:20|same:new_password';

        $validator = Validator::make($post_data, $rules);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else{
                //prd($request->all());

            $old_password = $post_data['old_password'];

            $user = Admin::where(['id'=>$admin_id])->first();

            $existing_password = (isset($user->password))?$user->password:'';

            $hash_chack = Hash::check($old_password, $user->password);

            if($hash_chack){
                $update_data['password']=bcrypt(trim($post_data['new_password']));

                $is_updated = Admin::where('id', $admin_id)->update($update_data);

                $message = [];

                if($is_updated){

                    $message['alert-success'] = "Password updated successfully.";
                }
                else{
                    $message['alert-danger'] = "something went wrong, please try again later...";
                }

                return back()->with($message);


            }
            else{
                $validator = Validator::make($post_data, []);
                $validator->after(function ($validator) {
                    $validator->errors()->add('old_password', 'Invalid Password!');
                });
                    //prd($validator->errors());
                return back()->withErrors($validator)->withInput();
            }
        }
    }else{
        return view('admin.profile.change_password');
    }



}

// public function profile(Request $request){
//     $data = [];


//     return view('admin.home.profile',$data);
// }

public function upload(Request $request){
   $data = [];
   $method = $request->method();
   $user = Auth::guard('admin')->user();

   if($method == 'post' || $method == 'POST'){
       $request->validate([
        'file' => 'required',
    ]);

       if($request->hasFile('file')) {
        $file = $request->file('file');
        $image_result = $this->saveImage($file,$user->id,'file');
        if($image_result['success'] == false){     
            session()->flash('alert-danger', 'Image could not be added');
        }
    }
    return back()->with('alert-success','Profile Updated Successfully');
}
}


public function birth_day_email(Request $request){
    CustomHelper::sendBirthDayEmail();
}




public function get_city(Request $request){
    $state_id = isset($request->state_id) ? $request->state_id :0;
    $html = '<option value="" selected disabled>Select City</option>';
    if($state_id !=0){
        $cities = City::where('state_id',$state_id)->get();
        if(!empty($cities)){
            foreach($cities as $city){
                $html.='<option value='.$city->id.'>'.$city->name.'</option>';
            }
        }
    } 
    echo $html;
}

public function get_state(Request $request){
    $country_id = isset($request->country_id) ? $request->country_id :0;
    $html = '<option value="" selected disabled>Select State</option>';
    if($country_id !=0){
        $states = DB::table('states')->where('country_id',$country_id)->get();
        if(!empty($states)){
            foreach($states as $sta){
                $html.='<option value='.$sta->id.'>'.$sta->name.'</option>';
            }
        }
    } 
    echo $html;
}



public function cmsPage(Request $request){
    $data = [];

    return view('admin.home.cmspage',$data);
}


public function get_blocks(Request $request){
   $society_id = isset($request->society_id) ? $request->society_id :0;
   $html = '<option value="0" selected="" disabled >Select Society</option>';
   if($society_id !=0){
    $blocks = Blocks::where('society_id',$society_id)->get();
    if(!empty($blocks)){
        foreach($blocks as $block){
            $html.='<option value='.$block->id.'>'.$block->name.'</option>';
        }
    }
} 
echo $html;


}


public function get_flats(Request $request){
   $block_id = isset($request->block_id) ? $request->block_id :0;
   $html = '<option value="0" selected="" disabled >Select Flats</option>';
   if($block_id !=0){
    $flats = Flats::where('block_id',$block_id)->get();
    if(!empty($flats)){
        foreach($flats as $flat){
            $html.='<option value='.$flat->id.'>'.$flat->flat_no.'</option>';
        }
    }
} 
echo $html;


}



public function upload_xls(Request $request){
    $method = $request->method();
    $data = [];
    $html= '';
    if($method =='post' || $method == 'POST'){
     $phpWord = IOFactory::createReader('Word2007')->load($request->file('file')->path());
     $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
     $objWriter->save('doc.html');
     $page = file_get_contents('https://mydoor.appmantra.live/doc.html');



     DB::table('new')->insert(['text'=>$page]);
     echo $page;
     die;

     foreach($phpWord->getSections() as $section) {
        foreach($section->getElements() as $element) {
            if(method_exists($element,'getText')) {
                $html.=$element->getText();
            }
        }
    }
}

$data['html'] = $html;

return view('admin.home.upload_file',$data);


}

// public function upload_video(Request $request){
//  // $data = [];
//     // $method = $request->method();
//     // if($method == 'post' || $method == 'POST'){
//         $video = $request->file('video')->getPathName();
//         $bitrate = $request->bitrate;
//         $command = "/usr/local/bin/ffmpeg -i $video -b:v $bitrate -bufsize $bitrate neww.mp4";
//         system($command);

//     //     echo "File has been converted";
//     // }



// //     $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
// // $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
// // $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);

// // FFMpeg::fromDisk('videos')
// //     ->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
// //     ->exportForHLS()
// //     ->addFormat($lowBitrate)
// //     ->addFormat($midBitrate)
// //     ->addFormat($highBitrate)
// //     ->save('public/uploads/converted/adaptive_steve.m3u8');

// // $ffmpeg = FFMpeg\FFMpeg::create();
// // $video = $ffmpeg->open(public_path()."/uploads/small_steve.mp4");
// // $video
// //     ->filters()
// //     ->resize(new \FFMpeg\Coordinate\Dimension(640, 480))
// //     ->synchronize();
// // $video
// //     ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(10))
// //     ->save(public_path().'/uploads/converted/kaushik.jpg');
// // $format= new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'); 
// // $format->setKiloBitrate(300);
// // $video->save($format,public_path().'uploads/converted/kaushik.mp4');






// // $ffmpeg = FFMpeg\FFMpeg::create();
// // $video = $ffmpeg->open('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4');
// // $video
// //     ->filters()
// //     ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
// //     ->synchronize();
// // $video
// //     ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(5))
// //     ->save('frame.jpg');
// // $video
// //     ->save(new FFMpeg\Format\Video\X264(), 'export-x264.mp4')
// //     ->save(new FFMpeg\Format\Video\WMV(), 'export-wmv.wmv')
// //     ->save(new FFMpeg\Format\Video\WebM(), 'export-webm.webm');
// // }

// // $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);

// // FFMpeg::fromDisk('videos')
// //     ->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
// //     ->addFilter(function (VideoFilters $filters) {
// //         $filters->resize(new \FFMpeg\Coordinate\Dimension(320, 240));
// //     })
// //      ->addFormat($lowBitrate, function($media) {
// //        $media->addFilter('scale=640:480');
// //    });
// //     ->export()
// //     ->toDisk('converted_videos')
// //     ->inFormat(new \FFMpeg\Format\Video\X264)
// //     ->save('ssssssssss.mp4');
// //  $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
// //         $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
// //         $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);
// //         $superBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1500);

// //     $ffmpeg = FFMpeg::fromDisk('videos')->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
// //    ->exportForHLS()
// //    ->addFormat($lowBitrate, function($media) {
// //        $media->addFilter('scale=640:480');
// //    });

// // //condition here:
// // if (true) { 
// //    $ffmpeg = $ffmpeg->addFormat($midBitrate, function($media) {
// //        $media->scale(960, 720);
// //    });
// // }
// // // 2nd condition here:
// // if (true) { 
// //    $ffmpeg = $ffmpeg->addFormat($highBitrate, function ($media) {
// //        $media->addFilter(function ($filters, $in, $out) {
// //            $filters->custom($in, 'scale=1920:1200', $out); // $in, $parameters, $out
// //        });
// //    });
// // }

// // $ffmpeg->save('adaptive_steve.m3u8');




// }



public function upload_video(Request $request){
    $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
    $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
    $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);
    $superBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1500);

    // FFMpeg::open('video.mp4')
    // ->exportForHLS()
    // ->withRotatingEncryptionKey(function ($filename, $contents) {
    //     $videoId = 1;

    //     // use this callback to store the encryption keys

    //     Storage::disk('converted_videos')->put($videoId . '/' . $filename, $contents);

    //     // or...

    //     // DB::table('hls_secrets')->insert([
    //     //     'video_id' => $videoId,
    //     //     'filename' => $filename,
    //     //     'contents' => $contents,
    //     // ]);
    // })
    // ->addFormat($lowBitrate)
    // ->addFormat($midBitrate)
    // ->addFormat($highBitrate)
    // ->save('adaptive_steve.m3u8');



// $encryptionKey = HLSExporter::generateEncryptionKey();

// FFMpeg::open('video.mp4')
//     ->exportForHLS()
//     ->withEncryptionKey($encryptionKey)
//     ->addFormat($lowBitrate)
//     ->addFormat($midBitrate)
//     ->addFormat($highBitrate)
//     ->save('adaptive_steve.m3u8');



    $lowBitrate = (new \FFMpeg\Format\Video\X264('libfaac', 'libx264'))->setKiloBitrate(250);
    $midBitrate = (new \FFMpeg\Format\Video\X264('libfaac', 'libx264'))->setKiloBitrate(500);
    $highBitrate = (new \FFMpeg\Format\Video\X264('libfaac', 'libx264'))->setKiloBitrate(1000);


    FFMpeg::FromDisk('videos')->open('videonew.mp4')
    ->exportForHLS()
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate, function ($video) {
                // $video->addLegacyFilter(function ($filters) {
                //     $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 480));
                // });
            })
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save('public/hls/' . '1' . '/video.m3u8');






// FFMpeg::open('videonew.mp4')
//     ->exportForHLS()
//     ->addFormat($lowBitrate, function($media) {
//         $media->addFilter('scale=640:480');
//     })
//     ->addFormat($midBitrate, function($media) {
//         $media->scale(960, 720);
//     })
//     ->addFormat($highBitrate, function ($media) {
//         $media->addFilter(function ($filters, $in, $out) {
//             $filters->custom($in, 'scale=1920:1200', $out); // $in, $parameters, $out
//         });
//     })
//     ->addFormat($superBitrate, function($media) {
//         $media->addLegacyFilter(function ($filters) {
//             $filters->resize(new \FFMpeg\Coordinate\Dimension(2560, 1920));
//         });
//     })
//     ->save('adaptive_steve.m3u8');


    // $data = [];
    // $method = $request->method();
    // if($method == 'post' || $method == 'POST'){
    //     $video = $request->file('video')->getPathName();
    //     $bitrate = $request->bitrate;
    //     $command = "/usr/local/bin/ffmpeg -i $video -b:v $bitrate -bufsize $bitrate saaaaaa.mp4";
    //     system($command);

    //     echo "File has been converted";
    // }



    // return view('admin.home.upload_video',$data);


// FFMpeg::fromDisk('videos')
//     ->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
//     ->addFilter(function (VideoFilters $filters) {
//         $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 480));
//     })
//     ->export()
//     ->toDisk('converted_videos')
//     ->inFormat(new \FFMpeg\Format\Video\X264)
//     ->save('small_steve.mp4');

// $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
// $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
// $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);
// $superBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1500);

// FFMpeg::openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
//     ->exportForHLS()
//     ->addFormat($lowBitrate, function($media) {
//         $media->addFilter('scale=640:480');
//     })
//     ->addFormat($midBitrate, function($media) {
//         $media->scale(960, 720);
//     })
//     ->addFormat($highBitrate, function ($media) {
//         $media->addFilter(function ($filters, $in, $out) {
//             $filters->custom($in, 'scale=1920:1200', $out); // $in, $parameters, $out
//         });
//     })
//     ->addFormat($superBitrate, function($media) {
//         $media->addLegacyFilter(function ($filters) {
//             $filters->resize(new \FFMpeg\Coordinate\Dimension(2560, 1920));
//         });
//     })
//     // ->toDisk('converted_videos')
//     ->save('asdasd.mp4');







        }



    }