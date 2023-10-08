<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\User;
use App\UserLogin;
use App\AppVersion;
use App\State;
use App\SubscriptionHistory;
use App\City;
use App\UserOtp;
use App\Transaction;
use App\Chats;
use App\MobileDetails;
use App\UserKyc;
use App\AssignCoupon;
use App\Coupon;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWT;
use Razorpay\Api\Api;
use Mail;
use Storage;
use PDF;
use App\Helpers\CustomHelper;
use DB;

class UserController extends Controller
{

   public function __construct()
   {
    $this->user = new User;
    date_default_timezone_set("Asia/Kolkata");
    $this->url = env('BASE_URL');
}




private function saveImage($request){

    $file = $request->file('image');

    if ($file) {
        $path = 'user/';  

        $thumb_path = 'user/thumb/';        
        $storage = Storage::disk('public');
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;
        $side_name = 'user_' . time() . rand(1111, 99999999) . '.' . $file->getClientOriginalExtension();
         $file->move(public_path('storage/'.$path), $side_name);
        return $side_name;
        // $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $THUMB_WIDTH, $THUMB_HEIGHT);
        // if($uploaded_data['success']){
        //     $image = $uploaded_data['file_name'];
        //     return $image;
        // }else{
        //     return "";
        // }
    }

}

public function pincode_data($pincode){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.postalpincode.in/pincode/'.$pincode,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

}


public function user_register(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email'=> 'required',
        'deviceID'=> 'required',
        'device_token'=> 'required',
        'device_type'=> 'required',
        'mobile'=> 'required',
        'mobile_name'=> 'required',
        'imei_no'=> 'required',
        'other_person_mobile_no'=> 'required',
        'other_person_name'=> 'required',      
        'country_code'=> '',      
        'address'=> '',      
        'coupon_code'=> 'required|unique:mobile_details,coupon_code',
    ]);
    $token = null;
    $status = 'New';
    if ($validator->fails()) {
        return response()->json([
            'result' => 'false',
            'message' => json_encode($validator->errors()),
            'token'=>$token,
            'status'=>$status,
        ],400);
    }

    $name = $request->name??'';
    $email = $request->email??'';
    $password = $request->password??'';
    $mobile = $request->mobile??'';
    $phone_model = $request->phone_model??'';
    $alternate_phone = $request->alternate_phone??'';
    $deviceID = $request->deviceID??'';
    $device_token = $request->device_token??'';
    $device_type = $request->device_type??'';
    $ip_address = $request->ip()??'';
    $coupon_code = $request->coupon_code??'';
    $address = $request->address??'';
    $mobile_name = $request->mobile_name??'';
    $imei_no = $request->imei_no??'';
    $imei_no2 = $request->imei_no2??'';
    $other_person_mobile_no = $request->other_person_mobile_no??'';
    $other_person_name = $request->other_person_name??'';
    $relation = $request->relation??'';
    $total_price = $request->total_price??'';
    $downpayment = $request->downpayment??'';
    $emi = $request->emi??'';
    $adhar_no = $request->adhar_no??'';
    $total_months = $request->total_months??'';
    $start_date = $request->start_date??'';
    $android_version = $request->android_version??'';
    $version = $request->version??'';
    $serial_no = $request->serial_no??'';
    $country_code = $request->country_code??'';

    
    $user_city = '';

    $state_id = '';
    $state_name = '';
    $city_id = '';
    $city_name = '';
    $pincode = $request->pincode??'';


   $pincode_data = $this->pincode_data($request->pincode);
    if(!empty($pincode_data)){
        $status = $pincode_data[0]->Status;
        if($status == 'Success'){
            $data = $pincode_data[0]->PostOffice;
            if(!empty($data)){
                $state_name = isset($data[0]->State) ? $data[0]->State :"";
                $city_name = isset($data[0]->District) ? $data[0]->District :"";
                $state = State::where('name','like','%'.$state_name.'%')->first();
                if(!empty($state)){
                    $state_id = $state->id??'';
                }
                $city = City::where('name','like','%'.$city_name.'%')->first();
                if(!empty($city)){
                    $city_id = $city->id??'';
                }
            }
        }
    } 


    if($total_months > 12){
        $total_months = 12;
    }

    $check_coupon_used = MobileDetails::where('coupon_code',$coupon_code)->first();
    if(empty($check_coupon_used)){
        $exist_coupon = Coupon::where('coupon_code',$coupon_code)->first();
        if(!empty($exist_coupon)){
            $exist_user = User::where('mobile',$mobile)->first();
            $image = '';
            $user_id = '';
            $file = $request->file('image');
            if(!empty($file)){
                $image = $this->saveImage($request);
            }

                $dbArray = [];
                $dbArray['name'] = $name;
                $dbArray['email'] = $email;
                $dbArray['mobile'] = $mobile;
                $dbArray['alternate_phone'] = $alternate_phone;
                $dbArray['adhar_no'] = $adhar_no;
                $dbArray['image'] = $image;
                $dbArray['password'] = bcrypt(123456);
                $dbArray['state_id'] = $state_id;
                $dbArray['city_id'] = $city_id;

                
               $user_id = User::insertGetId($dbArray);
            if(!empty($user_id)){
                $user = User::where('id',$user_id)->first();
                $coupon_parent = AssignCoupon::where('coupon_id',$exist_coupon->id)->latest()->first();
                $coupon_parent_id = $coupon_parent->child_id;
                $dbArray1 = [];
                $dbArray1['user_id'] = $user_id;
                $dbArray1['user_name'] = $user->name??'';
                $dbArray1['user_phone'] = $user->mobile??'';
                $dbArray1['user_image'] = $user->image??'';
                $dbArray1['coupon_id'] = $exist_coupon->couponID??'';
                $dbArray1['coupon_code'] = $coupon_code;
                $dbArray1['coupon_parent_id'] = $coupon_parent_id;
                $dbArray1['mobile_name'] = $mobile_name;
                $dbArray1['phone_model'] = $phone_model;
                $dbArray1['android_version'] = $android_version;
                $dbArray1['imei_no'] = $imei_no;
                $dbArray1['phone_status'] = 'unlock';
                $dbArray1['date_of_purchase'] = date('Y-m-d H:i:s');
                $dbArray1['imei_no2'] = $imei_no2;
                $dbArray1['country_code'] = '+'.$country_code;
                $dbArray1['other_person_mobile_no'] = $other_person_mobile_no;
                $dbArray1['other_person_name'] = $other_person_name;
                $dbArray1['relation'] = $relation;
                $dbArray1['total_price'] = $total_price;
                $dbArray1['downpayment'] = $downpayment;
                $dbArray1['emi'] = $emi;
                $dbArray1['device_token'] = $device_token;
                $dbArray1['total_months'] = $total_months;
                $dbArray1['version'] = $version;
                $dbArray1['state_id'] = $state_id;
                $dbArray1['state_name'] = $state_name;
                $dbArray1['city_id'] = $city_id;
                $dbArray1['city_name'] = $city_name;
                $dbArray1['pincode'] = $pincode;
                $dbArray1['serial_no'] = $serial_no;
                $dbArray1['address'] = $address;



                $dbArray1['image_version'] = 2;
                $dbArray1['start_date'] = date('Y-m-d',strtotime($start_date));

                $check_coupon_used = MobileDetails::where('coupon_code',$coupon_code)->first();
                if(!empty($check_coupon_used)){
                    return response()->json([
                        'result' => 'false',
                        'message' => "Coupon Already Used",
                        'token'=>'',
                        'status'=>'',
                    ],200);
                }

                $mobile_id = MobileDetails::insertGetId($dbArray1);
                for ($i=0; $i < $total_months; $i++) { 
                    $start_date1 = date('Y-m-d',strtotime("+".$i." months", strtotime($start_date)));
                    $dbArray2 = [];
                    $dbArray2['mobile_id'] = $mobile_id;
                    $dbArray2['user_id'] = $user_id;
                    $dbArray2['date'] = $start_date1;
                    $dbArray2['amount'] = $emi;
                    $dbArray2['status'] = 1;
                    $dbArray2['paid_status'] = 0;
                    DB::table('emis')->insert($dbArray2);
                }
                AssignCoupon::where('id',$coupon_parent->id)->update(['is_used'=>1,'is_view'=>1]);
                AssignCoupon::where('coupon_id',$exist_coupon->id)->update(['is_used'=>1,'is_view'=>1]);
                Coupon::where('id',$coupon_parent->coupon_id)->update(['is_used'=>1,'is_view'=>1,'mobile_id'=>$mobile_id]);
                UserLogin::create([
                    "user_id" => $user_id,
                    "ip_address" => $request->ip(),
                    "deviceID" => $deviceID,
                    "deviceToken" => $device_token,
                    "deviceType" => $device_type,
                ]);

                \Config::set('jwt.user', 'App\User'); 
                \Config::set('auth.providers.users.model', \App\User::class);
                $credentials = $request->only('mobile','password');
                try {
                    if (! $token = JWTAuth::attempt(['mobile'=>$request->mobile,'password'=>123456])) {
                        return response()->json([
                            'result' => 'false',
                            'message' => "Invalid",
                            'token'=>$token,
                            'status'=>$status,
                        ], 401);
                    }
                } catch (JWTException $e) {
                    return response()->json([
                        'result' => 'false',
                        'message' => "something went wrong",
                        'token'=>$token,
                        'status'=>$status,
                    ], 500);
                }
                return response()->json([
                    'result' => 'success',
                    'message' => "Successfully Registered",
                    'token'=>$token,
                    'status'=>$status,
                ],200);

            }
        }else{
         return response()->json([
            'result' => 'false',
            'message' => "Coupon Not Exist",
            'token'=>$token,
            'status'=>$status,
        ],200);
     }
 }else{
    return response()->json([
        'result' => 'false',
        'message' => "Coupon Already Used",
        'token'=>$token,
        'status'=>$status,
    ],200);
}
}










