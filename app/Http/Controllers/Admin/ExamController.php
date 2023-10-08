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
use App\Rank;
use App\Question;
use App\Subject;
use App\Topic;
use Yajra\DataTables\DataTables;


use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

use App\Imports\QuestionImport;
use App\Exports\UserExport;

use Maatwebsite\Excel\Facades\Excel;

use App\QuestionNotValid;

use Storage;
use DB;
use Hash;



Class ExamController extends Controller
{


	private $ADMIN_ROUTE_NAME;

    public function __construct(){

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


    }



    public function index(Request $request){
     $data = [];
     $data['exams'] = Exam::where('is_delete',0)->paginate(10);
     return view('admin.exams.index',$data);
 }



public function results(Request $request){
 $data = [];

 $exam_id = isset($request->exam_id) ? $request->exam_id: 0;

 $exam = Exam::where('id',$exam_id)->first();
 $rank = Rank::select('id')->where('exam_id',$exam_id)->get();

$results = Rank::where('exam_id',$exam_id)->orderBy('rank','asc')->paginate(10);
 $data['exam'] = $exam;
 $data['results'] = $results;

 $data['rank'] = $rank;

 return view('admin.exams.results',$data);
}


public function add(Request $request){
    $data = [];

    $id = (isset($request->id))?$request->id:0;

    $exams = '';
    if(is_numeric($id) && $id > 0){
        $exams = Exam::find($id);
        if(empty($exams)){
            return redirect($this->ADMIN_ROUTE_NAME.'/exams');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/exams';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];
        $rules['title'] = 'required';
        $rules['start_date'] = 'required';
        $rules['start_time'] = 'required';
        
        if($request->key == 'course'){

        }
        if($request->key == 'topic'){

        }
        
        


        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Exam has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Exam has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Exam';

    if(isset($exams->title)){
        $exams_name = $exams->title;
        $page_heading = 'Update Exam - '.$exams_name;
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['exams'] = $exams;

    $categories = Category::where('status',1)->where('is_delete',0)->get();
    $courses =[];
    $subjects =[];
    $topics =[];

    if(is_numeric($id) && $id > 0){
    $courses = Course::where('category_id',$exams->category_id)->get();  
    $topics = Topic::where('subject_id',$exams->subject_id)->get();

    }



    $data['categories'] = $categories;
     $data['courses'] = $courses;
   
     $data['topics'] = $topics;



    return view('admin.exams.form', $data);

}

public function send_new_exam_sms(){

    $users = User::where('status',1)->get();
    if(!empty($users)){
     foreach($users as $user){
        $mobile = isset($user->phone) ? $user->phone :'';
        if(!empty($mobile)){
            // $message = 'Hi! '.$user->name.'New Exam Created';
            $otp = 1234;
            $message = $otp." is your authentication Code to register.";;
            CustomHelper::send_message($mobile,$message);
        }
    } 
}


}

public function send_new_exam_email(){
    $users = User::where('status',1)->get();
    if(!empty($users)){
     foreach($users as $user){
        $email = isset($user->email) ? $user->email :'';
        if(!empty($email)){
            $message = 'Hi! '.$user->name.'New Exam Created';
            $subject = 'New Exam Created';
            $fromEmail = 'stpaul@gmail.com';
            $toEmail = $email;
            $data['name'] = $user->name;
            CustomHelper::sendEmail('emails.exam_create',$data, $toEmail, $fromEmail,$fromEmail, $subject);
        }
    } 
}

}





public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    if($id == 0){
        // $this->send_new_exam_sms();
        // $this->send_new_exam_email();
    }

    $oldImg = '';

    $exams = new Exam;

    if(is_numeric($id) && $id > 0){
        $exist = Exam::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $exams = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $exams->$key = $val;
    }

    $isSaved = $exams->save();

    // if($isSaved){
    //     $this->saveImage($request, $exams, $oldImg);
    // }

    return $isSaved;
}


private function saveImage($request, $course, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'course/';
        $thumb_path = 'course/thumb/';
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

            $course->image = $image;
            $course->save();         
        }

        if(!empty($uploaded_data)){   
            return $uploaded_data;
        }  

    }

}




public function delete(Request $request){

        //prd($request->toArray());

    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = Exam::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Exams has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}


public function change_status(Request $request){
  $exam_id = isset($request->exam_id) ? $request->exam_id :'';
  $status = isset($request->status) ? $request->status :'';
  $users = Exam::where('id',$exam_id)->first();
  if(!empty($users)){
   Exam::where('id',$exam_id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';
   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not Found';
   return response()->json($response);
}
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

    $sucess = Excel::import(new QuestionImport($exam_id),request()->file('importfile'));

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

$allquestions = Question::where('exam_id',$exam_id)->orderBy('id','desc');

 $courses = Course::where(['is_delete'=>0,'status'=>1])->get();

 
 $allocated_exam = DB::table('allocate_exam')->select('exam_id','course_id')->where('exam_id',$exam_id)->get();

 $course_Ids = [];
 if(!empty($allocated_exam))
 {
    foreach($allocated_exam as $allocate){
        $course_Ids[] = $allocate->course_id;
       
    }
 }

 $get_course = Course::where(['is_delete'=>0,'status'=>1,])->orderBy('id','desc');

 if(!empty($course_Ids)){

   $get_course->whereNotIn('id',$course_Ids);

 }
$get_course = $get_course->get();
 

$allquestions = $allquestions->paginate(10);
$data['allquestions'] = $allquestions;
$data['exam_id'] = $exam_id;
$data['get_course'] = $get_course;
$data['allocated_exam'] = $allocated_exam;

return view('admin.exams.import', $data); 
}


public function get_exam_question(Request $request){

    $routeName = CustomHelper::getAdminRouteName();
    $exam_id = isset($request->exam_id) ? $request->exam_id : '';

    $datas = Question::where('exam_id',$exam_id)->orderBy('id','desc')->get();

    return Datatables::of($datas)


    ->editColumn('id', function(Question $data) {

        return  $data->id;
    })
    ->editColumn('question_name', function(Question $data) {
        $question_name =  mb_strlen(strip_tags($data->question_name),'utf-8') > 50 ? mb_substr(strip_tags($data->question_name),0,50,'utf-8').'...' : strip_tags($data->question_name);
        return  $question_name;
    })
    ->editColumn('option_1', function(Question $data) {
       $option_1 =  mb_strlen(strip_tags($data->option_1),'utf-8') > 50 ? mb_substr(strip_tags($data->option_1),0,50,'utf-8').'...' : strip_tags($data->option_1);
       return  $option_1;
   })
    ->editColumn('option_2', function(Question $data) {
       $option_2 =  mb_strlen(strip_tags($data->option_2),'utf-8') > 50 ? mb_substr(strip_tags($data->option_2),0,50,'utf-8').'...' : strip_tags($data->option_2);
       return  $option_2;


   })
    ->editColumn('option_3', function(Question $data) {
      $option_3 =  mb_strlen(strip_tags($data->option_3),'utf-8') > 50 ? mb_substr(strip_tags($data->option_3),0,50,'utf-8').'...' : strip_tags($data->option_3);
      return  $option_3;


  })
    ->editColumn('option_4', function(Question $data) {
      $option_4 =  mb_strlen(strip_tags($data->option_4),'utf-8') > 50 ? mb_substr(strip_tags($data->option_4),0,50,'utf-8').'...' : strip_tags($data->option_4);
      return  $option_4;


  })
    ->editColumn('right_option', function(Question $data) {
        return $data->right_option;

    })
    
    ->editColumn('difficulti_level', function(Question $data) {
        return $data->difficulti_level;


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

      $BackUrl = $routeName.'/exams/import/'.$data->exam_id;

      return '<a title="Edit" href="' . route($routeName.'.exams.edit_question',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;
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
        $back_url = $this->ADMIN_ROUTE_NAME.'/exams/import/'.$question->exam_id;

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
    $back_url = $this->ADMIN_ROUTE_NAME.'/exams/import/'.$question->exam_id;

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




public function import_text()
    {
        $questions = [];
        $i = 0;
        $questionsFile = fopen('test.doc', 'r');
        while ($line = fgets($questionsFile)) {
            $questionArr = [];
            if ($line === "\n") {
                $i++;
                continue;
            }
            if (!isset($questions['questions'][$i])){
                $questions['questions'][$i] = [
                    'id' => rand(1596805341210, 9999999999999),
                    'type' => 'Multiple Choice Single Answer',
                    'question' => '',
                    'answer_options' => []
                ];
            }

            if (preg_match("/(.)+\?/", $line)) {
                $questions['questions'][$i]['question'] = $line;
            } else {
                $answer = [
                    'id' => rand(1596805341210, 9999999999999),
                    'marks' => null,
                    'value' => $line,
                    'selected' => false,
                ];
                if (preg_match("/(\*)(.)+/", $line)) {
                    $line= str_replace('*','',$line);
                    $answer['value'] = $line;
                    $answer['marks'] = 100;
                    $answer['selected'] = true;
                }
                $questions['questions'][$i]['answer_options'][] = $answer;
            }

            $questionArr[] = $questions;
        }
      
pr($questionArr);


         
    }


public function allocate_exam(Request $request)
{
   // print_r($_POST);
    $details = [];
    $course_id = $request->course_id;
    
    $exam_id = $request->exam_id;
    foreach($course_id as $c)
    {
         $details['course_id'] = $c;
        $details['exam_id'] = $exam_id;
         DB::table('allocate_exam')->insert($details);
    }

    return back();


  
  

}











}