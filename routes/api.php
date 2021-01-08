<?php

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

// register (name,cellphone,city,region,password)
Route::post('register','UserController@register');
// Active User (code&mobile)
Route::get('check_code/{code}/{mobile}','UserController@checkCode');
// check exist user
Route::get('exist_user/{mobile}','UserController@checkExist');
/* Admin Uri */
Route::group(['prefix' => 'admin'], function () {

    Route::post('/lawyer/change_status','UserController@changeStatus');
    Route::get('/lawyer/all/{status?}','LawyerController@getAll');

    Route::get('/confirmation/all/{status?}','ConfirmationController@getConfirmations');
    Route::post('/confirmation/update','ConfirmationController@update');

    Route::post('/service_price/create','ServicePriceController@create');
    Route::get('/service_price/remove/{id}','ServiceController@remove');

    // Topic Route
    // Create Topic (top_id,title,slug,body)
    Route::post('/topic/store','TopicController@store');
    // Update Topic (id,top_id,title,slug,body)
    Route::post('/topic/update','TopicController@edit');
    // Destroy Topic (id)
    Route::get('/topic/destroy','TopicController@destroy');

    // Tags Route
    // Create Tags (title)
    Route::post('/tags/store','TagController@store');
    // Update Tags (id,title)
    Route::post('/tags/update','TagController@edit');
    // Destroy Tags (id)
    Route::get('/tags/destroy','TagController@destroy');

    // Speciality Route
    // Create Speciality (title)
    Route::post('/speciality/store','SpecialityController@store');
    // Update Speciality (id,title)
    Route::post('/speciality/update','SpecialityController@edit');
    // Destroy Speciality (id)
    Route::get('/speciality/destroy','SpecialityController@destroy');

    // Blog Route
    // remove blog(blog_id)
    Route::get('/blog/remove/{blog_id}','BlogController@detroy');
    // change blog status(blog_id,status)
    Route::get('/blog/change_status/{blog_id}/{status}','BlogController@changeStatus');
    // remove blog (blog_id)
    Route::get('/blog/destroy/{blog_id}','BlogController@destroy');
    // Filter All Blog(status)
    Route::get('/blog/all_blog/{status}','BlogController@allBlog');

});

/* Lawyer Uri */
Route::group(['prefix' => 'lawyer'], function () {

    Route::post('/confirmation/create','ConfirmationController@create');

    Route::post('/answer/store','AnswerController@store');

    // create Blog(title,slug,body,image*)
    Route::post('/blog/create','BlogController@create');
    // update Blog(title,slug,body,image*)
    Route::post('/blog/update','BlogController@update');

});

/* User Uri */
// @TODO Check User Role
Route::get('/lawyers','LawyerController@getLawyer');

// Filter Lawyers (*city,*role_id)
Route::get('/lawyers/filter/{city?}/{role_id?}','LawyerController@filterLawyers');
// Complete Filter Lawyer(role_id*,city*,speciality*,service_types*,gender*)
Route::post('/lawyers/filter','LawyerController@lawyerFilter');

// question route
Route::post('/question/store','QuestionController@store');
Route::get('/question/get/{start?}','QuestionController@getQuestions');
Route::get('/question/{id}','QuestionController@show');

// get lawyer info (slug)
Route::get('/lawyer/{slug}','LawyerController@show');

// Top Lawyers
Route::get('/top_lawyers','LawyerController@topLawyers');

// service_price info (role_id,type)type = 1 online 2 phone
Route::get('/service_price/show/{role_id}/{type}','ServicePriceController@show');

// Topic Uri
Route::get('/topic/all','TopicController@topics');

// Tags Uri
Route::get('/tags/all','TagController@tags');

// Blog Uri(slug)
Route::get('/blog/show/{slug}','BlogController@show');
// Lawyer Blogs(user_id)
Route::get('/blog/all/{user_id}','BlogController@all');
// Return Blog with (exp_tag) without #
Route::get('/blog/tags/{tag}','BlogController@tagBlogs');
// Return Top View Blogs
Route::get('/blog/top_view','BlogController@topView');
// Return User Max Blog

// Return 10 Latest Blog
Route::get('/blog/latest','BlogController@latestBlog');
// get All Route
Route::get('/role/all','RoleController@all');

// Update Offline Or Online(user_id,status)
Route::post('/is_online','UserController@updatePresence');

// Kiri Route
Route::get('/update_slug_lawyers','TestController@updateSlug');
Route::get('/set_speciality','TestController@setSpeciality');
Route::get('/set_tags_blog','TestController@setTagsBlog');
