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

use App\SubCategory;

use App\Reports;

use App\MobileDetails;

use App\Coupon;



use App\SubscriptionHistory;

use Yajra\DataTables\DataTables;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UserExport;

use App\Exports\ReportExport;

use Rap2hpoutre\FastExcel\FastExcel;

use Storage;

use DB;

use Hash;



use PhpOffice\PhpWord\IOFactory;

use App\Imports\MobileDetailsImport;









class UserController extends Controller

{



    private $ADMIN_ROUTE_NAME;



    public function __construct()

    {

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }



    public function index(Request $request)

    {



        $search = isset($request->search) ? $request->search : '';

        $user_ids = isset($request->user_ids) ? $request->user_ids : '';

        $role_id = isset($request->role_id) ? $request->role_id : '';

        $start_date = isset($request->start_date) ? $request->start_date : '';

        $end_date = isset($request->end_date) ? $request->end_date : '';

        $phone_status = isset($request->phone_status) ? $request->phone_status : '';

        $is_export = isset($request->is_export) ? $request->is_export : 0;
        $state_id = isset($request->state_id) ? $request->state_id : "";
        $city_id = isset($request->city_id) ? $request->city_id : "";



        // prd($request->toArray());

        $key = isset($_GET['key']) ? $_GET['key'] : '';



        $data['search'] = $search;

        $date = $request->date ?? '';

        $admin_id = Auth::guard('admin')->user()->id ?? '';

        $parent_id = Auth::guard('admin')->user()->parent_id ?? '';

        $role_id = Auth::guard('admin')->user()->role_id ?? '';

        $i = $role_id - 1;

        $child_ids = [];



        $role_id = Auth::guard('admin')->user()->role_id ?? '';



        if ($role_id == 7) {



            $child_ids = [];

            $admin_data = Admin::where('id', $admin_id)->first();

            $superdist_id = $admin_data->superdist_id ?? '';

            if (!empty($superdist_id)) {

                $superdist_id = explode(",", $superdist_id);

                foreach ($superdist_id as $sdist_id) {

                    $allchild_ids = CustomHelper::getParentIds($role_id, $sdist_id, 1);

                    if (!empty($allchild_ids)) {

                        foreach ($allchild_ids as $key => $value) {

                            $child_ids[] = $value;
                        }
                    }
                }
            }
        } else if ($parent_id != 0) {


            $child_ids = CustomHelper::getParentIds($role_id, $admin_id, $i);
        }



        $superdistributor_id = $request->superdistributor_id ?? '';

        $distributor_id = $request->distributor_id ?? '';

        $tsm_id = $request->tsm_id ?? '';

        $seller_id = $request->seller_id ?? '';



        if (!empty($superdistributor_id)) {

            $child_ids = [];



            $child_ids = CustomHelper::getChildIds(2, $superdistributor_id, 4);
        }



        if (!empty($distributor_id)) {

            $child_ids = [];

            $child_ids = CustomHelper::getChildIds(3, $distributor_id, 3);
        }

        if (!empty($tsm_id)) {

            $child_ids = [];



            $child_ids = CustomHelper::getChildIds(4, $tsm_id, 2);
        }



        if (!empty($seller_id)) {

            $child_ids = [];

            $child_ids[] = $seller_id;
        }



        // \DB::enableQueryLog(); 

        $users = MobileDetails::orderBy('id', 'desc');

        if (!empty($search)) {

            $users->where('user_name', 'like', '%' . $search . '%');

            $users->orWhere('user_phone', 'like', '%' . $search . '%');
            $users->orWhere('coupon_code', 'like', '%' . $search . '%');
            $users->orWhere('imei_no', 'like', '%' . $search . '%');
            // $users->orWhere('coupon_code', 'like', '%' . $search . '%');

        }

        if (!empty($start_date)) {

            $users->whereDate('date_of_purchase', '>=', $start_date);
        }

        if (!empty($end_date)) {

            $users->whereDate('date_of_purchase', '<=', $end_date);
        }

        if (!empty($date)) {

            $users->whereDate('date_of_purchase', $date);
        }
        if (!empty($phone_status)) {

            $users->where('phone_status', $phone_status);
        }

        if ($parent_id != 0 || !empty($child_ids)) {

            $users->whereIn('coupon_parent_id', $child_ids);
        }

        if ($role_id == 7) {

            $users->whereIn('coupon_parent_id', $child_ids);
        }

        if ($is_export == 1) {

            $this->export_users($child_ids, $search);

            return back()->with('alert-success', 'Report Generated on Report Section Shortly...');
        }
        if (!empty($state_id)) {
            $users->where('state_id', $state_id);
        }
        if (!empty($city_id)) {
            $users->where('city_id', $city_id);
        }



        $users = $users->paginate(10);

        // prd($users);

        // dd(\DB::getQueryLog()); 

        $data['users'] = $users;

        return view('admin.user.index', $data);
    }





