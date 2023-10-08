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
use Illuminate\Support\Facades\File;

use App\Category;
use App\Business;
use App\City;
use App\State;
use App\SubCategory;
use App\BusinessCategory;

use Yajra\DataTables\DataTables;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BusinessExport;
use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class BusinessController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        // \DB::enableQueryLog(); // Enable query log

      $businesses = Business::where('is_delete','0')->latest();
      $search= $_GET['search']??'';
      $start_date = $_GET['start_date']??'';
      $end_date = $_GET['end_date']??'';
      $is_export = $_GET['is_export']??0;



      if(!empty($search)){
        $businesses->where('business_name','like', '%' . $search . '%');
        $businesses->orWhere('owner_name','like', '%' . $search . '%');
        $businesses->orWhere('address','like', '%' . $search . '%');
        $businesses->orWhere('contact_name','like', '%' . $search . '%');
        $businesses->orWhere('register_mobile','like', '%' . $search . '%');
        $businesses->orWhere('contact_person_no','like', '%' . $search . '%');
        $businesses->orWhere('referred_by','like', '%' . $search . '%');
    }
    if(!empty($start_date) && !empty($end_date)){
        $businesses->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date);
    }
    // if(!empty($start_date)){
    //     $businesses->whereDate('created_at', $start_date);
    // }
    if($is_export == 1){
     $exportArr = [];
     $business_list = Business::where('is_delete',0);
     if(!empty($start_date) && !empty($end_date)){
        $business_list->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date);
    }
    if(!empty($search)){
        $business_list->where('business_name','like', '%' . $search . '%');
        $business_list->orWhere('owner_name','like', '%' . $search . '%');
        $business_list->orWhere('address','like', '%' . $search . '%');
        $business_list->orWhere('contact_name','like', '%' . $search . '%');
        $business_list->orWhere('register_mobile','like', '%' . $search . '%');
        $business_list->orWhere('contact_person_no','like', '%' . $search . '%');
        $business_list->orWhere('referred_by','like', '%' . $search . '%');
    }
    $business_list->chunk(50, function($businesses) use (&$exportArr) {
        foreach ($businesses as $business) {
            $category1_name ='';
            $subcategory1_name ='';
            $category2_name='';
            $subcategory2_name='';
            $categories = BusinessCategory::where('business_id',$business->id)->get();

            if(!empty($categories[0])){
                $category1_name = Category::where('id',$categories[0]->cat_id)->first()->name??'';
                if(!empty($categories[0]->sub_cat_id)){
                    $sub_cat_id = explode(",",$categories[0]->sub_cat_id);
                    $subcategory1_name = SubCategory::whereIn('id',$sub_cat_id)->pluck('name');
                }
            }
            if(!empty($categories[1])){
                $category2_name = Category::where('id',$categories[1]->cat_id)->first()->name??'';
                if(!empty($categories[1]->sub_cat_id)){
                    $sub_cat_id1 = explode(",",$categories[1]->sub_cat_id);
                    $subcategory2_name = SubCategory::whereIn('id',$sub_cat_id1)->pluck('name');
                }
            }




            $businessArr = [];
            $businessArr['Business Name'] = $business->business_name ?? '';
            $businessArr['Type'] = $business->business_type ?? '';
            $businessArr['Owner Name'] = $business->owner_name ?? '';
            $businessArr['Mobile'] = $business->mobile ?? '';
            $businessArr['Land Line No'] = $business->land_line_no ?? '';
            $businessArr['Address'] = $business->address ?? '';
            $businessArr['Pincode'] = $business->pincode ?? '';
            $businessArr['Locality'] = $business->locality ?? '';
            $businessArr['Contact Person name'] = $business->contact_name ?? '';
            $businessArr['Contact Person No'] = $business->contact_person_no ?? '';
            $businessArr['Refered By'] = $business->referred_by ?? '';
            $businessArr['Category1'] = $category1_name ?? '';
            $businessArr['SubCategory1'] = $subcategory1_name ?? '';
            $businessArr['Category2'] = $category2_name ?? '';
            $businessArr['SubCategory2'] = $subcategory2_name ?? '';
            $businessArr['Registered Date'] = date('Y-m-d',strtotime($business->created_at)) ?? '';
            $businessArr['Registered Time'] = date('H:i A',strtotime($business->created_at)) ?? '';
            $exportArr[] = $businessArr;
        }

    });
    if(!empty($exportArr)){
        $fileNames = array_keys($exportArr[0]);
    $fileName = 'businesses_'.date('Y-m-d-H-i-s').'.xlsx';
    return Excel::download(new BusinessExport($exportArr, $fileNames), $fileName);
    }
    
}

