<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Libraries\ApiCurl;
use Sunra\PhpSimple\HtmlDomParser;

class Music163Controller extends Controller
{
   private $music_163_top = 'http://music.163.com/discover/toplist?id=19723756';
   private  $music_163_new = 'http://music.163.com/discover/toplist?id=3779629';
   private $music_163_hot = 'http://music.163.com/discover/toplist?id=3778678';
   private $link_url = ' http://music.163.com/outchain/player?type=2&id=';
    private $apiCurl;
    public function __construct(ApiCurl $apiCurl){
        $this->apiCurl = $apiCurl;
    }
    public function getMusic(Request $request)
    {
        $input = 'music_163_'.$request->input('key','hot');
        $url = $this->$input;
        $output = $this->apiCurl->curl_get($url);
        $dom = HtmlDomParser::str_get_html($output);
        $songArr = [];
        foreach($dom->find('#song-list-pre-cache .f-hide a') as $k=>$v){
            preg_match('/.*?id=([0-9]+)/',$v->href,$res);
            $songArr[]  = ['url'=>$this->link_url.$res[1].'&auto=1&height=166','name'=>strip_tags ((string) $v)];
            if(count($songArr) >=10){
                break;
            }
        }
        return view('music163.index',['songArr'=>$songArr]);
    }
}
