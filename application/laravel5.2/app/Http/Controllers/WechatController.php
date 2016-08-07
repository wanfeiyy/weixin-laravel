<?php

namespace App\Http\Controllers;

use App\Pymt;
use Illuminate\Http\Request;
use Log;
use App\Http\Requests;
use App\UserMessage;
use EasyWeChat\Message\News;

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
                    return $this->handleLocationMessage($message);
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
        $message =  \GuzzleHttp\json_decode($data,true);
        $result = $this->userMessage->addUserMessage($message);
        if(isset($result['state']) && !$result['state']){
            return '暂时无法识别该内容';
        }
        try{
            $lat = isset($message['Location_X']) ? $message['Location_X']:'' ;
            $lng = isset($message['Location_Y']) ? $message['Location_Y']:'';
            $geohash = substr(geohash_encode($lat,$lng),0,4);
            $returnData = Pymt::where('geohash','like',$geohash.'%')->take(5)->get(['name','url','pic','location_x','location_y'])->toArray();
            $list = [];
            if(count($returnData)){
                foreach ($returnData as $k=>$v){
                    $name = 'news'.$k;
                    $$name = new News([
                        'title' => $v['name'],
                        'description'=>'',
                        'url'=>$v['url'],
                        'image'=>$v['pic'],
                    ]);
                    $list[] = $$name;
                }
                return $list;
            }else{
                return '该位置暂无信息！';
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }

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