$businesses = $businesses->paginate(20);
// dd(\DB::getQueryLog()); // Show results of log

$data['businesses'] = $businesses;

$data['categories'] = Category::where('status',1)->where('is_delete',0)->get();
return view('admin.businesses.index',$data);
}


public function add(Request $request)
{


   $details = [];

   $id = isset($request->id) ? $request->id : 0;

   $businesses = '';

   if(is_numeric($id) && $id > 0)
   {
    $businesses = Business::find($id);
    if(empty($businesses))
    {
        return redirect($this->ADMIN_ROUTE_NAME.'/businesses');
    }
}


if($request->method() == "POST" || $request->method() == "post")
{

    // prd($request->toArray());

    if(empty($back_url))
    {
       $back_url = $this->ADMIN_ROUTE_NAME.'/businesses';
   }




   if(is_numeric($request->id) && $request->id > 0)
   {
       $details['business_name'] = 'required';
       $details['category_ids1'] = 'required';
       $details['owner_name'] = 'required';
       $details['mobile'] = 'required';
       $details['address'] = 'required';
       $details['pincode'] = 'required';
       $details['business_type'] = 'required';
       //$details['priority'] = 'nunique:businesses,priority';


   }else{

       $details['business_name'] = 'required';
       $details['category_ids1'] = 'required';
       $details['owner_name'] = 'required';
       $details['mobile'] = 'required';
       $details['address'] = 'required';
       $details['pincode'] = 'required';
       $details['business_type'] = 'required';
     //  $details['priority'] = 'unique:businesses,priority';



   }

   $this->validate($request , $details);    


  // prd($request->toArray());
   $createdDetails = $this->save($request , $id);

   if($createdDetails)
   {
    $alert_msg = "Business Created Successfully";

    if(is_numeric($id) & $id > 0)
    {
        $alert_msg = "Business Updated Successfully";
    } 
    return redirect(url($back_url))->with('alert-success',$alert_msg);
}else{

    return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
}

}

$page_Heading = "Add Business";




$cats = [];
$subcategories =[];
$categoryIds =[];
$subcategories1 = [];
$subcategories2 = [];
$subcategoriesids2 = [];
$subcategoriesids1 = [];

if(isset($businesses->id))
{
    $business_name = $businesses->business_name;
    $page_Heading = 'Update -'.$business_name;
    $cats = BusinessCategory::where('business_id',$businesses->id)->limit(2)->get();
    if(!empty($cats)){
        foreach($cats as $cat){
            $categoryIds[] = $cat->cat_id ??'';
            $subcategories[] = SubCategory::where('category_id',$cat->cat_id)->get();
            $subcategoryIds[] = $cat->sub_cat_id ??'';
        }
    }

    if(!empty($subcategoryIds)){
        $subcat1 = $subcategoryIds[0] ??'';
        $subcat2 = $subcategoryIds[1] ??'';
        if(!empty($subcat1)){
            $subcategoriesids1 = explode(",", $subcat1);
            //$subcategoriesids1 = SubCategory::whereIn('id',$subcat1)->pluck('id')->toArray();
        }
        if(!empty($subcat2)){
            $subcategoriesids2 = explode(",", $subcat2);
            //$subcategoriesids2 = SubCategory::whereIn('id',$subcat2)->pluck('id')->toArray();
        }

    }
}


$details['page_Heading'] = $page_Heading;
$details['id'] = $id;
$details['businesses'] = $businesses;
$details['states'] = State::get();
$details['cities'] = City::get();
$details['categoryIds'] = $categoryIds;
$details['cats'] = $cats;
$details['subcategories'] = $subcategories;
$details['subcategoriesids1'] = $subcategoriesids1;
$details['subcategoriesids2'] = $subcategoriesids2;
$details['categories'] = Category::get();



return view('admin.businesses.form',$details);

}


