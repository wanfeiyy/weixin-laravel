<?php
namespace App\Http\Controllers\Sdk\message\event\CLICK;
use App\Http\Controllers\Sdk\wlight\library\core\request\Response;
use wlight\runtime\ApiException;
class LbsClick extends  Response{
    public function verify(){
        return true;
    }
    public function invoke()
    {
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
            return $this->makeText('xxxx');
        }

        // var_dump($_REQUEST);
        //return $this->makeText('hello'.$this->map['Content']);
        //  return $this->makeNews(array('Title'=>'1', 'Description'=>'xxxx', 'PicUrl'=>'', 'Url'=>''));
    }

    public function tag(){
        return 'LBS';
    }
}