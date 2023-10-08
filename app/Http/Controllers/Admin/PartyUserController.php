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
use App\ReturnCoupon;
use App\MobileDetails;
use App\CouponTransaction;
use App\SubCategory;
use App\AssignCoupon;
use App\Coupon;
use Mail;

use App\SubscriptionHistory;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use Rap2hpoutre\FastExcel\FastExcel;
use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class PartyUserController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        

        // prd($request->toArray());
                $role_slug = $request->route()->getPrefix();

                $slug = str_replace('admin/', '', $role_slug) ;

                $roles = Roles::where('slug',$slug)->first();

                $search = isset($request->search) ? $request->search :'';
                $role_id = isset($request->role_id) ? $request->role_id :'';
                $find_id = isset($request->find_id) ? $request->find_id :'';
                $parent_id_value = isset($request->parent_id) ? $request->parent_id :'';
                $user_role_id =Auth::guard('admin')->user()->role_id??'';
                $user_id_login =Auth::guard('admin')->user()->id??'';

                $parent_ids = [];

                $fetch_role_id = $request->fetch_role_id ??0;
                $user_id = $request->user_id ??0;
                $key = $request->key ??0;
                $ids = [];
                $superdist_ids = [];
                // echo $role_id;

                if(!empty($parent_id_value)){
                    $parent_ids[] = $parent_id_value;
                }


                if(!empty($fetch_role_id) && !empty($user_id) && !empty($key) && $user_role_id !=0){
                    $ids = CustomHelper::getChildIds($fetch_role_id,$user_id,$key);
                }

                if(!empty($search)){
                    // $fetch_role_id = $roles->id;
                    // $user_id = $user_id_login;
                    // $key = 1;
                    // $ids = CustomHelper::getChildIds($fetch_role_id,$user_id,$key);

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

                // echo $role_id;
                \DB::enableQueryLog();
                $users = Admin::where('is_delete','0')->where('role_id',$roles->id)->orderBy('id','desc');

             if(!empty($parent_ids)){
                 $users->whereIn('parent_id', $parent_ids);
             }



             
             if(!empty($search)){
                if(!empty($request->parent_id) || $request->parent_id !=''){
                    $users->where('parent_id', $request->parent_id);
                }
                
                if($slug == 'tsm'){
                    $users->where('name', 'like', '%' . $search . '%');
                    $users->orWhere('email', 'like', '%' . $search . '%');
                    
                }else{
                    $users->where('business_name', 'like', '%' . $search . '%');
                    $users->orWhere('email', 'like', '%' . $search . '%');
                }
            }
        // prd($state_ids);
        // if(!empty($state_ids)){
        //      $users->whereIn('state_id', $state_ids);

        // }
            if(!empty($find_id)){
                $users->where('id', $request->find_id);

            }
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



     public function export(Request $request){
        $search = isset($request->search) ? $request->search:'';
        $users = User::select('id','name','email','phone','wallet','referral_code');
        if(!empty($search)){
            $users->where('name', 'like', '%' . $search . '%');
            $users->orWhere('email', 'like', '%' . $search . '%');
            $users->orWhere('phone', 'like', '%' . $search . '%');

        }
        $users = $users->get();
        if(!empty($users) && $users->count() > 0){
            foreach($users as $user){
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
         $fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';
         return Excel::download(new UserExport($exportArr, $filedNames), $fileName);
     }

 }







 public function add(Request $request)
 {
     $details = [];
     $role_slug = $request->route()->getPrefix();
     $slug = str_replace('admin/', '', $role_slug) ;
        // prd($slug);
     $roles = Roles::where('slug',$slug)->first();

     $id = isset($request->id) ? $request->id : 0;
     $users = '';
     if(is_numeric($id) && $id > 0)
     {
        $users = Admin::find($id);
        if(empty($users)){
            return redirect($this->ADMIN_ROUTE_NAME.'/'.$slug);
        }
    }


    if($request->method() == "POST" || $request->method() == "post"){
           // prd($request->toArray());
        if(empty($back_url)){
         $back_url = $this->ADMIN_ROUTE_NAME.'/'.$slug;
     }

     $details = [];

     

      if(is_numeric($id) && $id > 0)
     {
        $details['name'] = 'required';
     $details['username'] = 'required';
     $details['role_id'] = 'required';
     // $details['password'] = 'required';
     $details['phone'] = 'required';
     $details['address'] = 'required';
     $details['state_id'] = 'required';
     $details['city_id'] = 'required';
     }else{
        $details['name'] = 'required';
     $details['username'] = 'required|unique:admins,username';
     $details['role_id'] = 'required';
     // $details['password'] = 'required';
     $details['phone'] = 'required';
     $details['address'] = 'required';
     $details['state_id'] = 'required';
     $details['city_id'] = 'required';
     }
       // $details['business_name'] = 'required';
     $this->validate($request , $details); 

          // prd($dd);

     $createdDetails = $this->save($request , $id);

     if($createdDetails)
     {
        $alert_msg = $roles->name." Created Successfully";

        if(is_numeric($id) & $id > 0)
        {
            $alert_msg = $roles->name." Updated Successfully";
        } 
        return back()->with('alert-success', $alert_msg);

        // return redirect(url($back_url))->with('alert-success',$alert_msg);
    }else{

        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }

}

$page_Heading = "Add ".$roles->name;
if(isset($users->id))
{
    $name = $users->name;
    $page_Heading = 'Update '.$roles->name.' -'.$name;
}

$parents = [];
$parent_role = [];
$parent_role = Roles::where('id',$roles->parent_id)->first();
$child_role = Roles::where('parent_id',$roles->id)->first();

$parents = Admin::where('role_id',$roles->parent_id)->get();

$details['page_Heading'] = $page_Heading;
$details['id'] = $id;
$details['users'] = $users;
$details['roles'] = $roles;
$details['child_role'] = $child_role;
$details['parent_role'] = $parent_role;

$details['parents'] = $parents;
$details['parent_id'] = $request->parent_id??'';


// prd($_GET);
return view('admin.party_users.form',$details);

}


public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url','password','image']);

    if(!empty($request->password)){
        $details['password_value'] = $request->password;
        $details['password'] = bcrypt($request->password);
    }
    if(empty($request->parent_id)){
        $details['parent_id'] = 1;
    }
    if($id == 0){
        $details['unique_id'] = uniqid();
        $details['added_by'] = Auth::guard('admin')->user()->id;
    }

    $old_img = '';

    $user = new Admin;

    if(is_numeric($id) && $id > 0)
    {
        // $unique_id = CustomHelper::GetSlug('admins', 'id', $id, $request->business_name);
        $exist = Admin::find($id);

        if(isset($exist->id) && $exist->id == $id)
        {   
            $user = $exist;

            $old_img = $exist->image;

        }

    }

    foreach($details as $key => $val)
    {
        $user->$key = $val;
    }

    $isSaved = $user->save();

    if($isSaved)
    {
        $this->update_unique_id($user);
        
        if($id == 0){
            $this->send_email_to_party_user($user);
        }
        $this->saveImage($request , $user , $old_img);
    }

    return $isSaved;
}

public function update_unique_id($user){
   $zero = '';
   $prefix = '';
   if($user->id <=9){
    $zero = '0';
   }
   if($user->role_id == 2){
    $prefix = 'MELSDST';
   }if($user->role_id == 3){
    $prefix = 'MELDST';
   }if($user->role_id == 4){
    $prefix = 'MELSP';
   }if($user->role_id == 5){
    $prefix = 'MELSLR';
   }

 $unique_id = $prefix.$zero.$user->id;
 Admin::where('id',$user->id)->update(['unique_id'=>$unique_id]);
}


public function sendEmail($viewPath, $viewData, $to, $from, $replyTo, $subject, $params=array()){

    try{

        Mail::send(
            $viewPath,
            $viewData,
            function($message) use ($to, $from, $replyTo, $subject, $params) {
                $attachment = (isset($params['attachment']))?$params['attachment']:'';

                if(!empty($replyTo)){
                    $message->replyTo($replyTo);
                }

                if(!empty($from)){
                    $message->from($from,'Reptile India');
                }

                if(!empty($attachment)){
                    $message->attach($attachment);
                }

                $message->to($to);
                $message->subject($subject);

            }
        );
    }
    catch(\Exception $e){
        // print_r($e->getMessage());
            // Never reached
    }
            // print_r(Mail::failures());
    if( count(Mail::failures()) > 0 ) {
        return false;
    }       
    else {
        return true;
    }

}



public function send_email_to_party_user($user){
    // $user = Admin::where('id',1)->first();
    $to_email = $user->email??'';
    // $to_email = 'satyatekniko@gmail.com';
    $business_name = $user->name??'';
    // $business_name = $user->business_name??'';
    // if(empty($business_name)){
    //     $business_name = $user->name??'';
    // }

    $subject = 'Welcome - '.$business_name;

    $content = array('business_name'=>$business_name,'username'=>$user->username,'password'=>$user->password_value);
    $from_email = 'info.reptileindia@gmail.com';
    $success = $this->sendEmail('emails.registeremail', $content, $to=$to_email, $from_email, $replyTo = $from_email, $subject);
    return $success;

}

private function saveImage($request, $user, $oldImg=''){

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

           // prd($image);
            $user->image = $image;
            $user->save();         
        }

        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}