public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url','category_ids','category_ids1','category_ids2','subcategory_ids1','subcategory_ids2','is_export','page']);


    $old_img = '';
    $old_img1 = '';


    $business = new Business;

    if(is_numeric($id) && $id > 0)
    {
        $exist = Business::find($id);

        if(isset($exist->id) && $exist->id == $id)
        {   
            $business = $exist;

            $old_img = $exist->doc_image;
            $old_img1 = $exist->image;

        }

    }

    foreach($details as $key => $val)
    {
        $business->$key = $val;
    }

    $isSaved = $business->save();




    if($isSaved)
    {
        // $this->save_categories($business->id,$request);

        $category_ids1 = isset($request->category_ids1) ?$request->category_ids1 :'';
        $category_ids2 = isset($request->category_ids2) ?$request->category_ids2 :'';
        $subcategory_ids1 = isset($request->subcategory_ids1) ?$request->subcategory_ids1 :'';
        $subcategory_ids2 = isset($request->subcategory_ids2) ?$request->subcategory_ids2 :'';
        $dbArray = [];

        $business_id= $business->id;
        $count = BusinessCategory::where('business_id',$business_id)->count();
        $exist_category = BusinessCategory::where('business_id',$business_id)->limit(2)->get();
        if(!empty($exist_category)){

            BusinessCategory::where('business_id',$business_id)->delete();
            $dbArr = [];
            $dbArr['business_id'] = $business_id;
            $dbArr['cat_id'] = $category_ids1;
            $dbArr['sub_cat_id'] = 0;
            if(!empty($subcategory_ids1)){

                $dbArr['sub_cat_id'] = implode(",", $subcategory_ids1);
            }
            $exist1 = BusinessCategory::where('business_id',$business_id)->where('cat_id',$category_ids1)->first();
            if(!empty($exist1)){
                BusinessCategory::where('business_id',$business_id)->where('cat_id',$category_ids1)->update($dbArr);
            }else{
                //if($count<2){
             BusinessCategory::insert($dbArr);
               // }

         }

         $dbArr1 = [];
         $dbArr1['business_id'] = $business_id;
         $dbArr1['cat_id'] = $category_ids2;
         $dbArr['sub_cat_id'] = 0;
         if(!empty($subcategory_ids2)){

            $dbArr1['sub_cat_id'] = implode(",", $subcategory_ids2);
        }
        $exist1 = BusinessCategory::where('business_id',$business_id)->where('cat_id',$category_ids2)->first();
        if(!empty($exist1)){
            BusinessCategory::where('business_id',$business_id)->where('cat_id',$category_ids2)->update($dbArr1);
        }else{
                //if($count<2){
            BusinessCategory::insert($dbArr1);
               // }
        }
    }


    $this->saveImage($request , $business , $old_img,$old_img1);
}

return $isSaved;
}


public function save_categories($business_id,$request){


    $categoryIds = $request->category_ids??'';

    $exist_category = BusinessCategory::where('business_id',$business_id)->get();


    if(empty($exist_category) && count($exist_category) > 0){
      if(!empty($categoryIds)){
        foreach ($categoryIds as $key1 => $value1) {
            $dbArr = [];
            $dbArr['business_id'] = $business_id;
            $dbArr['cat_id'] = $value1;
            $dbArr['sub_cat_id'] = 0;
            $dbArr['status'] = 1;
            $exist = BusinessCategory::where('business_id',$business_id)->where('cat_id',$value1)->first();
            if(empty($exist)){
                BusinessCategory::insert($dbArr);
            }else{

            }

        }
    }
}else{

    if(!empty($categoryIds) && !empty($exist_category)){
        if(count($exist_category) == count($categoryIds)){





         if(!empty($categoryIds)){
            foreach ($categoryIds as $key2 => $value2) {
                $dbArr = [];
                $dbArr['business_id'] = $business_id;
                $dbArr['cat_id'] = $value2;
                $dbArr['sub_cat_id'] = 0;
                $dbArr['status'] = 1;
                $exist = BusinessCategory::where('business_id',$business_id)->where('cat_id',$exist_category[$key2]->cat_id)->first();
                if(!empty($exist)){
                    BusinessCategory::where('id',$exist->id)->update($dbArr);
                }else{

                }
            }
        }
    }
}
if(!empty($categoryIds) && !empty($exist_category)){
    if(count($exist_category) > count($categoryIds)){
        $catId = [];
        $count = count($exist_category) - count($categoryIds);
        BusinessCategory::orderBy('id', 'desc')->limit($count)->delete();
        if(!empty($categoryIds)){
            foreach ($categoryIds as $key3 => $value3) {
                $dbArr = [];
                $dbArr['business_id'] = $business_id;
                $dbArr['cat_id'] = $value3;
                $dbArr['sub_cat_id'] = 0;
                $dbArr['status'] = 1;
                $exist = BusinessCategory::where('business_id',$business_id)->where('cat_id',$exist_category[$key3]->cat_id)->first();
                if(!empty($exist)){
                    BusinessCategory::where('id',$exist->id)->update($dbArr);
                }else{
                //BusinessCategory::insert($dbArr);
                }
            }
        }




    }
}
if(!empty($categoryIds) && !empty($exist_category)){
    if(count($exist_category) < count($categoryIds)){
        if(!empty($categoryIds)){
            foreach ($categoryIds as $key4 => $value4) {
                $dbArr = [];
                $dbArr['business_id'] = $business_id;
                $dbArr['cat_id'] = $value4;
                $dbArr['sub_cat_id'] = 0;
                $dbArr['status'] = 1;
                $exist = [];
                $cat_id = $exist_category[$key4]->cat_id ?? '';
                if(!empty($cat_id) && $cat_id !=''){
                    $exist = BusinessCategory::where('business_id',$business_id)->where('cat_id',$cat_id)->first();
                }
                if(empty($exist)){
                    BusinessCategory::insert($dbArr);
                }else{
                    BusinessCategory::where('id',$exist->id)->update($dbArr);
                }

            }
        }
    }
}

}







return true;

}
















