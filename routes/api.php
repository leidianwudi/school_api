<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//跨域请求支持
Route::options('*', 'Controller@crossDo');

//外围扩展
Route::group(['prefix' => 'external', 'namespace' => 'External'], function()
{
    Route::get('getValidateCodeImg',    'ValidateCodeController@getValidateCodeImg'); //请求验证码图片
    Route::post('uploadImg',            'UploadImgController@uploadImg');             //上传图片
    Route::post('uploadFileToCache',    'UploadFileController@uploadFileToCache');    //上传文件到临时目录
});

//自动代码生成器，自动生成数据库表对应entity代码和model代码
Route::post('createAuto', 'AutoController@createAuto');
Route::get('createAuto', 'AutoController@createAuto');


//学校
Route::group(['prefix' => 'school', 'namespace' => 'School'], function()
{
    Route::post('getSchool',        'SchoolController@getSchool');      //查询学校
    Route::post('getProfession',    'SchoolController@getProfession');  //查询专业
    Route::post('getSubject1',      'SchoolController@getSubject1');    //查询首选科目
    Route::post('getSubject2',      'SchoolController@getSubject2');    //查询再选科目
});