public function add_customer(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => '',
        'email'=> '',
        'deviceID'=> '',
        'device_token'=> '',
        'device_type'=> '',
        'mobile'=> '',
        'mobile_name'=> '',
        'imei_no'=> '',
        'other_person_mobile_no'=> '',
        'other_person_name'=> '',      
        'coupon_code'=> '',
    ]);
    $token = null;
    $status = 'New';
    if ($validator->fails()) {
        return response()->json([
            'result' => 'false',
            'message' => json_encode($validator->errors()),
            'token'=>$token,
            'status'=>$status,
        ],400);
    }

    $name = $request->name??'';
    $email = $request->email??'';
    $password = $request->password??'';
    $mobile = $request->mobile??'';
    $phone_model = $request->phone_model??'';
    $alternate_phone = $request->alternate_phone??'';
    $deviceID = $request->deviceID??'';
    $device_token = $request->device_token??'';
    $device_type = $request->device_type??'';
    $ip_address = $request->ip()??'';
    $coupon_code = $request->coupon_code??'';
    $mobile_name = $request->mobile_name??'';
    $imei_no = $request->imei_no??'';
    $imei_no2 = $request->imei_no2??'';
    $other_person_mobile_no = $request->other_person_mobile_no??'';
    $other_person_name = $request->other_person_name??'';
    $relation = $request->relation??'';
    $total_price = $request->total_price??'';
    $downpayment = $request->downpayment??'';
    $emi = $request->emi??'';
    $adhar_no = $request->adhar_no??'';
    $total_months = $request->total_months??'';
    $start_date = $request->start_date??'';
    $android_version = $request->android_version??'';
    $version = $request->version??'';
    $serial_no = $request->serial_no??'';
    $country_code = $request->country_code??'';
    $address = $request->address??'';

    
    $user_city = '';

    $state_id = '';
    $state_name = '';
    $city_id = '';
    $city_name = '';
    $pincode = $request->pincode??'';


   // $pincode_data = $this->pincode_data($request->pincode);
   //  if(!empty($pincode_data)){
   //      $status = $pincode_data[0]->Status;
   //      if($status == 'Success'){
   //          $data = $pincode_data[0]->PostOffice;
   //          if(!empty($data)){
   //              $state_name = isset($data[0]->State) ? $data[0]->State :"";
   //              $city_name = isset($data[0]->District) ? $data[0]->District :"";
   //              $state = State::where('name','like','%'.$state_name.'%')->first();
   //              if(!empty($state)){
   //                  $state_id = $state->id??'';
   //              }
   //              $city = City::where('name','like','%'.$city_name.'%')->first();
   //              if(!empty($city)){
   //                  $city_id = $city->id??'';
   //              }
   //          }
   //      }
   //  } 


    if($total_months > 12){
        $total_months = 12;
    }

    $check_coupon_used = MobileDetails::where('coupon_code',$coupon_code)->first();
    if(empty($check_coupon_used)){
        $exist_coupon = Coupon::where('coupon_code',$coupon_code)->first();
        if(!empty($exist_coupon)){
            $exist_user = User::where('mobile',$mobile)->first();
            $image = '';
            $user_id = '';
            $file = $request->file('image');
            if(!empty($file)){
                $image = $this->saveImage($request);
            }

                $dbArray = [];
                $dbArray['name'] = $name;
                $dbArray['email'] = $email;
                $dbArray['mobile'] = $mobile;
                $dbArray['alternate_phone'] = $alternate_phone;
                $dbArray['adhar_no'] = $adhar_no;
                $dbArray['image'] = $image;
                $dbArray['password'] = bcrypt(123456);
               

                
               $user_id = User::insertGetId($dbArray);
            if(!empty($user_id)){
                $user = User::where('id',$user_id)->first();
                $coupon_parent = AssignCoupon::where('coupon_id',$exist_coupon->id)->latest()->first();
                $coupon_parent_id = $coupon_parent->child_id;
                $dbArray1 = [];
                $dbArray1['user_id'] = $user_id;
                $dbArray1['user_name'] = $user->name??'';
                $dbArray1['user_phone'] = $user->mobile??'';
                $dbArray1['user_image'] = $user->image??'';
                $dbArray1['coupon_id'] = $exist_coupon->couponID??'';
                $dbArray1['coupon_code'] = $coupon_code;
                $dbArray1['coupon_parent_id'] = $coupon_parent_id;
                $dbArray1['mobile_name'] = $mobile_name;
                $dbArray1['address'] = $address;
                $dbArray1['phone_model'] = $phone_model;
                $dbArray1['android_version'] = $android_version;
                $dbArray1['imei_no'] = $imei_no;
                $dbArray1['phone_status'] = 'unlock';
                $dbArray1['date_of_purchase'] = date('Y-m-d H:i:s');
                $dbArray1['imei_no2'] = $imei_no2;
                $dbArray1['other_person_mobile_no'] = $other_person_mobile_no;
                $dbArray1['other_person_name'] = $other_person_name;
                $dbArray1['relation'] = $relation;
                $dbArray1['total_price'] = $total_price;
                $dbArray1['downpayment'] = $downpayment;
                $dbArray1['country_code'] = $country_code;
                $dbArray1['emi'] = $emi;
                $dbArray1['device_token'] = $device_token;
                $dbArray1['total_months'] = $total_months;
                $dbArray1['version'] = $version;
                // $dbArray1['state_id'] = $state_id??'';
                // $dbArray1['state_name'] = $state_name??'';
                // $dbArray1['city_id'] = $city_id??'';
                // $dbArray1['city_name'] = $city_name??'';
                // $dbArray1['pincode'] = $pincode;
                $dbArray1['serial_no'] = $serial_no;



                $dbArray1['image_version'] = 2;
                $dbArray1['start_date'] = date('Y-m-d',strtotime($start_date));

                $check_coupon_used = MobileDetails::where('coupon_code',$coupon_code)->first();
                if(!empty($check_coupon_used)){
                    return response()->json([
                        'result' => 'false',
                        'message' => "Coupon Already Used",
                        'token'=>'',
                        'status'=>'',
                    ],200);
                }

                $mobile_id = MobileDetails::insertGetId($dbArray1);
                for ($i=0; $i < $total_months; $i++) { 
                    $start_date1 = date('Y-m-d',strtotime("+".$i." months", strtotime($start_date)));
                    $dbArray2 = [];
                    $dbArray2['mobile_id'] = $mobile_id;
                    $dbArray2['user_id'] = $user_id;
                    $dbArray2['date'] = $start_date1;
                    $dbArray2['amount'] = $emi;
                    $dbArray2['status'] = 1;
                    $dbArray2['paid_status'] = 0;
                    DB::table('emis')->insert($dbArray2);
                }
                AssignCoupon::where('id',$coupon_parent->id)->update(['is_used'=>1,'is_view'=>1]);
                AssignCoupon::where('coupon_id',$exist_coupon->id)->update(['is_used'=>1,'is_view'=>1]);
                Coupon::where('id',$coupon_parent->coupon_id)->update(['is_used'=>1,'is_view'=>1,'mobile_id'=>$mobile_id]);
                UserLogin::create([
                    "user_id" => $user_id,
                    "ip_address" => $request->ip(),
                    "deviceID" => $deviceID,
                    "deviceToken" => $device_token,
                    "deviceType" => $device_type,
                ]);

                \Config::set('jwt.user', 'App\User'); 
                \Config::set('auth.providers.users.model', \App\User::class);
                $credentials = $request->only('mobile','password');
                try {
                    if (! $token = JWTAuth::attempt(['mobile'=>$request->mobile,'password'=>123456])) {
                        return response()->json([
                            'result' => 'false',
                            'message' => "Invalid",
                            'token'=>$token,
                            'status'=>$status,
                        ], 401);
                    }
                } catch (JWTException $e) {
                    return response()->json([
                        'result' => 'false',
                        'message' => "something went wrong",
                        'token'=>$token,
                        'status'=>$status,
                    ], 500);
                }
                return response()->json([
                    'result' => 'success',
                    'message' => "Successfully Registered",
                    'token'=>$token,
                    'status'=>$status,
                ],200);

            }
        }else{
         return response()->json([
            'result' => 'false',
            'message' => "Coupon Not Exist",
            'token'=>$token,
            'status'=>$status,
        ],200);
     }
 }else{
    return response()->json([
        'result' => 'false',
        'message' => "Coupon Already Used",
        'token'=>$token,
        'status'=>$status,
    ],200);
}
}


















