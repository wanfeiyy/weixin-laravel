<?php
namespace wlight\msg;
use wlight\core\Response;
class HelloNews extends Response{
    public function verify(){
        return true;
    }
    public function invoke(){
       return $this->makeNews(array('Title'=>'1', 'Description'=>'xxxx', 'PicUrl'=>$this->map['MediaId'], 'Url'=>''));
    }

    public function tag(){
        return '测试Pic';
    }
    public function cache() {
        return true;
    }


}
