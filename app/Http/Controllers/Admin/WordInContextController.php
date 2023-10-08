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
use App\WordInContext;

use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class WordInContextController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
        $contextwords = WordInContext::where('is_delete',0)->paginate(10);
        $data['contextwords'] = $contextwords;
        return view('admin.word_in_context.index',$data);
    }

    

    public function add(Request $request)
    {
         $details = [];
    
        $id = isset($request->id) ? $request->id : 0;

        $word_in_context = '';

        if(is_numeric($id) && $id > 0)
        {

            $word_in_context = WordInContext::find($id);

            if(empty($word_in_context))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/word_in_context');
            }
        }
       

        if($request->method() == "POST" || $request->method() == "post")
        {
            
            // prd($request->toArray());

            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/word_in_context';
            }
 
                $details['phrase'] = 'required';               
                $details['clue'] = '';
                $details['right_answer'] = 'required';
                $details['marks'] = 'required';
                $details['duration'] = 'required';
        
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

        $word = WordInContext::where('status',1)->get();      
        if(is_numeric($id) && $id > 0){
            $page_Heading = 'Update';
        }
        $details['page_Heading'] = $page_Heading;      
        $details['word_in_context'] = $word_in_context;
       return view('admin.word_in_context.form',$details);

    }


    public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);

        $word_in_context = new WordInContext;

        if(is_numeric($id) && $id > 0)
        {
            $exist = WordInContext::find($id);

            if(isset($exist->id) && $exist->id == $id)
            {   
                $word_in_context = $exist;
                
            }
        }

        foreach($details as $key => $val)
        {
            $word_in_context->$key = $val;
        }      

        $isSaved = $word_in_context->save();
        return $isSaved;
    }



public function change_word_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $word = WordInContext::where('id',$id)->first();
  if(!empty($word)){

   WordInContext::where('id',$id)->update(['status'=>$status]);
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
        $back_url = $this->ADMIN_ROUTE_NAME.'/word_in_context';
    }
     if(is_numeric($id) && $id > 0)
     {
        $is_delete = WordInContext::where('id', $id)->update(['is_delete'=> '1']);
     }

     if(!empty($is_delete))
     {
        return back()->with('alert-success', 'Deleted Successfully');
     }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }    
}

   
}




