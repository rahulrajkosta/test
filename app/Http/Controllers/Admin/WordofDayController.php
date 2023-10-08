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
use App\Banner;

use App\Category;
use App\City;
use App\SubCategory;
use App\WordOfDay;

use App\DailyQuotes;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class WordofDayController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
        $word = WordOfDay::where('is_delete',0)->paginate(10);
        $data['word'] = $word;
        return view('admin.word_of_day.index',$data);
    }

    

    public function add(Request $request)
    {
         $details = [];
    
        $id = isset($request->id) ? $request->id : 0;

        $word_of_day = '';

        if(is_numeric($id) && $id > 0)
        {

            $word_of_day = WordOfDay::find($id);

            if(empty($word_of_day))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/daily_learnings');
            }
        }
       

        if($request->method() == "POST" || $request->method() == "post")
        {
            
            // prd($request->toArray());

            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/daily_learnings';
            }
 
                $details['word'] = 'required';               
                $details['meaning'] = '';
                $details['type'] = 'required';
                $details['date'] = 'required';
        
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

        $page_Heading = "Add";

        $word = WordOfDay::where('status',1)->get();      
        if(is_numeric($id) && $id > 0){
            $page_Heading = 'Update';
        }
        $details['page_Heading'] = $page_Heading;      
        $details['word_of_day'] = $word_of_day;
       return view('admin.word_of_day.form',$details);

    }


    public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);

        $word_of_day = new WordOfDay;

        if(is_numeric($id) && $id > 0)
        {
            $exist = WordOfDay::find($id);

            if(isset($exist->id) && $exist->id == $id)
            {   
                $word_of_day = $exist;
                
            }
        }

        foreach($details as $key => $val)
        {
            $word_of_day->$key = $val;
        }      

        $isSaved = $word_of_day->save();
        return $isSaved;
    }

//     private function saveImage($request, $daily_quotes, $oldImg=''){

//     $file = $request->file('bg_image');

//     //prd($file);
//     if ($file) {
//         $path = 'daily_quotes/';        
//         $thumb_path = 'daily_quotes/thumb/';        
//         $storage = Storage::disk('public');
//             //prd($storage);
//         $IMG_WIDTH = 768;
//         $IMG_HEIGHT = 768;
//         $THUMB_WIDTH = 336;
//         $THUMB_HEIGHT = 336;

//         $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $THUMB_WIDTH, $THUMB_HEIGHT);

//             // prd($uploaded_data);
//         if($uploaded_data['success']){

//             if(!empty($oldImg)){
//                 if($storage->exists($path.$oldImg)){
//                     $storage->delete($path.$oldImg);
//                 }
//                 if($storage->exists($thumb_path.$oldImg)){
//                     $storage->delete($thumb_path.$oldImg);
//                 }
//             }
//             $image = $uploaded_data['file_name'];

//            // prd($image);
//             $daily_quotes->bg_image = $image;
//             $daily_quotes->save();         
//         }

//         if(!empty($uploaded_data)){   
//             return  $uploaded_data;
//         }  

//     }

// }

public function change_word_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $word = WordOfDay::where('id',$id)->first();
  if(!empty($word)){

   WordOfDay::where('id',$id)->update(['status'=>$status]);
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
        $back_url = $this->ADMIN_ROUTE_NAME.'/word_of_day';
    }

     if(is_numeric($id) && $id > 0)
     {
        //echo $id;
        $is_delete = WordOfDay::where('id', $id)->update(['is_delete'=> '1']);
     }

     //die;

     if(!empty($is_delete))
     {
        return back()->with('alert-success', 'Word Deleted Successfully');
     }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }
    
}

   
}