public function user_login(Request $request){
    DB::table('new')->insert(['data'=>json_encode($request->toArray())]);
    $validator = Validator::make($request->all(), [
        'deviceID'=> 'required',
        'device_token'=> 'required',
        'device_type'=> 'required',
        'coupon_code'=> '',
        'imei_no'=> '',
        'imei_no2'=> '',
    ]);
    $user_details = null;
    $status = 'New';
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'user_details'=>$user_details,
        ],400);
    }

    $ip_address = $request->ip();
    // $exist = MobileDetails::where('imei_no',$request->imei_no)->orWhere('imei_no2',$request->imei_no2)->where(['coupon_code'=>$request->coupon_code])->first();
    $exist = MobileDetails::where('imei_no',$request->imei_no)->where('phone_status','!=','remove')->first();
    if(!empty($exist)){
        UserLogin::create([
            "user_id" => $exist->user_id??0,
            "ip_address" => $request->ip(),
            "deviceID" => $request->deviceID,
            "deviceToken" => $request->device_token,
            "deviceType" => $request->device_type,
        ]);
        MobileDetails::where('imei_no',$request->imei_no)->update(['device_token'=>$request->device_token,'version'=>$request->version]);
        $user = User::where('id',$exist->user_id)->first();
        if(!empty($user)){
             $user->mobile_details = $exist;
        }
        return response()->json([
            'result' => 'success',
            'message' => 'Successfully login',
            'user_details'=>$user,
        ],200);

    }else{
        return response()->json([
            'result' => 'failure',
            'message' => 'User Not Exist',
            'user_details'=>$user_details,
        ],200);
    }
}


