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
use App\Subject;
use App\Category;

use App\Content;
use App\SubscriptionHistory;

use App\Subscription;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class SubscriptionController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request){
        $search = isset($request->search) ? $request->search :'';
        $subscription = Subscription::where('is_delete',0);
        

        $subscription = $subscription->paginate(10);

        $data['subscription'] = $subscription;
        return view('admin.subscriptions.index',$data);
    }

    public function add(Request $request)
    {
      $data = [];

      $id = (isset($request->id))?$request->id:0;

      $subscription = '';
      if(is_numeric($id) && $id > 0){
        $subscription = Subscription::find($id);
        if(empty($subscription)){
            return redirect($this->ADMIN_ROUTE_NAME.'/subscriptions');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/subscriptions';
        }

        $rules = [];
        $rules['title'] = 'required';
        $rules['duration'] = 'required';
        $rules['price'] = 'required';
        $rules['status'] = 'required';
        
        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Subscription has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Subscription has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Subscription';

    if(isset($subscription->title)){
        $subscription_name = $subscription->title;
        $page_heading = 'Update Subscription - '.$subscription_name;
    }  

    $data['page_Heading'] = $page_heading;
    $data['id'] = $id;
    $data['subscription'] = $subscription;

    return view('admin.subscriptions.form', $data);


}



public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    $oldImg = '';

    $subscription = new Subscription;

    if(is_numeric($id) && $id > 0){
        $exist = Subscription::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $subscription = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $subscription->$key = $val;
    }

    $isSaved = $subscription->save();

    if($isSaved){
        $this->saveImage($request, $subscription, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $subscription, $oldImg=''){

    $file = $request->file('image');
    

    if ($file) {
        $path = 'subscription/';
        $thumb_path = 'subscription/thumb/';
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
            $subscription->image = $image;
            $subscription->save();         
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
        $is_delete = Subscription::where('id', $id)->delete();
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Subscription has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}

public function change_subscription_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $Subscription = Subscription::where('id',$id)->first();
  if(!empty($Subscription)){

   Subscription::where('id',$id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}
}



}