private function saveImage($request, $business, $oldImg='',$old_img1=''){

    $file = $request->file('doc_image');

    //prd($file);
    if ($file) {
        $path = 'business_docs/';
        $thumb_path = 'business_docs/thumb/';
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
            $business->doc_image = $image;
            $business->save();         
        }

        if(!empty($uploaded_data)){   
            //return  $uploaded_data;
        }  

    }



    $file1 = $request->file('image');

    //prd($file);
    if ($file1) {
        $path = 'business_gallery/';
        $thumb_path = 'business_gallery/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file1, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

            // prd($uploaded_data);
        if($uploaded_data['success']){

            if(!empty($old_img1)){
                if($storage->exists($path.$old_img1)){
                    $storage->delete($path.$old_img1);
                }
                if($storage->exists($thumb_path.$old_img1)){
                    $storage->delete($thumb_path.$old_img1);
                }
            }
            $image = $uploaded_data['file_name'];

           // prd($image);
            $business->image = $image;
            $business->save();         
        }

        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }















}

public function change_business_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $business = Business::where('id',$id)->first();
  if(!empty($business)){

     Business::where('id',$id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'Not  Found';
     return response()->json($response);  
 }

}




public function getsubcategories(Request $request){
    $category_ids1 = isset($request->category_ids1) ? $request->category_ids1 :'';
    $html='';
    if(!empty($category_ids1)){
        $subcategories = SubCategory::where('category_id',$category_ids1)->get();
        if(!empty($subcategories) && count($subcategories) > 0){
            $html = '<select class="form-control select2" name="subcategory_ids[]" id="subcategory_ids1" multiple>';
            foreach ($subcategories as $key) {
                $html.= '<option value='.$key->id.'>'.$key->name.'</option>';
            }
        }
    }
    echo $html;
}
public function getsubcategories1(Request $request){
    $category_ids2 = isset($request->category_ids2) ? $request->category_ids2 :'';
    $html='';
    if(!empty($category_ids2)){
        $subcategories = SubCategory::where('category_id',$category_ids2)->get();
        if(!empty($subcategories) && count($subcategories) > 0){
            $html = '<select class="form-control select2" name="subcategory_ids[]" id="subcategory_ids2" multiple>';
            foreach ($subcategories as $key) {
                $html.= '<option value='.$key->id.'>'.$key->name.'</option>';
            }
        }
    }
    echo $html;
}

public function delete(Request $request)
{
   $id = isset($request->id) ? $request->id : 0;



   $is_delete = 0;

   if(empty($back_url))
   {
    $back_url = $this->ADMIN_ROUTE_NAME.'/businesses';
}

if(is_numeric($id) && $id > 0)
{
        //echo $id;
    $is_delete = Business::where('id', $id)->update(['is_delete'=> '1']);
}

     //die;

if(!empty($is_delete))
{
    return back()->with('alert-success', 'Business Deleted Successfully');
}else{

    return back()->with('alert-danger', 'something went wrong, please try again...');
}

}





