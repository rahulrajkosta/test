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
use App\MatchWord;

use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class MatchWordsController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
        $match_words = MatchWord::where('is_delete',0)->paginate(10);
        $data['match_words'] = $match_words;
        return view('admin.match_words.index',$data);
    }

    

    public function add(Request $request)
    {
         $details = [];
    
        $id = isset($request->id) ? $request->id : 0;

        $match_words = '';

        if(is_numeric($id) && $id > 0)
        {

            $match_words = MatchWord::find($id);

            if(empty($match_words))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/match_words');
            }
        }
       

        if($request->method() == "POST" || $request->method() == "post")
        {
            
            // prd($request->toArray());

            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/match_words';
            }
 
                $details['word'] = 'required';               
                $details['match'] = 'required';
                $details['child_word'] = '';
                $details['child_match'] = '';
               
        
          $this->validate($request , $details); 

          $detail = [];
          $detail['word'] = $request->word;
          $detail['matches'] = $request->match;

          $credentials = MatchWord::insert($detail);

         
         $child_word = $request->child_word;
          $child_match = $request->child_match;       

          if(!empty($child_word) && !empty($child_match)){
            foreach(array_combine($child_word,$child_match) as $words => $matches ){              
             
                if(!empty($words) && !empty($matches)){
                    $data = array('word'=>$words,'matches'=>$matches);
                    $save_data = MatchWord::insert($data);
                // print_r($data);
                }               
               
            }
          }

          
           if($credentials && $save_data)
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

        $word = MatchWord::where('status',1)->get();      
        if(is_numeric($id) && $id > 0){
            $page_Heading = 'Update';
        }
        $details['page_Heading'] = $page_Heading;      
        $details['match_words'] = $match_words;
       return view('admin.match_words.form',$details);

}

public function edit(Request $request)
{
    // prd($request->toArray());
    $data= [];
    $id = $request->id;

    if(!empty($request->word)){
        $data['word'] = $request->word;
    }

      if(!empty($request->matches)){
        $data['matches'] = $request->matches;
    }

    $saveData = MatchWord::where('id',$id)->update($data);
    if($saveData){
        return back()->with('alert-success','Updated Successfully');
    }


}

public function change_word_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $word = MatchWord::where('id',$id)->first();
  if(!empty($word)){

   MatchWord::where('id',$id)->update(['status'=>$status]);
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
        $back_url = $this->ADMIN_ROUTE_NAME.'/match_words';
    }
     if(is_numeric($id) && $id > 0)
     {
        $is_delete = MatchWord::where('id', $id)->update(['is_delete'=> '1']);
     }

     if(!empty($is_delete))
     {
        return back()->with('alert-success', 'Deleted Successfully');
     }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
     }    
}

   
}




