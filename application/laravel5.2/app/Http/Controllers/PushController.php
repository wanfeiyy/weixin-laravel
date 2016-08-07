<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Wechat;
class PushController extends Controller
{
    public function index()
    {
        $broadcats = Wechat::broadcast();
        $broadcats->sendText('测试一下');
    }
}
