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
use App\LiveClass;
use App\Faculty;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class LiveClassController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
      $live_classes = LiveClass::orderBy('id','desc')->paginate(10);
      $data['live_classes'] = $live_classes;

      return view('admin.live_classes.index',$data);
  }


  public function add(Request $request)
  {
    $details = [];
    $id = isset($request->id) ? $request->id : 0;
    $live_class = '';
    if(is_numeric($id) && $id > 0)
    {
        $live_class = LiveClass::find($id);
        if(empty($live_class))
        {
            return redirect($this->ADMIN_ROUTE_NAME.'/live_class');
        }
    }
    if($request->method() == "POST" || $request->method() == "post")
    {

        if(empty($back_url))
        {
         $back_url = $this->ADMIN_ROUTE_NAME.'/live_class';
     }

     $details['title'] = 'required';
     $details['course_id'] = 'required';     
     $details['faculty_id'] = 'required';
     $details['start_date'] = 'required';
     $details['start_time'] = 'required';
     $details['end_date'] = 'required';
     $details['end_time'] = 'required';
     $details['channel_id'] = 'required';

     $this->validate($request , $details);    

     $createdDetails = $this->save($request , $id);

     if($createdDetails)
     {
        $alert_msg = "Live Class Created Successfully";

        if(is_numeric($id) & $id > 0)
        {
            $alert_msg = "Live Class Updated Successfully";
        } 
        return redirect(url($back_url))->with('alert-success',$alert_msg);
    }else{

        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }

}

$page_Heading = "Add Live Class";
 if(is_numeric($id) && $id > 0)
{
  
    $page_Heading = 'Update Live Class';

}

$details['page_Heading'] = $page_Heading;
$details['id'] = $id;
$details['live_class'] = $live_class;

$details['faculties'] = Admin::where('role_id',1)->where('status',1)->get();
$details['courses'] = Course::where('status',1)->get();

return view('admin.live_classes.form',$details);

}


public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url']);


    $old_img = '';

    $live_class = new LiveClass;

    if(is_numeric($id) && $id > 0)
    {
        $exist = LiveClass::find($id);

        if(isset($exist->id) && $exist->id == $id)
        {   
            $live_class = $exist;

            $old_img = $exist->image;

        }

    }

    foreach($details as $key => $val)
    {
        $live_class->$key = $val;
    }

    $isSaved = $live_class->save();

    if($isSaved)
    {
        $this->saveImage($request , $live_class , $old_img);
    }

    return $isSaved;
}

private function saveImage($request, $live_class, $oldImg=''){

    $file = $request->file('image');

    //prd($file);
    if ($file) {
        $path = 'live_class/';
        $thumb_path = 'live_class/thumb/';
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
            $live_class->image = $image;
            $live_class->save();         
        }

        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}



public function change_liveclass_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $live_class = LiveClass::where('id',$id)->first();
  if(!empty($live_class)){

   LiveClass::where('id',$id)->update(['status'=>$status]);
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
        //echo $id;
    $is_delete = LiveClass::where('id', $id)->update(['is_delete'=> '1']);
}

if(!empty($is_delete))
{
    return back()->with('alert-success', 'Category Deleted Successfully');
}else{

    return back()->with('alert-danger', 'something went wrong, please try again...');
}

}

public function get_subject(Request $request)
{

    $course_id = $request->course_id;

    $html = '<option value="" selected disabled>Select Subject</option>';



       if(!empty($course_id))
       {

         $subjects = DB::table('subjects')->select('id','subject_name')->where('course_id',$course_id)->get();
        
         if(!empty($subjects))
         {
            foreach($subjects as $subject)
            {            
                
                $html.= '<option value='.$subject->id.'>'.$subject->subject_name.'</option>';

            }

         }

       }
     
       
     echo $html;

}


}




