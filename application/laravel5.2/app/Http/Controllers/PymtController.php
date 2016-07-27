<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\PyMt;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;

class PymtController extends Controller
{
    public function getPy()
    {
        $start = microtime(true);
        $i =1;
        while($i<10){
            $data = file_get_contents('http://cd.meituan.com/category/meishi/all/hot/page'.$i);
            $this->dispatch(new PyMt($data));
            $i++;
        }
        $end = microtime(true);
        echo '耗时'.$end-$start;
    }

}
