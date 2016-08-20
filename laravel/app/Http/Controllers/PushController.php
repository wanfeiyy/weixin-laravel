<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Wechat;
use App\Member;
use Illuminate\Support\Facades\Response;

class PushController extends Controller
{
    public function index(Member $member,Request $request)
    {
        $mediaId = $request->input('media_id','');
        if (!$mediaId) {
            return Response::json(['state' => false, 'error' => ['error_code' => 3001, 'error_message' => '参数有误']]);
        }
        $openIds = $member::lists('open_id')->toArray();
        $broadcats = Wechat::broadcast();
        $result = $broadcats->sendNews($mediaId,$openIds);
        return Response::json($request);
    }
}
