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
use App\FAQs;
use App\Category;
use App\City;
use App\SubCategory;
use App\Blocks;
use App\Flats;
use App\News;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class NewsController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
      $news = News::where('is_delete','0')->orderBy('id','desc')->paginate(10);
      $data['news'] = $news;

      return view('admin.news.index',$data);
  }



  public function add(Request $request)
  {
   $details = [];

   $id = isset($request->id) ? $request->id : 0;
   $news = '';

   if(is_numeric($id) && $id > 0)
   {
    $news = News::find($id);
    if(empty($news))
    {
        return redirect($this->ADMIN_ROUTE_NAME.'/news');
    }
}       

if($request->method() == "POST" || $request->method() == "post")
{
            // prd($request->toArray());

    if(empty($back_url))
    {
       $back_url = $this->ADMIN_ROUTE_NAME.'/news';
   }           


   $details['title'] = 'required';
   $details['description'] = 'required';                

   $this->validate($request , $details);  

   $createdDetails = $this->save($request , $id);

   if($createdDetails)
   {
    $alert_msg = "Created Successfully";

    if(is_numeric($id) & $id > 0)
    {
        $alert_msg = "Updated Successfully";
    } 
    return redirect(url($back_url))->with('alert-success',$alert_msg);
}else{

    return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
}

}

$page_Heading = "Add News";      

$details['page_Heading'] = $page_Heading;
$details['id'] = $id;
$details['news'] = $news;

return view('admin.news.form',$details);
}


public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url']);
    $news = new News;
    $old_img = '';

    if(is_numeric($id) && $id > 0)
    {
        $exist = News::find($id);
        if(isset($exist->id) && $exist->id == $id)
        {   
            $news = $exist;
            $old_img = $exist->image;
        }
    }

    foreach($details as $key => $val)
    {
        $news->$key = $val;
    }

    $isSaved = $news->save();
      if($isSaved)
        {
            $this->saveImage($request , $news , $old_img);
        }




    return $isSaved;
}  


 private function saveImage($request, $news, $oldImg=''){

    $file = $request->file('image');

    //prd($file);
    if ($file) {
        $path = 'news/';
        $thumb_path = 'news/thumb/';
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
            $news->image = $image;
            $news->save();         
        }

        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}

public function change_news_status(Request $request){
  $news_id = isset($request->news_id) ? $request->news_id :'';
  $status = isset($request->status) ? $request->status :'';

  $news = News::where('id',$news_id)->first();
  if(!empty($news)){

     News::where('id',$news_id)->update(['status'=>$status]);
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

   if(empty($back_url))
   {
    $back_url = $this->ADMIN_ROUTE_NAME.'/faqs';
}

if(is_numeric($id) && $id > 0)
{        
    $is_delete = News::where('id', $id)->update(['is_delete'=> '1']);
}

if(!empty($is_delete))
{
    return back()->with('alert-success', 'Deleted Successfully');
}else{

    return back()->with('alert-danger', 'something went wrong, please try again...');
}

}


}




