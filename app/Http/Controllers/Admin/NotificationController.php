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

use App\Admin;
use App\Course;
use App\User;

use App\Category;
use App\City;
use App\SubCategory;
use App\UserLogin;
use App\Notification;
use App\SubscriptionHistory;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class NotificationController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request){
      $data = [];
      $method = $request->method();
      if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['text'] = 'required';
        $rules['title'] = 'required';
        $this->validate($request,$rules);
        $image = '';
        $file = $request->file('image');
        if(!empty($file)){
            $image = $this->saveImage($request);
        }
        //Db::table('notifications')->insert($details);
        $success = CustomHelper::sendNotificationtoSeller($request,$image);
        return back()->with('alert-success', ' Notification Sent Successfully'); 

    }
    
    return view('admin.notification.index',$data);
}




public function send_users(Request $request){
   $data = [];
   $method = $request->method();
   if($method == 'post' || $method == 'POST'){
    $rules = [];
   /// $rules['user_id'] = 'required';
    $rules['text1'] = 'required';
    $rules['title1'] = 'required';
    $this->validate($request,$rules);
    $image = '';
    $file = $request->file('image1');
    if(!empty($file)){
        $image = $this->saveImage1($request);
    }
    // if($request->user_id == "0" || $request->user_id == 0){
        $success = CustomHelper::sendNotificationtoSeller($request,$image);
        return back()->with('alert-success', 'All Notification Sent Successfully'); 
    // }
    // else{
    //     $success = CustomHelper::sendSingleUsers($request,$image);
    //     return back()->with('alert-success', 'Notification Sent Successfully'); 
    // }
}

}

private function saveImage1($request){

    $file = $request->file('image1');

    //prd($file);
    if ($file) {
        $path = 'notification/';
        $thumb_path = 'notification/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;
        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
        if($uploaded_data['success']){
            $image = $uploaded_data['file_name'];
            return $image;

        }

    }

}





private function saveImage($request){

    $file = $request->file('image');

    prd($file);
    if ($file) {
        $path = 'notification/';
        $thumb_path = 'notification/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;
        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
        if($uploaded_data['success']){
            $image = $uploaded_data['file_name'];
            return $image;

        }

    }

}











}




