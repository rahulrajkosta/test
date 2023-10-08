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
use App\Form;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class DGLController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

	public function index(Request $request)
    {
		$dgl_forms = Form::where('type','dgl')->orderBy('id','desc')->paginate(10);
        $data['dgl_forms'] = $dgl_forms;

        return view('admin.dgl_forms.index',$data);
    }

    
    public function details(Request $request){
        $data = [];
        $id = isset($request->id) ? $request->id :'';
        $dgl_forms = Form::where('type','dgl')->where('id',$id)->first();
        $data['dgl_forms'] = $dgl_forms;
        
        return view('admin.dgl_forms.details',$data);
    }
   
}




