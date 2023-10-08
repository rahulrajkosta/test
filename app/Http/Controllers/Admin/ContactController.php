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

use App\Contact;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class ContactController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
		$contacts = Contact::latest()->paginate(10);
        $data['contacts'] = $contacts;
        return view('admin.contacts.index',$data);
    }

public function change_contacts_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $contacts = Contact::where('id',$id)->first();
  if(!empty($contacts)){

   Contact::where('id',$id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}


   
}




