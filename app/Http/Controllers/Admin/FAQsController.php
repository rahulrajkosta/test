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
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class FAQsController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
		$faqs = FAQs::where('is_delete','0')->orderBy('id','desc')->paginate(10);
        $data['faqs'] = $faqs;

        return view('admin.faqs.index',$data);
    }

   

    public function add(Request $request)
    {
         $details = [];
    
        $id = isset($request->id) ? $request->id : 0;
        $faqs = '';

        if(is_numeric($id) && $id > 0)
        {
            $faqs = FAQs::find($id);
            if(empty($faqs))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/faqs');
            }
        }       

        if($request->method() == "POST" || $request->method() == "post")
        {
            // prd($request->toArray());

            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/faqs';
            }           

            if(is_numeric($request->id) && $request->id > 0)
            {
                 $details['questions'] = 'required';
                $details['answer'] = 'required'; 
              
            }else{
                 $details['questions'] = 'required';
                $details['answer'] = 'required';                
            }
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

        $page_Heading = "Add FAQs";      

        $details['page_Heading'] = $page_Heading;
        $details['id'] = $id;
        $details['faqs'] = $faqs;

        return view('admin.faqs.form',$details);
    }


    public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);
        $faqs = new FAQs;

        if(is_numeric($id) && $id > 0)
        {
            $exist = FAQs::find($id);
            if(isset($exist->id) && $exist->id == $id)
            {   
                $faqs = $exist;
                $old_img = $exist->image;
            }
        }

        foreach($details as $key => $val)
        {
            $faqs->$key = $val;
        }

        $isSaved = $faqs->save();
        return $isSaved;
    }  

public function change_faq_status(Request $request){
  $faq_id = isset($request->faq_id) ? $request->faq_id :'';
  $status = isset($request->status) ? $request->status :'';

  $faqs = FAQs::where('id',$faq_id)->first();
  if(!empty($faqs)){

   FAQs::where('id',$faq_id)->update(['status'=>$status]);
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
        $is_delete = FAQs::where('id', $id)->update(['is_delete'=> '1']);
     }

     if(!empty($is_delete))
     {
        return back()->with('alert-success', 'Deleted Successfully');
     }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }
    
}

   
}




