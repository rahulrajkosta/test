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
use App\Category;

use App\Course;
use App\Exam;
use App\Question;
use Yajra\DataTables\DataTables;


use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

use App\Imports\QuestionImport;
use App\Imports\QuestionNewImport;
use App\Exports\UserExport;

use Maatwebsite\Excel\Facades\Excel;

use App\QuestionNotValid;

use Storage;
use DB;
use Hash;



Class QuestionController extends Controller
{


	private $ADMIN_ROUTE_NAME;

    public function __construct(){

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


    }



    public function index(Request $request){
       $data = [];
       return view('admin.questions.index',$data);
   }



public function import(Request $request){
   $data = [];
   $exam_id = isset($request->id) ? $request->id :'';


   $method = $request->method();
   if($method == 'post' || $method == 'POST'){
    $rules = [];
    $rules['importfile'] = 'required';
    $message = ['importfile.required' => 'You have to choose the file!',];
    $this->validate($request,$rules,$message);

    $sucess = Excel::import(new QuestionNewImport,request()->file('importfile'));

    $questions = QuestionNotValid::get();
    if(!empty($questions) && $questions->count() > 0){
        foreach($questions as $question){
            $questionArr = [];
            $questionArr['question_name'] = $question->question_name;
            $questionArr['option_1'] = $question->option_1;
            $questionArr['option_2'] = $question->option_2;
            $questionArr['option_3'] = $question->option_3;
            $questionArr['option_4'] = $question->option_4;
            $questionArr['right_option'] = $question->right_option;
            $questionArr['difficulti_level'] = $question->difficulti_level;
            $exportArr[] = $questionArr;
        }

        $filedNames = array_keys($exportArr[0]);

        $fileName = 'questions_'.date('Y-m-d-H-i-s').'.xlsx';
        QuestionNotValid::truncate();
        return Excel::download(new UserExport($exportArr, $filedNames), $fileName);
    }

}


$data['exam_id'] = $exam_id;

return view('admin.exams.import', $data); 
}


public function get_questions(Request $request){

    $routeName = CustomHelper::getAdminRouteName();
    $exam_id = isset($request->exam_id) ? $request->exam_id : '';

    $datas = Question::where('is_delete',0)->orderBy('id','desc')->get();

    return Datatables::of($datas)


    ->editColumn('id', function(Question $data) {

        return  $data->id;
    })
    ->editColumn('question_name', function(Question $data) {
        $question_name =  mb_strlen(strip_tags($data->question_name),'utf-8') > 25 ? mb_substr(strip_tags($data->question_name),0,25,'utf-8').'...' : strip_tags($data->question_name);
        return  $question_name;
    })
    ->editColumn('option_1', function(Question $data) {
     $option_1 =  mb_strlen(strip_tags($data->option_1),'utf-8') > 25 ? mb_substr(strip_tags($data->option_1),0,25,'utf-8').'...' : strip_tags($data->option_1);
     return  $option_1;
 })
    ->editColumn('option_2', function(Question $data) {
     $option_2 =  mb_strlen(strip_tags($data->option_2),'utf-8') > 25 ? mb_substr(strip_tags($data->option_2),0,25,'utf-8').'...' : strip_tags($data->option_2);
     return  $option_2;


 })
    ->editColumn('option_3', function(Question $data) {
      $option_3 =  mb_strlen(strip_tags($data->option_3),'utf-8') > 25 ? mb_substr(strip_tags($data->option_3),0,25,'utf-8').'...' : strip_tags($data->option_3);
      return  $option_3;


  })
    ->editColumn('option_4', function(Question $data) {
      $option_4 =  mb_strlen(strip_tags($data->option_4),'utf-8') > 25 ? mb_substr(strip_tags($data->option_4),0,25,'utf-8').'...' : strip_tags($data->option_4);
      return  $option_4;


  })
    ->editColumn('right_option', function(Question $data) {

        $res ='';
        if($data->right_option == 1){
            $res = 'Option 1';
        }

        if($data->right_option == 2){
            $res = 'Option 2';
        }


        if($data->right_option == 3){
            $res = 'Option 3';
        }


        if($data->right_option == 4){
            $res = 'Option 4';
        }
        return $res;

    })
    
    ->editColumn('difficulti_level', function(Question $data) {


        $difficulti_level = '';
        if($data->difficulti_level == 1){
            $difficulti_level = 'Low';
        }
         if($data->difficulti_level == 2){
            $difficulti_level = 'Medium';
        }
         if($data->difficulti_level == 3){
            $difficulti_level = 'High';
        }


        return $difficulti_level;


    })
    
    
    ->editColumn('status', function(Question $data) {
     $sta = '';
     $sta1 ='';
     if($data->status == 1){
        $sta1 = 'selected';
    }else{
        $sta = 'selected';
    }

    $html = "<select id='change_status$data->id' onchange='change_status($data->id)'>
    <option value='1' ".$sta1.">Active</option>
    <option value='0' ".$sta.">InActive</option>
    </select>";

    return  $html;
})
    ->editColumn('created_at', function(Question $data) {
        return  date('d M Y',strtotime($data->created_at));
    })

    ->addColumn('action', function(Question $data) {

      $routeName = CustomHelper::getAdminRouteName();

      $BackUrl = $routeName.'/questions';

      return '<a title="Edit" href="' . route($routeName.'.questions.edit_question',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;
      ';
  })

    ->rawColumns(['name', 'status', 'action','image'])
    ->toJson();
}


public function add_question(Request $request){
    $exam_id  = isset($request->exam_id) ? $request->exam_id :'';
    $data = [];
    $data['exam_id'] = $exam_id;
    $data['question_id'] = '';
    $question_id ='';
    $back_url = $request->back_url;
    if(empty($request->back_url)){
        $back_url = $this->ADMIN_ROUTE_NAME.'/questions';

    }
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){

        $rules = [];
        $rules['question_name'] = 'required';
        $rules['option_1'] = 'required';
        $rules['option_2'] = 'required';
        $rules['option_3'] = 'required';
        $rules['option_4'] = 'required';
        $rules['right_option'] = 'required';
        $rules['difficulti_level'] = 'required';
        $rules['status'] = 'required';

        $this->validate($request, $rules);
        $createdQuestion = $this->save_questions($request,$exam_id,$question_id);
        if ($createdQuestion) {
            $alert_msg = 'Question has been Updated successfully.';
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Question';
    $data['page_heading'] = $page_heading;




    return view('admin.exams.question_form', $data); 


}
public function save_questions($request,$exam_id='',$question_id=''){
            //////UPDATE
    $dbArr = [];
        if($question_id !=''){
            $dbArr['question_name'] = $request->question_name;
            $dbArr['option_1'] = $request->option_1;
            $dbArr['option_2'] = $request->option_2;
            $dbArr['option_3'] = $request->option_3;
            $dbArr['option_4'] = $request->option_4;
            $dbArr['right_option'] = $request->right_option;
            $dbArr['difficulti_level'] = $request->difficulti_level;
            $dbArr['status'] = $request->status;
            $dbArr['is_delete'] = 0;

            $success = Question::where('id',$question_id)->update($dbArr);
            if($success){
                return true;
            }else{
                return false;
            }

        }else{
            ///////Create

            $dbArr['question_name'] = $request->question_name;
            $dbArr['exam_id'] = $exam_id;
            $dbArr['option_1'] = $request->option_1;
            $dbArr['option_2'] = $request->option_2;
            $dbArr['option_3'] = $request->option_3;
            $dbArr['option_4'] = $request->option_4;
            $dbArr['right_option'] = $request->right_option;
            $dbArr['difficulti_level'] = $request->difficulti_level;
            $dbArr['status'] = $request->status;
            $dbArr['is_delete'] = 0;

            $success = Question::create($dbArr);
            if($success){
                return true;
            }else{
                return false;
            }

        }

}




public function edit_question(Request $request){

 $question_id = isset($request->question_id) ? $request->question_id :'';

 $data = [];

 $question = Question::where('id',$question_id)->first();


 $data['exam_id'] = $question->exam_id;
 $data['question_id'] = $question_id;
 $data['question'] = $question;
$back_url = $request->back_url;
if(empty($request->back_url)){
    $back_url = $this->ADMIN_ROUTE_NAME.'/questions';

}
 $method = $request->method();
 if($method == 'post' || $method == 'POST'){

    $rules = [];
    $rules['question_name'] = 'required';
    $rules['option_1'] = 'required';
    $rules['option_2'] = 'required';
    $rules['option_3'] = 'required';
    $rules['option_4'] = 'required';
    $rules['right_option'] = 'required';
    $rules['difficulti_level'] = 'required';
    $rules['status'] = 'required';

    $this->validate($request, $rules);
    $createdQuestion = $this->save_questions($request,$question->exam_id,$question_id);
     if ($createdQuestion) {
            $alert_msg = 'Question has been added successfully.';
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }



}









$page_heading = 'Update Question';
$data['page_heading'] = $page_heading;




return view('admin.exams.question_form', $data); 

}




public function change_status(Request $request){

  $que_id = isset($request->que_id) ? $request->que_id :'';

  $status = isset($request->status) ? $request->status :'';

  $users = Question::where('id',$que_id)->first();

  if(!empty($users)){

     Question::where('id',$que_id)->update(['status'=>$status]);

     $response['success'] = true;

     $response['message'] = 'Status updated';

     return response()->json($response);

 }else{

     $response['success'] = false;

     $response['message'] = 'Not Found';

     return response()->json($response);

 }

}











}