<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;

class UserMessage extends Model
{
    const UPDATED_AT =  null;
    protected  $fillable = ['name','type','message_id','content','location_x','location_y'];
    protected $fields =  [
        'text'=>1,
        'image'=>2,
        'voice'=>3,
        'video'=>4,
        'shortvideo'=>5,
        'location'=>6,
        'link'=>7,
    ];
    public function addUserMessage($data)
    {
        $dbData['name'] = $data['FromUserName'];
        $dbData['type'] = $this->fields[$data['MsgType']];
        $dbData['message_id'] = $data['MsgId'];
        $dbData['created_at'] = date('Y-m-d H:i:s',$data['CreateTime']);
        isset($data['content']) && $dbData['content'] = $data['Content'];
        isset($data['Location_X']) && $dbData['location_x'] = $data['Location_X'];
        isset($data['Location_Y']) && $dbData['location_y'] = $data['Location_Y'];
        try{
            $result = $this->create($dbData);
            return $result;
        }catch (Exception $e){
            return ['state'=>false,'message'=>'保存失败'];
        }
    }

}
