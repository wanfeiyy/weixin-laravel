<?php
namespace App\Libraries;
use Illuminate\Support\Facades\Config;
use App\Libraries\ApiCurl;
class BaiduGeocoding{
    private $geocodingUrl = 'http://api.map.baidu.com/geocoder/v2/';
    public function getLatLng($address,$city='')
    {
        $ak =   Config::get('local.mapAk');
        $url = $this->geocodingUrl.'?ak='.$ak.'&output=json&address='.$address.'&city='.$city;
        return ApiCurl::curl_get($url);
    }
}