<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Country;
use App\State;

use Validator;
use Storage;

use App\Helpers\CustomHelper;

use Image;
use DB;

class StateController extends Controller
{

    private $limit;
    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->limit = 100;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {

        $data = [];
        $d_query = State::where('is_delete',0)->latest();        

        if(!empty($name)){
            $d_query->where(function($query) use($name)
            {
                $query->where('name', 'like', '%'.$name.'%');
                //$query->orWhere('nicename', 'like', '%'.$name.'%');
            });
        }      

        $states = $d_query->paginate(10);
        $data['states'] = $states;        
        // $data['limit'] = $limit;
        return view('admin.states.index', $data);

    }

    public function add(Request $request)
    {
        $details = [];    
        $id = isset($request->id) ? $request->id : 0;
        $states = '';
        if(is_numeric($id) && $id > 0)
        {
             $states = State::find($id);
            if(empty($states))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/states');
            }
        } 
        if($request->method() == "POST" || $request->method() == "post")
        {
            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/states';
            }
            if(is_numeric($request->id) && $request->id > 0)
            {
              $details['name'] = 'required';           

            }else{

                 $details['name'] = 'required';                              
            }
            $this->validate($request , $details); 
           $createdDetails = $this->save($request , $id);
           if($createdDetails)
           {
              $alert_msg = "State Created Successfully";
                if(is_numeric($id) & $id > 0)
                {
                    $alert_msg = "State Updated Successfully";
                } 
                return redirect(url($back_url))->with('alert-success',$alert_msg);
           }else{
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
           }
        }
        $page_Heading = "Add State";
        if(isset($states->id))
        {
            $state_name = $states->name;
            $page_Heading = 'Update -'.$state_name;
        }
        $countries = Country::where('status', '1')->get();

        $details['page_Heading'] = $page_Heading;
        $details['id'] = $id;
        $details['states'] = $states;
        $details['countries'] = $countries;
        return view('admin.states.form',$details);
    }

     public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);
        $states = new State;

        if(is_numeric($id) && $id > 0)
        {
            $exist = State::find($id);

            if(isset($exist->id) && $exist->id == $id)
            {   
                $states = $exist;
            }
        }
        foreach($details as $key => $val)
        {
            $states->$key = $val;
        }
        $isSaved = $states->save();
        return $isSaved;
    }

    public function delete(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;    

         $is_delete = 0;
         if(empty($back_url))
        {
            $back_url = $this->ADMIN_ROUTE_NAME.'/states';
        }
         if(is_numeric($id) && $id > 0)
         {          

             $is_delete = State::where('id', $id)->update(['is_delete'=> '1']);

         }       
         if(!empty($is_delete))
         {
            return back()->with('alert-success', 'State Deleted Successfully');
         }else{
            return back()->with('alert-danger', 'something went wrong, please try again...');
         }    
    }
   

    // public function saveImages($files, $ext='jpg,jpeg,png,gif')
    // {

    //     $filename = '';

    //     $path = 'states/';
    //     $thumb_path = 'states/thumb/';

    //     $IMG_WIDTH = 1600;
    //     $IMG_HEIGHT = 640;
    //     $THUMB_WIDTH = 400;
    //     $THUMB_HEIGHT = 400;

    //     $images_data = [];

    //     $upload_result = CustomHelper::UploadImage($files, $path, $ext, $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

    //         if($upload_result['success']){
               
    //             $filename = $upload_result['file_name'];
    //         }

       
    //     return $filename;

    // }

}