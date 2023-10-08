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
use App\State;
use App\City;
use App\Roles;
use App\SocietyDocument;
use Yajra\DataTables\DataTables;
use Storage;
use DB;
use Hash;



Class TeleCallerController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        date_default_timezone_set("Asia/Kolkata");

	}



	public function admins_list(Request $request){
     $data =[];
     $role_ids = Roles::where('parent_id',0)->pluck('id')->toArray();
     $search = $request->search??'';

     $method = $request->method();
     if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['admin_id'] = 'required';
        $rules['remarks'] = 'required';
        $this->validate($request,$rules);
        $dbArray = [];
        $dbArray['admin_id'] = $request->admin_id??'';
        $dbArray['remarks'] = $request->remarks??'';
        $dbArray['date_time'] = date('Y-m-d H:i:s');
        DB::table('telecaller_remarks')->insert($dbArray);
         return back()->with('alert-success', 'Remarks Added Successfully');

     }


     $admins = Admin::where('is_delete',0)->whereNotIn('role_id',$role_ids)->orderBy('id','desc');
     if(!empty($search)){
        $admins->where('name', 'like', '%' . $search . '%');
        $admins->orWhere('phone', 'like', '%' . $search . '%');
        $admins->orWhere('email', 'like', '%' . $search . '%');
     }
     $admins = $admins->paginate(10);
     $data['admins'] = $admins;
     return view('admin.telecaller.index',$data);
 }



public function telecaller_remarks(Request $request){
     $data =[];
     $search = $request->search??'';

     $admins = DB::table('telecaller_remarks')->orderBy('id','desc');
     $admins = $admins->paginate(10);
     $data['admins'] = $admins;
     return view('admin.telecaller.remarks',$data);
 }



}