public function send_sms_to_fetch(Request $request){
    $sim1 = $request->sim1??'';
    $sim2 = $request->sim2??'';
    if(!empty($sim1)){
        $this->send_sms_verification($sim1,'sim1');
        return response()->json([
            'result' => 'success',
            'message' => 'Message Sent Successfully',
        ],200);
    }
    elseif(!empty($sim2)){
        $this->send_sms_verification($sim2,'sim2');
        return response()->json([
            'result' => 'success',
            'message' => 'Message Sent Successfully',
        ],200);
    }else{
        return response()->json([
            'result' => 'success',
            'message' => 'number required',
        ],200);
    }
}

public function send_sms_verification($mobile,$type){
    $mobile = substr($mobile, -10);
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n  \"flow_id\": \"633417eee6dbfb439e6864d1\",\n  \"sender\": \"CCTREP\",\n  \"mobiles\": \"91$mobile\",\n  \"sim\": \"$type\"}",
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

public function send_notification($title, $body, $deviceToken,$type,$seller_name='',$seller_contact='',$amount=''){
    $sendData = array(
        'body' => !empty($body) ? $body : '',
        'title' => !empty($title) ? $title : '',
        'type' => !empty($type) ? $type : '',
        'seller_name' => !empty($seller_name) ? $seller_name : '',
        'seller_contact' => !empty($seller_contact) ? $seller_contact : '',
        'amount' => !empty($amount) ? $amount : '',
        'sound' => 'Default',
        
    );
    return $this->fcmNotification($deviceToken,$sendData);
}



public function fcmNotification($device_id, $sendData)
{
    if (!defined('API_ACCESS_KEY')){
        define('API_ACCESS_KEY', 'AAAALbfBga4:APA91bElJsuZZ3jAzRvKTqr3J8Ashu6V0aOAa0K06JZbicix5i_zig-jd9dzX3-l98RfG82U4lrZl8Abih5sauqvpFJ8aOOeA5Q0MdXwMmPcnksvEYlUv0CP-YsT1QeyxoasniW5I98j');
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



 // $this->db->insert('notification_log',['data'=>json_encode($result),'device_id'=>$device_id]);

   // print_r($result);
   // die;

 return $result;
}

public function run_cronjob(){
    $mobile_details = $this->db->get_where('mobile_details',['phone_status'=>'locked'])->result();
    $mobile_details = MobileDetails::where('phone_status','locked')->get();
    if(!empty($mobile_details)){
        foreach($mobile_details as $mobile){
            $admin = Admin::where('id',$mobile->coupon_parent_id)->first();
            $device_token = $mobile->device_token;
            $seller_name = $admin->business_name??'';
            $seller_contact = $admin->phone??'';
            $type ='locked';
            $title = 'Phone status changed';
            $body = ['notification_type'=>'text','title'=>'Phone status changed','msg'=>'Phone'.$status.' '];
            $success = $this->send_notification($title, $body, $device_token,$type,$seller_name,$seller_contact);
        }
    }
}

public function notification_list(Request $request)
{
     $validator = Validator::make($request->all(), [
        'token'=> 'required',
    ]);

    $data = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => false,
            'message' => json_encode($validator->errors()),
        ],400);
    }
    $user = auth('users')->user();

}




}