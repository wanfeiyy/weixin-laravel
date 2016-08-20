<?php
namespace wlight\msg;
use wlight\core\Response;
use wlight\runtime\ApiException;
class HelloText extends Response{
    public function verify(){
        return true;
    }
    public function invoke(){
        try {
            $accessTokenClass = $this->import('basic', 'AccessToken');
            $accessToken = $accessTokenClass->get(true);
            file_put_contents('log.log',$accessToken.PHP_EOL,FILE_APPEND);
            return $this->makeText($accessToken);
//            $ipListClass=$this->import('basic','IpList');
//            $ipList=$ipListClass->get();
//            return $this->makeText(json_encode($ipList));
//             $accountClass=$this->import('customservice','Account');
//             $result=$accountClass->add('gth123','feis','gth123456.');
 //             return $this->makeText($result);


        } catch (ApiException $e) {
            $e->log();
            return $this->makeText('无法获取AccessToken');
        }

       // var_dump($_REQUEST);
        //return $this->makeText('hello'.$this->map['Content']);
      //  return $this->makeNews(array('Title'=>'1', 'Description'=>'xxxx', 'PicUrl'=>'', 'Url'=>''));
    }

    public function tag(){
        return '测试';
    }
    public function cache() {
        return true;
    }


}
