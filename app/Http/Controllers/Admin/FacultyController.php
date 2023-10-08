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
use App\Faculty;

use App\Category;
use App\City;
use App\SubCategory;
use App\Blocks;
use App\Flats;
use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class FacultyController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        $faculties = Admin::where(['role_id'=>1,'is_delete'=>0])->paginate(10);


        $data['faculties'] = $faculties;
        return view('admin.faculties.index', $data);
    }


    public function add(Request $request)
    {


        $id = isset($request->id) ? $request->id : 0;

        $faculties = '';

        if (is_numeric($id) && $id > 0) {
            $faculties = Admin::find($id);
            if (empty($faculties)) {
                return redirect($this->ADMIN_ROUTE_NAME . '/faculties');
            }
        }


        if ($request->method() == "POST" || $request->method() == "post") {

            // prd($request->toArray());

            if (empty($back_url)) {
                $back_url = $this->ADMIN_ROUTE_NAME . '/faculties';
            }


            $details = [];

            if (is_numeric($request->id) && $request->id > 0) {
                $details['name'] = 'required';
                $details['phone'] = 'required';
                $details['username'] = 'required';
                $details['email'] = 'required';
                $details['education'] = 'required';
                $details['total_exp'] = 'required';
                $details['speciality'] = 'required';
                $details['status'] = 'required';
                $details['is_approve'] = 'required';

            } else {

                $details['name'] = 'required';
                $details['phone'] = 'required|unique:admins,phone';
                $details['email'] = 'required|unique:admins,email';
                $details['username'] = 'required|unique:admins,username';
                $details['password'] = 'required';
                $details['education'] = 'required';
                $details['total_exp'] = 'required';
                $details['speciality'] = 'required';
                $details['status'] = 'required';
                $details['is_approve'] = 'required';
            }

            $this->validate($request, $details);

            $createdDetails = $this->save($request, $id);

            if ($createdDetails) {
                $alert_msg = "Facutly Created Successfully";

                if (is_numeric($id) & $id > 0) {
                    $alert_msg = "Facutly Updated Successfully";
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {

                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }

        }

        $page_heading = "Add Faculty";

        if (is_numeric($id) && $id > 0) {
            $faculty_name = $faculties->name;
            $page_heading = 'Update Faculty -' . $faculty_name;
        }
        $details['page_heading'] = $page_heading;
        $details['id'] = $id;
        $details['faculties'] = $faculties;

        return view('admin.faculties.form', $details);

    }


    public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);
        $details['status'] = 1;
        $details['is_approve'] = 1;
        $details['role_id'] = 1;
        if(!empty($request->password)){
            $details['password'] = bcrypt($request->password);
        }
        $old_img = '';
        $faculties = new Admin;
        if (is_numeric($id) && $id > 0) {
            $exist = Admin::find($id);
            if (isset($exist->id) && $exist->id == $id) {
                $faculties = $exist;
                $old_img = $exist->image;
            }
        }

        foreach ($details as $key => $val) {
            $faculties->$key = $val;
        }

        $isSaved = $faculties->save();

        if ($isSaved) {
            $this->saveImage($request, $faculties, $old_img);
        }

        return $isSaved;
    }

    private function saveImage($request, $faculties, $oldImg = '')
    {
        $file = $request->file('image');
        if ($file) {
            $path = 'faculties/';
            $thumb_path = 'faculties/thumb/';
            $storage = Storage::disk('public');
            //prd($storage);
            $IMG_WIDTH = 768;
            $IMG_HEIGHT = 768;
            $THUMB_WIDTH = 336;
            $THUMB_HEIGHT = 336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext = '', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb = true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

            // prd($uploaded_data);
            if ($uploaded_data['success']) {

                if (!empty($oldImg)) {
                    if ($storage->exists($path . $oldImg)) {
                        $storage->delete($path . $oldImg);
                    }
                    if ($storage->exists($thumb_path . $oldImg)) {
                        $storage->delete($thumb_path . $oldImg);
                    }
                }
                $image = $uploaded_data['file_name'];

                // prd($image);
                $faculties->image = $image;
                $faculties->save();
            }

            if (!empty($uploaded_data)) {
                return $uploaded_data;
            }

        }

    }

    public function change_faculty_status(Request $request)
    {
        $id = isset($request->id) ? $request->id : '';
        $status = isset($request->status) ? $request->status : '';

        $faculties = Admin::where('id', $id)->first();
        if (!empty($faculties)) {

            Admin::where('id', $id)->update(['status' => $status]);
            $response['success'] = true;
            $response['message'] = 'Status updated';


            return response()->json($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Not  Found';
            return response()->json($response);
        }

    }

    public function delete(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;


        $is_delete = 0;

        if (empty($back_url)) {
            $back_url = $this->ADMIN_ROUTE_NAME . '/faculties';
        }

        if (is_numeric($id) && $id > 0) {
            //echo $id;
            $is_delete = Admin::where('id', $id)->update(['is_delete' => '1']);
        }

        //die;

        if (!empty($is_delete)) {
            return back()->with('alert-success', 'Faculty Deleted Successfully');
        } else {

            return back()->with('alert-danger', 'something went wrong, please try again...');
        }

    }
}



