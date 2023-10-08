<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;
use Artisan;
use app\http\controllers\ReportController;
use App\Http\Controllers\ExcelReportController;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Http\Controllers\KoolReportController;

Route::get('/generate-excel-report', [KoolReportController::class, 'generateExcelReport']);


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





// Route::get('/', function () {
//     return view('welcome');
// });

//Route::any('/', 'HomeController@index');
///////////////////////////////////SADMIN/////////////////////////////////////////

// $SADMIN_ROUTE_NAME = CustomHelper::getSadminRouteName();

//Route::get('/generate-excel-report', [ExcelReportController::class, 'generateExcelReport']);
Route::match(['get', 'post'], 'get_city', 'Admin\HomeController@get_city')->name('get_city');
Route::match(['get', 'post'], 'get_state', 'Admin\HomeController@get_state')->name('get_state');
Route::match(['get', 'post'], 'birth_day_email', 'Admin\HomeController@birth_day_email')->name('birth_day_email');


Route::post('update_status', 'Admin\CourseController@update_status')->name('update_status');
Route::match(['get','post'],'/report', "ReportController@index");
Route::get('/my-report', 'ReportController@generateExcelReport')->name('my-report');
Route::get('/my-report/export', 'ReportController@export')->name('my-report-export');
Route::match(['get','post'], 'export_reports', 'Admin\ReportController@export_reports')->name('.export_reports');
Route::match(['get','post'], 'export_reports_tsm', 'Admin\ReportController@export_reports_tsm')->name('.export_reports_tsm');
Route::match(['get','post'], 'all_coupon_allocation_test', 'Admin\ReportController@all_coupon_allocation_test')->name('.all_coupon_allocation_test');
Route::match(['get','post'], 'all_coupon_allocation', 'Admin\ReportController@all_coupon_allocation')->name('.all_coupon_allocation');

Route::match(['get','post'], 'role_coupon_allocation', 'Admin\ReportController@role_coupon_allocation')->name('.role_coupon_allocation');


////////////////////////////////////////ADMIN//////////////////////////////////////////

Route::match(['get', 'post'], '/user-logout', 'Auth\LoginController@logout');


$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();





Route::get('google_login', 'Admin\LoginController@loginWithGoogle')->name('login');
Route::any('google_callback', 'Admin\LoginController@callbackFromGoogle')->name('google.callback');













/////Login
Route::match(['get', 'post'], '/', 'Admin\LoginController@index')->name('admin.login');

Route::match(['get', 'post'], 'admin/logout', 'Admin\LoginController@logout');

/////Register


Route::match(['get', 'post'], 'admin/register', 'Admin\LoginController@register')->name('admin.register');
Route::match(['get', 'post'],'admin/verify_otp', 'Admin\LoginController@verify_otp')->name('admin.verify_otp');


/////Forgot Password
Route::match(['get', 'post'], 'admin/forgot-password', 'Admin\LoginController@forgot')->name('admin.forgot');
Route::match(['get', 'post'], 'admin/reset', 'Admin\LoginController@reset')->name('admin.reset');



