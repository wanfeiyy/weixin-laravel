<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Requests;
use App\UserMessage;

class WechatController extends Controller
{
    private $userMessage;
    public function __construct(UserMessage $userMessage)
    {
        $this->userMessage = $userMessage;
    }
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
            switch($message->MsgType){
                case 'text':
                    return $this->handleTextMessage($message);
                    break;
                case 'event':
                    return $this->handleEventMessage($message);
                    break;
                case 'location':
                    return 'ssss';
                    break;
                default:
                    return '无法识别';
                    break;
            }
        });
        Log::info('return response.');
        return $wechat->server->serve();
    }


    private function handleLocationMessage($data)
    {
        return \GuzzleHttp\json_decode($data,true);
    }

    private function handleTextMessage($data)
    {
        $message = \GuzzleHttp\json_decode($data,true);
        $result = $this->userMessage->addUserMessage($message);
        if(isset($result['state']) && !$result['state']){
            return '暂时无法识别该内容';
        }
        return "欢迎关注 Feis！";
    }


    private function handleEventMessage($data)
    {
        switch ($data->Event) {
            case 'subscribe':
                return '欢迎关注 Feis！';
                break;
            case 'location':
                $message = \GuzzleHttp\json_decode($data,true);
                return $message;
                break;
            default:
                return '无法识别';
                break;
        }

    }

}
