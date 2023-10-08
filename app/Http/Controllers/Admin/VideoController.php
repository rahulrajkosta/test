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
use App\Video;
use App\SubCategory;
use App\Blocks;
use App\Flats;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class VideoController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
        $videos = Video::where('is_delete',0)->paginate(10);
        $data['videos'] = $videos;
        return view('admin.videos.index',$data);
    }

    

    public function add(Request $request)
    {
         $details = [];
    
        $id = isset($request->id) ? $request->id : 0;

        $videos = '';

        if(is_numeric($id) && $id > 0)
        {

            $videos = Video::find($id);

            if(empty($videos))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/videos');
            }
        }
       

        if($request->method() == "POST" || $request->method() == "post")
        {
            
            // prd($request->toArray());

            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/videos';
            }


            if(is_numeric($request->id) && $request->id > 0)
            {                 
                       
                $details['video_id'] = 'required';               
                
              
            }else{

                $details['video_id'] = 'required';               
              
            }

          $this->validate($request , $details); 

           $createdDetails = $this->save($request , $id);

           if($createdDetails)
           {
                $alert_msg = "Video Created Successfully";

                if(is_numeric($id) & $id > 0)
                {
                    $alert_msg = "Video Updated Successfully";
                } 
                return redirect(url($back_url))->with('alert-success',$alert_msg);
           }else{

            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
           }

        }

        $page_Heading = "Add Video";

    
        if(is_numeric($id) && $id > 0){
            $page_Heading = 'Update Video';
        }

        $details['page_Heading'] = $page_Heading;
    
        $details['videos'] = $videos;      

       return view('admin.videos.form',$details);

    }


    public function save(Request $request, $id = 0)
    {
        // prd($request->toArray());

        $details = $request->except(['_token', 'back_url']);


        $old_img = '';

        $banners = new Video;

        if(is_numeric($id) && $id > 0)
        {
            $exist = Video::find($id);

            if(isset($exist->id) && $exist->id == $id)
            {   
                $banners = $exist;
                $old_img = $exist->image;
            }
        }

        foreach($details as $key => $val)
        {
            $banners->$key = $val;
        }

        $isSaved = $banners->save();

        if($isSaved)
        {
            // $this->saveImage($request , $banners , $old_img);
        }

        return $isSaved;
    }

    private function saveImage($request, $banners, $oldImg=''){

    $file = $request->file('image');

    
    if ($file) {
        $path = 'banners/';        
        // $thumb_path = 'banners/thumb/';        
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');

           
        if($uploaded_data['success']){

            $image = $uploaded_data['file_name'];

           
            $banners->image = $image;
            $banners->save();         
        }
        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}

public function change_video_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $faculties = Video::where('id',$id)->first();
  if(!empty($faculties)){

   Video::where('id',$id)->update(['status'=>$status]);
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
        $back_url = $this->ADMIN_ROUTE_NAME.'/videos';
    }

     if(is_numeric($id) && $id > 0)
     {
        //echo $id;
        $is_delete = Video::where('id', $id)->update(['is_delete'=> '1']);
     }

     //die;

     if(!empty($is_delete))
     {
        return back()->with('alert-success', 'Video Deleted Successfully');
     }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }
    
}

   
}