// Admin
Route::group(['namespace' => 'Admin', 'prefix' => $ADMIN_ROUTE_NAME, 'as' => $ADMIN_ROUTE_NAME.'.', 'middleware' => ['authadmin']], function() {
    Route::get('/',  'HomeController@index')->name('home');
  
    Route::get('/',  'HomeController@index')->name('.index');


// Route::get('/', function () {
//    return view('welcome');
// });


    Route::get('/logout', 'LoginController@logout')->name('logout');


    Route::match(['get','post'],'/profile', 'HomeController@profile')->name('profile');
    Route::match(['get','post'],'/change_password', 'HomeController@change_password')->name('change_password');
    Route::match(['get','post'],'/get_childs', 'PartyUserController@get_childs')->name('get_childs');
    Route::match(['get','post'],'/permission', 'HomeController@permission')->name('permission');
    Route::match(['get','post'],'/update_permission', 'HomeController@update_permission')->name('update_permission');
    
    Route::match(['get','post'],'/setting', 'HomeController@setting')->name('setting');


    Route::match(['get','post'],'/upload_xls', 'HomeController@upload_xls')->name('upload_xls');

    Route::match(['get','post'],'/upload_video', 'HomeController@upload_video')->name('upload_video');
    Route::match(['get','post'],'/lock_phone', 'HomeController@lock_phone')->name('lock_phone');
    Route::match(['get','post'],'/check_lock_status', 'HomeController@check_lock_status')->name('check_lock_status');
    Route::match(['get','post'],'/update_screenshot', 'HomeController@update_screenshot')->name('update_screenshot');
    Route::match(['get','post'],'/temporary_lock_phone', 'HomeController@temporary_lock_phone')->name('temporary_lock_phone');


    Route::match(['get','post'],'/get_blocks', 'HomeController@get_blocks')->name('get_blocks');
    Route::match(['get','post'],'/get_flats', 'HomeController@get_flats')->name('get_flats');


    Route::match(['get','post'],'/import_text', 'ExamController@import_text')->name('import_text');


    Route::match(['get','post'],'/getparent_coupons', 'PartyUserController@getparent_coupons')->name('getparent_coupons');
    Route::match(['get','post'],'/return_coupons', 'PartyUserController@return_coupons')->name('return_coupons');
        




        

    Route::match(['get','post'],'/upload', 'HomeController@upload')->name('upload');

    Route::match(['get','post'],'/change-password', 'HomeController@change_password')->name('change_password');

    // Route::get('/',  'HomeController@index')->name('home');
    // Route::get('/',  'HomeController@index')->name('index');

    Route::match(['get','post'],'get_sub_cat', 'HomeController@get_sub_cat')->name('get_sub_cat');


    // roles
    Route::group(['prefix' => 'roles', 'as' => 'roles' , 'middleware' => ['allowedmodule:roles,list'] ], function() {

        Route::get('/', 'RoleController@index')->name('.index');

        Route::match(['get', 'post'], 'add', 'RoleController@add')->name('.add');

        Route::match(['get', 'post'], 'get_roles', 'RoleController@get_roles')->name('.get_roles');

        Route::match(['get', 'post'], 'change_role_status', 'RoleController@change_role_status')->name('.change_role_status');
        Route::match(['get', 'post'], 'edit/{id}', 'RoleController@add')->name('.edit');

        Route::post('ajax_delete_image', 'RoleController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'RoleController@delete')->name('.delete');
    });



     // roles
    Route::group(['prefix' => 'migration', 'as' => 'migration' , 'middleware' => ['allowedmodule:migration,list'] ], function() {
        Route::get('/index', 'MigrationController@index')->name('.index');

        Route::get('/super_distributor', 'MigrationController@super_distributor')->name('.super_distributor');
        Route::get('/distributor', 'MigrationController@distributor')->name('.distributor');
        Route::get('/tsm', 'MigrationController@tsm')->name('.tsm');
        Route::get('/seller', 'MigrationController@seller')->name('.seller');
        Route::get('/super_distributor_coupons', 'MigrationController@super_distributor_coupons')->name('.super_distributor_coupons');
        Route::get('/distributor_coupons', 'MigrationController@distributor_coupons')->name('.distributor_coupons');
        Route::get('/tsm_coupons', 'MigrationController@tsm_coupons')->name('.tsm_coupons');
        Route::get('/seller_coupons', 'MigrationController@seller_coupons')->name('.seller_coupons');
        Route::get('/update_no_of_coupons', 'MigrationController@update_no_of_coupons')->name('.update_no_of_coupons');
        Route::get('/update_coupon_transaction', 'MigrationController@update_coupon_transaction')->name('.update_coupon_transaction');
        Route::get('/import_users', 'MigrationController@import_users')->name('.import_users');
        Route::get('/import_user_logins', 'MigrationController@import_user_logins')->name('.import_user_logins');
        Route::get('/import_mobile_details', 'MigrationController@import_mobile_details')->name('.import_mobile_details');
        Route::get('/update_is_used', 'MigrationController@update_is_used')->name('.update_is_used');
        Route::get('/update_emis', 'MigrationController@update_emis')->name('.update_emis');

    });





    Route::group(['prefix' => 'faculties' , 'as' => 'faculties', 'middleware' => ['allowedmodule:faculties,list'] ],  function() {

     Route::get('/', 'FacultyController@index')->name('.index');

     Route::match(['get','post'], 'get_faculty', 'FacultyController@get_faculty')->name('.get_faculty');

     Route::match(['get','post'], 'change_faculty_status', 'FacultyController@change_faculty_status')->name('.change_faculty_status');

     Route::match(['get','post'], 'edit/{id}' ,'FacultyController@add')->name('.edit');

     Route::match(['get','post'], 'get_profile' , 'FacultyController@get_profile')->name('.get_profile');

     Route::match(['get','post'], 'add', 'FacultyController@add')->name('.add');

     Route::match(['get','post'], 'delete/{id}', 'FacultyController@delete')->name('.delete');




 });



Route::group(['prefix' => 'invoice' , 'as' => 'invoice', 'middleware' => ['allowedmodule:invoice,list'] ],  function() {
     Route::get('/', 'InvoiceController@index')->name('.index');
     Route::match(['get','post'], 'edit/{id}' ,'InvoiceController@add')->name('.edit');
     Route::match(['get','post'], 'add', 'InvoiceController@add')->name('.add');
     Route::match(['get','post'], 'delete/{id}', 'InvoiceController@delete')->name('.delete');
 });






////////// COUNTRY

    Route::group(['prefix' => 'countries', 'as' => 'countries' , 'middleware' => ['allowedmodule:countries,list'] ], function() {

        Route::get('/', 'CountryController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'CountryController@add')->name('.add');
        Route::match(['get', 'post'], 'edit/{id}', 'CountryController@add')->name('.edit');
        Route::match(['get', 'post'], 'delete/{id}', 'CountryController@delete')->name('.delete');
    }); 


////////// STATE

    Route::group(['prefix' => 'states', 'as' => 'states' , 'middleware' => ['allowedmodule:states,list'] ], function() {

        Route::get('/', 'StateController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'StateController@add')->name('.add');
        Route::match(['get', 'post'], 'edit/{id}', 'StateController@add')->name('.edit');
        Route::match(['get', 'post'], 'delete/{id}', 'StateController@delete')->name('.delete');
    }); 


///////////// CITY
    Route::group(['prefix' => 'cities', 'as' => 'cities' , 'middleware' => ['allowedmodule:cities,list'] ], function() {

        Route::get('/', 'CityController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'CityController@add')->name('.add');
        Route::match(['get', 'post'], '/edit/{id?}', 'CityController@add')->name('.edit');
        Route::match(['get', 'post'], 'delete/{id}', 'CityController@delete')->name('.delete');
        Route::match(['get','post'], 'get_state', 'CityController@get_state')->name('.get_state');
        Route::match(['get','post'], 'get_city', 'CityController@get_city')->name('.get_city');
    });













    Route::group(['prefix' => 'courses' , 'as' => 'courses', 'middleware' => ['allowedmodule:courses,list'] ],  function() {

        Route::get('/', 'CourseController@index')->name('.index');

        Route::match(['get','post'], 'get_couses', 'CourseController@get_couses')->name('.get_couses');

        Route::match(['get','post'], 'add', 'CourseController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'CourseController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'CourseController@delete')->name('.delete');
        Route::match(['get','post'], 'payment/{id}', 'CourseController@payment')->name('.payment');

        Route::match(['get','post'], 'change_course_status', 'CourseController@change_course_status')->name('.change_course_status');

    });



    Route::group(['prefix' => 'subject' , 'as' => 'subject', 'middleware' => ['allowedmodule:subject,list'] ],  function() {

        Route::get('/', 'SubjectController@index')->name('.index');

        Route::match(['get','post'], 'get_couses','SubjectController@get_courses')->name('.get_courses');

        Route::match(['get','post'], 'add', 'SubjectController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'SubjectController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'SubjectController@delete')->name('.delete');

        Route::match(['get','post'], 'change_course_status', 'SubjectController@change_course_status')->name('.change_course_status');

        Route::match(['get','post'],'getcontent/{id}', 'SubjectController@getcontent')->name('.getcontent');


        Route::match(['get','post'],'delete_content/{id}', 'SubjectController@delete_content')->name('.delete_content');
            // Route::match(['get','post'], 'get_couses','SubjectController@get_courses')->name('.get_courses');

        Route::match(['get','post'], 'addcontent/{id}', 'SubjectController@addcontent')->name('.addcontent');

    });



    ////admins
    Route::group(['prefix' => 'admins', 'as' => 'admins' , 'middleware' => ['allowedmodule:admins,list'] ], function() {

        Route::get('/', 'AdminController@index')->name('.index');
        Route::match(['get', 'post'], 'add', 'AdminController@add')->name('.add');

        Route::match(['get', 'post'], 'get_admins', 'AdminController@get_admins')->name('.get_admins');
        Route::match(['get', 'post'], 'change_admins_status', 'AdminController@change_admins_status')->name('.change_admins_status');
        Route::match(['get', 'post'], 'change_admins_approve', 'AdminController@change_admins_approve')->name('.change_admins_approve');
        Route::match(['get', 'post'], 'edit/{id}', 'AdminController@add')->name('.edit');
        Route::post('ajax_delete_image', 'AdminController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'AdminController@delete')->name('.delete');
        Route::match(['get','post'],'change_admins_role', 'AdminController@change_admins_role')->name('.change_admins_role');
        Route::match(['get','post'],'change_admin_state', 'AdminController@change_admin_state')->name('.change_admin_state');
        Route::match(['get','post'],'change_admin_superdist', 'AdminController@change_admin_superdist')->name('.change_admin_superdist');

    });







    Route::group(['prefix' => 'topics' , 'as' => 'topics', 'middleware' => ['allowedmodule:topics,list'] ],  function() {

        Route::get('/', 'TopicController@index')->name('.index');

        Route::match(['get','post'], 'add', 'TopicController@add')->name('.add');
        Route::match(['get','post'], 'get_subject', 'TopicController@get_subject')->name('.get_subject');
        Route::match(['get','post'], 'get_topics', 'TopicController@get_topics')->name('.get_topics');

        Route::match(['get','post'], 'edit/{id}', 'TopicController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'TopicController@delete')->name('.delete');

        Route::match(['get','post'], 'change_course_status', 'TopicController@change_course_status')->name('.change_course_status');

        Route::match(['get','post'],'getcontent/{topic_id}', 'TopicController@getcontent')->name('.getcontent');


        Route::match(['get','post'],'delete_content/{id}', 'TopicController@delete_content')->name('.delete_content');
            // Route::match(['get','post'], 'get_couses','TopicController@get_courses')->name('.get_courses');

        Route::match(['get','post'], 'addcontent/{id}', 'TopicController@addcontent')->name('.addcontent');

    });


    Route::group(['prefix' => 'contents' , 'as' => 'contents', 'middleware' => ['allowedmodule:contents,list'] ],  function() {

        Route::get('/', 'ContentController@index')->name('.index');

        Route::match(['get','post'], 'add', 'ContentController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'ContentController@add')->name('.edit');
        Route::match(['get','post'], 'delete/{id}', 'ContentController@delete')->name('.delete');
        Route::match(['get','post'], 'get_courses', 'ContentController@get_courses')->name('.get_courses');
        Route::match(['get','post'], 'get_subjects', 'ContentController@get_subjects')->name('.get_subjects');
        Route::match(['get','post'], 'get_topics', 'ContentController@get_topics')->name('.get_topics');

        

    });


    

    


    Route::group(['prefix' => 'exams' , 'as' => 'exams', 'middleware' => ['allowedmodule:exams,list'] ],  function() {

       Route::match(['get','post'],'/', 'ExamController@index')->name('.index');
       Route::match(['get','post'],'get_exams', 'ExamController@get_exams')->name('.get_exams');
       Route::match(['get','post'],'import/{id}', 'ExamController@import')->name('.import');
       Route::match(['get','post'],'add_question/{exam_id}', 'ExamController@add_question')->name('.add_question');
       Route::match(['get','post'],'get_exam_question', 'ExamController@get_exam_question')->name('.get_exam_question');

       Route::match(['get','post'],'edit_question{question_id}', 'ExamController@edit_question')->name('.edit_question');

       Route::match(['get','post'],'change_status', 'ExamController@change_status')->name('.change_status');
       Route::match(['get','post'],'get_course', 'ExamController@get_course')->name('.get_course');
       Route::match(['get', 'post'], 'add', 'ExamController@add')->name('.add');
       Route::match(['get', 'post'], 'edit/{id}', 'ExamController@add')->name('.edit');
       Route::post('ajax_delete_image', 'ExamController@ajax_delete_image')->name('.ajax_delete_image');
       Route::match(['get','post'],'delete/{id}', 'ExamController@delete')->name('.delete');


       Route::match(['get','post'],'results/{exam_id}', 'ExamController@results')->name('.results');
       Route::match(['get','post'],'get_result_list{exam_id}', 'ExamController@get_result_list')->name('.get_result_list');
       Route::match(['get','post'],'allocate_exam', 'ExamController@allocate_exam')->name('.allocate_exam');

   });


    Route::group(['prefix' => 'coupons' , 'as' => 'coupons', 'middleware' => ['allowedmodule:coupons,list'] ],  function() {

        Route::match(['get','post'],'/', 'CouponController@index')->name('.index');
        Route::match(['get','post'], 'add', 'CouponController@add')->name('.add');
        Route::match(['get','post'], 'get_party_user', 'CouponController@get_party_user')->name('.get_party_user');
        Route::match(['get','post'], 'edit/{id}', 'CouponController@add')->name('.edit');
        Route::match(['get','post'], 'delete/{id}', 'CouponController@delete')->name('.delete');
        Route::match(['get','post'], 'assign_coupons', 'CouponController@assign_coupons')->name('.assign_coupons');
    });

    Route::group(['prefix' => 'my_coupons' , 'as' => 'my_coupons', 'middleware' => ['allowedmodule:my_coupons,list'] ],  function() {

        Route::get('/', 'MyCouponController@index')->name('.index');
        Route::get('/coupons_history', 'MyCouponController@coupons_history')->name('.coupons_history');
        Route::get('/view_coupons', 'MyCouponController@view_coupons')->name('.view_coupons');

    });

    Route::group(['prefix' => 'return_coupon' , 'as' => 'return_coupon', 'middleware' => ['allowedmodule:return_coupon,list'] ],  function() {
        Route::match(['get','post'],'/','ReturnCouponController@index')->name('.index');
        Route::match(['get','post'],'/return','ReturnCouponController@return')->name('.return');

    });




    Route::group(['prefix' => 'categories' , 'as' => 'categories', 'middleware' => ['allowedmodule:categories,list'] ],  function() {

        Route::get('/', 'CategoryController@index')->name('.index');

        Route::match(['get','post'], 'get_category', 'CategoryController@get_category')->name('.get_category');

        Route::match(['get','post'], 'add', 'CategoryController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'CategoryController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'CategoryController@delete')->name('.delete');

        Route::match(['get','post'], 'change_category_status', 'CategoryController@change_category_status')->name('.change_category_status');

    });




    Route::group(['prefix' => 'subcategories' , 'as' => 'subcategories', 'middleware' => ['allowedmodule:subcategories,list'] ],  function() {

        Route::get('/', 'SubCategoryController@index')->name('.index');

        Route::match(['get','post'], 'get_category', 'SubCategoryController@get_category')->name('.get_category');

        Route::match(['get','post'], 'add', 'SubCategoryController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'SubCategoryController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'SubCategoryController@delete')->name('.delete');

        Route::match(['get','post'], 'change_subcategory_status', 'SubCategoryController@change_subcategory_status')->name('.change_subcategory_status');

    });




    Route::group(['prefix' => 'businesses' , 'as' => 'businesses', 'middleware' => ['allowedmodule:businesses,list'] ],  function() {

        Route::get('/', 'BusinessController@index')->name('.index');

        Route::match(['get','post'], 'get_category', 'BusinessController@get_category')->name('.get_category');

        Route::match(['get','post'], 'add', 'BusinessController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'BusinessController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'BusinessController@delete')->name('.delete');
        Route::match(['get','post'], 'show/{id}', 'BusinessController@show')->name('.show');
        Route::match(['get','post'], 'ratings', 'BusinessController@ratings')->name('.ratings');

        Route::match(['get','post'], 'change_business_status', 'BusinessController@change_business_status')->name('.change_business_status');

        Route::match(['get','post'], 'gallery', 'BusinessController@gallery')->name('.gallery');
        Route::match(['get','post'], 'img_delete/{id}', 'BusinessController@img_delete')->name('.img_delete');
        Route::match(['get','post'], 'getsubcategories', 'BusinessController@getsubcategories')->name('.getsubcategories');
        Route::match(['get','post'], 'getsubcategories1', 'BusinessController@getsubcategories1')->name('.getsubcategories1');
        Route::match(['get','post'], 'export', 'BusinessController@export')->name('.export');


    });






    Route::group(['prefix' => 'read_respond' , 'as' => 'read_respond', 'middleware' => ['allowedmodule:read_respond,list'] ],  function() {

        Route::get('/', 'ReadrespondController@index')->name('.index');


        Route::match(['get','post'], 'add', 'ReadrespondController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'ReadrespondController@add')->name('.edit');
        Route::match(['get','post'], 'add_question', 'ReadrespondController@add_question')->name('.add_question');

        Route::match(['get','post'], 'delete/{id}', 'ReadrespondController@delete')->name('.delete');
        Route::match(['get','post'], 'delete_question/{id}', 'ReadrespondController@delete_question')->name('.delete_question');

        Route::match(['get','post'], 'change_read_status', 'ReadrespondController@change_read_status')->name('.change_read_status');

    });





//////////// BANNERS

    Route::group(['prefix' => 'banners' , 'as' => 'banners', 'middleware' => ['allowedmodule:banners,list'] ],  function() {
        Route::match(['get','post'],'/', 'BannerController@index')->name('.index');
        Route::match(['get','post'], 'add', 'BannerController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'BannerController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'BannerController@delete')->name('.delete');
        Route::match(['get','post'], 'change_banner_status', 'BannerController@change_banner_status')->name('.change_banner_status');

    });
   Route::group(['prefix' => 'videos' , 'as' => 'videos', 'middleware' => ['allowedmodule:videos,list'] ],  function() {
        Route::match(['get','post'],'/', 'VideoController@index')->name('.index');
        Route::match(['get','post'], 'add', 'VideoController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'VideoController@add')->name('.edit');

        Route::match(['get','post'], 'delete/{id}', 'VideoController@delete')->name('.delete');
        Route::match(['get','post'], 'change_video_status', 'VideoController@change_video_status')->name('.change_video_status');

    });



// roles
    Route::group(['prefix' => 'roles', 'as' => 'roles' , 'middleware' => ['allowedmodule:roles,list'] ], function() {

        Route::get('/', 'RoleController@index')->name('.index');

        Route::match(['get', 'post'], 'add', 'RoleController@add')->name('.add');

        Route::match(['get', 'post'], 'get_roles', 'RoleController@get_roles')->name('.get_roles');

        Route::match(['get', 'post'], 'change_role_status', 'RoleController@change_role_status')->name('.change_role_status');
        Route::match(['get', 'post'], 'edit/{id}', 'RoleController@add')->name('.edit');

        Route::post('ajax_delete_image', 'RoleController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get','post'],'delete/{id}', 'RoleController@delete')->name('.delete');
    });



/////// USERS

    Route::group(['prefix' => 'user' , 'as' => 'user', 'middleware' => ['allowedmodule:user,list'] ],  function() {
        Route::match(['get','post'],'/', 'UserController@index')->name('.index');
        Route::match(['get','post'], 'add', 'UserController@add')->name('.add');

        Route::match(['get','post'], 'edit/{id}', 'UserController@add')->name('.edit');
        Route::match(['get','post'], 'subscriptions/{id}', 'UserController@subscriptions')->name('.subscriptions');

        Route::match(['get','post'], 'delete_subscription/{id}', 'UserController@delete_subscription')->name('.delete_subscription');
        Route::match(['get','post'], 'update_subscription', 'UserController@update_subscription')->name('.update_subscription');

        Route::match(['get','post'], 'wallet', 'UserController@wallet')->name('.wallet');
        
        Route::match(['get','post'], 'export', 'UserController@export')->name('.export');

        Route::match(['get','post'], 'delete/{id}', 'UserController@delete')->name('.delete');
        Route::match(['get','post'], 'subscription', 'UserController@subscription')->name('.subscription');
        Route::match(['get','post'], 'change_user_role', 'UserController@change_user_role')->name('.change_user_role'); 
        Route::match(['get','post'], 'transaction/{id}', 'UserController@transaction')->name('.transaction');
        Route::match(['get','post'], 'user_list/{id}', 'UserController@user_list')->name('.user_list');
        Route::match(['get','post'], 'change_phone_status', 'UserController@change_phone_status')->name('.change_phone_status');
        
    });

/////// USERS

    Route::group(['prefix' => 'reports' , 'as' => 'reports', 'middleware' => ['allowedmodule:reports,list'] ],  function() {
        Route::match(['get','post'],'/', 'ReportController@index')->name('.index');
        Route::match(['get','post'], 'add', 'ReportController@add')->name('.add');

    });


/////// superdistributor
    // $role_slug = $request->route()->getPrefix();
    // $slug = str_replace('admin/', '', $role_slug) ;

    // $roles = Roles::where('slug',$slug)->first();
    $roles = \App\Roles::where('parent_id','!=',0)->get();
    // $roles = [];

    if(!empty($roles)){
        foreach($roles as $role){
         // Route::group(['prefix' => $role->slug , 'as' => $role->slug, 'middleware' => ['allowedmodule:'.$role->slug.',list'] ],  function() {

        Route::group(['prefix' => $role->slug , 'as' => $role->slug],  function() {
            Route::match(['get','post'],'/', 'PartyUserController@index')->name('.index');
            Route::match(['get','post'], 'add', 'PartyUserController@add')->name('.add');
            Route::match(['get','post'], 'edit/{id}', 'PartyUserController@add')->name('.edit');
            Route::match(['get','post'], 'delete/{id}', 'PartyUserController@delete')->name('.delete');
            Route::match(['get','post'], 'view_coupons', 'PartyUserController@view_coupons')->name('.view_coupons');   
            Route::match(['get','post'], 'coupons_history/{id}', 'PartyUserController@coupons_history')->name('.coupons_history');   
            Route::match(['get','post'], 'export', 'PartyUserController@export')->name('.export');   
            Route::match(['get','post'], 'change_parent', 'PartyUserController@change_parent')->name('.change_parent');   
            Route::match(['get','post'], 'return_coupon', 'PartyUserController@return_coupon')->name('.return_coupon');   
        });
     }
 }









     // contacts
 Route::group(['prefix' => 'contacts' , 'as' => 'contacts', 'middleware' => ['allowedmodule:contacts,list'] ],  function() {
    Route::match(['get','post'],'/', 'ContactController@index')->name('.index');
        // Route::match(['get','post'], 'add', 'ContactController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'ContactController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'ContactController@delete')->name('.delete');
    Route::match(['get','post'], 'change_contacts_status', 'ContactController@change_contacts_status')->name('.change_contacts_status');

});





     // contacts
 Route::group(['prefix' => 'live_class' , 'as' => 'live_class', 'middleware' => ['allowedmodule:live_class,list'] ],  function() {
    Route::match(['get','post'],'/', 'LiveClassController@index')->name('.index');
    Route::match(['get','post'], 'add', 'LiveClassController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'LiveClassController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'LiveClassController@delete')->name('.delete');
    Route::match(['get','post'], 'get_subject', 'LiveClassController@get_subject')->name('.get_subject');

    Route::match(['get','post'], 'change_liveclass_status', 'LiveClassController@change_liveclass_status')->name('.change_liveclass_status');

});







     // contacts
 Route::group(['prefix' => 'telecaller' , 'as' => 'telecaller', 'middleware' => ['allowedmodule:telecaller,list'] ],  function() {
    Route::match(['get','post'],'/admins_list', 'TeleCallerController@admins_list')->name('.admins_list');
    Route::match(['get','post'], 'telecaller_remarks', 'TeleCallerController@telecaller_remarks')->name('.telecaller_remarks');
   Route::match(['get','post'], 'export', 'TeleCallerController@export')->name('.export');

});



     // chats
 Route::group(['prefix' => 'chats' , 'as' => 'chats', 'middleware' => ['allowedmodule:chats,list'] ],  function() {
    Route::match(['get','post'],'/', 'ChatController@index')->name('.index');
    Route::match(['get','post'], 'add', 'ChatController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'ChatController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'ChatController@delete')->name('.delete');
    Route::match(['get','post'], 'get_user_name', 'ChatController@get_user_name')->name('.get_user_name');
    Route::match(['get','post'], 'get_user_list' ,'ChatController@get_user_list')->name('.get_user_list');
    Route::match(['get','post'], 'get_user_chat', 'ChatController@get_user_chat')->name('.get_user_chat');
    Route::match(['get','post'], 'send_message', 'ChatController@send_message')->name('.send_message');
    Route::match(['get','post'], 'upload_file', 'ChatController@upload_file')->name('.upload_file');


});

     // chats
 Route::group(['prefix' => 'firebasechats' , 'as' => 'firebasechats', 'middleware' => ['allowedmodule:firebasechats,list'] ],  function() {
    Route::match(['get','post'],'/', 'FirebaseChatController@index')->name('.index');
    Route::match(['get','post'],'/index_old', 'FirebaseChatController@index_old')->name('.index_old');
    Route::match(['get','post'], 'add', 'FirebaseChatController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'FirebaseChatController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'FirebaseChatController@delete')->name('.delete');
    Route::match(['get','post'], 'get_user_name', 'FirebaseChatController@get_user_name')->name('.get_user_name');
    Route::match(['get','post'], 'get_user_list' ,'FirebaseChatController@get_user_list')->name('.get_user_list');
    Route::match(['get','post'], 'get_user_chat', 'FirebaseChatController@get_user_chat')->name('.get_user_chat');
    Route::match(['get','post'], 'send_message', 'FirebaseChatController@send_message')->name('.send_message');
    Route::match(['get','post'], 'upload_file', 'FirebaseChatController@upload_file')->name('.upload_file');


});


         // notifications
 Route::group(['prefix' => 'notifications' , 'as' => 'notifications', 'middleware' => ['allowedmodule:notifications,list'] ],  function() {
    Route::match(['get','post'],'/', 'NotificationController@index')->name('.index');
    Route::match(['get','post'], 'add', 'NotificationController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'NotificationController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'NotificationController@delete')->name('.delete');
    Route::match(['get','post'], 'send_users', 'NotificationController@send_users')->name('.send_users');

});

           // dgl_form
 Route::group(['prefix' => 'dgl_form' , 'as' => 'dgl_form', 'middleware' => ['allowedmodule:dgl_form,list'] ],  function() {
    Route::match(['get','post'],'/', 'DGLController@index')->name('.index');
    Route::match(['get','post'], 'add', 'DGLController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'DGLController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'DGLController@delete')->name('.delete');
    Route::match(['get','post'], 'change_faq_status', 'DGLController@change_faq_status')->name('.change_faq_status');


});


           // faqs
 Route::group(['prefix' => 'faqs' , 'as' => 'faqs', 'middleware' => ['allowedmodule:faqs,list'] ],  function() {
    Route::match(['get','post'],'/', 'FAQsController@index')->name('.index');
    Route::match(['get','post'], 'add', 'FAQsController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'FAQsController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'FAQsController@delete')->name('.delete');
    Route::match(['get','post'], 'change_faq_status', 'FAQsController@change_faq_status')->name('.change_faq_status');


});



  // news
 Route::group(['prefix' => 'news' , 'as' => 'news', 'middleware' => ['allowedmodule:news,list'] ],  function() {
    Route::match(['get','post'],'/', 'NewsController@index')->name('.index');
    Route::match(['get','post'], 'add', 'NewsController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'NewsController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'NewsController@delete')->name('.delete');
    Route::match(['get','post'], 'change_news_status', 'NewsController@change_news_status')->name('.change_news_status');


});



    // daily quotes
 Route::group(['prefix' => 'daily_quotes' , 'as' => 'daily_quotes', 'middleware' => ['allowedmodule:daily_quotes,list'] ],  function() {
    Route::match(['get','post'],'/', 'DailyQuotesController@index')->name('.index');
    Route::match(['get','post'], 'add', 'DailyQuotesController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'DailyQuotesController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'DailyQuotesController@delete')->name('.delete');
    Route::match(['get','post'], 'change_quote_status', 'DailyQuotesController@change_quote_status')->name('.change_quote_status');


});
Route::get('/generate-excel-report', [mycontroller::class, 'generateExcelReport']);
Route::get('/user-data-report', [mycontroller::class, 'UserDataReport']);
//Route::get('/coupon-report', [mycontroller::class, 'coupon']);
Route::get('/generate-excel-report-zero', [mycontroller::class, 'generateExcelReportZero']);
Route::get('/coupon-details-report', [mycontroller::class, 'generateCouponAllocationReport']);
Route::get('/new_coupon', [mycontroller::class, 'new_coupon']);
Route::get('/CouponSummery', [mycontroller::class, 'CouponSummery']);


 Route::group(['prefix' => 'subscriptions' , 'as' => 'subscriptions', 'middleware' => ['allowedmodule:subscriptions,list'] ],  function() {
    Route::match(['get','post'],'/', 'SubscriptionController@index')->name('.index');
    Route::match(['get','post'], 'add', 'SubscriptionController@add')->name('.add');
    Route::match(['get','post'], 'edit/{id}', 'SubscriptionController@add')->name('.edit');
    Route::match(['get','post'], 'delete/{id}', 'SubscriptionController@delete')->name('.delete');
    Route::match(['get','post'], 'change_subscription_status', 'SubscriptionController@change_subscription_status')->name('.change_subscription_status');
});






});