public function change_user_role(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $role_id = isset($request->role_id) ? $request->role_id :'';

  $users = User::where('id',$id)->first();
  if(!empty($users)){

   User::where('id',$id)->update(['role_id'=>$role_id]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}

public function delete(Request $request)
{
 $id = isset($request->id) ? $request->id : 0;
 $is_delete = 0;

 if(is_numeric($id) && $id > 0)
 {       
    $is_delete = User::where('id', $id)->update(['is_delete'=> '1']);
}

if(!empty($is_delete))
{
    return back()->with('alert-success', 'User Deleted Successfully');
}else{

    return back()->with('alert-danger', 'something went wrong, please try again...');
}    
}



public function view_coupons(Request $request){
    $data = [];

    // prd($request->toArray());
    $user_id = $request->id??'';
    $date = $request->date??'';
    $type = $request->type??'';

    $role_slug = $request->route()->getPrefix();

    $user = Admin::where('id',$user_id)->first();
    $data['user'] = $user;
    $slug = str_replace('admin/', '', $role_slug) ;
    $data['slug'] = $slug;

    if($type == 'debit'){
        $coupon_ids = ReturnCoupon::where("parent_id",$user_id);
        if(!empty($date)){
            $coupon_ids->whereDate('date',$date);
        }
        $coupon_ids = $coupon_ids->pluck('coupon_id')->toArray();
        $coupons = Coupon::whereIn('id',$coupon_ids)->paginate(10);

    }else{
        $parent_total_coupons = AssignCoupon::where('is_return',0)->where('child_id',$user_id)->pluck('coupon_id')->toArray();
        $distribute_coupons = AssignCoupon::where('is_return',0)->where('parent_id',$user_id)->pluck('coupon_id')->toArray();

        if(!empty($date)){
            $parent_total_coupons = AssignCoupon::where('is_return',0)->where('child_id',$user_id)->whereDate('date',$date)->pluck('coupon_id')->toArray();
            $distribute_coupons = AssignCoupon::where('is_return',0)->where('parent_id',$user_id)->whereDate('date',$date)->pluck('coupon_id')->toArray();
        }


        // $eccc = array_diff($parent_total_coupons, $distribute_coupons);
    // prd($eccc);
    // prd($distribute_coupons);


        $coupons = [];
        $couponArr = [];
        if(!empty($parent_total_coupons)){
            foreach ($parent_total_coupons as $key => $value){
              if(!in_array($value, $distribute_coupons)){
               $couponArr[$key]=$value;
           }

       }
       $coupons = Coupon::whereIn('id',$couponArr)->paginate(10);
   } 
}


$data['coupons'] = $coupons;
$data['user_id'] = $user_id;


return view('admin.party_users.view_coupons',$data);
}

public function coupons_history(Request $request){
    $data = [];
    $role_slug = $request->route()->getPrefix();
    
    $slug = str_replace('admin/', '', $role_slug) ;
    $data['slug'] = $slug;
    $user_id = $request->id??'';
    $user = Admin::where('id',$user_id)->first();
    $data['user'] = $user;
    $transactions = DB::table('coupon_transaction')->where('parent_id',$user_id)->orderBy('date','desc')->paginate(10);

    $data['transactions'] = $transactions;
    $data['user_id'] = $user_id;


    return view('admin.party_users.coupons_history',$data);
    
}



public function change_parent(Request $request){
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['party_user_id'] = 'required';
        $rules['parent_id'] = 'required';
        $this->validate($request,$rules);

        Admin::where('id',$request->party_user_id)->update(['parent_id'=>$request->parent_id]);
        return back()->with('alert-success', 'Parent Change Successfully');
    }
}


public function get_childs(Request $request){
    $user_id = $request->user_id??'';
    $html ='<option value="" selected disabled>Select User Type</option>';
    $admins = Admin::where('parent_id',$user_id)->get();
    if(!empty($admins)){
        foreach ($admins as $value) {

            $business_name = $value->business_name??'';
            if(empty($business_name)){
                $business_name = $value->name??'';
            }
            $html.='<option value='.$value->id.'>'.$business_name.'---'.$value->unique_id.'</option>';
        }
    }

    echo $html;
}








public function return_coupon(Request $request){
    
  $method = $request->method();
  if($method == 'POST' || $method == 'post'){
    $rules = [];
    $rules['couponids'] = 'required';
    $rules['party_user_id'] = 'required';
    $this->validate($request,$rules);
    
    $user_id = $request->party_user_id;
    $couponids = $request->couponids ??'';
    $child_id = 0;
    if(!empty($couponids)){
      foreach ($couponids as $key => $value) {
        $exist = AssignCoupon::where('coupon_id',$value)->where('child_id',$user_id)->first();

        $exist->is_return = 1;
        $exist->save();
        // if(!empty($exist)){
        $getparent = CustomHelper::getUserDetails($user_id);



        $exist_parent = AssignCoupon::where('coupon_id',$value)->where('child_id',$exist->parent_id)->first();

        
        $dbArray = [];
        $parent = CustomHelper::getUserDetails($exist_parent->parent_id??1);
        $child = CustomHelper::getUserDetails($exist->parent_id);
        $child_id = $getparent->parent_id;
        $dbArray['parent_id']= $exist_parent->parent_id??1;
        $dbArray['child_id']= $getparent->parent_id;
        $dbArray['coupon_id']= $value;
        $dbArray['parent_role_id']= $parent->role_id;
        $dbArray['child_role_id']= $child->role_id;
        $dbArray['date'] = date('Y-m-d');
        $dbArray['time'] = date('H:i');
        AssignCoupon::insert($dbArray);

        $returnCouponArr = [];
        $returnCouponArr['parent_id'] = $user_id;
        $returnCouponArr['child_id'] = $getparent->parent_id;
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
    $couponArr1['type'] = 'debit';
    $couponArr1['date'] = date('Y-m-d');
    $couponArr1['time'] = date('H:i');
    CouponTransaction::insert($couponArr1);

    Admin::where('id',$child->id)->update(['no_of_coupons'=>$child->no_of_coupons+$count]);

    $couponArr = [];
    $couponArr['parent_id'] = $child_id;
    $couponArr['no_of_coupons'] = $count;
    $couponArr['type'] = 'credit';
    $couponArr['date'] = date('Y-m-d');
    $couponArr['time'] = date('H:i');
    CouponTransaction::insert($couponArr);
}
}

return back()->with('alert-success', 'Coupon Return Successfully');

}

public function getparent_coupons(Request $request){
    $parent_coupons = CustomHelper::getParentCoupons($request->parent_id);
    $html = '';
    if(!empty($parent_coupons)){
        $i = 0;
        foreach($parent_coupons as $coup){
            $html.='<option value='.$coup->id.'>'.++$i.'. ' .$coup->couponID.'</option>';
        }
    }
    echo $html;
}

public function return_coupons(Request $request){
    $couponArr = CustomHelper::returnCouponBalanace($request->user_id);
    $html = '';
    if(!empty($couponArr)){
        $i = 1;
         Coupon::latest('id')->whereIn('id',$couponArr)->where('is_view',0)->chunk(50, function($coupons) use (&$html,&$i) {
            
            foreach ($coupons as $coupon) {
                $html.='<option value='.$coupon->id.'>'.$i.'. ' .$coupon->couponID.'</option>';
                $i++;
                }
            });
    }
    echo $html;
}




}




