<?php
use App\UserMessage;
use Sunra\PhpSimple\HtmlDomParser;
use App\Pymt;
use App\Libraries\BaiduGeocoding;
use EasyWeChat\Message\News;
use App\Test;
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
Route::controller('pymt','PymtController');
Route::resource('geohash','GeohashController');
Route::resource('push','PushController');
Route::resource('material','MaterialController');