    public function export_users($child_ids, $search)
    {

        $user_id = Auth::guard('admin')->user()->id ?? '';

        $data[] = [

            'child_ids' => json_encode($child_ids),

            'search' => $search,



        ];

        $dbArray = [];

        $dbArray['type'] = "user_export";

        $dbArray['added_by'] = $user_id;

        $dbArray['data'] = json_encode($data);

        Reports::insert($dbArray);
    }





















    public function export(Request $request)
    {

        $search = isset($request->search) ? $request->search : '';

        $users = User::select('id', 'name', 'email', 'phone', 'wallet', 'referral_code');

        if (!empty($search)) {

            $users->where('name', 'like', '%' . $search . '%');

            $users->orWhere('email', 'like', '%' . $search . '%');

            $users->orWhere('phone', 'like', '%' . $search . '%');
        }

        $users = $users->get();

        if (!empty($users) && $users->count() > 0) {

            foreach ($users as $user) {

                $userArr = [];

                $userArr['ID'] = $user->id;

                $userArr['Name'] = $user->name ?? '';

                $userArr['Email'] = $user->email ?? '';

                $userArr['Phone'] = $user->phone ?? '';

                $userArr['Wallet'] = $user->wallet ?? 0;

                $userArr['Referal Code'] = $user->referral_code ?? 0;

                $exportArr[] = $userArr;
            }

            $filedNames = array_keys($exportArr[0]);

            $fileName = 'users_' . date('Y-m-d-H-i-s') . '.xlsx';

            return Excel::download(new UserExport($exportArr, $filedNames), $fileName);
        }
    }















    public function subscriptions(Request $request)
    {

        $id = isset($request->id) ? $request->id : 0;

        $data = [];

        $method = $request->method();





        if ($method == 'post' || $method == 'POST') {

            $rules = [];

            $rules['course_id'] = 'required';



            $this->validate($request, $rules);



            $dbArray = [];





            $course_details = Course::where('id', $request->course_id)->first();



            $dbArray['course_id'] = $request->course_id;

            $dbArray['user_id'] = $id;

            $dbArray['start_date'] = date('Y-m-d');

            $dbArray['amount'] = $course_details->full_amount;

            $dbArray['payment_type'] = 'Admin';

            $dbArray['payment_cause'] = 'subscription';

            $dbArray['paid_status'] = 1;

            $dbArray['end_date'] = date('Y-m-d', strtotime("+" . $course_details->duration . " months", strtotime(date('Y-m-d'))));



            SubscriptionHistory::insert($dbArray);
        }



        $subscriptions = SubscriptionHistory::where('is_delete', 0)->where('user_id', $id)->latest()->paginate(10);



        $back_url = $this->ADMIN_ROUTE_NAME . '/user';





        $course = Course::select('id', 'course_name')->where('status', 1)->get();





        $data['user'] = User::where('id', $id)->first();

        $data['subscriptions'] = $subscriptions;

        $data['course'] = $course;



        $data['back_url'] = $back_url;









        return view('admin.user.subscription', $data);
    }





    public function update_subscription(Request $request)
    {

        $method = $request->method();

        if ($method == 'post' || $method == 'POST') {

            SubscriptionHistory::where('id', $request->subscription_id)->update(['end_date' => $request->end_date]);

            return back()->with('alert-success', 'Updated Successfully');
        }
    }











    public function add(Request $request)

