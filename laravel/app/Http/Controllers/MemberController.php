<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Wechat;

use Illuminate\Support\Facades\Response;

class MemberController extends Controller
{
    private  $user;
    public function __construct()
    {
        $this->user = Wechat::user();
    }

    public function getUser()
    {
        return Response::json($this->user->lists());
    }
}
