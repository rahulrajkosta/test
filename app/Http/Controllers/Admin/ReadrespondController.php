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
use App\Read;

use App\ReadQuestion;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class ReadrespondController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
       $reads = Read::orderBy('id','desc')->paginate(10);
       $data['reads'] = $reads;

       return view('admin.reads.index',$data);
   }


   public function add(Request $request)
   {


       $details = [];

       $id = isset($request->id) ? $request->id : 0;

       $reads = '';

       if(is_numeric($id) && $id > 0)
       {
        $reads = Read::find($id);
        if(empty($reads))
        {
            return redirect($this->ADMIN_ROUTE_NAME.'/read_respond');
        }
    }
    if($request->method() == "POST" || $request->method() == "post")
    {

       // prd($request->toArray());

        if(empty($back_url))
        {
           $back_url = $this->ADMIN_ROUTE_NAME.'/read_respond';
       }
       $details['text'] = 'required';
       $this->validate($request , $details);    



       $createdDetails = $this->save($request , $id);

       if($createdDetails)
       {
        $alert_msg = "Read Created Successfully";

        if(is_numeric($id) & $id > 0)
        {
            $alert_msg = "Read Updated Successfully";
        } 
        return redirect(url($back_url))->with('alert-success',$alert_msg);
    }else{

        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }

}

$page_Heading = "Add ";
if(isset($reads->id))
{
    $page_Heading = 'Update ';

}


$questions = [];
if(is_numeric($id) && $id > 0){
    $questions = ReadQuestion::where('read_id',$id)->where('is_delete',0)->paginate(10);
}


$details['page_Heading'] = $page_Heading;
$details['id'] = $id;
$details['questions'] = $questions;
$details['reads'] = $reads;

return view('admin.reads.form',$details);

}

public function add_question(Request $request){
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['question_name'] = 'required';
        $rules['read_id'] = 'required';
        $rules['type'] = 'required';

        if($request->type == 'option'){
        $rules['option_1'] = 'required';
        $rules['option_2'] = 'required';
        $rules['option_3'] = 'required';
        $rules['option_4'] = 'required';
        $rules['right_option'] = 'required';
        }

        if($request->type == 'text'){
            $rules['answer'] = 'required';
        }   
        


        $this->validate($request,$rules);

        $dbArray = [];
        $dbArray['question_name'] = $request->question_name;
        $dbArray['read_id'] = $request->read_id;
        $dbArray['option_1'] = $request->option_1;
        $dbArray['option_2'] = $request->option_2;
        $dbArray['option_3'] = $request->option_3;
        $dbArray['option_4'] = $request->option_4;
        $dbArray['type'] = $request->type;
        $dbArray['answer'] = $request->answer;
        $dbArray['right_option'] = $request->right_option;

        $success = ReadQuestion::insert($dbArray);
        if($success){
            return back()->with('alert-success', 'Question Added Successfully');
        }else{
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }

}






public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url']);
    $old_img = '';
    $reads = new Read;
    if(is_numeric($id) && $id > 0)
    {
        $exist = Read::find($id);

        if(isset($exist->id) && $exist->id == $id)
        {   
            $reads = $exist;

            $old_img = $exist->image;

        }

    }

    foreach($details as $key => $val)
    {
        $reads->$key = $val;
    }

    $isSaved = $reads->save();


    return $isSaved;
}

public function change_read_status(Request $request){
    $id = isset($request->id) ? $request->id :'';
    $status = isset($request->status) ? $request->status :'';

    $reads = Read::where('id',$id)->first();
    if(!empty($reads)){

        Read::where('id',$id)->update(['status'=>$status]);
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
    $back_url = $this->ADMIN_ROUTE_NAME.'/read_respond';
}

if(is_numeric($id) && $id > 0)
{
    //echo $id;
    $is_delete = Read::where('id', $id)->update(['is_delete'=> '1']);
}

 //die;

if(!empty($is_delete))
{
    return back()->with('alert-success', 'Category Deleted Successfully');
}else{

    return back()->with('alert-danger', 'something went wrong, please try again...');
}

}



public function delete_question(Request $request){
    $id = isset($request->id) ? $request->id : 0;
    $is_delete = 0;
    if(is_numeric($id) && $id > 0)
    {
        $is_delete = ReadQuestion::where('id', $id)->update(['is_delete'=> '1']);
    }

    if(!empty($is_delete))
    {
        return back()->with('alert-success', 'Question Deleted Successfully');
    }else{

        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}

}




