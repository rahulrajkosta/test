<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


///////////////////////Seller///////////////////////////
Route::match(['get','post'],'locked_cron', 'Api\SellerController@locked_cron');
Route::match(['get','post'],'decode_token', 'Api\SellerController@decode_token');
Route::match(['get','post'],'fetch_device_ids', 'Api\SellerController@fetch_device_ids');


Route::match(['get','post'],'update_mobile_status', 'Api\SellerController@update_mobile_status');
Route::match(['get','post'],'upload_screenshot', 'Api\SellerController@upload_screenshot');
Route::match(['get','post'],'check_phone_status', 'Api\SellerController@check_phone_status');




Route::match(['get','post'],'login', 'Api\SellerController@login');
Route::match(['get','post'],'banners', 'Api\SellerController@banners');
Route::match(['get','post'],'check_version', 'Api\SellerController@check_version');
Route::match(['get','post'],'user_list_new', 'Api\SellerController@user_list_new');
Route::match(['get','post'],'getseller_coupons_new', 'Api\SellerController@getseller_coupons_new');
Route::match(['get','post'],'user_details_new', 'Api\SellerController@user_details_new');
Route::match(['get','post'],'change_password', 'Api\SellerController@change_password');
Route::match(['get','post'],'seller_profile', 'Api\SellerController@seller_profile');
Route::match(['get','post'],'unlock_request', 'Api\SellerController@unlock_request');
Route::match(['get','post'],'update_location', 'Api\SellerController@update_location');
Route::match(['get','post'],'get_states', 'Api\SellerController@get_states');
Route::match(['get','post'],'get_city', 'Api\SellerController@get_city');
Route::match(['get','post'],'qr_code', 'Api\SellerController@qr_code');
Route::match(['get','post'],'privacy_policy', 'Api\SellerController@privacy_policy');
Route::match(['get','post'],'request_location', 'Api\SellerController@request_location');
Route::match(['get','post'],'user_emi_listing', 'Api\SellerController@user_emi_listing');
Route::match(['get','post'],'emi_status_update', 'Api\SellerController@emi_status_update');
Route::match(['get','post'],'check_seller_status', 'Api\SellerController@check_seller_status');
Route::match(['get','post'],'logout', 'Api\SellerController@logout');
Route::match(['get','post'],'ferch_sim_card_of_user', 'Api\SellerController@ferch_sim_card_of_user');
Route::match(['get','post'],'send_emi_alert', 'Api\SellerController@send_emi_alert');
Route::match(['get','post'],'coupon_viewed_status', 'Api\SellerController@coupon_viewed_status');
Route::match(['get','post'],'privacy_policy', 'Api\SellerController@privacy_policy');
Route::match(['get','post'],'terms_and_condition', 'Api\SellerController@terms_and_condition');
Route::match(['get','post'],'update_user_profile', 'Api\SellerController@update_user_profile');
Route::match(['get','post'],'update_user_sim_card', 'Api\SellerController@update_user_sim_card');
Route::match(['get','post'],'seller_coupon_transaction', 'Api\SellerController@seller_coupon_transaction');
Route::match(['get','post'],'video_playlists', 'Api\SellerController@video_playlists');
Route::match(['get','post'],'finances', 'Api\SellerController@finances');
Route::match(['get','post'],'notification_list', 'Api\SellerController@notification_list');



Route::match(['get','post'],'get_customer_data', 'Api\SellerController@get_customer_data');

Route::match(['get','post'],'update_status', 'Api\SellerController@update_status');







///////////////////////User///////////////////////////
Route::match(['get','post'],'user_register', 'Api\UserController@user_register');
Route::match(['get','post'],'user_login', 'Api\UserController@user_login');
Route::match(['get','post'],'send_sms_to_fetch', 'Api\UserController@send_sms_to_fetch');
Route::match(['get','post'],'run_cronjob', 'Api\UserController@run_cronjob');



Route::match(['get','post'],'add_customer', 'Api\UserController@add_customer');





Route::match(['get','post'],'enable_apps', 'Api\SellerController@enable_apps');