    {

        $details = [];



        $id = isset($request->id) ? $request->id : 0;



        $users = '';



        if (is_numeric($id) && $id > 0) {

            $users = User::find($id);

            if (empty($users)) {

                return redirect($this->ADMIN_ROUTE_NAME . '/user');
            }
        }





        if ($request->method() == "POST" || $request->method() == "post") {



            // prd($request->toArray());



            if (empty($back_url)) {

                $back_url = $this->ADMIN_ROUTE_NAME . '/user';
            }





            if (is_numeric($request->id) && $request->id > 0) {

                $details['name'] = 'required';

                $details['email'] = 'required';

                $details['phone'] = 'required';

                $details['status'] = 'required';
            } else {



                $details['name'] = 'required';

                $details['email'] = 'required';

                $details['phone'] = 'required';

                $details['status'] = 'required';
            }



            $this->validate($request, $details);



            // prd($dd);



            $createdDetails = $this->save($request, $id);



            if ($createdDetails) {

                $alert_msg = "User Created Successfully";



                if (is_numeric($id) & $id > 0) {

                    $alert_msg = "User Updated Successfully";
                }

                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {



                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }



        $page_Heading = "Add User";

        if (isset($users->id)) {

            $name = $users->name;

            $page_Heading = 'Update User -' . $name;
        }







        $details['page_Heading'] = $page_Heading;

        $details['id'] = $id;

        $details['users'] = $users;





        return view('admin.user.form', $details);
    }





    public function save(Request $request, $id = 0)

    {

        $details = $request->except(['_token', 'back_url']);



        if (!empty($request->password)) {

            $details['password'] = bcrypt($request->password);
        }



        $old_img = '';



        $user = new User;



        if (is_numeric($id) && $id > 0) {

            $exist = User::find($id);



            if (isset($exist->id) && $exist->id == $id) {

                $user = $exist;



                $old_img = $exist->image;
            }
        }



        foreach ($details as $key => $val) {

            $user->$key = $val;
        }



        $isSaved = $user->save();



        if ($isSaved) {

            $this->saveImage($request, $user, $old_img);
        }



        return $isSaved;
    }



    private function saveImage($request, $user, $oldImg = '')
    {



        $file = $request->file('image');



        //prd($file);

        if ($file) {

            $path = 'user/';

            $thumb_path = 'user/thumb/';

            $storage = Storage::disk('public');

            //prd($storage);

            $IMG_WIDTH = 768;

            $IMG_HEIGHT = 768;

            $THUMB_WIDTH = 336;

            $THUMB_HEIGHT = 336;



            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext = '', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb = true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);



            // prd($uploaded_data);

            if ($uploaded_data['success']) {



                if (!empty($oldImg)) {

                    if ($storage->exists($path . $oldImg)) {

                        $storage->delete($path . $oldImg);
                    }

                    if ($storage->exists($thumb_path . $oldImg)) {

                        $storage->delete($thumb_path . $oldImg);
                    }
                }

                $image = $uploaded_data['file_name'];



                // prd($image);

                $user->image = $image;

                $user->save();
            }



            if (!empty($uploaded_data)) {

                return  $uploaded_data;
            }
        }
    }



    public function change_user_role(Request $request)
    {

        $id = isset($request->id) ? $request->id : '';

        $role_id = isset($request->role_id) ? $request->role_id : '';



        $users = User::where('id', $id)->first();

        if (!empty($users)) {



            User::where('id', $id)->update(['role_id' => $role_id]);

            $response['success'] = true;

            $response['message'] = 'Status updated';





            return response()->json($response);
        } else {

            $response['success'] = false;

            $response['message'] = 'Not  Found';

            return response()->json($response);
        }
    }



    public function delete(Request $request)

    {

        $id = isset($request->id) ? $request->id : 0;

        $is_delete = 0;



        if (is_numeric($id) && $id > 0) {

            $is_delete = User::where('id', $id)->update(['is_delete' => '1']);
        }



        if (!empty($is_delete)) {

            return back()->with('alert-success', 'User Deleted Successfully');
        } else {



            return back()->with('alert-danger', 'something went wrong, please try again...');
        }
    }



    public function delete_subscription(Request $request)

    {

        $id = isset($request->id) ? $request->id : 0;

        $is_delete = 0;



        if (is_numeric($id) && $id > 0) {

            $is_delete = SubscriptionHistory::where('id', $id)->update(['is_delete' => '1']);
        }



        if (!empty($is_delete)) {

            return back()->with('alert-success', 'Subscription Deleted Successfully');
        } else {



            return back()->with('alert-danger', 'something went wrong, please try again...');
        }
    }





    public function subscription(Request $request)
    {
    }

    public function wallet(Request $request)
    {



        $method = $request->method();

        if ($method == 'post' || $method == 'POST') {

            $rules = [];

            $rules['user_id'] = 'required';

            $rules['amount'] = 'required';

            $rules['type'] = 'required';





            $this->validate($request, $rules);



            $user = User::where('id', $request->user_id)->first();

            if (!empty($user)) {

                $wallet = $user->wallet ?? 0;

                if ($request->type == 'credit') {

                    $new_wallet = $wallet + $request->amount;

                    $success = User::where('id', $user->id)->update(['wallet' => $new_wallet]);

                    if ($success) {

                        $transactionArr = [];

                        $transactionArr['user_id'] = $user->id;

                        $transactionArr['txn_no'] = rand(111111, 9999999);

                        $transactionArr['reason'] = 'Amount Added By Admin';

                        $transactionArr['amount'] = $request->amount;

                        $transactionArr['type'] = 'credit';

                        $transactionArr['status'] = 1;

                        DB::table('transactions')->insert($transactionArr);

                        return back()->with('alert-success', 'Added  Successfully');
                    } else {

                        return back()->with('alert-danger', 'something went wrong, please try again...');
                    }
                }

                if ($request->type == 'debit') {

                    if ($wallet >= $request->amount) {

                        $new_wallet = $wallet - $request->amount;

                        $success = User::where('id', $user->id)->update(['wallet' => $new_wallet]);

                        if ($success) {

                            $transactionArr = [];

                            $transactionArr['user_id'] = $user->id;

                            $transactionArr['txn_no'] = rand(111111, 9999999);

                            $transactionArr['reason'] = 'Amount Debited By Admin';

                            $transactionArr['amount'] = $request->amount;

                            $transactionArr['type'] = 'debit';

                            $transactionArr['status'] = 1;

                            DB::table('transactions')->insert($transactionArr);

                            return back()->with('alert-success', 'Debited  Successfully');
                        } else {

                            return back()->with('alert-danger', 'something went wrong, please try again...');
                        }
                    } else {

                        return back()->with('alert-danger', 'Insufficient balance');
                    }
                }
            }
        }
    }

    public function change_phone_status(Request $request)
    {
        $method = $request->method();
        if ($method == 'post' || $method == "POST") {
            $id = $request->id;
            $mobile_details = MobileDetails::where('user_id', $id)->first();
            $seller = Admin::where('id', $mobile_details->coupon_parent_id)->first();
            $phonestatus = $request->phonestatus;
            $device_token = $mobile_details->device_token ?? '';
            $coupon_code = $mobile_details->coupon_code ?? '';
            $user_number = $mobile_details->user_phone ?? '';

            $seller_name = $seller->business_name ?? '';
            $seller_contact = $seller->phone ?? '';


            if ($phonestatus == 'locked') {
                $type = 'locked';
                $title = 'Phone status changed';
                $body = ['notification_type' => 'text', 'title' => 'Phone status changed', 'msg' => 'Phone' . $type . ' '];
                $success = $this->send_notification($title, $body, $device_token, $type, $seller_name, $seller_contact, '', '', $user_number, $coupon_code);
            }
            if ($phonestatus == 'unlock') {
                $type = 'unlock';
                $title = 'Phone status changed';
                $body = ['notification_type' => 'text', 'title' => 'Phone status changed', 'msg' => 'Phone' . $type . ' '];
                $success = $this->send_notification($title, $body, $device_token, $type, $seller_name, $seller_contact, '', '', $user_number, $coupon_code);
            }
            if ($phonestatus == 'remove') {
                $type = 'remove';
                $title = 'Phone status changed';
                $body = ['notification_type' => 'text', 'title' => 'Phone status changed', 'msg' => 'Phone' . $type . ' '];
                $success = $this->send_notification($title, $body, $device_token, $type, $seller_name, $seller_contact, '', '', $user_number, $coupon_code);
                $this->send_remove_notification($mobile_details->serial_no);
            }

            // $checkstatus = DB::table('mobile_details')->where('user_id', $id)->update(['phone_status' => $phonestatus]);
            $checkstatus = DB::table('mobile_details')->where('user_id', $id)->update(['phone_status' =>$phonestatus]);
            $checkstatus = DB::table('mobile_details')->where('user_id', $id)->update(['app_status' => $phonestatus]);
            if ($checkstatus) {
                echo $message = 'Phone Status Updated';
            } else {
                echo $message = 'Something Went Wrong';
            }
        }
    }

    public function send_remove_notification($serial_no)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://teknikoglobal.live/removedevice?serialNumber=' . $serial_no,
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
        return $response;
    }


