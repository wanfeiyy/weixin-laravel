<?php
namespace App\Libraries;
class ApiCurl
{

    /**
     * curl的post方法请求
     * @param string $url      要访问的地址
     * @array array $postPram     请求的参数 以键值对的形式的数组['username'=>'test','password'=>'1111']
     * @return mixed
     */
    public static function curl_post($url,$postPram)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postPram));
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * curl的get方法请求
     * @param string $url      要访问的地址。如果有参数就跟在url后面
     * @return mixed
     */
    public static function curl_get($url,$timeout = 0,$http_code = 0,$header=[])
    {

        $curl = curl_init();        //初始化一个 cURL 对象
        // 添加apikey到header
        curl_setopt($curl, CURLOPT_HTTPHEADER , $header);
        $timeout && curl_setopt($curl, CURLOPT_TIMEOUT,$timeout);      //设置超时时间  秒
        curl_setopt($curl, CURLOPT_URL, $url);      //设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);          //设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
//        curl_setopt($curl,CURLOPT_FORBID_REUSE,1);
        $data = curl_exec($curl);       //运行cURL，请求网页
        $curl_error = curl_errno($curl);
        if ($http_code) {
            $http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
            $tmp['http_code'] = $http_code;
            $tmp['data'] = $data;
            $data = $tmp;
        }
        curl_close($curl);      //关闭URL请求
        if($curl_error >0 ){
            return false;
        }
        return $data;
    }

}