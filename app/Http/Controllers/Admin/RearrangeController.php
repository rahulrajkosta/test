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

use App\RearrangeWord;


use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class RearrangeController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
        $words = RearrangeWord::where('is_delete',0)->paginate(10);
        $data['words'] = $words;
        return view('admin.rearrange.index',$data);
    }

    

    public function add(Request $request)
    {
         $details = [];
    
        $id = isset($request->id) ? $request->id : 0;

        $rearrange = '';

        if(is_numeric($id) && $id > 0)
        {

            $rearrange = RearrangeWord::find($id);

            if(empty($rearrange))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/rearrange');
            }
        }
       

        if($request->method() == "POST" || $request->method() == "post")
        {
            
            // prd($request->toArray());

            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/rearrange';
            }
 
                $details['sentence'] = 'required';               
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

        $word = RearrangeWord::where('status',1)->get();      
        if(is_numeric($id) && $id > 0){
            $page_Heading = 'Update';
        }
        $details['page_Heading'] = $page_Heading;      
        $details['rearrange'] = $rearrange;
       return view('admin.rearrange.form',$details);

    }


    public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);

        $rearrange = new RearrangeWord;

        if(is_numeric($id) && $id > 0)
        {
            $exist = RearrangeWord::find($id);

            if(isset($exist->id) && $exist->id == $id)
            {   
                $rearrange = $exist;
                
            }
        }

        foreach($details as $key => $val)
        {
            $rearrange->$key = $val;
        }      

        $isSaved = $rearrange->save();
        return $isSaved;
    }



public function change_word_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $word = RearrangeWord::where('id',$id)->first();
  if(!empty($word)){

   RearrangeWord::where('id',$id)->update(['status'=>$status]);
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
        $back_url = $this->ADMIN_ROUTE_NAME.'/rearrange';
    }

     if(is_numeric($id) && $id > 0)
     {
        //echo $id;
        $is_delete = RearrangeWord::where('id', $id)->update(['is_delete'=> '1']);
     }

     //die;

     if(!empty($is_delete))
     {
        return back()->with('alert-success', 'Deleted Successfully');
     }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }
    
}

   
}




