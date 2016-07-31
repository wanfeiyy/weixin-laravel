<?php

namespace App;

use App\Libraries\BaiduGeocoding;
use Illuminate\Database\Eloquent\Model;

class Pymt extends Model
{
    protected $fillable = ['name','pic','comment','url','type','city','location_x','location_y'];

    public function addMt($data)
    {
        $dbData['name'] = $data['saleName'];
        $dbData['url'] = $data['saleUrl'];
        $dbData['pic'] = $data['saleSrc'];
        $dbData['comment'] = $data['saleComment'];
        $dbData['type'] = $data['saleType'];
        $dbData['city'] = $data['saleCity'];
        list($dbData['location_x'],$dbData['location_y']) = $this->handleGeocoding($dbData['name'],$dbData['city']);
        return $this->create($dbData);
    }

    private function handleGeocoding($name,$city)
    {
        $baudiApi = new BaiduGeocoding();
        $result = json_decode($baudiApi->getLatLng($name,$city),true);
        if($result['status'] == 0){
            $location = $result['result']['location'];
            return [$location['lng'],$location['lat']];
        }else{
            return [0,0];
        }
    }
}
