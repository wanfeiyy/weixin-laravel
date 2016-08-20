<?php

namespace App\Http\Controllers;

use App\Libraries\BaiduGeocoding;
use Illuminate\Http\Request;
use App\Jobs\PyMt;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Log;

class PymtController extends Controller
{
    public function getPy(Request $request)
    {
        $start = microtime(true);
        $city = $request->input('city');
        $type = $request->input('type');
        $i =1;
        while($i<10){
            $url = 'http://'.$city.'.meituan.com/category/'.$type.'/all/hot/page'.$i;
            $this->dispatch(new PyMt($url));
            $i++;
        }
        $end = microtime(true);
        echo '耗时'.$end-$start;
    }


    public function getGeocoding()
    {
        $baiduApi = new BaiduGeocoding();
        $result = json_decode($baiduApi->getLatLng('四海一家（人民南路二段店）','成都'),true);
        if($result['status'] == 0){
            $location = $result['result']['location'];
            return [$location['lng'],$result['lat']];
        }else{
            return [0,0];
        }


    }

}
