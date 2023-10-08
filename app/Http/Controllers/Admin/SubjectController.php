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
use App\City;
use App\Content;
use App\SubCategory;
use App\Blocks;
use App\Flats;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class SubjectController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
		  $subject = Subject::where('is_delete',0)->paginate(10);
        $data['subject'] = $subject;
        return view('admin.subject.index',$data);
    }

   

    public function add(Request $request)
    {
         $details = [];
    
        $id = isset($request->id) ? $request->id : 0;

        $batches = '';

        if(is_numeric($id) && $id > 0)
        {
            $batches = Subject::find($id);

            // prd($courses);
            if(empty($batches))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/subject');
            }
        }
       

        if($request->method() == "POST" || $request->method() == "post")
        {
            
           // prd($request->toArray());

            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/subject';
            }


            if(is_numeric($request->id) && $request->id > 0)
            {
                 $details['category_id'] = 'required';
                 $details['course_id'] = 'required';                
                $details['image'] = '';   
                 $details['subject_name'] = 'required';              
              
              

            }else{

                  $details['category_id'] = 'required';
                 $details['course_id'] = 'required';                
                $details['image'] = '';   
                 $details['subject_name'] = 'required';   
            }

          $this->validate($request , $details); 

            // prd($dd);

           $createdDetails = $this->save($request , $id);

           if($createdDetails)
           {
                $alert_msg = "Subject Created Successfully";

                if(is_numeric($id) & $id > 0)
                {
                    $alert_msg = "Subject Updated Successfully";
                } 
                return redirect(url($back_url))->with('alert-success',$alert_msg);
           }else{

            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
           }

        }

        $page_Heading = "Add Subject";
        if(isset($batches->id))
        {

            $batch_name = $batches->subject_name;
            $page_Heading = 'Update Subject -'.$batch_name;

        }

        $category = Category::select('id','category_name')->where('status','1')->get();

        $details['page_Heading'] = $page_Heading;
        $details['id'] = $id;
        $details['category'] = $category;
        $details['batches'] = $batches;
        $course = [];
        if(is_numeric($request->id) && $request->id > 0) {
            $course = Course::where('category_id', $batches->category_id)->get();

        }
        $details['course'] = $course;
       return view('admin.subject.form',$details);

    }


    public function save(Request $request, $id = 0)
    {
       // prd($request->toArray());
        $details = $request->except(['_token', 'back_url']);


        $old_img = '';

        $batches = new Subject;

        if(is_numeric($id) && $id > 0)
        {
            $exist = Subject::find($id);

            if(isset($exist->id) && $exist->id == $id)
            {   
                $batches = $exist;

                $old_img = $exist->image;

            }

        }

        foreach($details as $key => $val)
        {
            $batches->$key = $val;
        }

        $isSaved = $batches->save();

        if($isSaved)
        {
            $this->saveImage($request , $batches , $old_img);
        }

        return $isSaved;
    }

    private function saveImage($request, $batches, $oldImg=''){

    $file = $request->file('image');

    //prd($file);
    if ($file) {
        $path = '/subjects';
        $thumb_path = 'subjects/thumb/';
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
            $batches->image = $image;
            $batches->save();         
        }

        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}

  public function get_courses(Request $request)
  {
      // prd($request->toArray());  

       $category_id = $request->category_id;

       $html = '<option value="" selected disabled>Select Course</option>';

       if(!empty($category_id))
       {
         $course = Course::select('id','course_name')->where('category_id',$category_id)->get();

         if(!empty($course))
         {

          //  print_r($course);
            foreach($course as $c)
            {   
                
                $html.= '<option value='.$c->id.'>'.$c->course_name.'</option>';

            }

         }

       }
     
       
      echo $html;
         
      
  }


public function change_batch_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $batches = Batch::where('id',$id)->first();
  if(!empty($batches)){

   Batch::where('id',$id)->update(['status'=>$status]);
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
        $back_url = $this->ADMIN_ROUTE_NAME.'/subject';
    }

     if(is_numeric($id) && $id > 0)
     {
        //echo $id;
        $is_delete = Subject::where('id', $id)->update(['is_delete'=> '1']);
     }

     //die;

     if(!empty($is_delete))
     {
        return back()->with('alert-success', 'Subject Deleted Successfully');
     }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }
    
}

 
public function getcontent(Request $request)
{
    $method = $request->method();

    if($method == "POST" ||  $method == "post")
    { 

    // prd($request->toArray());     
       
         if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/subject/getcontent/'.$request->subject_id.' ';
            }          
        
        $details = [];

         $details['subject_id'] = 'required';
        $details['title'] = 'required';
        $details['hls_type'] = 'required';
         $details['hls'] = 'required';


            

         $this->validate($request , $details); 
            
       $createdDetails = $this->savecontent($request);

       if($createdDetails)
       {
            $alert_msg = 'Content Saved Successsfully';
            return redirect(url($back_url))->with('alert-success', $alert_msg);
       }else{

        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
       }

    } 

    $subject_id = $request->id;
    $videos = Content::select('id','title','hls','hls_type')->where('hls_type','video')->where(['subject_id'=> $request->id, 'is_delete' => 0])->paginate(10);

    $notes = Content::select('id','title','hls','hls_type')->where('hls_type','notes')->where(['subject_id'=> $request->id, 'is_delete' => 0])->paginate(10);


    $data['videos'] = $videos;
    $data['notes'] = $notes;


    $data['subject_id'] = $subject_id;

    $data['subject'] = Subject::where('id',$subject_id)->first();

    return view('admin.subject.getcontent',$data);

}


public function savecontent(Request $request)
{

     $details = $request->except(['_token', 'back_url']);

        $old_img = '';
        $content = new Content;

        foreach($details as $key => $val)
        {
            $content->$key = $val;
        }

        $isSaved = $content->save();

        if($isSaved)
        {
            $this->saveContentImage($request , $content , $old_img);
        }

        return $isSaved;

}

  private function saveContentImage($request, $content, $oldImg=''){

    $file = $request->file('hls');

    //prd($file);
    if ($file) {
        $path = 'subjects/contents/';
       
        $storage = Storage::disk('public');
            //prd($storage);
       

        $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');

            // prd($uploaded_data);
        if($uploaded_data['success']){

           
            $image = $uploaded_data['file_name'];

           // prd($image);
            $content->hls = $image;
            $content->save();         
        }

        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}

public function delete_content(Request $request)
{

    $id = isset($request->id) ? $request->id : '';

    $is_Delete = 0;
     if(empty($back_url))
    {
        $back_url = $this->ADMIN_ROUTE_NAME.'/subject/getcontent';
    }


    if(is_numeric($id) && $id > 0)
    {
        $is_Delete = Content::where('id',$id)->update(['is_delete' => 1]);

    }

    if(!empty($is_Delete))
    {
        return back()->with('alert-success', 'Content Deleted Successfully');
    }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }


}



   
}




