<?php

namespace App\Http\Controllers;

use App\Pymt;
use App\PymtCounter;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Libraries\BaiduGeocoding;
use Illuminate\Support\Facades\DB;

class GeohashController extends Controller
{
    public function index(Request $request)
    {
        $num = (int) $request->input('num',500);
        $max_pymt_id = PymtCounter::pluck('max_mt_id')->toArray();
        $max_pymt_id = count($max_pymt_id) ? $max_pymt_id[0]:0;
        if($max_pymt_id){
            $py_mt = Pymt::where('id',$max_pymt_id)->pluck('geohash')->toArray();
            if(count($py_mt)){
                $flag = $py_mt[0];
            }
        }
        $start = isset($flag) ? $max_pymt_id:0;
        $data = Pymt::where('id','>',$start)->where('id','<=',$num+$start)->get(['id','name','city'])->toArray();
        $baiduApi = new BaiduGeocoding();
        foreach($data as $k=>$v){
            list($location_x,$location_y) = $baiduApi->getLatLng($v['name'],$v['city']);
            $this->handleDb($location_x,$location_y,$v['id']);
            DB::table('pymt_counters')->increment('max_mt_id',$num);
        }
    }

    private function handleDb($location_x,$location_y,$id)
    {
        $resource = Pymt::find($id);
        $geohash = geohash_encode($location_y,$location_x);
        $resource->location_x = $location_x;
        $resource->location_y = $location_y;
        $resource->geohash = $geohash;
        return $resource->save();
    }
}