public function show(Request $request){
    $data = [];
    $business_id = isset($request->id) ? $request->id :'';

    $data['astro_details'] = [];
    $data['rating'] = [];
    $data['astrologer_gallery'] = [];
    $data['astro_faqs'] = [];
    $data['transactions'] = [];
    $data['tabs'] = '';



    $business = Business::where('id',$business_id)->first();
    $data['business'] = $business;



    $galleries = DB::table('business_gallery')->where('business_id',$business_id)->latest()->get();
    $data['galleries'] = $galleries;



    $ratings = DB::table('ratings')->where('business_id',$business_id)->latest()->paginate(10);
    $data['ratings'] = $ratings;






    return view('admin.businesses.profile',$data);

}




public function gallery(Request $request,$ext='jpg,jpeg,png')
{
    $dbArray = [];
    $id = isset($request->business_id)?$request->business_id:0;       
    $files = $request->file('images');
    $path = 'business_gallery/';
    $thumb_path = 'business_gallery/thumb/';
    $storage = Storage::disk('public');
    $IMG_WIDTH = 768;
    $IMG_HEIGHT = 768;
    $THUMB_WIDTH = 336;
    $THUMB_HEIGHT = 336;



    if ($files && count($files) > 0) {
        foreach($files as $file){
          $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $width=768, $height=768, $is_thumb=false, $thumb_path, $thumb_width=300, $thumb_height=300);
          if($uploaded_data['success']){
            $image = $uploaded_data['file_name'];
            $dbArray['file'] = $image;
            $dbArray['business_id'] = $request->business_id;
            $dbArray['type'] = $uploaded_data['extension'];
            $success = DB::table('business_gallery')->insert($dbArray);
        }
    }
    if($success)
    {
        $alert_msg = 'Images Upload Successfully';
        return back()->with('alert-success',$alert_msg);
    }else{
        return back()->with('alert-danger','Somthing Went Wrong');
    }       
}else{
    return back()->with('alert-danger','Please Upload File');
} 
}





public function img_delete(Request $request)
{
    $image = DB::table('business_gallery')->find($request->id);
    if(!empty($image))
    {
        $images = isset($image->file) ? $image->file : '';
        $storage = Storage::disk('public');
        $path = 'astro_gallery';
        $image_path = url('public/storage/'.$path.'/'.$images);
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }        

        DB::table('business_gallery')->where('id',$request->id)->delete();
        return back()->with('success','Image Deleted Successfully');
    }else{

        return back()->with('alert-danger','Not Found');
    }


}



public function ratings(Request $request)
{
    $data = [];
    $details['user_id'] = 'required';
    $details['business_id'] = 'required';
    $details['rating'] = 'required';
    $details['review'] = '';                                   

    $this->validate($request , $details); 
    if(empty($back_url))
    {
        $back_url = $this->ADMIN_ROUTE_NAME.'/businesses/show/'.$request->business_id.'?back_url=admin/businesses';
    }
    $id = isset($request->id)?$request->id: '';
    $user_id = isset($request->user_id)?$request->user_id: '';
    $business_id = isset($request->business_id)?$request->business_id:'';
    $rating = isset($request->rating)?$request->rating:0;
    $review = isset($request->review)?$request->review:'';
    $data['user_id'] = $user_id;
    $data['business_id'] = $business_id;
    $data['rating'] = $rating;
    $data['review'] = $review; 
    if(!empty($id))
    {
        $success = DB::table('ratings')->where('id',$id)->update(['user_id'=>$user_id,'business_id'=>$business_id,'rating'=>$rating,'review'=>$review]);       
    }
    if($success)
    {
        $alert_msg = 'Ratings Update Successfully';
        return redirect(url($back_url))->with('alert-success',$alert_msg);
    }else{

        return back()->with('alert-danger','Somthing Went Wrong');
    }

}


