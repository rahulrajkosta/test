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
use App\Blocks;
use App\Permission;
use App\Roles;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class PermissionController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


	}



	public function index(Request $request){

        $method = $request->method();
        $roles = Roles::where('status',1)->get();


        if($method == 'post' || $method == 'POST'){
            $rules = [];
            $rules['section_name'] = 'required';

            $this->validate($request, $rules);
            if(!empty($roles)){
                foreach($roles as $role){
                    $data = $request->toArray();
                    $dbArray = [];
                    $dbArray['section_name'] = $request->section_name;
                    $dbArray['role_id'] = $role->id;
                    $dbArray['add_section'] = isset($data['add_section'.$role->id]) ? $data['add_section'.$role->id] :0;
                    $dbArray['edit_section'] =isset($data['edit_section'.$role->id]) ? $data['edit_section'.$role->id] :0;
                    $dbArray['delete_section'] = isset($data['delete_section'.$role->id]) ? $data['delete_section'.$role->id] :0;
                    $dbArray['show_section'] = isset($data['show_section'.$role->id]) ? $data['show_section'.$role->id] :0;


                    $exists = Permission::where('role_id',$role->id)->where('section_name',$request->section_name)->first();
                    if(!empty($exists)){
                    $sucess = Permission::where('role_id',$role->id)->where('section_name',$request->section_name)->update($dbArray);

                    
                    
                    }else{
                    $sucess = Permission::create($dbArray);

                    }
                }
            }

        }





		$roles = Roles::where('status',1)->get();
		$data['roles'] = $roles;
        $modules = config('modules.allowed');
        $allowedwithval = config('modules.allowedwithval');

        $data['modules'] = $modules;
        $data['allowedwithval'] = $allowedwithval;



            // print_r($data);
		return view('admin.permission.index',$data);
	}

	public function get_roles(Request $request){
		$routeName = CustomHelper::getSadminRouteName();
		$datas = Roles::orderBy('id','desc')->get();

		return Datatables::of($datas)


		->editColumn('id', function(Roles $data) {

			return  $data->id;
		})
		->editColumn('name', function(Roles $data) {
			return  $data->name;
		})
		->editColumn('status', function(Roles $data) {
			$sta = '';
			$sta1 ='';
			if($data->status == 1){
				$sta1 = 'selected';
			}else{
				$sta = 'selected';
			}

			$html = "<select id='change_role_status$data->id' onchange='change_role_status($data->id)'>
			<option value='1' ".$sta1.">Active</option>
			<option value='0' ".$sta.">InActive</option>
			</select>";
            
			return  $html;
		})

		->editColumn('created_at', function(Roles $data) {
			return  date('d M Y',strtotime($data->created_at));
		})

		->addColumn('action', function(Roles $data) {
			$routeName = CustomHelper::getAdminRouteName();

			$BackUrl = $routeName.'/roles';
			return '<a title="Edit" href="' . route($routeName.'.roles.edit',$data->id.'?back_url='.$BackUrl) . '"><i class="fa fa-edit">Edit</i></a>&nbsp;&nbsp;&nbsp;
			';
		})

		->rawColumns(['name', 'status', 'action'])
		->toJson();
	}




public function add(Request $request){
    $data = [];

    $id = (isset($request->id))?$request->id:0;

    $roles = '';
    if(is_numeric($id) && $id > 0){
        $roles = Roles::find($id);
        if(empty($roles)){
            return redirect($this->ADMIN_ROUTE_NAME.'/roles');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/roles';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];

        $rules['name'] = 'required';
        
        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Roles has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Roles has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Role';

    if(isset($roles->title)){
        $roles_name = $roles->title;
        $page_heading = 'Update Roles - '.$roles_name;
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['roles'] = $roles;

    return view('admin.roles.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    $oldImg = '';

    $roles = new Roles;

    if(is_numeric($id) && $id > 0){
        $exist = Roles::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $roles = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $roles->$key = $val;
    }

    $isSaved = $roles->save();

    if($isSaved){
        $this->saveImage($request, $roles, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $blockes, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'blockes/';
        $thumb_path = 'blockes/thumb/';
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
            $blockes->image = $image;
            $blockes->save();         
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
        $is_delete = Roles::where('id', $id)->delete();
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Roles has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



	public function change_role_status(Request $request){
		$role_id = isset($request->role_id) ? $request->role_id :'';
		$status = isset($request->status) ? $request->status :'';

		$roles = Roles::where('id',$role_id)->first();
		if(!empty($roles)){

			Roles::where('id',$role_id)->update(['status'=>$status]);
			$response['success'] = true;
			$response['message'] = 'Status updated';


			return response()->json($response);
		}else{
			$response['success'] = false;
			$response['message'] = 'No Roles FOund';
			return response()->json($response);
		}


	}


}