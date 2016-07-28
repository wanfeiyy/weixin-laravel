<?php

namespace App\Http\Controllers;

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

}