    public function send_notification($title, $body, $deviceToken, $type, $seller_name = '', $seller_contact = '', $amount = '', $user_id = '', $user_number = '', $coupon_code = '')
    {

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

        return $this->fcmNotification($deviceToken, $sendData);
    }







    public function fcmNotification($device_id, $sendData)

    {

        // echo $device_id;

        // DB::table('new')->insert(['data'=>json_encode($sendData)]);


        if (!defined('API_ACCESS_KEY')) {

            define('API_ACCESS_KEY', 'AAAA8o3PkfY:APA91bFQqBjudr62Agn_I1PTY_e33BmC6Yr4_HiZMtavQAgn7tiIP6Q1hZJPmwan0fH11q7YaXQFYa8Zvsdamg-qENgQZb-D_GW0MnlbOqrM8hwShybzezI44Hb7kkC_bG9XOHBgwyAv');
        }



        $fields = array(

            'to'        => $device_id,

            'data'  => $sendData,

            'priority' => 'high',

            // 'notification'  => $sendData

        );





        $headers = array(

            'Authorization: key=' . API_ACCESS_KEY,

            'Content-Type: application/json'

        );

        #Send Reponse To FireBase Server

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);



        if ($result === false)

            die('Curl failed ' . curl_error($ch));



        curl_close($ch);

        return $result;
    }
}
