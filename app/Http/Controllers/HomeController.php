<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Vendor;
use App\Users;
use App\CouponCategory;
use Auth;
use DB;
use Validator;
use Storage;
use App\Cart;
use App\State;
use App\Coupon;
use App\Roles;
use App\Category;
use App\Post;

use App\Order;

use Session;
use Hash;
use App;




class HomeController extends Controller
{

 public function __construct(){


 }

 public function index(Request $request){
  $data = [];

  return view('web.home',$data);

}

 // public static function getChildRoles(){
 //       prd(auth()->user());

 //       $role_id = Auth::guard('admin')->user()->role_id;
 //       $role = Roles::where('parent_id',$parent_id)->get();
 //       return $role;

 //   }




public function update_city(Request $request){
   $state_id = Session::get('state_id');
  $city_id = Session::get('city_id');
    if(!empty(Auth::guard('appusers')->user())){
    $user_id = Auth::guard('appusers')->user()->id;
    $city_id = Auth::guard('appusers')->user()->city;
    $state_id = Auth::guard('appusers')->user()->state;


      $new_city = $request->city_id;
      $new_state = $request->state_id;
      Users::where('id',$user_id)->update(array('state'=>$new_state,'city'=>$new_city));

      return json_encode(array('status'=>true));

  }
  else{
     $new_city = $request->city_id;
      $new_state = $request->state_id;

    Session::put('city_id', $new_city);

    Session::put('state_id', $new_state);



      return json_encode(array('status'=>true));

  }



}











public function subscription(){
  $data = [];

  $my_subscription = [];
   $state_id = Session::get('state_id');
  $city_id = Session::get('city_id');
  if(!empty(Auth::guard('appusers')->user())){
    $user_id = Auth::guard('appusers')->user()->id;
    $city_id = Auth::guard('appusers')->user()->city;
    $state_id = Auth::guard('appusers')->user()->state;


    $my_subscription = DB::table('user_subscription')->where('user_id',$user_id)->get();
    if(!empty($my_subscription)){

      foreach($my_subscription as $my){
        $sub_ids[] = $my->subscription_id;
      }
    }

  }
  if(!empty($sub_ids)){
    $subscription = DB::table('user_type_subscription')->whereNotIn('id',$sub_ids)->where('status',1)->get();
  }else{
    $subscription = DB::table('user_type_subscription')->where('status',1)->get();

  }
  $data['subscriptions'] = $subscription;
  $data['my_subscription'] = $my_subscription;

  return view('front.home.subscription',$data);

}




public function profile(){
  $data = [];


  if(!empty(Auth::guard('appusers')->user())){
    $user_id = Auth::guard('appusers')->user()->id;
    $city_id = Auth::guard('appusers')->user()->city;
    $state_id = Auth::guard('appusers')->user()->state;

  }

  return view('front.home.profile',$data);

}




public function get_sub_categories(Request $request){
  $category_id = isset($request->category_id) ? $request->category_id :'';
  $data = [];
  if(!empty($category_id)){

    $sub_category = DB::table('subcategories')->where('cat_id',$category_id)->get();
    $data['categories'] = Category::where('id',$category_id)->first();
    $data['subcategories'] = $sub_category;

    return view('front.home.sub_category',$data);

  }else{
    return redirect('/');
  }
}

public function get_all_categories(Request $request){



  $data['categories'] = Category::get();

  return view('front.home.category',$data);


}


public function wallet_reedem(Request $request){
  $method = $request->method();
  
  if(!empty(Auth::guard('appusers')->user())){
    $user_id = Auth::guard('appusers')->user()->id;
    $dbArray = [];
    $dbArray['user_id'] = $user_id;
    $dbArray['amount'] = $request->amount;
    $dbArray['status'] = 1;
      $trans = DB::table('request_wallet_amount')->where('user_id',$user_id)->where('status',1)->first();
    if(empty($trans)){
      $success = DB::table('request_wallet_amount')->insert($dbArray);
    if($success){

      // $new_wallet = Auth::guard('appusers')->user()->wallet - $request->amount;
      // Users::where('id',$user_id)->update(array('wallet'=>$new_wallet));


      return back()->with('alert-success', 'Your Request Placed Successfully');

    }else{
      return back()->with('alert-danger', 'something Went Wrong');

    }
  }else{
      return back()->with('alert-danger', 'Your Request Already in processing');

  }




    

  }

}

public function profile_update(Request $request){
  $method = $request->method();
  if(!empty(Auth::guard('appusers')->user())){
    $user_id = Auth::guard('appusers')->user()->id;

  }
  if($method == 'POST' || $method == 'post'){
    $rules = [];
    $rules['name'] = 'required';
    $rules['email'] = 'required';
    $rules['mobile'] = 'required';
    $rules['address'] = 'required';

    $this->validate($request,$rules);

    $dbArray = [];
    $dbArray['name'] = $request->name;
    $dbArray['address'] = $request->name;
    $success = Users::where('id',$user_id)->update($dbArray);
    if($success){
      return back()->with('alert-success', 'Profile Update Successfully');

    }else{
      return back()->with('alert-danger', 'something Went Wrong');

    }
  }
}


public function vendor_list(Request $request){
  $sub_cat_id = isset($request->sub_cat_id) ? $request->sub_cat_id :'';
  $data = [];
  $ven_ids = [];
  $vendors = [];
  if(!empty($sub_cat_id)){

    $vendor_subscription = DB::table('vendors_subscription')->where('sub_cat_id',$sub_cat_id)->get();
    if(!empty($vendor_subscription)){
      foreach($vendor_subscription as $ven){
        $ven_ids[] = $ven->vendor_id;
      }
    }

    if(!empty($ven_ids)){
      $vendors = Vendor::whereIn('id',$ven_ids)->get();

    }
    $data['vendors'] = $vendors;

    return view('front.home.vendors_list',$data);

  }else{
    return redirect('/');
  }
}

public function add_wallet(Request $request){

  $user_id = Auth::guard('appusers')->user()->id;
  $method = $request->method();
  if($method == 'post' || $method == 'POST'){
    $dbArray = [];
    $user = Users::where('id',$user_id)->first();
    $wallet = $user->wallet;
    $new_wallet = $user->wallet + $request->amount;
    Users::where('id',$user_id)->update(array('wallet'=>$new_wallet));

    $dbArray['user_id'] = $user_id;
    $dbArray['amount'] = $request->amount;
    $dbArray['amount_type'] = 'ADD';
    $dbArray['amount_type_from'] = 'add_wallet';
    $dbArray['description'] ='online Add wallet';

    $success = DB::table('wallet_transaction')->insert($dbArray);
    if($success){
      return back()->with('alert-success', 'Add To Wallet Successfully');

    }else{
      return back()->with('alert-danger', 'something Went Wrong');

    }

  }

}




public function buy_subscription(Request $request){
  $user_id = Auth::guard('appusers')->user()->id;
  $method = $request->method();
  if($method == 'post' || $method == 'POST'){
    $dbArray = [];
    $subscription_id = $request->subscription_id;
    $txn_no = $request->txn_no;
    $today = date('Y-m-d');

    $subscription = DB::table('user_type_subscription')->where('id',$subscription_id)->first();

    $dbArray['subscription_id'] = $subscription_id;
    $dbArray['txn_no'] = $txn_no;
    $dbArray['user_id'] = $user_id;
    $dbArray['start_date'] = date('Y-m-d');
    $dbArray['end_date'] = date('Y-m-d', strtotime($today. ' + '.$subscription->duration.' days'));

    $dbArray['duration'] =  $subscription->duration;
    $dbArray['status'] = 1;
    $dbArray['pament_mode'] = 'online';
    $insert = DB::table('user_subscription')->insert($dbArray);
    if($insert){
      return back()->with('alert-success', 'Subscribed Successfully');

    }else{
      return back()->with('alert-danger', 'something Went Wrong');

    }

  }
}










public function vendor_details(Request $request){

  $data = [];
  $vendor_id = isset($request->vendor_id) ? $request->vendor_id :'';
  if(!empty($vendor_id)){

    $vendor = Vendor::where('id',$vendor_id)->first();

    $vendor_posts = Post::where('merchant_id',$vendor_id)->get();
    $vendor_coupons = Coupon::where('merchant_id',$vendor_id)->get();

    $data['vendor'] = $vendor;
    $data['vendor_posts'] = $vendor_posts;
    $data['vendor_coupons'] = $vendor_coupons;
  }




  return view('front.home.vendor_details',$data);
}











public function search(Request $request){
  $data = [];

  $keyword = isset($request->keyword) ? $request->keyword :'';
   $state_id = Session::get('state_id');
  $city_id = Session::get('city_id');

  if(!empty(Auth::guard('appusers')->user())){
    $user_id = Auth::guard('appusers')->user()->id;
    $city_id = Auth::guard('appusers')->user()->city;
    $state_id = Auth::guard('appusers')->user()->state;
  }

  $posts = Post::orderby('created_at','desc');
  if(!empty($state_id)){
    $posts->where('state_id',$state_id);
  }

  if(!empty($city_id)){
    $posts->where('city_id',$city_id);
  }
  if(!empty($keyword)){
    $posts->where('name', 'like', '%' . $keyword . '%');
  }

  $posts = $posts->paginate(12);

  $coupons = Coupon::orderby('id','desc');

  if(!empty($state_id)){
    $coupons->where('state_id',$state_id);
  }

  if(!empty($city_id)){
    $coupons->where('city_id',$city_id);
  }
  if(!empty($keyword)){
    $coupons->where('name', 'like', '%' . $keyword . '%');
    $coupons->orWhere('description', 'like', '%' . $keyword . '%');
  }
  $coupons = $coupons->paginate(12);



  $data['coupons'] = $coupons;









  $data['posts'] = $posts;


  return view('front.home.search_ads',$data);




}





public function ad_details(Request $request){
  $data = [];
  $data['post'] =[];
  $post_id = isset($request->id) ? $request->id :'';
  if(!empty($post_id)){
    $post = Post::where('id',$post_id)->first();
    if(!empty($post)){
      if(!empty($post->merchant_id)){
        $data['merchant'] = Vendor::where('id',$post->merchant_id)->first();
      }else{
        $data['merchant']=[];
      }



      $data['post']= $post;
    }
  }




  return view('front.home.ad_details',$data);
}


public function login(Request $request){

  $data = [];
  $method = $request->method();
  if($method =='post' || $method == 'POST'){
    $rules = [];
    $rules['username'] = 'required';
    $rules['password'] = 'required';
    $this->validate($request,$rules);

    $password = md5($request->password);
    $exist = Users::where('mobile',$request->username)->orWhere('email',$request->username)->where('password',$password)->first();
    if(!empty($exist)){
      Auth::guard('appusers')->loginUsingId($exist->id);
      return redirect()->route('home')->with('alert-success', 'Login successfully');
    }else{
      return back()->with('alert-danger', 'Email or password wrong');
    }
  }
  if(!empty(Auth::guard('appusers')->user())){
    return redirect()->route('home');
  }
  else{
    return view('front.login',$data);
  }
}


public function register(Request $request){

  $data = [];

  $data['states'] = State::get();
  $dbArray= [];


  $method = $request->method();
  if($method =='post' || $method == 'POST'){
    $rules = [];
    $rules['name'] = 'required';
    $rules['email'] = 'required';
    $rules['address'] = 'required';
    $rules['phone'] = 'required';
    $rules['state'] = 'required';
    $rules['city'] = 'required';
    $rules['password'] = 'required';

    $this->validate($request,$rules);
    $setting = DB::table('setting')->where('id',1)->first();
    $password = md5($request->password);
    $referalcode = isset($request->referalcode) ? $request->referalcode :'';
    $dbArray['refer_id'] = 0;
    if(!empty($referalcode)){
      $exist = Users::where('refer_code',$referalcode)->first();
      if(!empty($exist)){
        if(!empty($setting)){
          $wallet = $setting->refer + $exist->wallet;
          Users::where('refer_code',$referalcode)->update(array('wallet'=>$wallet));
        }


        $dbArray['refer_id'] = isset($exist->id) ? $exist->id :'';
      }else{
        return back()->with('alert-danger', 'Referal Code Not Exist');
      }
    }




    $dbArray['name'] = $request->name;
    $dbArray['email'] = $request->email;
    $dbArray['mobile'] = $request->phone;
    $dbArray['latitude'] = $request->latitude;
    $dbArray['longitude'] = $request->longitude;
    $dbArray['address'] = $request->address;
    $dbArray['city'] = $request->city;
    $dbArray['state'] = $request->state;
    $dbArray['password'] = $password;



    $referal_code = $this->generateRandomString(8);
    $exist_refer = Users::where('refer_code',$referalcode)->first();
    if(empty($exist_refer)){
      $dbArray['refer_code'] = $referal_code;
    }else{
     $dbArray['refer_code'] = $this->generateRandomString(8);
   }




   $user_id = Users::create($dbArray)->id;
    // $exist = Users::where('mobile',$request->phone)->where('password',$password)->first();
   if(!empty($user_id)){
    Auth::guard('appusers')->loginUsingId($user_id);
    return redirect()->route('home')->with('alert-success', 'Login successfully');
  }else{
    return back()->with('alert-danger', 'Email or password wrong');
  }
}
if(!empty(Auth::guard('appusers')->user())){
  return redirect()->route('home');
}
else{
  return view('front.register',$data);
}
}

public function wallethistory(Request $request){

  $data =[];
  $user_id = Auth::guard('appusers')->user()->id;
  $wallet_history = DB::table('wallet_transaction')->where('user_id',$user_id)->paginate(10);
  if(!empty($wallet_history)){
    $data['wallets'] = $wallet_history;
  }


  $user = Users::where('id',$user_id)->first();
  $data['user'] = $user;

  return view('front.home.wallet_history',$data);

  
}







public function logout(Request $request){


  $request->session()->invalidate();

  return redirect()->route('home');

}


public  function generateRandomString($length = 20) {
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}


public function change_password(Request $request){
  $data = [];
  $slug = isset($request->slug) ? $request->slug : '';
  $method = $request->method();
  $user_id =  isset(Auth::guard('appusers')->user()->id) ? Auth::guard('appusers')->user()->id :'';

  if(!empty($slug)){
    $vendor = Vendor::where('slug',$slug)->first();
    $data['vendor'] = $vendor;
    if($method == 'post' || $method == 'POST'){
      $rules['old_password'] = 'required|min:6|max:20';
      $rules['password'] = 'required|min:6|max:20';
      $rules['confirm_password'] = 'required|min:6|max:20|same:password';
      $this->validate($request,$rules);

      $old_password = isset($request->old_password) ? $request->old_password :'';
      $password = isset($request->password) ? $request->password :'';
      $confirm_password = isset($request->confirm_password) ? $request->confirm_password :'';

      $user = Users::where(['id'=>$user_id])->first();
      $existing_password = (isset($user->password))?$user->password:'';
      $hash_chack = Hash::check($old_password, $existing_password);
      if($hash_chack){
        $update_data['password']=bcrypt(trim($password));
        $is_updated = DB::table('web_users')->where('id', $user_id)->update($update_data);
        if($is_updated){

          return back()->with('alert-success', 'Password Changed successfully.');
        }
        else{
         return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
       }
     }else{
      return back()->with('alert-danger', 'old Password Not Matched.');
    }

  }
  return view('front.home.change_password',$data);
}else{
  abort(404);
}

}


public function about_us(Request $request){
  $data = [];

   $setting = DB::table('settings')->where('id',1)->first();
  $data['settings'] = $setting; 

  return view('web.about',$data);

}

public function terms(Request $request){
  $data = [];

  $setting = DB::table('settings')->where('id',1)->first();
  $data['settings'] = $setting; 

  return view('web.terms',$data);

}


public function privacy_policy(Request $request){
  $data = []; 
  $setting = DB::table('settings')->where('id',1)->first();
  $data['settings'] = $setting; 

  return view('web.privacy',$data);

}












public function news_letter(Request $request){
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
      $email = $request->email;
      DB::table('news_letter')->insert(array('email'=>$email));
          return back()->with('alert-success', 'Subscribed Successfully.');

    }
}


public function download_db()
{
   $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";  
    $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
    $returnVar = NULL;
    $output  = NULL;
    exec($command, $output, $returnVar);
}


}