public function export_old($search='',$start_date='',$end_date='')
{
    $exportArr = [];
    $business_list = Business::where('is_delete',0)
    ->chunk(50, function($businesses) use (&$exportArr) {
        foreach ($businesses as $business) {
            $category1_name ='';
            $subcategory1_name ='';
            $category2_name='';
            $subcategory2_name='';
            $categories = BusinessCategory::where('business_id',$business->id)->get();

            if(!empty($categories[0])){
                $category1_name = Category::where('id',$categories[0]->cat_id)->first()->name??'';
                if(!empty($categories[0]->sub_cat_id)){
                    $sub_cat_id = explode(",",$categories[0]->sub_cat_id);
                    $subcategory1_name = SubCategory::whereIn('id',$sub_cat_id)->pluck('name');
                }
            }
            if(!empty($categories[1])){
                $category2_name = Category::where('id',$categories[1]->cat_id)->first()->name??'';
                if(!empty($categories[1]->sub_cat_id)){
                    $sub_cat_id1 = explode(",",$categories[1]->sub_cat_id);
                    $subcategory2_name = SubCategory::whereIn('id',$sub_cat_id1)->pluck('name');
                }
            }




            $businessArr = [];
            $businessArr['Business Name'] = $business->business_name ?? '';
            $businessArr['Type'] = $business->business_type ?? '';
            $businessArr['Owner Name'] = $business->owner_name ?? '';
            $businessArr['Mobile'] = $business->mobile ?? '';
            $businessArr['Land Line No'] = $business->land_line_no ?? '';
            $businessArr['Address'] = $business->address ?? '';
            $businessArr['Pincode'] = $business->pincode ?? '';
            $businessArr['Locality'] = $business->locality ?? '';
            $businessArr['Contact Person name'] = $business->contact_name ?? '';
            $businessArr['Contact Person No'] = $business->contact_person_no ?? '';
            $businessArr['Refered By'] = $business->referred_by ?? '';
            $businessArr['Category1'] = $category1_name ?? '';
            $businessArr['SubCategory1'] = $subcategory1_name ?? '';
            $businessArr['Category2'] = $category2_name ?? '';
            $businessArr['SubCategory2'] = $subcategory2_name ?? '';
            $businessArr['Registered Date'] = date('Y-m-d',strtotime($business->created_at)) ?? '';
            $businessArr['Registered Time'] = date('H:i A',strtotime($business->created_at)) ?? '';
            $exportArr[] = $businessArr;
        }

    });
    $fileNames = array_keys($exportArr[0]);


    $fileName = 'businesses_'.date('Y-m-d-H-i-s').'.xlsx';

    return Excel::download(new BusinessExport($exportArr, $fileNames), $fileName);

}

public function export()
{
    $exportArr = [];
    $business_list = Business::where('is_delete',0)
    ->chunk(50, function($businesses) use (&$exportArr) {
        foreach ($businesses as $business) {
            $category1_name ='';
            $subcategory1_name ='';
            $category2_name='';
            $subcategory2_name='';
            $categories = BusinessCategory::where('business_id',$business->id)->get();

            if(!empty($categories[0])){
                $category1_name = Category::where('id',$categories[0]->cat_id)->first()->name??'';
                if(!empty($categories[0]->sub_cat_id)){
                    $sub_cat_id = explode(",",$categories[0]->sub_cat_id);
                    $subcategory1_name = SubCategory::whereIn('id',$sub_cat_id)->pluck('name');
                }
            }
            if(!empty($categories[1])){
                $category2_name = Category::where('id',$categories[1]->cat_id)->first()->name??'';
                if(!empty($categories[1]->sub_cat_id)){
                    $sub_cat_id1 = explode(",",$categories[1]->sub_cat_id);
                    $subcategory2_name = SubCategory::whereIn('id',$sub_cat_id1)->pluck('name');
                }
            }




            $businessArr = [];
            $businessArr['Business Name'] = $business->business_name ?? '';
            $businessArr['Type'] = $business->business_type ?? '';
            $businessArr['Owner Name'] = $business->owner_name ?? '';
            $businessArr['Mobile'] = $business->mobile ?? '';
            $businessArr['Land Line No'] = $business->land_line_no ?? '';
            $businessArr['Address'] = $business->address ?? '';
            $businessArr['Pincode'] = $business->pincode ?? '';
            $businessArr['Locality'] = $business->locality ?? '';
            $businessArr['Contact Person name'] = $business->contact_name ?? '';
            $businessArr['Contact Person No'] = $business->contact_person_no ?? '';
            $businessArr['Refered By'] = $business->referred_by ?? '';
            $businessArr['Category1'] = $category1_name ?? '';
            $businessArr['SubCategory1'] = $subcategory1_name ?? '';
            $businessArr['Category2'] = $category2_name ?? '';
            $businessArr['SubCategory2'] = $subcategory2_name ?? '';
            $businessArr['Registered Date'] = date('Y-m-d',strtotime($business->created_at)) ?? '';
            $businessArr['Registered Time'] = date('H:i A',strtotime($business->created_at)) ?? '';
            $exportArr[] = $businessArr;
        }

    });
    $fileNames = array_keys($exportArr[0]);
    $fileName = 'businesses_'.date('Y-m-d-H-i-s').'.xlsx';
    return Excel::download(new BusinessExport($exportArr, $fileNames), $fileName);
}






}




