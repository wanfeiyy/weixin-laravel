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
        $broadcats->sendNews('BrKv7ybgBcD5mNLACFhXNjxdU3uc8dJjhWA69aVozkw',['obkNVs7CHVUnIRM983XHFdysZ7rU','obkNVs33T9wuAUqzuMuAB202C55w']);
    }
}
