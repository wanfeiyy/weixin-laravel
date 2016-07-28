<?php
use App\UserMessage;
use Sunra\PhpSimple\HtmlDomParser;
use App\Pymt;
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
    $url = 'http://cd.meituan.com/category/xiuxianyule/all/page2?mtt=1.index%2Fdefault%2Fpoi.0.0.ir6gpfj2';
    $reg = '/http:\/\/([a-z]+)\..*\/category\/([a-z]+)\//';
    preg_match($reg,$url,$re);
    dd($re);
});
Route::post('menu/delete','MenuController@delete');
Route::resource('menu','MenuController');
Route::controller('music163','Music163Controller');
Route::any('/wechat', 'WechatController@serve');
Route::controller('pymt','PymtController');
