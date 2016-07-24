<?php
use App\UserMessage;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
});
Route::post('menu/delete','MenuController@delete');
Route::resource('menu','MenuController');
Route::controller('music163','Music163Controller');
Route::any('/wechat', 'WechatController@serve');
